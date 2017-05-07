<?php
require_once dirname(__DIR__) . "/php/classes/autoload.php";
require_once dirname(__DIR__) . "/php/lib/xsrf.php";
require_once("/etc/apache2/datadesign-mysql/encrypted-comfig.php");
use Edu\Cnm\DataDesign\profile;

/**
 * API for check profile activation status
 **/

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("etc/apache2/datadesign-mysql");

	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING);

	if(strlen($activation) === false) {
		throw(new \InvalidArgumentException("activation is empty or has invalid contents", 405));
	}
	if($method === "GET") {

		setsXsrfCookie();


		$profile = Profile::getProfileByProfileActivationToken($pdo, $activation);
		if($profile !== null) {
			if($activation === $profile->getProfileActivationToken()) {
				$profile->getProfileActivationToken(null);

				$profile->update($pdo);
				$reply->data = "thankyou for activatiing your account, you will be auto-redirected to you profile shortly.";
			}
		} else {
			throw(new RuntimeException("profile with this activation code does not exist", 404));
		}
	} else {
		throw(new InvalidArgumentException("invalid HTTP method request"));
	}
} catch
(Exception $exception) {
	$reply->status = $exception->getcode();
	$reply->status = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getcode();
	$reply->message = $typeError->getmessage();

	header("Content-type: application/json");
	if($reply->data === null) {
		unset($reply->data);
	}
}
echo json_encode($reply);