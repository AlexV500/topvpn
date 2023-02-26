<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'translations/Model/TranslationsModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class TranslationsAdminAdd extends AdminPostAction{

    public function init( array $atts = []) : object{

        $this->initAllLanguageAdm('LangModel', 'topvpn_lang');
    }

    public function render() : object{

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Добавить Переводы');
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_lang" enctype="multipart/form-data" action="admin.php?page=show_topvpntranslationslist&action=add" method="post">';
        $allLanguages = $this->getAllLanguageAdm();
        foreach ($allLanguages as $language) {
            $output .= AdminHtmlFormInputs::textarea($language['lang_name'], 'translations_'.$language['lang_sys_name'], $this->getFormFill('translations_'.$language['lang_sys_name']), '');
        }
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="created" value="">';
        $output .= '<input type="hidden" name="add_lang" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Добавить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}