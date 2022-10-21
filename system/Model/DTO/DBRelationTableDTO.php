<?php

class DBRelationTableDTO
{
    private string $pivotTable;
    private string $thisPkeyRowName;
    private string $thatPkeyRowName;

    public function __construct($pivotTableName, $thisPkeyRowName, $thatPkeyRowName)
    {
        $this->pivotTable = $pivotTableName;
        $this->thisPkeyRowName = $thisPkeyRowName;
        $this->thatPkeyRowName = $thatPkeyRowName;
    }

    public function getPivotTableName(){
        return $this->pivotTableName;
    }

    public function getThisPkeyRowName(){
        return $this->thisPkeyRowName;
    }

    public function getThatPkeyRowName(){
        return $this->thatPkeyRowName;
    }
}