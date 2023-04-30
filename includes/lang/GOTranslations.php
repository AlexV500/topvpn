<?php

require_once V_CORE_LIB . 'Utils/TranslationsUtils.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/Translations/TranslationsModel.php';
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';

class GOTranslations{

    public $model;
    public $langModel;
    public $wrightingMode;
    public $currentLocale;
    public $locales;
    public $defaultLocale;

    public function __construct(string $model, string $dbTable, bool $wrightingMode = false)
    {
        $this->wrightingMode = $wrightingMode;
        $this->currentLocale = get_locale();
        $this->setModel($model, $dbTable);
        $this->init();
    }

    public function init(){
        $langModel = new LangModel('topvpn_lang');
        $this->locales = $langModel->getLocales();
        $row = $langModel->getDefaultLocale();
        $this->defaultLocale = $row['lang_sys_name'];
    }

    public function cdTranslate($phrase){
        if(is_admin()){
            return $phrase;
        }
        if(!$this->getWrightingMode()){
            if($this->getCurrentLocale() === $this->getDefaultLocale()){
               return $phrase;
            }
        }
//        if($this->getCurrentLocale() === $this->getDefaultLocale()){
//            return $phrase;
//        }
        $data = [];
        $phrase = trim($phrase);
        $phraseStrippedWsp = TranslationsUtils::stripWhitespaces($phrase);
        $phraseStrippedWspPr = TranslationsUtils::goPrepareKey($phraseStrippedWsp);
        $phraseInStorageByKey = $this->getModel()->getRowByPk('tkey', $phraseStrippedWspPr);

        if($phraseInStorageByKey !== null){
            $currentLocale = $this->getCurrentLocale();
            $phraseStrippedWspInStorage = TranslationsUtils::stripWhitespaces($phraseInStorageByKey[$currentLocale]);
            $phraseStrippedWspInStoragePr = TranslationsUtils::goPrepareKey($phraseStrippedWspInStorage);
            if($phraseStrippedWspInStoragePr !== ''){
                return $phraseInStorageByKey[$currentLocale];
            } else {
                return $phrase;
            }
        } else {

            if($this->getWrightingMode()){
                if(count($this->getLocales()) > 0){
                    foreach ($this->getLocales() as $id => $locale){
                        if($locale === $this->getDefaultLocale()){
                            $data['tkey'] = $phraseStrippedWspPr;
                            $data[$locale] = $phrase;
                            $data['active'] = 1;
                            $data['created'] = date('Y-m-d H:i:s');
                            $data['updated'] = date('Y-m-d H:i:s');
                            $result = $this->getModel()->autoAdd($data);
                        //    $this->setResultMessages('LangModel'.$id, $result->getResultStatus(), $result->getResultMessage());
                        }
                    }
                }
            }
        } return $phrase;

    }

    protected function getWrightingMode() : bool{
        return $this->wrightingMode;
    }

    protected function getCurrentLocale() : string{
        return $this->currentLocale;
    }

    protected function getLocales() : array{
        return $this->locales;
    }

    protected function getDefaultLocale() : string{
        return $this->defaultLocale;
    }

    protected function getModel() : object{
        return $this->model;
    }

    protected function setModel($model, $dbTable) : object{
        $this->model = new $model($dbTable);
        return $this;
    }

}