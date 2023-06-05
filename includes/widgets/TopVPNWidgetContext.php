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

    protected function renderFeatures(string $context) : string
    {
        $output = '';
        $exploded = explode(';', $context);

        if(count($exploded) === 0) {
            return $output;
        }
//        $output = '<div class="card">';
//        $output .= '<div class="card-body">';
        $output .= '<div class="">';
        $output .= '<div class="sticky-top list-group">';

        for ($i = 0; $i < count($exploded) - 1; $i++) {
            $string = trim($exploded[$i]);

            $output .= '<a href="#" class="list-group-item list-group-item-action">';
            $output .= $string;
            $output .= '</a>';
        }

//        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
}