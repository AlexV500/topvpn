<?php


class TopVPNAdminDelete extends AdminPostAction{

    public function init() : object {
        $this->setId(HTTP::getGet('id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'vpn_name' => $data['vpn_name'],
            ]
        );

        if ( isset( $_POST['delete_vpn'] )){
            $result = $this->getModel()->deleteRow($this->getId());
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('TopVPNModel', 'VPN '.HTTP::getGet('vpn_name').' удален успешно!');
                $this->setResultMessages('TopVPNModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('TopVPNModel', $result->getResultMessage());
                $this->setResultMessages('TopVPNModel','error', $this->getError());
            }
        }
        return $this;
    }

    public function render() : object {

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить VPN '.HTTP::getGet('vpn_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit-topvpn" enctype="" action="" method="post">';
        $output .= '<div class="topvpn delete">' .
            '<div class="field">' .
            '<label class="field-label first required">' .
            '<span class="label">' .
            __( 'Вы действительно хотите удалить брокера?', 'topvpn' ) .
            '</span>' .
            '</label>' .
            '</div>' .
            '<div class="mb-20"></div>'.
            '<input class="button button-primary" type="submit" value="' . __( 'Удалить', 'topvpn' ) . '"/>' .
            '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __( 'Отмена', 'topvpn' ) . '</a>' .
            '<input type="hidden" value="deleteBrokerAdm" name="delete_vpn"/>' .
            '<input type="hidden" value="'.$this->getId().'" name="vpn_id"/>' .
            '<input type="hidden" value="delete" name="action"/>';
        $output .=
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}