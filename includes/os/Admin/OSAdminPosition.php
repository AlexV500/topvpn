<?php


class OSAdminPosition extends AdminPosition{

    public function init(){

        $id = $_GET['vpn_id'];
        $this->setPosition($id);
    }
}