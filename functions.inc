<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | private/plugins/tag/functions.inc                                         |
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

/**
* Includes language file 
*/
$langfile = $_CONF['path'] . 'plugins/tag/language/' . $_CONF['language'] . '.php';

if (file_exists($langfile)) {
    include_once $langfile;
} else {
    include_once $_CONF['path'] . 'plugins/tag/language/english.php';
}

/*
* Loads config
*/
require_once $_CONF['path'] . 'plugins/tag/config.php';
TAG_loadConfig();

/**
* Includes Porter Stemmer
*/
if ($_TAG_CONF['tag_stemming'] === TRUE) {
	require_once $_CONF['path'] . 'plugins/tag/porter_stemmer.php';
}

// +---------------------------------------------------------------------------+
// | Geeklog Plugin API Implementations                                        |
// +---------------------------------------------------------------------------+

/**
* Returns the items for this plugin that should appear on the main menu
*
* @return    array   Associative array of menu items to be added to menu
*/
function plugin_getmenuitems_tag() {
	global $_CONF, $_TAG_CONF;
	
	if ($_TAG_CONF['hidetagmenu'] == FALSE) {
		$menulabel = TAG_str('admin_label');
		$menurul   = $_CONF['site_url'] . "/tag/index.php";
	    $menuitems = array();
		$menuitems[$menulabel] = $menurul;
	} else {
		$menuitems = FALSE;
	}
	
    return $menuitems;
}

/**
* Checks to see if this Plugin uses comments
*
* @return   boolean TRUE or FALSE
*/
function plugin_commentsupport_tag() {
    return FALSE;
}

/**
* This will put an option for the plugin in the command and control block on
* moderation.php
*
* @return   array   Array containing (plugin name, admin url, url of plugin icon)
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
* @return   array   Array containing (plugin name, plugin admin url, # of items
*                   in plugin or '')
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
* Upgrades this plugin
*
* @return   boolean TRUE (= success)
*/
function plugin_upgrade_tag() {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	// Version info stored in database
	$pi_version = DB_getItem($_TABLES['plugins'], 'pi_version', "(pi_name = 'tag')");
	
	// Do upgrading
	switch ($pi_version) {
		case '0.1.0':	// 0.1.0 --> 0.2.0
			$sql = "CREATE INDEX idx_tag_list_tag ON {$_TABLES['tag_list']} (tag)";
			DB_query($sql);
			$sql = "CREATE INDEX idx_tag_map_tag_id ON {$_TABLES['tag_map']} (tag_id)";
			DB_query($sql);
			/* Fall through into the next case */
			
		case '0.2.0':	// 0.2.0 --> 0.3.0
			/* Fall through into the next case */
		
		case '0.3.0':	// 0.3.0 --> 0.3.1
		case '0.3.1':
		case '0.3.2':
		    /**
		    * If the version of Tag plugin is lower than 0.4.0 AND Geeklog version
			* is 1.5 or newer, then save config values into DB with config.class.php.
		    */
		    if ((version_compare($pi_version, '0.4.0') < 0)
			 AND (version_compare(VERSION, '1.5') >= 0)) {
		        require_once $_CONF['path'] . 'plugins/tag/install_defaults.php';
		        plugin_initconfig_tag();
		    }
			
			/* Fall through into the next case */
		
		case '0.4.1':
			require_once $_CONF['path_system'] . 'classes/config.class.php';
	        require_once $_CONF['path'] . 'plugins/tag/install_defaults.php';
			
		    $c = config::get_instance();
	        $c->add('hidetagmenu', $_TAG_DEFAULT['hidetagmenu'], 'select', 0, 0, 0, 5, TRUE, 'tag');
			
			/* Fall through into the next case */
			
		case '0.5.0':
		case '0.5.1':
			require_once $_CONF['path_system'] . 'classes/config.class.php';
	        require_once $_CONF['path'] . 'plugins/tag/install_defaults.php';
			
		    $c = config::get_instance();
			$c->add('tag_cloud_threshold_max_count', $_TAG_DEFAULT['tag_cloud_threshold_max_count'], 'text', 0, 0, NULL, 105, TRUE, 'tag');
			break;
		
		default:
			break;
	}
	
	// Updates plugin info
	$sql = "UPDATE {$_TABLES['plugins']} "
		 . "  SET pi_version = '" . addslashes($_TAG_CONF['pi_version']) . "' "
		 . "  WHERE (pi_name = 'tag')";
	$result = DB_query($sql);
	COM_errorLog("Tag plugin was successfully updated to version {$_TAG_CONF['pi_version']}.");
	
	return TRUE;
}

/**
* Automatic uninstall function for plugins
*
* @return   array
*/
function plugin_autouninstall_tag() {
	global $_TAG_CONF;
	
	$out = array (
		/* give the name of the tables, without $_TABLES[] */
		'tables'     => array('tag_list', 'tag_map', 'tag_badwords', 'tag_menu'),
		/* give the full name of the group, as in the db */
		'groups'     => array('Tag Admin'),
		/* give the full name of the feature, as in the db */
		'features'   => array('tag.admin'),
		/* give the full name of the block, including 'phpblock_', etc */
		'php_blocks' => array('phpblock_tag_cloud', 'phpblock_tag_menu'),
		/* give all vars with their name */
		'vars'       => array()
	);
	
	return $out;
}

/**
* Appends a link to CSS file and <meta name="keywords" content="foo,bar"> tag
* to <head> section
*/
function plugin_getheadercode_tag() {
	global $_CONF, $_USER, $_TAG_CONF;
	
	if (!defined('XHTML')) {
		define('XHTML', '');
	}
	
	$theme = isset($_USER['theme']) ? $_USER['theme'] : $_CONF['theme'];
	clearstatcache();
	
	if (file_exists($_CONF['path_themes'] . $theme . '/tag.css')) {
		$url = $_CONF['site_url'] . '/layout/' . $theme . '/tag.css';
	} else {
		$url = $_CONF['site_url'] . '/tag/tag.css';
	}
	
	$retval = '<link rel="stylesheet" type="text/css" href="' . $url . '"' . XHTML . '>';
	
	if ($_TAG_CONF['num_keywords'] > 0) {
		$keywords = TAG_getKeywords($_TAG_CONF['num_keywords']);
		
		if (count($keywords) > 0) {
			$keywords = array_map('TAG_escape', $keywords);
			$retval .= LB
					.  "\t" . '<meta name="keywords" content="'
					.  implode(',', $keywords) . '"' . XHTML . '>';
		}
	}
	
	return $retval;
}

/**
* Enable/Disable a default tag cloud block when tag plugin is being
* enabled/disabled (for Geeklog-1.4.1 or later)
*
* @param boolean $state - TRUE if enabling, FALSE if disabling
*/
function plugin_enablestatechange_tag($state) {
	global $_TABLES, $_TAG_CONF;
	
	$state = ($state === TRUE) ? 1 : 0;
	$sql = "UPDATE {$_TABLES['blocks']} "
		 . "SET is_enabled = '" . addslashes($state) . "' "
		 . "WHERE (name = '" . addslashes($_TAG_CONF['default_block_name']) . "') "
		 . "OR (name = '" . addslashes($_TAG_CONF['default_block_name_menu']) . "')";
	DB_query($sql);
}

/**
* Add template vars to 'storytext.thtml', 'featuredstorytext.thtml' and
* 'archivestorytext.thtml'.
*
* Vars available in templates are {tag_label} and {tag_part}.
*/
if ($_TAG_CONF['publish_as_template_vars'] === TRUE) {
	function plugin_templatesetvars_tag($templatename, &$template) {
		global $_TAG_CONF;
		
		if (in_array($templatename, array('featuredstorytext', 'storytext', 'archivestorytext'))) {
			$template->set_var('tag_label', $_TAG_CONF['template_vars']['tag_label']);
			$template->set_var('tag_part', $_TAG_CONF['template_vars']['tag_part']);
		}
	}
}

/**
* Loads configuration
*/
function TAG_loadConfig() {
	global $_CONF, $_TAG_CONF;
	
    require_once $_CONF['path_system'] . 'classes/config.class.php';
	
    $config = config::get_instance();
	$config_data = $config->get_config('tag');
	
	if (is_array($config_data) AND (count($config_data) > 0)) {
    	$_TAG_CONF = array_merge($_TAG_CONF, $config_data);
	}
}

/**
* Strips slashes when magic_quotes_gpc is on
*/
function TAG_stripSlashes($var) {
	if (is_array($var)) {
		return array_map(__FUNCTION__, $var);
	} else {
		return COM_stripslashes($var);
	}
}

/**
* Retrieves a Get var
*/
function TAG_get($name, $is_num = FALSE) {
	if (isset($_GET[$name])) {
		return COM_applyFilter(TAG_stripSlashes($_GET[$name]), $is_num);
	} else {
		return FALSE;
	}
}

/**
* Retrieves a Post var
*/
function TAG_post($name, $is_token = FALSE, $is_num = FALSE) {
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
		return FALSE;
	}
}

/**
* Escapes a string for displaying in HTML
*/
function TAG_escape($str) {
	static $charset = NULL;
	
# 	// Unescape a string
# 	$str = str_replace(
# 		array('&lt;', '&gt;', '&amp;', '&quot:', '&#039;'),
# 		array(   '<',    '>',     '&',      '"',      "'"),
# 		$str
# 	);
	
	if ($charset === NULL) {
		$charset = COM_getCharset();
	}
	
	return htmlspecialchars($str, ENT_QUOTES, $charset);
}

/**
* Returns an escaped language-specific message
*/
function TAG_str($msg) {
	global $LANG_TAG;
	
	if (isset($LANG_TAG[$msg])) {
		return TAG_escape($LANG_TAG[$msg]);
	} else {
		COM_errorLog(__FUNCTION__ . ': Undefined lang var "' . $msg . '"');
		return '(undefined)';
	}
}

/**
* Splits a string acccoding to $LANG_TAG['tag_separators']
*/
function TAG_split($str) {
	global $LANG_TAG;
	
	$pattern = preg_quote($LANG_TAG['tag_separators']);
	$pattern = '[' . $pattern . ']+';
	
	if (function_exists('mb_split')) {
		if (function_exists('mb_regex_encoding')) {
			mb_regex_encoding(COM_getCharset());
		}
		
		$retval = mb_split($pattern, $str);
	} else {
		$retval = split($pattern, $str);
	}
	
	return $retval;
}

/**
* Stems a string using Porter Stemming algorithm
*
* @param  string $str - a normalized word
* @return string      - a stemmed word
*/
function TAG_stem($str) {
	global $_TAG_CONF;
	
	static $porterStemmer = null;
	
	$encoding = COM_getCharset();
	
	if (!is_object($porterStemmer)) {
		$porterStemmer = new PorterStemmer();
	}
	
	$retval = '';
	
	if (function_exists('mb_strlen')) {
		/**
		* Stem English part
		*/
		$i    = 0;
		$len  = mb_strlen($str, $encoding);
		$temp = $str;
		$str  = '';
		$word = '';
		
		while ($i < $len) {
			$ch = mb_substr($temp, $i, 1, $encoding);
			if (mb_strwidth($ch, $encoding) == 1) {
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
* Normalizes a string
*/
function TAG_normalize($str) {
	global $_TAG_CONF;
	
	$encoding = COM_getCharset();
	
	if (function_exists('mb_convert_kana')) {
		/**
		* Converts Zenkaku alphabet and number letters into Hankaku, and
		* Hankaku Katakana into Zenkaku Katakana.  We distinguish between
		* Hiragana and Katakana.
		*/
		$str = mb_convert_kana($str, 'aKV', $encoding);
		
		if ($_TAG_CONF['tag_case_sensitive'] === FALSE) {
			$str = mb_strtolower($str, $encoding);
		}
		
		if (mb_strwidth($str, $encoding) > $_TAG_CONF['max_tag_len']) {
			$str = mb_strcut($str, 0, $_TAG_CONF['max_tag_len'], $encoding);
		}
	} else {
		if ($_TAG_CONF['tag_case_sensitive'] === FALSE) {
			$str = strtolower($str);
		}
		
		if (strlen($_TAG_CONF['max_tag_len']) > $_TAG_CONF['max_tag_len']) {
			$str = substr($str, 0, $_TAG_CONF['max_tag_len']);
		}
	}
	
	return $str;
}

/**
* Replaces [tag:foo] tags
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
				
				if (TAG_isBadword($tag) === TRUE) {
					$tag_part .= TAG_str('badword_replace') . ' ';
				} else {
					if ($_TAG_CONF['tag_stemming'] === TRUE) {
						$tag = TAG_stem($tag);
					}
					
					$text = $tag;
					
					if ($_TAG_CONF['replace_underscore'] === TRUE) {
						$text = str_replace('_', ' ', $text);
					}
					
					$url = $_CONF['site_url'] . '/tag/index.php?tag='
						 . rawurlencode($tag);
					$tag_part .= '<a class="tag_link" href="' . COM_buildURL($url)
							  .  '">' .  TAG_escape($text) . '</a> ';
				}
			}
			
			$tag_part = rtrim($tag_part);
			
			// Sets work vars to be included in templates
			$_TAG_CONF['template_vars']['tag_part']  = $tag_part;
			$_TAG_CONF['template_vars']['tag_label'] = $LANG_TAG['display_label'];

			if ($_TAG_CONF['publish_as_template_vars'] === TRUE) {
				// Replaces a tag part with a null string
				$content = str_replace($tagstr, '', $content);
			} else {
				if ($tag_part != '') {
					$content = str_replace(
						$tagstr, $LANG_TAG['display_label'] . $tag_part, $content
					);
				}
			}
		}
	}
	
	return $content;
}

function plugin_getdocumentationurl_tag($file) {
	global $_CONF;
	
	static $docurl = NULL;
	
	switch ($file) {
		case 'index':
		case 'config':
			if ($docurl === NULL) {
				$lang = COM_getLanguageName();
				
				switch ($lang) {
					case 'english':
						$lang = 'en';
						break;
					
					case 'japanese':
						$lang = '_ja';
						break;
					
					default:
						$lang = 'en';
						break;
				}
				
				$doc = 'install' . $lang . '.html';
				
				if (file_exists($_CONF['path_html'] . 'admin/plugins/tag/' . $doc)) {
					$retval = $_CONF['site_admin_url'] . '/plugins/tag/' . $doc;
				} else {
					$retval = $_CONF['site_admin_url'] . '/plugins/tag/install.html';
				}
				
				$docurl = $retval;
			}
			
			break;
		
		default:
			$retval = FALSE;
			break;
	}
	
	return $retval;
}

/**
* Returns a tag id of a given tag
*
* @param   string  $tag  a normalized tag
* @return  int           a tag id
*/
function TAG_getTagId($tag) {
	global $_TABLES;
	
	static $list = array();
	
	if (isset($list[$tag])) {
		return $list[$tag];
	}
	
	$tag_id = DB_getItem(
		$_TABLES['tag_list'], 'tag_id', "(tag = '" . addslashes($tag) . "')"
	);
	
	if (DB_error() OR ($tag_id == '')) {
		return FALSE;
	} else {
		$list[$tag] = (int) $tag_id;
		
		return $tag_id;
	}
}

/**
* Returns a tag name
*
* @param   int     $tag_id  a tag id
* @return  string           the tag name
*/
function TAG_getTagName($tag_id) {
	global $_TABLES;
	
	$tag = DB_getItem(
		$_TABLES['tag_list'], 'tag', "(tag_id = '" . addslashes($tag_id) . "')"
	);
	
	if (DB_error() OR ($tag == '')) {
		return '';
	} else {
		return $tag;
	}
}

/**
* Saves a tag into tag list
*
* @param   string  $tag  a normalized tag
* @return  (void)
*/
function TAG_saveTagToList($tag) {
	global $_TABLES;
	
	$tag = trim($tag);
	
	if (($tag != '') AND (TAG_getTagId($tag) == '')) {
		$sql = "INSERT INTO {$_TABLES['tag_list']} (tag) "
			 . "  VALUES ('" . addslashes($tag) . "')";
		$result = DB_query($sql);
	}
}

/**
* Save a tag into tag map
*
* @param   string  $tag   a normalized tag
* @param   string  $id    the item id  (e.g.  sid, sp_id)
* @param   string  $type  item type  currently 'article' and 'staticpages' are
*                         supported.
* @return  (void)
*/
function TAG_saveTagToMap($tag, $id, $type) {
	global $_TABLES;
	
	$tag = trim($tag);
	
	if ($tag != '') {
		$tag_id = TAG_getTagId($tag);
		
		$count = DB_getItem(
			$_TABLES['tag_map'],
			"COUNT(*) AS cnt ",
			"(tag_id = '" . addslashes($tag_id) . "') AND " . " (sid = '" . addslashes($id) . "') AND " . " (type = '" . addslashes($type) . "')"
		);
		
		if (!DB_error() AND ($count == 0)) {
			$sql = "INSERT INTO {$_TABLES['tag_map']} (tag_id, type, sid) "
				 . "VALUES ('" . addslashes($tag_id) . "', '" . addslashes($type)
				 . "', '" . addslashes($id) . "')";
			$result = DB_query($sql);
		}
	}
}

/**
* Scans a text for tags and returns an array of tags
*
* @param   string  $str     a text to search
* @return  array of string  normalized tag(s)
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
				if (TAG_isBadword($part) === TRUE) {
					continue;
				} else {
					if ($_TAG_CONF['tag_stemming'] === TRUE) {
						$part = TAG_stem($part);
					}
				}
				
				if (($part != '') AND (strlen($part) <= $_TAG_CONF['max_tag_len'])) {
					$retval[] = $part;
				}
			}
		}
		
		$retval = array_unique($retval);
	}
	
	return $retval;
}

/**
* Returns if a word is "bad" or not
*
* @param   string  $word  a normalized word to check
* @return  boolean        TRUE = bad, FALSE = not bad
* @note                   This check will be done ignoring
*                         $_TAG_CONF['tag_case_sensitive']
*/
function TAG_isBadword($word) {
	global $_TABLES;
	
	static $badwords = null;
	
	if ($badwords === NULL) {
		$badwords = array();
		
		$sql = "SELECT * "
			 . "  FROM {$_TABLES['tag_badwords']} ";
		$result = DB_query($sql);
		
		if (!DB_error() AND (DB_numRows($result) > 0)) {
			while (($A = DB_fetchArray($result)) !== FALSE) {
				$badwords[] = $A['badword'];
			}
		}
	}
	
	if (function_exists('mb_strtolower')) {
		$word = mb_strtolower($word, COM_getCharset());
	} else {
		$word = strtolower($lower);
	}
	
	return in_array($word, $badwords);
}

/**
* Call back function invoked when an item is being saved
*
* @param   string  $id      an identifier of a given item
* @param   string  $type    the type of a given item.  Currently, 'article' and
*                           'staticpages' (since tag-0.5.0) are supported.
* @param   string  $old_id  old ID when the ID was changed (since tag-0.5.0)
* @return  boolean
*/
function plugin_itemsaved_tag($id, $type, $old_id = '') {
	global $_TABLES;
	
	switch ($type) {
		case 'article':
			// Deletes old records from tag_map
			$sid = ($old_id == '') ? $id : $old_id;
			$sql = "DELETE FROM {$_TABLES['tag_map']} "
				 . "WHERE (type = '" . addslashes($type) . "') AND (sid = '"
				 . addslashes($sid) . "')";
			DB_query($sql);
			
			// Retrieves article data and scan for tags
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
			
			break;
		
		case 'staticpages':
			// Deletes old records from tag_map
			$sid = ($old_id == '') ? $id : $old_id;
			$sql = "DELETE FROM {$_TABLES['tag_map']} "
				 . "WHERE (type = '" . addslashes($type) . "') AND (sid = '"
				 . addslashes($sid) . "')";
			DB_query($sql);
			
			// Retrieves article data and scan for tags
			$sql = "SELECT sp_content "
				 . "FROM {$_TABLES['staticpage']} "
				 . "WHERE (sp_id = '" . addslashes($id) . "') ";
			$result = DB_query($sql);
			$A = DB_fetchArray($result);
			$content = stripslashes($A['sp_content']);
			$tags = TAG_scanTag($content);
			$tags = array_unique($tags);
			
			if (count($tags) > 0) {
				foreach ($tags as $tag) {
					if (!TAG_isBadword($tag)) {
						TAG_saveTagToList($tag);
						TAG_saveTagToMap($tag, $id, $type);
					}
				}
			}
			
			break;
		
		default:
			COM_errorLog(__FUNCTION__ . ': $id = ' . $id . ', $type = ' . $type);
			break;
	}
	
	return FALSE;
}

/**
* Gets the level of a tag.  Once $count reaches the max level
* (= $_TAG_CONF['tag_cloud_threshold'][9]), then we use use percentages based
* on the thresholds.
*
* @param   int  $count  the number of a given tag
* @return  int          the corresponding level(0..9)
*/
function TAG_getLevel($count) {
	global $_TABLES, $_TAG_CONF;
	
	$count = intval($count);
	$total_tag_count = DB_getItem($_TABLES['tag_map'], 'COUNT(map_id) AS cnt', '');
	
	if ($total_tag_count > 0) {
		if ($total_tag_count <= $_TAG_CONF['tag_cloud_threshold_max_count']) {
			/**
			* Old way: we use thresholds based on the set number of tags.  We
			* might as well add a switch here to use this as percentages,
			* though it really would not when there are only a few tags in the
			* system.
			*/
			for ($i = 0; $i <= 9; $i ++) {
				if ($count <= $_TAG_CONF['tag_cloud_threshold'][$i]) {
					return $i;
				}
			}
			
			return 9;
		} else {
			/**
			* New way: we use thresholds based on percentages.
			*/
			$percentage = intval(100.0 * ($count / $total_tag_count));
			
			for ($i = 0; $i <= 9; $i ++) {
				if ($percentage <= $_TAG_CONF['tag_cloud_threshold'][$i]) {
					return $i;
				}
			}
			
			return 9;
		}
	}
	
	return 0;
}

/**
* Gets a tag cloud
*
* @param   int      $limit     max number of clouds
* @param   boolean  $is_block  TRUE = for site block, FALSE =
*                              for public_html/tag/index.php
* @return  string              HTML text representing a tag cloud
*/
function TAG_getTagCloud($limit = 0, $is_block = FALSE) {
	global $_CONF, $_TABLES, $_TAG_CONF;

	$sql = "SELECT t.tag_id, t.tag, COUNT(t.tag_id) AS cnt "
		 . "  FROM {$_TABLES['tag_map']} AS m "
		 . "    LEFT JOIN {$_TABLES['tag_list']} AS t "
		 . "    ON t.tag_id = m.tag_id "
		 . "  GROUP BY t.tag_id "
		 . "  ORDER BY cnt DESC ";
	
	if ($limit > 0) {
		$sql .= "LIMIT {$limit}";
	}
	
	$result = DB_query($sql);
	
	if (DB_error()) {
		return '';
	}
	
	$list = array();
	
	while (($A = DB_fetchArray($result)) !== FALSE) {
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
		$title = $tag;
		
		if ($_TAG_CONF['replace_underscore'] === TRUE) {
			$title = str_replace('_', ' ', $title);
		}
		
		$title = TAG_escape($title);
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
* Returns "Related tags" formatted in HTML
*/
function TAG_formatRelatedTags($all_tags, $tag) {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	$retval = '';
	
	if (is_array($all_tags) AND (count($all_tags) > 0)) {
		sort($all_tags);
		
		foreach ($all_tags as $t) {
			if ($t != $tag) {
				$url  = COM_buildURL($_CONF['site_url'] . '/tag/index.php?tag='
					  . rawurlencode($t));
				$text = $t;
				
				if ($_TAG_CONF['replace_underscore'] === TRUE) {
					$text = str_replace('_', ' ', $text);
				}
				
				$text = TAG_escape($text);
				$retval .= '<a href="' . $url . '" rel="' . $text . '">' . $text . '</a>  ';
			}
		}
	}
	
	return $retval;
}

/**
* Returns items with a given tag
*/
function TAG_getTaggedItems($tag) {
	global $_CONF, $_TABLES, $_PLUGINS, $_TAG_CONF;
	
	$retval = '';
	
	$sql = "SELECT type, sid FROM {$_TABLES['tag_map']} "
		 . "WHERE (tag_id = '" . addslashes(TAG_getTagId($tag)) . "')";
	$result = DB_query($sql);
	
	if (DB_error()) {
		return $retval;
	}
	
	$list = array();
	
	while (($A = DB_fetchArray($result)) !== FALSE) {
		$list[] = $A;
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
				
				$num_rows = DB_numRows($result);
				if ($num_rows == 0) {
					continue;
				}

				$A = DB_fetchArray($result);
				$title = TAG_escape(stripslashes($A['title']));
				$body  = stripslashes($A['introtext']) . '<br' . XHTML . '>'
					   . stripslashes($A['bodytext']);
				$all_tags = TAG_scanTag($body);
				$url = COM_buildURL($_CONF['site_url'] . '/article.php?story=' . $L['sid']);
				break;
			
			case 'staticpages':
				if (!in_array('staticpages', $_PLUGINS)) {
					continue;
				}
				
				$sql = "SELECT sp_title, sp_content "
					 . "FROM {$_TABLES['staticpage']} "
					 . "WHERE (sp_id = '" . addslashes($L['sid']) . "') "
					 . COM_getPermSQL('AND');
				$result = DB_query($sql);
				
				$num_rows = DB_numRows($result);
				if ($num_rows == 0) {
					continue;
				}
				
				$A = DB_fetchArray($result);
				$title = TAG_escape(stripslashes($A['sp_title']));
				$content  = stripslashes($A['sp_content']);
				$all_tags = TAG_scanTag($content);
				$url = COM_buildURL($_CONF['site_url'] . '/staticpages/index.php?page=' . $L['sid']);
				break;
			
			default:
				continue;
				break;
		}
		
		if ($num_rows != 0) {
			$T->set_var('title', $title);
			$T->set_var('url', $url);
			$T->set_var('tag', TAG_escape($tag));
			$T->set_var('related', TAG_str('related'));
			$T->set_var('related_tags', TAG_formatRelatedTags($all_tags, $tag));
			$T->parse('output', 'item');
			$retval .= $T->finish($T->get_var('output'));
		}
	}
	
	return $retval;
}

/**
* Selects most frequent tags as key words
*
* @todo                     some kind of caching mechanism will be useful
*
* @param   int              $num_keywords  number of key words to be included in <head>
* @return  array of string
*/
function TAG_getKeywords($num_keywords) {
	global $_TABLES;
	
	$retval = array();
	
	$num_keywords = intval($num_keywords);
	
	if (($num_keywords < 0) OR ($num_keywords > 100)) {
		$num_keywords = 10;
	}
	
	$sql = "SELECT L.tag, COUNT(m.tag_id) AS cnt "
		 . "  FROM {$_TABLES['tag_map']} AS m "
		 . "    LEFT JOIN {$_TABLES['tag_list']} AS L "
		 . "    ON m.tag_id = L.tag_id "
		 . "  GROUP BY m.tag_id "
		 . "  ORDER BY cnt DESC "
		 . "  LIMIT {$num_keywords}";
	$result = DB_query($sql);
	
	if (!DB_error()) {
		while (($A = DB_fetchArray($result)) !== FALSE) {
			$retval[] = $A['tag'];
		}
	}
	
	return $retval;
}

/**
* Scans all stories for tags
*
* @note  This is a very costly process, and should be used sparingly
*/
function TAG_scanAllStories() {
	global $_TABLES;
	
	$sql = "SELECT sid, introtext, bodytext "
		 . "  FROM {$_TABLES['stories']}";
	$result = DB_query($sql);
	
	if (!DB_error()) {
		$tags = array();
		
		while (($A = DB_fetchArray($result)) !== FALSE) {
			$tags_in_introxtext = TAG_scanTag(stripslashes($A['introtext']));
			$tags_in_bodytext   = TAG_scanTag(stripslashes($A['bodytext']));
			$temp = array_merge($tags_in_introxtext, $tags_in_bodytext);
			if (count($temp) > 0) {
				$tags[$A['sid']] = $temp;
			}
		}
		
		if (count($tags) > 0) {
			foreach ($tags as $sid => $tag) {
				foreach ($tag as $t) {
					TAG_saveTagToList($t);
					TAG_saveTagToMap($t, $sid, 'article');
				}
			}
		}
	}
}

/**
* Scans all staticpages for tags
*
* @note  This is a very costly process, and should be used sparingly
*/
function TAG_scanAllStaticpages() {
	global $_PLUGINS, $_TABLES;
	
	if (!in_array('staticpages', $_PLUGINS)) {
		return;
	}
	
	$sql = "SELECT sp_id, sp_content "
		 . "  FROM {$_TABLES['staticpage']}";
	$result = DB_query($sql);
	
	if (!DB_error()) {
		$tags = array();
		
		while (($A = DB_fetchArray($result)) !== FALSE) {
			$temp = TAG_scanTag(stripslashes($A['sp_content']));
			
			if (count($temp) > 0) {
				$tags[$A['sp_id']] = $temp;
			}
		}
		
		if (count($tags) > 0) {
			foreach ($tags as $sid => $tag) {
				foreach ($tag as $t) {
					TAG_saveTagToList($t);
					TAG_saveTagToMap($t, $sid, 'staticpages');
				}
			}
		}
	}
}

/**
* Scans all types of contents supported by the Tag plugin
*/
function TAG_scanAll() {
	TAG_scanAllStories();
	TAG_scanAllStaticpages();
}

/**
* Returns the menu list
*
* @return array of (
*             int          menu_id,
*             string       menu_name,
*             array of int tag_ids(array of tag ids),
*             int          parent_id,
*             int          dsp_order,
*             array of int child (array of menu_ids)
*         )
*/
function TAG_getMenuList() {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	$nodes = array();
	$sql = "SELECT * "
		 . "  FROM {$_TABLES['tag_menu']} "
		 . "  ORDER BY parent_id, dsp_order";
	$result = DB_query($sql);
	
	if (DB_error() OR (DB_numRows($result) == 0)) {
		return $nodes;
	}
	
	while (($A = DB_fetchArray($result)) !== FALSE) {
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
	
	return $nodes;
}

/**
* Increases a hit count of a tag
*/
function TAG_increaseHitCount($tag_id) {
	global $_TABLES;
	
	$sql = "UPDATE {$_TABLES['tag_list']} "
		 . "  SET hits = hits + 1 "
		 . "  WHERE (tag_id = '" . addslashes($tag_id) . "')";
	DB_query($sql);
}

/**
* Returns the title of a story
*/
function TAG_getStoryTitle($sid) {
	global $_TABLES;
	
	$title = DB_getItem(
		$_TABLES['stories'],
		'title',
		"(sid = '" . addslashes($sid) . "') " . COM_getPermSQL('AND')
	);
	
	if (!DB_error() AND ($title != '')) {
		return stripslashes($title);
	} else {
		return '';
	}
}

/**
* Returns the number of items which include all the tag ids
*
* @param   array of int  $tag_ids  array of tag ids
* @return  string                  the number of items
*/
function TAG_getNumItems($tag_ids) {
	global $_TABLES;
	
	$sql = "SELECT COUNT(sid) AS cnt "
		 . "  FROM {$_TABLES['tag_map']} "
		 . "  WHERE (tag_id IN ('" . implode("','", array_map('addslashes', $tag_ids))
		 . "')) "
		 . "  GROUP BY sid "
		 . "  HAVING cnt = " . count($tag_ids);
	$result = DB_query($sql);
	
	if (DB_error()) {
		return 'N/A';
	} else {
		return COM_numberFormat(DB_numRows($result));
	}
}

/**
* Builds a tag menu
*
* @access  private
*/
function TAG_buildTagMenu(&$nodes, &$node, $parent_tag_ids, $level) {
	global $_CONF, $_TAG_CONF;
	
	$retval = '';
	
	if (!defined('XHTML')) {
		define('XHTML', '');
	}
	
	$tag_ids = array_merge($parent_tag_ids, $node['tag_ids']);
	$tag_ids = array_unique($tag_ids);
	sort($tag_ids);
	$tag_ids4url = implode('_', $tag_ids);
	$url  = $_CONF['site_url'] . '/tag/menu.php?tag=' . $tag_ids4url;
	$text = $node['menu_name'];
	
	if ($_TAG_CONF['replace_underscore'] === TRUE) {
		$text = str_replace('_', ' ', $text);
	}
	
	$retval .= str_repeat($_TAG_CONF['menu_indenter'], $level)
			.  '<a class="bl_tag_menu_item" href="' . COM_buildURL($url) . '">'
			. TAG_escape($text) . '</a>';
	
	// Adds the number of items
	if ($_TAG_CONF['add_num_items_to_menu'] === TRUE) {
		$retval .= ' (' . TAG_getNumItems($tag_ids) . ')';
	}
	
	$retval .= '<br' . XHTML . '>' . LB;
	
	// Builds child items recursively
	foreach ($node['child'] as $child) {
		$retval .= TAG_buildTagMenu($nodes, $nodes[$child], $tag_ids, $level + 1);
	}
	
	return $retval;
}

/**
* Displays a tag cloud in the site block
*
* @access public
* @param int     $limit: max number of tags to be displayed
* @return string HTML
*/
function phpblock_tag_cloud($limit = 0) {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	// Checks the max number of clouds
	$limit = (int) $limit;
	
	if ($limit <= 0) {
		$limit = $_TAG_CONF['max_tag_cloud_in_block'];
	}
	
	return TAG_getTagCloud($limit, TRUE);
}

/**
* Displays a menu using tags
*
* @access public
*/
function phpblock_tag_menu() {
	global $_CONF, $_TABLES, $_TAG_CONF;
	
	$nodes = TAG_getMenuList();
	
	if (count($nodes) == 0) {
		return '';
	}
	
	$states_index = 0;
	$retval = '<div class="bl_tag_menu">' . LB;
	
	foreach ($nodes as $node) {
		if ($node['parent_id'] > 0) {
			break;
		}
		
		$retval .= TAG_buildTagMenu($nodes, $node, array(), 0);
	}
	
	$retval .= '</div>' . LB;
	
	return $retval;
}
