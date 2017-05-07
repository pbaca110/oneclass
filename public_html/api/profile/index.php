<?php


require_once(dirname(__DIR__,3) . "/vendor/autoload.php");
require_once(dirname(__DIR__,3) . "/php/classes/autoload.php");
require_once(dirname(__DIR_, 3) . "/php/lib/xsrf.php");


use Edu\Cnm\DataDesign\ {
	profile
};

/**
 * API for profileAtHandle
 **/

if(session_status() !==PHP_SESSION_ACTIVE) {
	session_start();
}
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/data-design.sql");

$method = array_key_exists("HTTP_X_HTTP_METHOD",$_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$profileAtHandle = filter_input(INPUT_GET, "profileAtHandle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

if(($method === "DELETE" || $method === "PUT") && (empty($id) ===true || $id < 0)){

	}

if($method ==="GET"){
	setXsrfcookie();

	if(empty($Id) ===false) {
		$profile = Profile::getProfileByProfileId($pdo,$id);

		if($profile !==null) {
			$reply->data = $profile;
		}
	} else if(empty($profileAtHandle) ===false){
		$profile = Profile::getProfileByProfileAtHandle($pdo,$profileAtHandle);
		if($profile !==null){
			$reply->data = $profile;
				}
			}
		}
	elseif($method ==='PUT'){
		if(empty($_SESSION))
	}}
