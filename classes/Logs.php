<?php

class Logs {
    private function addLine($line, $file_name) {
        if(strlen($file_name) == 0) {
            $file_name = self::pathToLogs();
        }
        $handle = fopen($file_name, "a+");

        $date       = date('Y-m-d H:i:s P');
        $from       = Input::get('HTTP_REFERER');
        $file       = Input::get('REQUEST_URI');
        $req        = Input::get('REQUEST_METHOD');
        $protocol   = Input::get('SERVER_PROTOCOL');
        $client     = Input::get('REMOTE_ADDR');
        $port       = Input::get('REMOTE_PORT');
        $user_agent = Input::get('HTTP_USER_AGENT');

        fputs($handle, "[{$date}] - {$client}:{$port} - {$req} {$protocol} {$from} >> {$file} --- ". $line ." --- {$user_agent}\n");

        fclose($handle);
    }

    public static function pathToLogs() {

        if(is_dir('files/logs')) {
            $path = 'files/logs/';
        } else if(is_dir('../files/logs')) {
            $path = '../files/logs/';
        } else if(is_dir('../../files/logs')) {
            $path = '../../files/logs/';
        }

        $path .= 'Logs_' . date('Y-m') . '.txt';

        return $path;
    }

    public static function addError($line, $path = '') {
        self::addLine( "Error: ". $line, $path);
    }

    public static function addWarning($line, $path = '') {
        self::addLine( "Warning: ". $line, $path);
    }

    public static function addInformation($line, $path = '') {
        self::addLine( "Information: ". $line, $path);
    }
}