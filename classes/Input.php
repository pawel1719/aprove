<?php
class Input {
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
            break;
            case 'get':
                return (!empty($_GET)) ? true : false;
            break;
            default:
                return false;
            break;
        }
    }

    public static function get($item) {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } else if(isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }

    public static function set($name, $value, $type = 'post') {
        switch($type) {
            case 'post':
                $_POST[$name] = $value;
            break;
            case 'get':
                $_GET[$name] = $value;
            break;
        }
    }

    public static function destroy($names) {
        $vars = explode(',', $names);

        foreach($vars as $var) {
            if(isset($_POST[$var])) {
                unset($_POST[$var]);
            } else if(isset($_GET[$var])) {
                unset($_GET[$var]);
            }
        }
    }
}