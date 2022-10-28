<?php


class AdminHtmlFormOutputs{

    public static function renderResultMessages( array $messages) : string{

        $output = '';

        foreach ($messages as $type => $message) {

            foreach ($message as $status => $text){

                if($status == 'ok'){
                    $output .= '<div class="resultMessageOk">';
                    $output .= $text;
                    $output .= '</div>';
                }
                if($status == 'error'){
                    $output .= '<div class="resultMessageError">';
                    $output .= $text;
                    $output .= '</div>';
                }
            }
        }

        return $output;
    }

}