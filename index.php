<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Upload.class.php');

// uploaden van foto
if(!empty($_POST['beschrijving']) && !empty($_FILES['foto']['name'])){
    // get file info
    $filetmp = $_FILES['foto']['tmp_name'];
    $filename = $_FILES['foto']['name'];
    $filetype = $_FILES['foto']['type'];
    $filesize = $_FILES['foto']['size'];
    // create unique filename
    $newFileName = $_SESSION['login']['userid'] . '_' . date('d-m-y') . '_' . date('H') . 'u' . date('i') . 'm' . date('s') . 's' . '_' . $filename;
    // path naar folder waar foto opgeslagen zal worden
    $filepath = "img/uploads/post-pictures/" . $newFileName;
    $maxBytes = 512000;


    // kijken of een geldig afbeeldingsformaat opgeladen is
    if(exif_imagetype($filetmp) == IMAGETYPE_JPEG || exif_imagetype($filetmp) == IMAGETYPE_PNG || exif_imagetype($filetmp) == IMAGETYPE_GIF){
        $upload = new Upload();
        if($upload->isValidSize($filesize, $maxBytes)){
        move_uploaded_file($filetmp, $filepath);
        $post = new Post();
        $post->setMSDescription($_POST['beschrijving']);
        $post->setMSImage($newFileName);
        $post->postPhoto();
        }else{
            $feedback = buildFeedbackBox("danger", "Je foto mag niet groter zijn dan 500KB.");

        }
    }else{
        $feedback = buildFeedbackBox("danger", "geen geldig afbeeldingsformaat");
    }


    }elseif(isset($_POST['beschrijving']) && empty($_POST['beschrijving'])){
    $feedback = buildFeedbackBox("info", "Je foto moet een beschrijving bevatten.");

    }elseif(isset($_FILES['foto']) && empty($_FILES['foto']['name'])){
    $feedback = buildFeedbackBox("warning", "Je hebt nog geen foto geslecteert.");
}

$getPosts = new Post();
$showPosts = $getPosts->getAllPosts();
?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram tijdlijn</title>
    <meta name="description"
          content="IMDstagram is dé creative place to be voor IMD studenten. IMDstagram geeft je inspiratie een boost door creatieve en inspirerende afbeeldingen te delen met andere studenten.">
    <?php include_once('inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->
<div class="container">
    <div class="row">

        <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
            <!-- upload form -->
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" id="postForm">
                <?php
                //toon boodschap
                if (!empty($feedback)) {
                    echo $feedback;
                }
                ?>
                <textarea name="beschrijving" id="beschrijving" maxlength="1000" rows="5" placeholder="beschrijving foto" required title="Beschrijving kan niet leeg zijn" class="form-control login-field"><?php echo isset($_POST['beschrijving']) && empty($_FILES['foto']['name']) ? htmlspecialchars($_POST['beschrijving']) : '' ?></textarea>
                    <input type="file" name="foto" id="file" >
                    <input type="submit" name="submit" value="plaatsen" class="btn btn-block btn-lg btn-primary">
            </form>
        </div>

        <!-- col-xx-offet-x zorgt voor de centrering middening -->
        <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">

            <h1>Photo wall</h1>
            <?php foreach($showPosts as $showPost): ?>
                <article class="thumbnail">
                    <p class="postDate"><?php echo $showPost['post_date']; ?></p>
                    <img src="img/uploads/post-pictures/<?php echo $showPost['post_photo'] ?>" alt="...">
                    <div class="caption">
                        <p><?php echo htmlspecialchars($showPost['post_description']); ?></p>
                    </div>
                </article>
            <?php endforeach ?>
        </div>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

</body>
</html>