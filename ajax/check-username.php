<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../classes/Db.class.php');
include_once('../classes/User.class.php');

$sessieUsername = $_SESSION['login']['gebruikersnaam'];
$nieuweUsername = $_POST['username'];

$user = new User();

if(isset($_POST['username']) && !empty($_POST['username'])) {

    $user->setMSGebruikersnaam($nieuweUsername);
    $isUsernameAvailable = $user->UsernameAvailable();

    if($isUsernameAvailable || $nieuweUsername == $sessieUsername) {
        $response['status'] = 'available';
        $response['message'] = 'De gebruikersnaam is beschikbaar.';
    }else {
        $response['status'] = "not-available";
        $response['message'] = 'De gebruikersnaam is reeds in gebruik.';
    }

    header('Content-type: application/json');
    echo json_encode($response);
}