<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'system/Model/DTO/DBRelationTableDTO.php';

class ManyToMany {

    private string $pivotTableName;
    private string $thisPkeyRowName;
    private string $thatPkeyRowName;

    public function __construct($DBRelationTableDTO){
        $this->pivotTableName = $DBRelationTableDTO->getPivotTableName();
        $this->thisPkeyRowName = $DBRelationTableDTO->getThisPkeyRowName();
        $this->thatPkeyRowName = $DBRelationTableDTO->getThatPkeyRowName();
    }

    public static function addManyToOne($keyManyToManyFields, $recordedRow, $manyItemsToThisOne){
        if(count($keyManyToManyFields) > 0){
            foreach ($keyManyToManyFields as $keyManyToOneField){
                $DBRelationTableDTO = new DBRelationTableDTO($keyManyToOneField['pivot_table_name'], $keyManyToOneField['this_key_name'], $keyManyToOneField['that_key_name']);
                $manyToOne = new self($DBRelationTableDTO);
                $manyToOneData = $manyToOne->_addManyToOne($recordedRow['last_insert_id'], $manyItemsToThisOne[$keyManyToOneField]);
                $recordedRow[$keyManyToOneField] = $manyToOneData;
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
        foreach ($manyItemsToThisOne as $thatId) {
            $thatIdArray[] = $thatId;
            if (!$this->recordToThisOneThatManyLink($thisId, $thatId)) {
                array_pop($thatIdArray);
                $this->removeBackUpThisOneThatManyLinks($thisId, $thatIdArray);
                return false;
            }
        } return true;
    }


    //-----------------EDIT------------------

    public static function editManyToOne($keyManyToManyFields, $recordedRow, $thisId, $manyItemsToThisOnePostData){
        if(count($keyManyToManyFields) > 0){
            foreach ($keyManyToManyFields as $keyManyToOneField){
                $DBRelationTableDTO = new DBRelationTableDTO($keyManyToOneField['pivot_table_name'], $keyManyToOneField['this_key_name'], $keyManyToOneField['that_key_name']);
                $manyToOne = new self($DBRelationTableDTO);
                $manyToOneData = $manyToOne->_editManyToOne($thisId, $manyItemsToThisOnePostData);
                if(count($manyToOneData) > 0){
                    $recordedRow[$keyManyToOneField] = $manyToOneData;
                }
            }
        } return $recordedRow;
    }

    private function _editManyToOne($thisId, $manyItemsToThisOnePostData) : array{
        $returnedData = [];
        if($this->refreshThisRowThatRowLinks($thisId, $manyItemsToThisOnePostData)) {
            $returnedData = $this->getThatManyToThisOneLinks($thisId);
        }
        return $returnedData;
    }

    private function refreshThisRowThatRowLinks($thisId, $manyItemsToThisOnePostData) : bool{

        $thatManyToThisOneLinks = $this->getThatManyToThisOneLinks($thisId);
        $manyItemsToThisOneIds = [];
        $checked = '';

        if(count($thatManyToThisOneLinks) > 0) {

            foreach ((array)$thatManyToThisOneLinks as $thatManyToThisOneLink) {
                if (strtolower($thatManyToThisOneLink[$this->thisPkeyRowName]) == strtolower($thisId)) {
                    array_push($manyItemsToThisOneIds, $thatManyToThisOneLink[$this->thatPkeyRowName]);
                }
            }

            if (empty($manyItemsToThisOnePostData)) {
                return TRUE;
            }
            if (empty(array_diff($manyItemsToThisOnePostData, $manyItemsToThisOneIds))&&(empty(array_diff($manyItemsToThisOneIds, $manyItemsToThisOnePostData)))) {
                return TRUE;
            }
            if (!$this->removeThisOneThatManyLinks($thatManyToThisOneLink[$this->thisPkeyRowName])){
                return false;
            }
        }
//
        if(!$this->addThisRowThatRowLinks($thisId, $manyItemsToThisOnePostData)){
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