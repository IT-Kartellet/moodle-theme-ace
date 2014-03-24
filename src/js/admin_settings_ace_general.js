var previewFont = function(){
    var selectedFontsStr = $("select[name='s_theme_ace_font']").val();
    var selectedFonts = selectedFontsStr.split(" / ");

    $("#id_s_theme_ace_font_preview h1").css("font-family", selectedFonts[0]);
    $("#id_s_theme_ace_font_preview span").css("font-family", selectedFonts[1]);
}

$(function() {
    previewFont();
    $("select[name='s_theme_ace_font']").change(function() {
        previewFont();
    });


});