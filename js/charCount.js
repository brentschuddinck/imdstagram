$(document).ready(function(){
    $('#charCount').text('1000 tekens over');
    $('#description').keyup(function () {
        var max = 1000;
        var len = $(this).val().length;
        if (len >= max) {
            $('#charCount').text('Je beschrijving heeft de limiet bereikt.');
        }
        else {
            var chars = max - len;
            $('#charCount').text(chars + ' tekens over');
        }
    });
});