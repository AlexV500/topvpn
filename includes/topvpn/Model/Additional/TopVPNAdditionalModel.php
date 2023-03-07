<?php
require_once V_CORE_LIB . 'Model/AbstractModel.php';

class TopVPNAdditionalModel extends AbstractModel{

    public function __construct(string $dbTable)
    {
        parent::__construct($dbTable);
    }

    public function getAdditionalRows(string $catSysName): array {

        $query = "SELECT * FROM `{$this->dbTable}` WHERE cat_sys_name = '$catSysName'";

        $rows = $this->wpdb->get_results($query, ARRAY_A);

        if (empty($rows)) {
            return [];
        }

        return $rows;
    }

    public function getAdditRow($data) : object
    {
        $vpnId = $data['foreign_id'];
        $catSysName = $data['cat_sys_name'];
        if($this->countRows($vpnId, $catSysName) == 0) {
            $recordedRow = $this->insertRow($data);
            if ($recordedRow['last_insert_id'] == 0) {
                $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка записи в Б.Д.');
                return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), $data);
            }
            $result = $this->getRowById( $recordedRow['last_insert_id']);
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', '');
            return Result::setResult('created', $this->resultMessages->getResultMessages($this->getNameOfClass()), $result);
        } else {

         //   $query = $this->wpdb->prepare("SELECT * FROM %s WHERE vpn_id = %s AND cat_sys_name = %s", $this->dbTable, $vpnId, $catSysName);
         //   $query = $this->wpdb->prepare("SELECT * FROM %s WHERE vpn_id = %s", $this->dbTable, $vpnId);
            $query = "SELECT * FROM `{$this->dbTable}` WHERE foreign_id ='$vpnId' AND cat_sys_name = '$catSysName'";
            $result = $this->wpdb->get_row($query, ARRAY_A);

            return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $result);
        }
    }

    public function editRow($id, $data) : object
    {
        $updatedRow = $this->updateRow($id, $data);
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', 'Доп. информация добавлена успешно!');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), $updatedRow);
    }


    public function deleteRow( array $data) : object{

        $id = $data['foreign_id'];
        $catSysName = $data['cat_sys_name'];

        if($this->removeAdditionalRow($id, $catSysName) !== ''){
            $this->resultMessages->addResultMessage($this->getNameOfClass(), 'error', 'Ошибка удаления Б.Д.');
            return Result::setResult('error', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
        }
        $this->resultMessages->addResultMessage($this->getNameOfClass(), 'ok', $data['cat_sys_name'].' удален успешно');
        return Result::setResult('ok', $this->resultMessages->getResultMessages($this->getNameOfClass()), '');
    }

    public function countRows($vpnId, $catSysName){

        $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE foreign_id ='$vpnId' AND cat_sys_name ='$catSysName'";
        return $this->wpdb->get_var($query);
    }

    private function removeAdditionalRow(int $id, string $catSysName){

        $this->wpdb->delete( $this->dbTable, ['foreign_id' => $id, 'cat_sys_name' => $catSysName]);
        return $this->wpdb->last_error;
    }
}