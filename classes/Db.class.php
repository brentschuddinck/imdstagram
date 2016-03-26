<?php

class Db {
    private static $conn;
    public static function getInstance() {
        if( is_null(self::$conn)) {
            self::$conn = new PDO("mysql:host=178.62.241.17; dbname=imdstagram", "imdstagram", "[lavfte{gqwedzsrjme7xsmXU");
        }
        return self::$conn;
    }
}