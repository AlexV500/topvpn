<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class LangAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $lang;

    public function init() : object
    {
        $this->setFormFills(
            [
                'lang_name' => '',
                'lang_sys_name' => '',
            //    'lang_logo' => '',
            //    'position' => '',
                'active' => 1,
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_lang'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            $this->setResultMessages('LangModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить Язык');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_lang" enctype="multipart/form-data" action="admin.php?page=show_topvpnlanguagelist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Языка','lang_name', $this->getFormFill('lang_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название OS','lang_sys_name', $this->getFormFill('lang_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Флаг','lang_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
    //    $output .= '<input type="hidden" name="lang_logo" value="">';
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_lang" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}