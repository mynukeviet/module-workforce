<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 06 May 2018 09:55:31 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

$db->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_salary')
    ->where('userid=' . $user_info['userid'])
    ->order('time DESC');

$sth = $db->prepare($db->sql());
$sth->execute();

$lang_module['salary_history'] = sprintf($lang_module['salary_history'], $workforce_list[$user_info['userid']]['fullname']);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('insurrance', $array_config['insurrance']);

while ($view = $sth->fetch()) {
    $view['salary'] = number_format($view['salary']);
    $view['allowance'] = number_format($view['allowance']);
    $view['total'] = number_format($view['total']);
    $view['deduction'] = number_format($view['deduction']);
    $view['received'] = number_format($view['received']);
    $view['holiday_salary'] = number_format($view['holiday_salary']);
    $view['bhxh'] = number_format($view['bhxh']);
    $xtpl->assign('VIEW', $view);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['salary_history'];
$array_mod_title[] = array(
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';