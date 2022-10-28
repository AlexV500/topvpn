<?php


abstract class AdminList extends AdminActions {

    protected int $paginationCount;
    protected int $rowsCount;
    protected array $rowsData;
    protected int $offset;
    protected int $paged;
    protected bool $paginate;
    protected array $columnDisplayNames;


    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }


    public function setRowsCount($activeMode) : object{
        $this->rowsCount = $this->getModel()->countAllRows($activeMode);
        return $this;
    }

    public function setRowsData($activeMode) : object{
        $this->rowsData = $this->getModel()->getAllRows($activeMode);
        return $this;
    }

    public function getRowsCount(){
        return $this->rowsCount;
    }

    public function getRowsData(){
        return $this->rowsData;
    }

    public function setPaginationCount($count = 20) : object{
        $this->paginationCount = $count;
        return $this;
    }

    public function getPaginationCount(){
        return $this->paginationCount;
    }


    protected function getOffset(){
        return $this->offset;
    }

    protected function getPaged(){
        return $this->paged;
    }

    protected function getPaginate(){
        return $this->paginate;
    }

    protected function setPaginationConfig(){
        $paginationConfig = new PaginationConfig($this->getPaginationCount(), $this->getRowCount());
        $this->offset = $paginationConfig->getOffset();
        $this->paged = $paginationConfig->getPaged();
        $this->paginate = $paginationConfig->getPaginate();
    }

    protected function setColumnDisplayNames(array $columnDisplayNames) : object{
        $this->columnDisplayNames = $columnDisplayNames;
        return $this;
    }

    protected function getColumnDisplayNames(){
        return $this->columnDisplayNames;
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