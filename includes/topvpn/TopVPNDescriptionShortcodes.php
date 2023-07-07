<?php
require_once V_CORE_LIB . 'Public/PublicItem.php';
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class TopVPNDescriptionShortcodes extends PublicItem{

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object
    {
        $this->switchMultiLangMode();
        $this->addItemToCollection(new DeviceModel('topvpn_device'), 'deviceModel');
        $this->addItemToCollection(new StreamingModel('topvpn_streaming'), 'streamingModel');
        $this->addItemToCollection(new PaymentsModel('topvpn_payments'), 'paymentsModel');
        $this->addItemToCollection(new LocationModel('topvpn_location'), 'locationModel');
        $this->initRowData('vpn_sys_name');
        return $this;
    }


    public function render() : string
    {
        $atts = $this->getAtts();
        if(isset($atts['shortcode'])) {
            switch ($atts['shortcode']){
                case 'payments' :
                    return $this->renderPayments();
                case 'devices' :
                    return $this->renderDevices();
                case 'streaming' :
                    return $this->renderStreamingPlatforms();
                case 'security' :
                    return $this->renderSecurityFeautures();
                case 'torrenting' :
                    return $this->renderTorrenting();
                case 'privacy' :
                    return $this->renderPrivacy();
                case 'server_locations' :
                    return $this->renderServerLocations();
                case '' :
                    return '';
            }
        }
    }

    private function renderServerLocations(){

        $output = '';
        if(trim($this->getRowData()['server_locations'] == '')){
            return $output;
        }
        $exploded = explode(';', $this->getRowData()['server_locations']);
        if (empty($exploded)) {
            return $output;
        }
        $output .= '<table class="table table-sm table-striped table-bordered mt-4">';
        $output .= '<thead>';
        $output .= '<tr class="table-secondary"><th scope="col">'. goTranslate("Continent") .'</th><th scope="col">'. goTranslate("Number of Countries").'</th></tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach ($exploded as $item) {
            $string = trim($item);
            if($string == ''){
                continue;
            }
            if (strpos($string, "#") !== false) {
                $exploded2 = explode('#', $string);
                $output .= '<tr><td>'. $exploded2[0] .'</td><td>'.$exploded2[1].'</td></tr>';
            }
        }
        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }

    private function renderPrivacy(){

        $output = '';
        $output .= '<table class="table table-sm table-striped table-bordered mt-4">';
        $output .= '<thead>';
        $output .= '<tr class="table-secondary"><th scope="col">'. goTranslate("Data Type") .'</th><th scope="col">'. goTranslate("Logged by") .' '. $this->getRowData()['vpn_name'].'</th></tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        $output .= '<tr><td>'. goTranslate("Browsing Activity") .'</td><td>'.$this->getRowData()['browsing_activity_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Device Information") .'</td><td>'.$this->getRowData()['device_information_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("DNS Queries") .'</td><td>'.$this->getRowData()['dns_queries_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Individual Bandwidth Usage") .'</td><td>'.$this->getRowData()['individual_bandwidth_usage_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Individual Connection Timestamps") .'</td><td>'.$this->getRowData()['individual_connection_timestamps_logged'].'</td></tr>';

        $output .= '<tr><td>'. goTranslate("ISP") .'</td><td>'.$this->getRowData()['isp_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Number of Simultaneous Connections") .'</td><td>'.$this->getRowData()['no_of_simult_connect_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Originating IP Address") .'</td><td>'.$this->getRowData()['originating_ip_address_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Account Information") .'</td><td>'.$this->getRowData()['account_information_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("VPN Server IP") .'</td><td>'.$this->getRowData()['vpn_server_ip_logged'].'</td></tr>';

        $output .= '<tr><td>'. goTranslate("VPN Server Location") .'</td><td>'.$this->getRowData()['vpn_server_location_logged'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Date of Last Connection") .'</td><td>'.$this->getRowData()['date_of_last_connect_logged'].'</td></tr>';


        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }

    private function renderTorrenting(){

        $output = '';
        $output .= '<table class="table table-sm table-striped table-bordered mt-4">';
        $output .= '<thead>';
        $output .= '<tr class="table-secondary"><th scope="col">'. goTranslate("Torrenting Attribute") .'</th><th scope="col">'. goTranslate("Result").'</th></tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        $output .= '<tr><td>'. goTranslate("Average Download Bitrate") .'</td><td>'.$this->getRowData()['average_download_bitrate'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("No. of P2P Servers") .'</td><td>'.$this->getRowData()['no_of_p2p_servers'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Logging Policy") .'</td><td>'.$this->getRowData()['logging_policy'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Kill Switch") .'</td><td>'.$this->getRowData()['kill_switch'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Port Forwarding") .'</td><td>'.$this->getRowData()['port_forwarding'].'</td></tr>';

        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }

    private function renderSecurityFeautures(){

        $output = '';
        $output .= '<table class="table table-sm table-striped table-bordered mt-4">';
        $output .= '<thead>';
        $output .= '<tr class="table-secondary"><th scope="col">'. goTranslate("Streaming Platform") .'</th><th scope="col">'. goTranslate("Works with ") .' '. $this->getRowData()['vpn_name'].'</th></tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        $output .= '<tr><td>'. goTranslate("Protocols") .'</td><td>'.$this->getRowData()['protocols'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Encryption") .'</td><td>'.$this->getRowData()['encryption'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Security") .'</td><td>'.$this->getRowData()['security'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Advanced Features") .'</td><td>'.$this->getRowData()['advanced_features'].'</td></tr>';

        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }

    private function renderStreamingPlatforms(){

        $streamingSystems = $this->getItemFromCollection('streamingModel')->getStreamingByVPNId($this->getRowData()['id']);
        $output = '';
        $output .= '<table class="table table-sm table-striped mt-4 vpn-key-data">';
        $output .= '<thead>';
        $output .= '<tr class="table-secondary"><th scope="col">'. goTranslate("Streaming Platform") .'</th><th scope="col">'. goTranslate("Works with ") .' '. $this->getRowData()['vpn_name'].'</th></tr>';
        $output .= '</thead>';
        $output .= '<tbody>';

        foreach ((array)$streamingSystems as $i => $streaming) {
            $output .= '<tr><td>'. $streaming['streaming_name'] .'</td><td>Yes</td></tr>';
        }

        $output .= '</tbody>';
        $output .= '</table>';
        return $output;
    }

    private function renderPayments(){
        $output = '';
        $paymentsSystems = $this->getItemFromCollection('paymentsModel')->getPaymentsByVPNId($this->getRowData()['id']);
        if (count($paymentsSystems) == 0){
            return '';
        }
        $output .= '<ul class="payments-list">';
        foreach ((array)$paymentsSystems as $i => $payment) {
            $logo = PAYEMENTS_LOGO_PATH . $payment['payments_logo'];
            $output .= '<li class="payments-list-li">';
            $output .= '<img data-toggle="tooltip" src="' . $logo . '" alt="' . $payment['payments_name'] . '" title="' . $payment['payments_name'] . '" data-original-title="' . $payment['payments_name'] . '" class="device-icons-small"> ';
            $output .= $payment['payments_name'];
            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    private function renderDevices(){
        $output = '';
        $deviceSystems = $this->getItemFromCollection('deviceModel')->getDeviceByVPNId($this->getRowData()['id']);
        if (count($deviceSystems) == 0){
            return '';
        }
        $output .= '<ul class="device-list">';
        foreach ((array)$deviceSystems as $i => $device) {
            $deviceLogo = DEVICE_LOGO_PATH . $device['device_logo'];

            if(trim($device['device_font_logo']) == ""){
                $output1 .= '<li class="device-list-li"><img data-toggle="tooltip" src="' . $deviceLogo . '" alt="' . $device['device_name'] . '" title="' . $device['device_name'] . '" data-original-title="' . $device['device_name'] . '" class="device-icons-small"></li>';
            } else {
                if (isset($device['device_font_logo_size']) && (trim($device['device_font_logo_size']) !== '')){
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
                $output1 = '<div class="device-list-div"><data-toggle="tooltip" title="'.$device['device_name'].'" style="'.$style.'"><i class="'.$device['device_font_logo'].'"></i></div>';
                $output .= '<li class="device-list-li"><div class="d-flex justify-content-start">'.$output1.'<div class="device-list-div2"><a href="/'.$device['device_page_uri'].'"> '.$device['device_name'].'</a></div></div></li>';
            }

            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }
}