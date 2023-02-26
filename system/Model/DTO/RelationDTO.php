<?php


class RelationDTO
{
    private string $relationValue = '';
    private string $relationName;
    private object $relationModel;
    private string $relationColumnName;

    public function __construct($relationName, $relationModel, $relationColumnName, $atts)
    {
        $this->relationName = $relationName;
        $this->relationModel = $relationModel;
        $this->relationColumnName = $relationColumnName;
        $this->atts = $atts;
        $this->setRelationValue();
    }

    public function setRelationValue() : object{
        if(isset($this->atts[$this->relationName])) {
            $this->relationValue = $this->atts[$this->relationName];
        } return $this;
    }

    public function getRelationValue() : string{
        return $this->relationValue;
    }

    public function getRelationName() : string{
        return $this->relationName;
    }

    public function getRelationModel() : object{
        return $this->relationModel;
    }

    public function getRelationColumnName() : string{
        return $this->relationColumnName;
    }

}