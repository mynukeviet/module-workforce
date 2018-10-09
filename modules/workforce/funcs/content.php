<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 07 Jan 2018 03:36:43 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');
$error = array();
$array_part = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_part')->fetch();
if (empty($array_part)) {
    $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=part';
    $contents = nv_theme_alert($lang_module['error_data_part_title'], $lang_module['error_data_part_content'], 'danger', $url, $lang_module['part_manage']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['workforce_add'] = $lang_module['workforce_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
    $row['part'] = $row['part_old'] = !empty($row['part']) ? array_map('intval', explode(',', $row['part'])) : array();
} else {
    $row['id'] = 0;
    $row['first_name'] = '';
    $row['last_name'] = '';
    $row['gender'] = 1;
    $row['birthday'] = 0;
    $row['main_phone'] = '';
    $row['other_phone'] = '';
    $row['main_email'] = '';
    $row['other_email'] = '';
    $row['address'] = '';
    $row['position'] = '';
    $row['knowledge'] = '';
    $row['image'] = '';
    $row['addtime'] = 0;
    $row['edittime'] = 0;
    $row['useradd'] = 0;
    $row['status'] = 1;
    $row['userid'] = 0;
    $row['jointime'] = 0;
    $row['part'] = $row['part_old'] = array();
    $row['salary'] = 0;
    $row['allowance'] = 0;

    $row['username'] = '';
    $row['password'] = '';
    $row['looppassword'] = '';
}

$row['redirect'] = $nv_Request->get_string('redirect', 'get,post', '');

if ($nv_Request->isset_request('submit', 'post')) {

    $username = $row['username'] = $nv_Request->get_title('username', 'post', '');
    $row['password'] = $nv_Request->get_title('password', 'post', '', 0);
    $row['looppassword'] = $nv_Request->get_title('looppassword', 'post', '', 0);

    $firstname = $row['first_name'] = $nv_Request->get_title('first_name', 'post', '');
    $lastname = $row['last_name'] = $nv_Request->get_title('last_name', 'post', '');
    $gender = $row['gender'] = $nv_Request->get_int('gender', 'post', 0);
    $row['salary'] = $nv_Request->get_string('salary', 'post', 0);
    $row['salary'] = preg_replace('/[^0-9]/', '', $row['salary']);
    $row['allowance'] = $nv_Request->get_string('allowance', 'post', 0);
    $row['allowance'] = preg_replace('/[^0-9]/', '', $row['allowance']);

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('birthday', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['birthday'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['birthday'] = 0;
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('jointime', 'post'), $m)) {
        $_hour = 23;
        $_min = 23;
        $row['jointime'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['jointime'] = 0;
    }

    $row['main_phone'] = $nv_Request->get_title('main_phone', 'post', '');
    $row['other_phone'] = $nv_Request->get_title('other_phone', 'post', '');
    $email = $row['main_email'] = $nv_Request->get_title('main_email', 'post', '');
    $row['other_email'] = $nv_Request->get_title('other_email', 'post', '');
    $row['address'] = $nv_Request->get_title('address', 'post', '');
    $row['position'] = $nv_Request->get_title('position', 'post', '');
    $row['part'] = $nv_Request->get_typed_array('part', 'post', 'int');
    $row['knowledge'] = $nv_Request->get_string('knowledge', 'post', '');
    $row['image'] = $nv_Request->get_title('image', 'post', '');
    $row['userid'] = $nv_Request->get_int('userid', 'post', 0);

    if (is_file(NV_DOCUMENT_ROOT . $row['image'])) {
        $row['image'] = substr($row['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['image'] = '';
    }

    $ingroups = $db->query("SELECT config_value FROM " . NV_CONFIG_GLOBALTABLE . " WHERE module='workforce' AND config_name='groups_use'")->fetch();

    $part = !empty($row['part']) ? implode(',', $row['part']) : '';

    if (empty($row['userid']) && empty($row['username'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_userid'],
            'input' => 'userid'
        ));
    } elseif (empty($row['first_name'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_first_name'],
            'input' => 'first_name'
        ));
    } elseif (empty($row['last_name'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_last_name'],
            'input' => 'last_name'
        ));
    } elseif (empty($row['birthday'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_birthday'],
            'input' => 'birthday'
        ));
    } elseif (empty($row['main_phone'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_main_phone'],
            'input' => 'main_phone'
        ));
    } elseif (empty($row['main_email'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_main_email'],
            'input' => 'main_email'
        ));
    } elseif (empty($row['password'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_password'],
            'input' => 'password'
        ));
    } elseif (empty($row['looppassword'])) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_looppassword'],
            'input' => 'looppassword'
        ));
    } elseif ($row['password'] != $row['looppassword']) {
        nv_jsonOutput(array(
            'error' => 1,
            'msg' => $lang_module['error_required_pass'],
            'input' => 'looppassword'
        ));
    }

    nv_createaccount($username, $row['password'], $email, $ingroups, $firstname, $lastname, $gender);

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $ingroups = implode(",", $ingroups);
                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (userid, first_name, last_name, gender, birthday, main_phone, other_phone, main_email, other_email, address, knowledge, image, jointime, position, part, salary, allowance, addtime, edittime, useradd) VALUES (:userid, :first_name, :last_name, :gender, :birthday, :main_phone, :other_phone, :main_email, :other_email, :address, :knowledge, :image, :jointime, :position, :part, :salary, :allowance, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $user_info['userid'] . ')';
                $data_insert = array();
                $data_insert['userid'] = $row['userid'];
                $data_insert['first_name'] = $row['first_name'];
                $data_insert['last_name'] = $row['last_name'];
                $data_insert['gender'] = $row['gender'];
                $data_insert['birthday'] = $row['birthday'];
                $data_insert['main_phone'] = $row['main_phone'];
                $data_insert['other_phone'] = $row['other_phone'];
                $data_insert['main_email'] = $row['main_email'];
                $data_insert['other_email'] = $row['other_email'];
                $data_insert['address'] = $row['address'];
                $data_insert['knowledge'] = $row['knowledge'];
                $data_insert['image'] = $row['image'];
                $data_insert['jointime'] = $row['jointime'];
                $data_insert['position'] = $row['position'];
                $data_insert['part'] = $part;
                $data_insert['salary'] = $row['salary'];
                $data_insert['allowance'] = $row['allowance'];
                $new_id = $db->insert_id($_sql, 'id', $data_insert);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET userid = :userid, first_name = :first_name, last_name = :last_name, gender = :gender, birthday = :birthday, main_phone = :main_phone, other_phone = :other_phone, main_email = :main_email, other_email = :other_email, address = :address, knowledge = :knowledge, image = :image, jointime = :jointime, position = :position, part = :part, salary = :salary, allowance = :allowance, edittime = ' . NV_CURRENTTIME . ' WHERE id=' . $row['id']);
                $stmt->bindParam(':userid', $row['userid'], PDO::PARAM_INT);
                $stmt->bindParam(':first_name', $row['first_name'], PDO::PARAM_STR);
                $stmt->bindParam(':last_name', $row['last_name'], PDO::PARAM_STR);
                $stmt->bindParam(':gender', $row['gender'], PDO::PARAM_INT);
                $stmt->bindParam(':birthday', $row['birthday'], PDO::PARAM_INT);
                $stmt->bindParam(':main_phone', $row['main_phone'], PDO::PARAM_STR);
                $stmt->bindParam(':other_phone', $row['other_phone'], PDO::PARAM_STR);
                $stmt->bindParam(':main_email', $row['main_email'], PDO::PARAM_STR);
                $stmt->bindParam(':other_email', $row['other_email'], PDO::PARAM_STR);
                $stmt->bindParam(':address', $row['address'], PDO::PARAM_STR);
                $stmt->bindParam(':knowledge', $row['knowledge'], PDO::PARAM_STR, strlen($row['knowledge']));
                $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
                $stmt->bindParam(':jointime', $row['jointime'], PDO::PARAM_INT);
                $stmt->bindParam(':position', $row['position'], PDO::PARAM_INT);
                $stmt->bindParam(':part', $part, PDO::PARAM_INT);
                $stmt->bindParam(':salary', $row['salary'], PDO::PARAM_STR);
                $stmt->bindParam(':allowance', $row['allowance'], PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $new_id = $row['id'];
                }
            }

            if ($new_id > 0) {

                if ($row['part'] != $row['part_old']) {
                    $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_part_detail (userid, part) VALUES(:userid, :part)');
                    foreach ($row['part'] as $partid) {
                        if (!in_array($partid, $row['part_old'])) {
                            $sth->bindParam(':userid', $row['userid'], PDO::PARAM_INT);
                            $sth->bindParam(':part', $partid, PDO::PARAM_INT);
                            $sth->execute();
                        }
                    }

                    foreach ($row['part_old'] as $partid) {
                        if (!in_array($partid, $row['part'])) {
                            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_part_detail WHERE userid = ' . $row['userid'] . ' AND part=' . $partid);
                        }
                    }
                }
                if (empty($row['id'])) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['title_workforce'], $workforce_list[$user_info['userid']]['fullname'] . " " . $lang_module['content_workforce'] . " " . $row['last_name'] . " " . $row['first_name'], $workforce_list[$user_info['userid']]['fullname']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['title_workforce'], $workforce_list[$user_info['userid']]['fullname'] . " " . $lang_module['edit_workforce'] . " " . $row['last_name'] . " " . $row['first_name'], $workforce_list[$user_info['userid']]['fullname']);
                }

                $nv_Cache->delMod($module_name);

                if (!empty($row['redirect'])) {

                    $url = nv_redirect_decrypt($row['redirect']);
                } else {
                    $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                }
                Header('Location: ' . $url);
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

$row['birthday'] = !empty($row['birthday']) ? date('d/m/Y', $row['birthday']) : '';
$row['jointime'] = !empty($row['jointime']) ? date('d/m/Y', $row['jointime']) : '';
$row['salary'] = !empty($row['salary']) ? $row['salary'] : '';
$row['allowance'] = !empty($row['allowance']) ? $row['allowance'] : '';

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

$userinfo = array();
if ($row['userid'] > 0) {
    $userinfo = $rows = $db->query('SELECT userid, first_name, last_name, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $row['userid'])->fetch();
    $userinfo['fullname'] = nv_show_name_user($userinfo['first_name'], $userinfo['last_name'], $userinfo['username']);
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('URL_USERS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&get_user_json=1');

foreach ($array_gender as $index => $value) {
    $ck = $index == $row['gender'] ? 'checked="checked"' : '';
    $xtpl->assign('GENDER', array(
        'index' => $index,
        'value' => $value,
        'checked' => $ck
    ));
    $xtpl->parse('main.gender');
}

foreach ($array_part_list as $partid => $rows_i) {
    $sl = in_array($partid, $row['part']) ? ' selected="selected"' : '';
    $xtpl->assign('pid', $rows_i[0]);
    $xtpl->assign('ptitle', $rows_i[1]);
    $xtpl->assign('pselect', $sl);
    $xtpl->parse('main.parent_loop');
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

$page_title = $lang_module['workforce_add'];
$array_mod_title[] = array(
    'title' => $lang_module['workforce'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
);
$array_mod_title[] = array(
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';