<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class OSModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function addRow($data) : object
    {
        $validate = (new Validator)->checkLength($data['os_name'], 3, 30, 'os_name', true)
            ->checkLength($data['os_sys_name'], 3, 30, 'os_sys_name', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . '/images/os/';
        $imgAdded = $this->checkFileAndUpload('os_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['os_logo'] = $imgAdded->getResultData();
        }
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getResultMessage(), $data);
        }
        return Result::setResult('ok', 'Добавлено<br/>' . $imgAdded->getResultMessage(), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $validate = (new Validator)->checkLength($data['os_name'], 3, 30, 'os_name', true)
            ->checkLength($data['os_sys_name'], 3, 30, 'os_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }
        $path = V_PLUGIN_INCLUDES_DIR . '/images/os/';
        $imgAdded = $this->checkFileAndUpload('os_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['os_logo'] = $imgAdded->getResultData();
        }

        $updatedRow = $this->updateRow($id, $data);

        return Result::setResult('ok', 'Изменено<br/>'.$imgAdded->getResultMessage(), $updatedRow);
    }

    public function deleteRow($data) : object{

        $id = $data['id'];
        $this->deleteName = $data['lang_name'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/os/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'os_logo', $path);

        if($this->removeRow($id) !== ''){
            return Result::setResult('error', 'Ошибка<br/>'.$deleteImgResult, '');
        }
        return Result::setResult('ok', 'Удалено<br/>'.$deleteImgResult, '');
    }

    public function getOSByVPNId($id)
    {
        $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.os_id)  
                              INNER JOIN {$this->prefix}topvpn_vpn ON ({$this->prefix}topvpn_vpn.id = {$this->prefix}topvpn_vpn_os.vpn_id)   
                              WHERE {$this->prefix}topvpn_vpn.id = $id";
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

}