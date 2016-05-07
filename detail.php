<?php include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');
include_once('classes/Comment.class.php');

$category = "";
if(isset($_GET['location'])){
    $category = "location";
    $getcategory = htmlspecialchars($_GET[$category]);
}else if(isset($_GET['tag'])){
    $category = "tag";
    $getcategory = htmlspecialchars($_GET[$category]);



}else if(isset($_GET['profile'])){
    $category = "user";
    $getcategory = htmlspecialchars($_GET["profile"]);
}else{
    header('location: /imdstagram/error/404.php');
}


if (isset($_GET['postid']) && !empty($_GET['postid'])) {
    try {
        $post = new Post();
        $post->setMSPostId($_GET['postid']);
        $showPosts = $post->getSinglePostDetail();
    } catch (Exception $e) {
        $feedback = $e->getMessage();
    }
}


if (empty($showPosts)) {
    //header('Location: error/404.php');
}


if (!empty($_POST['commentPostId']) && !empty($_POST['commentDescription'])) {
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


<!-- start photowall -->

    <div class="detailbox">
        <?php if (isset($feedback) && !empty($feedback)) {
            echo $feedback;
        } ?>
        <?php foreach ($showPosts as $showPost): ?>
        <?php
        $post->setMSPostId($showPost['post_id']);
        $comment = new Comment();
        $comment->setMIPostId($showPost['post_id']);
        $postComments = $comment->getAllComments();
        $cleanPostDescription = htmlspecialchars($showPost['post_description']);
        $postDescription = $cleanPostDescription;
        if ($post->doesStringContain($postDescription, '#')) {
            $postDescription = $post->hashtag_links($postDescription);
        }
        ?>

        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="user-block">
                    <a href="/imdstagram/explore/profile.php?user=<?php echo htmlspecialchars($post->usernameFromPost()); ?>"><img
                            class="img-circle"
                            src="/imdstagram/img/uploads/profile-pictures/<?php echo htmlspecialchars($post->userImgFromPost()); ?>"
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
                        class="<?php echo $showPost['user_id'] != $_SESSION['login']['userid'] ? 'show' : 'hidden' ?>">
                        <a href="?<?php echo $category ?>=<?php echo $getcategory ?>&flag=<?php echo $showPost['post_id']; ?>" type="button" class="btn btn-box-tool"
                           title="post rapporteren" data-target="#confirm-flag"><i
                                class="fa fa-exclamation fa-lg flagpost"></i></a>
                    </div>
                    <div
                        class="<?php echo $showPost['user_id'] != $_SESSION['login']['userid'] ? 'hidden' : 'show' ?>">
                        <a href="?<?php echo $category ?>=<?php echo $getcategory ?>&delete=<?php echo $showPost['post_id']; ?>" type="button" class="btn btn-box-tool"
                           title="post verwijderen" data-target="#confirm-delete"><i
                                class="fa fa-trash-o fa-lg deletepost"></i></a>
                    </div>
                </div>


            </div>
            <div class="box-body">
                <img class="img-responsive pad <?php echo htmlspecialchars($showPost['photo_effect']); ?>"
                     src="/imdstagram/img/uploads/post-pictures/<?php echo $showPost['post_photo'] ?>" alt="Photo">
                <p><?php echo $postDescription; ?></p>
                <a href="?<?php echo $category ?>=<?php echo $getcategory ?>&click=<?php echo $showPost['post_id']; ?>" data-id="<?php echo $showPost['post_id'] ?>"
                   class="likeBtn btn btn-sm <?php echo $post->isLiked() == true ? 'liked ' : 'btn-default ' ?>"><i
                        class="fa fa-heart-o fa-lg"></i> vind ik leuk</a>
                    <span
                        class="pull-right text-muted showLikes"><?php echo $post->showLikes(); ?><?php echo $post->showLikes() == 1 ? ' like' : ' likes' ?> </span>
            </div>
            <div id="allComments">
                <?php foreach ($postComments as $postComment){ ?>
                <div class="box-footer box-comments">
                    <div class="box-comment">
                        <a href="/imdstagram/explore/profile.php?user=<?php echo $postComment['username']; ?>">
                            <img class="img-circle img-sm"
                                 src="/imdstagram/img/uploads/profile-pictures/<?php echo $postComment['profile_picture']; ?>"
                                 alt="User Image">
                            <div class="comment-text">
                          <span class="username"><?php echo $postComment['username']; ?>
                        </a>
                        <span
                            class="text-muted pull-right"><?php echo $post->timePosted($postComment['comment_date']); ?></span>
                        <?php echo $postComment['comment_description']; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <!--<div class="box-footer">
            <form action="" method="post">
                <img class="img-responsive img-circle img-sm"
                     src="/imdstagram/img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>"
                     alt="Alt Text">
                <div class="img-push">
                    <input type="hidden" name="commentPostId" value="<?php echo $showPost['post_id'] ?>">
                    <input id="commentDescription" type="text" name="commentDescription" class="form-control input-sm"
                           placeholder="Schrijf een reactie...">
                    <button data-id="<?php echo $showPost['post_id'] ?>" name="submit"
                            class="btn btn-success green comment"><i
                            class="reply"></i>Reageer
                    </button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>-->
    </div>
    <?php endforeach ?>
    <?php /*if (count($showPosts) > 2) :*/ ?>
    <!--<a href="#" class="btn btn-primary btn-more-results btn-md">Toon meer resultaten</a>-->
    <?php /*endif;*/ ?>
</div>
<!-- einde photowall -->
<script src="/imdstagram/js/jquery.min.js"></script>
<script src="/imdstagram/js/ajax/liking-a-post.js"></script>
<script src="/imdstagram/js/ajax/comment-on-post.js"></script>

</body>
</html>