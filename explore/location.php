<?php
include_once('../inc/sessiecontrole.inc.php');
include_once('../inc/feedbackbox.inc.php');
include_once('../classes/Validation.class.php');
include_once('../classes/Search.class.php');
include_once('../classes/Post.class.php');
include_once('../classes/User.class.php');
include_once('../classes/Comment.class.php');


if (isset($_GET['location']) && !empty($_GET['location'])) {

    $location = $_GET['location'];
    $amountOfSearchResults = 0;

//controleer geldige locatie
    $search = new Search();
    $validation = new Validation();

    if ($validation->isValidSearchTerm($location)) {
        try {
            $search->setMSLocation($location);
            $search->setMSUserid($_SESSION['login']['userid']);
            $userPosts = $search->getAllLocationPosts();
            $amountOfSearchResults = count($userPosts);
            $search->splitBigNumberAmountOfResults($amountOfSearchResults);

            //taglinks opvragen
            $post = new Post();

            //comments ophalen
            $comment = new Comment();

        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    } else {
        header('Location: /imdstagram/error/404.php');
    }






    if (isset($_POST['btnLikePicture'])) {
        $post->likePost();
    }

    if (isset($_GET['click']) && !empty($_GET['click'])) {
        $getclick = $_GET['click'];
        $post->setMSPostId($getclick);
        $post->likePost();
    }



    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        try {
            $deletePostId = $_GET['delete'];
            $post->setMSPostId($deletePostId);
            $postToDelete = $post->getSinglePost();
            if($postToDelete[0]['user_id'] == $_SESSION['login']['userid']){
                if($post->deletePost()){
                    $post->deletePostImage($postToDelete[0]['post_photo']);
                    $feedback = buildFeedbackBox("success", "De post is verwijderd.");
                    header("Location: location.php?location=". $location);
                }
            }else{
                $feedback = buildFeedbackBox("danger", "je kan enkel posts wissen die je zelf geplaatst hebt.");
            }

        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    }


    if (isset($_GET['flag']) && !empty($_GET['flag'])) {
        try {
            $flagPostId = $_GET['flag'];
            $post->setMSPostId($flagPostId);
            $postToFlag = $post->getSinglePost();
            if($post->checkIfUserAlreadyFlagged()){
                if($post->flagPost()){
                    $post->addUsersWhoFlagged();
                    $feedback = buildFeedbackBox("success", "Bedankt! De post is gerapporteerd.");
                    //header('Location: index.php');
                }
            }
        } catch (Exception $e) {
            $feedback = buildFeedbackBox("danger", $e->getMessage());
        }
    }


    if(!empty($_POST['commentPostId']) && !empty($_POST['commentDescription']))    {
        $comment->setMSComment($_POST['commentDescription']);
        $comment->setMIUserId($_SESSION['login']['userid']);
        $comment->setMIPostId($_POST['commentPostId']);
        $comment->postComment();
    }












}
else{
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
            <?php
            $post->setMSPostId($userPost['post_id']);
            $cleanPostDescription = htmlspecialchars($userPost['post_description']);
            $postDescription = $cleanPostDescription;
            if($post->doesStringContain($postDescription, '#')){
                $postDescription = $post->hashtag_links($postDescription);
            }
            ?>
            <div class="col-xs-12 col-sm-4 col-md-3">

                <a href="/imdstagram/detail.php?location=<?php echo htmlspecialchars($location) ?>&postid=<?php echo $userPost['post_id'] ?>"
                   data-toggle="lightbox"
                   data-gallery="multiimages"
                   class="thumbnail picturelist">
                    <img src="/imdstagram/img/uploads/post-pictures/<?php echo $userPost['post_photo']; ?>" class="img-responsive <?php echo $userPost['photo_effect']; ?>">
                </a>

            </div>


        <?php endforeach; ?>
    <?php endif; ?>
    <!-- einde lijst zoekresultaten -->


</div class="container search">
<!-- einde location feed -->


<?php include_once('../inc/footer.inc.php'); ?>
<!-- lightbox required components -->
<script src="/imdstagram/js/lightbox-style.js"></script>
<script src="/imdstagram/js/lightbox-call.js"></script>

</body>
</html>