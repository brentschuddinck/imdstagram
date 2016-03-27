<?php
session_start();
/* Voorwaarden succesvol inloggen:
* - sessie login bestaat en is niet NULL
* - sessie login met key loggedin is true
*
* Indien bovenstaande voorwaarden voldaan, dan mag bezoeker op de pagina blijven tenzij:
* de huidige pagina login.php of register.php is. In dat geval doorsturen naar index page.
*
* Indien login niet succesvol is, dan wordt gebruiker naar login.php gestuurd tenzij hij zelf op login.php of register.php is
*/


//tijdelijk totdat login.php de sessievariabelen opgevangen heeft
//$_SESSION['login']['loggedin']=1;
$_SESSION['login']['gebruikersnaam'] = "brentschuddinck";
//indien profile picture in db leeg is, vul dan op met default.png. Niet in db => onnodige opslag en lastig indien image later verplaatsen/hernoemen
//$_SESSION['login']['profielfoto']= "profile-picture_brentschuddinck_1459066448.jpg";
$_SESSION['login']['profielfoto']= "default.png";
$_SESSION['login']['naam']= "Brent Schuddinck";


$huidige_pagina = basename($_SERVER['PHP_SELF']);

if(isset($_SESSION['login']) && $sessieLoggedin=1){
    //makkelijk oproepen vanaf andere pagina's die sessiecontrole includen
    $sessie = $_SESSION['login'];
    $sessieNaam = 'login';
    $sessieLoggedin = $_SESSION['login']['loggedin'];
    $sessieGebruikersnaam = $_SESSION['login']['gebruikersnaam'];
    $sessieProfielfoto = $_SESSION['login']['profielfoto'];
    $sessieVoornaamFamilienaam = $_SESSION['login']['naam'];

    if ($huidige_pagina == 'login.php' || $huidige_pagina == 'register.php') {
        header('location: /imdstagram/index.php');
        //live site: header('location: /index.php');
    }
}else if ($huidige_pagina != 'login.php' && $huidige_pagina != 'register.php') {
    header('location: /imdstagram/login.php');
    // live site: header('location: /login.php');
}