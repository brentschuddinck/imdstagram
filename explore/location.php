<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../inc/feedbackbox.inc.php');
include_once('../classes/Validation.class.php');
include_once('../classes/Post.class.php');

$location = $_GET['location'];


if (isset($_GET['location']) && !empty($_GET['location']) && count($_GET) === 1) {

    $amountOfSearchResults = 0;

//controleer geldige locatie
    $post = new Post();
    $validation = new Validation();

    if ($validation->isValidSearchTerm($location)) {
        try {
            $post->setMSLocation($location);
            $userPosts = $post->getAllLocationPosts();
            echo print_r($userPosts);
            $amountOfSearchResults = count($userPosts);
        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    } else {
        header('Location: /imdstagram/error/404.php');
    }
} else {
    header('Location: /imdstagram/error/404.php');
}


?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Zoeken naar posts in <?php echo htmlspecialchars($location); ?></title>
    <meta name="description">
    <?php include_once('../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('../inc/header.inc.php'); ?>


<!-- start location feed -->
<div class="container search">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="centreer tekst"><?php echo htmlspecialchars($location); ?></h1>
            <div class='centreer tekst search nmessages vet'><?php echo $amountOfSearchResults ?> resultaten</div>
            <br/>

            <?php if (isset($feedback) && !empty($feedback)) : ?>
                <div class="centreer tekst col-xs-12 col-md-8 col-md-offset-2"><?php echo $feedback; ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- start lijst met zoekresultaten -->


    <?php if (isset($userPosts) && !empty($userPosts)) : ?>

        <?php foreach ($userPosts as $userPost): ?>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <a data-id="<?php echo $userPost['post_id'] ?>" class="thumbnail picturelist">
                    <img class="thumb <?php echo $userPost['photo_effect']; ?>"
                         src="../img/uploads/post-pictures/<?php echo $userPost['post_photo']; ?>" alt="">
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- einde lijst zoekresultaten -->


</div>
<!-- einde location feed -->


<?php include_once('../inc/footer.inc.php'); ?>

</body>
</html>