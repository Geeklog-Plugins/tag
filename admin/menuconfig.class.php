<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/menuconfig.class.php                        |
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

class TagMenuconfig
{
	var $menuList;
	var $tagList;
	
	function TagMenuconfig()
	{
		$this->_setMenuList();
		$this->_setTagList();
	}
	
	function _setMenuList()
	{
		global $_TABLES;
		
		$this->menuList = array();
		$this->menuList[0] = TAG_str('no_parent');
		$sql = "SELECT menu_id, menu_name FROM {$_TABLES['tag_menu']}";
		$result = DB_query($sql);
		if (!DB_error()) {
			while (($A = DB_fetchArray($result)) !== false) {
				$this->menuList[$A['menu_id']] = $A['menu_name'];
			}
		}
	}
	
	/**
	* Set tag list
	*/
	function _setTagList() {
		global $_TABLES;
		
		$this->tagList = array();
		
		$sql = "SELECT * FROM {$_TABLES['tag_list']}";
		$result = DB_query($sql);
		if (!DB_error() AND DB_numRows($result) >0) {
			while (($A = DB_fetchArray($result)) !== false) {
				$this->tagList[$A['tag_id']] = $A['tag'];
			}
		}
	}
	
	/**
	* Return option list of parent menus
	*/
	function getParentList($child_id, $current_parent_id)
	{
		$retval = '<select name="parent_id">' . LB;
		
		if (count($this->menuList) > 0) {
			foreach ($this->menuList as $id => $name) {
				if ($child_id == 0 OR $id != $child_id) {
					$retval .= '<option value="' . TAG_escape($id) . '"';
					if ($id == $current_parent_id) {
						$retval .= ' selected="selected"';
					}
					$retval .= '>' .  TAG_escape($name) . '</option>' . LB;
				}
			}
		}
		
		$retval .= '</select>' . LB;
		
		return $retval;
	}
	
	/**
	* Return all tags a menu has
	*/
	function _getTags($menu_id)
	{
		global $_TABLES;
		
		$retval = '';
		
		$sql = "SELECT tag_ids FROM {$_TABLES['tag_menu']} "
			 . "WHERE (menu_id = '" . addslashes($menu_id) . "')";
		$result = DB_query($sql);
		if (!DB_error() AND DB_numRows($result) == 1) {
			$A = DB_fetchArray($result);
			$tag_ids = $A['tag_ids'];
			if ($tag_ids != '') {
				$sql = "SELECT tag FROM {$_TABLES['tag_list']} "
					 . "WHERE (tag_id IN (" . $tag_ids . "))";
				$result = DB_query($sql);
				if (!DB_error()) {
					while (($A = DB_fetchArray($result)) !== false) {
						$retval .= $A['tag'] . ' ';
					}
				
					$retval = rtrim($retval);
				}
			}
		}
		
		return $retval;
	}
	
	/**
	* Convert tag list into tag id list
	*/
	function _getIdList($menu_tags) {
		$retval = array();
		
		if (is_array($menu_tags) AND count($menu_tags) > 0) {
			foreach ($menu_tags as $menu_tag) {
				$tag_id = array_search($menu_tag, $this->tagList);
				if ($tag_id !== false) {
					$retval[] = $tag_id;
				}
			}
		}
		
		return $retval;
	}
	
	function _addEdit($id, $parent_id)
	{
		global $_CONF, $_TABLES;
		
		if ($id != 0) {
			$sql = "SELECT * FROM {$_TABLES['tag_menu']} "
				 . "WHERE (menu_id = '" . addslashes($id) . "')";
			$result = DB_query($sql);
			$A = DB_fetchArray($result);
		} else {
			$A = array(
				'menu_id' => 0,
				'menu_name' => '',
				'tag_ids' => '',
				'parent_id' => $parent_id,
				'dsp_order' => 0
			);
		}
		
		$T = new Template($_CONF['path'] . 'plugins/tag/templates');
		$T->set_file('addEdit', 'admin_menu_edit.thtml');
		$T->set_var(
			'lang_desc',
			($id == 0) ? TAG_str('desc_add_menu') : TAG_str('desc_edit_menu')
		);
		$T->set_var('this_script', $_CONF['site_admin_url'] . '/plugins/tag/index.php');
		$T->set_var('lang_menu_name', TAG_str('menu_name'));
		$T->set_var('menu_name', TAG_escape($A['menu_name']));
		$T->set_var('lang_menu_parent', TAG_str('menu_parent'));
		$T->set_var('menu_parent', $this->getParentList($A['menu_id'], $A['parent_id']));
		$T->set_var('lang_menu_tags', TAG_str('menu_tags'));
		$T->set_var('menu_tags', TAG_escape($this->_getTags($id)));
		$T->set_var('action', ($id == 0) ? 'doAdd' : 'doEdit');
		$T->set_var('menu_id', TAG_escape($id));
		$T->set_var('lang_submit', TAG_str('submit'));
		$T->parse('output', 'addEdit');
		return $T->finish($T->get_var('output'));
	
	}
	
	function add()
	{
		return $this->_addEdit(0, 0);
	}
	
	function edit()
	{
		global $_TABLES;
		
		$id  = TAG_get('id', true);
		$pid = TAG_get('pid', true, true);
		return $this->_addEdit($id, $pid);
	}
	
	function delete()
	{
		global $_CONF, $_TABLES;
		
		$menu_id = TAG_get('id');
		$sql = "SELECT * FROM {$_TABLES['tag_menu']} "
			 . "WHERE (menu_id = '" . addslashes($menu_id) . "')";
		$result = DB_query($sql);
		if (DB_numRows($result) == 0) {
			return TAG_str('db_error');
		}
		
		$A = DB_fetchArray($result);
		$lang_vars = array(
			'desc_delete_menu', 'menu_name', 'menu_parent', 'menu_tags',
			'submit', 'cancel'
		);
		
		$T = new Template($_CONF['path'] . 'plugins/tag/templates');
		$T->set_file('delete', 'admin_menu_delete.thtml');
		
		foreach ($lang_vars as $lang_var) {
			$T->set_var('lang_' . $lang_var, TAG_str($lang_var));
		}
		
		$T->set_var('this_script', $_CONF['site_admin_url'] . '/plugins/tag/index.php');
		$T->set_var('menu_name', TAG_escape($A['menu_name']));
		$T->set_var('menu_parent', TAG_escape($this->menuList[$A['parent_id']]));
		$T->set_var('menu_tags', TAG_escape($this->_getTags($menu_id)));
		$T->set_var('menu_id', TAG_escape($menu_id));
		$T->parse('output', 'delete');
		return $T->finish($T->get_var('output'));
	}
	
	function view()
	{
		global $_CONF, $_TABLES, $LANG_TAG;
		
		$this_script = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
		$add_link = '<p><a href="' . $this_script . '?cmd=menuconfig&amp;action=add'
				  . '">' . TAG_str('add') . '</a></p>' . LB;
		
		$retval = $add_link . LB;
		$sql = "SELECT * FROM {$_TABLES['tag_menu']} "
			 . "ORDER BY parent_id, dsp_order";
		$result = DB_query($sql);
		if (DB_error()) {
			return $retval . TAG_str('db_error');
		} else if (DB_numRows($result) == 0) {
			return $retval . TAG_str('no_menu');
		}
		
		$retval .= '<table class="plugin">' . LB
				.  '<tr><th>' . TAG_str('menu_name') . '</th><th>'
				.  TAG_str('menu_parent') . '</th><th>' . TAG_str('menu_tags')
				.  '</th><th>' . TAG_str('menu_dsp_order') . '</th><th>'
				.  TAG_str('action'). '</th></tr>' . LB;
		$sw = 1;
		
		while (($A = DB_fetchArray($result)) !== false) {
			$edit_link = $this_script . '?cmd=menuconfig&amp;action=edit&amp;id='
					   . $A['menu_id'] . '&amp;pid=' . $A['parent_id'];
			$delete_link = $this_script . '?cmd=menuconfig&amp;action=delete&amp;id='
						 . $A['menu_id'];
			$retval .= '<tr class="pluginRow' . $sw . '"><td>'
					.  TAG_escape($A['menu_name']) . '</td><td>'
					.  TAG_escape($this->menuList[$A['parent_id']]) . '</td><td>'
					.  TAG_escape($this->_getTags($A['menu_id']))
					.  '</td><td>' . TAG_escape($A['dsp_order']) . '</td>'
					.  '<td><a href="' . $edit_link . '">' . TAG_str('edit') . '</a>'
					.  '&nbsp;&nbsp;<a href="' . $delete_link . '">' . TAG_str('delete')
					.  '</a></td></tr>' . LB;
			$sw = ($sw == 1) ? 2 : 1;
		}
		
		$retval .= '</table>' . LB
				.  $add_link . LB;
		
		return $retval;
	}
	
	function doAdd()
	{
		global $_TABLES, $_TAG_CONF;
		
		$menu_name = trim(TAG_post('menu_name'));
		if ($menu_name == '') {
			return TAG_str('add_fail');
		}
		
		$menu_tags = TAG_post('menu_tags');
		$menu_tags = TAG_scanTag('[' . $_TAG_CONF['tag_name'] . ':' . $menu_tags . ']');
		if (count($menu_tags) == 0) {
			$tag_ids = '';
		} else {
			$tag_ids = array();
			
			foreach ($menu_tags as $menu_tag) {
				$temp = TAG_getTagId($menu_tag);
				if ($temp !== false) {
					$tag_ids[] = $temp;
				}
			}
			
			if (count($tag_ids) > 0) {
				$tag_ids = implode(',', $tag_ids);
			} else {
				$tag_ids = '';
			}
		}
		
		$parent_id = TAG_post('parent_id');
		if (!array_key_exists($parent_id, $this->menuList)) {
			$parent_id = 0;
		}
		
		$dsp_order = TAG_post('dsp_order');
		
		$sql = "INSERT INTO {$_TABLES['tag_menu']} "
			 . "(menu_name, tag_ids, parent_id, dsp_order) "
			 . "VALUES ('" . addslashes($menu_name) . "', '" . $tag_ids
			 . "', '" . addslashes($parent_id) . "', '" . $dsp_order . "')";
		$result = DB_query($sql);
		return DB_error() ? TAG_str('add_fail') : TAG_str('add_success');
	}
	
	function doEdit()
	{
		global $_TABLES, $_TAG_CONF;
		
		$menu_id   = TAG_post('menu_id');
		$menu_name = TAG_post('menu_name');
		$menu_tags = TAG_post('menu_tags');
		$parent_id = TAG_post('parent_id');
		$menu_tags = TAG_scanTag('[' . $_TAG_CONF['tag_name'] . ':' . $menu_tags . ']');
		$tag_ids   = $this->_getIdList($menu_tags);
		if (count($tag_ids) > 0) {
			$tag_ids = implode(',', $tag_ids);
		} else {
			$tag_ids = '';
		}
		$sql = "UPDATE {$_TABLES['tag_menu']} "
			 . "SET menu_name = '" . addslashes($menu_name) . "', "
			 . "tag_ids = '" . addslashes($tag_ids) . "', "
			 . "parent_id = '" . addslashes($parent_id) . "' "
			 . "WHERE (menu_id = '" . addslashes($menu_id) . "')";
		$result = DB_query($sql);
		return DB_error() ? TAG_str('edit_fail') : TAG_str('edit_success');
	}
	
	function doDelete()
	{
		global $_TABLES, $LANG_TAG;
		
		$submit = TAG_post('submit');
		if ($submit !== $LANG_TAG['submit']) {
			return '';
		}
		
		$menu_id = TAG_post('menu_id', true, true);
		$sql = "DELETE FROM {$_TABLES['tag_menu']} "
			 . "WHERE (menu_id = '" . addslashes($menu_id) . "')";
		DB_query($sql);
		return DB_error() ? TAG_str('delete_fail') : TAG_str('delete_success');
	}
}
?>
