<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';

class OSAdminEdit extends AdminPostAction {


    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'os_name' => $data['os_name'],
                'os_sys_name' => $data['os_sys_name'],
                'os_font_logo' => $data['os_font_logo'],
                'os_font_logo_size' => $data['os_font_logo_size'],
                'os_font_logo_color' => $data['os_font_logo_color'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_os'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('OSModel', $result->getResultStatus(), $result->getResultMessage());

        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать OS '.$this->getFormFill('os_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_os" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название OS','os_name', $this->getFormFill('os_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название OS','os_sys_name', $this->getFormFill('os_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','os_logo', 'namefield','required');
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Логотип(Шрифт)','os_font_logo', $this->getFormFill('os_font_logo'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Логотип(Размер шрифта)','os_font_logo_size', $this->getFormFill('os_font_logo_size'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Цвет логотипа','os_font_logo_color', $this->getFormFill('os_font_logo_color'),'namefield','');
        $output .= '</div>';
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_os" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}