<?php

class ConnectToDb{

    public static function make($config) {
        return new PDO("mysql:host={$config['host']};dbname={$config['dbname']}","{$config['dbuser']}","{$config['dbpassword']}");
    }
} 
?>