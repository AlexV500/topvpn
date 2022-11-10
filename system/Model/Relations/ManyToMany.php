<?php

require_once V_CORE_LIB . '/Model/DTO/DBRelationTableDTO.php';
require_once V_CORE_LIB . 'Utils/Result.php';
require_once V_CORE_LIB . 'Utils/ResultMessages.php';

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
            if ($this->_deleteManyToOne($thisId) !== 'ok'){
                return false;
            }
        }
//
        if(!$this->addThisRowThatRowLinks($thisId, $manyItemsToThisOne)){
            return false;
        }

        return true;
    }

    public static function deleteManyToOne($keyManyToManyFields, $thisId)
    {
        $returnMessages = [];
        $resultMessages = new ResultMessages();
        $count = count($keyManyToManyFields);
        $status = 'ok';
        if ($count > 0) {
            foreach ($keyManyToManyFields as $keyManyToOneField) {
                $DBRelationTableDTO = new DBRelationTableDTO($keyManyToOneField['pivot_table_name'], $keyManyToOneField['this_key_name'], $keyManyToOneField['that_key_name']);
                $manyToOne = new self($DBRelationTableDTO);
                $countLinksBefore = $manyToOne->countThatManyToThisOneLinks($thisId);
                $deleted = $manyToOne->_deleteManyToOne($thisId);
                $countLinksAfter = $manyToOne->countThatManyToThisOneLinks($thisId);
                $returnMessages[] =[$keyManyToOneField['pivot_table_name'].': Кол. ссылок до удаления: '.$countLinksBefore.', Кол. ссылок после удаления: '.$countLinksAfter, $deleted];
            }
        }
        if(count($returnMessages) > 0) {
            foreach ($returnMessages as $key => $value) {
                if (($value[1] == 'ok')or($value[1] == 'no_links')) {
                    $resultMessages->addResultMessage('ManyToMany', 'ok', $value[0]);
                }
                else {
                    $status = 'error';
                    $resultMessages->addResultMessage('ManyToMany', 'error', $value[0]);
                }
            }
            $result = Result::setResult($status, $resultMessages->getResultMessages('ManyToMany'), '');
        }
        else {
            $resultMessages->addResultMessage('ManyToMany', 'error', 'Ошибка');
            $result = Result::setResult('error', 'Ошибка', '');
        }
        return $result;
    }

    private function _deleteManyToOne($thisId) : string
    {
     //   $this->setErrorStatus('_deleteManyToOne');
        $thatManyToThisOneLinks = $this->getThatManyToThisOneLinks($thisId);
        if($this->countThatManyToThisOneLinks($thisId) == 0){
            return 'no_links';
        }
        $deleted = $this->removeThisOneThatManyLinks($thisId);
        if($deleted == ''){
            return 'ok';
        } else {
       //     $this->setErrorStatus('_deleteManyToOne', 'Не удалена ссылка '. $deleted);
            return $deleted;
        }
    }

    //------------------------------------------------------


    private function countThatManyToThisOneLinks($thisId) : int{

        $sql = "SELECT COUNT(*) FROM `{$this->pivotTableName}` WHERE $this->thisPkeyRowName = $thisId ORDER BY id";
        return $this->wpdb->get_var($sql);
    }

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