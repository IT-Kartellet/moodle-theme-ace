<?php

/**
 * Fileupload script
 *
 * @package    theme_ace
 * @copyright  2014 IT-Kartellet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
error_reporting(E_ALL | E_STRICT);
session_start();
require('lib/UploadHandler.php');
$upload_handler = new UploadHandler();

?>
