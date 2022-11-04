<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';
require_once V_PLUGIN_INCLUDES_DIR . 'os/Model/OSModel.php';

class OSAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['os_id'];
        $this->setPosition($id);
    }
}