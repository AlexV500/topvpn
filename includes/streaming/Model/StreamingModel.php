<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Model/Relations/ManyToMany.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class StreamingModel extends AbstractModel{

    private array $keyManyToManyFields = [
        'vpn' =>[
            'pivot_table_name' => 'topvpn_vpn_streaming',
            'this_key_name' => 'streaming_id',
            'that_key_name' => 'vpn_id'],
    ];

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function addRow($data) : object
    {
        $validate = (new Validator)->checkLength($data['streaming_name'], 2, 30, 'streaming_name', 'Имя', true)
            ->checkLength($data['streaming_sys_name'], 2, 30, 'streaming_sys_name', 'Системное имя', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . 'images/streaming/';
        $imgAdded = $this->checkFileAndUpload('streaming_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['streaming_logo'] = $imgAdded->getResultData();
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Streaming добавлен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $validate = (new Validator)->checkLength($data['streaming_name'], 2, 30, 'streaming_name', 'Имя', true)
            ->checkLength($data['streaming_sys_name'], 2, 30, 'streaming_sys_name', 'Системное имя', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }
        $path = V_PLUGIN_INCLUDES_DIR . 'images/streaming/';
        $imgAdded = $this->checkFileAndUpload('streaming_logo', $path);
        if ($imgAdded->getResultStatus() !== 'no_file') {
            if ($imgAdded->getResultStatus() == 'ok') {
                $data['streaming_logo'] = $imgAdded->getResultData();
            }
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        }
        $updatedRow = $this->updateRow($id, $data);

        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Streaming '. $updatedRow['streaming_name'] .' изменен  успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow($data) : object{

        $id = $data['id'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/streaming/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'streaming_logo', $path);
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
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['streaming_name'].' удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function getStreamingByVPNId($id) : array
    {
        $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_streaming ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_streaming.streaming_id)  
                              INNER JOIN {$this->prefix}topvpn_vpn ON ({$this->prefix}topvpn_vpn.id = {$this->prefix}topvpn_vpn_streaming.vpn_id)   
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
                              INNER JOIN {$this->prefix}topvpn_vpn_streaming ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_streaming.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_streaming ON ({$this->prefix}topvpn_streaming.id = {$this->prefix}topvpn_vpn_streaming.streaming_id)
                              WHERE {$this->prefix}topvpn_streaming.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";

        }

        if($keyManyToMany == 'streaming') {
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