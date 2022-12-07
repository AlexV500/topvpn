<?php

require_once V_CORE_LIB . 'Components/Components.php';
require_once V_CORE_LIB . 'Utils/PaginationConfig.php';
require_once V_CORE_LIB . 'Traits/InitRows.php';
require_once V_CORE_LIB . 'Traits/InitOrder.php';
require_once V_CORE_LIB . 'Traits/InitPagination.php';

abstract class PublicList extends Components{

    use InitRows, InitOrder, InitPagination;

    protected bool $activeMode = true;
    protected string $imgPath = V_CORE_URL .'public/images';

    public function __construct($model, $dbTable)
    {
        parent::__construct($model, $dbTable);
    }

    public function setLimitCount($count = 10) : object{
        $this->model->setLimitCount($count);
        return $this;
    }

    public function switchMultiLangMode($atts){
        $lang = '';
        if(isset($atts['lang'])) {
            $lang = $atts['lang'];
        }
        return $this->getModel()->switchMultiLangMode($lang);
    }

}