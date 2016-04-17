<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');

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