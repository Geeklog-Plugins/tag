<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------|
// | private/plugins/tag/language/english_utf-8.php                            |
// +---------------------------------------------------------------------------|
// | Copyright (C) 2008 mystral-kk - geeklog AT mystral-kk DOT net             |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// | Copyright (C) 2002 by the following authors:                              |
// | Tom Willett                 -    twillett@users.sourceforge.net           |
// | Blaine Lang                 -    langmail@sympatico.ca                    |
// | The Universal Plugin is based on prior work by:                           |
// | Tony Bibbs                  -    tony@tonybibbs.com                       |
// +---------------------------------------------------------------------------|
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
// | along with this program; if not, write to the Free Software               |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA|
// |                                                                           |
// +---------------------------------------------------------------------------|
//
// $Id$

$LANG_CHARSET = 'utf-8';

$LANG_TAG = array(
    'plugin'            => 'tag Plugin',
	'access_denied'     => 'Access Denied',
	'access_denied_msg' => 'Only Root Users have Access to this Page.  Your user name and IP have been recorded.',
	'admin'		        => 'tag Plugin Admin',
	'install_header'	=> 'Install/Uninstall the tag Plugin',
	'install_success'	=> 'Installation Successful',
	'install_fail'	    => 'Installation Failed -- See your error log to find out why.',
	'uninstall_success'	=> 'Uninstallation Successful',
	'uninstall_fail'    => 'Installation Failed -- See your error log to find out why.',
	'uninstall_msg'		=> 'Tag plugin was successfully uninstalled.',
	'tag_separators'    => ' ',	// Can be more than one character
	'badword_replace'   => '',
	'admin_label'       => 'Tag',
	'display_label'     => 'Tag: ',
	'tag_list'          => 'Tag list',
	'selected_tag'      => 'Items having <strong>%s</strong> tag: ',	// %s = tag name
	'related'           => 'Related tags',
	'block_title'       => 'Popular tags at this site',
	'menu_stats'        => 'Statistics',
	'menu_badword'      => 'Bad Words',
	'menu_menuconfig'   => 'Tag Menu Config',
	'db_error'          => 'Cannot read from database.',
	'action'            => 'Action',
	'lbl_tag'           => 'Tag',
	'lbl_count'         => 'Frequency',
	'lbl_hit_count'     => 'Number of clicks',
	'check'             => 'Check',
	'add'               => 'Add',
	'edit'              => 'Edit',
	'delete'            => 'Delete',
	'delete_checked'    => 'Delete checked entries',
	'submit'            => 'Submit',
	'cancel'            => 'Cancel',
	'badword'           => 'Bad words',
	'no_tag'            => 'No tag is defined yet.',
	'no_badword'        => 'No Bad Word is defined yet.',
	'no_menu'           => 'No Tag Menu is defined yet.',
	'no_parent'         => '(None)',
	'menu_name'         => 'Menu Name',
	'menu_parent'       => 'Parent Menu',
	'menu_tags'         => 'Contained Tags',
	'menu_dsp_order'    => 'Display Order',
	'desc_add_menu'     => 'Add Tag Menu',
	'desc_edit_menu'    => 'Edit Tag Menu',
	'desc_delete_menu'  => 'Delete Tag Menu',
	'add_success'       => 'Successfully added.',
	'add_fail'          => 'Cannot add.',
	'delete_success'    => 'Successfully deleted.',
	'delete_fail'       => 'Cannot delete.',
	'edit_success'      => 'Successfully modified.',
	'edit_fail'         => 'Cannot modify.',
	'menu_title'        => 'Items containing tags: %s',
	'no_item'           => 'No matching items found.',
);

?>
