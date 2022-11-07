<?php

trait initOrder{

    public function setOrderColumn( string $column) : object{
//        $this->changeModelConf($this->getModel()->setOrderColumnn($column));
//        return $this;

        $this->model->setOrderColumn($column);
        return $this;
    }

    public function setOrderDirection( string $direction) : object{
//        $this->changeModelConf($this->getModel()->setOrderDirection($direction));
//        return $this;
        $this->model->setOrderDirection($direction);
        return $this;
    }
}
