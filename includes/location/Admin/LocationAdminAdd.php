<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';

class LocationAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $location;

    public function init( array $atts = []) : object
    {
        $this->setFormFills(
            [
                'location_name' => '',
                'location_sys_name' => '',

                'active' => 1,
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_location'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            $this->setResultMessages('LocationModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить Location');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_location" enctype="multipart/form-data" action="admin.php?page=show_locationlist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Location','location_name', $this->getFormFill('location_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Location','location_sys_name', $this->getFormFill('location_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','location_logo', 'namefield','required');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_location" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}