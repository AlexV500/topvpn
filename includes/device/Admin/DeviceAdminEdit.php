<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';

class DeviceAdminEdit extends AdminPostAction {


    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'device_name' => $data['device_name'],
                'device_sys_name' => $data['device_sys_name'],
                'device_font_logo' => $data['device_font_logo'],
                'device_font_logo_size' => $data['device_font_logo_size'],
                'device_font_logo_color' => $data['device_font_logo_color'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_device'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('DeviceModel', $result->getResultStatus(), $result->getResultMessage());

        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать Device '.$this->getFormFill('device_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_device" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Device','device_name', $this->getFormFill('device_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Device','device_sys_name', $this->getFormFill('device_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','device_logo', 'namefield','required');
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Логотип(Шрифт)','device_font_logo', $this->getFormFill('device_font_logo'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Логотип(Размер шрифта)','device_font_logo_size', $this->getFormFill('device_font_logo_size'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Цвет логотипа','device_font_logo_color', $this->getFormFill('device_font_logo_color'),'namefield','');
        $output .= '</div>';
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_device" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}