<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

class PaymentsAdminManager{

    public static function init(){

        $action = HTTP::getGet('action');

        switch ($action){
            case '':
                require_once V_PLUGIN_INCLUDES_DIR . 'payments/Admin/PaymentsAdminList.php';
                (new PaymentsAdminList('PaymentsModel', 'topvpn_payments'))->init()->render()->show();
                break;
            case 'add':
                require_once V_PLUGIN_INCLUDES_DIR . 'payments/Admin/PaymentsAdminAdd.php';
                (new PaymentsAdminAdd('PaymentsModel', 'topvpn_payments'))->init()->render()->show();
                break;
            case 'edit':
                require_once V_PLUGIN_INCLUDES_DIR . 'payments/Admin/PaymentsAdminEdit.php';
                (new PaymentsAdminEdit('PaymentsModel', 'topvpn_payments'))->init()->render()->show();
                break;
            case 'position':
                require_once V_PLUGIN_INCLUDES_DIR . 'payments/Admin/PaymentsAdminPosition.php';
                (new PaymentsAdminPosition('PaymentsModel', 'topvpn_payments'))->init();
                break;
            case 'delete':
                require_once V_PLUGIN_INCLUDES_DIR . 'payments/Admin/PaymentsAdminDelete.php';
                (new OSAdminDelete('PaymentsModel', 'topvpn_payments'))->init()->render()->show();
                break;
        }
    }
}