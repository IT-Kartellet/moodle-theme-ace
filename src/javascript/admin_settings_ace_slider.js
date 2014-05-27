(function() {
    var wf = document.createElement("script");
    wf.src = "/theme/ace/javascript/jquery-1.11.0.min.js";
    wf.type = "text/javascript";
    wf.async = "false";
    wf.onload = function() {
        $.getScript("/theme/ace/javascript/select2/select2.js" );
        $.getScript("/theme/ace/javascript/admin_settings_ace_general.js" );
        $.getScript("/theme/ace/javascript/jquery/jquery.fileupload-image.js");
        $("head").append("<link rel=\"stylesheet\" href=\"/theme/ace/javascript/select2/select2.css\" type=\"text/css\" />");
        $("head").append("<link rel=\"stylesheet\" href=\"/theme/ace/style/static/settings.css\" type=\"text/css\" />");

        $.getScript("/theme/ace/javascript/front.js").done(function( script, textStatus ) {
            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = "/theme/ace/fileupload.php",
                uploadError = function(context) {
                    var errorDiv = $("<div />").html("ERROR").css({'textAlign': 'center', width: '100px', marginTop: "40px"});
                    $(context).css({backgroundColor: '#B00000', color: '#FFFFFF', fontWeight: 'bold',
                        width: '100px', height: '100px'})
                        .html('')
                        .append(errorDiv);

                    // Hide error in 2 seconds
                    setTimeout(function () {
                        $(context).remove();
                    }, 2000);
                }

            $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                autoUpload: false,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                maxFileSize: 5000000, // 5 MB

                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                disableImageResize: /Android(?!.*Chrome)|Opera/
                    .test(window.navigator.userAgent),
                previewMaxWidth: 267,
                previewMaxHeight: 143,
                previewCrop: true
            }).on('fileuploadadd', function (e, data) {
                    $.each(data.files, function (index, file) {
                        data.context = $('<div />').css({
                                                        float: 'left',
                                                        height: '100px;',
                                                        width: '100px;',
                                                        position: 'relative',
                                                        backgroundColor: '#EBEBEB',
                                                        margin: '0 5px 5px 0'
                                                        })
                            .appendTo('#files');
                    });
                }).on('fileuploadprocessalways', function (e, data) {
                    var index = data.index,
                        file = data.files[index];
                    if (file.preview) {
                        data.context.append(file.preview);
                    }

                    if (file.error) {
                        uploadError(data.context);

                        // Hide error in 2 seconds
                        setTimeout(function () {
                            $(data.context).remove();
                        }, 2000);
                    } else {
                        var rm = $('<button/>').text('x')
                            .css({textAlign: 'center', fontWeight: 'bold', backgroundColor: '#000000',
                                color: "#FFFFFF", display: 'block', width: '20px', height: '20px',
                                position: 'absolute', top: 0, right: 0, border: 0})
                            .on('click', function () {
                                var $this = $(this);
                                $this.parent().remove();
                            });
                        data.context.append(rm);
                        data.submit();
                    }
                }).on('fileuploaddone', function (e, data) {
                    if (data.result.length) {
                        $.each(data.result, function (index, file64) {
                            if (!file64) {
                                uploadError(data.context);
                            } else {
                                var hidden64 = $("<input />").attr({type: "hidden", value: file64});
                                $(data.context).css({cursor: "pointer"}).append(hidden64);

                                $( "#files" ).sortable();
                                $( "#files" ).disableSelection();
                            }
                        });
                    } else {
                        uploadError(data.context);
                    }
                }).on('fileuploadfail', function (e, data) {
                    $.each(data.files, function (index, file) {
                        uploadError(data.context);
                    });
                }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');

            $(".form-submit").on("click", function(event) {
                var thumbs = [];

                $(sliderID).val("");
                $("#files div input").each(function (id, image) {
                    thumbs.push($(image).val());
                });

                $(sliderID).val(thumbs);

                // For testing
                //event.preventDefault();
                //event.stopPropagation();
            });

            if ($(sliderID).val()) {
                if ($("#id_s_theme_ace_slider2_thumbs").val()) {
                    var images = $("#id_s_theme_ace_slider2").val().split(",");
                    var thumbs = $("#id_s_theme_ace_slider2_thumbs").val().split(",");

                    for (var i in thumbs) {
                        var thumb = thumbs[i];

                        var thumbDiv = $('<div />').css({float: 'left', height: '100px;', width: '100px;', position: 'relative',
                            backgroundColor: '#EBEBEB', margin: '0 5px 5px 0'});

                        var preview = $('<img />').css({width: '267px', height: '143px', cursor: 'pointer'})
                            .attr('src', "data:image/jpeg;base64,"+thumb)
                            .appendTo(thumbDiv);
                        thumbDiv.append(preview);

                        var rm = $('<button/>').text('x')
                            .on('click', function () {
                                var $this = $(this);
                                $this.parent().remove();
                            });
                        thumbDiv.append(rm);

                        $('<input/>').attr("type", "hidden").val(images[i]).appendTo(thumbDiv);

                        $("#files").append(thumbDiv);
                    }

                    $( "#files" ).sortable();
                    $( "#files" ).disableSelection();
                }
            }

        });


    };
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(wf, s);
})();