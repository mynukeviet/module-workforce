<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 07 Jan 2018 03:36:43 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['workforce_add'] = $lang_module['workforce_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
        die();
    }
} else {
    $row['salary'] = 0;
    $row['allowance'] = 0;
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['userid'] = $nv_Request->get_int('userid', 'post', 0);
    $row['salary'] = $nv_Request->get_string('salary', 'post', 0);
    $row['salary'] = preg_replace('/[^0-9]/', '', $row['salary']);
    $row['allowance'] = $nv_Request->get_string('allowance', 'post', 0);
    $row['allowance'] = preg_replace('/[^0-9]/', '', $row['allowance']);

    if (empty($row['salary'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_salary'],
            'input' => 'salary'
        ));
    } elseif (empty($row['allowance'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_allowance'],
            'input' => 'allowance'
        ));
    }
    if (empty($error)) {

        try {
            if (empty($row['id'])) {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET salary = :salary, allowance = :allowance');
                $stmt->bindParam(':salary', $row['salary'], PDO::PARAM_STR);
                $stmt->bindParam(':allowance', $row['allowance'], PDO::PARAM_STR);
                $exc = $stmt->execute();
                if ($exc) {
                    $stmt = $db->prepare ('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_history_salary (userid, salary, allowance, addtime, useradd) VALUES (:userid, :salary, :allowance, ' . NV_CURRENTTIME . ', ' . $user_info['userid'].')');
                    $stmt->bindParam(':userid', $row['userid'], PDO::PARAM_INT);
                    $stmt->bindParam(':salary', $row['salary'], PDO::PARAM_STR);
                    $stmt->bindParam(':allowance', $row['allowance'], PDO::PARAM_STR);
                    $exc = $stmt->execute();
                    $nv_Cache->delMod($module_name);
                    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=detail&id=' . $row['userid']);
                    die();
                }
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); //Remove this line after checks finished
        }
    }
}

$row['salary'] = !empty($row['salary']) ? $row['salary'] : '';
$row['allowance'] = !empty($row['allowance']) ? $row['allowance'] : '';

$userinfo = array();
if ($row['userid'] > 0) {
    $userinfo = $rows = $db->query('SELECT userid, first_name, last_name, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['userid'])->fetch();
    $userinfo['fullname'] = nv_show_name_user($userinfo['first_name'], $userinfo['last_name'], $userinfo['username']);
}

$arr = array();
$approval = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_history_salary WHERE userid = ' . $row['id']);
while ($row = $approval->fetch()) {
    $row['addtime'] = nv_date('H:i d/m/Y', $row['addtime']);
    $row['salary'] = nv_number_format($row['salary']);
    $row['allowance'] = nv_number_format($row['allowance']);
    $arr[$row['id']] = $row;
}


$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

foreach ($arr as $approval) {
    $xtpl->assign('APPROVAL', $approval);
    $xtpl->parse('main.approval');
}

if (!empty($userinfo)) {
    $xtpl->assign('USER_INFO', $userinfo);
    $xtpl->parse('main.user_info');
}
if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
$page_title = $lang_module['approval'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';