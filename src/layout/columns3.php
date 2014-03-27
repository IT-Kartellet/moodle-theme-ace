<?php echo $OUTPUT->doctype();?>
<html <?php echo $OUTPUT->htmlattributes();?>>
<head>
    <title><?php echo $OUTPUT->page_title();?></title>
    <link rel="shortcut icon" href="<?=$OUTPUT->favicon()?>" />
    <?php echo $OUTPUT->standard_head_html();?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body <?php echo $OUTPUT->body_attributes();?>>
<?php echo $OUTPUT->standard_top_of_body_html();?>

<div id="primary_wrap" class="columns-3">
<div id="secondary_wrap">

<header id="main_header" role="banner" class="container-fluid">
    <div id="mobile_header" class="visible-mobile">
        <div id="mobile_header_left">

        </div>
        <div id="mobile_header_middle">

        </div>
        <div id="mobile_header_right">

        </div>
    </div>
    <div class="row-fluid">
        <a id="logo_wrap" class="span2" href="<?php echo $CFG->wwwroot; ?>">
            <img id="logo" src='<?=$CFG->wwwroot.'/theme/'.$PAGE->theme->name."/pix/logo.svg"?>'>
        </a>
    </div>
</header>

<div class="visible-mobile">
    <button id="show_navigation">Left</button>
    <button id="show_sidebar">Right</button>
    <button id="show_content">Middle</button>
</div>

<div id="page" class="container-fluid">
    <div id="page-content" class="row-fluid">
        <div id="page_wrapper" class="span12">
            <?php echo $OUTPUT->blocks('side-pre', '');?>
            <section id="region-main">
                <?php
                    echo $OUTPUT->course_content_header();
                    echo $OUTPUT->main_content();
                    echo $OUTPUT->course_content_footer();
                ?>
            </section>
            <?php echo $OUTPUT->blocks('side-post', '');?>
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
</div>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>