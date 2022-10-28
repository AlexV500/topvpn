<?php


abstract class AdminActions implements IAdminActions {

    protected object $model;
    protected string $languageSysNameGet;
    protected array $allLanguageAdm;
    protected string $currentURL;
    protected array $resultMessages;
    protected array $ok;
    protected array $errors;
    protected string $render;

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

    protected function initAllLanguageAdm( string $languageModel, string $dbTable) : object{
        $this->allLanguageAdm = (new $languageModel($dbTable))->getAllLanguageAdm();
        return $this;
    }

    protected function setCurrentURL() : object{
        $this->currentURL = HTTP::getCurrentURL();
        return $this;
    }

    protected function getCurrentURL(){
        return $this->currentURL;
    }

    protected function selectLanguageAdm() : object{

        if(isset($_SESSION['fxlang'])){
            $this->languageSysNameGet = $_SESSION['fxlang'];
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

    protected function setError($type, $error) : object{
        $this->errors[$type] = $error;
        return $this;
    }

    protected function getError($type) : string{
        return $this->errors[$type];
    }

    protected function setResultMessages($type, $status, $message) : object{
        $this->resultMessages[$type][] = [$status => $message];
        return $this;
    }

    protected function getResultMessages() : array{
        return $this->resultMessages;
    }

    abstract public function init();
    abstract public function render();

    public function show(){
        echo $this->render;
    }
}