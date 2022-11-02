<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class LangModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
    }

    public function addRow($data)
    {
        $validate = (new Validator)->checkLength($data['lang_name'], 3, 30, 'lang_name', true)
            ->checkLength($data['lang_sys_name'], 2, 10, 'lang_sys_name', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $imgAdded = $this->checkFileAndUpload('lang_logo', 'languageLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['lang_logo'] = $imgAdded->getResultData();
        }
        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getMessage(), $data);
        }
        return Result::setResult('ok', 'Добавлено<br/>' . $imgAdded->getMessage(), $recordedRow);
    }

    public function editRow($id, $data)
    {
        $validate = (new Validator)->checkLength($data['lang_name'], 3, 30, 'lang_name', true)
            ->checkLength($data['lang_sys_name'], 3, 30, 'lang_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $imgAdded = $this->checkFileAndUpload('lang_logo', 'langLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['lang_logo'] = $imgAdded->getResultData();
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

}