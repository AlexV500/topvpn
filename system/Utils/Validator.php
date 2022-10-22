<?php


class Validator
{
    private $errors = [];
    const OK = 'ok';
    const ERROR = 'error';
    const VALUE_IS_NOT_CORRECT_LENGHT = 'Значение имеет не корректную длинну';
    const VALUE_IS_EMPTY = 'Поле не должно быть пустым';
    const EMAIL_IS_NOT_VALID = 'Email адресс не корректен';
    const DATETIME_IS_NOT_VALID = 'Не корректен формат даты-времени';
    const VALUE_IS_NOT_INTEGER = 'Значение должно быть целым числом';

    public function checkLength($value = "", $min, $max, $field, $emptycheck = false) {

        if ($this->emptyCheck($emptycheck, $value)){
            $this->errors[][$field] = self::VALUE_IS_EMPTY;
            return $this;
        }

        $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
        if($result){
            $this->errors[][$field] = self::VALUE_IS_NOT_CORRECT_LENGHT;
        }
        return $this;
    }

    public function isEmail($email, $field = 'email', $emptycheck = false){

        if ($this->emptyCheck($emptycheck, $email)){
            $this->errors[][$field] = self::VALUE_IS_EMPTY;
            return $this;
        }

        if ( is_email( $email ) ) {
            return $this;
        } else {
            $this->errors[][$field] = self::EMAIL_IS_NOT_VALID;
            return $this;
        }
    }

    public function isInteger($value, $field){
        if (!is_int($value)) {
            $this->errors[][$field] = self::VALUE_IS_NOT_INTEGER;
            return $this;
        }
        return $this;
    }

    public function validateDateTime($date, $format = 'Y-m-d H:i:s', $field)
    {
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date){
            return $this;
        } else {
            $this->errors[][$field] = self::DATETIME_IS_NOT_VALID;
            return $this;
        }
    }

    private function emptyCheck($emptycheck, $value){
        if($emptycheck){
            if (!isset($value) || trim($value) == ''){
                return true;
            }
        } return false;
    }

    public function getResultMessage(){
        $output = '';
        if (count($this->errors) > 0){
            foreach($this->errors as $error){
                $output .= $error.'<br/>';
            }
        }
        return 'Ошибка валидации<br/>' .$output;
    }

    public function getResultStatus(){
        if (count($this->errors) == 0){
            return self::OK;
        } else {
            return self::ERROR;
        }
    }
}