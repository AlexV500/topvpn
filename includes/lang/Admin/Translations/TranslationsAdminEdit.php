<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/Translations/TranslationsModel.php';
require_once V_CORE_LIB . 'Utils/Collection.php';

class TranslationsAdminEdit extends AdminPostAction{

    protected array $postData;
    protected array $langData;
    protected array $defaultLocale;

    public function init( array $atts = [])
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
                $this->defaultLocale['lang_sys_name'] => $data[$this->defaultLocale['lang_sys_name']],
                $this->langData['lang_sys_name'] => $data[$this->langData['lang_sys_name']],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );
        if ( isset( $_POST['edit_translation'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('LangModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render(){

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать Перевод '.$this->langData['lang_name']);
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_translation" enctype="multipart/form-data" action="" method="post">';
        $output .= $this->getFormFill($this->defaultLocale['lang_sys_name']);
        $output .= AdminHtmlFormInputs::input('Перевод', $this->langData['lang_sys_name'], $this->getFormFill($this->langData['lang_sys_name']),'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');

        $output .= '<input type="hidden" name="'.$this->defaultLocale['lang_sys_name'].'" value="'.$this->getFormFill($this->defaultLocale['lang_sys_name']).'">';
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_translation" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}