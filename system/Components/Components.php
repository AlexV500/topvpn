<?php
require_once V_CORE_LIB . 'Components/IComponents.php';

abstract class Components implements IComponents{

    protected string $languageSysNameGet = 'en_US';
    protected object $model;
    protected string $currentURL;
    protected string $dbTable;
    protected string $render;
    protected array $relationNames = [];
    protected object $relModelCollection;
    protected array $atts;

    public function __construct($model, $dbTable, $atts = []){
        $this->setAtts($atts);
        $this->model = new $model($dbTable);
        $this->dbTable = $dbTable;
        $this->relModelCollection = new Collection();
    }

    public function getModel(){
        return $this->model;
    }

    public function getDbTable(){
        return $this->getDbTable();
    }

    public function changeModelConf($model){
        $this->model = $model;
    }

    public function setAtts($atts) : object{
        $this->atts = $atts;
        return $this;
    }

    public function getAtts(){
        return $this->atts;
    }

    public function hasAttribute(string $attributeName): bool
    {
        return isset($this->atts[$attributeName]);
    }

    public function hasRelationActive(): bool
    {
        foreach ($this->relationNames as $attributeName) {
            if ($this->hasAttribute($attributeName)) {
                return true;
            }
        }
        return false;
    }

    public function getActiveRelationAttributeName(): string
    {
        foreach ($this->relationNames as $attributeName) {
            if ($this->hasAttribute($attributeName)) {
                return $this->getAttribute($attributeName);
            }
        }
        return false;
    }

    public function getAttribute(string $attributeName)
    {
        return $this->atts[$attributeName];
    }

    public function setLang(){
        return $this->getModel()->setLang($this->languageSysNameGet);
    }

    public function getLanguageSysNameGet(){
        return $this->languageSysNameGet;
    }

    public function switchMultiLangMode(){
        $lang = '';
        $atts = $this->getAtts();
        if(isset($atts['lang'])) {
            $lang = $atts['lang'];
        }
        return $this->getModel()->switchMultiLangMode($lang);
    }

    public function setLimitCount($count = 10) : object{
        $this->model = $this->model->setLimitCount($count);
        return $this;
    }

    public function addItemToCollection( object $obj, string $key){
        $this->getRelModelCollection()->addItem($obj, $key);
        return $this;
    }

    public function getItemFromCollection(string $key){
        try {
            return $this->getRelModelCollection()->getItem($key);
        } catch (Exception $key) {
            print "The collection doesn't contain anything called '$key'";
        }
    }

    public function getRelModelCollection(){
        return $this->relModelCollection;
    }

//    protected function setCurrentURL() : object{
//        $this->currentURL = HTTP::getCurrentURL();
//        return $this;
//    }

    protected function getCurrentURL(array $config = []) : string{
//        $url = HTTP::getCurrentURL();
//        if(isset($config['remove']))
        return HTTP::getCurrentURL();
    }

    abstract public function init();
    abstract public function render();

    public function show(){
        echo $this->render;
    }
}