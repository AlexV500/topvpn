<?php


class TopVPNAdminManager{

    public static function init(){

        $action = HTTP::getRequestParam('action');

        switch ($action){
            case '':
                $render = new TopVPNAdminList();
                break;
            case 'add':
                $render = new TopVPNAdminAdd();
                break;
            case 'edit':
                $render = new TopVPNAdminEdit();
                break;
            case 'position':
                $render = new TopVPNAdminPosition();
                break;
            case 'delete':
                $render = new TopVPNAdminDelete();
                break;
        }

        $render->init()->show();
    }
}