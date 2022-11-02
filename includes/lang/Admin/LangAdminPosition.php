<?php
require_once V_CORE_LIB . 'Admin/AdminPosition.php';

class LangAdminPosition extends AdminPosition
{

    public function init(){

        $id = $_GET['lang_id'];
        $this->setPosition($id);
    }
}