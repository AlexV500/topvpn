<?php
require_once V_CORE_LIB . 'Components/IComponents.php';

abstract class Components implements IComponents{

    protected string $languageSysNameGet = 'en_EN';
    protected object $model;
    protected string $currentURL;
    protected string $dbTable;
    protected string $render;

    public function __construct($model, $dbTable){
        $this->model = new $model($dbTable);
        $this->dbTable = $dbTable;
    }

    public function getModel(){
        return $this->model;
    }

    public function changeModelConf($model){
        $this->model = $model;
    }

    public function setLang(){
        return $this->getModel()->setLang($this->languageSysNameGet);
    }

    public function getLanguageSysNameGet(){
        return $this->languageSysNameGet;
    }

    protected function setCurrentURL() : object{
        $this->currentURL = HTTP::getCurrentURL();
        return $this;
    }

    protected function getCurrentURL() : string{
        return $this->currentURL;
    }

    abstract public function init( array $atts = []);
    abstract public function render();

    public function show(){
        echo $this->render;
    }
}