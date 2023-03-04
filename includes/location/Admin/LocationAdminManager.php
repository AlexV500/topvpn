<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class LocationAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'location/Admin/LocationAdminList.php';
                (new LocationAdminList('LocationModel', 'topvpn_location'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'location/Admin/LocationAdminAdd.php';
                (new LocationAdminAdd('LocationModel', 'topvpn_location'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'location/Admin/LocationAdminEdit.php';
                (new LocationAdminEdit('LocationModel', 'topvpn_location'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'location/Admin/LocationAdminPosition.php';
                (new LocationAdminPosition('LocationModel', 'topvpn_location'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'location/Admin/LocationAdminDelete.php';
                (new LocationAdminDelete('LocationModel', 'topvpn_location'))->init()->render()->show();
                break;
        }
    }
}