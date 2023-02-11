<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'customization/Model/CustomizationModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class CustomizationAdminEdit extends AdminPostAction{

    protected array $postData;
    protected array $customization;

    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'customization_name' => $data['customization_name'],
                'page_uri' => $data['page_uri'],
                'lang' => $data['lang'],
                'header_content' => $data['header_content'],
                'active' => $data['active'],
                'created' => $data['created'],
            ]
        );

        if ( isset( $_POST['edit_customization'] )){
            $this->setPostData();
            $result = $this->getModel()->editRow($this->postData);
            $this->setResultMessages('CustomizationModel',$result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Редактировать сниппет');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_customization" enctype="multipart/form-data" action="admin.php?page=show_topvpncustomizationlist&action=add" method="post">';
        $output .= AdminHtmlFormInputs::input('Название сниппета','customization_name', $this->getFormFill('customization_name'),'namefield','required');
        $output .= AdminHtmlFormInputs::input('URI страницы','page_uri', $this->getFormFill('page_uri'),'namefield','required');
        $output .= AdminHtmlFormInputs::textarea('Контент шапки', 'header_content', $this->getFormFill('header_content'), '');
        $output .= AdminHtmlFormInputs::renderAdminLanguageSelectorField($this->getAllLanguageAdm(), $this->getLanguageSysNameGet());
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="edit_customization" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Редактировать');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}