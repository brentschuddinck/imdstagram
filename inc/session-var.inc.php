<?php
//makkelijk oproepen vanaf andere pagina's die sessiecontrole uitvoeren.
//Voordeel: stel we veranderen iets (bv: sessienaam), dan niet overal moeten gaan anpassen.
$sessieNaam = 'login';
$sessie = $_SESSION[$sessieNaam];
$sessieLoggedin = $sessie['loggedin'];
$sessieGebruikersnaam = $sessie['gebruikersnaam'];
$sessieProfielfoto = $sessie['profielfoto'];
$sessieVoornaamFamilienaam = $sessie['naam'];
$sessieEmail = $sessie['email'];