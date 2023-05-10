<?php
require_once V_CORE_LIB . 'Public/PublicItem.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';

class TopVPNHeaderShortcode extends PublicItem{

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object
    {
        $this->switchMultiLangMode();
        $this->initRowData('vpn_sys_name');
        return $this;
    }

    public function render() : string
    {
        $output = '';
        if(isset($this->getRowData()['short_description'])){
            $output .= '<div class="hero-slider-content text-white">';
            $output .= '<h5 class="text-white">';
            $output .= $this->getRowData()['short_description'];
            $output .= '</h5>';
            $output .= '</div>';
        }

        return $output;
    }
}