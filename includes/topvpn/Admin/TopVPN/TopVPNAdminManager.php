<?php


class TopVPNAdminManager{

    public static function init(){

        $action = HTTP::getRequestParam('action');

        switch ($action){
            case '':
                (new TopVPNAdminList('TopVPNModel', 'vpn'))->init()->show();
                break;
            case 'add':
                (new TopVPNAdminAdd('TopVPNModel', 'vpn'))->init()->show();
                break;
            case 'edit':
                (new TopVPNAdminEdit('TopVPNModel', 'vpn'))->init()->show();
                break;
            case 'position':
                (new TopVPNAdminPosition('TopVPNModel', 'vpn'))->init()->show();
                break;
            case 'delete':
                (new TopVPNAdminDelete('TopVPNModel', 'vpn'))->init()->show();
                break;
        }
    }
}