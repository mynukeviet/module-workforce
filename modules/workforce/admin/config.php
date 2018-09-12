<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];
$groups_list = nv_groups_list();

$data = array();
if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['groups_admin'] = $nv_Request->get_typed_array('groups_admin', 'post', 'int');
    $data['groups_admin'] = !empty($data['groups_admin']) ? implode(',', $data['groups_admin']) : '';
    $data['groups_use'] = $nv_Request->get_typed_array('groups_use', 'post', 'int');
    $data['groups_use'] = !empty($data['groups_use']) ? implode(',', $data['groups_use']) : '';

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($data as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid']);
    $nv_Cache->delMod('settings');

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op);
    die();
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);

$groups = !empty($array_config['groups_admin']) ? explode(',', $array_config['groups_admin']) : array();
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groups) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groups');
}

$groups = !empty($array_config['groups_use']) ? explode(',', $array_config['groups_use']) : array();
foreach ($groups_list as $group_id => $grtl) {
    $_groups_view = array(
        'value' => $group_id,
        'checked' => in_array($group_id, $groups) ? ' checked="checked"' : '',
        'title' => $grtl
    );
    $xtpl->assign('GROUPS', $_groups_view);
    $xtpl->parse('main.groups_use');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
