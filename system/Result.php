<?php

class Result{

    private string $resultStatus;
    private string $resultMessage;
    private $resultData;

    public function __construct($resultStatus, $resultMessage, $resultData){

        $this->setResultStatus($resultStatus);
        $this->setResultMessage($resultMessage);
        $this->setResultData($resultData);
    }

    public static function setResult($resultStatus, $resultMessage, $resultData) : object {
        return new self($resultStatus, $resultMessage, $resultData);
    }

    public function setResultStatus($resultStatus) : object
    {
        $this->resultStatus = $resultStatus;
        return $this;
    }

    public function setResultMessage($resultMessage) : object
    {
        $this->resultMessage = $resultMessage;
        return $this;
    }

    public function setResultData($resultData) : object
    {
        $this->resultData = $resultData;
        return $this;
    }

    public function getResultStatus() : string
    {
        return $this->resultStatus;
    }

    public function getResultMessage() : string
    {
        return $this->resultMessage;
    }

    public function getResultData()
    {
        return $this->resultData;
    }
}