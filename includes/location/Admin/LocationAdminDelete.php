<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';

class LocationAdminDelete extends AdminPostAction{

    public function init( array $atts = []) : object {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'location_name' => $data['location_name'],
            ]
        );

        if ( isset( $_POST['delete_location'] )){
            $result = $this->getModel()->deleteRow($data);
            $this->setResultMessages('LocationModel', $result->getResultStatus(), $result->getResultMessage());
            $this->setResultStatus('done');
        }
        return $this;
    }

    public function render(): object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить Location ' . $this->getFormFill('location_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if ($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_location" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить Location ' . $this->getFormFill('location_name') . '?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="deleteLocationAdm" name="delete_location"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="location_id"/>' .
                '<input type="hidden" value="delete" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }
}