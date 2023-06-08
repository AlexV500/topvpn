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

        if (empty($exploded)) {
            return $output;
        }

        $output .= '<div class="list-group sticky-top mb-3 widget-context">';

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
        return $output;
    }
}