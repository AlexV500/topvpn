<?php


class ArrayExtractor{

    public $array = [];

    public function __construct(array $array = []){
        $this->array = $array;
    }

    public function extractKeys(bool $unique = true) : array {

        $keys = [];

        if(count($this->array) == 0){
            return $keys;
        }

        foreach ($this->array as $key => $value) {
            $keys[] = $key;
        }
        if($unique){
            return array_unique($keys);
        }
        return $keys;
    }
}