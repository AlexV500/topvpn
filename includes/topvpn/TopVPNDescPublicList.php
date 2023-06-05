<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/LocationModel.php';
require_once V_CORE_LIB . 'Public/PublicList.php';
require_once V_CORE_LIB . 'Utils/Collection.php';

class TopVPNDescPublicList extends PublicList{

    protected int $showTrigger = 0;
    protected int $showCount = 5;

    public function __construct($model, $dbTable, array $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object{

        $this->addItemToCollection(new DeviceModel('topvpn_device'), 'deviceModel');
        $this->addItemToCollection(new StreamingModel('topvpn_streaming'), 'streamingModel');
        $this->addItemToCollection(new LocationModel('topvpn_location'), 'locationModel');
        $this->switchMultiLangMode();
//        $this->setOrderColumn('rating');
//        $this->setOrderDirection('DESC');
        $this->setLimitCount(5);
        $this->initRows();
        $this->addRelationParam('device', $this->getItemFromCollection('deviceModel'), 'device_sys_name');
        $this->addRelationParam('streaming', $this->getItemFromCollection('streamingModel'), 'streaming_sys_name');
        $this->addRelationParam('location', $this->getItemFromCollection('locationModel'), 'location_sys_name');
        $this->initRowsCount($this->activeMode);
        $this->initRowsData($this->activeMode, false, false);
        return $this;
    }

    public function render() : string {

        $output = '';
        $show_count = 3;
        $show_trigger_2 = 0;
        $deviceLogoPath = DEVICE_LOGO_PATH;
        $count = count($this->getRowsData());
        $output .= '';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = VPN_LOGO_PATH . $result['vpn_logo'];
                $mtop = ($i == 0) ? 'mt-0' : 'mt-4';
                $deviceSystems = $this->getItemFromCollection('deviceModel')->getDeviceByVPNId($result['id']);
                $pos = $i + 1;
                if($i == $show_count){
                    // $output .= '<div id="theDIV2" style="display: none;">';
                    $show_trigger_2 = 1;
                    $output .= '<div class="box2">';
                }
                $output .='<div class="desc-public-list d-block '.$mtop.' pt-4 pb-3 pl-2 pr-2 p-3 list list-'.$pos.' pm-1">';
                $output .='<div class="row">';
                $output .= '<div class="col-md-4 col-lg-2 col-sm-12 d-flex bg-white">';
                $output .= '<div class="entry-logo"><a href="' . $result['vpn_sys_name'] . '/" alt="Logo"><img src="' . $logo . '" height="35px" alt="Logo"></a>
      '.HTMLOutputs::renderRating($result['rating'], 0);
                $output .= '<div class="mb-2"></div>';
                $output .= '<a class="btn btn-warning btn-xsm" href="/' . $result['vpn_sys_name'] . '-review/" role="button">'. goTranslate("View more...") .'</a>&nbsp';
                $output .= '<a class="btn btn-tertiary btn-xsm" href="'.$result['referal_link'].'"  target="_blank" role="button">'. goTranslate("Visit site") .'</a>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="col-md-8 col-lg-10 col-sm-12 d-flex bg-white">';
                $output .= $result['short_description'];
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
            if($show_trigger_2 == 1){
                $output .= '</div>';
            }
        }
        $this->render = $output;
        return $output;
    }

}