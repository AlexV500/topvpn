<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class PaymentsAdminDelete extends AdminPostAction{

    public function init( array $atts = []) : object {
        $this->setId(HTTP::getGet('payments_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'payments_name' => $data['payments_name'],
            ]
        );

        if ( isset( $_POST['delete_payments'] )){
            $result = $this->getModel()->deleteRow($this->getId());
            $this->setResultMessages('PaymentsModel', $result->getResultStatus(), $result->getResultMessage());
            $this->setResultStatus('done');
        }
        return $this;
    }

    public function render(): object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить платежную систему ' . $this->deleteName);
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if ($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_payments" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить Payments ' . $this->deleteName . '?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="deletePaymentsAdm" name="delete_payments"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="payments_id"/>' .
                '<input type="hidden" value="delete" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }
}