<?php

require_once V_CORE_LIB . 'Components/Components.php';

abstract class AdminActions extends Components{

 //   protected object $model;
    protected int $id;
    protected array $allLanguageAdm;
    protected array $resultMessages = [];
    protected string $logoPath;
    protected string $screenPath;
    protected array $ok;
    protected array $errors;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
        $this->model = $this->model->setAdminMode();
    }

//    public function __construct($model, $dbTable){
//        $this->model = new $model($dbTable);
//        $this->dbTable = $dbTable;
//        $this->setCurrentURL();
//    }
//
//    public function getModel(){
//        return $this->model;
//    }


    protected function setId($id){

        $this->id = (int) $id;
    }

    protected function getId(){

        return $this->id;
    }

    public function getLogoPath() : string
    {
        return $this->logoPath;
    }

    public function setLogoPath( string $path) : object
    {
        $this->logoPath = $path;
        return $this;
    }

    public function getScreenPath() : string
    {
        return $this->screenPath;
    }

    public function setScreenPath( string $path) : object
    {
        $this->screenPath = $path;
        return $this;
    }

    public function getAllLanguageAdm(){
        return $this->allLanguageAdm;
    }

    public function switchMultiLangMode(){
        return $this->getModel()->switchMultiLangMode($this->languageSysNameGet);
    }

    protected function initAllLanguageAdm( string $languageModel, string $dbTable) : object{
        $this->allLanguageAdm = (new $languageModel($dbTable))->getAllRows(true, false);
        return $this;
    }

    protected function selectLanguageAdm() : object{

        if ( isset( $_POST['selectLanguageAdm'] )){
            $languageSysNamePost = $_POST['languageSysName'];
            if($languageSysNamePost == 'no_lang'){
                $_SESSION['fxlang'] = 'no_lang';
            } else {
                $_SESSION['fxlang'] = $languageSysNamePost;
            }
        }

        if(isset($_SESSION['fxlang'])){
            if($_SESSION['fxlang'] == 'no_lang') {
                $this->languageSysNameGet = '';
            } else {
                $this->languageSysNameGet = $_SESSION['fxlang'];
            }
        } else {
            $this->languageSysNameGet = '';
        }
        return $this;
    }

    protected function getStatusTitle(int $rowStatus) : string{

        $status = '';

        if($rowStatus == 0){
            $status = 'Скрытый';
        }
        if($rowStatus == 1){
            $status = 'Активный';
        }
        return $status;
    }

    protected function setOk($type, $ok) : object{
        $this->ok[$type] = $ok;
        return $this;
    }

    protected function getOk($type) : string{
        return $this->ok[$type];
    }

    protected function setError( string $type, string $error) : object{
        $this->errors[$type] = $error;
        return $this;
    }

    protected function getError($type) : string{
        return $this->errors[$type];
    }

    protected function setResultMessages( string $type, string $status, string $message) : object{
        $this->resultMessages[$type][] = [$status => $message];
        return $this;
    }

    protected function getResultMessages() : array{
        return $this->resultMessages;
    }
}