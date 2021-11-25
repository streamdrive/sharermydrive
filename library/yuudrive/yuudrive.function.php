<?php defined('BASEPATH') OR exit('No direct script access allowed');
function base_url($page='') {
	global $app;
	if (!$page) return BASE_HOST;
	return rtrim($app['base_url'], '/')."/$page";
}
function changeTitle($title,$description='', $img=''){
	global $app;
	if(!$description) $description = $app['description'];
	$img = ($img) ? $img : base_url('assets/img/logo.png');
    $output = ob_get_contents();
    if(ob_get_length() > 0) { ob_end_clean(); }
    $patterns = array("/<title>(.*?)<\/title>/",'<meta property="og:description" content="(.*)">', '<meta property="og:image" content="(.*)">');
    $replacements = array("<title>$title</title>","meta property='og:description' content='$description'","meta property='og:image' content='$img'");
    print preg_replace($patterns, $replacements,$output);
}
function generate_tokenFileID() {
	$res = '';
	$pattrn = '0123456789abcdefghijklmnopqrstuvwxzy';
	for ($i=0; $i <=150; $i++) { 
		$res .= $pattrn[mt_rand(0, strlen($pattrn) - 1)];
	} return $res;
}
function get_token_file() {
	return generate_csrf_token('file_token');
}
function generate_csrf_token($session='csrf_token', $len=32) {
	$token = base64_encode(openssl_random_pseudo_bytes($len));
   	$token = sha1($token);
	if(empty($_SESSION[$session])) $_SESSION[$session] = $token;
	return $_SESSION[$session];
}
function getShort($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, base_url("api/googl?url=$url"));
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}
function htmlcode($url, $text) {
	return htmlspecialchars('<a href="'.$url.'">'.$text.'</a>');
}
function bbcode($url, $text) {
	return htmlspecialchars('[URL="'.$url.'"]'.$text.'[/URL]');
}
function embedcode($id) {
	return htmlspecialchars('<iframe src="'.base_url("embed/$id").'" scrolling="no" allowfullscreen="true" width="100%" height="100%"><iframe>');
}
function allow_video($ext, $byext=false) {
	$allow = ($byext) ? array('mp4', 'mkv', 'avi', '3gp', 'ts', 'wmv') :
						array('video/x-matroska', 'video/mp4', 'video/x-flv', 'video/MP2T', 'video/3gpp');
    return in_array(strtolower($ext), $allow);
}
function allow_audio($ext) {
    $allow = array('mp3', 'm4a', 'ogg', 'flac', 'raw', 'wav');
    return in_array(strtolower($ext), $allow);
}
function allow_archive($ext) {
    $allow = array('zip', 'rar', 'gz', 'tar', '7z', 'iso', 'jar');
    return in_array(strtolower($ext), $allow);
}
function redirect($page='') {
	global $app;
	if($page) {
		//header("HTTP/1.1 301 Moved Permanently");
		header("Location: " . filter_var($page, FILTER_SANITIZE_URL));
	} else {
		header("Location: $app[base_url]");
	}
		
}
function get_icon($ext) {
	// $ext is file extention EX: (png, jpg, html)
	if(allow_video($ext)) {
		return '/assets/img/file/video.png';
	} elseif (allow_audio($ext)) {
		return '/assets/img/file/music.png';
	} elseif($ext == 'custom_fileext') {
		return '/assets/img/file/customicon.png';
	} else {
		return '/assets/img/file/docs.png';
	}
}
function is_admin() {
	global $app; global $_user;
	if(!empty($_user)) {
		return (in_array($_user['email'], $app['admin']));
	} else {
		return false;
	}
}
function is_login() {
	return (isset($_COOKIE['user']));
}
function check_login($access='user') {
	global $_user; global $app;
	if(!is_login() || check_public()) header('Location: /');
	if($access=='admin') {
		if(!is_admin()) header('Location: /');
	}
}
function check_renew_token($user) {
	if(!isset($_COOKIE['token_expired_in_time']) || empty($user)) return false;
	$today = date('Y-m-d H:i:s');
	$expiry  = $_COOKIE['token_expired_in_time'];
	if(!is_string($expiry)) return false;
	if(strtotime($today) >= strtotime($expiry)) {
		if(isset($_COOKIE['refresh_token'])) {
			$extend_sess = time() + 3600*24*2;
			$refresh = refresh_token($_COOKIE['refresh_token']);
			if(!empty($refresh->access_token)) {
				setcookie('g_token', $refresh->access_token, $extend_sess, '/', BASE_DOMAIN, TRUE, TRUE);
				setcookie("token_expired_in_time", date('Y-m-d H:i:s', strtotime('+1 hour')), time()+3600*24*2, '/', BASE_DOMAIN, TRUE, TRUE);
				header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
				header("Pragma: no-cache"); // HTTP 1.0.
				header("Expires: 0");
				$_COOKIE['g_token'] = $refresh->access_token;
				// header('Refresh:1;');
				// setcookie('g_token', $refresh->access_token, $expiry_extend);
				// setcookie('token_expired_in_time', time() + 3600);
			} else {
				return redirect(base_url('logout?r='.CURRENT_URL));
			}
		} else {
			return redirect(base_url('logout'));
		}
	}
}
function check_public() {
	global $app;
	global $_user;
	return ($app['public'] != 1 && !is_admin());
}
function check_file_access($file_id) {
	global $_user;
	global $YuuClass; global $app;
	$get_file = $YuuClass->get_file($file_id);
	if(is_admin()) return true;
	return ($get_file['file_owner_mail'] == $_user['email']);
}
function check_protect_file($email, $protect_list) {
	global $_user;
	if(array_search($email, array_column($protect_list, 'email')) !== false 
		&& $email !== $_user['email']) {
		return true;
	} return false;
}
function getFileExtension($filename) {
	return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}
function parse_param($get_param, $url) {
	$query_str = parse_url($url, PHP_URL_QUERY);
	parse_str($query_str, $query_params);
	if(isset($query_params[$get_param])) {
		return $query_params[$get_param];
	} else {
		return false;
	}
}
function filesize_formatted($size) {
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	$power = $size > 0 ? floor(log($size, 1024)) : 0;
	return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}
function timeAgo($time_ago){
	$time_elapsed   = time() - strtotime($time_ago);
	$seconds    = $time_elapsed ;
	$minutes    = round($time_elapsed / 60);
	$hours      = round($time_elapsed / 3600);
	$days       = round($time_elapsed / 86400);
	$weeks      = round($time_elapsed / 604800);
	$months     = round($time_elapsed / 2600640);
	$years      = round($time_elapsed / 31207680);
	if($seconds <= 60){
	    return "just now";
	} else if($minutes <=60){
		return ($minutes==1) ? "one minute ago" : "$minutes minutes ago";
	} else if($hours <=24){
		return ($hours==1) ? "an hour ago" : "$hours hrs ago";
	} else if($days <= 7){
		return ($days==1) ? "yesterday" : "$days days ago";
	} else if($weeks <= 4.3){
		return ($weeks==1) ? "a week ago" : "$weeks weeks ago";
	} else if($months <=12){
		return ($months==1) ? "a month ago" : "$months months ago";
	} else{
		return ($years==1) ? "one year ago" : "$years years ago";
	}
}
function sanitize_filename($string) {
	$string = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/', '/\./'), array('-', '-', '', '-'), $string);
	$string = preg_replace('/(.)\\1+/', '$1', $string);
	return strtolower($string);
}
function array_remove(&$array, $min=5){
    foreach ($array as $key => $link) {
        $link = filter_var($link, FILTER_SANITIZE_URL);
        if(filter_var($link, FILTER_VALIDATE_URL) === false || strpos($link, 'google.com') <= 0) unset($array[$key]);
    } return array_splice($array, 0, $min);
}
?>