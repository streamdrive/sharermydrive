<?php error_reporting(0);
include('htmldom/lib_html_dom.php');

if (!file_exists('cache')) {
    mkdir('cache', 0777, true);
}
//Check cache
function getDirect($id) {
	$timeout = 900;
	$file_name = md5($id);
	if(file_exists('cache/'.$file_name.'.tmp')) {
		$fopen = file_get_contents('cache/'.$file_name.'.tmp');
		$data = explode('@@', $fopen);
		$now = gmdate('Y-m-d H:i:s', time() + 3600*(+7+date('I')));
		$times = strtotime($now) - $data[0];
		if($times >= $timeout) {
			$linkdown = trim(linkDown($id));
			$create_cache	= gd_cache($id, $linkdown);
			$arrays = explode('|', $create_cache);
			$cache = $arrays[0];
		} else {
			$cache = $data[1];
		}
	} else {
		$linkdown = trim(linkDown($id));
		$create_cache	= gd_cache($id, $linkdown);
		$arrays = explode('|', $create_cache);
		$cache = $arrays[0];
	}
	return $cache;
}
function gd_cache($id, $source) {
	$time = gmdate('Y-m-d H:i:s', time() + 3600*(+7+date('I')));
	$file_name = md5($id);
	$string = strtotime($time).'@@'.$source;
	$file = fopen("cache/".$file_name.".tmp",'w');
	fwrite($file,$string);
	fclose($file);
	if(file_exists('cache/'.$file_name.'.tmp')) {
		$msn = $source;
	} else {
		$msn = $source;
	}
	return $msn;
}
function locheader($page){
	$temp = explode("\r\n", $page);
	foreach ($temp as $item) {
		$temp2 = explode(": ", $item);$infoheader[$temp2[0]] = $temp2[1];
	}$location = $infoheader['Location'];
	return $location;
}
function linkDown($id){
	$link = "https://drive.google.com/uc?export=download&id=$id";
	$userAgent="Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/google.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/google.txt");
	$page = curl_exec($ch);
	$get = locheader($page);
	if($get == "") {
		$html = str_get_html($page);
		$link = urldecode(trim($html->find('a[id=uc-download-link]',0)->href));
		$tmp = explode("confirm=",$link);
		$tmp2 = explode("&",$tmp[1]);
		$confirm = $tmp2[0];
		$linkdowngoc = "https://drive.google.com/uc?export=download&id=$id&confirm=$confirm";
		curl_setopt ($ch, CURLOPT_URL, $linkdowngoc);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/google.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/google.txt");
		$page = curl_exec($ch);
		$get =  locheader($page);
	}
	curl_close($ch);
	return $get;
}
?>