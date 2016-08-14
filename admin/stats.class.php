<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/stats.class.php                             |
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
		
		$sql = "SELECT L.tag, COUNT(m.tag_id) AS cnt, L.hits "
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
		
		$sw = 1;
		$retval = '<table class="plugin">' . LB
				.  '<tr><th>' . TAG_str('lbl_tag') . '</th><th>' . TAG_str('lbl_count')
				.  '</th><th>' . TAG_str('lbl_hit_count') . '</th></tr>' . LB;
		
		while (($A = DB_fetchArray($result)) !== false) {
			$retval .= '<tr class="pluginRow' . $sw . '"><td>'
					.  TAG_escape($A['tag']) . '</td><td style="text-align: right;">'
					.  TAG_escape($A['cnt']) . '</td><td style="text-align: right;">'
					.  TAG_escape($A['hits']) .  '</td></tr>' . LB;
			$sw = ($sw == 1) ? 2 : 1;
		}
		
		$retval .= '</table>' . LB;
		
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
	}
}

?>
