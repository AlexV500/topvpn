<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class PaymentsAdminEdit extends AdminPostAction {

    protected array $postData;

    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'payments_name' => $data['payments_name'],
                'payments_sys_name' => $data['payments_sys_name'],
            //    'payments_logo' => $data['payments_logo'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_payments'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('PaymentsModel', $result->getResultStatus(), $result->getResultMessage());

        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать платежную систему '.$this->getFormFill('payments_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_payments" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название платежную систему','payments_name', $this->getFormFill('payments_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название платежную систему','payments_sys_name', $this->getFormFill('payments_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','payments_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_payments" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}