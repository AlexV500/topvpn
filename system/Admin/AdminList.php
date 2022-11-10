<?php

require_once V_CORE_LIB . 'Admin/AdminActions/AdminActions.php';
require_once V_CORE_LIB . 'Utils/PaginationConfig.php';
require_once V_CORE_LIB . 'Traits/InitRows.php';
require_once V_CORE_LIB . 'Traits/InitOrder.php';
require_once V_CORE_LIB . 'Traits/InitPagination.php';

abstract class AdminList extends AdminActions {
    use InitRows, InitOrder, InitPagination;

    protected array $columnDisplayNames;
    protected bool $activeMode = false;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);

        $this->setCurrentURL();
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

    public function countAllRowsFromCustomTable($fieldName){

        $countAllRows = $this->getModel()->countAllRowsFromCustomTable($this->dbTable, false);
        $rows = $this->getModel()->getAllRowsFromCustomTable($this->dbTable, false, false);
        if($countAllRows > 0){
            $fileNames = array_column($rows, $fieldName);
            return count(array_diff($fileNames, array('')));
        } else return 'В Б.Д. пока нету записей!';
    }

    public function countFiles(){

        $path = $this->getLogoPath();
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

    protected function unlinkAllUnusedImagesPostHandler( string $fieldName){

        if ( isset( $_POST['deleteLostImages'] )) {
            $path = $this->getLogoPath();
            $result = $this->getModel()->UnlinkAllUnusedImages($this->dbTable, $fieldName, $path);
            $this->setResultMessages('TopVPNAdminList',$result->getResultStatus(), $result->getResultMessage());
        } return $this;
    }

}