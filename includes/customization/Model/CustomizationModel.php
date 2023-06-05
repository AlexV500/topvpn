<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';
require_once V_CORE_LIB . 'Utils/Validator.php';
require_once V_CORE_LIB . 'Utils/Result.php';

class CustomizationModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
        $this->unsetMultiLangMode();
    }

    public function addRow($data) : object
    {
//        $validate = (new Validator)->checkLength($data['customization_name'], 3, 255, 'customization_name', true)
//            ->checkLength($data['page_uri'], 2, 255, 'page_uri', true);

//        if ($validate->getResultStatus() == 'error') {
//            return Result::setResult('error', $validate->getResultMessage(), $data);
//        }

        $recordedRow = $this->insertRow($data);
        if ($recordedRow['last_insert_id'] == 0) {
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Сниппет добавлен успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $recordedRow);
    }

    public function editRow($id, $data) : object
    {
//        $validate = (new Validator)->checkLength($data['customization_name'], 3, 255, 'customization_name', true)
//            ->checkLength($data['page_uri'], 2, 255, 'page_uri', true);
//
//        if($validate->getResultStatus() == 'error'){
//            return Result::setResult('error', $validate->getResultMessage(), $data);
//        }

        $updatedRow = $this->updateRow($id, $data);

        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Сниппет '. $updatedRow['customization_name'] .' изменен  успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }

    public function deleteRow( array $data) : object{

        $id = $data['id'];
        $this->deleteName = $data['customization_name'];

        if($this->removeRow($id) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка удаления Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['customization_name'].'Сниппет удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

}