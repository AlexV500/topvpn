<?php
require_once V_CORE_LIB . 'Admin/AdminList.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormInputs.php';
require_once V_CORE_LIB . 'View/Admin/AdminHtmlFormOutputs.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/Additional/TopVPNAdditionalModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
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
        $this->initRows($this->atts);
        $this->addRelationParam('device', $this->getItemFromCollection('deviceModel'), 'device_sys_name');
        $this->addRelationParam('streaming', $this->getItemFromCollection('streamingModel'), 'streaming_sys_name');
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
            $output .= "<td class='edit'><a href='" . add_query_arg( $this->getCurrentURL() ) . "&action=edit_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $device['device_sys_name'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit2.png'/></a></td>";
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
            if (isset($streaming['streaming_font_logo_size']) && (trim($streaming['streaming_font_logo_size']) !== '')){
                $size = 'font-size: '.$result['streaming_font_logo_size'].';';
            } else {
                $size = 'font-size: 1.4rem;';
            }
            if (isset($streaming['streaming_font_logo_color']) && (trim($streaming['streaming_font_logo_color']) !== '')){
                $color = 'color: '.$streaming['streaming_font_logo_color'].';';
            } else {
                $color = 'color: #6c737b;';
            }
            $style = $color .' '. $size;
            if($streaming['streaming_font_logo'] == ''){
                $output .= "<td class='topvpnLogo'><img src='". V_CORE_URL .'includes/images/streaming/'.$streaming['streaming_logo']."' width='21px' height='21px'></td>";
            } else {
                $output .= '<td class="topvpnLogo"><span class="streaming-font-logo" style="'.$style.'"><i class="'.$streaming['streaming_font_logo'].'"></i></td>';
            }
            $output .= "<td class='topvpnName'>" . stripslashes( wp_filter_nohtml_kses( $streaming['streaming_name'] ) )  . "</td>";
            $output .= "<td class='edit'><a href='" . add_query_arg( $this->getCurrentURL() ) . "&action=edit_additional&foreign_id=" . $this->getId() . "&cat_sys_name=" . $streaming['streaming_sys_name'] . "' alt='" . __( 'Редактировать', 'topvpn') . "'><img src='". V_CORE_URL ."admin/images/edit2.png'/></a></td>";
            $output .= '<tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        $this->render = $output;
        return $this;
    }
}