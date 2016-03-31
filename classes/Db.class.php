<?php

class Db {

    private static $conn;

    public static function getInstance() {
        if( is_null(self::$conn)) {
            try{
                self::$conn = new PDO("mysql:host=178.62.241.17; dbname=imdstagram", "imdstagram", "[lavfte{gqwedzsrjme7xsmXU");
            }catch(Exception $e){
                throw new Exception("connectie met server mislukt. Onze excuses voor het ongemak. Probeer later opnieuw.");
            }
        }
        return self::$conn;
    }
}