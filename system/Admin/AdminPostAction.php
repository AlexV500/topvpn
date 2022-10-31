<?php


abstract class AdminPostAction extends AdminActions{

    protected int $id;
    protected array $formFills;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    protected function setId($id){

        $this->id = (int) $id;
    }

    protected function getId(){

        return $this->id;
    }

    protected function setFormFills(array $formfills){

        $this->formFills = $formfills;
    }

    protected function getFormFills() : array{

        return $this->formFills;
    }

    protected function getFormFill($key) : string{

        return $this->formFills[$key];
    }

    protected function getFormFillArray($key) : array{

        if(is_array($this->formFills[$key])){
            return $this->formFills[$key];
        }
        return [];
    }

}