<?php

class ResultMessages{

    protected array $messages;

    public function addResultMessage($type, $status, $message) : object{

        $this->messages[$type][] = $status.'@$@'.$message;
        return $this;
    }

    public function getResultMessages( string $type) : string{
        $output = '';
        if((isset($this->messages[$type]))&&(count($this->messages[$type]) > 0)){
            foreach ($this->messages[$type] as $message){
                $exploded = explode('@$@' ,$message);
                if($exploded[0] == 'ok') {
                    $output .= '<div class="message-ok">' . $exploded[1] . '</div>';
                }
                if($exploded[0] == 'no_file') {
                    $output .= '<div class="message-ok">' . $exploded[1] . '</div>';
                }
                if($exploded[0] == 'error') {
                    $output .= '<div class="message-error">' . $exploded[1] . '</div>';
                }
            }
        }
        return $output;
    }
}