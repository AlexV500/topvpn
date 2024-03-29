<?php

require_once V_CORE_LIB . 'Public/PublicList.php';

class TopVPNWidgetList extends PublicList{

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object{


        $this->switchMultiLangMode();
//        $this->setOrderColumn('position');
//        $this->setOrderDirection('ASC');
        $this->setLimitCount(10);
        $this->initRows();
        $this->initRowsCount($this->activeMode);
        $this->initRowsData($this->activeMode, false, false);
        return $this;
    }

    public function render() : string
    {
        $logoPath = V_CORE_URL . 'includes/images/vpn';
        $output = '<div class="card">';
        $output .= '<div class="card-header">';
        $output .= goTranslate("Top 10 VPN");
        $output .= '</div>';
        $output .= '<div class="card-body">';
        $count = count($this->getRowsData());
        $output .= '<ul class="sidebar-widget list-group list-group-flush">';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = $logoPath . '/' . $result['vpn_logo'];
                $pos = $i + 1;
                $output .= '<li class="list-group-item">';
                $output .= '<div class="row">';
                $output .= '<div class="col-5">';
                $output .= '<span><img alt="' . $result['vpn_name'] . '"src="' . $logo . '" width="100px" height="21px" alt="' . $result['vpn_name'] . '" title="' . $result['vpn_name'] . '"></span>';
                $output .= HTMLOutputs::renderRating($result['rating'], 0);
                $output .= '</div>';
                $output .= '<div class="col-7 pt-2">';
                $output .= '<a class="btn btn-warning btn-xsm" href="/' . $result['vpn_sys_name'] . '-review" role="button">'. goTranslate("View more...") .'</a>&nbsp';
                $output .= '<a class="btn btn-tertiary btn-xsm" href="' . $result['referal_link'] . '" role="button">'. goTranslate("Visit site") .'</a>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        $output .= '</div>';
        $output .= '</div>';

        $this->render = $output;
        return $output;
    }

}