<?php

require_once V_CORE_LIB . 'Public/PublicList.php';

class TopVPNWidgetList extends PublicList{

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function init( array $atts = []) : object{

        $lang = get_locale();
        $this->switchMultiLangMode($atts);
        $this->setOrderColumn('position');
        $this->setOrderDirection('ASC');
        $this->setLimitCount(5);
        $this->initRowsCount($this->activeMode);
        $this->initRowsData($this->activeMode, false, true);
        return $this;
    }

    public function render() : string
    {
        $logoPath = V_CORE_URL . 'includes/images/vpn';
        $output = '<div class="card">';
        $output .= '<div class="card-header">';
        $output .= 'Top VPN';
        $output .= '</div>';
        $output .= '<div class="card-body">';
        $count = count($this->getRowsData());
        $output .= '<ul class="list-group list-group-flush">';
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $result = $this->getRowsData()[$i];
                $logo = $logoPath . '/' . $result['vpn_logo'];
                $pos = $i + 1;
                $output .= '<li class="list-group-item d-flex justify-content-start">';
                $output .= '<div class="row">';
                $output .= '<div class="col-6">';
                $output .= '<span><img alt="' . $result['vpn_name'] . '" class="img-fluid max-240" src="' . $logo . '" alt="' . $result['vpn_name'] . '" title="' . $result['vpn_name'] . '"></span>';
                $output .= '</div>';
                $output .= '<div class="col-6">';
                $output .= $result['vpn_name'];
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        $this->render = $output;
        return $output;
    }

}