<?php
require_once V_CORE_LIB . 'Public/PublicItem.php';
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';

class TopVPNDescriptionBefore extends PublicItem{

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object
    {
        $this->switchMultiLangMode();
        $this->addItemToCollection(new DeviceModel('topvpn_device'), 'deviceModel');

        $this->initRowData('vpn_sys_name');
        return $this;
    }


    public function render() : string
    {
        $logo = VPN_LOGO_PATH . $this->getRowData()['vpn_logo'];
        $screen = VPN_SCREEN_PATH . $this->getRowData()['screen'];

        $output = '';

        //    $output .= $this->getRowData()['short_description'];
        $output .= '<div class="d-flex justify-content-between bg-main py-2 mb-3 bottom-border-gray sticky-top" syle="z-index: 2">';
        $output .= '<div class="bd-highlight">';

    //    $output .= '<a class="no-decor-link" href="' . $this->getRowData()['referal_link'] . '" target="_blank">';
        $output .= '<img alt="' . $this->getRowData()['vpn_name'] . '" class="" height="40" src="' . $logo . '" alt="' . $this->getRowData()['vpn_name'] . '" title="' . $this->getRowData()['vpn_name'] . '">';
//   $output .= '<div class="">';
        //  $output .= '</div>';
        //  $output .= '<div class="" style="text-align: center">'.goTranslate("Visit Website:") . '<i class="fa fa-arrow-right"></i>';
        //  $output .= '</div>';
    //    $output .= '</a>';
        $output .= '</div>';


        $output .= '<div class="pt-2 px-2 bd-highlight d-flex">';
        
        $output .= '<div class="d-flex justify-content-end">';
        $output .= HTMLOutputs::renderRating($this->getRowData()['rating'], 0) . '&nbsp';
        $output .= '</div>';

        $output .= '<div class="d-flex justify-content-end">';
        $output .=  $this->getRowData()['rating'] . '/10';
        
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="bd-highlight">';
        $output .= '<a class="btn btn-tertiary " href="' . $this->getRowData()['referal_link'] . '" target="_blank" role="button">' . goTranslate("Visit website") . '</a>';
        $output .= '</div>';
        $output .= '</div>';

////////////
        //      $output .= '<img alt="' . $this->getRowData()['vpn_name'] . '" class="img-fluid max-240" src="' . $screen . '" alt="' . $this->getRowData()['screen'] . '" title="' . $this->getRowData()['screen'] . '">';
////////////
        $output .= '<div class="">';
        $output .= '<img alt="' . $this->getRowData()['vpn_name'] . '" class="img-fluid img-thumbnail max-240" src="' . $screen . '" alt="' . $this->getRowData()['screen'] . '" title="' . $this->getRowData()['screen'] . '">';
//        $output .='<a class="col-md-12 col-lg-12 no-decor-link" href="'.$this->getRowData()['referal_link'].'" target="_blank"><img alt="' . $this->getRowData()['screen'] . '" class="img-fluid" src="' . $screen . '" alt="' . $this->getRowData()['screen'] . '" title="' . $this->getRowData()['screen'] . '">';
//        $output .= '</a>';
        $output .= '</div>';
        $output .= '<div class="row d-flex justify-content-between">';
        $output .= '<div class="col-md-12 col-lg-6">';
        $output .= '<ul class="check-list-wrap list-two-col py-3">';
        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Speed:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['overall_speed']) . '</div><div class="rating-rate">' . $this->getRowData()['overall_speed'] . '/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Streaming:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['streaming_rate']) . '</div><div class="rating-rate">' . $this->getRowData()['streaming_rate'] . '/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Torrenting:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['torrenting_rate']) . '</div><div class="rating-rate">' . $this->getRowData()['torrenting_rate'] . '/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Bypassing Censorship:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['bypassing_censorship_score']) . '</div><div class="rating-rate">' . $this->getRowData()['bypassing_censorship_score'] . '/10</div></li>';


        $output .= '</ul>';
        $output .= '</div>';

        $output .= '<div class="col-md-12 col-lg-6">';
        $output .= '<ul class="check-list-wrap list-two-col py-3">';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Privacy & Logging:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['privacy_score']) . '</div><div class="rating-rate">' . $this->getRowData()['privacy_score'] . '/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Security & Features:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['feautures_score']) . '</div><div class="rating-rate">' . $this->getRowData()['feautures_score'] . '/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Price & Value:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['value_for_money_score']) . '</div><div class="rating-rate">' . $this->getRowData()['value_for_money_score'] . '/10</div></li>';

        $output .= '<li><div class="entry-rate-descr">' . goTranslate("Ease of Use:") . '</div></li>';
        $output .= '<li><div class="entry-rate"><div class="rating-bar">' . HTMLOutputs::renderRatingBar2($this->getRowData()['easy_to_use']) . '</div><div class="rating-rate">' . $this->getRowData()['easy_to_use'] . '/10</div></li>';

        $output .= '</ul>';
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="">';
        $output .= '<h3>' . $this->getRowData()['vpn_name'] .' '. goTranslate("key data:") .'</h3>';
//        $output .= '<div class="col-sm-6 col-md-6 col-lg-6 d-flex justify-content-start">';
//        $output .= '<div class="pr-2 pt-2 pb-2"><div class="popup" onclick="togglePopup(\'disclaimerPopup\')">' . goTranslate("Advertiser Disclosure") . '
//  <span class="popuptext" id="disclaimerPopup">' . goTranslate("To keep Top10VPN a free online resource, we receive advertising/referral fees when you buy a VPN through outlinks on this page. This impacts the score, location, prominence and order in which a VPN service appears. Our extensive tests of each VPN, and how it compares with other VPNs in different countries and/or for specific purposes, are also factored in. We do not feature every VPN product on the market. Listings on this page do not imply endorsement. To learn more, see") . '</span>
//</div>
//</div>';
//        $output .= '</div>';
//
//        $output .= '<div class="col-sm-6 col-md-6 col-lg-6 d-flex justify-content-end">';
//        $output .= '<a class="btn btn-tertiary" href="' . $this->getRowData()['referal_link'] . '" target="_blank" role="button">' . goTranslate("Visit website") . '</a>';
//        $output .= '</div>';
//
//        $output .= '</div>';

        return $output;
    }
}
