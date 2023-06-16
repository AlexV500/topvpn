<?php
require_once V_CORE_LIB . 'Public/PublicItem.php';
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class TopVPNDescriptionAfter extends PublicItem{

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
        $deviceSystems = $this->getItemFromCollection('deviceModel')->getDeviceByVPNId($this->getRowData()['id']);
        $streamingSystems = $this->getItemFromCollection('streamingModel')->getStreamingByVPNId($this->getRowData()['id']);
        $paymentsSystems = $this->getItemFromCollection('paymentsModel')->getPaymentsByVPNId($this->getRowData()['id']);
        $locations = $this->getItemFromCollection('locationModel')->getLocationByVPNId($this->getRowData()['id']);
        $output = '';


        $output .= '<table class="table table-sm table-striped mt-4 vpn-key-data">';
        $output .= '<tbody>';
        $output .= '<tr><td>'. goTranslate("Kill Switch:") .'</td><td>'.HTMLOutputs::renderCheckStatus((int) $this->getRowData()['kill_switch']).'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Wi-Fi Protection:") .'</td><td>'.HTMLOutputs::renderCheckStatus((int) $this->getRowData()['wi_fi_protection']).'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Encryption:") .'</td><td>'.$this->getRowData()['encryption'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Keep Your IP Private:") .'</td><td>'.HTMLOutputs::renderCheckStatus((int) $this->getRowData()['keep_your_ip_private']).'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Open Source VPN:") .'</td><td>'.HTMLOutputs::renderCheckStatus((int) $this->getRowData()['open_source_vpn']).'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Data Cap:") .'</td><td>'.$this->getRowData()['data_cap'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("IP Addresses:") .'</td><td>'.$this->getRowData()['ip_adresses'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Speed:") .'</td><td>'.$this->getRowData()['speed'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Data Leaks:") .'</td><td>'.$this->getRowData()['data_leaks'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Logging Policy:") .'</td><td>'.$this->getRowData()['logging_policy'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Jurisdiction:") .'</td><td>'.$this->getRowData()['jurisdiction'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Simultaneous Connections:") .'</td><td>'.$this->getRowData()['simultaneous_connections'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Servers:") .'</td><td>'.$this->getRowData()['count_of_servers'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Countries:") .'</td><td>'.$this->getRowData()['countries'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Works In China:") .'</td><td>'.$this->getRowData()['work_in_china'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Support:") .'</td><td>'.$this->getRowData()['support'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Money Back:") .'</td><td>'.$this->getRowData()['money_back'].'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Payments:") .'</td><td>'.$this->getPayments().'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Compatibility with devices:") .'</td><td>'.$this->renderDevicesList($deviceSystems).'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Compatibility with streaming:") .'</td><td>'.$this->renderStreamingList($streamingSystems).'</td></tr>';
        $output .= '<tr><td>'. goTranslate("Supported location:") .'</td><td>'.$this->renderLocations($locations).'</td></tr>';
        $output .= '</tbody>';
        $output .= '</table>';

        $output .= '<div class="row">';
        $output .= '<div class="col-sm-6 col-md-6 col-lg-6 d-flex justify-content-start">';
        $output .= '<div class="pr-2 pt-2 pb-2"><div class="popup" onclick="togglePopup(\'disclaimerPopup2\')">'. goTranslate("Advertiser Disclosure") .'
  <span class="popuptext" id="disclaimerPopup2">'. goTranslate("To keep Top10VPN a free online resource, we receive advertising/referral fees when you buy a VPN through outlinks on this page. This impacts the score, location, prominence and order in which a VPN service appears. Our extensive tests of each VPN, and how it compares with other VPNs in different countries and/or for specific purposes, are also factored in. We do not feature every VPN product on the market. Listings on this page do not imply endorsement. To learn more, see") .'</span>
</div>
</div>';
        $output .= '</div>';

        $output .= '<div class="col-sm-6 col-md-6 col-lg-6 d-flex justify-content-end">';
        $output .= '<a class="btn btn-tertiary" href="'.$this->getRowData()['referal_link'].'" target="_blank" role="button">'. goTranslate("Visit website") .'</a>';
        $output .= '</div>';
        $output .= '</div>';


        return $output;
    }

    protected function getPayments(){
        $output = '';
        $paymentsSystems = $this->getItemFromCollection('paymentsModel')->getPaymentsByVPNId($this->getRowData()['id']);
        foreach ((array)$paymentsSystems as $y => $payment) {
            $logo = PAYEMENTS_LOGO_PATH . $payment['payments_logo'];
            $output .= '<img data-toggle="tooltip" src="' . $logo . '" alt="' . $payment['payments_name'] . '" title="' . $payment['payments_name'] . '" data-original-title="' . $payment['payments_name'] . '" class="device-icons-small"> ';
        }
        return $output;
    }

    protected function renderDevicesList($deviceSystems) : string{
        $output = '';
        if(count($deviceSystems) > 0){
            foreach((array)$deviceSystems as $y => $device) {
                $output .= '<a href="/'.$device['device_page_uri'].'">'.$device['device_name'].'</a>, ';
            }
        }
        return $output;
    }

    protected function renderStreamingList($streamingSystems) : string{
        $output = '';
        if(count($streamingSystems) > 0){
            foreach((array)$streamingSystems as $y => $streaming) {
                $output .= '<a href="/'.$streaming['streaming_page_uri'].'">'.$streaming['streaming_name'].'</a>, ';
            }
        }
        return $output;
    }

    protected function renderLocations($locations) : string{
        $output = '';
        if(count($locations) > 0){
            foreach((array)$locations as $y => $location) {
                $output .= '<a href="/'.$location['location_page_uri'].'">'.$location['location_name'].'</a>, ';
            }
        }
        return $output;
    }
}
