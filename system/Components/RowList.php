<?php


class RowList
{
    public function initRowsCount($activeMode) : object{
        $this->rowsListObject->initCountRowsData($this->model, $activeMode);
        $this->rowsCount = $this->rowsListObject->getRowsCount();
        return $this;
    }

    public function initRowsData($activeMode, $paginationMode = true, $limitMode = false) : object{
        $this->rowsListObject->initRowsData($this->model, $activeMode, $paginationMode, $limitMode);
        $this->rowsData = $this->rowsListObject->getRowsData();
        return $this;
    }

    public function getRowsData(){
        return $this->rowsData;
    }

    public function getRowsCount() : int{
        return $this->rowsCount;
    }
}