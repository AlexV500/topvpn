<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'topvpn/Admin/TopVPNModel.php';

class TopVPNAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['vpn_id'];
        $this->setPosition($id);
    }
}