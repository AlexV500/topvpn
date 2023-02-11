<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';

class StreamingAdminDelete extends AdminPostAction{

    public function init( array $atts = []) : object {
        $this->setId(HTTP::getGet('item_id'));
        $data = $this->getModel()->getRowById($this->getId());
        $this->setFormFills(
            [
                'streaming_name' => $data['streaming_name'],
            ]
        );

        if ( isset( $_POST['delete_streaming'] )){
            $result = $this->getModel()->deleteRow($data);
            $this->setResultMessages('StreamingModel', $result->getResultStatus(), $result->getResultMessage());
            $this->setResultStatus('done');
        }
        return $this;
    }

    public function render(): object
    {
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить Streaming ' . $this->getFormFill('streaming_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if ($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_streaming" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить Streaming ' . $this->getFormFill('streaming_name') . '?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="deleteStreamingAdm" name="delete_streaming"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="streaming_id"/>' .
                '<input type="hidden" value="delete" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }
}