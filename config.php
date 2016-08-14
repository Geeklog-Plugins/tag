<?php
//
// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/tag/config.php                                            |
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

global $_DB_table_prefix, $_TABLES, $_TAG_CONF;

// Sets plugin table prefix the same as Geeklog's
$_TAG_table_prefix = $_DB_table_prefix;

// Adds to $_TABLES array the tables your plugin uses
$_TABLES['tag_list']      = $_TAG_table_prefix . 'tag_list';
$_TABLES['tag_map']       = $_TAG_table_prefix . 'tag_map';
$_TABLES['tag_badwords']  = $_TAG_table_prefix . 'tag_badwords';
$_TABLES['tag_menu']      = $_TAG_table_prefix . 'tag_menu';

$_TAG_CONF = array();

// Plugin info
$_TAG_CONF['pi_version']    = '0.6.0';					// Plugin Version
$_TAG_CONF['pi_gl_version'] = '1.7.1';					// GL Version plugin for
$_TAG_CONF['pi_url']        = 'http://mystral-kk.net/';	// Plugin Homepage

$_TAG_CONF['GROUPS'] = array(
	'Tag Admin' => 'Users in this group can administer the tag plugin'
);
$_TAG_CONF['FEATURES'] = array(
	'tag.admin' => 'Access to Tag plugin editor',
);
$_TAG_CONF['MAPPINGS'] = array(
	'tag.admin' => array('Tag Admin'),
);
