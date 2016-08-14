<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/stats.class.php                             |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008-2011 mystral-kk - geeklog AT mystral-kk DOT net        |
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

if (!in_array('tag', $_PLUGINS)) {
	COM_output(COM_refresh($_CONF['site_url'] . '/index.php'));
	exit;
}

TAG_checkAdmin();

/**
* Main 
*/
class TagStats
{
	function TagStats()
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

		$retval = '';

		$sql = "SELECT L.tag_id, L.tag, COUNT(m.tag_id) AS cnt, L.hits "
			 . "FROM {$_TABLES['tag_list']} AS L "
			 . "LEFT JOIN {$_TABLES['tag_map']} AS m "
			 . "ON L.tag_id = m.tag_id "
			 . "GROUP BY m.tag_id "
			 . "ORDER BY cnt DESC, tag";
		$result = DB_query($sql);

		if (DB_error()) {
			return $retval . '<p>' . TAG_str('db_error') . '</p>';
		} else if (DB_numRows($result) == 0) {
			return $retval . '<p>' . TAG_str('no_tag') . '</p>';
		}

		$T = new Template($_CONF['path'] . 'plugins/tag/templates');
		$T->set_file('stats', 'admin_stats.thtml');
		$T->set_var('xhtml', XHTML);
		$T->set_var(
			'this_script',
			COM_buildURL($_CONF['site_admin_url'] . '/plugins/tag/index.php')
		);
		$T->set_var('lang_desc_admin_stats', TAG_str('desc_admin_stats'));
		$T->set_var('lang_lbl_tag', TAG_str('lbl_tag'));
		$T->set_var('lang_lbl_count', TAG_str('lbl_count'));
		$T->set_var('lang_lbl_hit_count', TAG_str('lbl_hit_count'));
		$T->set_var('lang_delete_checked', TAG_str('delete_checked'));
		$T->set_var('lang_ban_checked', TAG_str('ban_checked'));

		$sw = 1;
		$body = '';

		while (($A = DB_fetchArray($result)) !== false) {
			$tag_id = $A['tag_id'];
			$body .= '<tr class="pluginRow' . $sw . '">'
				  .  '<td><input id="tag' . TAG_escape($tag_id) . '" name="tag_ids[]" '
				  .  'type="checkbox" value="' . TAG_escape($A['tag_id'])
				  .  '"' . XHTML . '><label for="tag' . TAG_escape($tag_id)
				  .  '">' . TAG_escape($A['tag']) . '</label></td>'
				  .  '<td style="text-align: right;">' .  TAG_escape($A['cnt'])
				  .  '</td><td style="text-align: right;">'
				  .  TAG_escape($A['hits']) .  '</td></tr>' . LB;
			$sw = ($sw == 1) ? 2 : 1;
		}

		$T->set_var('body', $body);
		$T->parse('output', 'stats');
		$retval = $T->finish($T->get_var('output'));

		return $retval;
	}

	function doAdd()
	{
	}

	function doEdit()
	{
	}

	function doDelete()
	{
		global $_TABLES, $LANG_TAG;

		// Retrieves request vars
		$tag_ids = TAG_post('tag_ids', true, true);

		if (!is_array($tag_ids) OR (count($tag_ids) === 0)) {
			return '';
		}

		$cmd = TAG_post('submit');
		
		if ($cmd != $LANG_TAG['delete_checked'] 
		 AND $cmd != $LANG_TAG['ban_checked']) {
			return '';
		}

		$tag_ids = array_map('addslashes', $tag_ids);
		$tag_ids = "'" . implode("','", $tag_ids) . "'";

		// Registers banned words into DB

		if ($cmd == $LANG_TAG['ban_checked']) {
			$sql = "INSERT INTO {$_TABLES['tag_badwords']} "
				 . "SELECT tag FROM {$_TABLES['tag_list']} "
				 . "WHERE (tag_id IN ({$tag_ids}))";
			$result = DB_query($sql);
		}

		// Deletes tags from registered tag list
		$sql = "DELETE FROM {$_TABLES['tag_list']} "
			 . "WHERE (tag_id IN ({$tag_ids}))";
		$result = DB_query($sql);

		$sql = "DELETE FROM {$_TABLES['tag_map']} "
			 . "WHERE (tag_id IN ({$tag_ids}))";
		$result = DB_query($sql);

		return DB_error() ? TAG_str('delete_fail') : TAG_str('delete_success');
	}
}
