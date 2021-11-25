<?php
header('Content-type:text/plain');
header('Access-Control-Allow-Origin: *'); 	
header('Access-Control-Allow-Methods: GET, POST'); 	
require_once('lib/Google_shorten_API.php');

$googl = new Google_Shorten();
$googl->setApiKey('AIzaSyCjFuDD1KL7tPVks9boDJMplLUnV3PREgY');
$googl->setSSL(false);
$googl->setProjection('ANALYTICS_CLICKS');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if(isset($_REQUEST['url'])) {
		http_response_code(200);
		$_URL = $_REQUEST['url'];
	    print $googl->shortURL($_URL);
	} else {
		http_response_code(400);
		print('Wrong Parameters');
	}
} else {
	http_response_code(400);
	print('Wrong Request Method');
}

?>