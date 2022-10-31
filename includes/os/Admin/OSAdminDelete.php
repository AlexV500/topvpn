<?php


class OSAdminDelete extends AdminPostAction
{

    public function init() : object {
        $this->setId(HTTP::getGet('os_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'os_name' => $data['os_name'],
            ]
        );

        if ( isset( $_POST['delete_os'] )){
            $result = $this->getModel()->deleteRow($this->getId());
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('OSModel', 'OS '.HTTP::getGet('os_name').' удален успешно!');
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
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить OS '.HTTP::getGet('os_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit-os" enctype="" action="" method="post">';
        $output .= '<div class="topvpn delete">' .
            '<div class="field">' .
            '<label class="field-label first required">' .
            '<span class="label">' .
            __( 'Вы действительно хотите удалить OS?', 'topvpn' ) .
            '</span>' .
            '</label>' .
            '</div>' .
            '<div class="mb-20"></div>'.
            '<input class="button button-primary" type="submit" value="' . __( 'Удалить', 'topvpn' ) . '"/>' .
            '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __( 'Отмена', 'topvpn' ) . '</a>' .
            '<input type="hidden" value="deleteOSAdm" name="delete_os"/>' .
            '<input type="hidden" value="'.$this->getId().'" name="os_id"/>' .
            '<input type="hidden" value="delete" name="action"/>';
        $output .=
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}