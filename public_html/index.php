<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/tag/index.php                                                 |
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
$tag = COM_getArgument('tag');

/**
* Display
*/
$T = new Template($_CONF['path'] . 'plugins/tag/templates');
$T->set_file('page', 'index.thtml');
$T->set_var('xhtml', XHTML);

// Lang vars
$lang_vars = array('tag_list');

foreach ($lang_vars as $lang_var) {
	$T->set_var('lang_' . $lang_var, TAG_str($lang_var));
}

// Tag cloud
$T->set_var('tag_cloud', TAG_getTagCloud($_TAG_CONF['max_tag_cloud'], false));

// Other tags
if ($tag !== '') {
	$tag = TAG_normalize($tag);
	$tag_id = TAG_getTagId($tag);

	if ($tag_id !== FALSE) {
		TAG_increaseHitCount($tag_id);
		$text = $tag;

		if ($_TAG_CONF['replace_underscore'] === TRUE) {
			$text = str_replace('_', ' ', $text);
		}

		$T->set_var('selected_tag', sprintf($LANG_TAG['selected_tag'], TAG_escape($text)));
	}

	$T->set_var(
		'tagged_items',
		($tag !== '') ? TAG_getTaggedItems($tag) : ''
	);
}

$T->parse('output', 'page');
$content = $T->finish($T->get_var('output'));

if (is_callable('COM_createHTMLDocument')) {
	$display = COM_createHTMLDocument($content);
} else {
	$display = COM_siteHeader() . $content . COM_siteFooter();
}

COM_output($display);
