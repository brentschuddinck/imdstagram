$(document).ready(function () {

    function readURL(input) {
        if (input.files && input.files[0]) {

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
                    //toon default foto
                    $('.profielfoto.groot').attr('src', '/imdstagram/img/uploads/profile-pictures/default.png');
                    $('.btn-primary').prop('disabled', true);
                    return false;
                }

            }



            if(checkFileType()){

                $('.btn-primary').prop('disabled', false);

                var reader = new FileReader();

                reader.onload = function (e) {
                    $('.profielfoto.groot').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    }

    $("#fileToUpload").change(function () {
        readURL(this);
    });
});