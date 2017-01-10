<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------|
// | geeklog/plugins/tag/language/english_utf-8.php                            |
// +---------------------------------------------------------------------------|
// | Copyright (C) 2008-2017 mystral-kk - geeklog AT mystral-kk DOT net        |
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

$LANG_TAG = array(
    'plugin'            => 'Tag Plugin',
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
	'default_block_title' => 'Popular tags at this site',
	'default_block_title_menu' => 'Tag Menu',
	'tag_list'          => 'Tag list',
	'selected_tag'      => 'Items having <strong>%s</strong> tag: ',	// %s = tag name
	'related'           => 'Related tags',
	'block_title'       => 'Popular tags at this site',
	'menu_stats'        => 'Statistics',
	'menu_badword'      => 'Banned Tags',
	'menu_menuconfig'   => 'Tag Menu Config',
	'db_error'          => 'Cannot read from database.',
	'action'            => 'Action',
	'desc_admin_stats'  => 'This is the list of registered tags.  You can delete wrongly registered tags or can ban tags (e.g. too popular tags or vulgar tags).',
	'lbl_tag'           => 'Tag',
	'lbl_count'         => 'Frequency',
	'lbl_hit_count'     => 'Number of clicks',
	'delete_checked'    => 'Delete checked tags',
	'ban_checked'       => 'Ban checked tags',
	'desc_admin_badword' => 'This is the list of tags you would like to ban, for example, too popular or too vulgar tags.',
	'check'             => 'Check',
	'add'               => 'Add',
	'edit'              => 'Edit',
	'delete'            => 'Delete',
	'submit'            => 'Submit',
	'cancel'            => 'Cancel',
	'badword'           => 'Banned tags',
	'no_tag'            => 'No tag is defined yet.',
	'no_badword'        => 'No tag is banned yet.',
	'desc_admin_menuconfig' => 'These are defined menu items.',
	'no_parent'         => '(None)',
	'menu_name'         => 'Menu Name',
	'menu_parent'       => 'Parent Menu',
	'menu_tags'         => 'Contained Tags',
	'menu_dsp_order'    => 'Display Order',
	'no_menu'			=> 'No menu defined',
	'desc_add_menu'     => 'Add Tag Menu',
	'desc_edit_menu'    => 'Edit Tag Menu',
	'desc_delete_menu'  => 'Delete Tag Menu',
	'add_child'         => 'Add child menu',
	'order_up'          => 'Up',
	'order_down'        => 'Down',
	'add_success'       => 'Successfully added.',
	'add_fail'          => 'Cannot add.',
	'delete_success'    => 'Successfully deleted.',
	'delete_fail'       => 'Cannot delete.',
	'edit_success'      => 'Successfully modified.',
	'edit_fail'         => 'Cannot modify.',
	'menu_title'        => 'Items containing tags: %s',
	'no_item'           => 'No matching items found.',
	'config'			=> 'Configuration',
);


// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'Tag plugin',
    'title' => 'Tag plugin Config'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
	'hidetagmenu'					=> 'Hide Tag Menu entry',
	'default_block_name'			=> 'Default name for Tag Cloud Block',
	'tag_name'						=> 'Tag name',
	'max_tag_len'					=> 'Max length of tag in bytes',
	'tag_case_sensitive'			=> 'Tag case-sensitive',
    'tag_stemming'					=> 'Allow stemming words',
	'tag_check_badword'				=> 'Use list of bad words',
	'tag_cloud_spacer'				=> 'String to be used as spacer in Tag Cloud',
	'max_tag_cloud'					=> 'Max number of tags in Tag Cloud',
	'max_tag_cloud_in_block'		=> 'Max Number of tgas in Tag Cloud Block',
	'tag_cloud_threshold'			=> 'Threshold of Tag Levels',
	'tag_cloud_threshold_max_count'	=> 'Threshold of percentage-based system',
	'replace_underscore'			=> 'Replace an underscore with a space',
	'num_keywords'					=> 'Max number of keywords',
	'publish_as_template_vars'		=> 'Publish tags as template vars',
	'default_block_name_menu'		=> 'Default name for Tag Menu Block',
	'menu_indenter'					=> 'String to be used as an indenter in Tag Menu',
	'add_num_items_to_menu'			=> 'Display number of items in Tag Menu',
);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'Main'
);

$LANG_fs['tag'] = array(
    'fs_main'   => 'Tag plugin Main Config',
);

// Note: entries 0, 1, 9, and 12 are the same as in $LANG_configselects['Core']
$LANG_configselects['tag'] = array(
    0 => array('Yes' => true, 'No' => false),
);
