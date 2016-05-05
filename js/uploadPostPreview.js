$(document).ready(function () {

    /*disable select box if browser support javascript*/
    $('select').css('display', 'none');

    /*melding eerst foto toevoegen*/
    $('#label-effect').css('display', 'none');

    /*Array van effecten*/
    var arrEffects = document.getElementById('effect').options;
    //alert(arrEffects[0].value); //=> Value of the first option


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
                    $('#label-effect').css('display', 'block');

                    var previewblock = $("#send");
                    previewblock.before("<div class='wrapper-preview'><div class='preview-gallery'>");

                    for(var i = 0; i < arrEffects.length; i++){
                        $('.preview-gallery').append("<div class='preview-block'><div class='effect-name'>" + arrEffects[i].text + "</div><div class='preview-block-corner'><img class='image-preview " + arrEffects[i].text.toLowerCase() +"'></div>");
                    }

                    $('.preview-gallery').after("</div></div></div>");

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