<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'customization/Model/CustomizationModel.php';

class CustomizationAdminPosition{

    public function init(){

        $id = $_GET['device_id'];
        $this->setPosition($id);
    }
}