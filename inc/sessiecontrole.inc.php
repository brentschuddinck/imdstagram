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
$huidige_pagina = basename($_SERVER['PHP_SELF']);
if(isset($_SESSION['login']['loggedin']) && $_SESSION['login']['loggedin']=1){
    if ($huidige_pagina == 'login.php' || $huidige_pagina == 'register.php') {
        header('location: index.php');
        //live site: header('location: /index.php');
    }
}else if ($huidige_pagina != 'login.php' && $huidige_pagina != 'register.php') {
    header('location: login.php');
    // live site: header('location: /login.php');
}