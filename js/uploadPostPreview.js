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

                $('#label-effect').css('display', 'block');

                var previewblock = $("#send");
                previewblock.before("<div class='wrapper-preview'><div class='preview-gallery'>");



                reader.onload = function (e) {



                    var effectselected = "";

                    for(var i = 0; i < arrEffects.length; i++){

                        if(i != 0){
                            effectselected = "";
                        }else{
                            effectselected = "effect-selected";
                        }

                        $('.preview-gallery').append("<div class='preview-block'><div class='effect-name'>" + arrEffects[i].text + "</div><div class='preview-block-corner " + effectselected  + "'><img class='image-preview " + arrEffects[i].text.toLowerCase() +"'></div>");
                    }

                    $('.image-preview').attr('src', e.target.result);
                }

                $('.preview-gallery').after("</div></div></div>");

                reader.readAsDataURL(input.files[0]);
            }

        }
    }

        $("#file").change(function () {
        readURL(this);
    });





    $(document).on('click', '.image-preview', function(){
        //detecteer hoeveelste element geklikt werd
        var selectedEffectElement = $('.image-preview').index(this);
        //alert( $('.image-preview').index(this) );

        //wijzig huidige omkadering naar geselecteerd element
        $(".preview-block-corner").removeClass("effect-selected");
        $(".preview-block-corner").eq(selectedEffectElement).addClass("effect-selected");

        //pas element aan in onzichtbare lijst (wel getoond als js uitgeschakeld is om toch effect te kunnen toevoegen)
        $("#effect").prop('selectedIndex', selectedEffectElement);
    });

});