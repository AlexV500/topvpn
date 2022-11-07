<?php


class AdminHtmlFormOutputs{

    public static function renderResultMessages( array $data) : string{

        $output = '';
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        foreach ($data as $type => $array) {

            foreach ($array as $key => $messages){

                foreach ($messages as $status => $text) {
                    if ($status == 'ok') {
                        $output .= '<div class="resultMessageOk">';
                        $output .= $text;
                        $output .= '</div>';
                    }
                    if ($status == 'error') {
                        $output .= '<div class="resultMessageError">';
                        $output .= $text;
                        $output .= '</div>';
                    }
                }
            }
        }

        return $output;
    }

}