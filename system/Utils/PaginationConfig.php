<?php


class PaginationConfig{

    private int $setValueOfCount;
    private int $count;
    private int $offset;
    private int $paged;
    private bool $paginate;

    public function __construct($setValueOfCount, $count){

        $this->setValueOfCount = $setValueOfCount;
        $this->count = $count;
    }

    public function calculate() : object{

        $this->offset = isset( $_GET['offset'] ) ? intval( $_GET['offset'] ) : 0;
        if ( $this->offset < 0 ) {
            $this->offset = 0;
        }
        $this->paged = isset( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : 0;
        if ( $this->paged < 0 ) {
            $this->paged = 0;
        }

        if ( $this->count > $this->setValueOfCount ) {
            $this->paginate = true;
        } else {
            $this->paginate = false;
        }
        $pages = ceil ( $this->count / $this->setValueOfCount );
        if ( $this->paged > $pages ) {
            $this->paged = $pages;
        }
        if ( $this->paged != 0 ) {
            $this->offset = ( $this->paged - 1 ) * $this->setValueOfCount;
        }
        return $this;
    }

    public function getOffset(){
        return $this->offset;
    }

    public function getPaged(){
        return $this->paged;
    }

    public function getPaginate(){
        return $this->paginate;
    }
}