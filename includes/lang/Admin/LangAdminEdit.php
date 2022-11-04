<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class LangAdminEdit extends AdminPostAction{

    protected array $postData;

    public function init()
    {
        $this->setId(HTTP::getGet('lang_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'lang_name' => $data['lang_name'],
                'lang_sys_name' => $data['lang_sys_name'],
                'lang_logo' => $data['logo'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_lang'] )){
            foreach ($this->getFormFills() as $key => $value){
                $this->postData[$key] = $_POST[$key];
                $formFill[$key] = $_POST[$key];
                $this->setFormFills($formFill);
            }
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('LangModel', 'Lang '.$this->getFormFill('lang_name').' изменен успешно!');
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
        $output .= '<form id="edit_lang" enctype="" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Языка','lang_name', $this->getFormFill('lang_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Языка','lang_sys_name', $this->getFormFill('lang_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Флаг','lang_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', [1 => 'Да', 0 => 'Нет'], $this->getFormFill('active'), '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}