<?php
require_once V_CORE_LIB . 'Public/PublicItem.php';
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class TopVPNDescription extends PublicItem{

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
        $this->initRowData('vpn_sys_name');
        return $this;
    }


    public function render() : string
    {
        $logo = VPN_LOGO_PATH . $this->getRowData()['vpn_logo'];
        $screen = VPN_SCREEN_PATH . $this->getRowData()['screen'];
        $output = '';

    //    $output .= $this->getRowData()['short_description'];
        $output .= '<a class="row d-flex justify-content-between no-decor-link" href="'.$this->getRowData()['referal_link'].'" target="_blank">';
        $output .='<div class="col-md-12 col-lg-6"><img alt="' . $this->getRowData()['vpn_name'] . '" class="img-fluid max-240" src="' . $logo . '" alt="' . $this->getRowData()['vpn_name'] . '" title="' . $this->getRowData()['vpn_name'] . '">';
        $output .= '</div>';
        $output .= '<div class="col-md-12 col-lg-4 d-flex justify-content-end pt-3" style="padding-right: 25px">';
        $output .= HTMLOutputs::renderRating($this->model->getAverageRating($this->getRowData()), 0).'&nbsp'.$this->model->getAverageRating($this->getRowData()).'/10';
        $output .= '</div>';
        $output .= '</a>';
        $output .= '<hr>';



        $output .= '<div class="row justify-content-between">';
        $output .='<a class="col-md-12 col-lg-12 no-decor-link" href="'.$this->getRowData()['referal_link'].'" target="_blank"><img alt="' . $this->getRowData()['screen'] . '" class="img-fluid" src="' . $screen . '" alt="' . $this->getRowData()['screen'] . '" title="' . $this->getRowData()['screen'] . '">';
        $output .= '</a>';

        $output .='<div class="col-md-12 col-lg-6">';
        $output .='<ul class="check-list-wrap list-two-col py-3">';
        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Speed:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['overall_speed']).'</div><div class="rating-rate">'.$this->getRowData()['overall_speed'].'/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Streaming:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['streaming_rate']).'</div><div class="rating-rate">'.$this->getRowData()['streaming_rate'].'/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Torrenting:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['torrenting_rate']).'</div><div class="rating-rate">'.$this->getRowData()['torrenting_rate'].'/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Bypassing Censorship:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['bypassing_censorship_score']).'</div><div class="rating-rate">'.$this->getRowData()['bypassing_censorship_score'].'/10</div></li>';


        $output .= '</ul>';
        $output .= '</div>';

        $output .='<div class="col-md-12 col-lg-6">';
        $output .='<ul class="check-list-wrap list-two-col py-3">';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Privacy & Logging:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['privacy_score']).'</div><div class="rating-rate">'.$this->getRowData()['privacy_score'].'/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Security & Features:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['feautures_score']).'</div><div class="rating-rate">'.$this->getRowData()['feautures_score'].'/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Price & Value:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['value_for_money_score']).'</div><div class="rating-rate">'.$this->getRowData()['value_for_money_score'].'/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">'. goTranslate("Ease of Use:") .'</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">'.HTMLOutputs::renderRatingBar2($this->getRowData()['easy_to_use']).'</div><div class="rating-rate">'.$this->getRowData()['easy_to_use'].'/10</div></li>';

        $output .= '</ul>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="mt-2">';
        $output .= $this->getRowData()['description'];
        $output .= '</div>';
        $output .= '<div class="col-md-12 col-lg-12 d-flex justify-content-end">';
        $output .= '<a class="btn btn-tertiary" href="'.$this->getRowData()['referal_link'].'" target="_blank" role="button">Visit website</a>';
        $output .= '</div>';
        $output .= '<table class="table table-sm table-striped mt-4">';
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
        $output .= '</tbody>';
        $output .= '</table>';

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
}
