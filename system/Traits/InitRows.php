<?php

require_once V_CORE_LIB . 'DTO/RelationDTO.php';
require_once V_CORE_LIB . 'DTO/Collection.php';


trait InitRows{

    protected int $rowsCount;
    protected array $rowsData;
    protected bool $relationTrigger = false;
    protected array $relationDTO;
    protected array $relationValue;

    public function initRowsCount($activeMode) : object{
        $this->rowsCount = $this->getModel()->countAllRows($activeMode);
        return $this;
    }

    public function initRowsData($activeMode, bool $paginationMode = true, bool $limitMode = false) : object{

        if($this->relationTrigger){

            $relationValue = $this->getRelationDTO()->getRelationValue();
            $relationColumnName = $this->getRelationDTO()->getRelationColumnName();
            $row = $this->getRelationDTO()->getRelationModel()->getRowsByColumnName($relationColumnName, $relationValue);

            echo '<pre>';
            print_r($row);
            echo '</pre>';
            $this->rowsData = $this->getModel()->getRowsByRelId($row['id'], $paginationMode, $limitMode);
        }
        else {
            $this->rowsData = $this->getModel()->getAllRows($activeMode, $paginationMode, $limitMode);
        }

        return $this;
    }

    public function initRelationParam($relationName, $relationModel, $relationColumnName) : object{

        $relationValue = $this->getAtts()[$relationName];
        if(isset($relationName)&&($relationName !== '')){
            $this->relationDTO[$relationName] = new RelationDTO($relationName, $relationModel, $relationColumnName);
        }


        $this->relationValue[$relationType] = $this->getRelationDTO($relationType)->getRelationValue();
        if($this->relationValue[$relationType] !== ''){
            $this->relationTrigger = true;
        }
        return $this;
    }

    public function getRowsCount() : int{
        return $this->rowsCount;
    }

    public function getRowsData(){
        return $this->rowsData;
    }

    protected function getRelationDTO($relationType){
        return $this->relationDTO[$relationType];
    }


}