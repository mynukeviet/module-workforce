<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Sat, 05 May 2018 23:45:39 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if ($nv_Request->isset_request('change_status', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);

    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_status = $nv_Request->get_int('new_status', 'post');

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $new_status . ' WHERE id=' . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);
    die('OK_' . $id);
}

$id = $nv_Request->get_int('id', 'get', 0);
$status = $nv_Request->get_int('status', 'get', 0);

$result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id)->fetch();
if (!$result) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$result['fullname'] = nv_show_name_user($result['first_name'], $result['last_name']);
$result['gender'] = $array_gender[$result['gender']];
$result['addtime'] = nv_date('H:i d/m/Y', $result['addtime']);
$result['edittime'] = !empty($result['edittime']) ? nv_date('H:i d/m/Y', $result['edittime']) : '';
$result['birthday'] = !empty($result['birthday']) ? nv_date('d/m/Y', $result['birthday']) : '';
$result['jointime'] = !empty($result['jointime']) ? nv_date('d/m/Y', $result['jointime']) : '-';

$array_parts_title = array();
$result['part'] = explode(",", $result['part']);
foreach ($result['part'] as $partid) {
    $array_parts_title[] = $array_part_list[$partid]['title'];
}
$result['part'] = implode(", ", $array_parts_title);

if(isset($site_mods['salary'])){
    $array_salary = array();
    $approval = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_salary_history_salary WHERE userid = ' . $result['userid']);
    while ($row = $approval->fetch()) {
        $row['addtime'] = nv_date('H:i d/m/Y', $row['addtime']);
        $row['salary'] = nv_number_format($row['salary']);
        $row['allowance'] = nv_number_format($row['allowance']);
        $array_salary[$row['id']] = $row;
    }
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('URL_EDIT', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $id . '&amp;redirect=' . nv_redirect_encrypt($client_info['selfurl']));
$xtpl->assign('URL_DELETE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;delete_id=' . $id . '&amp;delete_checkss=' . md5($id . NV_CACHE_PREFIX . $client_info['session_id']));
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('WORKFORCE', $result);

if (nv_workforce_check_premission() && isset($site_mods['salary'])) {
    $xtpl->assign('URL_APPROVAL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=history-salary&amp;id=' . $id);
    $xtpl->parse('main.salary');
}

foreach ($array_status as $data => $value) {
    $selected = $data == $result['status'] ? 'selected = "selected"' : '';
    $xtpl->assign('STATUS', array(
        'data' => $data,
        'value' => $value,
        'selected' => $selected
    ));
    $xtpl->parse('main.status');
}

if(!empty($array_salary)){
    foreach ($array_salary as $approval) {
        $xtpl->assign('APPROVAL', $approval);
        $xtpl->parse('main.approval.loop');
    }
    $xtpl->parse('main.approval');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $result['fullname'];
$array_mod_title[] = array(
    'title' => $page_title
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';