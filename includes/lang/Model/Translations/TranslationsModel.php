<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';

class TranslationsModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function addNewTranslationColumn($prevRecordedRow, $data){

        $prevLangSysName = $prevRecordedRow['lang_sys_name'];
        $langSysName = $data['lang_sys_name'];

        $query = "ALTER TABLE `{$this->dbTable}` ADD `{$langSysName}` TEXT NOT NULL AFTER `{$prevLangSysName}`; ";
        $result = $this->wpdb->query($query);
        if(!$result){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Не добавлена колонка переводов в Б.Д.!');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Колонка переводов в Б.Д. добавлена успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function editTranslationColumn($langSysNameBefore, $data){

        $langSysName = $data['lang_sys_name'];
        $query = "ALTER TABLE `{$this->dbTable}` CHANGE `{$langSysNameBefore}` `{$langSysName}` TEXT CHARACTER SET utf8mb4 NOT NULL; ";
        echo $query;
        $result = $this->wpdb->query($query);
        if(!$result){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Не изменена колонка переводов в Б.Д.!');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Колонка переводов '.$langSysNameBefore.' в Б.Д. изменена на '.$langSysName.' успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function autoAdd($data){
        $recordedRow = $this->insertRow($data);
        $lastInsertId = $recordedRow['last_insert_id'];
        if ($lastInsertId == 0) {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Перевод добавлен успешно!');

        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {

        $updatedRow = $this->updateRow($id, $data);

        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Перевод изменен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow( array $data) : object{

        $id = $data['id'];

        if($this->removeRow($id) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка удаления Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Перевод удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }


}