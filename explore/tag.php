<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../inc/feedbackbox.inc.php');
include_once('../classes/Validation.class.php');
include_once('../classes/Search.class.php');

if (isset($_GET['tag']) && !empty($_GET['tag']) && count($_GET) === 1) {
    $tag = $_GET['tag'];

    $amountOfSearchResults = 0;

//controleer geldige locatie
    $search = new Search();
    $validation = new Validation();

    if ($validation->isValidSearchTerm($tag)) {

        $tag = str_replace('#', '', $tag);
        $tag = str_replace('%23', '', $tag);

        try {
            $search->setMStag($tag);
            $search->setMSUserid($_SESSION['login']['userid']);
            $userPosts = $search->getAllTagPosts();
            $amountOfSearchResults = count($userPosts);
            $search->splitBigNumberAmountOfResults($amountOfSearchResults);
        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    } else {
        header('location: /imdstagram/error/404.php');
    }
} else {
    header('location: /imdstagram/error/404.php');
}


?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Zoeken naar posts met hashtag <?php echo '#' . htmlspecialchars($tag); ?></title>
    <meta name="description">
    <?php include_once('../inc/style.inc.php'); ?>
</head>
<body class="template">

<?php include_once('../inc/header.inc.php'); ?>


<!-- start tag feed -->
<div class="container search">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="centreer tekst"><?php echo '#' . htmlspecialchars($tag); ?></h1>
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
<!-- einde tag feed -->


<?php include_once('../inc/footer.inc.php'); ?>

</body>
</html>