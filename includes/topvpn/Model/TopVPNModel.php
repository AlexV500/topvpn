<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Model/Relations/ManyToMany.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

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
        $path = V_PLUGIN_INCLUDES_DIR . '/images/vpn/';
        $imgAdded = $this->checkFileAndUpload('vpn_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['vpn_logo'] = $imgAdded->getResultData();
        }
        $data['position'] = $this->getMaxPosition() + 1;
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] > 0) {
            $recordedRow = ManyToMany::addManyToOne($this->keyManyToManyFields, $recordedRow, $data);
        } else {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getResultMessage(), $data);
        }
        return Result::setResult('ok', 'Добавлено<br/>'.$imgAdded->getResultMessage(), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $validate = (new Validator)->checkLength($data['vpn_name'], 3, 30, 'vpn_name', true)
            ->checkLength($data['vpn_sys_name'], 3, 30, 'vpn_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . '/images/vpn/';
        $imgAdded = $this->checkFileAndUpload('vpn_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['vpn_logo'] = $imgAdded->getResultData();
        }

        $updatedRow = $this->updateRow($id, $data);
        if(count($updatedRow) > 0){
            $updatedRow = ManyToMany::editManyToOne($this->keyManyToManyFields, $updatedRow, $id, $data);
        } else {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getResultMessage(), $data);
        }

        return Result::setResult('ok', 'Изменено<br/>'.$imgAdded->getResultMessage(), $updatedRow);
    }

    public function deleteRow($data) : object{

        $id = $data['id'];
        $this->deleteName = $data['lang_name'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/vpn/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'vpn_logo', $path);

        if($this->removeRow($id) !== ''){
            return Result::setResult('error', 'Ошибка<br/>'.$deleteImgResult, '');
        }
        return Result::setResult('ok', 'Удалено<br/>'.$deleteImgResult, '');
    }

}