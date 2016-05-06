<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Upload.class.php');

// uploaden van foto
if (!empty($_POST['description']) && !empty($_FILES['postPhoto']['name'])) {

    // get file array info
    $postPhotoArr = $_FILES["postPhoto"];
    $postPhotoName = $postPhotoArr["name"];
    $postPhotoType = $postPhotoArr["type"];
    $postPhotoSize = $postPhotoArr["size"];
    $postPhotoTmpName = $postPhotoArr["tmp_name"];
    $postPhotoError = $postPhotoArr["error"];

    $maxBytes = 5242880; // maximum toegestane grote voor post foto's is 5mb
    $maxMB = floor($maxBytes / 1024 / 1024);


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
        if ($ValidType && $ValidExtension) {

            //security met getimagesize kan gekeken worden of de inhoud geen script, maar wel een echte afbeelding is
            if ($uploadPost->isImageNotAScript($postPhotoTmpName) === false) {
                $feedback = buildFeedbackBox("danger", "hackpoging gedetecteerd. Alleen echte afbeeldingen worden geaccepteerd.");
            } else {
                //check dat bestand  niet te groot is
                $ValidSize = $uploadPost->isValidSize($postPhotoSize, $maxBytes);

                if ($ValidSize === true) {
                    // give file new name
                    $newFileName = "post-picture_userid-" . $_SESSION['login']['userid'] . "-" . time();
                    // add extension to new name
                    $FullFileName = $newFileName . "." . $fileExtension;
                    $filePath = "img/uploads/post-pictures/" . $FullFileName;

                    // move uploaded file to new location
                    $uploadFile = $uploadPost->uploadFile($postPhotoTmpName, $filePath);

                    if ($uploadFile) {
                        // upload foto to database
                        $post = new Post();
                        $post->setMSDescription($_POST['description']);
                        $post->setMSImageName($FullFileName);
                        $post->setMSLocation($_POST['location']);
                        $post->setMSEffect($_POST['effect']);
                        $post->setMSUserId($_SESSION['login']['userid']);

                        if($post->postPhoto()){
                            if ($post->doesStringContain($post->getMSDescription(), '#')) {
                                //bevat de geplaatste post een hashtag?

                                //haal id van laatste geposte post op
                                $postId = $post->getPostIdFromLatestPost();
                                $post->setMSPostId($postId['post_id']);


                                //zoek naar hashtags in post_description en stop ze in een array
                                $hashtagsInPostDescription = $post->get_hashtags($post->getMSDescription(), $str = 0);
                                if (!empty($hashtagsInPostDescription)) {
                                    foreach ($hashtagsInPostDescription as $hashtag) {
                                        //plaats elke hashtag in de database
                                        $post->setMSTag($hashtag);
                                        $post->addTagToDatabase();
                                    }
                                }
                            }
                            $feedback = buildFeedbackBox("success", "Je foto is geplaatst! <a href='/imdstagram/index.php'>Bekijk het resultaat</a>.");

                        }else{
                            $feedback = buildFeedbackBox("danger", "er is iets misgelopen bij het plaatsen van je post. Onze excuses voor het ongemak. Probeer het later opnieuw.");
                        }

                    } else {
                        $feedback = buildFeedbackBox("danger", "er is iets misgelopen bij het plaatsen van je post. Onze excuses voor het ongemak. Probeer het later opnieuw.");
                    }
                } else {
                    $feedback = buildFeedbackBox("danger", "je foto mag niet groter zijn dan " . $maxMB . "MB.");
                }
            }

        } else {
            $feedback = buildFeedbackBox("danger", "je gekozen foto heeft geen geldige extentie. Toegestane extenties: .jpg, .png & .gif.");
        }
    } else {
        $feedback = buildFeedbackBox("danger", "er is iets fout gegaan bij het uploaden van je foto, probeer het nog eens opnieuw.");
    }

} elseif (isset($_POST['description']) && empty($_POST['description'])) {
    $feedback = buildFeedbackBox("info", "Voeg een beschrijving aan je foto toe.");

} elseif (isset($_FILES['postPhoto']) && empty($_FILES['postPhoto']['name'])) {
    $feedback = buildFeedbackBox("warning", "je hebt nog geen foto toegevoegd.");
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

<div class="container uploads">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

        <div class="row">
            <h1>Upload foto</h1>
        </div>

        <div class="row">

            <div class="widget-area no-padding blank">
                <div class="status-upload">
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data"
                          id="postForm">
                        <?php
                        //toon boodschap
                        if (!empty($feedback)) {
                            echo $feedback;
                        }
                        ?>
                        <input type="hidden" name="location" id="location" value="">

                        <label for="description">Bericht bij je foto:</label>
                        <textarea name="description" id="description" maxlength="1000"
                                  placeholder="Bericht dat bij je foto zal worden getoond" autofocus required
                                  title="voeg een beschrijving toe aan je foto."><?php echo isset($_POST['description']) && empty($_FILES['postPhoto']['name']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                        <span><p id="charCount" class="help-block ">Je bericht heeft de limiet van 1000 karakters
                                bereikt.</p></span>

                        <label for="file">Bladeren naar een afbeelding:</label>
                        <input type="file" id="file" name="postPhoto" accept="image/*;capture=camera">

                        <label for="effect" id="label-effect">Versterk je foto met een effect:</label>
                        <noscript>Tip: schakel JavaScript in om een preview van je foto met gekozen effect te kunnen bekijken.</noscript>
                        <select name="effect" id="effect">
                            <option value="default">Geen effect</option>
                            <option value="_1977">1977</option>
                            <option value="aden">Aden</option>
                            <option value="brooklyn">Brooklyn</option>
                            <option value="clarendon">Clarendon</option>
                            <option value="earlybird">Earlybird</option>
                            <option value="gingham">Gingham</option>
                            <option value="hudson">Hudson</option>
                            <option value="inkwell">Inkwell</option>
                            <option value="lark">Lark</option>
                            <option value="lofi">Lofi</option>
                            <option value="mayfair">Mayfair</option>
                            <option value="moon">Moon</option>
                            <option value="nashville">Nashville</option>
                            <option value="perpetua">Perpetua</option>
                            <option value="reyes">Reyes</option>
                            <option value="rise">Rise</option>
                            <option value="reyes">Reyes</option>
                            <option value="slumber">Slumber</option>
                            <option value="toaster">Toaster</option>
                            <option value="walden">Walden</option>
                            <option value="willow">Willow</option>
                            <option value="xpro2">Xpro2</option>
                        </select>

                        <div id="send">
                            <label for="submit">Plaats je foto:</label>
                            <button type="submit" name="submit" id="submit" class="btn btn-success green"><i
                                    class="fa fa-reply"></i>Plaats je foto
                            </button>
                        </div>

                    </form>
                </div><!-- Status Upload  -->
            </div><!-- Widget Area -->
        </div>

    </div>
</div>

<?php include_once('inc/footer.inc.php'); ?>
<script src="js/charCount.js"></script>
<script src="js/uploadPostPreview.js"></script>
<!--<script src="js/effectPreview.js"></script>-->
<script src="js/postLocation.js"></script>



</body>
</html>