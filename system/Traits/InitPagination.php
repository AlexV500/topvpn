<?php

trait initPagination{

    protected int $paginationCount;
    protected int $offset;
    protected int $paged;
    protected bool $paginate;

    public function setPaginationCount($count = 10) : object{
        $this->paginationCount = $count;
        return $this;
    }

    public function getPaginationCount() : int{
        return $this->paginationCount;
    }

    protected function getOffset() : int{
        return $this->offset;
    }

    protected function getPaged(){
        return $this->paged;
    }

    protected function getPaginate() : bool{
        return $this->paginate;
    }

    protected function initPaginationConfig(){
        $paginationConfig = new PaginationConfig($this->getPaginationCount(), $this->getRowsCount());
        $paginationConfig->calculate();
        $this->offset = $paginationConfig->getOffset();
        $this->paged = $paginationConfig->getPaged();
        $this->paginate = $paginationConfig->getPaginate();
        $this->setModelPaginationConfig();
    }

    protected function setModelPaginationConfig() : object{
        //   $this->getModel()->setRowCount($this->getRowsCount());
        //   $this->changeModelConf($this->getModel()->setOffset($this->getOffset()));
        $this->model->setOffset($this->getOffset());
        $this->model->setPaginationCount($this->getPaginationCount());
        return $this;
    }
}