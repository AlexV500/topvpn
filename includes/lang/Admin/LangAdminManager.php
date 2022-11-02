<?php


class LangAdminManager{

    public static function init(){

        $action = HTTP::getRequestParam('action');

        switch ($action){
            case '':
                (new LangAdminList('LangModel', 'Lang'))->init()->render()->show();
                break;
            case 'add':
                (new LangAdminAdd('LangModel', 'Lang'))->init()->render()->show();
                break;
            case 'edit':
                (new LangAdminEdit('LangModel', 'Lang'))->init()->render()->show();
                break;
            case 'position':
                (new LangAdminPosition('LangModel', 'Lang'))->init();
                break;
            case 'delete':
                (new LangAdminDelete('LangModel', 'Lang'))->init()->render()->show();
                break;
        }
    }
}