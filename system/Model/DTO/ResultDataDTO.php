<?php


class ResultDataDTO{

    private array $rowsData;
    private array $additionalResultData = [];

    public function __construct($rowsData)
    {
        $this->rowsData = $rowsData;
    }

    public function getRowsData() : array{
        return $this->rowsData;
    }

    public function setAdditionalResultData($additionalResultData, $type) : object{
        $this->additionalResultData[$type] = $additionalResultData;
        return $this;
    }

    public function getAdditionalResultData() : array{
        return $this->additionalResultData;
    }

}