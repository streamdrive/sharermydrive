<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * ====================================================================================
 * @author MuhBayu
 * @link https://github.com/MuhBayu
 * @package YuuDrive Core Library
 */
# ===================================================
function authURL() {
	global $google;
	$url = "https://accounts.google.com/o/oauth2/auth?";
	$params_request = array(
	    "response_type" => "code",
		"client_id" => trim($google['client_id']),
		"redirect_uri" => trim($google['redirect']),
		"access_type" => 'offline',
		'include_granted_scopes' => 'true',
		"scope" => implode(' ', [
			'https://www.googleapis.com/auth/userinfo.profile', 'email', 'https://www.googleapis.com/auth/drive',
			'https://www.googleapis.com/auth/drive.appdata',
			'https://www.googleapis.com/auth/drive.metadata.readonly'
		]),
	);
	return $url.http_build_query($params_request);
}
function cURL_post($url, $data, $token=null, $arr=true) {
    if($token) {
        $authorization = "Authorization: Bearer $token";
    } else {
        $authorization = "Authorization: Bearer $_COOKIE[g_token]";
    }
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	$json = json_decode($result, true);
	curl_close($ch);
	if(!isset($json['error'])) {
		if($arr) {
			return $json;
		} return $result;
	} else {
		return throwErr($json['error']['code'], $json['error']['message']);
	}
}
function cURL_get($url) {
	$authorization = "Authorization: Bearer $_COOKIE[g_token]";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	$response 	 = curl_exec($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$json 		 = json_decode($response, TRUE);
	curl_close($ch);
	if(!isset($json['error'])) {
		return $json;
	} else {
		return throwErr($json['error']['code'], $json['error']['message']);
	}
}
function get_token($code) {
	global $google;
	try {
		$data = array(
			'code' => $code,
			'client_id' => trim($google['client_id']),
			'client_secret' => trim($google['secret_key']),
			'redirect_uri' => urldecode($google['redirect']),
			'grant_type' => 'authorization_code'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, false); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		$result = curl_exec($ch);
		$errors = curl_error($ch);
		curl_close($ch);
		if($errors) return (object)array('error' => TRUE, 'error_description' => $errors);
		return json_decode($result);
	} catch (\Exception $e) {
		return (object)array('error' => TRUE, 'error_description' => $e->getMessage());
	}
}
function refresh_token($token) {
	global $google;
	$data = array(
	    'client_id' => $google['client_id'],
	    'client_secret' => $google['secret_key'],
	    'refresh_token' => $token,
	    'grant_type' => 'refresh_token'
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v4/token');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FAILONERROR, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result);
}
function revokeToken($access_token) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://accounts.google.com/o/oauth2/revoke?token=".$access_token);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$response = curl_exec($ch); //run
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //get HTTP code
	curl_close($ch);
	if ($httpCode == 200) {
		return true;
	} else {
		return false;
	}
}
function get_user($token) {
	try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/userinfo?alt=json&access_token='.$token);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		$response 	 = curl_exec($ch);
		$errors = curl_error($ch);
		curl_close($ch);
		if($errors) return array('error' => TRUE, 'error_description' => $errors);
		return json_decode($response, TRUE);
	} catch (\Exception $e) {
		return array('error' => TRUE, 'error_description' => $e->getMessage());
	}
}
function account_info($token=null) {
	return cURL_get('https://www.googleapis.com/drive/v3/about/?' . http_build_query(['fields' => 'user,storageQuota']));
}
# ===========================================================================================
# SUB FUNCTION
# ===============

function insertPermission($folder_id) {
	@$post = array(
		"role" => 'reader',
		"type" => 'anyone'
	);
	return cURL_post('https://www.googleapis.com/drive/v2/files/'.$folder_id.'/permissions', $post);
}
function createFolder($folder_name) {
	try {
		$post = array(
			"title" => $folder_name,
			"mimeType" => 'application/vnd.google-apps.folder'
		);
		$folder = cURL_post('https://www.googleapis.com/drive/v2/files', $post, null, TRUE);
		if(empty($folder['error'])) {
			insertPermission($folder['id']);
			return $folder['id'];
		} return false;
	} catch (\Throwable $th) {
		return false;
	}
}
function copyFile($file_id, $folder_id, $title=NULL) {
	@$post = array(
		"parents" => [[
			"id" => $folder_id
		]],
		'title' => $title,
		'description' => 'download from YuuDrive.me'
	);
	return cURL_post('https://www.googleapis.com/drive/v2/files/'.$file_id.'/copy', $post);
}

function copyFile_createFolder($file_id, $file_name=null) {
	global $app;
	$folder = checkFolder($app['folder']);
	return copyFile($file_id, $folder, $file_name);
}
function getFile($file_id) {
	$drive = cURL_get('https://www.googleapis.com/drive/v2/files/'.$file_id);
	return $drive;
}
function getAllFile($file_name) {
	$drive = cURL_get('https://www.googleapis.com/drive/v2/files');
	if(!empty($drive['items'])) foreach ($drive['items'] as $key => $file) {
		if( $file['mimeType'] != 'application/vnd.google-apps.folder'
			AND empty($file['sharedWithMeDate'])
			AND $file['labels']['trashed'] != TRUE) {
			return $file;
		}
	}
	return false;
}
function checkFileExist($file_name) {
	$drive = cURL_get('https://www.googleapis.com/drive/v2/files');
	if(!empty($drive['items'])) foreach ($drive['items'] as $key => $file) {
		if( $file['title'] == $file_name
			AND $file['mimeType'] != 'application/vnd.google-apps.folder'
			AND empty($file['sharedWithMeDate'])
			AND $file['labels']['trashed'] != TRUE) {
			return $file['id'];
		}
	}
	return false;
}
function checkFolder($folder_name='YuuDrive') {
	$fields = http_build_query([
		'fields' => 'files(id,name,mimeType,trashed,shared,owners/me)'
	]);
	$drive = cURL_get('https://www.googleapis.com/drive/v3/files?'.$fields);
	if(!empty($drive['files'])) foreach ($drive['files'] as $key => $file) {
		if($file['name'] == $folder_name
			AND $file['mimeType'] == 'application/vnd.google-apps.folder'
			AND $file['owners'][0]['me'] === true
			AND !$file['trashed']) {
			return $file['id'];
		}
	}
	return createFolder($folder_name);
}
# ----------------------------------------------------------
function throwErr($code, $msg) {
	$throw = array('error' => $code, 'error_description' => $msg);
	return $throw;
}
function errorText($code) {
	if($code == '400') {
		return "Sharing succeeded, but the notification email was not correctly delivered. <b>Please login with another Google account.</b>";
	} elseif($code == '401') {
		return "Authorization Error, your login session has expired. <b>Please log-in again.</b><br/><a href=\"/OAuth?r=".base64_encode(CURRENT_URL)."\">Click here..</a>";
	} elseif($code == '403') {
		return "Your Google Drive account storage was full, please delete another file in your <a href=\"https://drive.google.com/drive/\" target=\"_blank\">Google Drive</a> or login with another Google account.";
	} elseif($code == '404') {
		return "File was not Found!";
	} elseif($code == '500') {
		return "500 Internal Server Error";
	}
}
function directdl($google_id) {
	$ch = curl_init("https://drive.google.com/uc?id=$google_id&authuser=0&export=download");
	curl_setopt_array($ch, array(
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_POSTFIELDS => [],
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => 'gzip,deflate',
		CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
		CURLOPT_HTTPHEADER => [
			'accept-encoding: gzip, deflate, br',
			'content-length: 0',
			'content-type: application/x-www-form-urlencoded;charset=UTF-8',
			'origin: https://drive.google.com',
			'referer: https://drive.google.com/drive/my-drive',
			'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
			'x-drive-first-party: DriveWebUi',
			'x-json-requested: true'
		]
	));
	$response = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($response_code == '200') { // Jika response status OK
		$object = json_decode(str_replace(')]}\'', '', $response));
		if(isset($object->downloadUrl)) {
			return $object->downloadUrl;
		}
	} return false;
}
?>