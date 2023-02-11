<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';

class StreamingAdminEdit extends AdminPostAction {


    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'streaming_name' => $data['streaming_name'],
                'streaming_sys_name' => $data['streaming_sys_name'],
                'streaming_font_logo' => $data['streaming_font_logo'],
                'streaming_font_logo_size' => $data['streaming_font_logo_size'],
                'streaming_font_logo_color' => $data['streaming_font_logo_color'],
                'active' => $data['active'],
                'updated' => $data['updated'],
            ]
        );

        if ( isset( $_POST['edit_streaming'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->getId(), $this->postData);
            $this->setResultMessages('StreamingModel', $result->getResultStatus(), $result->getResultMessage());

        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать Streaming '.$this->getFormFill('streaming_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="edit_streaming" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Streaming','streaming_name', $this->getFormFill('streaming_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Streaming','streaming_sys_name', $this->getFormFill('streaming_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','streaming_logo', 'namefield','required');
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Логотип(Шрифт)','streaming_font_logo', $this->getFormFill('streaming_font_logo'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Логотип(Размер шрифта)','streaming_font_logo_size', $this->getFormFill('streaming_font_logo_size'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Цвет логотипа','streaming_font_logo_color', $this->getFormFill('streaming_font_logo_color'),'namefield','');
        $output .= '</div>';
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_streaming" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}