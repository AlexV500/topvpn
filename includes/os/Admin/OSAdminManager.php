<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class OSAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'os/Admin/OSAdminList.php';
                (new OSAdminList('OSModel', 'topvpn_os'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'os/Admin/OSAdminAdd.php';
                (new OSAdminAdd('OSModel', 'topvpn_os'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'os/Admin/OSAdminEdit.php';
                (new OSAdminEdit('OSModel', 'topvpn_os'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'os/Admin/OSAdminPosition.php';
                (new OSAdminPosition('OSModel', 'topvpn_os'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'os/Admin/OSAdminDelete.php';
                (new CustomizationAdminDelete('OSModel', 'topvpn_os'))->init()->render()->show();
                break;
        }
    }
}