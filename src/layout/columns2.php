<?php require 'helper.php';?>
<?php echo $OUTPUT->doctype();?>
<html <?php echo $OUTPUT->htmlattributes();?>>
<head>
    <title><?php echo $OUTPUT->page_title();?></title>
    <link rel="shortcut icon" href="<?=$OUTPUT->favicon()?>" />
    <?php echo $OUTPUT->standard_head_html();?>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>
<body <?php echo $OUTPUT->body_attributes();?>>
<?php echo getAceCSS() ?>
<?php echo $OUTPUT->standard_top_of_body_html();?>

<div id="primary_wrap" class="columns-2">
<div id="secondary_wrap">

<header id="main_header" role="banner" class="container-fluid">
    <div id="mobile_header" class="visible-mobile">
        <div id="mobile_header_left">
            <span class="pull-left">Menu</span>
            <button class="pull-right navigate_content"><i class="icon-right-open"></i></button>
        </div>
        <div id="mobile_header_middle">
            <button class="pull-left navigate_menu"><i class="icon-menu-1"></i></button>
            <span id="mobile_middle_title">Home</span>
        </div>
    </div>

    <div class="row-fluid hidden-mobile">
        <a id="logo_wrap" class="span2 hidden-mobile" href="<?php echo $CFG->wwwroot; ?>">
            <?php if ($PAGE->theme->settings->logo): ?>
                <?php
                $fs = get_file_storage();
                $files = $fs->get_area_files(1, 'theme_ace', 'logo', 0, 'filesize DESC');

                echo "<img id=\"logo\" src='";
                if (sizeof($files)) {
                    $file = current($files);
                    $filename = $file->get_filename();

                    echo file_encode_url($CFG->wwwroot.'/pluginfile.php', '/1/theme_ace/logo/0/'.$filename);
                } else {
                    echo $CFG->wwwroot.'/theme/'.$PAGE->theme->name.'/pix/logo.svg';
                }
                echo "' />";

                ?>
            <?php else: ?>
                <img id="logo" src='<?=$CFG->wwwroot.'/theme/'.$PAGE->theme->name."/pix/logo.svg"?>' />
            <?php endif; ?>
        </a>
    </div>
</header>

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
        </div>
    </div>
</div>

<?php echo getAceFooter(); ?>

</div>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
