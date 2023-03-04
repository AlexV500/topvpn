<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Model/Relations/ManyToMany.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class LocationModel extends AbstractModel{

    private array $keyManyToManyFields = [
        'vpn' =>[
            'pivot_table_name' => 'topvpn_vpn_location',
            'this_key_name' => 'location_id',
            'that_key_name' => 'vpn_id'],
    ];

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function addRow($data) : object
    {
        $validate = (new Validator)->checkLength($data['location_name'], 2, 30, 'location_name', 'Имя', true)
            ->checkLength($data['location_sys_name'], 2, 30, 'location_sys_name', 'Системное имя', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . 'images/location/';
        $imgAdded = $this->checkFileAndUpload('location_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['location_logo'] = $imgAdded->getResultData();
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Location добавлен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $validate = (new Validator)->checkLength($data['location_name'], 2, 30, 'location_name', 'Имя', true)
            ->checkLength($data['location_sys_name'], 2, 30, 'location_sys_name', 'Системное имя', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }
        $path = V_PLUGIN_INCLUDES_DIR . 'images/location/';
        $imgAdded = $this->checkFileAndUpload('location_logo', $path);
        if ($imgAdded->getResultStatus() !== 'no_file') {
            if ($imgAdded->getResultStatus() == 'ok') {
                $data['location_logo'] = $imgAdded->getResultData();
            }
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        }
        $updatedRow = $this->updateRow($id, $data);

        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Location '. $updatedRow['location_name'] .' изменен  успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow($data) : object{

        $id = $data['id'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/location/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'location_logo', $path);
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $deleteImgResult->getResultStatus(), $deleteImgResult->getResultMessage());
        $deleteManyToOne = ManyToMany::deleteManyToOne($this->keyManyToManyFields, $id);
        if($deleteManyToOne->getResultStatus() == 'error'){
            return Result::setResult('error', $deleteManyToOne->getResultMessage('ManyToMany'), '');
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $deleteManyToOne->getResultMessage('ManyToMany'));
        }
        if($this->removeRow($id) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка удаления Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['location_name'].' удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function getLocationByVPNId($id) : array
    {
        $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_location ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_location.location_id)  
                              INNER JOIN {$this->prefix}topvpn_vpn ON ({$this->prefix}topvpn_vpn.id = {$this->prefix}topvpn_vpn_location.vpn_id)   
                              WHERE {$this->prefix}topvpn_vpn.id = $id";
        return $this->wpdb->get_results($sql, ARRAY_A);
    }


    public function countAllRowsByRelId(string $keyManyToMany, int $id){

        $queryParamsDTO = $this->getSqlQueryParams(false, false);
        $multiLangMode = $queryParamsDTO->getMultiLangMode();

        if($keyManyToMany == ''){
            return [];
        }
        if($keyManyToMany == 'os'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_location ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_location.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_location ON ({$this->prefix}topvpn_location.id = {$this->prefix}topvpn_vpn_location.location_id)
                              WHERE {$this->prefix}topvpn_location.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";

        }

        if($keyManyToMany == 'location') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_device ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_device.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_device ON ({$this->prefix}topvpn_device.id = {$this->prefix}topvpn_vpn_device.device_id)
                              WHERE {$this->prefix}topvpn_device.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";
        }

        $this->wpdb->get_results($sql);
        return $this->wpdb->num_rows;
    }
}