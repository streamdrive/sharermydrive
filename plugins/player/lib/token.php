<?php
function generateID() {
	$token = base64_encode(openssl_random_pseudo_bytes(72));
   	$token = str_replace('/', '', $token);
	$token = str_replace('+', '', $token);
	$token = str_replace('=', '', $token);
	return $token;
}

$token = generateID();
$_SESSION['player_token'] = $token;
?>