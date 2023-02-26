<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Model/Relations/ManyToMany.php';
require_once V_CORE_LIB . 'Model/DTO/QueryParamsDTO.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class TopVPNModel extends AbstractModel{

    private array $keyManyToManyFields = [
        'device' =>[
            'pivot_table_name' => 'topvpn_vpn_device',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'device_id'],
//        'os' =>[
//            'pivot_table_name' => 'topvpn_vpn_os',
//            'this_key_name' => 'vpn_id',
//            'that_key_name' => 'os_id'],
        'streaming' =>[
            'pivot_table_name' => 'topvpn_vpn_streaming',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'streaming_id'],
        'payments' =>[
            'pivot_table_name' => 'topvpn_vpn_payments',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'payments_id']
    ];

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
    }

    public function addRow( array $data) : object{

        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', 'Имя', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', 'Системное имя', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }
        $path = V_PLUGIN_INCLUDES_DIR . 'images/vpn/';
        $imgAdded = $this->checkFileAndUpload('vpn_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['vpn_logo'] = $imgAdded->getResultData();
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        $data['position'] = $this->getMaxPosition() + 1;
        $recordedRow = $this->insertRow($data);

        if ($recordedRow['last_insert_id'] > 0) {
            $recordedRow = ManyToMany::addManyToOne($this->keyManyToManyFields, $recordedRow, $data);
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'VPN добавлен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $resultMessages = new ResultMessages();
        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', 'Имя', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', 'Системное имя', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . 'images/vpn/';
       // $this->UnlinkAllUnusedImages('vpn_logo', $path);
        $imgAdded = $this->checkFileAndUpload('vpn_logo', $path);
        if ($imgAdded->getResultStatus() !== 'no_file') {
            if ($imgAdded->getResultStatus() == 'ok') {
                $data['vpn_logo'] = $imgAdded->getResultData();
            }
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        }
        $updatedRow = $this->updateRow($id, $data);
//        echo '<pre>';
//        print_r($updatedRow);
//        echo '</pre>';
        if(count($updatedRow) > 0){
            $updatedRow = ManyToMany::editManyToOne($this->keyManyToManyFields, $updatedRow, $id, $data);
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'VPN '. $updatedRow['vpn_name'] .' изменен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow($data) : object{

        $id = $data['id'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/vpn/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'vpn_logo', $path);
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $deleteImgResult->getResultStatus(), $deleteImgResult->getResultMessage());
        $deleteManyToOne = ManyToMany::deleteManyToOne($this->keyManyToManyFields, $id);
        if($deleteManyToOne->getResultStatus() == 'error'){
            return Result::setResult('error', $deleteManyToOne->getResultMessage('ManyToMany'), '');
        } else {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $deleteManyToOne->getResultMessage('ManyToMany'));
        }
        if($this->removeRow($id) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['vpn_name'].' удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function getRowsByRelId(string $keyManyToMany, int $id, bool $paginationMode = true, bool $limitMode = false) : array{

        $queryParamsDTO = $this->getSqlQueryParams($paginationMode, $limitMode);
        $orderSql = $queryParamsDTO->getOrderSql();
        $paginatSql = $queryParamsDTO->getPaginatSql();
        $multiLangMode = $queryParamsDTO->getMultiLangMode();

        if($keyManyToMany == ''){
            return [];
        }
        if($keyManyToMany == 'os'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";

        }

        if($keyManyToMany == 'device') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_device ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_device.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_device ON ({$this->prefix}topvpn_device.id = {$this->prefix}topvpn_vpn_device.device_id)
                              WHERE {$this->prefix}topvpn_device.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";
        }
        if($keyManyToMany == 'streaming'){
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_streaming ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_streaming.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_streaming ON ({$this->prefix}topvpn_streaming.id = {$this->prefix}topvpn_vpn_streaming.streaming_id)
                              WHERE {$this->prefix}topvpn_streaming.id = $id AND {$this->dbTable}.active = 1 $multiLangMode $orderSql $paginatSql";

        }


//        if ($this->multiLangMode) {
//            $sql = "SELECT $this->dbTable.*
//                              FROM $this->dbTable
//                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)
//                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
//                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 AND {$this->dbTable}.lang = '{$this->lang}' $orderSql $paginatSql";
//        } else {
//            $sql = "SELECT $this->dbTable.*
//                              FROM $this->dbTable
//                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)
//                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
//                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 $orderSql $paginatSql";
//        }
//        echo $sql;
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
                              INNER JOIN {$this->prefix}topvpn_vpn_os ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_os.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_os ON ({$this->prefix}topvpn_os.id = {$this->prefix}topvpn_vpn_os.os_id)
                              WHERE {$this->prefix}topvpn_os.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";

        }

        if($keyManyToMany == 'device') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_device ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_device.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_device ON ({$this->prefix}topvpn_device.id = {$this->prefix}topvpn_vpn_device.device_id)
                              WHERE {$this->prefix}topvpn_device.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";
        }

        if($keyManyToMany == 'streaming') {
            $sql = "SELECT $this->dbTable.*
                              FROM $this->dbTable
                              INNER JOIN {$this->prefix}topvpn_vpn_streaming ON ({$this->dbTable}.id={$this->prefix}topvpn_vpn_streaming.vpn_id)    
                              INNER JOIN {$this->prefix}topvpn_streaming ON ({$this->prefix}topvpn_streaming.id = {$this->prefix}topvpn_vpn_streaming.streaming_id)
                              WHERE {$this->prefix}topvpn_streaming.id = $id AND {$this->dbTable}.active = 1 $multiLangMode";
        }

        $this->wpdb->get_results($sql);
        return $this->wpdb->num_rows;
    }


    public function combineAdditionalData(array $rowsData, array $additionalData): array
    {
        foreach ($rowsData as &$rowData) {
            foreach ($additionalData as $rowAdditional) {
                if ($rowData['id'] === $rowAdditional['foreign_id']) {
                    if($rowAdditional['active'] == 1){
                        $rowData = array_merge($rowData, array_filter([
                            'top_status_description' => $rowAdditional['top_status_description'] ?? null,
                            'short_description' => $rowAdditional['short_description'] ?? null,
                            'rating' => $rowAdditional['rating'] ?? null,
                            'rating_description' => $rowAdditional['rating_description'] ?? null,
                        ]));
                    }

                }
            }
        }
        return $rowsData;
    }
}