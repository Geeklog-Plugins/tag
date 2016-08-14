<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/tag/menu.php                                                  |
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

require_once '../lib-common.php';

if (!in_array('tag', $_PLUGINS)) {
	COM_output(COM_refresh($_CONF['site_url'] . '/index.php'));
	exit;
}

// Retrieves request vars
COM_setArgNames(array('tag'));
$tag  = COM_getArgument('tag');
$tags = explode('_', COM_applyFilter($tag));

if (count($tags) === 0) {
	COM_refresh($_CONF['site_url'] . '/index.php');
	exit;
}

/**
* Display
*/
$T = new Template($_CONF['path'] . 'plugins/tag/templates');
$T->set_file('page', 'menu.thtml');
$T->set_var('xhtml', XHTML);

// Lang vars
$lang_vars = array('tag_list');

foreach ($lang_vars as $lang_var) {
	$T->set_var('lang_' . $lang_var, TAG_str($lang_var));
}

$tag_menu = array();
$sql = "SELECT type, sid, COUNT(sid) AS cnt "
	 . "  FROM {$_TABLES['tag_map']} "
	 . "WHERE (tag_id IN ('" . implode("','", array_map('addslashes', $tags)) . "')) "
	 . "GROUP BY type, sid "
	 . "HAVING cnt = '" . addslashes(count($tags)) . "'";
$result = DB_query($sql);

if (!DB_error()) {
	while (($A = DB_fetchArray($result)) !== FALSE) {
		$url   = '';
		$title = '';
		$item  = '<li><a href="';

		switch ($A['type']) {
			case 'article':
				/* Falls through to default */

			default:
				$url = COM_buildURL(
					$_CONF['site_url'] . '/article.php?story=' . TAG_escape($A['sid'])
				);
				$title = TAG_getStoryTitle($A['sid']);
				break;
		}

		if ($url === '') {
			continue;
		}

		$item .= $url . '">' . TAG_escape($title) . '</a></li>' . LB;
		$tag_menu[] = $item;
	}
}

if (count($tag_menu) > 0) {
	$tag_menu = '<ol>' . LB
			  . implode(LB, $tag_menu) . LB
			  . '</ol>' . LB;
} else {
	$tag_menu = TAG_str('no_item');
}

$tags = array_map('TAG_getTagName', $tags);

if ($_TAG_CONF['replace_underscore'] === TRUE) {
	$temp = array();

	foreach ($tags as $tag) {
		$temp[] = str_replace('_', ' ', $tag);
	}

	$tags = $temp;
}

$T->set_var(
	'title',
	TAG_escape(
		sprintf(
			$LANG_TAG['menu_title'],
			implode(', ', $tags)
		)
	)
);
$T->set_var('tag_menu', $tag_menu);
$T->parse('output', 'page');
$content = $T->finish($T->get_var('output'));

if (is_callable('COM_createHTMLDocument')) {
	$display = COM_createHTMLDocument($content);
} else {
	$display = COM_siteHeader() . $content . COM_siteFooter();
}

COM_output($display);
