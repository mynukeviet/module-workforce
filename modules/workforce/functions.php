<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Sat, 05 May 2018 23:45:39 GMT
 */
if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_WORKFORCE', true);

$array_config = $module_config[$module_name];

$array_gender = array(
    1 => $lang_module['male'],
    0 => $lang_module['female']
);

$array_status = array(
    1 => $lang_module['status_1'],
    0 => $lang_module['status_0']
);

function nv_workforce_delete($id)
{
    global $db, $module_data;
    
    $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id);
    if ($count) {
        //
    }
}

function nv_workforce_check_premission()
{
    global $array_config, $user_info;
    
    if (empty($array_config['groups_manage'])) {
        return false;
    } elseif (!empty(array_intersect(explode(',', $array_config['groups_manage']), $user_info['in_groups']))) {
        return true;
    }
    return false;
}

function nv_empty_value($value)
{
    return !empty($value) ? $value : '';
}

function nv_caculate_percent($a, $b)
{
    return ($a * 100) / $b;
}
