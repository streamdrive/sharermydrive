<?php
if(!isset($_SESSION)) session_start();
require_once('config/config.php');
require_once('fpdo/autoload.php');
require_once('yuudrive/yuudrive.core.php');
require_once('yuudrive/yuudrive.function.php');
require_once('yuudrive/yuudrive.class.php');

$YuuClass = new YuuDrive\YuuDrive_db();

if(is_login()) {
    $_user = json_decode($_COOKIE['user'], true);
    if(in_array(basename($_SERVER['PHP_SELF']), ['file.php', 'upload.php', 'upload-drive.php'])) {
        check_renew_token($_user);
    }
}
if($app['debug']==0) error_reporting(E_ALL);
?>