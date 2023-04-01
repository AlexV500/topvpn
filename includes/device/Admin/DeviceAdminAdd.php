<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';

class DeviceAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $device;

    public function init( array $atts = []) : object
    {
        $this->setFormFills(
            [
                'device_name' => '',
                'device_sys_name' => '',
                'device_page_uri' => '',
                'device_font_logo' => '',
                'device_font_logo_size' => '',
                'device_font_logo_color' => '',
              //  'position' => '',
                'active' => 1,
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_device'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            $this->setResultMessages('DeviceModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить Device');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_device" enctype="multipart/form-data" action="admin.php?page=show_devicelist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Device','device_name', $this->getFormFill('device_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Device','device_sys_name', $this->getFormFill('device_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Device page URI','device_page_uri', $this->getFormFill('device_page_uri'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','device_logo', 'namefield','required');
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Логотип(Шрифт)','device_font_logo', $this->getFormFill('device_font_logo'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Логотип(Размер шрифта)','device_font_logo_size', $this->getFormFill('device_font_logo_size'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Цвет логотипа','device_font_logo_color', $this->getFormFill('device_font_logo_color'),'namefield','');
        $output .= '</div>';
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_device" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}