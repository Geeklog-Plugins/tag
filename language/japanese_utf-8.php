<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------|
// | geeklog/plugins/tag/language/japanese_utf-8.php                           |
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

$LANG_CHARSET = 'utf-8';

$LANG_TAG = array (
    'plugin'            => 'タグプラグイン',
	'access_denied'     => 'アクセスは拒否されました。',
	'access_denied_msg' => 'このページにアクセスできるのは，Rootユーザだけです。あなたのユーザ名とIPアドレスは記録されました。',
	'admin'		        => 'タグプラグイン管理',
	'install_header'	=> 'タグプラグインのインストール/アンインストール',
	'install_success'	=> 'タグプラグインのインストールに成功しました。',
	'install_fail'  	=> 'タグプラグインのインストールに失敗しました。詳細はエラーログ(error.log)をご覧ください。',
	'uninstall_success'	=> 'タグプラグインのアンインストールに成功しました。',
	'uninstall_fail'    => 'タグプラグインのアンインストールに失敗しました。詳細はエラーログ(error.log)をご覧ください。',
	'uninstall_msg'		=> 'タグプラグインはアンインストールされました。',
	'tag_separators'    => ' 　',	// 2文字以上も可
	'badword_replace'   => '',
	'admin_label'       => 'タグ',
	'display_label'     => 'タグ：',
	'default_block_title' => 'このサイトの主なタグ',
	'default_block_title_menu' => 'タグメニュー',
	'tag_list'          => 'タグ一覧',
	'selected_tag'      => '「<strong>%s</strong>」タグのついている項目一覧：',	// %s = tag name
	'related'           => '関連タグ',
	'block_title'       => 'このサイトの主なタグ',
	'menu_stats'        => '統計',
	'menu_badword'      => '禁止タグ',
	'menu_menuconfig'   => 'タグメニュー設定',
	'db_error'          => 'データベースから読み込めません。',
	'action'            => '操作',
	'desc_admin_stats'  => '登録されているタグ一覧です。間違って登録されているタグや禁止タグ（あまりに一般的な語や卑語など）を一括して処理できます。',
	'lbl_tag'           => 'タグ',
	'lbl_count'         => '個数',
	'lbl_hit_count'     => 'クリック数',
	'delete_checked'    => 'チェックしたタグを削除する',
	'ban_checked'       => 'チェックしたタグを禁止タグにする',
	'desc_admin_badword' => '禁止タグ（あまりに一般的な語や卑語など）の一覧です。',
	'check'             => 'チェック',
	'add'               => '追加',
	'edit'              => '編集',
	'delete'            => '削除',
	'submit'            => '送信',
	'cancel'            => '中止',
	'badword'           => '禁止タグ',
	'no_tag'            => 'タグが定義されていません。',
	'no_badword'        => '禁止タグが登録されていません。',
	'desc_admin_menuconfig' => '定義されているタグメニュー一覧です。',
	'no_parent'         => '（なし）',
	'menu_name'         => 'メニュー名',
	'menu_parent'       => '親メニュー',
	'menu_tags'         => '含まれるタグ',
	'menu_dsp_order'    => '表示順',
	'desc_add_menu'     => 'タグメニュー追加',
	'desc_edit_menu'    => 'タグメニュー編集',
	'desc_delete_menu'  => 'タグメニュー削除',
	'add_child'         => '子メニューを追加',
	'order_up'          => '表示順を上げる',
	'order_down'        => '表示順を下げる',
	'add_success'       => '追加しました。',
	'add_fail'          => '追加できませんでした。',
	'delete_success'    => '削除しました。',
	'delete_fail'       => '削除できませんでした。',
	'edit_success'      => '変更しました。',
	'edit_fail'         => '変更できませんでした。',
	'menu_title'        => '「%s」タグを含むアイテム',
	'no_item'           => '該当するアイテムはありません。',
);

// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => 'タグプラグイン',
    'title' => 'タグプラグインの設定'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
	'default_block_name'       => 'タグクラウドブロック名の初期値',
	'tag_name'                 => 'タグの識別子',
	'max_tag_len'              => 'タグの長さの最大値（単位：バイト）',
	'tag_case_sensitive'       => 'タグの大文字小文字を区別する',
    'tag_stemming'             => 'タグの語尾正規化を行う',
	'tag_check_badword'        => 'バッドワードリストを使用する',
	'tag_cloud_spacer'         => 'タグクラウドのスペーサ',
	'max_tag_cloud'            => 'タグクラウドに表示されるタグの最大数',
	'max_tag_cloud_in_block'   => 'タグクラウドブロックに表示されるタグの最大数',
	'tag_cloud_threshold'      => 'タグレベルの上限値',
	'replace_underscore'       => 'タグのアンダースコアをスペースに置換して表示',
	'num_keywords'             => 'keywordsタグに表示するキーワード数の上限',
	'publish_as_template_vars' => 'タグをテンプレート変数として出力',
	'default_block_name_menu'  => 'タグメニューブロック名の初期値',
	'menu_indenter'            => 'タグメニューをインデントする文字列',
	'add_num_items_to_menu'    => 'タグメニューでアイテム数を表示',
	
);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => 'メイン'
);

$LANG_fs['tag'] = array(
    'fs_main'   => 'タグプラグインのメイン設定',
);

// Note: entries 0, 1, 9, and 12 are the same as in $LANG_configselects['Core']
$LANG_configselects['tag'] = array(
    0 => array('はい' => true, 'いいえ' => false),
);
