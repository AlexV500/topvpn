<?php

trait InitRows{

    protected int $rowsCount;
    protected array $rowsData;


    public function initRowsCount($activeMode) : object{
        $this->rowsCount = $this->getModel()->countAllRows($activeMode);
        return $this;
    }

    public function initRowsData($activeMode, bool $paginationMode = true, bool $limitMode = false) : object{
        $this->rowsData = $this->getModel()->getAllRows($activeMode, $paginationMode, $limitMode);
        return $this;
    }

    public function getRowsCount() : int{
        return $this->rowsCount;
    }

    public function getRowsData(){
        return $this->rowsData;
    }


}