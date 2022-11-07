<?php

class DBRelationTableDTO
{
    private string $pivotTable;
    private string $thisPkeyRowName;
    private string $thatPkeyRowName;

    public function __construct($pivotTableName, $thisPkeyRowName, $thatPkeyRowName)
    {
        $this->pivotTableName = $pivotTableName;
        $this->thisPkeyRowName = $thisPkeyRowName;
        $this->thatPkeyRowName = $thatPkeyRowName;
    }

    public function getPivotTableName() : string{
        return $this->pivotTableName;
    }

    public function getThisPkeyRowName() : string{
        return $this->thisPkeyRowName;
    }

    public function getThatPkeyRowName() : string{
        return $this->thatPkeyRowName;
    }
}