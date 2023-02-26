<?php
require_once V_CORE_LIB . 'Utils/Collection.php';
require_once V_CORE_LIB . 'Model/DTO/RelationDTO.php';
require_once V_CORE_LIB . 'Model/DTO/AdditionalDTO.php';

class RowsListObject{

    protected int $rowsCount;
    protected array $rowsData;
    protected array $additionalData = [];
    protected array $relationNames;
    protected array $additionalNames;
    protected object $model;
    protected string $dbTable;
    protected array $atts;
    protected object $relModelCollection;
    protected object $addModelCollection;

    public function __construct($atts = []){
        $this->atts = $atts;
        $this->relModelCollection = new Collection();
        $this->addModelCollection = new Collection();
    }


    public function initRowsData($model, bool $activeMode, bool $paginationMode = true, bool $limitMode = false): self
    {
        if ($this->hasRelationModels()) {
            foreach ($this->getRelationNames() as $relationName) {
                if ($this->hasAttribute($relationName)) {
                    $relationDto = $this->getItemFromRelCollection($relationName);

                    if ($this->getAttribute($relationName) == $relationDto->getRelationValue()) {
                        $row = $relationDto->getRelationModel()->getRowsByColumnName($relationDto->getRelationColumnName(), $relationDto->getRelationValue());
                        $this->rowsData = $model->getRowsByRelId($relationName, $row[0]['id'], $paginationMode, $limitMode);
                        if ($this->hasAdditionalModels()) {
                            foreach ($this->getAdditionalNames() as $additionalName) {
                                if ($relationName == $additionalName) {
                                    $additionalDto = $this->getItemFromAdditionalCollection($additionalName);

                                    if ($this->getAttribute($additionalName) == $additionalDto->getAdditionalValue()) {
                                        $this->additionalData = $additionalDto->getAdditionalModel()->getAdditionalRows($this->getAttribute($additionalName));
                                        return $this;
                                    }
                                }
                            }
                        }
                        return $this;
                    }
                }
            }
        }

        $this->rowsData = $model->getAllRows($activeMode, $paginationMode, $limitMode);
        return $this;
    }

    public function initCountRowsData($model, bool $activeMode): self
    {
        if ($this->hasRelationModels()) {
            foreach ($this->getRelationNames() as $relationName) {
                $relationDto = $this->getItemFromRelCollection($relationName);
                if ($this->hasAttribute($relationName) && $this->getAttribute($relationName) == $relationDto->getRelationValue()) {
                    $row = $relationDto->getRelationModel()->getRowsByColumnName($relationDto->getRelationColumnName(), $relationDto->getRelationValue());
                    $this->rowsCount = $model->countAllRowsByRelId($relationName, $row[0]['id']);
                    return $this;
                }
            }
        }

        $this->rowsCount = $model->countAllRows($activeMode);
        return $this;
    }

    public function addRelationParam($relationName, $relationModel, $relationColumnName) : object{

        if (!empty($relationName)) {
            $this->relationNames[] = $relationName;
            $this->addItemToRelCollection(new RelationDTO($relationName, $relationModel, $relationColumnName, $this->atts), $relationName);
        }
        return $this;
    }

    public function addAdditionalParam($additionalName, $additionalModel) : object{

        if (!empty($additionalName)) {
            $this->additionalNames[] = $additionalName;
            $this->addItemToAdditionalCollection(new AdditionalDTO($additionalName, $additionalModel, $this->atts), $additionalName);
        }
        return $this;
    }

    private function addItemToRelCollection( object $obj, string $key){
        $this->relModelCollection->addItem($obj, $key);
        return $this;
    }

    private function getItemFromRelCollection(string $key){
        try {
            return $this->relModelCollection->getItem($key);
        } catch (Exception $key) {
            print "The collection doesn't contain anything called '$key'";
        }
    }

    private function addItemToAdditionalCollection( object $obj, string $key){
        $this->addModelCollection->addItem($obj, $key);
        return $this;
    }

    private function getItemFromAdditionalCollection(string $key){
        try {
            return $this->addModelCollection->getItem($key);
        } catch (Exception $key) {
            print "The collection doesn't contain anything called '$key'";
        }
    }

    private function hasRelationModels(): bool
    {
        return !$this->relModelCollection->isEmpty();
    }

    private function hasAdditionalModels(): bool
    {
        return !$this->addModelCollection->isEmpty();
    }

    private function getAdditionalNames(): array
    {
        return $this->additionalNames;
    }

    private function getRelationNames(): array
    {
        return $this->relationNames;
    }

    private function hasAttribute(string $attributeName): bool
    {
        return array_key_exists($attributeName, $this->atts);
    }

    private function getAttribute(string $attributeName)
    {
        return $this->atts[$attributeName];
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
    public function getAdditionalData() : array{
        return $this->additionalData;
    }

}