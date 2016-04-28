<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../classes/Db.class.php');
include_once('../classes/User.class.php');
include_once('../classes/Validation.class.php');

$sessieUsername = $_SESSION['login']['username'];
$nieuweUsername = $_POST['username'];

$user = new User();
$validation = new Validation();

if(isset($_POST['username']) && !empty($_POST['username'])) {

    //geldigheid controleren. Hergebruik functie uit validatieklasse
    //eerst kijken op validerend. Indien niet, een onnodige query vermeden.
    $isValidUsername = $validation->isValidUsername($nieuweUsername);

    if(empty($isValidUsername)){
        $user->setMSUsername($nieuweUsername);
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
        $response['message'] = $isValidUsername;
    }

    header('Content-type: application/json');
    echo json_encode($response);
}