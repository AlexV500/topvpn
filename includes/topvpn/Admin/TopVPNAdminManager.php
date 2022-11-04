<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class TopVPNAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNAdminList.php';
                (new TopVPNAdminList('TopVPNModel', 'topvpn_vpn'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNAdminAdd.php';
                (new TopVPNAdminAdd('TopVPNModel', 'topvpn_vpn'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNAdminEdit.php';
                (new TopVPNAdminEdit('TopVPNModel', 'topvpn_vpn'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNAdminPosition.php';
                (new TopVPNAdminPosition('TopVPNModel', 'topvpn_vpn'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNAdminDelete.php';
                (new TopVPNAdminDelete('TopVPNModel', 'topvpn_vpn'))->init()->render()->show();
                break;
        }
    }
}