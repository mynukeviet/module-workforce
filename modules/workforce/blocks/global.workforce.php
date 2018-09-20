<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (quanglh268@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Tue, 05 Feb 2018 08:34:29 GMT
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

if (!nv_function_exists('nv_workforce_list')) {

    function nv_block_config_workforce_list($module, $data_block, $lang_block)
    {
        $html = '';

        $array_updown = array(
            'addtime' => $lang_block['updown1']
        );
        $html .= '<div class="form-group">';
        $html .= '	<label class="control-label col-sm-6">' . $lang_block['numrow'] . ':</label>';
        $html .= '	<div class="col-sm-18"><input class="form-control" type="text" name="config_numrow" value="' . $data_block['numrow'] . '"/></div>';
        $html .= '</div>';
        return $html;
    }

    function nv_block_config_workforce_list_submit($module, $lang_block)
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['numrow'] = $nv_Request->get_int('config_numrow', 'post', 10);
        return $return;
    }

    function nv_workforce_list($block_config)
    {
        global $global_config, $site_mods, $nv_Cache, $module_name, $my_footer, $lang_module, $nv_Request, $user_info, $db;

        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];

        // khai báo thư viện global cho block

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module . '/block.workforce.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module . '/block.workforce.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        if ($module != $module_name) {
            include NV_ROOTDIR . '/modules/workforce/language/' . NV_LANG_DATA . '.php';
            $my_footer .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . 'themes/' . $block_theme . '/js/workforce.js"></script>';
        }

        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . ' ORDER BY addtime DESC  LIMIT ' . $block_config['numrow'];
        $list = $nv_Cache->db($sql, 'id', $module);

        $xtpl = new XTemplate('block.workforce.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/workforce');
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('MODULE_NAME', $module_name);
        $xtpl->assign('BLOCK_THEME', $block_theme);

        if (!empty($list)) {
            foreach ($list as $view) {
                $view['fullname'] = nv_show_name_user($view['first_name'], $view['last_name']);
                $xtpl->assign('WORKFORCE_VIEW', $view);
                $xtpl->parse('main.workforce');
            }
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_workforce_list($block_config);
}

