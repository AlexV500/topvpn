<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Model/TopVPNModel.php';

class TopVPNAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['item_id'];
        $this->setPosition($id);
    }
}