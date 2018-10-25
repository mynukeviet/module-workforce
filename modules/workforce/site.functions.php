<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Tue, 02 Jan 2018 08:34:29 GMT
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

$workforce_list = nv_crm_list_workforce();

$sql = 'SELECT id, title, lev FROM ' . NV_PREFIXLANG . '_workforce_part WHERE status=1 ORDER BY sort ASC';
$result = $db->query($sql);
$array_part_list = array();

while (list ($id_i, $title_i, $lev_i) = $result->fetch(3)) {
    $xtitle_i = '';
    if ($lev_i > 0) {
        $xtitle_i .= '&nbsp;';
        for ($i = 1; $i <= $lev_i; $i++) {
            $xtitle_i .= '---';
        }
    }
    $xtitle_i .= $title_i;
    $array_part_list[$id_i] = array(
        $id_i,
        $xtitle_i
    );
}


function nv_crm_list_workforce($in_groups = '')
{
    global $db, $nv_Cache, $module_info;

    $where = '';
    if (!empty($in_groups)) {
        $where = ' AND t1.userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id IN (' . $in_groups . '))';
    }
    $sql = 'SELECT t1.userid, t2.first_name, t2.last_name, t1.username, t1.photo, t2.main_email email, salary, allowance FROM ' . NV_USERS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_PREFIXLANG . '_workforce t2 ON t1.userid=t2.userid WHERE t1.active=1' . $where;
    $array_data = $nv_Cache->db($sql, 'userid', 'users');
    if (!empty($array_data)) {
        foreach ($array_data as $index => $value) {
            if (!empty($value['photo']) && file_exists(NV_ROOTDIR . '/' . $value['photo'])) {
                $value['photo'] = NV_BASE_SITEURL . $value['photo'];
            } else {
                $value['photo'] = NV_BASE_SITEURL . 'themes/default/images/users/no_avatar.png';
            }
            $array_data[$index]['fullname'] = nv_show_name_user($value['first_name'], $value['last_name'], $value['username']);
        }
    }
    return $array_data;
}

function nv_crm_workforce_info($userid)
{
    global $db;

    $workforce_info = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_workforce WHERE userid=' . $userid)->fetch();
    if ($workforce_info) {
        $workforce_info['fullname'] = nv_show_name_user($workforce_info['first_name'], $workforce_info['last_name']);
    }

    return $workforce_info;
}

/**
 * nv_crm_department()
 *
 * @return
 */
function nv_crm_department($in_groups)
{
    global $nv_Cache, $db, $global_config;

    $_groups = array();

    if (!empty($in_groups)) {
        $query = 'SELECT group_id, title, exp_time FROM ' . NV_GROUPS_GLOBALTABLE . ' WHERE act=1 AND (idsite = ' . $global_config['idsite'] . ' OR (idsite =0 AND siteus = 1)) ORDER BY idsite, weight';
        $list = $nv_Cache->db($query, '', 'users');
        if (!empty($list)) {
            $reload = array();
            $in_groups = explode(',', $in_groups);
            for ($i = 0, $count = sizeof($list); $i < $count; ++$i) {
                if ($list[$i]['exp_time'] != 0 and $list[$i]['exp_time'] <= NV_CURRENTTIME) {
                    $reload[] = $list[$i]['group_id'];
                } elseif (in_array($list[$i]['group_id'], $in_groups)) {
                    $_groups[$list[$i]['group_id']] = $list[$i];
                }
            }

            if ($reload) {
                $db->query('UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET act=0 WHERE group_id IN (' . implode(',', $reload) . ')');
                $nv_Cache->delMod('users');
            }
        }
    }
    return $_groups;
}