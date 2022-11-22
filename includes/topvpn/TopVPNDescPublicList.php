<?php

require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';
require_once V_CORE_LIB . 'Public/PublicList.php';


class TopVPNDescPublicList extends PublicList{

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
        return $this;
    }

    public function render() : string {

        $output = '';
        $logoPath = V_CORE_URL .'includes/images/vpn';
    }

}