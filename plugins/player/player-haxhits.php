<?php error_reporting(0);
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(realpath(DOCUMENT_ROOT.'/library/autoload.php'));
$file_id = $_GET['id'];
$file 	= $YuuClass->get_file($file_id);
$img 	= 'https://drive.google.com/vt?id='.$file['file_id'];
$api 	= "https://haxhits.com/api/getlink/?ptype=json&secretkey=pU39Sd4P&link=$file[file_id]&poster=https://yuudrive.me/assets/img/cover-player.jpg";
$sources = curl($api);
function curl($url){
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    $page = curl_exec($ch);
    curl_close($ch);
    return $page;
}
$sources = json_decode($sources);
?>
<!doctype html>
<html lang="en">
<head>
  	<meta charset="utf-8" />
  	<meta name="robots" content="noindex,nofollow">
  	<meta name="googlebot" content="noindex,nofollow">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
    <link rel="icon" href="/assets/img/favicon.ico">
	<title><?php echo $file['file_name'];?></title>
</head>
<body>
<iframe src="<?= $sources->player; ?>" style="position:fixed; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;border: none;" allowfullscreen="true" width="100%" height="100%">
    Your browser doesn't support iframes
</iframe>
<script data-cfasync='false' type='text/javascript' src='//p244229.clksite.com/adServe/banners?tid=244229_465249_7&type=shadowbox&size=800x440'></script>
</body>
</html>