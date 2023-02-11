<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'customization/Model/CustomizationModel.php';


class CustomizationAdminDelete extends AdminPostAction{

    public function init( array $atts = []) : object {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'customization_name' => $data['customization_name'],
            ]
        );

        if ( isset( $_POST['delete_customization'] )){
            $result = $this->getModel()->deleteRow($data);
            $this->setResultMessages('CustomizationModel', $result->getResultStatus(), $result->getResultMessage());
            $this->setResultStatus('done');
        }
        return $this;
    }

    public function render(): object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить снипет ' . $this->getFormFill('customization_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if ($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_customization" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить снипеты ' . $this->getFormFill('customization_name') . '?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="deleteCustomizationAdm" name="delete_customization"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="customization_id"/>' .
                '<input type="hidden" value="delete" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }
}