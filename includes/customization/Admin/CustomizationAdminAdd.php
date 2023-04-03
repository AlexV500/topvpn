<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'customization/Model/CustomizationModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class CustomizationAdminAdd extends AdminPostAction{

    protected array $postData;
    protected array $customization;

    public function init( array $atts = []) : object
    {
        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $this->setFormFills(
            [
                'customization_name' => '',
                'page_uri' => '',
                'lang' => '',
                'header_content' => '',
                'header_image' => '',
                'comparison_header_content' => '',
                'description_header_content' => '',
                'active' => 1,
                'position' => '',
                'created' => '',
            ]
        );

        if ( isset( $_POST['add_customization'] )){
            $this->setPostData();
            $result = $this->getModel()->addRow($this->postData);
            $this->setResultMessages('CustomizationModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить сниппет');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_customization" enctype="multipart/form-data" action="admin.php?page=show_topvpncustomizationlist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название сниппета','customization_name', $this->getFormFill('customization_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('URI страницы','page_uri', $this->getFormFill('page_uri'),'namefield','required');
        $output .= AdminHtmlFormInputs::textarea('Контент шапки', 'header_content', $this->getFormFill('header_content'), '');
        $output .= AdminHtmlFormInputs::textarea('Изображение шапки', 'header_image', $this->getFormFill('header_image'), '');
        $output .= AdminHtmlFormInputs::textarea('Блок заголовка таблицы сравнения', 'comparison_header_content', $this->getFormFill('comparison_header_content'), '');
        $output .= AdminHtmlFormInputs::textarea('Блок заголовка описаний(внизу)', 'description_header_content', $this->getFormFill('description_header_content'), '');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelectorField($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_customization" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}