<?php
require_once V_CORE_LIB . 'Utils/ImageUpload.php';
require_once V_CORE_LIB . 'Utils/ErrorStatus.php';
require_once V_CORE_LIB . 'Utils/Result.php';
require_once V_CORE_LIB . 'Utils/ResultMessages.php';
require_once V_CORE_LIB . 'Model/DTO/QueryParamsDTO.php';

abstract class AbstractModel
{
    public $wpdb;
    public string $prefix;
    public string $dbTable;
    public string $pk = 'id' ;
    public string $lang = '';
    protected bool $multiLangMode = true;
    protected string $orderColumn = 'id';
    protected string $orderDirection = 'ASC';
    public string $rowCount;
    public string $paginationCount;
    public string $limitCount;
    public string $offset;
    public object $errorStatus;
    public object $resultMessages;

    protected function __construct(string $dbTable)
    {
        global $wpdb;
        $this->wpdb = &$wpdb;
        $this->prefix = $wpdb->prefix;
        $this->dbTable = $this->prefix.$dbTable;
        $this->errorStatus = new ErrorStatus();
        $this->resultMessages = new ResultMessages();
    }

    public function getNameOfClass() : string
    {
        return static::class;
    }

    public function getDbTable() : string
    {
        return $this->dbTable;
    }

    public function switchMultiLangMode( string $lang) : object
    {
        if($lang !== ''){
            $this->setMultiLangMode();
            $this->setLang($lang);
        } else {
            $this->unsetMultiLangMode();
            $this->setLang('');
        }
        return $this;
    }

    public function setMultiLangMode() : object
    {
        $this->multiLangMode = true;
        return $this;
    }

    public function unsetMultiLangMode() : object
    {
        $this->multiLangMode = false;
        return $this;
    }

    public function setLang( string $lang) : object
    {
        $this->lang = $lang;
        return $this;
    }

    public function setPk( string $pk) : object
    {
        $this->pk = $pk;
        return $this;
    }

    public function setRowCount( int $rowCount) : object
    {
        $this->rowCount = $rowCount;
        return $this;
    }

    public function setLimitCount( int $limitCount) : object
    {
        $this->limitCount = $limitCount;
        return $this;
    }

    public function setPaginationCount( int $paginationCount) : object
    {
        $this->paginationCount = $paginationCount;
        return $this;
    }

    public function setOffset($offset) : object
    {
        $this->offset = $offset;
        return $this;
    }

    public function setOrderColumn( string $column) : object
    {
        $this->orderColumn = $column;
        return $this;
    }

    public function setOrderDirection( string $direction) : object
    {
        $this->orderDirection = $direction;
        return $this;
    }

    public function getRowById( int $id)
    {
        return $this->wpdb->get_row("SELECT * FROM `{$this->dbTable}` WHERE id={$id}", ARRAY_A);
    }

    public function getRowByPk($pk, $pkValue)
    {
        $multiLangMode = '';

//        if ($this->multiLangMode) {
//            $multiLangMode = 'AND ' . $this->dbTable . '.lang =' . '"'.$this->lang.'"';
//        }

        return $this->wpdb->get_row("SELECT * FROM `{$this->dbTable}` WHERE $pk='$pkValue'", ARRAY_A);
    }

    public function getSqlQueryParams(bool $paginationMode = true, bool $limitMode = false) : object{

        $orderSql = '';
        $paginatSql = '';
        $multiLangMode = '';
        $orderSql = 'ORDER BY ' . $this->orderColumn . ' ' . $this->orderDirection;
        if ($limitMode) {
            $paginationMode = false;
            $paginatSql = 'LIMIT ' . $this->limitCount;
        }
        if ($paginationMode) {
            $paginatSql = 'LIMIT ' . $this->paginationCount . ' OFFSET ' . $this->offset;
        }
        if ($this->multiLangMode) {
            $multiLangMode = 'AND ' . $this->dbTable . '.lang =' . '"'.$this->lang.'"';
        }

        return new QueryParamsDTO($orderSql, $paginatSql, $multiLangMode);
    }

    public function getRowsByColumnName($columnName, $cnValue, bool $activeMode = true, bool $paginationMode = false, bool $limitMode = false)
    {
        $orderSql = '';
        $paginatSql = '';

        if ($paginationMode) {
            $paginatSql = 'LIMIT ' . $this->paginationCount . ' OFFSET ' . $this->offset;
        }

        if ($limitMode) {
            $paginationMode = false;
            $paginatSql = 'LIMIT ' . $this->limitCount;
        }

        if ($this->multiLangMode) {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE $columnName='$cnValue' AND active='1' AND lang = '{$this->lang}' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE $columnName='$cnValue' AND lang = '{$this->lang}' $orderSql $paginatSql";
            }
        } else {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE $columnName='$cnValue' AND active='1' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$this->dbTable}` $columnName='$cnValue' $orderSql $paginatSql";
            }
        }
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

    public function getAllRows(bool $activeMode = true, bool $paginationMode = true, bool $limitMode = false)
    {
        $orderSql = '';
        $paginatSql = '';

        $orderSql = 'ORDER BY '.$this->orderColumn.' ' . $this->orderDirection;

        if ($limitMode) {
            $paginationMode = false;
            $paginatSql = 'LIMIT ' . $this->limitCount;
        }

        if ($paginationMode) {
            $paginatSql = 'LIMIT ' . $this->paginationCount . ' OFFSET ' . $this->offset;
        }

        if ($this->multiLangMode) {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE active='1' AND lang = '{$this->lang}' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE lang = '{$this->lang}' $orderSql $paginatSql";
            }
        } else {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$this->dbTable}` WHERE active='1' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$this->dbTable}` $orderSql $paginatSql";
            }
        }

        return $this->wpdb->get_results($sql, ARRAY_A);
    }

    public function getAllRowsFromCustomTable( string $dbTable, bool $activeMode = true, bool $paginationMode = true)
    {
        $orderSql = '';
        $paginatSql = '';
        $dbTable = $this->prefix.$dbTable;
        $orderSql = 'ORDER BY '.$this->orderColumn.' ' . $this->orderDirection;

        if ($paginationMode) {
            $paginatSql = 'LIMIT ' . $this->paginationCount . ' OFFSET ' . $this->offset;
        }

        if ($this->multiLangMode) {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$dbTable}` WHERE active='1' AND lang = '{$this->lang}' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$dbTable}` WHERE lang = '{$this->lang}' $orderSql $paginatSql";
            }
        } else {
            if ($activeMode) {
                $sql = "SELECT * FROM `{$dbTable}` WHERE active='1' $orderSql $paginatSql";
            } else {
                $sql = "SELECT * FROM `{$dbTable}` $orderSql $paginatSql";
            }
        }

        return $this->wpdb->get_results($sql, ARRAY_A);
    }


//    public function countAllRows($activeMode = true){
//
//        if($this->multiLangMode) {
//            if ($activeMode) {
//                $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE active='1' AND lang = '{$this->lang}'";
//            } else {
//                $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE lang = '{$this->lang}'";
//            }
//        } else {
//            if ($activeMode) {
//                $query = "SELECT COUNT(*) FROM `{$this->dbTable}` WHERE active='1'";
//            } else {
//                $query = "SELECT COUNT(*) FROM `{$this->dbTable}`";
//            }
//        }
//
//        return $this->wpdb->get_var($query);
//    }

//    public function countAllRowsFromCustomTable( string $dbTable, $activeMode = true){
//
//        $dbTable = $this->prefix.$dbTable;
//
//        if($this->multiLangMode) {
//            if ($activeMode) {
//                $query = "SELECT COUNT(*) FROM `{$dbTable}` WHERE active='1' AND lang = '{$this->lang}'";
//            } else {
//                $query = "SELECT COUNT(*) FROM `{$dbTable}` WHERE lang = '{$this->lang}'";
//            }
//        } else {
//            if ($activeMode) {
//                $query = "SELECT COUNT(*) FROM `{$dbTable}` WHERE active='1'";
//            } else {
//                $query = "SELECT COUNT(*) FROM `{$dbTable}`";
//            }
//        }
//
//        return $this->wpdb->get_var($query);
//    }

    public function countAllRows(bool $activeMode = true)
    {
        $query = "SELECT COUNT(*) FROM `{$this->dbTable}`";

        if ($this->multiLangMode) {
            $query .= " WHERE lang = '{$this->lang}'";

            if ($activeMode) {
                $query .= " AND active = '1'";
            }
        } elseif ($activeMode) {
            $query .= " WHERE active = '1'";
        }

        return (int) $this->wpdb->get_var($query);
    }

    public function countAllRowsFromCustomTable(string $table, bool $onlyActive = true)
    {
        // Подготовленный запрос
        $condition = $onlyActive ? 'active = 1' : '1=1';
        $query = $this->wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->prefix}$table WHERE $condition");

        // Если используется мультиязычный режим, добавляем условие на язык.
        if ($this->multiLangMode) {
            $query .= $this->wpdb->prepare(" AND lang = $this->lang");
        }

        // Выполнение запроса и возврат количества строк
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
//        echo '<pre>';
//        print_r($fields);
//        echo '</pre>';
        foreach($fields as $field => $val){
            if(is_array($val)){
                continue;
            }
            if ($field == 'created'){
                $val = date( 'Y-m-d H:i:s' , time() );
            }
            if ($field == 'updated') {
                $val = date('Y-m-d H:i:s', time());
            }
            if ($field == 'position') {
                $val = $this->getMaxPosition() + 1;
            }
            $names[] = $field;
            $masks[] = "'".trim($val)."'";
        }

        $namesStr = implode(', ', $names);
        $masksStr = implode(', ', $masks);

        $query = "INSERT INTO {$this->dbTable} ($namesStr) VALUES ($masksStr)";
        $this->wpdb->query($query, $fields);

        $insertedRow = $this->getRowById($this->wpdb->insert_id);
//        echo 'Test<br/>';
//        print_r($insertedRow);
        foreach ($insertedRow as $key => $val){
            if(array_key_exists($key, $fields)){
                $returnData[$key] = $val;
            }
        }
        $returnData['last_insert_id'] = $this->wpdb->insert_id;
        return $returnData;
    }

//    public function insertRow(array $fields): array
//    {
//
//        // Обработка полей
//        $processedFields = $this->processFields($fields);
//
//        // Подготовленный запрос
//        $query = $this->wpdb->prepare(
//            "INSERT INTO {$this->dbTable} (%s) VALUES (%s)",
//            implode(',', array_keys($processedFields)),
//            implode(',', array_fill(0, count($processedFields), '%s'))
//        );
//
//        // Выполнение запроса
//        $this->wpdb->query($query, array_values($processedFields));
//
//        // Получение вставленной строки
//        $insertedRow = $this->getRowById($this->wpdb->insert_id);
//
//        // Сбор данных для возврата
//        $returnData = [];
//        foreach ($insertedRow as $key => $val) {
//            if (array_key_exists($key, $fields)) {
//                $returnData[$key] = $val;
//            }
//        }
//        $returnData['last_insert_id'] = $this->wpdb->insert_id;
//        return $returnData;
//    }

    /**
     * Обработка полей перед вставкой в базу данных.
     *
     * @param array $fields
     * @return array
     */
    private function processFields(array $fields): array
    {
        $processedFields = [];
        foreach ($fields as $field => $value) {
            switch ($field) {
                case 'created':
                case 'updated':
                    $value = date('Y-m-d H:i:s');
                    break;
                case 'position':
                    $value = $this->getMaxPosition() + 1;
                    break;
            }
            $processedFields[$field] = trim($value);
        }
        return $processedFields;
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
        $this->errorStatus->setErrorStatus('updateRow');

        foreach ($fields as $field => $val) {
            if (is_array($val)) {
                continue;
            }
            if ($field == 'updated') {
                $val = date('Y-m-d H:i:s', time());
            }
            $val = trim($val);
            $pairs[] = "$field='$val'";
        }

        $pairsStr = implode(', ', $pairs);

        $query = "UPDATE `{$this->dbTable}` SET $pairsStr WHERE {$this->pk} = {$id}";
        if ($this->wpdb->query($query, $fields + [$this->pk => $id])) {
            $updatedRow = $this->getRowByPk($this->pk, $id);
//            echo '<pre>';
//            print_r($updatedRow);
//            echo '</pre>';
            if ($updatedRow !== null) {
                foreach ($updatedRow as $key => $val) {
                    if (array_key_exists($key, $fields)) {
                        $returnData[$key] = $val;
                    }
                }
            } else $this->errorStatus->setErrorStatus('updateRow', true);
        }
        return $returnData;
    }

    public function removeRow(int $pk){

        $this->wpdb->delete( $this->dbTable, [$this->pk => $pk]);
        return $this->wpdb->last_error;
    }

    public function getMaxPosition() : int
    {
        $query = "SELECT COUNT(*) FROM `{$this->dbTable}`";
        $count =  $this->wpdb->get_var($query);
        if($count == 0){
            return 0;
        }
        return $this->wpdb->get_var("SELECT MAX(position) as maxPosition FROM `{$this->dbTable}`");
    }

    public function positionUp(int $id) : bool
    {
        $currentRow = false;
        $prevRow = false;
        $count = $this->countAllRows(false);
        $query = $this->getAllRows(false, true, false);
        if (($count > 1) && ($id > 0)) {
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
                   return $this->setPosition($id, $currentRow, $prevRow);
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

        if (($count > 1) && ($id > 0)) {
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
                  return $this->setPosition($id, $currentRow, $nextRow);
                } else return false;
            } else return false;
        } else return false;
    }

    protected function setPosition( int $id, $currentRow, $nextOrPrevRow) : bool
    {
        $fields1 = [
            'position' => $nextOrPrevRow['position']
        ];

        $fields2 = [
            'position' => $currentRow['position']
        ];

        $this->updateRow($currentRow['id'], $fields1, false);

        if($this->errorStatus->getErrorStatus('updateRow')){
            return false;
        }

        $this->updateRow($nextOrPrevRow['id'], $fields2, false);

        if($this->errorStatus->getErrorStatus('updateRow')){
            return false;
        }
        return true;
    }

    public function checkFileAndUpload( string $fieldName, string $path){
        return ImageUpload::Upload($fieldName, $path);
    }

    public function checkFileAndUnlink(array $data, string $fieldName, string $path): object
    {
        $ok = false;
        $file = '';
        $message = '';
        $this->errorStatus->setErrorStatus('no_file');
        $this->errorStatus->setErrorStatus('no_column_record');
        $data[$fieldName] = trim($data[$fieldName]);
        if (isset($data[$fieldName]) && $data[$fieldName] !== '') {
            $file = $path . $data[$fieldName];
            if (file_exists($file)) {
                unlink($file);
                $ok = true;
            } else {
                $this->errorStatus->setErrorStatus('no_file', true);
                return Result::setResult('no_file', 'Не найден файл изображения!', '');
            }
            $this->errorStatus->setErrorStatus('no_column_record', true);
        } else return Result::setResult('error', 'Не найдено название изображения в колонке записи в б.д.!', '');

        if($ok) {
            $message = 'Изображение: ' . $data[$fieldName] . ' удалено успешно!<br/>';
        }
        return Result::setResult('ok', $message, '');
    }

    public function getFiles($path){
        $files = scandir($path);
        $files = array_diff($files, ['..', '.']);
        return $files;
    }

    public function unlinkAllUnusedImages( string $dbTable, string $fieldName, string $path){

        $countAllRows = $this->countAllRowsFromCustomTable($dbTable,false);
        $rows = $this->getAllRowsFromCustomTable($dbTable,false, false);

        $files = $this->getFiles($path);
        if(!$files){
            return Result::setResult('error', 'Ошибка!', '');
        }
        $fileNames = array_column($rows, $fieldName);
        if(($countAllRows > 0)&&(count($files) > 0)) {
            foreach ($files as $file) {
                if (!in_array($file, $fileNames)) {
                    $file = $path . $file;
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }

        if(($countAllRows == 0)&&(count($files) > 0)) {
            foreach ($files as $file) {
                $file = $path . $file;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
        return Result::setResult('ok', 'Процедура проведена!', '');
    }
}