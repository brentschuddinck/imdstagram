<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../classes/Db.class.php');
include_once('../classes/User.class.php');
include_once('../classes/Validation.class.php');

$sessieUsername = $_SESSION['login']['gebruikersnaam'];
$nieuweUsername = $_POST['username'];

$user = new User();
$validation = new Validation();

if(isset($_POST['username']) && !empty($_POST['username'])) {

    //geldigheid controleren. Hergebruik functie uit validatieklasse
    $isGeldigeGebruikersnaam = $validation->isGeldigGebruikersnaam($nieuweUsername);

    if(empty($isGeldigeGebruikersnaam)){
        $user->setMSGebruikersnaam($nieuweUsername);
        $isUsernameAvailable = $user->UsernameAvailable();

        if($isUsernameAvailable /*|| $nieuweUsername == $sessieUsername*/) {
            $response['status'] = 'available';
            $response['message'] = 'De gebruikersnaam is beschikbaar.';
        }else{
            $response['status'] = "not-available";
            $response['message'] = 'De gebruikersnaam is reeds in gebruik.';
        }
    }else{
        $response['status'] = "error";
        $response['message'] = $isGeldigeGebruikersnaam;
    }

    header('Content-type: application/json');
    echo json_encode($response);
}