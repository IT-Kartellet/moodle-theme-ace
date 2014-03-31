<?php echo $OUTPUT->doctype();?>
<html <?php echo $OUTPUT->htmlattributes();?>>
<head>
    <title><?php echo $OUTPUT->page_title();?></title>
    <link rel="shortcut icon" href="<?=$OUTPUT->favicon()?>" />
    <?php echo $OUTPUT->standard_head_html();?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body <?php echo $OUTPUT->body_attributes();?>>
<?php if ($PAGE->theme->settings->csscustom or $PAGE->theme->settings->font): ?>
    <style>
        <?php
            if ($PAGE->theme->settings->font):
                $fontCombo = preg_split("/\//", $PAGE->theme->settings->font);
                if ($fontCombo && is_array($fontCombo) and (sizeof($fontCombo) == 2)):
                    $primaryFont = trim($fontCombo[0]);
                    $secondaryFont = trim($fontCombo[1]);

                    if (($primaryFont != 'Droid Serif') and ($primaryFont != 'serid')): ?>
        body {
            font-family: "<?php echo $primaryFont; ?>";
        }
        legend, label, .block .header .title h2, .block h3, h1, h2, h3, h4, h5, h6 {
            font-family: "<?php echo $secondaryFont; ?>";
        }
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
        <?php echo $PAGE->theme->settings->csscustom; ?>
    </style>
<?php endif; ?>
<?php echo $OUTPUT->standard_top_of_body_html();?>

<div id="primary_wrap" class="columns-1">
<div id="secondary_wrap">

<header id="main_header" role="banner" class="container-fluid">
    <div id="mobile_header" class="visible-mobile">
        <div id="mobile_header_middle">
            <span id="mobile_middle_title">Home</span>
        </div>
    </div>

    <div class="row-fluid hidden-mobile">
        <a id="logo_wrap" class="span2 hidden-mobile" href="<?php echo $CFG->wwwroot; ?>">
            <img id="logo" src='<?=$CFG->wwwroot.'/theme/'.$PAGE->theme->name."/pix/logo.svg"?>'>
        </a>
    </div>
</header>

<div id="page" class="container-fluid">
    <div id="page-content" class="row-fluid">
        <div id="page_wrapper" class="span12">
            <section id="region-main">
                <a id="logo_wrap" class="span2 visible-mobile" href="<?php echo $CFG->wwwroot; ?>">
                    <img id="logo" src='<?=$CFG->wwwroot.'/theme/'.$PAGE->theme->name."/pix/logo.svg"?>'>
                </a>
                <?php
                    echo $OUTPUT->course_content_header();
                    echo $OUTPUT->main_content();
                    echo $OUTPUT->course_content_footer();
                ?>
            </section>
        </div>
    </div>
</div>

<footer id="page-footer">
    <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
    <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
    <?php
        echo $OUTPUT->login_info();
        echo $OUTPUT->standard_footer_html();
    ?>
</footer>

</div>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
