<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class LangAdminManager{

    public static function init(){

        $action = HTTP::getRequestParam('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'lang/Admin/LangAdminList.php';
                (new LangAdminList('LangModel', 'topvpn_lang'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'lang/Admin/LangAdminAdd.php';
                (new LangAdminAdd('LangModel', 'topvpn_lang'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'lang/Admin/LangAdminEdit.php';
                (new LangAdminEdit('LangModel', 'topvpn_lang'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'lang/Admin/LangAdminPosition.php';
                (new LangAdminPosition('LangModel', 'topvpn_lang'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'lang/Admin/LangAdminDelete.php';
                (new LangAdminDelete('LangModel', 'topvpn_lang'))->init()->render()->show();
                break;
        }
    }
}