<?php


class LanguageModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
    }

    public function addRow($data)
    {
        $validate = (new Validator)->checkLength($data['language_name'], 3, 30, 'os_name', true)
            ->checkLength($data['language_sys_name'], 2, 10, 'os_sys_name', true);

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
        $validate = (new Validator)->checkLength($data['language_name'], 3, 30, 'language_name', true)
            ->checkLength($data['language_sys_name'], 3, 30, 'language_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $imgAdded = $this->checkFileAndUpload('lang_logo', 'languageLogo');
        if ($imgAdded->getStatus() == 'ok') {
            $data['lang_logo'] = $imgAdded->getResultData();
        }

        $updatedRow = $this->updateRow($id, $data);
        if(count($updatedRow) > 0){
            $updatedRow = ManyToMany::editManyToOne($this->keyManyToManyFields, $updatedRow, $id, $data);
        } else {
            return Result::setResult('error', 'Ошибка<br/>' . $imgAdded->getMessage(), $data);
        }

        return Result::setResult('ok', 'Изменено<br/>'.$imgAdded->getMessage(), $updatedRow);
    }

    public function deleteRow($id){

        if($this->removeRow($id) !== ''){
            return Result::setResult('error', 'Ошибка<br/>', '');
        }
        return Result::setResult('ok', 'Удалено<br/>', '');
    }


}