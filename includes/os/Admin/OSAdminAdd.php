<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';

class OSAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $os;

    public function init() : object
    {
        $this->setFormFills(
            [
                'os_name' => '',
                'os_sys_name' => '',
                'os_logo' => '',
                'active' => '',
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_os'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('OSModel', 'OS добавлен успешно!');
                $this->setResultMessages('OSModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('OSModel', $result->getResultMessage());
                $this->setResultMessages('OSModel','error', $this->getError());
            }
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить OS');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_os" enctype="" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название OS','os_name', $this->getFormFill('os_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название OS','os_sys_name', $this->getFormFill('os_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','os_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_os" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}