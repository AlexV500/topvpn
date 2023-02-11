<?php
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_CORE_LIB . 'Model/DTO/RelationDTO.php';

class RowsListObject{

    protected int $rowsCount;
    protected array $rowsData;
    protected array $relationNames;
    protected object $model;
    protected string $dbTable;
    protected array $atts;
    protected object $relModelCollection;

    public function __construct($atts = []){
        $this->atts = $atts;
        $this->relModelCollection = new Collection();
    }

    public function initRowsData($model, $activeMode, bool $paginationMode = true, bool $limitMode = false) : object{
//        echo '<pre>';
//        print_r($this->relModelCollection);
//        echo '</pre>';

        if(!$this->relModelCollection->isEmpty()){
            foreach ($this->relationNames as $relName){
                if(array_key_exists($relName, $this->atts)){
                    $relationDTO = $this->getItemFromCollection($relName);
                    if($this->atts[$relName] == $relationDTO->getRelationValue()){
                        $row = $relationDTO->getRelationModel()->getRowsByColumnName($relationDTO->getRelationColumnName(), $relationDTO->getRelationValue());
//                        echo '<pre>';
//                        print_r($row);
//                        echo '</pre>';
                        $this->rowsData = $model->getRowsByRelId($relName, $row[0]['id'], $paginationMode, $limitMode);
                    } else {
                        $this->rowsData = $model->getAllRows($activeMode, $paginationMode, $limitMode);
                    }
                } else {
                    $this->rowsData = $model->getAllRows($activeMode, $paginationMode, $limitMode);
                }
            }

        } else {
            $this->rowsData = $model->getAllRows($activeMode, $paginationMode, $limitMode);
        }
//        echo '<pre>';
//                        print_r($this->rowsData);
//                        echo '</pre>';
        return $this;
    }

    public function initCountRowsData($model, $activeMode) : object{

        if(!$this->relModelCollection->isEmpty()){
            foreach ($this->relationNames as $relName){
                if(array_key_exists($relName, $this->atts)){
                    $relationDTO = $this->getItemFromCollection($relName);
                    if($this->atts[$relName] == $relationDTO->getRelationValue()){
                        $row = $relationDTO->getRelationModel()->getRowsByColumnName($relationDTO->getRelationColumnName(), $relationDTO->getRelationValue());
//                        echo '<pre>';
//                        print_r($row);
//                        echo '</pre>';
                        $this->rowsCount = $model->countAllRowsByRelId($relName, $row[0]['id']);

                    } else {
                        $this->rowsCount = $model->countAllRows($activeMode);
                    }
                } else {
                    $this->rowsCount = $model->countAllRows($activeMode);
                }
            }

        } else {
            $this->rowsCount = $model->countAllRows($activeMode);
        }

        return $this;
    }

    public function addRelationParam($relationName, $relationModel, $relationColumnName) : object{

        if(isset($relationName)&&($relationName !== '')){
            $this->relationNames[] = $relationName;
            $this->addItemToCollection(new RelationDTO($relationName, $relationModel, $relationColumnName, $this->atts), $relationName);
        }

        return $this;
    }

    public function addItemToCollection( object $obj, string $key){
        $this->relModelCollection->addItem($obj, $key);
        return $this;
    }

    public function getItemFromCollection(string $key){
        try {
            return $this->relModelCollection->getItem($key);
        } catch (Exception $key) {
            print "The collection doesn't contain anything called '$key'";
        }
    }

    public function getModel(){
        return $this->model;
    }

    public function getDbTable(){
        return $this->dbTable;
    }

    public function getRowsCount() : int{
        return $this->rowsCount;
    }

    public function getRowsData() : array{
        return $this->rowsData;
    }

}