<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/LangModel.php';

class LangAdminDelete extends AdminPostAction{

    public function init() : object {
        $this->setId(HTTP::getGet('lang_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'lang_name' => $data['lang_name'],
            ]
        );

        if ( isset( $_POST['delete_lang'] )){
            $result = $this->getModel()->deleteRow($this->getId());
            if ($result->getResultStatus() == 'ok'){
                $this->setOk('LangModel', 'Язык '.HTTP::getGet('lang_name').' удален успешно!');
                $this->setResultMessages('LangModel','ok', $this->getOk());
            }
            if ($result->getResultStatus() == 'error'){
                $this->setError('LangModel', $result->getResultMessage());
                $this->setResultMessages('LangModel','error', $this->getError());
            }
        }
        return $this;
    }


    public function render() : object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить Язык '.HTTP::getGet('lang_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="delete_lang" enctype="" action="" method="post">';
        $output .= '<div class="topvpn delete">' .
            '<div class="field">' .
            '<label class="field-label first required">' .
            '<span class="label">' .
            __( 'Вы действительно хотите удалить Язык?', 'topvpn' ) .
            '</span>' .
            '</label>' .
            '</div>' .
            '<div class="mb-20"></div>'.
            '<input class="button button-primary" type="submit" value="' . __( 'Удалить', 'topvpn' ) . '"/>' .
            '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __( 'Отмена', 'topvpn' ) . '</a>' .
            '<input type="hidden" value="deleteLangAdm" name="delete_lang"/>' .
            '<input type="hidden" value="'.$this->getId().'" name="lang_id"/>' .
            '<input type="hidden" value="delete" name="action"/>';
        $output .=
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }
}