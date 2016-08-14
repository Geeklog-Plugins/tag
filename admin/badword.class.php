<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/badword.class.php                           |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2012 mystral-kk - geeklog AT mystral-kk DOT net        |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// | Copyright (C) 2002 by the following authors:                              |
// | Tom Willett                 -    twillett@users.sourceforge.net           |
// | Blaine Lang                 -    langmail@sympatico.ca                    |
// | The Universal Plugin is based on prior work by:                           |
// | Tony Bibbs                  -    tony@tonybibbs.com                       |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';

global $_CONF, $_PLUGINS;

if (!in_array('tag', $_PLUGINS)) {
	COM_output(COM_refresh($_CONF['site_url'] . '/index.php'));
	exit;
}

TAG_checkAdmin();

/**
* Main 
*/
class TagBadword
{
	function TagBadword()
	{
	}

	function add()
	{
	}

	function edit()
	{
	}

	function delete()
	{
	}

	function view()
	{
		global $_CONF, $_TABLES;

		$body = '';
		$T = new Template($_CONF['path'] . 'plugins/tag/templates');
		$T->set_file('badword', 'admin_badword.thtml');
		$T->set_var('xhtml', XHTML);
		$T->set_var(
			'this_script',
			COM_buildURL($_CONF['site_admin_url'] . '/plugins/tag/index.php')
		);
		$T->set_var('lang_desc_admin_badword', TAG_str('desc_admin_badword'));
		$T->set_var('lang_add', TAG_str('add'));
		$T->set_var('lang_lbl_tag', TAG_str('lbl_tag'));
		$T->set_var('lang_delete_checked', TAG_str('delete_checked'));

		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);

		if (DB_error()) {
			return $retval . '<p>' . TAG_str('db_error') . '</p>';
		} else if (DB_numRows($result) == 0) {
			$T->set_var('msg', '<p>' . TAG_str('no_badword') . '</p>');
		} else {
			$sw = 1;

			while (($A = DB_fetchArray($result)) !== false) {
				$word = TAG_escape($A['badword']);
				$body .= '<tr><td>'
					  .  '<input id="' . $word . '" name="words[]" type="checkbox" '
					  .  'value="' . $word . '"><label for="' . $word . '">'
					  .  $word . '</label></td></tr>' . LB;
				$sw = ($sw == 1) ? 2 : 1;
			}
		}

		$T->set_var('body', $body);
		$T->parse('output', 'badword');
		$retval = $T->finish($T->get_var('output'));

		return $retval;
	}

	function doAdd()
	{
		global $_TABLES;

		/**
		* Adds a bad word into DB
		*/
		$word = TAG_post('word');
		$word = trim($word);
		
		if (empty($word)
		 OR (DB_count($_TABLES['tag_badwords'], 'badword', addslashes($word)) > 0)) {
			return TAG_str('add_fail');
		}

		$sql = "INSERT INTO {$_TABLES['tag_badwords']} (badword) "
			 . "VALUES ('" . addslashes($word) . "')";
		$result = DB_query($sql);

		// Deletes the bad word from list and map if it already exists
		$tag_id = TAG_getTagId($word);

		if ($tag_id !== false) {
			$sql = "DELETE FROM {$_TABLES['tag_list']} "
				 . "WHERE (tag_id = '" . addslashes($tag_id) . "')";
			DB_query($sql);

			$sql = "DELETE FROM {$_TABLES['tag_map']} "
				 . "WHERE (tag_id = '" . addslashes($tag_id) . "')";
			DB_query($sql);
		}

		return DB_error() ? TAG_str('add_fail') : TAG_str('add_success');
	}

	function doEdit()
	{
	}

	function doDelete()
	{
		global $_TABLES, $LANG_TAG;

		$submit = TAG_post('submit');

		if ($submit == $LANG_TAG['add']) {
			$this->doAdd();
			return;
		}

		$words = TAG_post('words');

		if (!is_array($words) OR (count($words) === 0)) {
			return '';
		}

		/**
		* Deletes a bad word from DB
		*/
		$words4db = array_map('addslashes', $words);
		$words4db = "('" . implode("','", $words4db) . "')";

		$sql = "DELETE FROM {$_TABLES['tag_badwords']} "
			 . "WHERE (badword IN " . $words4db . ")";
		$result = DB_query($sql);

		/**
		* Rescans articles and staticpages for tags
		*/
		DB_query("DELETE FROM {$_TABLES['tag_list']} ");
		DB_query("DELETE FROM {$_TABLES['tag_map']} ");
		TAG_scanAll();

		return DB_error() ? TAG_str('delete_fail') : TAG_str('delete_success');
	}
}
