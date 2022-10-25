<?php


class AdminActions{

    public function setPosition($model){

        $set = '';
        $id = '';

        if ( isset( $_GET['position_set'] )){
            $set = $_GET['position_set'];
        }
        if ( isset( $_GET['id'] )){
            $id = $_GET['id'];
        }

        if($set == 'up'){
            (new $model)->positionUp($id);
        }
        if($set == 'down'){
            (new $model)->positionDown($id);
        }
    }

    public function selectLanguageAdm(){

        if(isset($_SESSION['fxlang'])){
            $languageSysNameGet = $_SESSION['fxlang'];
        } else {
            $languageSysNameGet = '';
        }
        return $languageSysNameGet;
    }
}