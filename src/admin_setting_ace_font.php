<?php

class admin_setting_ace_font extends admin_setting_configselect {
    private $supportedFonts;
    private $updateError;

    public function __construct($name, $visiblename, $description, $defaultsetting, $choices, $supportedFonts = null) {
        $this->choices = $choices;
        $this->supportedFonts = $supportedFonts;

        parent::__construct($name, $visiblename, $description, $defaultsetting, $choices);
    }

    /**
     * Returns XHTML select field
     *
     * Ensure the options are loaded, and generate the XHTML for the select
     * element and any warning message. Separating this out from output_html
     * makes it easier to subclass this class.
     *
     * @param string $data the option to show as selected.
     * @param string $current the currently selected option in the database, null if none.
     * @param string $default the default selected option.
     * @param string $extraname the default selected option.
     * $
     * @return array the HTML for the select element, and a warning message.
     */
    public function output_select_html($data, $current, $default, $extraname = '') {
        if (!$this->load_choices() or empty($this->choices)) {
            return array('', '');
        }

        $warning = '';
        if (is_null($current)) {
            // first run
        } else if (empty($current) and (array_key_exists('', $this->choices) or array_key_exists(0, $this->choices))) {
            // no warning
        } else if (!array_key_exists($current, $this->choices)) {
            $warning = get_string('warningcurrentsetting', 'admin', s($current));
            if (!is_null($default) and $data == $current) {
                $data = $default; // use default instead of first value when showing the form
            }
        }

        $selecthtml = '<select id="'.$this->get_id().'" name="'.$this->get_full_name().$extraname.'" style="float: left; width: 200px;">';
        foreach ($this->choices as $key => $value) {
            // the string cast is needed because key may be integer - 0 is equal to most strings!
            $selecthtml .= '<option value="'.$key.'"'.((string)$key==$data ? ' selected="selected"' : '').'>'.$value.'</option>';
        }
        $selecthtml .= '</select>';
        return array($selecthtml, $warning);
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
        $jsLoadFonts = array();
        foreach ($this->supportedFonts as $fontCombo) {
            foreach (preg_split("/\//", $fontCombo) as $font) {
                $jsLoadFonts []= "'".trim($font)."'";
            }
        }


        $default = $this->get_defaultsetting();
        $current = $this->get_setting();

        list($selecthtml, $warning) = $this->output_select_html($data, $current, $default);
        if (!$selecthtml) {
            return '';
        }

        $defaultinfo = NULL;
        if (!is_null($default) and array_key_exists($default, $this->choices)) {
            $defaultinfo = $this->choices[$default];
        }

        $return = '<div class="form-select defaultsnext" style="">' . $selecthtml . '</div>';

        global $CFG;

        // Include JS
        $wwwRoot = $CFG->wwwroot;

        // Because this is admin settings page we assume they are accessing it via a Desktop
        $injectedJS = '<script type="text/javascript">
WebFontConfig = {
     google: { families: [ '.join(", ", $jsLoadFonts).' ] }
};
(function() {
var wf = document.createElement("script");
wf.src = ("https:" == document.location.protocol ? "https" : "http") +
"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
wf.type = "text/javascript";
wf.async = "false";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(wf, s);
})();
(function() {
var wf = document.createElement("script");
wf.src = ("https:" == document.location.protocol ? "https" : "http") +
"://code.jquery.com/jquery-1.11.0.min.js";
wf.type = "text/javascript";
wf.async = "false";
wf.onload = function() {
    $.getScript("/theme/ace/javascript/select2/select2.js" );
    $.getScript("/theme/ace/javascript/admin_settings_ace_general.js" );
    $("head").append("<link rel=\"stylesheet\" href=\"/theme/ace/javascript/select2/select2.css\" type=\"text/css\" />");
};
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(wf, s);
})();
 </script>';

        return format_admin_setting($this, $this->visiblename, $injectedJS.$return, null, true,
            $warning, null, $query);
    }
}