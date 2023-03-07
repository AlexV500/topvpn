<?php
require_once V_CORE_LIB . 'Admin/AdminPostAction.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/Additional/TopVPNAdditionalModel.php';

class TopVPNAdditionalAdminDelete extends AdminPostAction{

    protected string $catSysName;

    public function init(array $atts = []): object
    {
        $this->setId(HTTP::getGet('foreign_id'));
        $this->addItemToCollection(new TopVPNModel('topvpn_vpn'), 'vpnModel');
        $vpn = $this->getItemFromCollection('vpnModel')->getRowById($this->getId());
        $reqData = [
            'foreign_id' => $this->getId(),
            'cat_sys_name' => HTTP::getGet('cat_sys_name'),
            'vpn' => $vpn['vpn_name']
        ];

        if (isset($_POST['delete_addit_vpn_info'])) {
            $result = $this->getModel()->deleteRow($reqData['foreign_id'], $reqData['cat_sys_name']);
            $this->setResultMessages('TopVPNAdditionalModel', $result->getResultStatus(), $result->getResultMessage());
        }
        return $this;
    }

    public function render() : object{
          
        $vpn = $this->getItemFromCollection('vpnModel')->getRowById($this->getId());

        $output = '';
        $output .= AdminHtmlFormInputs::renderAdminHead('Удалить Доп. Информацию '.$vpn['vpn_name'] . HTTP::getGet('cat_sys_name'));
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        if($this->getResultStatus() == 'waiting') {
            $output .= '<form id="delete_vpn" enctype="" action="" method="post">';
            $output .= '<div class="topvpn delete">' .
                '<div class="field">' .
                '<label class="field-label first required">' .
                '<span class="label">' .
                __('Вы действительно хотите удалить Доп. Информацию '.$vpn['vpn_name'] . HTTP::getGet('cat_sys_name') . '?', 'topvpn') .
                '</span>' .
                '</label>' .
                '</div>' .
                '<div class="mb-20"></div>' .
                '<input class="button button-primary" type="submit" value="' . __('Удалить', 'topvpn') . '"/>' .
                '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Отмена', 'topvpn') . '</a>' .
                '<input type="hidden" value="1" name="delete_addit_vpn_info"/>' .
                '<input type="hidden" value="' . $this->getId() . '" name="vpn_id"/>' .
                '<input type="hidden" value="delete_addit_vpn_info" name="action"/>';
            $output .= '</form>';
        } else {
            $output .= '<a class="cancel button" href="' . $this->getCurrentURL() . '">' . __('Назад', 'topvpn') . '</a>';
        }
        $this->render = $output;
        return $this;
    }

    private function getFormFillsFromResponse(array $respData): array
    {
        return [
            'id' => $respData['id'],
            'foreign_id' => $respData['foreign_id'],
            'cat_sys_name' => $respData['cat_sys_name'],
            'top_status_description' => $respData['top_status_description'],
            'short_description' => $respData['short_description'],
            'rating' => $respData['rating'],
            'rating_description' => $respData['rating_description'],
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
            'top_status_description' => '',
            'short_description' => '',
            'rating' => '',
            'rating_description' => '',
            'add_position' => 0,
            'active' => 1,
            'created' => '',
            'updated' => '',
        ];
    }
}