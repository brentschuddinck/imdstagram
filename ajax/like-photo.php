<?php
include_once("../classes/Post.class.php");
include_once('../inc/sessiecontrole.inc.php');

if(!empty($_POST)){
    try{
        $post = new Post();
        $currentPostId = $_POST['id'];
        $post->setMSPostId($_POST['id']);
        $post->likePost();
        $like = $post->showLikes();
        $liked = $post->isLiked();

        $response['like'] = $like;
        $response['liked'] = $liked;
        $response['status'] = "working";
    }catch (Exception $e){
        $response['status'] = "not working";
        $response['message'] = $e->getMessage();
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}