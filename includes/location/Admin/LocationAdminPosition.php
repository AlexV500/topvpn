<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'location/Model/DeviceModel.php';

class LocationAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['location_id'];
        $this->setPosition($id);
    }
}