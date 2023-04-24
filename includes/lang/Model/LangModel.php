<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';
require_once V_PLUGIN_INCLUDES_DIR . 'lang/Model/Translations/TranslationsModel.php';

class LangModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function getDefaultLocale() : array
    {
        $row = $this->getRowByPk('primary_lang ', 1);
        return $row;
    }

    public function getLocales() : array
    {
        $locales = [];
        $rows = $this->getAllRows(true, false);
        if(count($rows) > 0){
            foreach ($rows as $row){
                $locales[$row['id']] = $row['lang_sys_name'];
            }
        }
        return $locales;
    }

    public function getPrevLocale($lastInsertId) : array
    {
        $prevRecordedRow = [];
        $localesIds = [];
        $rows = $this->getAllRows(false, false);

        if(count($rows) == 0){
            return $prevRecordedRow;
        }
        if(count($rows) > 0){
            foreach ($rows as $row){
                $localesIds[] = $row['id'];
            }
        }
        $key = array_search($lastInsertId, $localesIds);
        $prevKey = $key - 1;
        $prevRowId = $localesIds[$prevKey];

        if(!$prevRowId){
            return $prevRecordedRow;
        }
        return $this->getRowById($prevRowId);
    }

    public function addRow($data) : object
    {
        $validate = (new Validator)->checkLength($data['lang_name'], 3, 30, 'lang_name', true)
            ->checkLength($data['lang_sys_name'], 2, 10, 'lang_sys_name', true);

        if ($validate->getResultStatus() == 'error') {
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . 'images/lang/';
        $imgAdded = $this->checkFileAndUpload('lang_logo', $path);
        if ($imgAdded->getResultStatus() == 'ok') {
            $data['lang_logo'] = $imgAdded->getResultData();
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        $data['position'] = $this->getMaxPosition() + 1;
        $recordedRow = $this->insertRow($data);
        $lastInsertId = $recordedRow['last_insert_id'];
        if ($lastInsertId == 0) {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }

        $prevRecordedRow = $this->getPrevLocale($lastInsertId);
        $translationsModel = new TranslationsModel('topvpn_translations');
        $columnAdded = $translationsModel->addNewTranslationColumn($prevRecordedRow, $data);
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Язык добавлен успешно!');
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $columnAdded->getResultStatus(), $columnAdded->getResultMessage());
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
        $validate = (new Validator)->checkLength($data['lang_name'], 3, 30, 'lang_name', true)
            ->checkLength($data['lang_sys_name'], 3, 30, 'lang_sys_name', true);

        if($validate->getResultStatus() == 'error'){
            return Result::setResult('error', $validate->getResultMessage(), $data);
        }

        $path = V_PLUGIN_INCLUDES_DIR . 'images/lang/';
        $imgAdded = $this->checkFileAndUpload('lang_logo', $path);

        if(($data['lang_sys_name_before']) !== ''){
            $langSysNameBefore = $data['lang_sys_name_before'];
            unset($data['lang_sys_name_before']);
            $translationsModel = new TranslationsModel('topvpn_translations');
            $columnEdited = $translationsModel->editTranslationColumn($langSysNameBefore, $data);
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $columnEdited->getResultStatus(), $columnEdited->getResultMessage());
            if($columnEdited->getResultStatus() == 'error'){
                $row = $this->getRowById($id);
                return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $row);
            }
        }

        if ($imgAdded->getResultStatus() !== 'no_file') {
            if ($imgAdded->getResultStatus() == 'ok') {
                $data['lang_logo'] = $imgAdded->getResultData();
            }
            $this->resultMessages->addResultMessage($this->getNameOfClass(), $imgAdded->getResultStatus(), $imgAdded->getResultMessage());
        }

        $updatedRow = $this->updateRow($id, $data);

        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Язык '. $updatedRow['lang_name'] .' изменен  успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow( array $data) : object{

        $id = $data['id'];
        $this->deleteName = $data['lang_name'];

        $path = V_PLUGIN_INCLUDES_DIR . 'images/lang/';
        $deleteImgResult = $this->checkFileAndUnlink($data, 'lang_logo', $path);
        $this->resultMessages->addResultMessage($this->getNameOfClass(), $deleteImgResult->getResultStatus(), $deleteImgResult->getResultMessage());
        if($this->removeRow($id) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка удаления Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['lang_name'].'Язык удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

}