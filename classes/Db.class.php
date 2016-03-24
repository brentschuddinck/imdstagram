<?php
class Db
{
    private static $conn;

    public static function getInstance(){
        if(is_null(self::$conn)){
            self::$conn = new PDO("mysql:host=159.253.0.244; dbname=brentca106_imdstagram", "brentca106_imdst", "gvqQpiGqo#(5)");
        }
        return self::$conn;
    }
}
?>