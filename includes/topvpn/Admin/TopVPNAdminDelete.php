<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';

class TopVPNAdminDelete extends AdminPostAction{

    public function init() : object {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->deleteName = $data['vpn_name'];
        $this->setFormFills(
            [
                'vpn_name' => $data['vpn_name'],
            ]
        );

        if ( isset( $_POST['delete_vpn'] )){
            $result = $this->getModel()->deleteRow($data);
            $this->setResultStatus($result->getResultStatus());
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('TopVPNModel', $result->getResultMessage());
                $this->setResultMessages('TopVPNModel','ok', $this->getOk('TopVPNModel'));
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('TopVPNModel', $result->getResultMessage());
                $this->setResultMessages('TopVPNModel','error', $this->getError('TopVPNModel'));
            }
        }
        return $this;
    }

    public function render() : object {

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить VPN '.$this->deleteName);
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_vpn" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить VPN ' . $this->deleteName . '?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="1" name="delete_vpn"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="vpn_id"/>' .
                '<input type="hidden" value="delete" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }
}