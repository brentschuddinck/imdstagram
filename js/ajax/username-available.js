$(document).ready(function () {

    //variabelen init
    var userinput = $("#inputUsername");
    var userinputbox = $("#available-username");
    var feedbackbox = $(".ajax-feedback.msg");

    //feedbackbox boorbereiden. Beter hier zetten (maar 1x aanroepen)
    feedbackbox.css("display", "block");

    //na keyup, 1s wachten voordat query db wordt uitgevoerd. Zo voorkomen dat bij elke letters direct een query wordt uitgevoerd
    var timer = null;
    userinput.on("keyup", function(e) {
        clearTimeout(timer);
        timer = setTimeout(checkUsernameAvailable, 1000);
        e.preventDefault();
    });


    function checkUsernameAvailable() {
        var username = userinput.val();

        //AJAX Call
        $.post("/imdstagram/ajax/check-username.php", {username: username}).done(function (response) {

            //bestaande klassen leegmaken om kleurfeedbackproblemen te vermeiden
            if(userinputbox.hasClass("available")){
                userinputbox.removeClass("available");
            }else if(userinputbox.hasClass("has-error")){
                userinputbox.removeClass("has-error");
            }


            if (response.status == 'available') {
                //username is beschikbaar OF username = huidige gebruikersnaam
                userinputbox.addClass("has-success");
                var feedbackstatus = "available";
            } else {
                //username is niet meer beschikbaar
                userinputbox.addClass("has-error");
                var feedbackstatus = "error";
            }


            //classenamen leegmaken. Iets beter dan met lussen te checken, dan die leeg te maken en dan op te vullen. Dit is korter
            feedbackbox.attr("class", "cleanstate");
            //standaard klasse toevoegen
            feedbackbox.addClass("ajax-feedback msg " + feedbackstatus);
            feedbackbox.html(response.message);


        }); //einde AJAX call

    }


});