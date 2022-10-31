<?php


class TopVPNAdminPosition{

    protected object $model;

    public function __construct($model, $dbTable){
        $this->model = new $model($dbTable);
    }

    public function init(){

        $id = $_GET['vpn_id'];
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

    protected function getModel(){

        return $this->model;
    }


}