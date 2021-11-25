<?php header('Access-Control-Allow-Origin: *');
require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/library/autoload.php');
require_once('lib/token.php');
$file_id 	= $_REQUEST['id'];
$file 		= $YuuClass->get_file($file_id);
$img 		= 'https://drive.google.com/vt?id='.$file['file_id'];
$passing    = BASE_HOST . '/plugins/player/check/'.$token.':'.$file_id;
?>
<!doctype html>
<html lang="en">
<head>
  	<meta charset="utf-8" />
  	<meta name="robots" content="noindex"/>
	<meta name="googlebot" content="noindex"/>
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
    <meta property='og:description' content="Streaming - <?= $file['file_name']; ?>"/>
	<title>YuuDrive Player</title>
	<link rel="icon" href="/assets/img/favicon.ico">
	<link href="/plugins/player/skins/jw-logo-bar.min.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
	*{margin:0;padding:0}#my-player{position:fixed;top:50%;left:50%;min-width:100%;min-height:100%;width:auto;height:auto;z-index:-100;transform:translateX(-50%) translateY(-50%)}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="/assets/js/jquery.base64.min.js"></script>
	<script src="/plugins/player/jwplayer/jwplayer.js"></script>
</head>
<body>
<div id="my-player"></div>
<script src="/plugins/player/skins/jw-logo-bar.js"></script>
<script type="text/javascript">
var set = {
'name': 'YuuPlayer',
'skin': 'glow',
'logo': '/assets/img/logo.png',
'poster': '/assets/img/cover-player.jpg',
'link': 'http://yuudrive.com'
};
jwplayer.key="Ywok59g9j93GtuSU7+axNzjIp/TBfiK4s0vvYg==";
get_video();
function get_video() {
	$.get('<?php echo $passing; ?>', function(data, status) {loadVid(data);})
}
function loadVid(sc) {
		var YuuPlayer = jwplayer("my-player").setup({
			sources: [{
				"type": "video/mp4", "label": "HD", "file": sc
			}],
			image: set['poster'],
			flashplayer:'/plugins/player/lib/jwplayer.flash1.swf',
			skin:set['skin'],
			logo: {logoBar: set['logo'],link: set['link']},
			abouttext: set['name'],
			aboutlink: set['link'],
			controls:true,
			autostart:false,
			allowfullscreen:true,
			fullscreen:false,
			width:"100%",
			height:"100%",
			aspectratio:'16:9',
		});
		YuuPlayer.on('ready',function() {
			jwLogoBar.addLogo(YuuPlayer);
		});
		YuuPlayer.getCurrentCaptions();
}
</script>
</body>
</html>