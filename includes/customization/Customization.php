<?php
require_once V_CORE_LIB . 'Public/PublicItem.php';
require_once V_PLUGIN_INCLUDES_DIR . 'customization/Model/CustomizationModel.php';

class Customization extends PublicItem{

    public function __construct($model, $dbTable, $atts = [])
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function init() : object
    {
        $this->switchMultiLangMode();
        $this->initRowData('page_uri');
        return $this;
    }

    public function render() : string
    {
        $atts = $this->getAtts();
        $output = '';
        if(isset($atts['content_type'])) {
            if($atts['content_type'] == 'header_text') {
                if(isset($this->getRowData()['header_content']))
                $output .= $this->getRowData()['header_content'];
            }
            if($atts['content_type'] == 'header_image') {
                if(isset($this->getRowData()['header_image']))
                $output .= $this->getRowData()['header_image'];
            }
            if($atts['content_type'] == 'comparison_header_content') {
                if(isset($this->getRowData()['comparison_header_content']))
                $output .= $this->getRowData()['comparison_header_content'];
            }
            if($atts['content_type'] == 'description_header_content') {
                if(isset($this->getRowData()['description_header_content']))
                $output .= $this->getRowData()['description_header_content'];
            }
        }

        return $output;
    }
}