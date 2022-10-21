<?php

class Result{
    private $resultStatus;
    private $resultMessage = [];
    private $resultData;

    public function __construct($resultStatus, $resultMessage, $resultData){

        $this->setResultStatus($resultStatus);
        $this->setResultMessage($resultMessage);
        $this->setResultData($resultData);
    }

    public static function setResult($resultStatus, $resultMessage, $resultData){
        return new self($resultStatus, $resultMessage, $resultData);
    }

    public function setResultStatus($resultStatus)
    {
        $this->resultStatus = $resultStatus;
        return $this;
    }

    public function setResultMessage($resultMessage)
    {
        $this->resultMessage[] = $resultMessage;
        return $this;
    }

    public function setResultData($resultData)
    {
        $this->resultData[] = $resultData;
        return $this;
    }

    public function getResultStatus()
    {
        return $this->resultStatus;
    }

    public function getResultMessage()
    {
        return $this->resultMessage;
    }

    public function getResultData()
    {
        return $this->resultData;
    }
}