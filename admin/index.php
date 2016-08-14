<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | public_html/admin/plugins/tag/index.php                                   |
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

require_once '../../../lib-common.php';
require_once $_CONF['path_system'] . 'classes/navbar.class.php';

// Only let admin users access this page
if (!SEC_hasRights('tag.admin')) {
    // Someone is trying to illegally access this page
    COM_errorLog("Someone has tried to illegally access the tag Admin page.  User id: {$_USER['uid']}, Username: {$_USER['username']}, IP: {$_SERVER['REMOTE_ADDR']}", 1);
    $display  = COM_siteHeader();
    $display .= COM_startBlock($LANG_TAG['access_denied']);
    $display .= $LANG_TAG['access_denied_msg'];
    $display .= COM_endBlock();
    $display .= COM_siteFooter(true);
    echo $display;
    exit;
}
 
/**
* Main 
*/

if (!defined('XHTML')) {
	define('XHTML', '');
}

$this_script = $_CONF['site_admin_url'] . '/plugins/tag/index.php';
//$commands = array('stats', 'badword', 'menuconfig');
$commands = array('stats', 'badword');
$actions  = array('view', 'add', 'edit', 'delete', 'doAdd', 'doEdit', 'doDelete');

// Retrieve request vars
$cmd = TAG_get('cmd');
if ($cmd === false) {
	$cmd = TAG_post('cmd');
}
if ($cmd === false OR !in_array($cmd, $commands)) {
	$cmd = 'stats';
}

$action = TAG_get('action');
if ($action === false) {
	$action = TAG_post('action', true);
}
if ($action === false OR !in_array($action, $actions)) {
	$action = 'view';
}

// Process command
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
		$msg = '&nbsp;';
		break;
}

// Display
$display = COM_siteHeader();
$T = new Template($_CONF['path'] . 'plugins/tag/templates');
$T->set_file('admin', 'admin.thtml');
$T->set_var('xhtml', XHTML);
$T->set_var('header', $LANG_TAG['admin']);
$T->set_var('msg', $msg);

// Navbar
$navbar = new navbar;

foreach ($commands as $menu_item) {
	$navbar->add_menuitem(
		$LANG_TAG['menu_' . $menu_item],
		$this_script . '?cmd=' . $menu_item
	);
}

$navbar->set_selected($LANG_TAG['menu_' . $cmd], $cmd);
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
$display .= $T->finish($T->get_var('output'));
$display .= COM_siteFooter();

echo $display;

?>
