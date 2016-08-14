<?php
//
// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | private/plugins/tag/config.php                                            |
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

global $_DB_table_prefix, $_TABLES;

// set Plugin Table Prefix the Same as Geeklogs

$_TAG_table_prefix = $_DB_table_prefix;

// Add to $_TABLES array the tables your plugin uses

$_TABLES['tag_list']      = $_TAG_table_prefix . 'tag_list';
$_TABLES['tag_map']       = $_TAG_table_prefix . 'tag_map';
$_TABLES['tag_badwords']  = $_TAG_table_prefix . 'tag_badwords';
$_TABLES['tag_menu']      = $_TAG_table_prefix . 'tag_menu';

$_TAG_CONF = array();

// Plugin info

$_TAG_CONF['pi_version'] = '0.3.2';						// Plugin Version
$_TAG_CONF['gl_version'] = '1.4.0';						// GL Version plugin for
$_TAG_CONF['pi_url']     = 'http://mystral-kk.net/';	// Plugin Homepage

//=========================================================
//  DO NOT CHANGE ANYTHING ABOVE THIS LINE
//=========================================================

/**
* User Configurations
*/

/**
* Default name of a tag cloud block which will be created during the
* installation.  If you use Geeklog-1.4.1 or later and disable/enable the tag
* plugin, the block named $_TAG_CONF['default_block_name'] will also
* be disabled/enabled automatically.
*/
$_TAG_CONF['default_block_name'] = 'tag_cloud_block';

/**
* Tag name to be used in items (articles), like '[tag:foo]'.  You might prefer
* a shorter name like '[t:foo]'.
*/
$_TAG_CONF['tag_name'] = 'tag';

/**
* Max length of a tag in bytes.  Should not be longer than 255.
*/
$_TAG_CONF['max_tag_len'] = 60;

/**
* If this is true, the tag "Geeklog" will NOT be identified with the tag
* "geeklog".
*/
$_TAG_CONF['tag_case_sensitive'] = false;

/**
* If this is true, each tag consisting only of alphabets will be stemmed.  For
* example, tag "realize" will be stemmed into "real", thus tag "realize" will be
* identidied with tag "real".
*
* @WARNING: The stemming feature is still not perfect.  For example, 'Firefox'
*           is stemmed into 'Firefoxi'.  So, I don't recommend you set
*           $_TAG_CONF['tag_stemming'] to true for the time being.
*/
$_TAG_CONF['tag_stemming'] = false;

/**
* Whether to use a list of bad words.  If a tag is regarded as bad, it will be
* replaced with $LANG_TAG['badword_replace'] automatically.
*/
$_TAG_CONF['tag_check_badword'] = true;

/**
* A string to be used as a spacer in displaying tag clouds
*/
$_TAG_CONF['tag_cloud_spacer'] = '  ';

/**
* Max number of tags to be displayed in tag clouds in public_html/tag/index.php
*/
$_TAG_CONF['max_tag_cloud'] = 30;

/**
* Max number of tags to be displayed in tag clouds in side block
*/
$_TAG_CONF['max_tag_cloud_in_block'] = 20;

/**
* Thresholds of frequency of each tag cloud level
*
* All tag clouds are classified in 10 levels (level 0..level 9).  Those tags
* whose number is equal to or smaller than $_TAG_CONF['tag_cloud_threshold'][x]
* belong to level x.  Each level corresponds to its own class in CSS(Cascading
* Style Sheet), so you can display in different styles tags according to their
* levels.
*/
$_TAG_CONF['tag_cloud_threshold'][0]  = 1;
$_TAG_CONF['tag_cloud_threshold'][1]  = 2;
$_TAG_CONF['tag_cloud_threshold'][2]  = 3;
$_TAG_CONF['tag_cloud_threshold'][3]  = 4;
$_TAG_CONF['tag_cloud_threshold'][4]  = 5;
$_TAG_CONF['tag_cloud_threshold'][5]  = 6;
$_TAG_CONF['tag_cloud_threshold'][6]  = 7;
$_TAG_CONF['tag_cloud_threshold'][7]  = 8;
$_TAG_CONF['tag_cloud_threshold'][8]  = 9;

/**
* Whether to replace an underscore included in tag texts with a space
*/
$_TAG_CONF['replace_underscore'] = false;

/**
* The number of key words to be included in <meta name="keywords"
* content="foo,bar"> tag
*/
$_TAG_CONF['num_keywords'] = 0;

/**
* Whether to publish tags as template vars which can be used in
* 'storytext.thtml', 'featuredstorytext.thtml' and 'archivestorytext.thtml'.
* This idea was provided by dengen.
*
* CAUTION: This feature is valid for Geeklog-1.4.1 or later.
*/
$_TAG_CONF['publish_as_template_vars'] = false;

/**
* This is work vars and should be left untouched by users
*/
$_TAG_CONF['template_vars'] = array();

/**
* Configurations for tag menu (Tag-0.3.0 or later)
*/

/**
* Default name of a tag menu block which will be created during the
* installation.  If you use Geeklog-1.4.1 or later and disable/enable the tag
* plugin, the block named $_TAG_CONF['default_block_name'] will also
* be disabled/enabled automatically.
*/
$_TAG_CONF['default_block_name_menu'] = 'tag_menu_block';

/**
* Character(s) for indenting tag menu item
*/
$_TAG_CONF['menu_indenter'] = '&nbsp;&nbsp;&nbsp;';

/**
* Whether to add the number of items to each tag menu item
*
* @note This feature could be a costly operation
*/
$_TAG_CONF['add_num_items_to_menu'] = false;

?>
