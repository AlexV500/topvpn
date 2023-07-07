<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/Additional/TopVPNAdditionalModel.php';

class TopVPNAdditionalAdminEdit extends AdminPostAction{

    public function init(array $atts = []): object
    {
        $this->setId(HTTP::getGet('foreign_id'));
        $this->addItemToCollection(new TopVPNModel('topvpn_vpn'), 'vpnModel');
        $reqData = [
            'foreign_id' => $this->getId(),
            'cat_sys_name' => HTTP::getGet('cat_sys_name'),
            'active' => 1,
            'created' => '',
            'updated' => '',
        ];

        $result = $this->getModel()->getAdditRow($reqData);
        $respData = $result->getResultData();

        if ($result->getResultStatus() == 'error') {
            $this->setFormFills($this->getDefaultFormFills($respData));
            $this->setResultMessages('TopVPNAdditionalModel', $result->getResultStatus(), $result->getResultMessage());
            return $this;
        }

//        if ($result->getResultStatus() == 'created') {
//            $this->setFormFills($this->getDefaultFormFills($respData));
//        } else {
//            $this->setFormFills($this->getFormFillsFromResponse($respData));
//        }

        $this->setFormFills($this->getFormFillsFromResponse($respData));

        if (isset($_POST['edit_addit_vpn_info']) && ($result->getResultStatus() !== 'error')) {
            $this->setPostData();
            $id = $this->getFormFills()['id'];
            $result = $this->getModel()->editRow($id, $this->postData);
            $this->setResultMessages('TopVPNAdditionalModel', $result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }



    public function render() : object{
          
        $vpn = $this->getItemFromCollection('vpnModel')->getRowById($this->getId());
        $header = 'Добавить Доп. Информацию ' .$vpn['vpn_name'];
        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead($header);
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<form id="add_additional" enctype="multipart/form-data" action="" method="post">';
        $output .= AdminHtmlFormInputs::select('Топ статус', 'top_status', $this->getFormFill('top_status'), [0 => 'Нет', 1 => 'Да'], '');
        $output .= AdminHtmlFormInputs::input('Описание топ статуса (регалии)','top_status_description', $this->getFormFill('top_status_description'),'namefield','');
        $output .= AdminHtmlFormInputs::textarea('Короткое описание', 'short_description', $this->getFormFill('short_description'), '');
        $output .= AdminHtmlFormInputs::textarea('Features', 'features', $this->getFormFill('features'), '');
        $output .= AdminHtmlFormInputs::input('Рейтинг','rating', $this->getFormFill('rating'),'namefield','');
        $output .= AdminHtmlFormInputs::input('Описание рейтинга','rating_description', $this->getFormFill('rating_description'),'namefield','');
        $output .= '<div class="inp-group">';
        $output .= '[Название{Описание}:Значение]; - показывать в таблице рейтинга и сравнения<br/>';
        $output .= '[!Название{Описание}:Значение]; - показывать в таблице сравнения<br/>';
        $output .= 'Название{Описание}:Значение; - показывать в таблице рейтинга<br/>';
        $output .= 'Примеры:<br/>';
        $output .= '[Android App {Android Application}: 8.8];<br/>
                    [!Android Installs {Android Installs}: 50M];<br/>
                    Speed: 7.6;<br/>';
        $output .= '</div>';
        $output .= AdminHtmlFormInputs::textarea('Rating Features', 'rating_features', $this->getFormFill('rating_features'), '');
        $output .= AdminHtmlFormInputs::input('Позиция','add_position', $this->getFormFill('add_position'),'namefield','');
        $output .= AdminHtmlFormInputs::select('Активный', 'active', $this->getFormFill('active'), [1 => 'Да', 0 => 'Нет'], '');
        $output .= '<input type="hidden" name="id" value="'.$this->getFormFill('id').'">';
        $output .= '<input type="hidden" name="created" value="'.$this->getFormFill('created').'">';
        $output .= '<input type="hidden" name="updated" value="">';
        $output .= '<input type="hidden" name="edit_addit_vpn_info" value="1">';
        $output .= AdminHtmlFormInputs::renderAdminFormSubmitButton('Изменить');
        $output .= '</form>';
        $this->render = $output;
        return $this;
    }

    private function getFormFillsFromResponse(array $respData): array
    {
        return [
            'id' => $respData['id'],
            'foreign_id' => $respData['foreign_id'],
            'cat_sys_name' => $respData['cat_sys_name'],
            'top_status' => $respData['top_status'],
            'top_status_description' => $respData['top_status_description'],
            'short_description' => $respData['short_description'],
            'features' => $respData['features'],
            'rating' => $respData['rating'],
            'rating_description' => $respData['rating_description'],
            'rating_features' => $respData['rating_features'],
            'add_position' => $respData['add_position'],
            'active' => $respData['active'],
            'created' => $respData['created'],
            'updated' => $respData['updated'],
        ];
    }

    private function getDefaultFormFills(array $respData): array
    {
        return [
            'id' => $respData['last_insert_id'],
            'foreign_id' => $respData['foreign_id'],
            'cat_sys_name' => $respData['cat_sys_name'],
            'top_status' => '',
            'top_status_description' => '',
            'short_description' => '',
            'features' => '',
            'rating' => '',
            'rating_description' => '',
            'rating_features' => '',
            'add_position' => 0,
            'active' => 1,
            'created' => '',
            'updated' => '',
        ];
    }
}