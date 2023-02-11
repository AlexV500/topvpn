<?php

require_once V_CORE_LIB . 'Components/Components.php';
require_once V_CORE_LIB . 'Utils/PaginationConfig.php';
require_once V_CORE_LIB . 'Traits/InitOrder.php';
require_once V_CORE_LIB . 'Traits/InitPagination.php';
require_once V_CORE_LIB . 'Model/RowsListObject.php';

abstract class PublicList extends Components{

    use InitOrder, InitPagination;

    protected bool $activeMode = true;
    protected string $imgPath = V_CORE_URL .'public/images';
    protected object $rowsListObject;
    protected int $rowsCount;
    protected array $rowsData;

    public function __construct($model, $dbTable, $atts)
    {
        parent::__construct($model, $dbTable, $atts);
    }

    public function initRows() : object{
        $this->rowsListObject = new RowsListObject($this->getAtts());
        return $this;
    }

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

    public function addRelationParam($relationName, $relationModel, $relationColumnName) : object{
        $this->rowsListObject->addRelationParam($relationName, $relationModel, $relationColumnName);
        return $this;
    }

    public function getRowsData(){
        return $this->rowsData;
    }

    public function getRowsCount() : int{
        return $this->rowsCount;
    }

}