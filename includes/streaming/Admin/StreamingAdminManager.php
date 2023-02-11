<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class StreamingAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Admin/StreamingAdminList.php';
                (new StreamingAdminList('StreamingModel', 'topvpn_streaming'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Admin/StreamingAdminAdd.php';
                (new StreamingAdminAdd('StreamingModel', 'topvpn_streaming'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Admin/StreamingAdminEdit.php';
                (new StreamingAdminEdit('StreamingModel', 'topvpn_streaming'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Admin/StreamingAdminPosition.php';
                (new StreamingAdminPosition('StreamingModel', 'topvpn_streaming'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Admin/StreamingAdminDelete.php';
                (new StreamingAdminDelete('StreamingModel', 'topvpn_streaming'))->init()->render()->show();
                break;
        }
    }
}