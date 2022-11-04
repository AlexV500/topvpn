<?php
require_once V_CORE_LIB . 'Utils/ImageUpload.php';

abstract class AbstractModel
{
    public $wpdb;
    public string $prefix;
    public string $dbTable;
    public string $pk;
    public string $lang = '';
    protected bool $multiLangMode = true;
    protected string $orderDirection = 'ASC';
    public string $rowCount;
    public string $offset;


    protected function __construct(string $dbTable)
    {
        global $wpdb;
        $this->wpdb = &$wpdb;
        $this->prefix = $wpdb->prefix;
        $this->dbTable = $this->prefix.$dbTable;
    }

    public function switchMultiLangMode($lang)
    {
        if($lang !== ''){
            $this->setMultiLangMode();
        } else {
            $this->unsetMultiLangMode();
        }
        return $this;
    }

    public function setMultiLangMode()
    {
        $this->multiLangMode = true;
        return $this;
    }

    public function unsetMultiLangMode()
    {
        $this->multiLangMode = false;
        return $this;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function setRowCount($rowCount)
    {
        $this->rowCount = $rowCount;
        return $this;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function setOrderDirection($direction)
    {
        $this->orderDirection = $direction;
        return $this;
    }

    public function getRowById($id)
    {
        return $this->wpdb->get_row("SELECT * FROM `{$this->dbTable}` WHERE id={$id}", ARRAY_A);
    }

    public function getRowByPk($pk, $pkValue)
    {
        return $this->wpdb->get_row("SELECT * FROM `{$this->dbTable}` WHERE $pk={$pkValue}", ARRAY_A);
    }

    public function getRowByColumnName($columnName, $cnValue)
    {
        return $this->wpdb->get_row("SELECT * FROM `{$this->dbTable}` WHERE $columnName={$cnValue}", ARRAY_A);
    }

    public function getAllRows(bool $activeMode = true, bool $orderMode = true, bool $paginationMode = true)
    {
        $orderSql = '';
        $paginatSql = '';

        if ($orderMode) {
            $orderSql = 'ORDER BY position ' . $this->orderDirection;
        }

        if ($paginationMode) {
            $paginatSql = 'LIMIT ' . $this->countAllRows($activeMode) . ' OFFSET ' . $this->offset;
        }

        if ($this->multiLangMode) {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE active=1 AND lang = '{$this->lang}' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE lang = '{$this->lang}' $orderSql $paginatSql";
            }
        } else {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE active=1 $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$this->dbTable}` $orderSql $paginatSql";
            }
        }

        return $this->wpdb->get_results($sql, ARRAY_A);
    }


    public function countAllRows($activeMode = true){

        if($this->multiLangMode) {
            if ($activeMode) {
                $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE active=1 AND lang = '{$this->lang}'";
            } else {
                $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE lang = '{$this->lang}'";
            }
        } else {
            if ($activeMode) {
                $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE active=1";
            } else {
                $query = "SELECT COUNT(*) FROM `{$this->dbTable}`";
            }
        }

        return $this->wpdb->get_var($query);
    }
    
    public function insertRow(array $fields, bool $validationMode = true) : array{

//        if($validationMode) {
//            $rules = $this->rebuildRules($this->validationRules);
//            $validation = $this->validator->validate($fields, $rules);
//
//            if ($validation->fails()) {
//                throw new ExcValidation('cant add article', $validation->errors());
//            }
//        }
        $names = [];
        $masks = [];
        $returnData = [];

        foreach($fields as $field => $val){
            if ($field == 'created'){
                $val = date( 'Y-m-d H:i:s' , time() );
            }
            $names[] = $field;
            $masks[] = "$val";
        }

        $namesStr = implode(', ', $names);
        $masksStr = implode(', ', $masks);

        $query = "INSERT INTO {$this->dbTable} ($namesStr) VALUES ($masksStr)";
        $this->wpdb->query($query, $fields);

        $insertedRow = $this->getRowById($this->wpdb->insert_id);
        foreach ($insertedRow as $key => $val){
            if(array_key_exists($key, $fields)){
                $returnData[$key] = $val;
            }
        }
        $returnData['last_insert_id'] = $this->wpdb->insert_id;
        return $returnData;
    }

    public function updateRow(int $id, array $fields, bool $validationMode = true) : array{

//        if($validationMode) {
//            $rules = $this->rebuildRules($this->validationRules, $id);
//            $validation = $this->validator->validate($fields, $rules);
//
//            if ($validation->fails()) {
//                throw new ExcValidation('cant add article', $validation->errors());
//            }
//        }
        $pairs = [];
        $returnData = [];

        foreach($fields as $field => $val){
            if ($field == 'updated'){
                $val = date( 'Y-m-d H:i:s' , time() );
            }
            $pairs[] = "$field=$val";
        }

        $pairsStr = implode(', ', $pairs);

        $query = "UPDATE `{$this->dbTable}` SET $pairsStr WHERE {$this->pk} = {$this->pk}";
        if($this->wpdb->query($query, $fields + [$this->pk => $id])){
            $insertedRow = $this->getRowById($this->wpdb->insert_id);
            foreach ($insertedRow as $key => $val){
                if(array_key_exists($key, $fields)){
                    $returnData[$key] = $val;
                }
            }
        }
        return $returnData;
    }

    public function removeRow(int $pk){

        $this->wpdb->delete( $this->dbTable, [$this->pk => $pk]);
        return $this->wpdb->last_error;
    }

    public function getMaxPosition() : int
    {
        return $this->wpdb->get_var("SELECT MAX(position) as maxPosition FROM `{$this->dbTable}`");
    }

    public function positionUp(int $id) : bool
    {
        $currentRow = false;
        $prevRow = false;
        $count = $this->countAllRows(false);
        $query = $this->getAllRows(false, true, false);
        if ($count > 1) {
            foreach ($query as $key => $row) {
                if ($row['id'] == $id) {
                    if ($key !== 0) {
                        $currentRow = $query[$key - 1];
                        $prevRow = $query[$key];
                    }
                }
            }
            if (($currentRow) && ($prevRow)) {
                if (($currentRow !== '') && ($prevRow !== '')) {
                    $this->setPosition($id, $currentRow, $prevRow);
                } else return false;
            } else return false;
        } else return false;
    }

    public function positionDown(int $id) : bool
    {
        $currentRow = false;
        $nextRow = false;
        $count = $this->countAllRows(false);
        $query = $this->getAllRows(false, true, false);

        if ($count > 1) {
            foreach ($query as $key => $row) {
                if ($row['id'] == $id) {
                    if ($key < ($count - 1)) {
                        $currentRow = $query[$key];
                        $nextRow = $query[$key + 1];
                    }
                }
            }
            if (($currentRow) && ($nextRow)) {
                if (($currentRow !== '') && ($nextRow !== '')) {
                    $this->setPosition($id, $currentRow, $nextRow);
                } else return false;
            } else return false;
        } else return false;
    }

    protected function setPosition($id, $currentRow, $nextOrPrevRow)
    {
        $fields1 = [
            'position' => $nextOrPrevRow['position']
        ];
        $this->updateRow($currentRow['id'], $fields1, false);

        $fields2 = [
            'position' => $currentRow['position']
        ];
        $this->updateRow($nextOrPrevRow['id'], $fields2, false);
    }

    public function checkFileAndUpload($fieldName, $folder){
        $path = V_PLUGIN_DIR . '/images/' . $folder . '/';
        return ImageUpload::Upload($fieldName, $path);
    }
}