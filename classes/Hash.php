<?php
class Hash {
    public static function make($string, $salt = '') {
        return hash('sha512', $string . $salt);
    }

    public static function slat($length = 32) {
        return bin2hex(random_bytes($length));
    }

    public static function unique() {
        return self::make(uniqid());
    }
}