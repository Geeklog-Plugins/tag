<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/index.php                                   |
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

require_once '../../../lib-common.php';
require_once $_CONF['path_system'] . 'classes/navbar.class.php';

if (!in_array('tag', $_PLUGINS)) {
	COM_output(COM_refresh($_CONF['site_url'] . '/index.php'));
	exit;
}

TAG_checkAdmin();

// Main
$this_script = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
$commands = array('stats', 'badword', 'menuconfig');
$actions  = array('view', 'add', 'edit', 'delete', 'doAdd', 'doEdit', 'doDelete');

// Retrieves request vars
$cmd = TAG_get('cmd');

if ($cmd === FALSE) {
	$cmd = TAG_post('cmd');
}

if (($cmd === FALSE) OR !in_array($cmd, $commands)) {
	$cmd = 'stats';
}

$action = TAG_get('action');

if ($action === FALSE) {
	$action = TAG_post('action', true);
}

if (($action === FALSE) OR !in_array($action, $actions)) {
	$action = 'view';
}

// Processes command
require_once $cmd . '.class.php';

$class = 'Tag' . ucfirst($cmd);
$obj = new $class;

switch ($action) {
	case 'doAdd':
		$msg = $obj->doAdd();
		break;

	case 'doEdit':
		$msg = $obj->doEdit();
		break;

	case 'doDelete':
		$msg = $obj->doDelete();
		break;

	default:
		$msg = '';
		break;
}

/**
* Display
*/
$T = new Template($_CONF['path'] . 'plugins/tag/templates');
$T->set_file('admin', 'admin.thtml');
$T->set_var('xhtml', XHTML);
$T->set_var('header', TAG_str('admin'));
$T->set_var('config_url', $_CONF['site_admin_url'] . '/configuration.php');
$T->set_var('lang_config', TAG_str('config'));

if ($msg !== '') {
	$T->set_var('msg', '<p>' . $msg . '</p>');
}

// Navbar
$navbar = new navbar;

foreach ($commands as $menu_item) {
	$navbar->add_menuitem(
		TAG_str('menu_' . $menu_item),
		$this_script . '?cmd=' . $menu_item
	);
}

$navbar->set_selected(TAG_str('menu_' . $cmd), $cmd);
$T->set_var('navbar', $navbar->generate());

// Menu
switch ($action) {
	case 'add':
		$content = $obj->add();
		break;

	case 'edit':
		$content = $obj->edit();
		break;

	case 'delete':
		$content = $obj->delete();
		break;

	case 'view':
		/* Fall through to default */

	default:
		$content = $obj->view();
		break;
}

$T->set_var('content', $content);
$T->parse('output', 'admin');
$content = $T->finish($T->get_var('output'));

if (is_callable('COM_createHTMLDocument')) {
	$display = COM_createHTMLDocument($content);
} else {
	$display = COM_siteHeader() . $content . COM_siteFooter();
}

COM_output($display);
