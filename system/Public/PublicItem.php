<?php

require_once V_CORE_LIB . 'Components/Components.php';

abstract class PublicItem extends Components{

    protected $rowData = [];

    public function __construct($model, $dbTable, $atts)
    {
        parent::__construct($model, $dbTable, $atts);
    }

    protected function initRowData($columnName) : object{

        $uri = get_page_uri();
        $uri = $this->removeReviewSubstring($uri);

        $atts = $this->getAtts();
        if(isset($atts['id'])) {
            $this->rowData = $this->getModel()->getRowById($atts['id']);
        }
        if(isset($atts['sys_name'])) {
            $sysName = $this->removeReviewSubstring($atts['sys_name']);
            $this->rowData = $this->getModel()->getRowByPk($columnName, $sysName);
        }
        else {
            $uri = $this->removeReviewSubstring($uri);
            $this->rowData = $this->getModel()->getRowByPk($columnName, $uri);
        }
        return $this;
    }

    function removeReviewSubstring($string) {
        $substring = '-review';
        $position = strpos($string, $substring);

        if ($position !== false) {
            $string = substr_replace($string, '', $position, strlen($substring));
        }

        return $string;
    }

    public function getRowData(){

        return $this->rowData;
    }
}