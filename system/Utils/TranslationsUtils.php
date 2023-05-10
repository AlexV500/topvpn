<?php


class TranslationsUtils{


    public static function stripWhitespaces(string $string): string {

        $old_string = $string;
        $string = strip_tags($string);
        $string = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $string);
        $string = str_replace('  ',' ', $string);
        $string = trim($string);

        if ($string === $old_string) {
            return $string;
        } else {
            return self::stripWhitespaces($string);
        }
    }

    public static function goPrepareKey($key){
        $string = htmlentities($key, null, 'utf-8');
        $content = str_replace(" ", "", $string);
        $content = html_entity_decode($content);
        return $content;
    }

}