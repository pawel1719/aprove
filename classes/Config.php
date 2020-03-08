<?php
class Config {

    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);

            foreach($path as $step) {
                if(isset($config[$step])) {
                    $config = $config[$step];
                }
            }

            return $config;
        }

        return false;
    }

}