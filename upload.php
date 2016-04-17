<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Upload.class.php');

// uploaden van foto
    if(!empty($_POST['description']) && !empty($_FILES['postPhoto']['name'])) {
        // get file array info
        $postPhotoArr = $_FILES["postPhoto"];
        $postPhotoName = $postPhotoArr["name"];
        $postPhotoType = $postPhotoArr["type"];
        $postPhotoSize = $postPhotoArr["size"];
        $postPhotoTmpName = $postPhotoArr["tmp_name"];
        $postPhotoError = $postPhotoArr["error"];

        $maxBytes = 5120000; // maximum toegestane grote voor post foto's is 5mb

        if ($postPhotoError == 0) {
            $uploadPost = new Upload();

            // toegestane extenties/types
            $validExtensions = array("jpg", "png", "gif");
            $validTypes = array("image/jpeg", "image/png", "image/gif");
            $fileName = basename($postPhotoName);
            $fileExtension = substr($fileName, strrpos($fileName, '.') + 1);

            // check dat de file een toegestane extentie heeft/geldig type
            $ValidExtension = $uploadPost->isValidExtension($validExtensions, $fileExtension);
            $ValidType = $uploadPost->isValidType($validTypes, $postPhotoType);

            // als file een geldige extentie heeft & geldig type
            if($ValidType && $ValidExtension){
                //check dat bestand  niet te groot is
                $ValidSize = $uploadPost->isValidSize($postPhotoSize, $maxBytes);

                if($ValidSize){
                    // give file new name
                    $newFileName = "post-picture_userid-" . $_SESSION['login']['userid'] . "-" . time();
                    // add extension to new name
                    $FullFileName = $newFileName . "." . $fileExtension;
                    $filePath = "img/uploads/post-pictures/" . $FullFileName;

                    // move uploaded file to new location
                    $uploadFile = $uploadPost->uploadFile($postPhotoTmpName, $filePath);

                    if($uploadFile){
                        // upload foto to database
                        $post = new Post();
                        $post->setMSDescription($_POST['description']);
                        $post->setMSImageName($FullFileName);
                        $post->postPhoto();
                        $feedback = buildFeedbackBox("success", "Je foto is geplaatst!");
                    }
                }else{
                    $feedback = buildFeedbackBox("danger", "Je foto mag niet groter zijn dan 5mb.");
                }
            }else{
                $feedback = buildFeedbackBox("danger", "Je geselecteerde foto heeft geen geldige extentie. Toegestane extenties: .jpg, .png & .gif.");

            }
        }else{
            $feedback = buildFeedbackBox("danger", "Er is iets fout gegaan bij het uploaden van je foto, probeer het nog eens opnieuw.");

        }
    }elseif(isset($_POST['description']) && empty($_POST['description'])){
    $feedback = buildFeedbackBox("info", "Voeg een beschrijving aan je foto toe.");

    }elseif(isset($_FILES['postPhoto']) && empty($_FILES['postPhoto']['name'])){
    $feedback = buildFeedbackBox("warning", "Je hebt nog geen foto geslecteerd.");
    }
   

?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Imdstagram foto upload</title>
    <meta name="description"
          content="IMDstagram is dé creative place to be voor IMD studenten. IMDstagram geeft je inspiratie een boost door creatieve en inspirerende afbeeldingen te delen met andere studenten.">
    <?php include_once('inc/style.inc.php'); ?>
    <link rel="stylesheet" href="http://fontawesome.io/assets/font-awesome/css/font-awesome.css">

</head>
<body class="template">
<?php include_once('inc/header.inc.php'); ?>

<div class="container">
    <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">

    <div class="row">
        <h2>Upload foto</h2>
    </div>

    <div class="row">

            <div class="widget-area no-padding blank">
                <div class="status-upload">
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="postForm">
                        <?php
                        //toon boodschap
                        if (!empty($feedback)) {
                            echo $feedback;
                        }
                        ?>
                        <textarea name="description" id="description" maxlength="1000" placeholder="Beschrijving foto" required title="voeg een beschrijving toe aan je foto." ><?php echo isset($_POST['description']) && empty($_FILES['postPhoto']['name']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        <span><p id="charCount" class="help-block ">You have reached the limit</p></span>
                        <input type="file" id="file" name="postPhoto">
                        <button type="submit" name="submit" class="btn btn-success green"><i class="fa fa-reply"></i>Post</button>
                    </form>
                </div><!-- Status Upload  -->
            </div><!-- Widget Area -->
        </div>

    </div>
</div>

<?php include_once('inc/footer.inc.php'); ?>
<script src="js/charCount.js"></script>
</body>
</html>