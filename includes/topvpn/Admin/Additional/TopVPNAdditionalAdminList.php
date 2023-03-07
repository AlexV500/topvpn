<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/Additional/TopVPNAdditionalModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_CORE_LIB . 'Utils/Collection.php';

class TopVPNAdditionalAdminList extends AdminList{

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object
    {
        $this->setId(HTTP::getGet('item_id'));
        $this->addItemToCollection(new TopVPNModel('topvpn_vpn'), 'vpnModel');
        $this->addItemToCollection(new DeviceModel('topvpn_device'), 'deviceModel');
        $this->addItemToCollection(new StreamingModel('topvpn_streaming'), 'streamingModel');
        $this->addItemToCollection(new LocationModel('topvpn_location'), 'locationModel');
        $this->addItemToCollection(new TopVPNAdditionalModel('topvpn_vpn_additional'), 'vpnAdditionalModel');
        $this->initRows($this->atts);
        $this->addRelationParam('device', $this->getItemFromCollection('deviceModel'), 'device_sys_name');
        $this->addRelationParam('streaming', $this->getItemFromCollection('streamingModel'), 'streaming_sys_name');
        $this->addRelationParam('location', $this->getItemFromCollection('locationModel'), 'location_sys_name');
        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'logo' => __('Логотип', 'topvpn'),
            'name'         => __( 'Devices', 'topvpn' ),
            'edit'         => __( 'Редакт.', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        return $this;
    }

    public function render() : object {

        $vpn = $this->getItemFromCollection('vpnModel')->getRowById($this->getId());
        $deviceSystems = $this->getItemFromCollection('deviceModel')->getDeviceByVPNId($this->getId());
        $streamingSystems = $this->getItemFromCollection('streamingModel')->getStreamingByVPNId($this->getId());
        $locationSystems = $this->getItemFromCollection('locationModel')->getLocationByVPNId($this->getId());
        $additionalModel = $this->getItemFromCollection('vpnAdditionalModel');

        $output = '';
        $header = 'Список ' .$vpn['vpn_name'];
        $output .= AdminHtmlFormInputs::renderAdminHead($header);
        $output .= AdminHtmlFormOutputs::renderResultMessages($this->getResultMessages());
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), 'topvpnlist');
        $output .= '<tbody>';

        foreach ((array)$deviceSystems as $y => $device) {
            $output .= '<tr>';
            $output .= "<td class='topvpn-id'>";
            $output .= $device['id'];
            $output .= "</td>";
            if (isset($result['device_font_logo_size']) && (trim($device['device_font_logo_size']) !== '')){
                $size = 'font-size: '.$device['device_font_logo_size'].';';
            } else {
                $size = 'font-size: 1.4rem;';
            }
            if (isset($device['device_font_logo_color']) && (trim($device['device_font_logo_color']) !== '')){
                $color = 'color: '.$device['device_font_logo_color'].';';
            } else {
                $color = 'color: #6c737b;';
            }
            $style = $color .' '. $size;
            if($device['device_font_logo'] == ''){
                $output .= "<td class='topvpnLogo'><img src='". V_CORE_URL .'includes/images/device/'.$device['device_logo']."' width='21px' height='21px'></td>";
            } else {
                $output .= '<td class="topvpnLogo"><span class="device-font-logo" style="'.$style.'"><i class="'.$device['device_font_logo'].'"></i></td>';
            }

            $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $device['device_name'] ) )  . "</td>";
            $output .= "<td class='edit'><a href='" . $this->getCurrentURL() . "&action=edit_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $device['device_sys_name'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit2.png'/></a></td>";
            if($additionalModel->countRows($this->getId(), $device['device_sys_name']) > 0){
                $output .= "<td class='remove'>" .
                    "<a href='" . $this->getCurrentURL() . "&action=delete_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $device['device_sys_name'] . "' alt='" . __( 'Удалить', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/remove2.png'/></a>" . "</td>";
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'logo' => __('Логотип', 'topvpn'),
            'name'         => __( 'Стриминги', 'topvpn' ),
            'edit'         => __( 'Редакт.', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), 'topvpnlist');
        $output .= '<tbody>';

        foreach ((array)$streamingSystems as $z => $streaming) {
            $output .= '<tr>';
            $output .= "<td class='topvpn-id'>";
            $output .= $streaming['id'];
            $output .= "</td>";

            if($streaming['streaming_logo'] !== ''){
                $output .= "<td class='topvpnLogo'><img src='". V_CORE_URL .'includes/images/streaming/'.$streaming['streaming_logo']."' width='21px' height='21px'></td>";
            } else {
                $output .= '<td class="topvpnLogo"><span class="streaming-font-logo"><i class="fa fa-picture-o" aria-hidden="true"></i></td>';
            }
            $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $streaming['streaming_name'] ) )  . "</td>";
            $output .= "<td class='edit'><a href='" . $this->getCurrentURL() . "&action=edit_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $streaming['streaming_sys_name'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit2.png'/></a></td>";
            if($additionalModel->countRows($this->getId(), $streaming['streaming_sys_name']) > 0) {
                $output .= "<td class='remove'>" .
                    "<a href='" . $this->getCurrentURL() . "&action=delete_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $streaming['streaming_sys_name'] . "' alt='" . __('Удалить', 'topvpn') . "'><img src='" . V_CORE_URL . "admin/images/remove2.png'/></a>" . "</td>";
            }
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $this->setColumnDisplayNames(array(
            'id' => __( 'id', 'topvpn' ),
            'logo' => __('Логотип', 'topvpn'),
            'name'         => __( 'Location', 'topvpn' ),
            'edit'         => __( 'Редакт.', 'topvpn' ),
            'delete'       => __( 'Удалить', 'topvpn' ),
        ));
        $output .= '<table id="" class="wp-list-table widefat fixed" cellspacing="0">';
        $output .= AdminHtmlFormInputs::renderAdminHeadOfTableList($this->getColumnDisplayNames(), 'topvpnlist');
        $output .= '<tbody>';
        foreach ((array)$locationSystems as $z => $location) {
            $output .= '<tr>';
            $output .= "<td class='topvpn-id'>";
            $output .= $location['id'];
            $output .= "</td>";
            if(trim($location['location_logo']) !== ''){
                $output .= "<td class='topvpnLogo'><img src='". V_CORE_URL .'includes/images/location/'.$location['location_logo']."' width='16px' height='12px'></td>";
            } else {
                $output .= '<td class="topvpnLogo"><span class="streaming-font-logo"><i class="fa fa-picture-o" aria-hidden="true"></i></td>';
            }

            $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $location['location_name'] ) )  . "</td>";
            $output .= "<td class='edit'><a href='" . $this->getCurrentURL() . "&action=edit_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $location['location_sys_name'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit2.png'/></a></td>";
            if($additionalModel->countRows($this->getId(), $location['location_sys_name']) > 0) {
                $output .= "<td class='remove'>" .
                    "<a href='" . $this->getCurrentURL() . "&action=delete_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $location['location_sys_name'] . "' alt='" . __('Удалить', 'topvpn') . "'><img src='" . V_CORE_URL . "admin/images/remove2.png'/></a>" . "</td>";
            }
            $output .= '</tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $this->render = $output;
        return $this;
    }
}