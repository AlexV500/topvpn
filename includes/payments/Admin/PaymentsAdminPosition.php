<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'payments/Model/PaymentsModel.php';

class PaymentsAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['payments_id'];
        $this->setPosition($id);
    }
}