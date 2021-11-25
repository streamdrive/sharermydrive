<?php
/*
|--------------------------------------------------------------------------
| DEFINE Configuration
|--------------------------------------------------------------------------
*/
define("_NAME", "YuuDrive");
define("_VERSION", "2.3.2");
define("BASEPATH", dirname(dirname(__DIR__)));
define('BASE_DOMAIN', $_SERVER['HTTP_HOST']);
define("BASE_HOST", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".BASE_DOMAIN);
define('BASE_PAGE', basename($_SERVER['PHP_SELF']));
define('CURRENT_URL', BASE_HOST.$_SERVER['REQUEST_URI']);


/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
*/
$dbinfo['host']     = 'localhost'; 		// your mysql server
$dbinfo['db']       = 'yuudrive_v2'; // your database name
$dbinfo['user']     = 'root'; 			// your username for mysql
$dbinfo['password'] = '';				// your password for mysql

/*
|--------------------------------------------------------------------------
| APP Configuration
|--------------------------------------------------------------------------
*/

if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
     (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
     (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
    $server_request_scheme = 'https';
} else {
    $server_request_scheme = 'http';
}
$root = $server_request_scheme . '://' . $_SERVER['HTTP_HOST'];

/* URL to your YuuDrive root */
$app['base_url'] = $root;
$app['name'] = _NAME;
$app['admin'] = [
  'yuudrive.com@gmail.com',
];
$app['description'] = _NAME.' is a Google Drive file sharing service to prevent Quota Limits & Suspended File';
$app['folder'] = 'YuuDrive';
$app['debug'] = 1;
$app['public'] = 1;


/*
|--------------------------------------------------------------------------
| APP Plugins Configuration
|
| DirectDL Script -> https://p-store.net/go/16705
|
|--------------------------------------------------------------------------
*/
$plugins['player'] = 1;
$plugins['directdl_api'] = 'https://dl.yuudrive.me/api/'; // set to 0 for disable

/*
|--------------------------------------------------------------------------
| Google Client Configuration
|--------------------------------------------------------------------------
|
| How to change login system without Copy Paste Token?
| replace `urn:ietf:wg:oauth:2.0:oob` to `https://yoursite.xxx/OAuth`
| 
*/
$google['developer_key'] = 'YOUR_API_KEY';
$google['client_id'] = 'YOUR_CLIENT_ID';
$google['secret_key'] = 'YOUR_SECRET_KEY';
$google['redirect'] = 'urn:ietf:wg:oauth:2.0:oob';
$google['access_type'] = 'offline';
$google['approval_prompt'] = 'force';
$google['scopes'] = array(
  'https://www.googleapis.com/auth/userinfo.profile',
  'email', 'https://www.googleapis.com/auth/drive',
  'https://www.googleapis.com/auth/drive.appdata',
  'https://www.googleapis.com/auth/drive.metadata.readonly'
);

?>
