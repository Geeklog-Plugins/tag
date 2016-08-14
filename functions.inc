<?php
//
// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | private/plugins/tag/functions.inc                                         |
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

/**
* Language file Include
*/
$langfile = $_CONF['path'] . 'plugins/tag/language/' . $_CONF['language'] . '.php';

if (file_exists($langfile)) {
    include_once $langfile;
} else {
    include_once $_CONF['path'] . 'plugins/tag/language/english.php';
}

/*
* Include tag config file
*/
require_once $_CONF['path'] . 'plugins/tag/config.php';

/**
* Include Porter Stemmer
*/
if ($_TAG_CONF['tag_stemming'] === true) {
	require_once $_CONF['path'] . 'plugins/tag/porter_stemmer.php';
}

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+

/**
* Returns the items for this plugin that should appear on the main menu
*
* NOTE: this MUST return the url/value pairs in the following format
* $<arrayname>[<label>] = <url>
*
* Universal Plugin:  Uncomment and fill in variables;
* 
* @return    array   Associative array of menu items to be added to menu
*
*/
function plugin_getmenuitems_tag() {
	global $_CONF;

	$menulabel = TAG_str('admin_label');
	$menurul   = $_CONF['site_url'] . "/tag/index.php";
    $menuitems = array();
	$menuitems[$menulabel] = $menurul;
    return $menuitems;
}

/**
* Check to see if this Plugin uses comments
*
* Geeklog is checking to see if this plugin supports comments, tell it no!
* NOTE: to support comments you must use the same date/time based ID for your
* widget.  In other words, to make primary keys for your plugin you should call
* makesid().  Comments are keyed off of that...it is a limitation on how geeklog
* does comments.
*
* @return   boolean true or false
*
*/
function plugin_commentsupport_tag() {
    return false;
}

/**
* This will put an option for the plugin in the command and control block on
* moderation.php
*
* Add the plugin name, icon and link to the command and control block in moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
*
*/
function plugin_cclabel_tag() {
    global $_CONF, $LANG_TAG;

	$pi_name   = TAG_str('admin_label');
	$admin_url = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
	$icon_url  = $_CONF['site_url'] . '/tag/images/tag.gif';

    return array($pi_name, $admin_url, $icon_url);
}

/**
* Returns the administrative option for this plugin
*
* Adds the plugin to the Admin menu
*
* Universal Plugin:  Modify/Uncomment use it
*
* @return   array   Array containing (plugin name, plugin admin url, # of items in plugin or '')
*
*/
function plugin_getadminoption_tag() {
	global $_CONF, $_TABLES, $LANG_TAG;

	if (SEC_hasRights('tag.admin')) {
		$pi_name   = TAG_str('admin_label');
		$admin_url = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
		$num_items = DB_count($_TABLES['tag_list']);
		return array($pi_name, $admin_url, $num_items);
	}
}

/*
* Returns the version for this plugin
*
* @return string VersionNo
*/
function plugin_chkVersion_tag() {
	global $_TAG_CONF;
	
	return $_TAG_CONF['pi_version'];
}

/**
* Upgrade the plugin
*
* Universal Plugin:  modify/uncomment use it
*
* @return   boolean true (= success)
*
*/
function plugin_upgrade_tag() {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$result = DB_query("SELECT pi_version FROM {$_TABLES['plugins']} WHERE (pi_name = 'tag')");
	$A = DB_fetchArray($result);
	$pi_version = $A['pi_version'];		// Version info stored in database
	
	// Do upgrading
	
// 	switch ($pi_version) {
// 		case '':
// 		break;
// 		
// 		case '':
// 		break;
// 		
// 	}
	
	// Update plugin info
	
	$result = DB_query("UPDATE {$_TABLES['plugins']} SET pi_version = \"{$_TAG_CONF['pi_version']}\" WHERE (pi_name = 'tag')");
	COM_errorLog(ucfirst("tag") . " plugin was successfully updated to version {$_TAG_CONF['pi_version']}.");
	
	return true;
}

/**
* Removes the datastructures for this plugin from the Geeklog database.
* This routine will get called from the Plugin install program if user select De-Install or if Delete is used in the Plugin Editor.
* The Plugin Installer will also call this routine upon and install error to remove anything it has created.
* The Plugin installer will pass the optional parameter which will then double check that plugin has first been disabled. 
* 
* For this plugin, this routine will also remove the Block definition.
* 
* Returns True if all Plugin related data is removed without error
*
* @param    string   $installCheck     Default is blank but if set, check if plugin is disabled first
* 
* @return   boolean True if successful false otherwise
*
*/  
function plugin_uninstall_tag($installCheck = '') {
    global $_TABLES, $LANG_TAG;

    // $FEATURES and $TABLES have to be changed accodrding to your plugin
    $pi_name  = 'tag';
    $FEATURES = array('tag.admin');
    $TABLES   = array('tag_list', 'tag_map', 'tag_badwords', 'tag_menu');
    
    // Check and see if plugin is still enabled - if so display warning and exit
    if ($installCheck != '' && DB_getItem($_TABLES['plugins'], 'pi_enabled', 'pi_name = "' . $pi_name . '"')) {
	    COM_errorLog("Plugin is installed and enabled. Disable first if you want to de-install it", 1);
		$display .= COM_startBlock($LANG_TAG['warning']);
		$display .= $LANG_TAG['enabled'];
		$display .= COM_endBlock();
		echo $display;
		return false;
		exit;
	}
    	
	// Ok to proceed and delete plugin

    // Unregister the plugin with Geeklog
    COM_errorLog('Attempting to unregister the tag plugin from Geeklog', 1);
    DB_query("DELETE FROM {$_TABLES['plugins']} WHERE pi_name = 'tag'", 1);

    // Drop tag tables
    foreach ($TABLES as $table) {
        $t = $_TABLES["$table"];
        COM_errorLog("Removing Table $t", 1);
        DB_query("DROP TABLE $t", 1);
    }

    // Remove Security for this plugin
    $grp_id = DB_getItem($_TABLES['vars'], 'value', "name = '{$pi_name}_gid'");
    
    COM_errorLog("Removing $pi_name Admin Group", 1);
    DB_query("DELETE FROM {$_TABLES['groups']} WHERE grp_id = $grp_id", 1);
    DB_query("DELETE FROM {$_TABLES['vars']} WHERE name = '{$pi_name}_gid'");
    COM_errorLog("Removing root users from admin of $pi_name");
    DB_query("DELETE FROM {$_TABLES['group_assignments']} WHERE ug_main_grp_id = $grp_id", 1);
    
    // Remove all the associated features -- access rights
    foreach ($FEATURES as $feature) {
        COM_errorLog("Removing $feature feature and rights to it", 1);
        $feat_id = DB_getItem($_TABLES['features'], 'ft_id', "ft_name = '$feature'");
        DB_query("DELETE FROM {$_TABLES['access']} WHERE acc_ft_id = $feat_id", 1);
        DB_query("DELETE FROM {$_TABLES['features']} WHERE ft_id = $feat_id", 1);
    }
        
    COM_errorLog('...success', 1);
    return true;
}

/**
* Compatibe functions for Geeklog-1.4.x
*/
if (!function_exists('COM_isAnonUser')) {
	/**
	* Checks to see if a specified user, or the current user if non-specified
	* is the anonymous user.
	*
	* @param  int $uid    ID of the user to check, or none for the current user.
	* @return boolean     true if the user is the anonymous user.
	*/
	function COM_isAnonUser($uid = '') {
		global $_USER;

		/* If no user was specified, fail over to the current user if there is one */
		if (empty($uid)) {
			if (isset($_USER['uid'])) {
				$uid = $_USER['uid'];
			}
		}

		if (!empty($uid)) {
			return ($uid == 1);
		} else {
			return true;
		}
	}
}

if (!function_exists('COM_getCharset')) {
	/**
	* Get the current character set
	*
	* @return   string      character set, e.g. 'utf-8'
	*
	* Uses (if available, and in this order)
	* - $LANG_CHARSET (from the current language file)
	* - $_CONF['default_charset'] (from config.php)
	* - 'iso-8859-1' (hard-coded fallback)
	*
	*/
	function COM_getCharset() {
		global $_CONF, $LANG_CHARSET;

		if (empty($LANG_CHARSET)) {
			$charset = $_CONF['default_charset'];
			if (empty($charset)) {
				$charset = 'iso-8859-1';
			}
		} else {
			$charset = $LANG_CHARSET;
		}

		return $charset;
	}
}

if (!function_exists('COM_truncate')) {
	if (!function_exists('MBYTE_strlen')) {
		function MBYTE_strlen($str) {
			if (function_exists('mb_strlen')) {
				return mb_strlen($str, COM_getCharset());
			} else {
				return strlen($str);
			}
		}
	}
	
	if (!function_exists('MBYTE_substr')) {
		function MBYTE_substr($str, $start, $len) {
			if (function_exists('mb_substr')) {
				return mb_substr($str, $start, $len, COM_getCharset());
			} else {
				return substr($str, $start, $len);
			}
		}
	}
	
	/**
	* Truncate a string
	*/
	function COM_truncate($text, $maxlen, $filler = '') {
		$newlen = $maxlen - MBYTE_strlen($filler);
		$len = MBYTE_strlen($text);
		if ($len > $maxlen) {
			$text = MBYTE_substr($text, 0, $newlen) . $filler;
		}

		return $text;
	}
}

/**
* Append a link to CSS file to <head> section
*/
function plugin_getheadercode_tag() {
	global $_CONF, $_USER;
	
	$theme = isset($_USER['theme']) ? $_USER['theme'] : $_CONF['theme'];
	clearstatcache();
	if (file_exists($_CONF['path_themes'] . $theme . '/tag.css')) {
		$url = $_CONF['site_url'] . '/layout/' . $theme . '/tag.css';
	} else {
		$url = $_CONF['site_url'] . '/tag/tag.css';
	}
	
	return '<link rel="stylesheet" type="text/css" href="' . $url . '">';
}

/**
* Strip slashes when magic_quotes_gpc is on
*/
function TAG_stripSlashes($var) {
	if (is_array($var)) {
		return array_map('TAG_stripSlashes', $var);
	} else {
		if (get_magic_quotes_gpc()) {
			$var = stripslashes($var);
		}
		
		return $var;
	}
}

/**
* Retrieve a Get var
*/
function TAG_get($name, $is_num = false) {
	if (isset($_GET[$name])) {
		return COM_applyFilter(TAG_stripSlashes($_GET[$name]), $is_num);
	} else {
		return false;
	}
}

/**
* Retrieve a Post var
*/
function TAG_post($name, $is_token = false, $is_num = false) {
	if (isset($_POST[$name])) {
		$var = TAG_stripSlashes($_POST[$name]);
		if ($is_token) {
			if (is_array($var)) {
				return array_map('COM_applyFilter', $var, array_fill(0, count($var), $is_num));
			} else {
				return COM_applyFilter($var, $is_num);
			}
		} else {
			return $var;
		}
	} else {
		return false;
	}
}

/**
* Escape a string for displaying in HTML
*/
function TAG_escape($str) {
	// Unescape a string
	$str = str_replace(
		array('&lt;', '&gt;', '&amp;', '&quot:', '&#039;'),
		array(   '<',    '>',     '&',      '"',      "'"),
		$str
	);
	
	return htmlspecialchars($str, ENT_QUOTES, COM_getCharset());
}

/**
* Return an escaped language-specific message
*/
function TAG_str($msg) {
	global $LANG_TAG;
	
	if (isset($LANG_TAG[$msg])) {
		return TAG_escape($LANG_TAG[$msg]);
	} else {
		return '';
	}
}

/**
* Split a string acccoding to $LANG_TAG['tag_separators']
*/
function TAG_split($str) {
	global $LANG_TAG;
	
	$pattern = preg_quote($LANG_TAG['tag_separators']);
	$pattern = '[' . $pattern . ']+';
	if (function_exists('mb_split')) {
		$retval = mb_split($pattern, $str);
	} else {
		$retval = split($pattern, $str);
	}
	
	return $retval;
}

/**
* Stem a string using Porter Stemming algorithm
*
* @param  string $str - a normalized word
* @return string      - a stemmed word
*/
function TAG_stem($str) {
	global $_TAG_CONF;
	
	static $porterStemmer = null;
	
	if (!is_object($porterStemmer)) {
		$porterStemmer = new PorterStemmer();
	}
	
	$retval = '';
	
	if (function_exists('mb_strlen')) {
		/**
		* Stem English part
		*/
		$i    = 0;
		$len  = mb_strlen($str);
		$temp = $str;
		$str  = '';
		$word = '';
		
		while ($i < $len) {
			$ch = mb_substr($temp, $i, 1);
			if (mb_strwidth($ch) == 1) {
				$word .= $ch;
			} else {
				if ($word != '') {
					$str .= $porterStemmer->Stem($str);
					$word = '';
				} else {
					$str .= $ch;
				}
			}
			
			$i ++;
		}
		
		if ($word != '') {
			$str .= $porterStemmer->Stem($word);
		}
	} else {
		$str = $porterStemmer->Stem($str);
	}
	
	return $str;
}

/**
* Normalize a string
*/
function TAG_normalize($str) {
	global $_TAG_CONF;
	
	if (function_exists('mb_convert_kana')) {
		/**
		* Convert Zenkaku alphabet and number into Hankaku, and Hankaku Katakana
		* into Zenkaku Katakana.  We distinguish between Hiragana and Katakana.
		*/
		$str = mb_convert_kana($str, 'aKV');
		if ($_TAG_CONF['tag_case_sensitive'] === false) {
			$str = mb_strtolower($str);
		}
		
		if (mb_strwidth($str) > $_TAG_CONF['max_tag_len']) {
			$str = mb_strcut($str, 0, $_TAG_CONF['max_tag_len']);
		}
	} else {
		if ($_TAG_CONF['tag_case_sensitive'] === false) {
			$str = strtolower($str);
		}
		
		if (strlen($_TAG_CONF['max_tag_len']) > $_TAG_CONF['max_tag_len']) {
			$str = substr($str, 0, $_TAG_CONF['max_tag_len']);
		}
	}
	
	return $str;
}

/**
* Replace [tag:foo] tags
*
* @note: As of Geeklo-1.4.0 (1.4.1 as well?), when you use [tag:foo] tag MORE
* THAN ONE time in a story, $autotag['startpos'] cannot be relied upon due to
* a bug in PLG_replaceTags().
*/
function plugin_autotags_tag($cmd, $content = '', $autotag = '') {
	global $_CONF, $_TAG_CONF, $LANG_TAG;
	
	if ($cmd == 'tagname') {
		return $_TAG_CONF['tag_name'];
	} else if (in_array(strtolower($autotag['tag']), (array) $_TAG_CONF['tag_name'])) {
		$tagstr   = $autotag['tagstr'];
		$param    = substr($tagstr, strlen($_TAG_CONF['tag_name']) + 2, -1);
		$tag_part = '';
		
		if ($param != '') {
			$tags = TAG_split($param);
			
			foreach ($tags as $tag) {
				$tag = TAG_normalize($tag);
				if (TAG_checkBadword($tag) === true) {
					$tag_part .= TAG_str('badword_replace') . ' ';
				} else {
					if ($_TAG_CONF['tag_stemming'] === true) {
						$tag = TAG_stem($tag);
					}
					$url = $_CONF['site_url'] . '/tag/index.php?tag='
						 . rawurlencode($tag);
					$tag_part .= '<a class="tag_link" href="' . COM_buildURL($url)
							  .  '">' .  TAG_escape($tag) . '</a> ';
				}
			}
			
			$tag_part = rtrim($tag_part);
			
			if ($tag_part != '') {
				$content = str_replace($tagstr, $LANG_TAG['display_label'] . $tag_part, $content);
			}
		}
	}
	
	return $content;
}

/**
* Return a tag id
*
* @param $str - a normalized tag
*/
function TAG_getTagId($tag) {
	global $_TABLES;
	static $list = array();
	
	if (isset($list[$tag])) {
		return $list[$tag];
	}
	
	$sql = "SELECT tag_id FROM {$_TABLES['tag_list']} "
		 . "WHERE (tag = '" . addslashes($tag) . "')";
	$result = DB_query($sql);
	if (DB_error()) {
		return false;
	} else if (DB_numRows($result) == 0) {
		return false;
	} else {
		$A = DB_fetcharray($result);
		$tag_id = $A['tag_id'];
		$list[$tag] = $tag_id;
		return $tag_id;
	}
}

/**
* Return a tag name
*
* @param  int    $id - tag id
* @return string     - tag name
*/
function TAG_getTagName($id) {
	global $_TABLES;
	
	$sql = "SELECT tag FROM {$_TABLES['tag_list']} WHERE (tag_id = '"
		 . addslashes($id) . "')";
	$result = DB_query($sql);
	if (!DB_error() AND DB_numRows($result) == 1) {
		$A = DB_fetchArray($result);
		return $A['tag'];
	} else {
		return '';
	}
}

/**
* Save a tag into tag list
*
* @param $str - a normalized tag
*/
function TAG_saveTagToList($tag) {
	global $_TABLES;
	
	$tag = trim($tag);
	if (empty($tag) === true OR TAG_getTagId($tag) !== false) {
		return true;
	}
	
	$A = DB_fetchArray($result);
	if ($A['cnt'] == 0) {
		$sql = "INSERT INTO {$_TABLES['tag_list']} (tag) "
			 . "VALUES ('" . addslashes($tag) . "')";
		$result = DB_query($sql);
		return (DB_error() == '');
	} else {
		return true;
	}
}

/**
* Save a tag into tag map
*
* @param $str - a normalized tag
*/
function TAG_saveTagToMap($tag, $id, $type) {
	global $_TABLES;
	
	$tag = trim($tag);
	if (empty($tag) === true) {
		return true;
	} else {
		$tag_id = TAG_getTagId($tag);
	}
	
	$sql = "SELECT COUNT(*) AS cnt FROM {$_TABLES['tag_map']} "
		 . "WHERE (tag_id = '" . addslashes($tag_id) . "') AND "
		 . "(sid = '" . addslashes($id) . "') AND "
		 . "(type = '" . addslashes($type) . "')";
	$result = DB_query($sql);
	if (DB_error()) {
		return false;
	}
	
	$A = DB_fetchArray($result);
	if ($A['cnt'] == 0) {
		$sql = "INSERT INTO {$_TABLES['tag_map']} (tag_id, type, sid) "
			 . "VALUES ('" . addslashes($tag_id) . "', '" . addslashes($type)
			 . "', '" . addslashes($id) . "')";
		$result = DB_query($sql);
		return (DB_error() == '');
	} else {
		return true;
	}
	
}

/**
* Scan a text and return an array of tags
*
* @param  string $str - a text to search
* @return array of string - normalized tag(s)
*/
function TAG_scanTag($str) {
	global $_TAG_CONF;
	
	$retval = array();
	
	$pattern = '|\[' . $_TAG_CONF['tag_name'] . ':([^\]]*)\]|i';
	if (preg_match_all($pattern, $str, $match, PREG_SET_ORDER)) {
		foreach ($match as $M) {
			$parts = TAG_split($M[1]);
			
			foreach ($parts as $part) {
				$part = TAG_normalize($part);
				if (TAG_checkBadword($part) === true) {
					continue;
				} else {
					if ($_TAG_CONF['tag_stemming'] === true) {
						$part = TAG_stem($part);
					}
				}
				
				if ($part != '' AND strlen($part) <= $_TAG_CONF['max_tag_len']) {
					$retval[] = $part;
				}
			}
		}
		
		$retval = array_unique($retval);
	}
	
	return $retval;
}

/**
* Return if a word is "bad" or not
*
* @param  string $word - a normalized word to check
* @return boolean - true = bad, false = not bad
*/
function TAG_isBadword($word) {
	global $_TABLES;
	
	static $badwords = null;
	static $loaded = false;
	
	if (!$loaded) {
		$badwords = array();
		
		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);
		if (!DB_error() AND DB_numRows($result) > 0) {
			while (($A = DB_fetchArray($result)) !== false) {
				$badwords[] = $A['badword'];
			}
		}
		
		$loaded = true;
	}
	
	return array_search($word, $badwords);
}

/**
* Call back function invoked when an item is being saved
*/
function plugin_itemsaved_tag($id, $type) {
	global $_TABLES;
	
	if ($type == 'article') {
		$sql = "SELECT introtext, bodytext FROM {$_TABLES['stories']} "
			 . "WHERE (sid = '" . addslashes($id) . "')";
		$result = DB_query($sql);
		$A = DB_fetchArray($result);
		$introtext = stripslashes($A['introtext']);
		$bodytext  = stripslashes($A['bodytext']);
		$tag1 = TAG_scanTag($introtext);
		$tag2 = TAG_scanTag($bodytext);
		$tags = array_merge($tag1, $tag2);
		$tags = array_unique($tags);
		if (count($tags) > 0) {
			foreach ($tags as $tag) {
				if (!TAG_isBadword($tag)) {
					TAG_saveTagToList($tag);
					TAG_saveTagToMap($tag, $id, $type);
				}
			}
		}
	} else {
		COM_errorLog('plugin_itemsaved_tag: $id = ' . $id . ', $type = ' . $type);
	}
	
	return false;
}

/**
* Get the level of a tag
*
* @param  int $count: number of a given tag
* @return int the corresponding level(0..9)
*/
function TAG_getLevel($count) {
	global $_TAG_CONF;
	
	$count = intval($count);
	for ($i = 0; $i <= 8; $i ++) {
		if ($count <= $_TAG_CONF['tag_cloud_threshold'][$i]) {
			return $i;
		}
	}
	
	return 9;
}

/**
* Get a tag cloud
*
* @param int     $limit: max number of clouds
* @param boolean $is_block: true = for site block,
*                           false = for public_html/tag/index.php
*/
function TAG_getTagCloud($limit = 0, $is_block = false) {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$sql = "SELECT t.tag_id, t.tag, COUNT(t.tag_id) AS cnt "
		 . "FROM {$_TABLES['tag_map']} AS m "
		 . "LEFT JOIN {$_TABLES['tag_list']} AS t "
		 . "ON t.tag_id = m.tag_id "
		 . "GROUP BY t.tag_id "
		 . "ORDER BY cnt DESC ";
	if ($limit > 0) {
		$sql .= "LIMIT {$limit}";
	}
	$result = DB_query($sql);
	if (DB_error()) {
		return '';
	}
	
	$list = array();
	
	while (($A = DB_fetchArray($result)) !== false) {
		$list[$A['tag']] = array('tag_id' => $A['tag_id'], 'cnt' => $A['cnt']);
	}
	
	ksort($list, SORT_STRING);
	
	$T = new Template($_CONF['path'] . 'plugins/tag/templates');
	$T->set_file(
		array(
			'container' => 'tagcloud_container.thtml',
			'item'      => 'tagcloud_item.thtml',
		)
	);
	
	$items = '';
	
	foreach ($list as $tag => $data) {
		$rel   = rawurlencode($tag);
		$title = TAG_escape($tag);
		$level = TAG_getLevel($data['cnt']);
		$url   = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag=' . $rel);
		$T->set_var('level', $level);
		$T->set_var('url', $url);
		$T->set_var('rel', $rel);
		$T->set_var('title', $title);
		$T->parse('output', 'item');
		$items .= $T->finish($T->get_var('output')) . $_TAG_CONF['tag_cloud_spacer'];
	}
	
	$T->set_var('container_id', $is_block ? 'b_tag_cloud_container' : 'tag_cloud_container');
	$T->set_var('items', $items);
	$T->parse('output', 'container');
	
	return $T->finish($T->get_var('output'));
}

/**
* Return "Related tags" formatted in HTML
*/
function TAG_formatRelatedTags($all_tags, $tag) {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	$retval = '';
	sort($all_tags);
	
	foreach ($all_tags as $t) {
		if ($t != $tag) {
			$url  = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag='
				  . rawurlencode($t));
			$text = TAG_escape($t);
			$retval .= '<a href="' . $url . '" rel="' . $text . '">' . $text . '</a>  ';
		}
	}
	
	return $retval;
}

/**
* Return items with a given tag
*/
function TAG_getTaggedItems($tag) {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	$retval = '';
	
	$sql = "SELECT type, sid FROM {$_TABLES['tag_map']} "
		 . "WHERE (tag_id = '" . addslashes(TAG_getTagId($tag)) . "')";
	$result = DB_query($sql);
	if (DB_error()) {
		return $retval;
	}
	
	$list = array();
	
	while (($A = DB_fetchArray($result)) !== false) {
		list($type, $sid) = $A;
		$type = stripslashes($type);
		$sid  = stripslashes($sid);
		$list[] = array('type' => $type, 'sid' => $sid);
	}
	
	$T = new Template($_CONF['path'] . 'plugins/tag/templates');
	$T->set_file('item', 'tagged_item.thtml');
	
	foreach ($list as $L) {
		switch ($L['type']) {
			case 'article':
				$sql = "SELECT title, introtext, bodytext "
					 . "FROM {$_TABLES['stories']} "
					 . "WHERE (sid = '" . addslashes($L['sid']) . "') "
					 . COM_getPermSQL('AND');
				$result = DB_query($sql);
				if (DB_numRows($result) == 0) {
					continue;
				}

				$A = DB_fetchArray($result);
				$title = TAG_escape(stripslashes($A['title']));
				$body  = stripslashes($A['introtext']) . '<br>'
					   . stripslashes($A['bodytext']);
				$all_tags = TAG_scanTag($body);
				$url = COM_buildURL($_CONF['site_url'] . '/article.php?sid=' . $L['sid']);
				break;
			
			default:
				continue;
				break;
		}
		
		$T->set_var('title', $title);
		$T->set_var('url', $url);
		$T->set_var('tag', TAG_escape($tag));
		$T->set_var('related', TAG_str('related'));
		$T->set_var('related_tags', TAG_formatRelatedTags($all_tags, $tag));
		$T->parse('output', 'item');
		$retval .= $T->finish($T->get_var('output'));
	}
	
	return $retval;
}

/**
* Check if a word is bad.
*
* @note Check will be done ignoring $_TAG_CONF['tag_case_sensitive']
*/
function TAG_checkBadword($word) {
	global $_TABLES, $_TAG_CONF;
	
	static $list = null;
	static $loaded = false;
	
	if (!$loaded) {
		$list = array();
		
		$sql = "SELECT * FROM {$_TABLES['tag_badwords']}";
		$result = DB_query($sql);
		
		while (($A = DB_fetchArray($result)) !== false) {
			$list[] = $A['badword'];
		}
		
		$loaded = true;
	}
	
	if (function_exists('mb_strtolower')) {
		$word = mb_strtolower($word);
	} else {
		$word = strtolower($lower);
	}
	
	return in_array($word, $list);
}

/**
* Display a tag cloud in the site block
*
* @access public
* @param int     $limit: max number of tags to be displayed
* @return string HTML
*/
function phpblock_tag_cloud($limit = 0) {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	// Check the max number of clouds
	$limit = (int) $limit;
	if ($limit <= 0) {
		$limit = $_TAG_CONF['max_tag_cloud_in_block'];
	}
	
	return TAG_getTagCloud($limit, true);
}

/**
* Increase a hit count of a tag
*/
function TAG_increaseHitCount($tag_id) {
	global $_TABLES;
	
	$sql = "UPDATE {$_TABLES['tag_list']} SET hits = hits + 1 "
		 . "WHERE (tag_id = '" . addslashes($tag_id) . "')";
	DB_query($sql);
}

/**
* Return the title of a story
*/
function TAG_getStoryTitle($sid) {
	global $_TABLES;
	
	$sql = "SELECT title FROM {$_TABLES['stories']} "
		 . "WHERE (sid = '" . addslashes($sid) . "') "
		 . COM_getPermSQL(' AND ');
	$result = DB_query($sql);
	if (!DB_error() AND DB_numRows($result) == 1) {
		$A = DB_fetchArray($result);
		return stripslashes($A['title']);
	} else {
		return '';
	}
}

/**
* Build a tag menu
*
* @access private
*/
function TAG_build_tag_menu(&$nodes, &$node, $parent_tag_ids, $level) {
	global $_CONF;
	
	$retval = '';
	
	$tag_ids = array_merge($parent_tag_ids, $node['tag_ids']);
	$tag_ids = array_unique($tag_ids);
	sort($tag_ids);
	$tag_ids4url = implode('_', $tag_ids);
	$url = $_CONF['site_url'] . '/tag/menu.php?tag=' . $tag_ids4url;
	$url = COM_buildURL($url);
	$retval .= str_repeat('&nbsp;&nbsp;&nbsp;', $level) . '<a href="' . $url . '">'
			.  TAG_escape($node['menu_name']) . '</a><br' . XHTML . '>' . LB;
	if (count($node['child']) > 0) {
		foreach ($node['child'] as $child) {
			$retval .= TAG_build_tag_menu($nodes, $nodes[$child], $tag_ids, $level + 1);
		}
	}
	
	return $retval;
}

/**
* Display a menu using tags
*
* @access public
*/
function phpblock_tag_menu() {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	$sql = "SELECT * FROM {$_TABLES['tag_menu']} "
		 . "ORDER BY parent_id, dsp_order";
	$result = DB_query($sql);
	if (DB_error()) {
		return TAG_str('db_error');
	} else if (DB_numRows($result) == 0) {
		return TAG_str('no_menu');
	}
	
	$nodes  = array();
	$retval = '';
	
	while (($A = DB_fetchArray($result)) !== false) {
		$A['child'] = array();
		
		if (trim($A['tag_ids']) == '') {
			$A['tag_ids'] = array();
		} else {
			$A['tag_ids'] = explode(',', $A['tag_ids']);
		}
		if ($A['parent_id'] != 0) {
			$nodes[$A['parent_id']]['child'][] = $A['menu_id'];
		}
		$nodes[$A['menu_id']] = $A;
	}
	
	foreach ($nodes as $node) {
		if ($node['parent_id'] > 0) {
			break;
		}
		$retval .= TAG_build_tag_menu($nodes, $node, array(), 0);
	}
	
	$retval = '<div class="tag_menu">' . LB
			.  $retval
			.  '</div>' . LB;
	
	return $retval;
}

?>
