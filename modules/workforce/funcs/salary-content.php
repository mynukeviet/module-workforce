<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Sat, 05 May 2018 23:45:39 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

$maxdays = $array_config['workdays'];
$percent_overtime = $array_config['overtime'];
$groups_admin = $array_config['groups_admin'];
$groups_use = $array_config['groups_use'];
$current_month = $nv_Request->get_string('month', 'get', nv_date('m/Y', NV_CURRENTTIME));

if ($nv_Request->isset_request('save_change', 'post')) {
    $data = $nv_Request->get_array('data', 'post');
    $data['workday'] = (isset($data['workday']) && !empty($data['workday'])) ? preg_replace('/[^0-9]\./', '', $data['workday']) : '';
    $data['overtime'] = !empty($data['overtime']) ? preg_replace('/[^0-9]\./', '', $data['overtime']) : 0;
    $data['holiday'] = !empty($data['holiday']) ? preg_replace('/[^0-9]\./', '', $data['holiday']) : 0;
    $data['advance'] = !empty($data['advance']) ? preg_replace('/[^0-9]\./', '', $data['advance']) : 0;
    $data['bonus'] = !empty($data['bonus']) ? preg_replace('/[^0-9]\./', '', $data['bonus']) : 0;
    $data['deduction'] = !empty($data['deduction']) ? preg_replace('/[^0-9]\./', '', $data['deduction']) : 0;

    $data['salary'] = $workforce_list[$data['userid']]['salary'];
    $data['allowance'] = $workforce_list[$data['userid']]['allowance'];

    // Lương lễ: Lương cơ bản / Số ngày công trong tháng * ngày công nghỉ, lễ
    $data['holiday_salary'] = $data['salary'] / $maxdays * $data['holiday'];
    $data['total'] = (floatval($data['salary']) / $maxdays) * floatval($data['workday']);

    if ($data['overtime'] > 0) {
        $data['overtime_salary'] = 0;
        $salary_one_day = $data['salary'] / $maxdays;
        $total_workday = $data['overtime'] + $data['workday'];
        $real_overtime_day = $total_workday - $maxdays;
        if ($real_overtime_day > 0) {
            $data['total'] = ($maxdays * $salary_one_day) + ((($real_overtime_day * $percent_overtime) / 100) * $salary_one_day);
        } else {
            $data['total'] = ($total_workday * $salary_one_day);
        }
    }

    $data['received'] = $data['total'] - floatval($data['deduction']) - floatval($data['advance']) + floatval($data['allowance']) + floatval($data['bonus']) + floatval($data['holiday_salary'] - floatval($data['bhxh']));
    $data['bhxh'] = ($data['salary'] * $array_config['insurrance']) / 100;

    try {
        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_salary (userid, salary, allowance, workday, holiday, holiday_salary, overtime, advance, bonus, total, bhxh, deduction, received, time) VALUES (:userid, :salary, :allowance, :workday, :holiday, :holiday_salary, :overtime, :advance, :bonus, :total, :bhxh, :deduction, :received, ' . $db->quote($current_month) . ')');
        $stmt->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
        $stmt->bindParam(':salary', $data['salary'], PDO::PARAM_STR);
        $stmt->bindParam(':allowance', $data['allowance'], PDO::PARAM_STR);
        $stmt->bindParam(':workday', $data['workday'], PDO::PARAM_STR);
        $stmt->bindParam(':holiday', $data['holiday'], PDO::PARAM_STR);
        $stmt->bindParam(':holiday_salary', $data['holiday_salary'], PDO::PARAM_STR);
        $stmt->bindParam(':overtime', $data['overtime'], PDO::PARAM_STR);
        $stmt->bindParam(':advance', $data['advance'], PDO::PARAM_STR);
        $stmt->bindParam(':bonus', $data['bonus'], PDO::PARAM_STR);
        $stmt->bindParam(':total', $data['total'], PDO::PARAM_STR);
        $stmt->bindParam(':bhxh', $data['bhxh'], PDO::PARAM_STR);
        $stmt->bindParam(':deduction', $data['deduction'], PDO::PARAM_STR);
        $stmt->bindParam(':received', $data['received'], PDO::PARAM_STR);
        $stmt->execute();
    } catch (Exception $e) {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_salary SET workday = :workday, holiday=:holiday, holiday_salary=:holiday_salary, overtime = :overtime, advance = :advance, bonus = :bonus, total = :total, bhxh=:bhxh, deduction = :deduction, received = :received WHERE userid=:userid AND time=' . $db->quote($current_month));
        $stmt->bindParam(':userid', $data['userid'], PDO::PARAM_INT);
        $stmt->bindParam(':workday', $data['workday'], PDO::PARAM_STR);
        $stmt->bindParam(':holiday', $data['holiday'], PDO::PARAM_STR);
        $stmt->bindParam(':holiday_salary', $data['holiday_salary'], PDO::PARAM_STR);
        $stmt->bindParam(':overtime', $data['overtime'], PDO::PARAM_STR);
        $stmt->bindParam(':advance', $data['advance'], PDO::PARAM_STR);
        $stmt->bindParam(':bonus', $data['bonus'], PDO::PARAM_STR);
        $stmt->bindParam(':total', $data['total'], PDO::PARAM_STR);
        $stmt->bindParam(':bhxh', $data['bhxh'], PDO::PARAM_STR);
        $stmt->bindParam(':deduction', $data['deduction'], PDO::PARAM_STR);
        $stmt->bindParam(':received', $data['received'], PDO::PARAM_STR);
        $stmt->execute();
    }

    nv_jsonOutput(array(
        'error' => 0,
        'total' => nv_empty_value($data['total']),
        'received' => nv_empty_value($data['received']),
        'holiday_salary' => nv_empty_value($data['holiday_salary'])
    ));
}

if (!nv_user_in_groups($groups_admin)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

$current_month = nv_date('m/Y', strtotime("first day of previous month"));
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$array_users = array();
$array_search = array(
    'month' => $nv_Request->get_string('month', 'get', $current_month)
);

if (!empty($array_search['month'])) {
    $current_month = $array_search['month'];
}

$workforce_list = nv_crm_list_workforce($groups_use);
if (!empty($workforce_list)) {
    $bonus = $advance = $deduction = $received = $total = 0;
    $array_data = array();
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_salary WHERE time=' . $db->quote($current_month));
    while ($row = $result->fetch()) {
        $row = array_map('nv_empty_value', $row);
        $total += $row['total'];
        $received += $row['received'];
        $deduction += $row['deduction'];
        $bonus += $row['bonus'];
        $advance += $row['advance'];
        $array_data[$row['userid']] = $row;
    }

    $array_salary = array();
    foreach ($workforce_list as $userid => $data) {
        if (isset($array_data[$userid])) {
            $array_data[$userid]['fullname'] = $data['fullname'];
            $array_data[$userid]['position'] = '';
            $array_salary[$userid] = $array_data[$userid];
        } else {
            $array_salary[$userid] = array(
                'userid' => $userid,
                'fullname' => $data['fullname'],
                'position' => '',
                'salary' => nv_empty_value($data['salary']),
                'allowance' => nv_empty_value($data['allowance']),
                'bhxh' => ($data['salary'] * $array_config['insurrance']) / 100
            );
        }
    }
}

/* $array_salary[] = array(
    'fullname' => $lang_module['total'],
    'total' => $total,
    'received' => $received,
    'deduction' => $deduction,
    'advance' => $advance,
    'bonus' => $bonus

); */

if (!empty($array_search['month'])) {
    $base_url .= '&month=' . $array_search['month'];
    $current_month = $array_search['month'];
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', json_encode(array_values($array_salary)));
$xtpl->assign('TITLE', $lang_module['salary_content'] . ' ' . $current_month);

for ($i = 1; $i <= 12; $i++) {
    $xtpl->assign('MONTH', array(
        'index' => vsprintf('%02d', $i) . '/' . nv_date('Y', NV_CURRENTTIME),
        'value' => sprintf($lang_module['month_f'], $i),
        'selected' => $current_month == $i ? 'selected="selected"' : ''
    ));

    $xtpl->parse('main.month');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['salary_content'];
$array_mod_title[] = array(
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=salary'
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';