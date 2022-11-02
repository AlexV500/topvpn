<?php


class OSAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['os_id'];
        $this->setPosition($id);
    }
}