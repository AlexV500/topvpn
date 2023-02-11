<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'streaming/Model/DeviceModel.php';

class StreamingAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['streaming_id'];
        $this->setPosition($id);
    }
}