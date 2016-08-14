<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------+
// | geeklog/plugins/tag/sql/mysql_install.php                                 |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010 mystral-kk - geeklog AT mystral-kk DOT net             |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is licensed under the terms of the GNU General Public License|
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                      |
// | See the GNU General Public License for more details.                      |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

if (strpos(strtolower($_SERVER['PHP_SELF']), strtolower(basename(__FILE__))) !== FALSE) {
    die('This file can not be used on its own!');
}

$_SQL[] = "
CREATE TABLE {$_TABLES['tag_list']} (
	tag_id MEDIUMINT(10) NOT NULL AUTO_INCREMENT,
	tag VARCHAR(255) NOT NULL DEFAULT '',
	hits MEDIUMINT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY tag_id(tag_id)
) ENGINE=MyISAM
";

$_SQL[] = "
CREATE INDEX idx_tag_list_tag ON {$_TABLES['tag_list']} (tag)
";

$_SQL[] = "
CREATE TABLE {$_TABLES['tag_map']} (
	map_id MEDIUMINT(10) NOT NULL AUTO_INCREMENT,
	tag_id MEDIUMINT(10) NOT NULL DEFAULT '0',
	type VARCHAR(30) NOT NULL DEFAULT 'article',
	sid VARCHAR(40) NOT NULL DEFAULT '',
	PRIMARY KEY map_id(map_id)
) ENGINE=MyISAM
";

$_SQL[] = "
CREATE INDEX idx_tag_map_tag_id ON {$_TABLES['tag_map']} (tag_id)
";

$_SQL[] = "
CREATE TABLE {$_TABLES['tag_badwords']} (
	badword VARCHAR(255) NOT NULL,
	PRIMARY KEY badword(badword)
) ENGINE=MyISAM
";

$_SQL[] = "
CREATE TABLE {$_TABLES['tag_menu']} (
	menu_id INT(10) NOT NULL AUTO_INCREMENT,
	menu_name VARCHAR(255) NOT NULL DEFAULT '',
	tag_ids VARCHAR(255) NOT NULL DEFAULT '',
	parent_id INT(10) NOT NULL DEFAULT '0',
	dsp_order INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY menu_id(menu_id)
) ENGINE=MyISAM
";
