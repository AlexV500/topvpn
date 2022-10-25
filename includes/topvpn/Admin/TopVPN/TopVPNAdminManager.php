<?php


class TopVPNAdminManager{

    public static function init(){

        $action = HTTP::getRequestParam('action');

        switch ($action){
            case '':
                (new TopVPNAdminList)->init()->show();
                break;
            case 'add':
                (new TopVPNAdminAdd)->init()->show();
                break;
            case 'edit':
                (new TopVPNAdminEdit)->init()->show();
                break;
            case 'position':
                (new TopVPNAdminPosition)->init()->show();
                break;
            case 'delete':
                (new TopVPNAdminDelete)->init()->show();
                break;
        }
    }
}