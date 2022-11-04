<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class LangAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $lang;

    public function init()
    {
        $this->setFormFills(
            [
                'lang_name' => '',
                'lang_sys_name' => '',
                'lang_logo' => '',
                'active' => '',
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_lang'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('LangModel', 'Язык добавлен успешно!');
                $this->setResultMessages('LangModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('LangModel', $result->getResultMessage());
                $this->setResultMessages('LangModel','error', $this->getError());
            }
        }
        return $this;
    }

    public function render()
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить Язык');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_lang" enctype="" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Языка','lang_name', $this->getFormFill('lang_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название OS','lang_sys_name', $this->getFormFill('lang_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Флаг','lang_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}