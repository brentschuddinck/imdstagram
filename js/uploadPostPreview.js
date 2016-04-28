$(document).ready(function () {
    function readURL(input) {
        if (input.files && input.files[0]) {

            $("div.wrapper-preview").remove(); //remove potential previews
            var fileName = input.files[0].name; //var with filename.extention

            /*function to check if file validates by extention. Is not so secure as our checks in PHP, but is not necessary here.
            * Nothing will be uploaded. Only the file will be presented as a preview for the user. Server has nothing to do with the preview.
            * Extra validation is when someone publish his post.
            * */
            function checkFileType() {
                var validFileType = false;
                var regexFileType = /(?:\.([^.]+))?$/; //whats my extention?
                var checkValidFileType = regexFileType.exec(fileName)[1];
                var validFileTypes = ["jpeg", "png", "gif", "jpg"]; //which file types are valid?

                for(var i = 0; i < validFileTypes.length; i++){
                    if(validFileTypes[i] === checkValidFileType){
                        validFileType = true;
                    }
                }

                if(validFileType){
                    return true;
                }else{
                    return false;
                }

            }



            if(checkFileType()){
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#send').append("<div class='wrapper-preview'><h2>Preview foto</h2><img class='image-preview'></div>");
                    $('.image-preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    $("#file").change(function () {
        readURL(this);
    });
});