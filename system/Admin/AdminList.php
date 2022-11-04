<?php

require_once V_CORE_LIB . 'Admin/AdminActions/AdminActions.php';
require_once V_CORE_LIB . 'Utils/PaginationConfig.php';

abstract class AdminList extends AdminActions {

    protected int $paginationCount;
    protected int $rowsCount;
    protected array $rowsData;
    protected int $offset;
    protected int $paged;
    protected bool $paginate;
    protected array $columnDisplayNames;
    protected bool $activeMode = false;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);

        $this->setCurrentURL();
    }

    public function initRowsCount($activeMode) : object{
        $this->rowsCount = $this->getModel()->countAllRows($activeMode);
        return $this;
    }

    public function initRowsData($activeMode) : object{
        $this->rowsData = $this->getModel()->getAllRows($activeMode);
        return $this;
    }

    public function getRowsCount() : int{
        return $this->rowsCount;
    }

    public function getRowsData(){
        return $this->rowsData;
    }

    public function setPaginationCount($count = 20) : object{
        $this->paginationCount = $count;
        return $this;
    }

    public function getPaginationCount() : int{
        return $this->paginationCount;
    }

    protected function getOffset() : int{
        return $this->offset;
    }

    protected function getPaged(){
        return $this->paged;
    }

    protected function getPaginate() : bool{
        return $this->paginate;
    }

    protected function initPaginationConfig(){
        $paginationConfig = new PaginationConfig($this->getPaginationCount(), $this->getRowsCount());
        $paginationConfig->calculate();
        $this->offset = $paginationConfig->getOffset();
        $this->paged = $paginationConfig->getPaged();
        $this->paginate = $paginationConfig->getPaginate();
    }

    protected function setModelPaginationConfig(){
     //   $this->getModel()->setRowCount($this->getRowsCount());
        $this->getModel()->setOffset($this->getOffset());
    }

    protected function setColumnDisplayNames(array $columnDisplayNames) : object{
        $this->columnDisplayNames = $columnDisplayNames;
        return $this;
    }

    protected function getColumnDisplayNames(){
        return $this->columnDisplayNames;
    }

    public function setActiveMode( bool $mode) : object{
        $this->activeMode = $mode;
        return $this;
    }

    protected function checkPositionAction(){

        $set = '';
        $id = '';

        if ( isset( $_GET['position_set'] )){
            $set = $_GET['position_set'];
        }
        if ( isset( $_GET['id'] )){
            $id = $_GET['id'];
        }

        if($set == 'up'){
            (new $this->model)->positionUp($id);
        }
        if($set == 'down'){
            (new $this->model)->positionDown($id);
        }
    }


}