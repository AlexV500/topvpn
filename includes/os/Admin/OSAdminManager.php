<?php


class OSAdminManager{

    public static function init(){

        $action = HTTP::getRequestParam('action');

        switch ($action){
            case '':
                (new OSAdminList('OSModel', 'os'))->init()->render()->show();
                break;
            case 'add':
                (new OSAdminAdd('OSModel', 'os'))->init()->render()->show();
                break;
            case 'edit':
                (new OSAdminEdit('OSModel', 'os'))->init()->render()->show();
                break;
            case 'position':
                (new OSAdminPosition('OSModel', 'os'))->init();
                break;
            case 'delete':
                (new OSAdminDelete('OSModel', 'os'))->init()->render()->show();
                break;
        }
    }
}