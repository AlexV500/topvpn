<?php


class AdminActions{

    protected $model;
    protected $languageSysNameGet;
    protected $allLanguageAdm;
    protected $paginationCount;
    protected $rowsCount;
    protected $rowsData;
    protected $offset;
    protected $paged;
    protected $paginate;
    protected $columnDisplayNames;
    protected $currentURL;

    public function __construct($model, $dbTable){
        $this->model = new $model($dbTable);
    }

    public function getModel(){
        return $this->model;
    }

    public function getLanguageSysNameGet(){
        return $this->languageSysNameGet;
    }

    public function getAllLanguageAdm(){
        return $this->allLanguageAdm;
    }

    public function setLang(){
        return $this->getModel()->setLang($this->languageSysNameGet);
    }

    public function switchMultiLangMode(){
        return $this->getModel()->switchMultiLangMode($this->languageSysNameGet);
    }

    public function setRowsCount($activeMode) : object{
        $this->rowsCount = $this->getModel()->countAllRows($activeMode);
        return $this;
    }

    public function setRowsData($activeMode) : object{
        $this->rowsData = $this->getModel()->getAllRows($activeMode);
        return $this;
    }

    public function getRowsCount(){
        return $this->rowsCount;
    }

    public function getRowsData(){
        return $this->rowsData;
    }

    public function setPaginationCount($count = 20) : object{
        $this->paginationCount = $count;
        return $this;
    }

    public function getPaginationCount(){
        return $this->paginationCount;
    }

    protected function initAllLanguageAdm(){
        $this->allLanguageAdm = (new LanguageModel('languages'))->getAllLanguageAdm();
        return $this;
    }

    protected function getOffset(){
        return $this->offset;
    }

    protected function getPaged(){
        return $this->paged;
    }

    protected function getPaginate(){
        return $this->paginate;
    }

    protected function setPaginationConfig(){
        $paginationConfig = new PaginationConfig($this->getPaginationCount(), $this->getRowCount());
        $this->offset = $paginationConfig->getOffset();
        $this->paged = $paginationConfig->getPaged();
        $this->paginate = $paginationConfig->getPaginate();
    }

    protected function setColumnDisplayNames(array $columnDisplayNames) : object{
        $this->columnDisplayNames = $columnDisplayNames;
        return $this;
    }

    protected function getColumnDisplayNames(){
        return $this->columnDisplayNames;
    }

    protected function setCurrentURL() : object{
        $this->currentURL = HTTP::getCurrentURL();
        return $this;
    }

    protected function getCurrentURL(){
        return $this->currentURL;
    }

    protected function checkPositionAction(){

        $set = '';
        $id = '';

        if ( isset( $_GET['position_set'] )){
            $set = $_GET['position_set'];
        }
        if ( isset( $_GET['id'] )){
            $id = $_GET['id'];
        }

        if($set == 'up'){
            (new $this->model)->positionUp($id);
        }
        if($set == 'down'){
            (new $this->model)->positionDown($id);
        }
    }

    protected function selectLanguageAdm() : object{

        if(isset($_SESSION['fxlang'])){
            $this->languageSysNameGet = $_SESSION['fxlang'];
        } else {
            $this->languageSysNameGet = '';
        }
        return $this;
    }

    protected function getStatusTitle(int $rowStatus){

        $status = '';

        if($rowStatus == 0){
            $status = 'Скрытый';
        }
        if($rowStatus == 1){
            $status = 'Активный';
        }
        return $status;
    }
}