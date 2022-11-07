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


}