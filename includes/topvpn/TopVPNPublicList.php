<?php
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/Additional/TopVPNAdditionalModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_CORE_LIB . 'View/HTMLOutputs.php';
require_once V_CORE_LIB . 'Public/PublicList.php';
require_once V_CORE_LIB . 'Utils/Collection.php';

class TopVPNPublicList extends PublicList{

    protected int $showTrigger = 0;
    protected int $showCount = 5;

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object{

        $this->addItemToCollection(new DeviceModel('topvpn_device'), 'deviceModel');
        $this->addItemToCollection(new StreamingModel('topvpn_streaming'), 'streamingModel');
        $this->addItemToCollection(new LocationModel('topvpn_location'), 'locationModel');
        $this->addItemToCollection(new TopVPNAdditionalModel('topvpn_vpn_additional'), 'vpnAdditionalModel');
        $this->switchMultiLangMode();
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->setPaginationCount(8);
        $this->initRows();
        $this->addRelationParam('device', $this->getItemFromCollection('deviceModel'), 'device_sys_name');
        $this->addRelationParam('streaming', $this->getItemFromCollection('streamingModel'), 'streaming_sys_name');
        $this->addRelationParam('location', $this->getItemFromCollection('locationModel'), 'location_sys_name');
        $this->addAdditionalParam('device', $this->getItemFromCollection('vpnAdditionalModel'));
        $this->addAdditionalParam('streaming', $this->getItemFromCollection('vpnAdditionalModel'));
        $this->addAdditionalParam('location', $this->getItemFromCollection('vpnAdditionalModel'));
        $this->initRowsCount($this->activeMode);
        $this->initPaginationConfig();
        $this->initRowsData($this->activeMode);

        return $this;
    }

    public function render() : string {
        $output = '';
        $count = count($this->getRowsData());
        $output .= '<div class="d-flex justify-content-between">';
        $output .= '<div class="">Updated: '.HTMLOutputs::updatedAt().'</div>';
        $output .= '<div class=""><div class="popup" onclick="togglePopup(\'disclaimerPopup\')">Advertiser Disclosure
  <span class="popuptext" id="disclaimerPopup">To keep Top10VPN a free online resource, we receive advertising/referral fees when you buy a VPN through outlinks on this page. This impacts the score, location, prominence and order in which a VPN service appears. Our extensive tests of each VPN, and how it compares with other VPNs in different countries and/or for specific purposes, are also factored in. We do not feature every VPN product on the market. Listings on this page do not imply endorsement. To learn more, see</span>
</div>
</div>';
        $output .= '</div>';
        $output .= '<div class="">';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = VPN_LOGO_PATH . $result['vpn_logo'];
                $deviceSystems = $this->getItemFromCollection('deviceModel')->getDeviceByVPNId($result['id']);
                $pos = $i + 1;
                $output .= '';
                if ($i == $this->showCount) {
                    $this->showTrigger = 1;
                    //    $output .= '<div id="theDIV" style="display: none;">';
                    $output .= '<div class="box">';
                }
                $output .= '<div class="rating-info d-block no-gutters mt-4 list list-'.$pos.' pm-1">';
                //TOP STATUS
                $output .= $this->renderTopStatus($pos, $result['top_status_description']);

                //TOP STATUS END

//                $output .= '<div class="row">';


                /**/$output .='';/**/

//                /*---*/$output .='<div class="col-auto text-center align-center d-none d-lg-flex flex-column justify-content-center">';
//                /*------*/$output .='<div class="arating">'.$pos.'</div>';
//                /*---*/$output .='</div>';

                /*---*/
                //$output .='<div class="col">';
                /*------*/$output .='<div class="row">';
                /*---------*/$output .= '<div class="col-12 col-md-6 column-1 logo-holder d-flex flex-column justify-content-top">';
                /*------------*/$output .='<span><img alt="' . $result['vpn_name'] . '" class="img-fluid max-240" src="' . $logo . '" alt="' . $result['vpn_name'] . '" title="' . $result['vpn_name'] . '"></span>';
                /*------------*/$output .='<p class="new_product_description d-none d-md-block mb-0 mr-lg-1 mr-xl-0 mt-2">';
                /*------------*/$output .=$result['short_description'];
                /*------------*/$output .='</p>';

                /*------------*/$output .= '<a class="tt-read-more" href="' . $result['vpn_sys_name'] .'">Go To Review <i class="fa fa-arrow-right"></i></a>';
                /*---------*/$output .='</div>';


                /*---------*/$output .='<div class="col-12 col-md-6 column-2 features-holder mt-3 mt-md-0 mb-4 mb-md-0 pl-2 pt-lg-2 pt-xl-0 text-left">';
                /*------------*/$output .= $this->renderFeatures($result['features']);


                /*------------*/$output .='<div class="mt-3">';
                /*---------------*/$output .= '<span class="available-on-text">Available on:</span>';
                /*---------------*/foreach ((array)$deviceSystems as $y => $device) {
                    /*------------------*/$deviceLogo = DEVICE_LOGO_PATH . $device['device_logo'];
                                          if(trim($device['device_font_logo']) == ""){
                                              $output .= '<img data-toggle="tooltip" src="' . $deviceLogo . '" alt="' . $device['device_name'] . '" title="' . $device['device_name'] . '" data-original-title="' . $device['device_name'] . '" class="device-icons-small"> ';
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
                                              $output .= '<span class="device-font-logo" data-toggle="tooltip" title="'.$device['device_name'].'" style="'.$style.'"><i class="'.$device['device_font_logo'].'"></i></span>&nbsp';
                                          }
                    /*------------------*/
                    /*---------------*/}

                /*------------*/$output .= '</div>';

                if(trim($result['verdict'] !== '')){
                    /*---------*/$output .= '<div class="mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-person-heart text-blue" viewBox="0 0 16 16">
  <path d="M9 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h10s1 0 1-1-1-4-6-4-6 3-6 4Zm13.5-8.09c1.387-1.425 4.855 1.07 0 4.277-4.854-3.207-1.387-5.702 0-4.276Z"/>
</svg> <span class="usinglink">'.$result['verdict'].'</span>';
                    /*---------*/$output .= '</div>';
                }

                /*---------*/$output .='</div>';
                /*---------*/$output .='<div class="col-12 col-md-6 column-3 rating-info-holder d-flex flex-column justify-content-center">';

                /*------------*/$output .= '<div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">Overall speed:</div>
                                            <div class="wss-rt_container-1819568874">
                '.HTMLOutputs::renderRate($result['overall_speed']).'
                                            </div>
                                            </div>  
                
                <div class="wss-scoreRow-772925660"><div class="wss-multiScoreText-3728111154">Privacy & Logging:</div>
                                            <div class="wss-rt_container-1819568874">
                                            
                                            '.HTMLOutputs::renderRate(9.5).'
                                            </div>
                                            </div>
                                            
                                            <div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">Security & Features:</div>
                                            <div class="wss-rt_container-1819568874"> 
                                            '.HTMLOutputs::renderRate(9.8).'
                                            </div>
                                            </div>
                                            
                                            <div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">Value for money:</div>
                                            <div class="wss-rt_container-1819568874">
                                            '.HTMLOutputs::renderRate(9.5).'
                                            </div>
                                            </div>
                                            
                                            <div class="wss-scoreRow-772925660">
                                            <div class="wss-multiScoreText-3728111154">Ease of Use:</div>
                                            <div class="wss-rt_container-1819568874">                                           
                                            
                                            '.HTMLOutputs::renderRate(9.9).'
                                            </div>
                                            </div>';
                /*---------*/$output .='</div>';
                /*---------*/$output .='<div class="col-12 col-md-6 column-4 price-holder d-flex flex-column justify-content-center align-items-center text-center">';
                /*------------*/$output .='<div class="d-score">';
                /*---------------*/$output .= $result['rating'];
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="d-rating">';
                /*---------------*/$output .='<span>'.$result['rating_description'].'</span>';
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="pt-2 d-stars">';
                /*---------------*/$output .='<div class="star-ratings-sprite zoom8">';
                /*------------------*/$output .= $this->getRating($result['rating'], 0);
                /*---------------*/$output .='</div>';
                /*------------*/$output .='</div>';
                /*------------*/$output .='<div class="mb-3 prices">';
                /*---------------*/$output .='<span class="price"><span class="font-12">From</span> '.$result['price'].'&nbsp;USD</span>';
                /*------------------*/$output .='<button class="btn btn-tertiary margin-0-auto">Visit Website</button>';
                /*------------*/$output .='</div>';
                /*---------*/$output .='</div>';
                /*------*/$output .= '</div>';
                /*---*/
                //$output .='</div>';

                $output .= '</div>';
//                $output .= '</div>';

            }
            if($this->showTrigger == 1){
                $output .= '</div>';
            }
            $output .= '</div>';

            $output .= '<div class="d-flex justify-content-between mt-3">';
            $output .= '<div class="">Updated: '.HTMLOutputs::updatedAt().'</div>';
            $output .= '<div class=""><div class="popup" onclick="togglePopup(\'disclaimerPopup2\')">Advertiser Disclosure
  <span class="popuptext" id="disclaimerPopup2">To keep Top10VPN a free online resource, we receive advertising/referral fees when you buy a VPN through outlinks on this page. This impacts the score, location, prominence and order in which a VPN service appears. Our extensive tests of each VPN, and how it compares with other VPNs in different countries and/or for specific purposes, are also factored in. We do not feature every VPN product on the market. Listings on this page do not imply endorsement. To learn more, see</span>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            if($count > $this->showCount){
                $output .= '<div id="other-brokers-button-down" class="mt-3">';
                $output .= '<div class="row justify-content-center">
                <div class="text-center">
                    <button class="btn btn-tertiary mx-auto toggle"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg>'.__('Other VPN').'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"></path>
</svg></button>
                </div>
            </div>
            </div>';


                $output .= '<div id="other-brokers-button-up" class="pt-5">';
                $output .= '<div class="row justify-content-center">
                <div class="text-center">
                    <button class="btn btn-tertiary mx-auto toggle"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg>'.__('Hide Other VPN').'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
</svg></button>
                </div>
            </div>
            </div>';
            }
        }

        $this->render = $output;
        return $output;
    }

    protected function renderFeatures(string $features) : string{

        $output = '';
        $featureRed = '';
        $exploded = explode(';', $features);
//        echo '<pre>';
//        print_r($exploded);
//        echo '</pre>';
        if(count($exploded) == 0){
            return $output;
        }
        $output .= '<ul class="features">';
        for ($i = 0; $i < (count($exploded) - 1); $i++) {
            $y = $i;
            $string = $exploded[$i];
            $substrCount = strspn($string,'[red]');
            if($substrCount == 5){
                $featureRed = ' feature-red';
                $string = substr($string, 5);
                $string = trim($string);
            } else {
                $featureRed = '';
                $y = $y + 1;
            }
         //   $output .= '<li class="feature-'.$y.'"><img class="padli s16x16" src="'.$this->imgPath.'/tick-3.png">';
            $output .= '<li class="feature-'.$y.'">';
            $output .= HTMLOutputs::renderFeatureIcon();
            $output .= ' ';
            $output .= '<span class="'.$featureRed.'">'.$string.'</span>';
            $output .= '</li>';
        }
        $output .= '</ul>';
        return $output;
    }

    protected function renderRate(){

        $options = "data-pie='{\"percent\" : 75}'";
        $output = '<div class="pie" '.$options.'></div>';
        return $output;
    }

    protected function renderTopStatus( int $pos, string $statusDescr) : string{

        $output = '';
        $status = '';
        $class = '';
        $ribbonClass = '';
        if(trim($statusDescr) !== ''){
            $status = '<span class="status-descr">'. $statusDescr .'</span>';
        }
        if($pos <= 3) {
            $class = 'vendor-'.$pos;
            $ribbonClass = 'ribbon-number-'.$pos;
        } else {
            $class = 'vendor-';
            $ribbonClass = 'ribbon-number';
        }
        $output .= '<div class="d-none d-md-block text-left">';
        $output .= '<div class="'.$class.'">';
        $output .= '<div class="vendor__position">' . $pos . $status.'</div>';
        $output .= '</div>';

//        if(trim($statusDescr) !== '') {
//            $output .= '<div class="'.$ribbonClass.'"><h2>' . $statusDescr . '</h2></div>';
//        }
        $output .= '</div>';
        return $output;
    }

    protected function getRating($posRev, $negRev)
    {
        $posRev = $posRev * 10;
        //  echo $posRev.'<br/>';
        if (($posRev > 0) && ($negRev > 0)) {
            if ($posRev == $negRev) {
                $p = 50;
            }
        }

        if (($posRev == 0) && ($negRev == 0)) {

            $p = 0;
        }

        if (($posRev > 0) && ($negRev == 0)) {

            $p = ((int)$posRev / 100) * 100;
        }

        return $this->renderRating($p);
    }

    protected function renderRating($p) : string
    {
        $output = '<div class="rating">
      <div class="stars">
        <div class="on" style="width: ' . $p . '%;"></div>
          <div class="live">
            <span data-rate="1"></span>
            <span data-rate="2"></span>
            <span data-rate="3"></span>
            <span data-rate="4"></span>
            <span data-rate="5"></span>
          </div>
        </div>
      </div>';
        return $output;
    }
}