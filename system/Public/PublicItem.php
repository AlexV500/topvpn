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
        $atts = $this->getAtts();
        if(isset($atts['id'])) {
            $this->rowData = $this->getModel()->getRowById($atts['id']);
        } else {
            $this->rowData = $this->getModel()->getRowByPk($columnName, $uri);
        }
        return $this;
    }

    public function getRowData(){

        return $this->rowData;
    }
}