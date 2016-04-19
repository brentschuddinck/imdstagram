<?php
include_once('inc/sessiecontrole.inc.php');
include_once('classes/Post.class.php');
include_once('inc/feedbackbox.inc.php');

$getPosts = new Post();
$showPosts = $getPosts->getAllPosts();
if(isset($_POST['btnLikePicture'])) {
    $getPosts->likePost();

}

if(!empty($_GET) ){
    $getclick = $_GET['click'];
    $getPosts->setMSPostId($getclick);
    $getPosts->likePost();
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body class="template">

<?php include_once('inc/header.inc.php'); ?>


<!-- start photowall -->

<div class="container bootstrap">
    <div class="col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
        <h2>Tijdlijn</h2>
        <?php foreach($showPosts as $showPost): ?>
            <?php $getPosts->setMSPostId($showPost['post_id']);?>


            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="user-block">
                        <img class="img-circle" src="img/uploads/profile-pictures/<?php echo $getPosts->userImgFromPost(); ?>" alt="User Image">
                        <span class="username"><a href="/imdstagram/account/profile.php?user=<?php echo $getPosts->usernameFromPost();?>"><?php echo $getPosts->usernameFromPost(); ?></a></span>
                        <span class="description"><?php echo $showPost['post_date']; ?> - locatie</</span>
                    </div>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Mark as read">
                            <i class="fa fa-circle-o"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <img class="img-responsive pad" src="img/uploads/post-pictures/<?php echo $showPost['post_photo'] ?>" alt="Photo">
                    <p><?php echo $showPost['post_description'] ?></p>
                    <a href="?click=<?php echo $showPost['post_id'];?>" data-id="<?php echo $showPost['post_id'] ?>" class="likeBtn btn btn-xs <?php echo $getPosts->isLiked() == true ? 'liked ' : 'btn-default '?>"><i class="fa fa-thumbs-o-up"></i> vind ik leuk</a>
                        <span class="pull-right text-muted showLikes"><?php echo $getPosts->showLikes();?> <?php echo $getPosts->showLikes() == 1 ? 'like' : 'likes' ?> </span>
                </div>
                <div class="box-footer box-comments">
                    <div class="box-comment">
                        <img class="img-circle img-sm" src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>" alt="User Image">
                        <div class="comment-text">
          <span class="username">anyone
          <span class="text-muted pull-right">8:03</span>
          </span>comment 1</div>
                    </div>

                    <div class="box-comment">
                        <img class="img-circle img-sm" src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>" alt="User Image">
                        <div class="comment-text">
          <span class="username">Someone
          <span class="text-muted pull-right">8:03</span>
          </span>comment 2
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <form action="#" method="post">
                        <img class="img-responsive img-circle img-sm" src="img/uploads/profile-pictures/<?php echo $_SESSION['login']['profilepicture']; ?>" alt="Alt Text">
                        <div class="img-push">
                            <input type="text" class="form-control input-sm" placeholder="Schrijf een reactie op deze foto...">
                            <button type="submit" name="submit" class="btn btn-success green comment"><i class="reply"></i>Reageer</button>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<!-- einde photowall -->


<?php include_once('inc/footer.inc.php'); ?>

    <script src="js/ajax/liking-a-post.js"></script>
</body>
</html>