<?php

class ErrorStatus{

    public array $errorStatus;

    public function setErrorStatus( string $type, bool $status = false) : object
    {
        $this->errorStatus[$type] = $status;
        return $this;
    }

    public function getErrorStatus(string $type){

        return $this->errorStatus[$type];
    }
}