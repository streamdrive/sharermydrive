<?php error_reporting(0);
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(realpath(DOCUMENT_ROOT.'/library/autoload.php'));
$file_id 	= $_REQUEST['id'];
$file 		= $YuuClass->get_file($file_id);
$img 		= 'https://drive.google.com/vt?id='.$file['file_id'];
$api 		= 'https://api.mahouhost.com/jwplay.php?id='.$file['file_id'];
$sources 	= curl($api);
$jes 		= json_encode($sources);
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
?>
<!doctype html>
<html lang="en">
<head>
  	<meta charset="utf-8" />
  	<meta name="robots" content="noindex,nofollow">
  	<meta name="googlebot" content="noindex,nofollow">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
	<title><?php echo $file['file_name'];?></title>
	<link rel="icon" href="/assets/img/favicon.ico">
	<link href="/plugins/player/skins/thin-blue.min.css" rel="stylesheet" type="text/css" />
	<link href="/plugins/player/skins/jw-logo-bar.min.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
	*{margin:0;padding:0}#yuu-player{position:fixed;top:50%;left:50%;min-width:100%;min-height:100%;width:auto;height:auto;z-index:-100;transform:translateX(-50%) translateY(-50%)}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://content.jwplatform.com/libraries/aIwBA1sT.js?cache=57bcab112c69a"></script>
</head>
<body>
<div id="yuu-player"></div>
<script src="/plugins/player/skins/jw-logo-bar.js"></script>
<script type="text/javascript">jwplayer.key = "OfbORZJ6ILKCFw7Kjb5SK2VuLlGnoHoUEJ4aCw";
var videoPlayer = jwplayer("yuu-player").setup({
	sources:<?php echo json_decode($jes); ?>,image:"/assets/img/cover-player.jpg",
	controls:true,displaytitle:true,skin:"thin blue",
	logo:{link:"https://yuudrive.com",file:"/assets/img/logo-player.png",},
	abouttext:"YuuDrive",aboutlink:"https://yuudrive.com",
	controls:true,displaytitle:true,
	width:"100%",height:"100%",
	aspectratio: "16:9",fullscreen: "true",
	autostart: false,
});
videoPlayer.on('ready',function() {
	jwLogoBar.addLogo(videoPlayer);
});
</script>
<script data-cfasync='false' type='text/javascript' src='//p244229.clksite.com/adServe/banners?tid=244229_465249_7&type=shadowbox&size=800x440'></script>
</body>
</html>