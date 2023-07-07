<?php

require_once V_CORE_LIB . 'Public/PublicItem.php';

class TopVPNWidgetContext extends PublicItem{

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init()
    {
        $this->switchMultiLangMode();
        $this->initRowData('vpn_sys_name');
        return $this;
    }

    public function render() : string
    {
        $output = '';
        if(is_admin()){
            return $output;
        }
        $output = $this->renderFeatures($this->getRowData()['context_description']);

        $this->render = $output;
        return $output;
    }

    protected function renderFeatures(string $context): string
    {
        $output = '';
        $exploded = explode(';', $context);
        $logo = VPN_LOGO_PATH . $this->getRowData()['vpn_logo'];
        if (empty($exploded)) {
            return $output;
        }

        $output .= '<div class="sticky-top mb-4">';

        $output .= '<div class="list-group widget-context">';

        $output .= '<a class="list-group-item list-group-item-action" href="' . $this->getRowData()['referal_link'] . '" target="_blank">';

        $output .= '<img alt="' . $this->getRowData()['vpn_name'] . '" class="max-240" src="' . $logo . '" alt="' . $this->getRowData()['vpn_name'] . '" title="' . $this->getRowData()['vpn_name'] . '">';

        $output .= '</a>';

        foreach ($exploded as $item) {
            $string = trim($item);
            if($string == ''){
                continue;
            }
            if (strpos($string, "#") !== false) {
                $exploded2 = explode('#', $string);
                $output .= '<a href="#' . $exploded2[1] . '" class="list-group-item list-group-item-action">';
                $output .= $exploded2[0];
                $output .= '</a>';
            } else {
                $output .= '<a href="#" class="list-group-item list-group-item-action">';
                $output .= $string;
                $output .= '</a>';
            }
        }

        $output .= '</div>';

        $output .= '</div>';


        return $output;
    }
}