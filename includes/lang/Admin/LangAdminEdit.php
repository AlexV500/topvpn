<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class LangAdminEdit extends AdminPostAction{

    protected array $postData;

    public function init( array $atts = [])
    {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'lang_name' => $data['lang_name'],
                'lang_sys_name' => $data['lang_sys_name'],
                'lang_sys_name_before' => $data['lang_sys_name'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_lang'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('LangModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render()
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать Язык '.$this->getFormFill('lang_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_lang" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Языка','lang_name', $this->getFormFill('lang_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Языка','lang_sys_name', $this->getFormFill('lang_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Флаг','lang_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_lang" value="1">';
        $output .= '<input type="hidden" name="lang_sys_name_before" value="'.$this->getFormFill('lang_sys_name_before').'">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}