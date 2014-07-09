<?php

function getAceFontCSS() {
    global $PAGE;

    if ($PAGE->theme->settings->csscustom or $PAGE->theme->settings->font) {
        if ($PAGE->theme->settings->font) {
            $fontCombo = preg_split("/\//", $PAGE->theme->settings->font);
            if ($fontCombo && is_array($fontCombo) and (sizeof($fontCombo) == 2)) {
                $primaryFont = trim($fontCombo[0]);
                $secondaryFont = trim($fontCombo[1]);

                if (($primaryFont != 'Droid Serif') and ($secondaryFont != 'serid')) {
                    return array($primaryFont, $secondaryFont);
                }
            }
        }
    }

    return false;
}

function getAceCSS() {
    global $PAGE;

    if (isset($PAGE->theme->settings->csscustom) or isset($PAGE->theme->settings->font)) {
        $inlineCSS = "<style>\n";
        $fonts = getAceFontCSS();

        if ($fonts) {
            $inlineCSS .= "body {\n\tfont-family: \"".$fonts[0]."\";\n}\n";
            $inlineCSS .= "legend, label, .block .header .title h2, .block h3, h1, h2, h3, h4, h5, h6 {\n\tfont-family: \"".$fonts[1]."\";\n}\n";
        }

        $inlineCSS .= $PAGE->theme->settings->csscustom."\n";
        $inlineCSS .= "</style>\n";

        return $inlineCSS;
    }

    return false;
}

function getAceFooter() {
	global $OUTPUT, $PAGE;

	$html = '<footer id="page-footer">
    <div id="course-footer">'.$OUTPUT->course_footer().'</div>';
    
	if (isset($PAGE->theme->settings->footer)) {
        	$html .= $OUTPUT->login_info();
	        $html .= $PAGE->theme->settings->footer;
    	} else {
        	$html .= "<p class=\"helplink\">".$OUTPUT->page_doc_link()."</p>";
        	$html .= $OUTPUT->login_info();
	        $html .= $OUTPUT->standard_footer_html();
        }
	
	$html .= '</footer>';

	return $html;
}

?>
