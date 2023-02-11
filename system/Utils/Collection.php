<?php


class Collection{

    private array $_members = [];

    public function addItem($obj, $key = null) : object{
        if($key) {
//Throw exception if this key is already in use.
            if(isset($this->_members[$key])) {
                throw new Exception("Key \"$key\" already in use!");
            } else {
                $this->_members[$key] = $obj;
            }
        } else {
            $this->_members[] = $obj;
        }
        return $this;
    }

    public function removeItem($key) : object {
        if(isset($this->_members[$key])) {
            unset($this->_members[$key]);
        } else {
            throw new Exception("Invalid key \"$key\"!");
        }
        return $this;
    }

    public function getItem($key) {
        if(isset($this->_members[$key])) {
            return $this->_members[$key];
        } else {
            throw new Exception("Invalid key \"$key\"!");
        }
    }

    public function length() {   return sizeof($this->_members); }

    public function exists($key) {
        return (isset($this->_members[$key]));
    }

    public function isEmpty(): bool
    {
        return $this->_members === [];
    }

    public function getItems() : array{
        return $this->_members;
    }
}