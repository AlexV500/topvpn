<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/StreamingModel.php';
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
        $this->switchMultiLangMode();
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->setLimitCount(5);
        $this->initRows();
        $this->addRelationParam('device', $this->getItemFromCollection('deviceModel'), 'device_sys_name');
        $this->addRelationParam('streaming', $this->getItemFromCollection('streamingModel'), 'streaming_sys_name');
        $this->initRowsCount($this->activeMode);
        $this->initRowsData($this->activeMode, false, true);
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
                $deviceSystems = $this->getItemFromCollection('deviceModel')->getDeviceByVPNId($result['id']);
                $pos = $i + 1;
                if($i == $show_count){
                    // $output .= '<div id="theDIV2" style="display: none;">';
                    $show_trigger_2 = 1;
                    $output .= '<div class="box2">';
                }
                $output .= '<div class="rating-info d-block no-gutters mt-4 pt-4 pb-3 pl-2 pr-2 p-lg-4 list list-'.$pos.' pm-1">';
                $output .= '<div class="d-flex justify-content-center align-items-center bg-white">';
                $output .= '<div class="entry-logo"><a href="' . $result['vpn_sys_name'] . '/" alt="Logo"><img src="' . $logo . '" height="35" alt="Logo"></a>
      '.HTMLOutputs::renderRating($result['rating'], 0);
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