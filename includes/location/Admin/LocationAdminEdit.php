<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';

class LocationAdminEdit extends AdminPostAction {


    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'location_name' => $data['location_name'],
                'location_sys_name' => $data['location_sys_name'],
                'location_page_uri' => $data['location_page_uri'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_location'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('LocationModel', $result->getResultStatus(), $result->getResultMessage());

        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать Location '.$this->getFormFill('location_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_location" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Location','location_name', $this->getFormFill('location_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Location','location_sys_name', $this->getFormFill('location_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Location page URI','location_page_uri', $this->getFormFill('location_page_uri'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','location_logo', 'namefield','required');

        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_location" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}