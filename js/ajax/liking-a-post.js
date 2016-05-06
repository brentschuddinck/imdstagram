$(document).ready(function(){
    $(".likeBtn").on("click", function(e){

        var link = $(this);
        var postId = link.data('id');

        //ajax call
        $.post("/imdstagram/ajax/like-photo.php", {id: postId}).done(function(response) {
            if(response.status == "working"){
                if(response.like == 1){
                    link.next(".showLikes").text(response.like + " " + "like");
                }else{
                    link.next(".showLikes").text(response.like + " " + "likes");
                }
                if(response.liked == true){
                    link.removeClass('btn-default');
                    link.addClass('liked');
                }else{
                    link.removeClass('liked');
                    link.addClass('btn-default');

                }
            }
        });
        e.preventDefault();
    })

});