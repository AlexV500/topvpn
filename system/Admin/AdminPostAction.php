<?php


abstract class AdminPostAction extends AdminActions{

    protected array $formFills;

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
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

}