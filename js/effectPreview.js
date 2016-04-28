$(document).ready(function () {
    $("select#effect").change(function () {
        var effectName = $("select#effect").val();
        $(".image-preview").attr('class', 'image-preview ' + effectName);
    });
});