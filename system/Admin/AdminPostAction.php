<?php

require_once V_CORE_LIB . 'Admin/AdminActions/AdminActions.php';

abstract class AdminPostAction extends AdminActions{


    protected array $formFills;
    protected array $postData;
    protected string $deleteName;
    protected string $resultStatus = 'waiting';

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    protected function setPostData()
    {
        $formFill = [];
        foreach ($this->getFormFills() as $key => $value) {
            if ($key == 'position') {
                //     $this->postData['position'] = $this->getModel()->getMaxPosition() + 1;
                $this->postData['position'] = '';
                continue;
            }
            if (isset($_POST[$key])) {
                $this->postData[$key] = $_POST[$key];
                $formFill[$key] = $_POST[$key];
            }
        }
        $this->setFormFills($formFill);
    }

    protected function setFormFills(array $formfills) : object{

        $this->formFills = $formfills;
        return $this;
    }

    protected function getFormFills() : array{

        return $this->formFills;
    }

    protected function setResultStatus(string $resultStatus) : object{

        $this->resultStatus = $resultStatus;
        return $this;
    }

    protected function getResultStatus() : string{

        return $this->resultStatus;
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