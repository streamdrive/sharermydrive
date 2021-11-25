<?php require_once(realpath($_SERVER['DOCUMENT_ROOT']).'/library/autoload.php');
require_once('lib/token.php');
$file_id 	= $_GET['id'];
$file 		= $YuuClass->get_file($file_id);
$img 		= "https://drive.google.com/vt?id=$file[file_id]";
$passing    = base_url("/plugins/player/check/$token:$file_id");
if(!$file) exit('Source not Found');
?><!doctype html>
<html lang="en">
<head>
  	<meta charset="utf-8" />
  	<meta name="robots" content="noindex"/>
	<meta name="googlebot" content="noindex"/>
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0"/>
    <meta property='og:description' content="Streaming - <?= $file['file_name']; ?>"/>
	<title><?= $file['file_name']; ?></title>
	<link rel="icon" href="<?= base_url('assets/img/favicon.ico'); ?>">
	<style type="text/css">
	*{margin:0;padding:0}#yuu-player{position:fixed;top:0;bottom:0;left:0; width:auto;height: auto;}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="<?= base_url('assets/js/jquery.base64.min.js'); ?>"></script>
	<script type="text/javascript" src="//ssl.p.jwpcdn.com/player/v/8.0.12/jwplayer.js"></script>
</head>
<body onload="get_video()">
<div id="yuu-player"></div>
<script type="text/javascript">
var set = {
'name': 'YuuPlayer 1.1',
'skin': 'glow',
'logo': '<?= base_url("assets/img/logo.png"); ?>',
'poster': '<?= base_url("assets/img/cover-player.jpg"); ?>',
'link': '<?= base_url(); ?>'
};jwplayer.key = 'XSuP4qMl+9tK17QNb+4+th2Pm9AWgMO/cYH8CI0HGGr7bdjo';
function get_video() {$.get('<?= $passing; ?>', function(data, status) {loadVid(data);})}
function loadVid(a){
	var player=jwplayer("yuu-player").setup({
		sources:[{ type:"video/mp4", label:"HD", file:a }],
		image:set.poster,flashplayer:"//ssl.p.jwpcdn.com/player/v/8.1.1/jwplayer.flash.swf",
		skin:set.skin,
		logo: { file: 'https://cgs.yuudrive.me/img/logo.png', logoBar:set.logo, link:set.link },
		title:"<?= $file['file_name']; ?>",
		abouttext:set.name,
		aboutlink:set.link,
		controls:true,
		autostart:false,
		allowfullscreen:true,
		fullscreen:false,
		preload:true,
		primary:'html5',
		width:"100%",
		height:"100%",
		aspectratio:"16:9",
		displaytitle:true,
		ph:1,
		playbackRateControls: [0.5, 0.75, 1, 1.25, 1.5, 2],
		sharing: { link: '<?= CURRENT_URL; ?>' }
	});
	player.on("ready",function(){
		jwLogoBar.addLogo(b)
	}),
	player.getCurrentCaptions()
}
</script>
</body>
</html>