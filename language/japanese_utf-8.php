<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------|
// | private/plugins/tag/language/japanese_utf-8.php                           |
// +---------------------------------------------------------------------------|
// | Copyright (C) 2008 mystral-kk - geeklog AT mystral-kk DOT net             |
// |                                                                           |
// | Constructed with the Universal Plugin                                     |
// | Copyright (C) 2002 by the following authors:                              |
// | Tom Willett                 -    twillett@users.sourceforge.net           |
// | Blaine Lang                 -    langmail@sympatico.ca                    |
// | The Universal Plugin is based on prior work by:                           |
// | Tony Bibbs                  -    tony@tonybibbs.com                       |
// +---------------------------------------------------------------------------|
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
// | along with this program; if not, write to the Free Software               |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA|
// |                                                                           |
// +---------------------------------------------------------------------------|
//
// $Id$

$LANG_CHARSET = 'utf-8';

$LANG_TAG = array (
    'plugin'            => 'tagプラグイン',
	'access_denied'     => 'アクセスは拒否されました。',
	'access_denied_msg' => 'このページにアクセスできるのは，Rootユーザだけです。あなたのユーザ名とIPアドレスは記録されました。',
	'admin'		        => 'tagプラグイン管理',
	'install_header'	=> 'tagプラグインのインストール/アンインストール',
	'install_success'	=> 'tagプラグインのインストールに成功しました。',
	'install_fail'  	=> 'tagプラグインのインストールに失敗しました。詳細はエラーログ(error.log)をご覧ください。',
	'uninstall_success'	=> 'tagプラグインのアンインストールに成功しました。',
	'uninstall_fail'    => 'tagプラグインのアンインストールに失敗しました。詳細はエラーログ(error.log)をご覧ください。',
	'uninstall_msg'		=> 'tagプラグインはアンインストールされました。',
	'tag_separators'    => ' 　',	// 2文字以上も可
	'badword_replace'   => '',
	'admin_label'       => 'タグ',
	'display_label'     => 'タグ：',
	'tag_list'          => 'タグ一覧',
	'selected_tag'      => '「<strong>%s</strong>」タグのついている項目一覧：',	// %s = tag name
	'related'           => '関連タグ',
	'block_title'       => 'このサイトの主なタグ',
	'menu_stats'        => '統計',
	'menu_badword'      => '禁止語',
	'menu_menuconfig'   => 'タグメニュー設定',
	'db_error'          => 'データベースから読み込めません。',
	'action'            => '操作',
	'lbl_tag'           => 'タグ',
	'lbl_count'         => '個数',
	'lbl_hit_count'     => 'クリック数',
	'check'             => 'チェック',
	'add'               => '追加',
	'edit'              => '編集',
	'delete'            => '削除',
	'delete_checked'    => 'チェックしたものを削除',
	'submit'            => '送信',
	'cancel'            => '中止',
	'badword'           => '禁止語',
	'no_tag'            => 'タグが定義されていません。',
	'no_badword'        => '禁止語が登録されていません。',
	'no_menu'           => 'メニューが定義されていません。',
	'no_parent'         => '（なし）',
	'menu_name'         => 'メニュー名',
	'menu_parent'       => '親メニュー',
	'menu_tags'         => '含まれるタグ',
	'menu_dsp_order'    => '表示順',
	'desc_add_menu'     => 'タグメニュー追加',
	'desc_edit_menu'    => 'タグメニュー編集',
	'desc_delete_menu'  => 'タグメニュー削除',
	'add_success'       => '追加しました。',
	'add_fail'          => '追加できませんでした。',
	'delete_success'    => '削除しました。',
	'delete_fail'       => '削除できませんでした。',
	'edit_success'      => '変更しました。',
	'edit_fail'         => '変更できませんでした。',
	'menu_title'        => '%s タグを含むアイテム',
	'no_item'           => '該当するアイテムはありません。',
);

?>
