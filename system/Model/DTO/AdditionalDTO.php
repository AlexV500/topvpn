<?php


class AdditionalDTO
{
    private string $additionalValue = '';
    private string $additionalName;
    private object $additionalModel;
    private array $atts;

    public function __construct($additionalName, $additionalModel, $atts)
    {
        $this->additionalName = $additionalName;
        $this->additionalModel = $additionalModel;
        $this->atts = $atts;
        $this->setAdditionalValue();
    }

    public function setAdditionalValue() : object{
        if(isset($this->atts[$this->additionalName])) {
            $this->additionalValue = $this->atts[$this->additionalName];
        } return $this;
    }

    public function getAdditionalValue() : string{
        return $this->additionalValue;
    }

    public function getAdditionalName() : string{
        return $this->additionalName;
    }

    public function getAdditionalModel() : object{
        return $this->additionalModel;
    }


}