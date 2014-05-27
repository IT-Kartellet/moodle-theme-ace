<?php

/**
 * Ace theme settings page
 *
 * @package    theme_ace
 * @copyright  2014 IT-Kartellet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_ace_slider extends admin_setting_configselect {
    public function __construct($name, $visiblename, $description, $defaultsetting) {
        parent::__construct($name, $visiblename, $description, $defaultsetting, null);
    }

    public function get_setting() {
        return $this->config_read($this->name);
    }

    public function write_setting($data) {
        return ($this->config_write($this->name, $data) ? '' : get_string('errorsetting', 'admin'));
    }

    public function output_html($data, $query='') {
        $id = $this->get_id();
        $thumbsID = $id."_thumbs";
        $name = $this->get_full_name();

        $images = array();
        foreach (preg_split('/,/', $data) as $img) {
            $im = new Imagick();
            $im->readImageBlob(base64_decode($img));
            $im->scaleImage(267, 143, true);
            $thumb = base64_encode($im->getimageblob());
            //$grape .= "<img src=\"data:image/jpeg;base64,$rim\"/>";

            $thumbs [] = $thumb;
        }
        $dataThumbs = join(',', $thumbs);

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
    <input type="hidden" id="$id" name="$name" value="$data" />
    <input type="hidden" id="$thumbsID" value="$dataThumbs" />
</div>
<script type="text/javascript">
    var sliderID = "#$id";
</script>
<script type="text/javascript" src="/theme/ace/javascript/admin_settings_ace_slider.js"></script>
EOT;


        return format_admin_setting($this, $this->visiblename, $content, null, true,
            null, null, $query);
    }
}
