<?php

require_once V_CORE_LIB . 'Admin/AdminActions/AdminActions.php';
require_once V_CORE_LIB . 'Utils/PaginationConfig.php';
require_once V_CORE_LIB . 'Traits/InitOrder.php';
require_once V_CORE_LIB . 'Traits/InitPagination.php';
require_once V_CORE_LIB . 'Model/RowsListObject.php';

abstract class AdminList extends AdminActions {
    use InitOrder, InitPagination;

    protected array $columnDisplayNames;
    protected bool $activeMode = false;
    protected object $rowsListObject;
    protected int $rowsCount;
    protected array $rowsData;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
 //       $this->rowsListObject = new RowsListObject($this->model, $this->dbTable);
//        $this->setCurrentURL();
    }

    public function initRows($atts) : object{
        $this->rowsListObject = new RowsListObject($atts);
        return $this;
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

    public function countAllRowsFromCustomTable($fieldName){

        $countAllRows = $this->getModel()->countAllRowsFromCustomTable($this->dbTable, false);
        $rows = $this->getModel()->getAllRowsFromCustomTable($this->dbTable, false, false);
        if($countAllRows > 0){
            $fileNames = array_column($rows, $fieldName);
            return count(array_diff($fileNames, array('')));
        } else return 'В Б.Д. пока нету записей!';
    }

    public function countFiles($path){

        $files = $this->getModel()->getFiles($path);
        if(!$files){
            return 'Ошибка!';
        } else {
            return count($files);
        }
    }

    protected function checkPositionAction() : bool{

        $set = '';
        $id = 0;
        $positionUp = false;
        $positionDown = false;

        if ( isset( $_GET['position_set'] )){
            $set = $_GET['position_set'];
        }
        if ( isset( $_GET['item_id'] )){
             $id = (int) $_GET['item_id'];
        }

        if($set == 'up'){
            $positionUp = $this->getModel()->positionUp($id);
            if(!$positionUp){
                return false;
            }
        }
        if($set == 'down'){
            $positionDown = $this->getModel()->positionDown($id);
            if(!$positionDown){
                return false;
            }
        }
        return true;
    }

    protected function unlinkAllUnusedImagesPostHandler( string $fieldName, string $path){

        if ( isset( $_POST['deleteLostImages'] )) {
            $result = $this->getModel()->unlinkAllUnusedImages($this->dbTable, $fieldName, $path);
            $this->setResultMessages('TopVPNAdminList',$result->getResultStatus(), $result->getResultMessage());
        } return $this;
    }

}