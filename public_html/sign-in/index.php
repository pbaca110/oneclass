<?php

require_once dirname(__DIR__,3) . "/php/classes/autoload.php";
require_once dirname(__DIR__,3) . "/php/lib/xsrf.php";
require_once ("/etc/apache2/datadesign-mysql/encrypted-config.php");
use Edu\Cnm\dataDesign\profile;

/**
 * api for handling sign-in
 **/

$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try{

	if(session_status() !==PHP_SESSION_ACTIVE){
		session_start();}
		$pdo = connectToEncryptedMySQL("/etc/apache2/datadesign-mysql");

	$method = array_key_exists("HTTP_X_HTTP_METHOD",$_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	if($method ==="POST") {verifXsrf();

	$requescontent = file_get_contents();
	$requestobject = json_encode($requescontent);
	if(empty($requestobject->profileEmail) ===true)
	{throw(new \InvalidArgumentException("wrong email adress.",401));
	}else{
		$profileEmail = filter_var($requestobject->profileEmail,FILTER_SANITIZE_EMAIL);}
	if(empty($requestobject->profilePassword)===true){throw(new \InvalidArgumentException("must enter a password.",401));
	}else{
		$profilepassword = $requestobject->profilepassword;

	}
	}
}