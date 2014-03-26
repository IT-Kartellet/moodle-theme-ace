$(function() {
    function format(font) {
        var fonts = font.id.split(' / ');

        return "<b style=\"font-family: "+fonts[0]+";\">"+fonts[0]+"</b><br /><span style=\"font-family: "+fonts[1]+";\">"+fonts[1]+"</span>";

        return /*"<span style=\"font-family: '"+fonts[*/font.id/*]+"';\">"+fonts[font.id]+"</span>"*/;
    }

    $("select[name='s_theme_ace_font']").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; }
    });
});