<?php

// +---------------------------------------------------------------------------+
// | Tag Plugin for Geeklog - The Ultimate Weblog                              |
// +---------------------------------------------------------------------------|
// | geeklog/plugins/tag/language/japanese.php                                 |
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

$LANG_CHARSET = 'euc-jp';

$LANG_TAG = array (
    'plugin'            => '�����ץ饰����',
	'access_denied'     => '���������ϵ��ݤ���ޤ�����',
	'access_denied_msg' => '���Υڡ����˥��������Ǥ���Τϡ�Root�桼�������Ǥ������ʤ��Υ桼��̾��IP���ɥ쥹�ϵ�Ͽ����ޤ�����',
	'admin'		        => '�����ץ饰�������',
	'install_header'	=> '�����ץ饰����Υ��󥹥ȡ���/���󥤥󥹥ȡ���',
	'install_success'	=> '�����ץ饰����Υ��󥹥ȡ�����������ޤ�����',
	'install_fail'  	=> '�����ץ饰����Υ��󥹥ȡ���˼��Ԥ��ޤ������ܺ٤ϥ��顼��(error.log)��������������',
	'uninstall_success'	=> '�����ץ饰����Υ��󥤥󥹥ȡ�����������ޤ�����',
	'uninstall_fail'    => '�����ץ饰����Υ��󥤥󥹥ȡ���˼��Ԥ��ޤ������ܺ٤ϥ��顼��(error.log)��������������',
	'uninstall_msg'		=> '�����ץ饰����ϥ��󥤥󥹥ȡ��뤵��ޤ�����',
	'tag_separators'    => ' ��',	// 2ʸ���ʾ���
	'badword_replace'   => '',
	'admin_label'       => '����',
	'display_label'     => '������',
	'default_block_title' => '���Υ����Ȥμ�ʥ���',
	'default_block_title_menu' => '������˥塼',
	'tag_list'          => '��������',
	'selected_tag'      => '��<strong>%s</strong>�ץ����ΤĤ��Ƥ�����ܰ�����',	// %s = tag name
	'related'           => '��Ϣ����',
	'block_title'       => '���Υ����Ȥμ�ʥ���',
	'menu_stats'        => '����',
	'menu_badword'      => '�ػߥ���',
	'menu_menuconfig'   => '������˥塼����',
	'db_error'          => '�ǡ����١��������ɤ߹���ޤ���',
	'action'            => '���',
	'desc_admin_stats'  => '��Ͽ����Ƥ��륿�������Ǥ����ְ�ä���Ͽ����Ƥ��륿����ػߥ����ʤ��ޤ�˰���Ū�ʸ���ܸ�ʤɡˤ��礷�ƽ����Ǥ��ޤ���',
	'lbl_tag'           => '����',
	'lbl_count'         => '�Ŀ�',
	'lbl_hit_count'     => '����å���',
	'delete_checked'    => '�����å�����������������',
	'ban_checked'       => '�����å�����������ػߥ����ˤ���',
	'desc_admin_badword' => '�ػߥ����ʤ��ޤ�˰���Ū�ʸ���ܸ�ʤɡˤΰ����Ǥ���',
	'check'             => '�����å�',
	'add'               => '�ɲ�',
	'edit'              => '�Խ�',
	'delete'            => '���',
	'submit'            => '����',
	'cancel'            => '���',
	'badword'           => '�ػߥ���',
	'no_tag'            => '�������������Ƥ��ޤ���',
	'no_badword'        => '�ػߥ�������Ͽ����Ƥ��ޤ���',
	'desc_admin_menuconfig' => '�������Ƥ��륿����˥塼�����Ǥ���',
	'no_parent'         => '�ʤʤ���',
	'menu_name'         => '��˥塼̾',
	'menu_parent'       => '�ƥ�˥塼',
	'menu_tags'         => '�ޤޤ�륿��',
	'menu_dsp_order'    => 'ɽ����',
	'desc_add_menu'     => '������˥塼�ɲ�',
	'desc_edit_menu'    => '������˥塼�Խ�',
	'desc_delete_menu'  => '������˥塼���',
	'add_child'         => '�ҥ�˥塼���ɲ�',
	'order_up'          => 'ɽ�����夲��',
	'order_down'        => 'ɽ����򲼤���',
	'add_success'       => '�ɲä��ޤ�����',
	'add_fail'          => '�ɲäǤ��ޤ���Ǥ�����',
	'delete_success'    => '������ޤ�����',
	'delete_fail'       => '����Ǥ��ޤ���Ǥ�����',
	'edit_success'      => '�ѹ����ޤ�����',
	'edit_fail'         => '�ѹ��Ǥ��ޤ���Ǥ�����',
	'menu_title'        => '��%s�ץ�����ޤॢ���ƥ�',
	'no_item'           => '�������륢���ƥ�Ϥ���ޤ���',
);

// Localization of the Admin Configuration UI
$LANG_configsections['tag'] = array(
    'label' => '�����ץ饰����',
    'title' => '�����ץ饰���������'
);

/**
* For Config UI
*/
$LANG_confignames['tag'] = array(
	'default_block_name'       => '�������饦�ɥ֥�å�̾�ν����',
	'tag_name'                 => '�����μ��̻�',
	'max_tag_len'              => '������Ĺ���κ����͡�ñ�̡��Х��ȡ�',
	'tag_case_sensitive'       => '��������ʸ����ʸ������̤���',
    'tag_stemming'             => '�����θ�����������Ԥ�',
	'tag_check_badword'        => '�Хåɥ�ɥꥹ�Ȥ���Ѥ���',
	'tag_cloud_spacer'         => '�������饦�ɤΥ��ڡ���',
	'max_tag_cloud'            => '�������饦�ɤ�ɽ������륿���κ����',
	'max_tag_cloud_in_block'   => '�������饦�ɥ֥�å���ɽ������륿���κ����',
	'tag_cloud_threshold'      => '������٥�ξ����',
	'replace_underscore'       => '�����Υ�������������򥹥ڡ������ִ�����ɽ��',
	'num_keywords'             => 'keywords������ɽ�����륭����ɿ��ξ��',
	'publish_as_template_vars' => '������ƥ�ץ졼���ѿ��Ȥ��ƽ���',
	'default_block_name_menu'  => '������˥塼�֥�å�̾�ν����',
	'menu_indenter'            => '������˥塼�򥤥�ǥ�Ȥ���ʸ����',
	'add_num_items_to_menu'    => '������˥塼�ǥ����ƥ����ɽ��',
	
);

$LANG_configsubgroups['tag'] = array(
    'sg_main' => '�ᥤ��'
);

$LANG_fs['tag'] = array(
    'fs_main'   => '�����ץ饰����Υᥤ������',
);

// Note: entries 0, 1, 9, and 12 are the same as in $LANG_configselects['Core']
$LANG_configselects['tag'] = array(
    0 => array('�Ϥ�' => true, '������' => false),
);
