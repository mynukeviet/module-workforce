<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Sat, 05 May 2018 23:45:39 GMT
 */
if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_WORKFORCE', true);
require_once NV_ROOTDIR . '/modules/workforce/site.functions.php';

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

    $rows = $db->query('SELECT userid FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id)->fetch();
    if ($rows) {
        $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id);
        if ($count) {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_part_detail WHERE userid = ' . $rows['userid']);
        }
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

function nv_fix_order($table_name, $parentid = 0, $sort = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT id, parentid FROM ' . $table_name . ' WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_order = array();
    while ($row = $result->fetch()) {
        $array_order[] = $row['id'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }
    foreach ($array_order as $order_i) {
        ++$sort;
        ++$weight;

        $sql = 'UPDATE ' . $table_name . ' SET weight=' . $weight . ', sort=' . $sort . ', lev=' . $lev . ' WHERE id=' . $order_i;
        $db->query($sql);

        $sort = nv_fix_order($table_name, $order_i, $sort, $lev);
    }

    $numsub = $weight;

    if ($parentid > 0) {
        $sql = "UPDATE " . $table_name . " SET numsub=" . $numsub;
        if ($numsub == 0) {
            $sql .= ",subid=''";
        } else {
            $sql .= ",subid='" . implode(",", $array_order) . "'";
        }
        $sql .= " WHERE id=" . intval($parentid);
        $db->query($sql);
    }
    return $sort;
}

function nv_empty_value($value)
{
    return !empty($value) ? $value : '';
}

function nv_caculate_percent($a, $b)
{
    return ($a * 100) / $b;
}
