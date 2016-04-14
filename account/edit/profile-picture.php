<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/Upload.class.php');

// uploaden van foto
if(isset($_POST['btnUploadProfilePicture'])) {

    //ophalen afbeelding tyupe
    $profilePhotoArr = $_FILES["uploaded_profielfoto"];
    $profilephotoName = $profilePhotoArr["name"]; //the original name of the file uploaded from the user's machine
    $profilePhotoType = $profilePhotoArr["type"]; //the MIME type of the uploaded file (if the browser provided the type)
    $profilePhotoSize = $profilePhotoArr["size"]; //the size of the uploaded file in bytes
    $profilePhotoTmpName = $profilePhotoArr["tmp_name"]; //the location in which the file is temporarily stored on the server
    $profilePhotoError = $profilePhotoArr["error"]; //an error code resulting from the file upload

    $maxBytes = 512000; //1024 x 500 = 512000 bytes = 500KB. Maximale bestandsgrootte dat we willen toestaan
    $uploadQualityInPct = 60; //algemeen genomen zoals we doen bij Photoshop export van afbeeldingen naar het web

    //checken of we een bestand hebben en error geen 0 is
    if ((!empty($profilePhotoArr)) && ($profilePhotoError == 0)) {
        try{
            $upload = new Upload();

            //controleer geldig afbeeldingsformaat
                //bestandsnaam ophalen
                $filename = basename($profilephotoName);
                //extentie ophalen +1 => .jpg => jpg
                $extensionFile = substr($filename, strrpos($filename, '.') + 1);
                $validExtensions = array("jpg", "png", "gif");
                $validTypes = array("image/jpeg", "image/png", "image/gif");

                $isValidExtension = $upload->isValidExtension($validExtensions, $extensionFile);
                $isValidType = $upload->isValidType($validTypes, $profilePhotoType);

                if($isValidType === true && $isValidExtension === true){
                    //controleer of bestand niet te groot is
                    $isValidSize = $upload->isValidSize($profilePhotoSize, $maxBytes);
                    if($isValidSize){


                        //Nieuwe locatie en naam bepalen tmp folder naar uiteindelijke folder
                        $dbFileName = "profile-picture_userid-" . $_SESSION['login']['userid'];
                        $dbFullFileName = $dbFileName . "." . $extensionFile;
                        $newnameTillId = "../../img/uploads/profile-pictures/" . $dbFileName;
                        $fullNewName =  $newnameTillId . "." . $extensionFile;


                        //Kijk of file al bestaat. Profielfoto mag overschreven worden, maar zo kunnen we extra query vermeiden

                        $uploadSucces = false;

                        if(!file_exists($fullNewName)){
                            //oud bestand? Dit moet gewist worden
                            //als nieuwe naam niet bestaat kijken of er nog oude file is en die wissen
                            //glob: 'Find pathnames matching a pattern'
                            foreach (glob($newnameTillId . ".*") as $fileToRemove) {
                                unlink($fileToRemove);
                            }

                            //eventuele oude bestanden zijn gewist. Nu foto updaten
                            $uploadFile = $upload->uploadFile($profilePhotoTmpName, $fullNewName);

                            //er is een nieuwe bestandsextentie => database updaten
                            $upload->setMSUserId($_SESSION['login']['userid']);
                            $upload->setMSProfilePicture($dbFullFileName);
                            $uploadProfilePhoto = $upload->saveProfilePicture();

                            if($uploadProfilePhoto){
                                $uploadSucces = true;
                            }

                        }else{
                            //het bestand bestaat, mag overschreven worden + geen query nodig
                            $uploadFile = $upload->uploadFile($profilePhotoTmpName, $fullNewName);
                            $uploadSucces = true;
                        }


                        //indien upload (incl db) geslaagd is, dan sessie aanpassen en successboodschap tonen
                        if($uploadSucces){
                            $_SESSION['login']['profilepicture'] = $dbFullFileName;
                            $feedback = buildFeedbackBox("success", "Je profielfo is gewijzigd.");
                        }


                    }else{
                        $feedback = buildFeedbackBox("danger", "de profielfoto mag niet groter zijn dan 500KB.");
                    }
                }else{
                    $feedback = buildFeedbackBox("danger", "de profielfoto is geen geldige afbeelding. Alleen jpg, png en gif bestanden zijn toegestaan.");
                }

        }catch(Exception $e){
            $feedback = $e->getMessage();
            $feedback = buildFeedbackBox("danger", $feedback);
        }

    } else {
        $feedback = buildFeedbackBox("danger", "er is geen profielfoto geüpload.");
    }
}



?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Profiel bewerken</title>
    <?php include_once('../../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('../../inc/header.inc.php'); ?>

<!-- start pagina profiel bewerken  -->
<?php include_once('../../inc/preferences-sidebar.inc.php'); ?>


<!-- start profiel content sectie -->

<h1>Profielfoto wijzigen</h1>
<!-- Start sectie privacy -->

<section class="settings profielfoto">
    <!-- start form profielinstellingen -->
    <form action="" method="POST" enctype="multipart/form-data">

        <?php
        //toon feedback
        if (!empty($feedback)) {
            echo $feedback;
        }
        ?>

        <!-- start formuliergroep profielfoto -->
        <div class="form-group">
            <img
                class="profielfoto groot"
                title="Mijn profielfoto"
                src="../../img/uploads/profile-pictures/<?php echo htmlspecialchars($_SESSION['login']['profilepicture']); ?>"
                alt="Profielfoto van <?php echo htmlspecialchars($_SESSION['login']['name']); ?>">
        </div>
        <!-- einde formuliergroep profielfoto -->

        <input type="file" id="fileToUpload" class="uploadFile" name="uploaded_profielfoto">
        <input type="submit" value="Profielfoto wijzigen" name="btnUploadProfilePicture" id="btnUploadProfilePicture"
               class="btn btn-primary btn-large">


    </form>
    <!-- einde form profielinstellingen-->
</section>
<!-- Einde sectie privacy  -->

</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<?php include_once('../../inc/footer.inc.php'); ?>
</body>
</html>