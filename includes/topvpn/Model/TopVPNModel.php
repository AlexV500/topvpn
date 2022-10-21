<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'system/Model/AbstractModel.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'system/Model/Relations/ManyToMany.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'system/Utils/ImageUpload.php';

class TopVPNModel extends AbstractModel{

    private $keyManyToManyFields = [
        'os' =>[
            'pivot_table_name' => 'vpn_os',
            'this_key_name' => 'vpn_os',
            'that_key_name' => 'os_id']
    ];

    public function addRow($data)
    {
        $imgAdded = $this->checkFileAndUpload('os_logo', 'OSLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['os_logo'] = $imgAdded->getResultData();
        }
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] > 0) {
            $recordedRow = ManyToMany::addManyToOne($this->keyManyToManyFields, $recordedRow, $data);
        } else {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getMessage(), $data);
        }
        return Result::setResult('ok', 'Добавлено<br/>'.$imgAdded->getMessage(), $recordedRow);
    }

    public function editRow($id, $data)
    {
        $imgAdded = $this->checkFileAndUpload('os_logo', 'OSLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['os_logo'] = $imgAdded->getResultData();
        }

        $updatedRow = $this->updateRow($id, $data);
        if(count($updatedRow) > 0){
            $updatedRow = ManyToMany::editManyToOne($this->keyManyToManyFields, $id, $data);
        }

        return $updatedRow;
    }

    public function checkFileAndUpload($fieldName, $folder){
        $path = FXREVIEWS_CORE_LIB . '/images/' . $folder . '/';
        return ImageUpload::Upload($fieldName, $path);
    }

}