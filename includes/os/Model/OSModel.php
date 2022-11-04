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

    public function addRow($data)
    {
        $validate = (new Validator)->checkLength($data['os_name'], 3, 30, 'os_name', true)
            ->checkLength($data['os_sys_name'], 3, 30, 'os_sys_name', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $imgAdded = $this->checkFileAndUpload('os_logo', 'OSLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['os_logo'] = $imgAdded->getResultData();
        }
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getMessage(), $data);
        }
        return Result::setResult('ok', 'Добавлено<br/>' . $imgAdded->getMessage(), $recordedRow);
    }

    public function editRow($id, $data)
    {
        $validate = (new Validator)->checkLength($data['os_name'], 3, 30, 'os_name', true)
            ->checkLength($data['os_sys_name'], 3, 30, 'os_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $imgAdded = $this->checkFileAndUpload('os_logo', 'OSLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['os_logo'] = $imgAdded->getResultData();
        }

        $updatedRow = $this->updateRow($id, $data);

        return Result::setResult('ok', 'Изменено<br/>'.$imgAdded->getMessage(), $updatedRow);
    }

    public function deleteRow($id){

        if($this->removeRow($id) !== ''){
            return Result::setResult('error', 'Ошибка<br/>', '');
        }
        return Result::setResult('ok', 'Удалено<br/>', '');
    }

    public function getOSByVPNId($id)
    {
        $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}vpn_os ON ({$this->dbTable}.id={$this->prefix}vpn_os.os_id)  
                              INNER JOIN {$this->prefix}vpn ON ({$this->prefix}vpn.id = {$this->prefix}vpn_os.vpn_id)   
                              WHERE {$this->prefix}vpn.id = $id";
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

}