<?php
session_start();

//sessiecontrole
if(!isset($_SESSION["loggedin"])){
    //live server: header('location: /login.php');
    header('location: /imdstagram/login.php'); //offline werken in map imdstagram
}

?>