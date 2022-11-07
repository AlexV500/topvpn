<?php

require_once V_CORE_LIB .  '/Model/DTO/DBRelationTableDTO.php';

class ManyToMany {

    public $wpdb;
    public string $prefix;
    private string $pivotTableName;
    private string $thisPkeyRowName;
    private string $thatPkeyRowName;

    public function __construct($DBRelationTableDTO){
        global $wpdb;
        $this->wpdb = &$wpdb;
        $this->prefix = $wpdb->prefix;
        $this->pivotTableName = $this->prefix.$DBRelationTableDTO->getPivotTableName();
        $this->thisPkeyRowName = $DBRelationTableDTO->getThisPkeyRowName();
        $this->thatPkeyRowName = $DBRelationTableDTO->getThatPkeyRowName();
    }

    public static function addManyToOne( array $keyManyToManyFields, array $recordedRow, array $manyItemsToThisOnePostData){
        if(count($keyManyToManyFields) > 0){
            foreach ($keyManyToManyFields as $keyManyToOne => $keyManyToOneData){
                $DBRelationTableDTO = new DBRelationTableDTO($keyManyToOneData['pivot_table_name'], $keyManyToOneData['this_key_name'], $keyManyToOneData['that_key_name']);
                $manyToOne = new self($DBRelationTableDTO);
                $manyToOneData = $manyToOne->_addManyToOne($recordedRow['last_insert_id'], $manyItemsToThisOnePostData[$keyManyToOne]);
                $recordedRow[$keyManyToOne] = $manyToOneData;
            }
        } return $recordedRow;
    }

    private function _addManyToOne($lastInsertIdOne, $manyItemsToThisOne) : array
    {
        $returnedData = [];

        if (isset($manyItemsToThisOne) && ($lastInsertIdOne)) {
            if ((is_array($manyItemsToThisOne))&&count($manyItemsToThisOne)>0){
                if($this->addThisRowThatRowLinks($lastInsertIdOne, $manyItemsToThisOne)){
                    $returnedData[] = $this->getThatManyToThisOneLinks($lastInsertIdOne);
                }
            }

        } return $returnedData;
    }

    private function addThisRowThatRowLinks($thisId, $manyItemsToThisOne) : bool
    {
        $thatIdArray = [];
        if(count($manyItemsToThisOne) > 0) {
            foreach ($manyItemsToThisOne as $thatId) {
                $thatIdArray[] = $thatId;
                if (!$this->recordToThisOneThatManyLink($thisId, $thatId)) {
                    array_pop($thatIdArray);
                    $this->removeBackUpThisOneThatManyLinks($thisId, $thatIdArray);
                    return false;
                }
            }
            return true;
        } return false;
    }


    //-----------------EDIT------------------

    public static function editManyToOne(array $keyManyToManyFields, array $recordedRow, int $thisId, array $manyItemsToThisOnePostData)
    {
        if (count($keyManyToManyFields) > 0) {
            foreach ($keyManyToManyFields as $keyManyToOne => $keyManyToOneData) {
                $DBRelationTableDTO = new DBRelationTableDTO($keyManyToOneData['pivot_table_name'], $keyManyToOneData['this_key_name'], $keyManyToOneData['that_key_name']);
                $manyToOne = new self($DBRelationTableDTO);

                $manyToOneData = $manyToOne->_editManyToOne($thisId, $manyItemsToThisOnePostData[$keyManyToOne]);
                $recordedRow[$keyManyToOne] = $manyToOneData;

            }
        }
        return $recordedRow;
    }

    private function _editManyToOne($thisId, $manyItemsToThisOne): array
    {
        $returnedData = [];
        if (is_array($manyItemsToThisOne) && (count($manyItemsToThisOne)) > 0) {
            if ($this->refreshThisRowThatRowLinks($thisId, $manyItemsToThisOne)) {
                $returnedData = $this->getThatManyToThisOneLinks($thisId);
            }
        }
        return $returnedData;
    }

    private function refreshThisRowThatRowLinks($thisId, $manyItemsToThisOne) : bool{

        $thatManyToThisOneLinks = $this->getThatManyToThisOneLinks($thisId);
        $manyItemsToThisOneIds = [];
        $checked = '';

        if(count($thatManyToThisOneLinks) > 0) {

            foreach ((array)$thatManyToThisOneLinks as $thatManyToThisOneLink) {
                if (strtolower($thatManyToThisOneLink[$this->thisPkeyRowName]) == strtolower($thisId)) {
                    array_push($manyItemsToThisOneIds, $thatManyToThisOneLink[$this->thatPkeyRowName]);
                }
            }

            if (empty($manyItemsToThisOne)) {
                return TRUE;
            }
            if (empty(array_diff($manyItemsToThisOne, $manyItemsToThisOneIds))&&(empty(array_diff($manyItemsToThisOneIds, $manyItemsToThisOne)))) {
                return TRUE;
            }
            if (!$this->_deleteManyToOne($thisId)){
                return false;
            }
        }
//
        if(!$this->addThisRowThatRowLinks($thisId, $manyItemsToThisOne)){
            return false;
        }

        return true;
    }

    public static function deleteManyToOne($keyManyToManyFields, $recordedRow, $thisId, $manyItemsToThisOnePostData)
    {
        $count = count($keyManyToManyFields);
        if ($count > 0) {
            foreach ($keyManyToManyFields as $keyManyToOneField) {
                $DBRelationTableDTO = new DBRelationTableDTO($keyManyToOneField['pivot_table_name'], $keyManyToOneField['this_key_name'], $keyManyToOneField['that_key_name']);
                $manyToOne = new self($DBRelationTableDTO);
                $deleted[$keyManyToOneField['pivot_table_name'].':'.$thisId.'=>'.$keyManyToOneField] = $manyToOne->_deleteManyToOne($thisId);
            }
        }
    }

    private function _deleteManyToOne($thisId) : int
    {
        $deleted = $this->removeThisOneThatManyLinks($thisId);
        if($deleted == ''){
            return 'Ok';
        } else {
            return $deleted;
        }
    }

    //------------------------------------------------------


    private function getThatManyToThisOneLinks($thisId) : array{

        $sql = "SELECT * FROM `{$this->pivotTableName}` WHERE $this->thisPkeyRowName = $thisId ORDER BY id";
        return $this->wpdb->get_results($sql, ARRAY_A);
    }

    private function recordToThisOneThatManyLink($thisId, $thatId) : bool{

    //    $sql = "INSERT INTO `{$this->pivotTableName}` ($this->thisPkeyRowName, $this->thatPkeyRowName) VALUES ($thisId, $thatId)";
        $this->wpdb->insert(
            $this->pivotTableName,
            array( $this->thisPkeyRowName => $thisId, $this->thatPkeyRowName => $thatId ),
            array( '%d', '%d' )
        );
        if($this->wpdb->insert_id == 0){
            return false;
        }
        return true;
    }

    private function removeThisOneThatManyLinks($thisId)
    {
    //    $this->wpdb->query("DELETE FROM `{$this->pivotTableName}` WHERE $this->thisPkeyRowName = $thisId");
        $this->wpdb->delete( $this->pivotTableName, [$this->thisPkeyRowName => $thisId]);
        return $this->wpdb->last_error;
    }

    private function removeBackUpThisOneThatManyLinks($thisId, $thatIdArray)
    {
        foreach ($thatIdArray as $thatId) {
            $this->wpdb->delete( $this->pivotTableName, [$this->thisPkeyRowName => $thisId, $this->thatPkeyRowName => $thatId]);
        }

        return $this->wpdb->last_error;
    }
}