<?php


class Validator
{
    private array $errors = [];
    const OK = 'ok';
    const ERROR = 'error';
    const VALUE_IS_NOT_CORRECT_LENGHT = 'Значение имеет не корректную длинну';
    const VALUE_IS_EMPTY = 'Поле не должно быть пустым';
    const EMAIL_IS_NOT_VALID = 'Email адресс не корректен';
    const DATETIME_IS_NOT_VALID = 'Не корректен формат даты-времени';
    const VALUE_IS_NOT_INTEGER = 'Значение должно быть целым числом';

    public function checkLength($value = "", $min, $max, string $field, string $fieldName, $emptycheck = false) : object {

        if ($this->emptyCheck($emptycheck, $value)){
            $this->errors[][$field] = self::VALUE_IS_EMPTY . ' (Поле: '.$fieldName.')';
            return $this;
        }

        $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
        if($result){
            $this->errors[][$field] = self::VALUE_IS_NOT_CORRECT_LENGHT. ' (Поле: '.$fieldName.')';
        }
        return $this;
    }

    public function isEmail($email, $field = 'email', $emptycheck = false) : object{

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

    public function isInteger($value, $field) : object{
        if (!is_int($value)) {
            $this->errors[][$field] = self::VALUE_IS_NOT_INTEGER;
            return $this;
        }
        return $this;
    }

    public function validateDateTime($date, $format = 'Y-m-d H:i:s', $field) : object
    {
        $d = DateTime::createFromFormat($format, $date);
        if($d && $d->format($format) == $date){
            return $this;
        } else {
            $this->errors[][$field] = self::DATETIME_IS_NOT_VALID;
            return $this;
        }
    }

    private function emptyCheck($emptycheck, $value) : bool{
        if($emptycheck){
            if (!isset($value) || trim($value) == ''){
                return true;
            }
        } return false;
    }

    public function getResultMessage() : string {
        $output = '';
        if (count($this->errors) > 0){
            foreach($this->errors as $errorArr){
                foreach($errorArr as $field => $error) {
                    $output .= $error .'<br/>';
                }
            }
        }
        return 'Ошибка валидации<br/>' .$output;
    }

    public function getResultStatus() : string {
        if (count($this->errors) == 0){
            return self::OK;
        } else {
            return self::ERROR;
        }
    }
}