<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../inc/feedbackbox.inc.php');
include_once('../classes/Validation.class.php');
include_once('../classes/Search.class.php');
include_once('../classes/Post.class.php');
include_once('../classes/User.class.php');

if (isset($_GET['tag']) && !empty($_GET['tag']) && count($_GET) === 1) {
    $tag = $_GET['tag'];

    $amountOfSearchResults = 0;

//controleer geldige locatie
    $search = new Search();
    $validation = new Validation();
    $post = new Post();
    $user = new User();

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

        <?php foreach ($userPosts as $userPost) : ?>
            <?php $userPost['post_description'] = htmlspecialchars($userPost['post_description']); ?>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <a href="/imdstagram/img/uploads/post-pictures/<?php echo $userPost['post_photo']; ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php /*echo "<a><img  class='img-circle img-circle-detail' src='/imdstagram/img/uploads/profile-pictures/". htmlspecialchars($userPost['profile_picture']) ."'></a>" . "</a>"  . "<a href='/imdstagram/explore/profile.php?user=". htmlspecialchars($userPost['username']) ."'>" . htmlspecialchars($userPost['username']) . "</a>";*/ ?>" data-footer="<?php echo $post->hashtag_links(htmlspecialchars($userPost['post_description'])); ?>" class="thumbnail picturelist">
                    <img src="/imdstagram/img/uploads/post-pictures/<?php echo $userPost['post_photo']; ?>" class="img-responsive <?php echo $userPost['photo_effect']; ?>">
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- einde lijst zoekresultaten -->


</div>
<!-- einde tag feed -->


<?php include_once('../inc/footer.inc.php'); ?>
<!-- lightbox required components -->
<script src="/imdstagram/js/lightbox-style.js"></script>
<script src="/imdstagram/js/lightbox-call.js"></script>
</body>
</html>