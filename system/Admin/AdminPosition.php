<?php
require_once V_CORE_LIB . 'Utils/HTTP.php';

abstract class AdminPosition{

    protected object $model;

    public function __construct($model, $dbTable){
        $this->model = new $model($dbTable);
    }

    public function setPosition($id){

        $line = $_GET['line'];
        $currentURL = HTTP::getCurrentURL();

        if($line == 'up'){
            $positionUp = $this->getModel()->positionUp($id);
        }
        if($line == 'down'){
            $positionDown = $this->getModel()->positionDown($id);
        }
        if(($positionUp) or ($positionDown)){
            wp_redirect($currentURL);
        }
    }

    abstract public function init();

    protected function getModel(){

        return $this->model;
    }
}