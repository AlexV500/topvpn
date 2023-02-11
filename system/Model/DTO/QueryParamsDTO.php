<?php


class QueryParamsDTO{

    private string $orderSql;
    private string $paginatSql;
    private string $multiLangMode;

    public function __construct($orderSql, $paginatSql, $multiLangMode)
    {
        $this->orderSql = $orderSql;
        $this->paginatSql = $paginatSql;
        $this->multiLangMode = $multiLangMode;
    }


    public function getOrderSql() : string{
        return $this->orderSql;
    }

    public function getPaginatSql() : string{
        return $this->paginatSql;
    }

    public function getMultiLangMode() : string{
        return $this->multiLangMode;
    }

}