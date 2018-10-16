<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 02 Dec 2015 08:26:04 GMT
 */
define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Duyệt tất cả các ngôn ngữ
$language_query = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
while (list ($lang) = $language_query->fetch(3)) {
    $mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'workforce'");
    while (list ($mod, $mod_data) = $mquery->fetch(3)) {
        $sql = array();
        $data = array();

        $sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . " ADD  position varchar(100) NOT NULL AFTER jointime;";

        $sql[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_history_salary(
          id smallint(4) unsigned NOT NULL AUTO_INCREMENT,
          userid mediumint(8) unsigned NOT NULL,
          salary double unsigned NOT NULL,
          allowance double unsigned NOT NULL DEFAULT '0' COMMENT 'Phụ cấp',
          useradd mediumint(8) NOT NULL,
          addtime varchar(10) NOT NULL,
          UNIQUE KEY userid (userid,addtime)
          ) ENGINE=MyISAM";

        foreach ($sql as $_sql) {
            try {
                $db->query($_sql);
            } catch (PDOException $e) {
                //
            }
        }
        $nv_Cache->delMod($mod);
    }
}
die('OK');