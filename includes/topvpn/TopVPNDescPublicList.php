<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_CORE_LIB . 'Public/PublicList.php';


class TopVPNDescPublicList extends PublicList{

    protected object $osModel;
    protected int $showTrigger = 0;
    protected int $showCount = 5;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object{

        $this->switchMultiLangMode($atts);
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->initRowsCount($this->activeMode);
        $this->setPaginationCount(5);
        $this->initPaginationConfig();
        $this->initRowsData($this->activeMode);
        $this->osModel = new OSModel('topvpn_os');
        return $this;
    }

    public function render() : string {

        $output = '';
        $show_count = 3;
        $show_trigger_2 = 0;
        $logoPath = V_CORE_URL .'includes/images/vpn';
        $OSLogoPath = V_CORE_URL .'includes/images/os';
        $count = count($this->getRowsData());
        $output .= '<div class="">';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = $logoPath . '/' . $result['vpn_logo'];
                $osSystems = $this->osModel->getOSByVPNId($result['id']);
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