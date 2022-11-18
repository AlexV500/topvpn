<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Model/Relations/ManyToMany.php';
require_once V_CORE_LIB . 'Utils/Validator.php';

class TopVPNModel extends AbstractModel{

    private array $keyManyToManyFields = [
        'os' =>[
            'pivot_table_name' => 'topvpn_vpn_os',
            'this_key_name' => 'vpn_id',
            'that_key_name' => 'os_id']
    ];

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
    }

    public function addRow( array $data) : object
    {
        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', true);

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
        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', true);

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
//        print_r($data);
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

}