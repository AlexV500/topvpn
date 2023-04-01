<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';

class StreamingAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $streaming;

    public function init( array $atts = []) : object
    {
        $this->setFormFills(
            [
                'streaming_name' => '',
                'streaming_sys_name' => '',
                'streaming_page_uri' => '',
                'streaming_font_logo' => '',
                'streaming_font_logo_size' => '',
                'streaming_font_logo_color' => '',
              //  'position' => '',
                'show_in_rating' => 0,
                'active' => 1,
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_streaming'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            $this->setResultMessages('StreamingModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить Streaming');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_streaming" enctype="multipart/form-data" action="admin.php?page=show_streaminglist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название Streaming','streaming_name', $this->getFormFill('streaming_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Системное название Streaming','streaming_sys_name', $this->getFormFill('streaming_sys_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('Streaming page URI','streaming_page_uri', $this->getFormFill('streaming_page_uri'),'namefield','required');
        $output .= AdminHtmlFormInputs::file('Логотип','streaming_logo', 'namefield','required');
        $output .= '<div class="inp-group">';
        $output .= AdminHtmlFormInputs::input('Логотип(Шрифт)','streaming_font_logo', $this->getFormFill('streaming_font_logo'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Логотип(Размер шрифта)','streaming_font_logo_size', $this->getFormFill('streaming_font_logo_size'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Цвет логотипа','streaming_font_logo_color', $this->getFormFill('streaming_font_logo_color'),'namefield','');
        $output .= '</div>';
        $output .= AdminHtmlFormInputs::select('Показывать в таблице рейтинга', 'show_in_rating', $this->getFormFill('show_in_rating'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_streaming" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}