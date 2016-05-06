$(document).ready(function(){

    //htmlspecialchars equivalent
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }


    $(".comment").on("click", function(e){

        var link = $(this);
        var postId = link.data('id');

        var commentbad = $('#commentDescription').val();
        var comment = escapeHtml(commentbad);

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