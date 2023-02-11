<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class DeviceAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'device/Admin/DeviceAdminList.php';
                (new DeviceAdminList('DeviceModel', 'topvpn_device'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'device/Admin/DeviceAdminAdd.php';
                (new DeviceAdminAdd('DeviceModel', 'topvpn_device'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'device/Admin/DeviceAdminEdit.php';
                (new DeviceAdminEdit('DeviceModel', 'topvpn_device'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'device/Admin/DeviceAdminPosition.php';
                (new DeviceAdminPosition('DeviceModel', 'topvpn_device'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'device/Admin/DeviceAdminDelete.php';
                (new CustomizationAdminDelete('DeviceModel', 'topvpn_device'))->init()->render()->show();
                break;
        }
    }
}