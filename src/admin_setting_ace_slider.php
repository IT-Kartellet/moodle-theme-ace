<?php

class admin_setting_ace_slider extends admin_setting_configselect {
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, null);
    }

    /**
     * Returns XHTML select field and wrapping div(s)
     *
     * @see output_select_html()
     *
     * @param string $data the option to show as selected
     * @param string $query
     * @return string XHTML field and wrapping div
     */
    public function output_html($data, $query='') {
        $content = <<<EOT
<link rel="stylesheet" href="/theme/ace/style/static/jquery.fileupload.css">
<div class="container">
    <!-- The fileinput-button span is used to style the file input field as button -->
    <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Add files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
    <div id="files" class="files" style="margin-top: 5px;"></div>
    <textarea id="thumbs"></textarea>
    <div style="clear: left;">
        <button id="done_button" name="done_button">Done</button>
    </div>
</div>
<script type="text/javascript">
var scripts = ["/theme/ace/javascript/jquery/jquery.ui.widget.js",
    "/theme/ace/javascript/blueimp/load-image.min.js",
    "/theme/ace/javascript/blueimp/canvas-to-blob.min.js",
    "/theme/ace/javascript/bootstrap-3.1.1.min.js",
    "/theme/ace/javascript/jquery/jquery.iframe-transport.js",
    "/theme/ace/javascript/jquery/jquery.fileupload.js",
    "/theme/ace/javascript/jquery/jquery.fileupload-process.js",
    "/theme/ace/javascript/jquery/jquery.fileupload-image.js",
    "/theme/ace/javascript/jquery/jquery.fileupload-audio.js",
    "/theme/ace/javascript/jquery/jquery.fileupload-video.js",
    "/theme/ace/javascript/jquery/jquery.fileupload-validate.js",
    "/theme/ace/javascript/jquery/jquery.ui.widget.js"];
if (window.jQuery) {
    (function() {
    var wf = document.createElement("script");
    wf.src = ("https:" == document.location.protocol ? "https" : "http") +
    "://code.jquery.com/jquery-1.11.0.min.js";
    wf.type = "text/javascript";
    wf.async = "false";
    wf.onload = function() {


        for (script in scripts) {
           console.log(script);
        }

        $.getScript("/theme/ace/javascript/select2/select2.js" );
        $.getScript("/theme/ace/javascript/admin_settings_ace_general.js" );
        $("head").append("<link rel=\"stylesheet\" href=\"/theme/ace/javascript/select2/select2.css\" type=\"text/css\" />");
    };
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(wf, s);
    })();
} else {

}


$(function () {




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
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        $.each(data.files, function (index, file) {
            data.context = $('<div />').css({float: 'left', height: '100px;', width: '100px;', position: 'relative',
                backgroundColor: '#EBEBEB', margin: '0 5px 5px 0'}).appendTo('#files');

            var file = data.files[index];
            data.context.text(file.preview);
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
                            var \$this = $(this);

                            \$this.parent().remove();
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

    $("#done_button").on("click", function() {
        var thumbs = "";

        $("#thumbs").val("");
        $( "#files div input").each(function (id, image) {
            var image = $(image).val();
            if ($("#thumbs").val()) {
                $("#thumbs").val($("#thumbs").val()+","+image+"");
            } else {
                $("#thumbs").val(""+image+"");
            }
        });

        $("#thumbs").val(thumbs);
    });
});
</script>
EOT;

        return format_admin_setting($this, $this->visiblename, $content, null, true,
            null, null, $query);
    }
}