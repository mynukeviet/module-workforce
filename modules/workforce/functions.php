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
function nv_createaccount($username, $password, $email, $ingroups, $firstname, $lastname, $gender)
{
    global $db, $global_config, $crypt, $user_info, $lang_module;

    $module_name = 'users';
    $module_data = 'users';
    $md5username = nv_md5safe($username);

    // Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username');

    $stmt->bindParam(':md5username', $md5username, PDO::PARAM_STR);
    $stmt->execute();
    $query_error_username = $stmt->fetchColumn();
    if ($query_error_username) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'username',
            'mess' => $lang_module['edit_error_username_exist']
        ));
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email');
    $stmt->bindParam(':email', $row['main_email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email = $stmt->fetchColumn();
    if ($query_error_email) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => 'main_email',
            'mess' => $lang_module['edit_error_email_exist']
        ));
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv4_users_reg  chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email');
    $stmt->bindParam(':email', $row['main_email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_reg = $stmt->fetchColumn();
    if ($query_error_email_reg) {
        $error[] = $lang_module['edit_error_email_exist'];
    }

    // Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong nv3_users_openid chưa.
    $stmt = $db->prepare('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email= :email');
    $stmt->bindParam(':email', $row['main_email'], PDO::PARAM_STR);
    $stmt->execute();
    $query_error_email_openid = $stmt->fetchColumn();
    if ($query_error_email_openid) {
        $error[] = $lang_module['edit_error_email_exist'];
    }

    if (empty($ingroups)) {
        $error[] = $lang_module['edit_error_group_default'];
    }

    $in_groups_default = 4;

    $sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
        group_id,username,md5username,password,email,first_name,last_name,gender,regdate,
        passlostkey,view_mail,remember,in_groups,active,checknum,last_login,
        last_ip, last_agent, last_openid, idsite, email_verification_time
    ) VALUES (
        $in_groups_default,
        :username,
        :md5_username,
        :password,
        :email,
        :first_name,
        :last_name,
        :gender,
        " . NV_CURRENTTIME . ",
        '', 0 , 1 , '" . implode(',', $ingroups) . "' , 1 , '' , 0 , '' , '' , '' , " . $global_config['idsite'] . ", 0
    )";
        $data_insert = array();
        $data_insert['username'] = $username;
        $data_insert['md5_username'] = $md5username;
        $data_insert['password'] = $crypt->hash_password($password, $global_config['hashprefix']);
        $data_insert['email'] = $email;
        $data_insert['first_name'] = $firstname;
        $data_insert['last_name'] = $lastname;
        $data_insert['gender'] = $gender;

        $userid = $db->insert_id($sql, 'userid', $data_insert);

        if (!$userid) {
            $error[] = $lang_module['edit_add_error'];
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_user', 'userid ' . $userid, $user_info['userid']);

        if (!empty($ingroups)) {
            foreach ($ingroups as $group_id) {
                if ($group_id != 7) {
                    nv_groups_add_user($group_id, $userid, 1, $module_data);
                }
            }
        }
}