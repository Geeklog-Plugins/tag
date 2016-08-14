<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/tag/autoinstall.php                                       |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2012 mystral-kk - geeklog AT mystral-kk DOT net        |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
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

if (stripos($_SERVER['PHP_SELF'], basename(__FILE__)) !== FALSE) {
	die('This file can not be used on its own!');
}

require_once dirname(__FILE__) . '/config.php';

/**
* Plugin autoinstall function
*
* @param    string  $pi_name    Plugin name
* @return   array               Plugin information
*/
function plugin_autoinstall_tag($pi_name) {
	global $_TAG_CONF;

	$pi_name         = 'tag';
	$pi_display_name = 'Tag';
	$pi_admin        = $pi_display_name . ' Admin';

	$info = array(
		'pi_name'         => $pi_name,
		'pi_display_name' => $pi_display_name,
		'pi_version'      => $_TAG_CONF['pi_version'],
		'pi_gl_version'   => $_TAG_CONF['pi_gl_version'],
		'pi_homepage'     => $_TAG_CONF['pi_url'],
	);

	$inst_parms = array(
		'info'      => 	array(
							'pi_name'         => $pi_name,
							'pi_display_name' => $pi_display_name,
							'pi_version'      => $_TAG_CONF['pi_version'],
							'pi_gl_version'   => $_TAG_CONF['pi_gl_version'],
							'pi_homepage'     => $_TAG_CONF['pi_url'],
						),
		'groups'    => $_TAG_CONF['GROUPS'],
		'features'  => $_TAG_CONF['FEATURES'],
		'mappings'  => $_TAG_CONF['MAPPINGS'],
		'tables'    => array('tag_list', 'tag_map', 'tag_badwords', 'tag_menu'),
	);

	return $inst_parms;
}

/**
* Loads plugin configuration from database
*
* @param    string  $pi_name    Plugin name
* @return   boolean             TRUE on success, otherwise FALSE
* @see      plugin_initconfig_tag
*/
function plugin_load_configuration_tag($pi_name)
{
    global $_CONF;

    $base_path = $_CONF['path'] . 'plugins/' . $pi_name . '/';

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $base_path . 'install_defaults.php';

    return plugin_initconfig_tag();
}

/**
* Checks if the plugin is compatible with this Geeklog version
*
* @param    string  $pi_name    Plugin name
* @return   boolean             TRUE: plugin compatible; FALSE: not compatible
*/
function plugin_compatible_with_this_version_tag($pi_name) {
	global $_CONF, $_DB_dbms, $_TABLES, $_TAG_CONF;

	$retval = TRUE;

	// Checks if we support the DBMS the site is running on
	$dbFile = $_CONF['path'] . 'plugins/' . $pi_name . '/sql/' . $_DB_dbms
			. '_install.php';
	clearstatcache();

	if (!file_exists($dbFile)) {
		$retval = FALSE;
	} else if (defined('VERSION')) {
		$gl_version = preg_replace('/[^0-9\.]/', '', VERSION);
		$retval = (version_compare($gl_version, $_TAG_CONF['pi_gl_version']) >= 0);
	} else {
		$retval = FALSE;
	}

	// Checks if the version of the staticpages plugin is 1.6.3 or greater
	$version = DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name = 'staticpages'");
	$version = preg_replace('/[^0-9\.]/', '', $version);

	if (version_compare($version, '1.6.3') < 0) {
		$retval = FALSE;
	}

	return $retval;
}

/**
* Adds PHP blocks and scans all the items supported by the Tag plugin
*/
function plugin_postinstall_tag($pi_name) {
	global $_CONF, $_TABLES, $_USER, $_TAG_CONF, $LANG_TAG;

	require_once dirname(__FILE__) . '/functions.inc';
	
	if (is_callable('COM_createHTMLDocument')) {
		// Adds a tag cloud block to the site
		$sql = "INSERT INTO {$_TABLES['blocks']} "
			 . "  (is_enabled, name, type, title, blockorder, onleft, "
			 . "  phpblockfn, owner_id, group_id, perm_owner, perm_group, "
			 . "  perm_members, perm_anon) "
			 . "VALUES (1, '" . addslashes($_TAG_CONF['default_block_name'])
			 . "', 'phpblock', '" . addslashes($LANG_TAG['default_block_title'])
			 . "', 1, 0, 'phpblock_tag_cloud', '" . addslashes($_USER['uid'])
			 . "', 1, 3, 3, 2, 2)";
		DB_query($sql);
		$bid = DB_insertId();
		$sql = "INSERT INTO {$_TABLES['topic_assignments']} "
			 . "  (tid, type, id, inherit, tdefault) "
			 . "VALUES ('all', 'block', {$bid}, 1, 0) ";
		DB_query($sql);
		
		// Adds a tag menu block to the site
		$sql = "INSERT INTO {$_TABLES['blocks']} (is_enabled, name, type, title, "
			 . "  blockorder, onleft, phpblockfn, owner_id, group_id, "
			 . "  perm_owner, perm_group, perm_members, perm_anon) "
			 . "VALUES (1, '" . addslashes($_TAG_CONF['default_block_name_menu'])
			 . "', 'phpblock', '" . addslashes($LANG_TAG['default_block_title_menu'])
			 . "', 1, 1, 'phpblock_tag_menu', '" . addslashes($_USER['uid'])
			 . "', 1, 3, 3, 2, 2)";
		DB_query($sql);
		$bid = DB_insertId();
		$sql = "INSERT INTO {$_TABLES['topic_assignments']} "
			 . "  (tid, type, id, inherit, tdefault) "
			 . "VALUES ('all', 'block', {$bid}, 1, 0) ";
		DB_query($sql);
	} else {
		// Adds a tag cloud block to the site
		$sql = "INSERT INTO {$_TABLES['blocks']} "
			 . "  (is_enabled, name, type, title, tid, blockorder, onleft, "
			 . "  phpblockfn, owner_id, group_id, perm_owner, perm_group, "
			 . "  perm_members, perm_anon) "
			 . "VALUES (1, '" . addslashes($_TAG_CONF['default_block_name'])
			 . "', 'phpblock', '" . addslashes($LANG_TAG['default_block_title'])
			 . "', 'all', '1', '0', 'phpblock_tag_cloud', '" . addslashes($_USER['uid'])
			 . "', '1', '3', '3', '2', '2')";
		DB_query($sql);

		// Adds a tag menu block to the site
		$sql = "INSERT INTO {$_TABLES['blocks']} (is_enabled, name, type, title, "
			 . "  tid, blockorder, onleft, phpblockfn, owner_id, group_id, "
			 . "  perm_owner, perm_group, perm_members, perm_anon) "
			 . "VALUES ('1', '" . addslashes($_TAG_CONF['default_block_name_menu'])
			 . "', 'phpblock', '" . addslashes($LANG_TAG['default_block_title_menu'])
			 . "', 'all', '1', '1', 'phpblock_tag_menu', '" . addslashes($_USER['uid'])
			 . "', '1', '3', '3', '2', '2')";
		DB_query($sql);
	}
	
	// Scans all contents for tags
	TAG_scanAll();

	return TRUE;
}
