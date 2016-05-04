$(document).ready(function(){


    $(".comment").on("click", function(e){

        var link = $(this);
        var postId = link.data('id');

        var comment = $('#commentDescription').val();

        //ajax call
        if(comment.length > 0) {
            $.post("ajax/comment-on-post.php", {id: postId, comment: comment}).done(function (response) {
                if (response.status == "working") {

                   var userName = response.name;
                   var userImg = response.profileImg;


                    $('#commentDescription').val(" ");
                    var element = " <div class='box-footer box-comments'><div class='box-comment'><a href='explore/profile.php?user=" + userName + "'><img class='img-circle img-sm' src='img/uploads/profile-pictures/" + userImg + "' alt='User Image'><div class='comment-text'> <span class='username'>" + userName +  "</a> <span class='text-muted pull-right'>zojuist</span>" + comment + "</div> </div> </div>"
                    $('#allComments').append(element);
                }
            });
        }
        e.preventDefault();
    })

});