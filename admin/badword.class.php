<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/badword.class.php                           |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2008 mystral-kk - geeklog AT mystral-kk DOT net             |
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
//
// $Id$

require_once '../../../lib-common.php';

// Only let admin users access this page
if (!SEC_hasRights('tag.admin')) {
    // Someone is trying to illegally access this page
    COM_errorLog("Someone has tried to illegally access the tag Admin page.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: {$_SERVER['REMOTE_ADDR']}", 1);
    $display  = COM_siteHeader();
    $display .= COM_startBlock($LANG_TAG['access_denied']);
    $display .= $LANG_TAG['access_denied_msg'];
    $display .= COM_endBlock();
    $display .= COM_siteFooter(true);
    echo $display;
    exit;
}
 
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
		
		$retval = '<form method="post" action="' . $_CONF['site_admin_url']
				. '/plugins/tag/index.php' . '">' . LB
				. '<input name="cmd" type="hidden" value="badword">' . LB
				. '<input name="action" type="hidden" value="doAdd">' . LB
				. '<p>' . TAG_str('add') . ':&nbsp;<input name="word" type="text">'
				. '<input name="submit" type="submit" value="' . TAG_str('submit')
				. '"></p>'
				. '</form>' . LB;
		
		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);
		if (DB_error()) {
			return $retval . '<p>' . TAG_str('db_error') . '</p>';
		} else if (DB_numRows($result) == 0) {
			return $retval . '<p>' . TAG_str('no_badword') . '</p>';
		}
		
		$retval .= '<form method="post" action="' . $_CONF['site_admin_url']
				.  '/plugins/tag/index.php' . '">' . LB
				.  '<input name="cmd" type="hidden" value="badword">' . LB
				.  '<input name="action" type="hidden" value="doDelete">' . LB;

		$sw = 1;
		$retval .= '<table class="plugin">' . LB
				.  '<tr><th>' . TAG_str('check') . '</th><th>' . TAG_str('badword')
				.  '</th></tr>' . LB;
		
		while (($A = DB_fetchArray($result)) !== false) {
			$word = TAG_escape($A['badword']);
			$retval .= '<tr><td>'
					.  '<input name="words[]" type="checkbox" value="' . $word
					. '"></td><td>' . $word . '</td></tr>' . LB;
			$sw = ($sw == 1) ? 2 : 1;
		}
		
		$retval .= '</table>' . LB
				.  '<input name="submit" type="submit" value="'
				.  TAG_str('delete_checked') . '">' . LB
				.  '</form>' . LB;
		
		return $retval;
	}
	
	function doAdd()
	{
		global $_TABLES;
		
		$word = TAG_post('word');
		$sql = "INSERT INTO {$_TABLES['tag_badwords']} (badword) "
			 . "VALUES ('" . addslashes($word) . "')";
		$result = DB_query($sql);
		return DB_error() ? TAG_str('add_fail') : TAG_str('add_success');
	}
	
	function doEdit()
	{
	}
	
	function doDelete()
	{
		global $_TABLES;
		
		$words = TAG_post('words');
		$words = array_map('addslashes', $words);
		$words = "('" . implode("','", $words) . "')";
		
		$sql = "DELETE FROM {$_TABLES['tag_badwords']} "
			 . "WHERE (badword IN " . $words . ")";
		$result = DB_query($sql);
		return DB_error() ? TAG_str('delete_fail') : TAG_str('delete_success');
	}
}
?>
