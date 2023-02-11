<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'device/Model/DeviceModel.php';

class DeviceAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['device_id'];
        $this->setPosition($id);
    }
}