<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Comment.class.php');


$post = new Post();
$showPosts = $post->getAllPosts();



if (empty($showPosts)) {
    $feedback = buildFeedbackBox("leeg", "vul je tijdlijn door <a href='upload.php'>foto's toe te voegen</a> en vrienden te volgen. Je kan vrienden, locaties en tags zoeken via het zoekveld bovenaan de pagina.");
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
                header("Location: index.php");
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


if(isset($_POST['commentPostId']) && isset($_POST['commentDescription']) && !empty($_POST['commentPostId']) && !empty($_POST['commentDescription'])) {
    $comment->setMSComment($_POST['commentDescription']);
    $comment->setMIUserId($_SESSION['login']['userid']);
    $comment->setMIPostId($_POST['commentPostId']);
    $comment->postComment();
}





?><!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>IMDstagram tijdlijn</title>
    <meta name="description"
          content="IMDstagram is dé creative place to be voor IMD studenten. IMDstagram geeft je inspiratie een boost door creatieve en inspirerende afbeeldingen te delen met andere studenten.">
    <?php include_once('inc/style.inc.php'); ?>
    <style>
        body {
            background-color: #ecf0f5;
        }
    </style>

</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->

<div class="container bootstrap">
    <div class="col-sm-12 col-md-8 col-md-offset-2">
        <?php if (isset($feedback) && !empty($feedback)) { echo $feedback; } ?>
        <?php foreach ($showPosts as $showPost): ?>
        <?php
        $post->setMSPostId($showPost['post_id']);
        $comment = new Comment();
        $comment->setMIPostId($showPost['post_id']);
        $postComments = $comment->getAllComments();
        $cleanPostDescription = htmlspecialchars($showPost['post_description']);
        $postDescription = $cleanPostDescription;
        if($post->doesStringContain($postDescription, '#')){
            $postDescription = $post->hashtag_links($postDescription);
        }
        ?>

        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="user-block">
                    <a href="/imdstagram/explore/profile.php?user=<?php echo htmlspecialchars($post->usernameFromPost()); ?>"><img
                            class="img-circle"
                            src="img/uploads/profile-pictures/<?php echo htmlspecialchars($post->userImgFromPost()); ?>"
                            alt="User Image"></a>
                        <span class="username"><a
                                href="/imdstagram/explore/profile.php?user=<?php echo htmlspecialchars($post->usernameFromPost()); ?>"><?php echo htmlspecialchars($post->usernameFromPost()); ?></a></span>
                        <span
                            class="description"><?php echo $post->timePosted($showPost['post_date']); ?> <?php echo !empty($showPost['post_location']) ? '-' : '' ?>
                            <span
                                class="<?php echo !empty($showPost['post_location']) ? 'fa fa-map-marker' : '' ?>"><?php echo " <a href='explore/location.php?location=" . htmlspecialchars($showPost['post_location']) . "'>" . htmlspecialchars($showPost['post_location']) . "</a>"; ?></span></span>
                </div>
                <div class="box-tools">
                    <div
                        class="<?php echo $showPost['user_id'] != $_SESSION['login']['userid'] ? 'hidden' : 'show' ?>">
                        <a href="?flag=<?php echo $showPost['post_id']; ?>" type="button" class="btn btn-box-tool"
                           title="post rapporteren" data-target="#confirm-flag"><i
                                class="fa fa-exclamation fa-lg flagpost"></i></a>
                    </div>
                    <div
                        class="<?php echo $showPost['user_id'] != $_SESSION['login']['userid'] ? 'show' : 'hidden' ?>">
                        <a href="?delete=<?php echo $showPost['post_id']; ?>" type="button" class="btn btn-box-tool"
                           title="post verwijderen" data-target="#confirm-delete"><i
                                class="fa fa-trash-o fa-lg deletepost"></i></a>
                    </div>
                </div>


            </div>
            <div class="box-body">
                <img class="img-responsive pad <?php echo htmlspecialchars($showPost['photo_effect']); ?>"
                     src="img/uploads/post-pictures/<?php echo $showPost['post_photo'] ?>" alt="Photo">
                <p><?php echo $postDescription; ?></p>
                <a href="?click=<?php echo $showPost['post_id']; ?>" data-id="<?php echo $showPost['post_id'] ?>"
                   class="likeBtn btn btn-sm <?php echo $post->isLiked() == true ? 'liked ' : 'btn-default ' ?>"><i
                        class="fa fa-heart-o fa-lg"></i> vind ik leuk</a>
                    <span
                        class="pull-right text-muted showLikes"><?php echo $post->showLikes(); ?><?php echo $post->showLikes() == 1 ? ' like' : ' likes' ?> </span>
            </div>
            <div id="commentId<?php echo $showPost['post_id']; ?>">
            <?php foreach( $postComments as $postComment){ ?>
                <div class="box-footer box-comments">

                <div class="box-comment">
                        <a href="explore/profile.php?user=<?php echo $postComment['username']; ?>">
                            <img class="img-circle img-sm"
                                 src="img/uploads/profile-pictures/<?php echo $postComment['profile_picture']; ?>"
                                 alt="User Image">
                            <div class="comment-text">
                          <span class="username"><?php echo $postComment['username']; ?>
                        </a>
                        <span class="text-muted pull-right"><?php echo $post->timePosted($postComment['comment_date']); ?></span>
                        <?php echo htmlspecialchars($postComment['comment_description']); ?>
                    </div>
                </div>
            </div>

    <?php } ?>
        </div>

        <div class="box-footer">
            <form action="" method="post">
                <img class="img-responsive img-circle img-sm"
                     src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>"
                     alt="Alt Text">
                <div class="img-push">
                    <input type="hidden" name="commentPostId" value="<?php echo $showPost['post_id'] ?>">
                    <input id="commentDescription<?php echo $showPost['post_id'] ?>" required="Je comment bevat nog geen tekst." type="text" name="commentDescription" class="form-control input-sm" placeholder="Schrijf een reactie...">
                    <button data-id="<?php echo $showPost['post_id'] ?>" name="submit" class="btn btn-success green comment"><i
                            class="reply"></i>Reageer
                    </button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach ?>
    <?php /*if (count($showPosts) > 2) :*/ ?>
    <!--<a href="#" class="btn btn-primary btn-more-results btn-md">Toon meer resultaten</a>-->
    <?php /*endif;*/ ?>
</div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>
<script src="js/ajax/liking-a-post.js"></script>
<script src="js/ajax/comment-on-post.js"></script>

</body>
</html>