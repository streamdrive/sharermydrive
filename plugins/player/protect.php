<?php
header('Content-Type:text/plain');
header('Access-Control-Allow-Origin: yuudrive.me, localhost');
require_once(__DIR__.'/lib/cURL.php');
require_once(realpath($_SERVER['DOCUMENT_ROOT'].'/library/autoload.php'));

$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if($isAjax) {
	if (isset($_GET['t_player']) && $_GET['t_player'] == $_SESSION['player_token']) {
		$file_id 	= $_GET['id'];
		$file 		= $YuuClass->get_file($file_id);
		$url = "https://www.googleapis.com/drive/v3/files/$file[file_id]?alt=media&key=AIzaSyBpGu8j3PJI_wNuohCIodyFV-T0-VBEh0U";
		return print $url;
	}
} else {
	print json_encode(array(
		'error' => true,
		'code' => 403
	));
}
?>