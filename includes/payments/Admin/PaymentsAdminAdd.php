<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class PaymentsAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $payments;

    public function init( array $atts = []) : object
    {
        $this->setFormFills(
            [
                'payments_name' => '',
                'payments_sys_name' => '',
            //    'payments_logo' => '',
              //  'position' => '',
                'active' => 1,
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_payments'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            $this->setResultMessages('PaymentsModel', $result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить платежную систему');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_payments" enctype="multipart/form-data" action="admin.php?page=show_paymentslist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название платежной системы','payments_name', $this->getFormFill('payments_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название платежной системы','payments_sys_name', $this->getFormFill('payments_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','payments_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_payments" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}