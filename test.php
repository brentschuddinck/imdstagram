<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Db.class.php');
include_once('classes/User.class.php');
include_once('classes/Upload.class.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Validation.class.php');


// uploaden van foto

if (isset($_POST['btnUploadProfielfoto'])) {
    $oudeProfielfoto = $_SESSION['login']['profielfoto'];

    //data nieuwe profielfoto ophalen uit array
    $userid = $_SESSION['login']['userid'];
    $nieuweProfielfotoFiletmp = $_FILES['fileToUpload']['tmp_name'];
    $nieuweProfielfotoFilename = $_FILES['fileToUpload']['name'];
    $nieuweProfielfotoFiletype = $_FILES['fileToUpload']['type'];
    $nieuweProfielfotoFilesize = $_FILES['fileToUpload']['size']; //bestandsgrootte in bytes => /1024 = KB
    $maxBytes = 512000; //profielfoto mag max 500KB groot zijn. (500 x 1024)

    //haalt bestandsextentie van de foto op (bijvoobeeld: jpg opgelet NIET .jpg maar jpg
    $nieuweProfielfotoFileExtention = pathinfo($nieuweProfielfotoFilename, PATHINFO_EXTENSION);

    $nieuweProfielfotoNaam = "profile-picture_userid-" . $_SESSION['login']['userid'] . '.' . $nieuweProfielfotoFileExtention;

    // path naar folder waar foto opgeslagen zal worden
    $path = "img/uploads/profile-pictures/";
    $nieuweProfielfotoPad = $path . $nieuweProfielfotoNaam;


    //valideer op geldige afbeeldingsextentie

    $validation = new Validation();


    $source_img = 'source.jpg';
    $destination_img = 'destination .jpg';


    if($validation->isExtentieAfbeelding($nieuweProfielfotoFiletmp)){

        //move upload file naar path met profielfoto's
        move_uploaded_file($nieuweProfielfotoFiletmp, $nieuweProfielfotoPad);

        //foto's uploaden
        try {
            $uploadProfilePicture = new Upload();
            $uploadProfilePicture->setMSProfilePicture($nieuweProfielfotoNaam);
            $uploadProfilePicture->setMSUserId($userid);
            if($uploadProfilePicture->isBestandNietTeGroot($nieuweProfielfotoFilesize, $maxBytes)){
                if($uploadProfilePicture->minifyImage($nieuweProfielfotoPad, $nieuweProfielfotoPad, 60)){
                    $uploadProfilePicture->saveProfilePicture();
                    $uploadProfilePicture->deleteFileFromServer($path, $oudeProfielfoto, $nieuweProfielfotoNaam);
                    //update de sessievariabele
                    $_SESSION['login']['profielfoto'] = $nieuweProfielfotoNaam;

                    //toon feedback
                    $feedback = bouwFeedbackBox("success", "Je profielfoto is bijgewerkt.");
                }else{
                    $feedback = bouwFeedbackBox("danger", "Je profielfoto kan niet verkleind worden.");
                }
            }else{
                $feedback = bouwFeedbackBox("danger", "Je profielfoto mag niet groter dan 500KB zijn.");
            }


        } catch (Exception $e) {
            $exception = $e->getMessage();
            $feedback = bouwFeedbackBox("danger", $exception);
        }

    }else{
        //validatie is niet gelukt
        $feedback = bouwFeedbackBox("danger", "Je kan alleen afbeeldingen uploaden met een jpg, jpeg, png of gif extentie. Andere bestanden zijn niet toegestaan.");
    }

}



?><!DOCTYPE html>
<html>
<head>
    <title>Test</title>
</head>
<body>

<form action="" method="POST" enctype="multipart/form-data">
    <?php
    //toon feedback
    if (!empty($feedback)) {
        echo $feedback;
    }
    ?>
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="btnUploadProfielfoto" id="btnUploadProfielfoto">
</form>

</body>
</html>