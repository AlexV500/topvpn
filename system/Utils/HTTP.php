<?php


class HTTP{

    public static function requestGet( $key, $def = false ) {
        return isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $def;
    }

    public static function requestHas( $key ) {
        return isset( $_REQUEST[ $key ] );
    }

    public static function requestIsPost() {
        return ( 'post' === strtolower( $_SERVER['REQUEST_METHOD'] ) );
    }

    public static function requestIsGet() {
        return ( 'get' === strtolower( $_SERVER['REQUEST_METHOD'] ) );
    }

    public static function getRequestParam($name) {

        if(self::requestIsGet()){
            if(isset($_GET[$name]))
                return $_GET[$name];
            else
                return null;
        }else{
            if(isset($_POST[$name]))
                return $_POST[$name];
            else
                return null;
        }
    }

    public static function getPost($idx, $default = null){

        if(isset($_POST[$idx]))
            return $_POST[$idx];
        return $default;
    }

    public static function getGet($idx, $default = null){

        if(isset($_GET[$idx]))
            return $_GET[$idx];
        return $default;
    }

    public static function getIP(){

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        } return $ip;
    }

    public static function getIPToLang(){
        return ip2long(self::getIP());
    }

    public static function requestUrl()
    {
        $result = ''; // Пока результат пуст
        $default_port = 80; // Порт по-умолчанию

        // А не в защищенном-ли мы соединении?
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
            // В защищенном! Добавим протокол...
            $result .= 'https://';
            // ...и переназначим значение порта по-умолчанию
            $default_port = 443;
        } else {
            // Обычное соединение, обычный протокол
            $result .= 'http://';
        }
        // Имя сервера, напр. site.com или www.site.com
        $result .= $_SERVER['SERVER_NAME'];

        // А порт у нас по-умолчанию?
        if ($_SERVER['SERVER_PORT'] != $default_port) {
            // Если нет, то добавим порт в URL
            $result .= ':'.$_SERVER['SERVER_PORT'];
        }
        // Последняя часть запроса (путь и GET-параметры).
        $result .= $_SERVER['REQUEST_URI'];

        return $result;
    }

    public static function getPath(){

        $url = self::requestUrl();
        $parsed = parse_url($url);
        $path = substr($parsed['path'], 1);
        return explode("/", $path);
    }


    public static function getURI($lvl = null){

        $url = self::requestUrl();
        $parsed_array = parse_url($url);
        $path = trim($parsed_array['path'], '/');
        $ret = $path;
        if($lvl !== null){
            $exploded = explode($path, '/');
            if($lvl == 2){
                $ret = $exploded['1'];
            }
        }
        return $ret;
    }

    public static function getPageNum(){

        $url = self::requestUrl();
        $parsed_array = parse_url($url);
        $path = substr($parsed_array['path'], 1);
        $exploded = explode("/", $path);
        if ($exploded[1] == ''){$exploded[1] = 1;}
        return $exploded[1];
    }

    public static function checkImageType($img){

        if (in_array($img['type'], array('image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'))) {
            return true;
        } else {
            return false;
        }
    }

}