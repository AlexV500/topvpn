<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class CustomizationAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'customization/Admin/CustomizationAdminList.php';
                (new CustomizationAdminList('CustomizationModel', 'topvpn_customization'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'customization/Admin/CustomizationAdminAdd.php';
                (new CustomizationAdminAdd('CustomizationModel', 'topvpn_customization'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'customization/Admin/CustomizationAdminEdit.php';
                (new CustomizationAdminEdit('CustomizationModel', 'topvpn_customization'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'customization/Admin/CustomizationAdminPosition.php';
                (new CustomizationAdminPosition('CustomizationModel', 'topvpn_customization'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'customization/Admin/CustomizationAdminDelete.php';
                (new CustomizationAdminDelete('CustomizationModel', 'topvpn_customization'))->init()->render()->show();
                break;
        }
    }
}