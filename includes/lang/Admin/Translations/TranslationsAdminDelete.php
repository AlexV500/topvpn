<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/Translations/TranslationsModel.php';
require_once V_CORE_LIB . 'Utils/Collection.php';

class TranslationsAdminDelete extends AdminPostAction{

    protected array $data;
    protected array $postData;
    protected array $langData;
    protected array $defaultLocale;


    public function init()
    {
        $this->setId(HTTP::getGet('item_id'));
        $foreignId = HTTP::getGet('foreign_id');
        $data = $this->getModel()->getRowById($this->getId());
        $this->addItemToCollection(new LangModel('topvpn_lang'), 'LangModel');
        $langModel = $this->getItemFromCollection('LangModel');
        $this->defaultLocale = $langModel->getDefaultLocale();
        $this->langData = $langModel->getRowById($foreignId);
        $this->setFormFills(
            [
                'default_text' => $data[$this->defaultLocale['lang_sys_name']],
            ]
        );
        if ( isset( $_POST['delete_translation'] )){
            $result = $this->getModel()->deleteRow($data);
            $this->setResultMessages('LangModel',$result->getResultStatus(), $result->getResultMessage());
            $this->setResultStatus('done');
        }
        return $this;
    }

    public function render()
    {

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить '.$this->langData['lang_name'].' текст и все его переводы');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if ($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_translation" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                $this->getFormFill('default_text') .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить ' . $this->langData['lang_name'] . ' текст и все его переводы?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '&action=list_translations">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="deleteTranslationAdm" name="delete_translation"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="item_id"/>' .
                '<input type="hidden" value="delete" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '&action=list_translations">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }
}