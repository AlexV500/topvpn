<?php

require_once V_CORE_LIB . 'Admin/AdminActions/AdminActions.php';

abstract class AdminPostAction extends AdminActions{

    protected int $id;
    protected array $formFills;
    protected array $postData;
    protected string $deleteName;
    protected string $resultStatus = 'waiting';

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

    protected function setPostData()
    {
        $formFill = [];
        foreach ($this->getFormFills() as $key => $value) {
            if (isset($_POST[$key])) {
//                if ($key == 'position') {
//                    $this->postData['position'] = $this->getModel()->getMaxPosition() + 1;
//                    continue;
//                }
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