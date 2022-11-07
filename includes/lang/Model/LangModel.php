<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class LangModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function addRow($data) : object
    {
        $validate = (new Validator)->checkLength($data['lang_name'], 3, 30, 'lang_name', true)
            ->checkLength($data['lang_sys_name'], 2, 10, 'lang_sys_name', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . '/images/lang/';
        $imgAdded = $this->checkFileAndUpload('lang_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['lang_logo'] = $imgAdded->getResultData();
        }
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getResultMessage(), $data);
        }
        return Result::setResult('ok', 'Язык добавлен успешно!<br/>' . $imgAdded->getResultMessage(), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $validate = (new Validator)->checkLength($data['lang_name'], 3, 30, 'lang_name', true)
            ->checkLength($data['lang_sys_name'], 3, 30, 'lang_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . '/images/lang/';
        $imgAdded = $this->checkFileAndUpload('lang_logo', $path);
        if ($imgAdded->getStatus() == 'ok') {
            $data['lang_logo'] = $imgAdded->getResultData();
        }

        $updatedRow = $this->updateRow($id, $data);

        return Result::setResult('ok', 'Язык изменен успешно!<br/>'.$imgAdded->getResultMessage(), $updatedRow);
    }

    public function deleteRow( array $data) : object{

        $id = $data['id'];
        $this->deleteName = $data['lang_name'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/lang/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'lang_logo', $path);

        if($this->removeRow($id) !== ''){
            return Result::setResult('error', 'Ошибка<br/>'.$deleteImgResult, '');
        }
        return Result::setResult('ok', 'Удалено<br/>'.$deleteImgResult, '');
    }

}