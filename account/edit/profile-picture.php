<?php
include_once('../../inc/sessiecontrole.inc.php');
include_once('../../inc/feedbackbox.inc.php');
include_once('../../classes/Upload.class.php');

// uploaden van foto
if(isset($_POST['btnUploadProfielfoto'])) {

    //ophalen afbeelding tyupe
    $profielfotoArr = $_FILES["uploaded_profielfoto"];
    $profielfotoNaam = $profielfotoArr["name"]; //the original name of the file uploaded from the user's machine
    $profielfotoType = $profielfotoArr["type"]; //the MIME type of the uploaded file (if the browser provided the type)
    $profielfotoSize = $profielfotoArr["size"]; //the size of the uploaded file in bytes
    $profielfotoTmpName = $profielfotoArr["tmp_name"]; //the location in which the file is temporarily stored on the server
    $profielfotoError = $profielfotoArr["error"]; //an error code resulting from the file upload

    $maxBytes = 512000; //1024 x 500 = 512000 bytes = 500KB. Maximale bestandsgrootte dat we willen toestaan
    $uploadKwaliteitInPct = 60; //algemeen genomen zoals we doen bij Photoshop export van afbeeldingen naar het web

    //checken of we een bestand hebben en error geen 0 is
    if ((!empty($profielfotoArr)) && ($profielfotoError == 0)) {
        try{
            $upload = new Upload();

            //controleer geldig afbeeldingsformaat
                //bestandsnaam ophalen
                $filename = basename($profielfotoNaam);
                //extentie ophalen +1 => .jpg => jpg
                $extentieBestand = substr($filename, strrpos($filename, '.') + 1);
                $geldigeExtenties = array("jpg", "png", "gif");
                $geldigeTypes = array("image/jpeg", "image/png", "image/gif");

                $isGeldigeExtentie = $upload->isExtentieGeldig($geldigeExtenties, $extentieBestand);
                $isGeldigType = $upload->isTypeGeldig($geldigeTypes, $profielfotoType);

                if($isGeldigType === true && $isGeldigeExtentie === true){
                    //controleer of bestand niet te groot is
                    $isBestandNietTeGroot = $upload->isBestandNietTeGroot($profielfotoSize, $maxBytes);
                    if($isBestandNietTeGroot){



                        //Nieuwe locatie en naam bepalen tmp folder naar uiteindelijke folder
                        $dbFileName = "profile-picture_userid-" . $_SESSION['login']['userid'];
                        $dbFullFileName = $dbFileName . "." . $extentieBestand;
                        $newnameTillId = "../../img/uploads/profile-pictures/" . $dbFileName;
                        $fullNewName =  $newnameTillId . "." . $extentieBestand;

                        //Kijk of file al bestaat. Profielfoto mag overschreven worden, maar zo kunnen we extra query vermeiden

                        $uploadGeslaagd = false;

                        if(!file_exists($fullNewName)){
                            //oud bestand? Dit moet gewist worden
                            //als nieuwe naam niet bestaat kijken of er nog oude file is en die wissen
                            //glob: 'Find pathnames matching a pattern'
                            foreach (glob($newnameTillId . ".*") as $fileToRemove) {
                                unlink($fileToRemove);
                            }

                            //eventuele oude bestanden zijn gewist. Nu foto updaten
                            $uploadBestand = $upload->uploadFile($profielfotoTmpName, $fullNewName);

                            //er is een nieuwe bestandsextentie => database updaten
                            $upload->setMSUserId($_SESSION['login']['userid']);
                            $upload->setMSProfilePicture($dbFullFileName);
                            $uploadProfielfoto = $upload->saveProfilePicture();

                            if($uploadProfielfoto){
                                $uploadGeslaagd = true;
                            }

                        }else{
                            //het bestand bestaat, mag overschreven worden + geen query nodig
                            $uploadBestand = $upload->uploadFile($profielfotoTmpName, $fullNewName);
                            $uploadGeslaagd = true;
                        }


                        //indien upload (incl db) geslaagd is, dan sessie aanpassen en successboodschap tonen
                        if($uploadGeslaagd){
                            $_SESSION['login']['profielfoto'] = $dbFullFileName;
                            $feedback = bouwFeedbackBox("success", "Je profielfo is gewijzigd.");
                        }


                    }else{
                        $feedback = bouwFeedbackBox("danger", "de profielfoto mag niet groter zijn dan 500KB.");
                    }
                }else{
                    $feedback = bouwFeedbackBox("danger", "de profielfoto is geen geldige afbeelding. Alleen jpg, png en gif bestanden zijn toegestaan.");
                }

        }catch(Exception $e){
            $feedback = $e->getMessage();
            $feedback = bouwFeedbackBox("danger", $feedback);
        }

    } else {
        $feedback = bouwFeedbackBox("danger", "er is geen profielfoto geüpload.");
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
                src="../../img/uploads/profile-pictures/<?php echo htmlspecialchars($_SESSION['login']['profielfoto']); ?>"
                alt="Profielfoto van <?php echo htmlspecialchars($_SESSION['login']['naam']); ?>">
        </div>
        <!-- einde formuliergroep profielfoto -->

        <input type="file" id="fileToUpload" class="uploadfile" name="uploaded_profielfoto">
        <input type="submit" value="Profielfoto wijzigen" name="btnUploadProfielfoto" id="btnUploadProfielfoto"
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