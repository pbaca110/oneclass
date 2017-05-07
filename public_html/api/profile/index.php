<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once("/etc/apache2/datadesign-mysql/encrypted-config.php");
use Edu\Cnm\DataDesign\ {
	Profile
};

/**
 * API for profile
 **/

if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/ddctwitter.ini");
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileAtHandle = filter_input(INPUT_GET, "profileAtHandle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}
	if($method === "GET") {
		setXsrfCookie();
		if(empty($id) === false) {
			$profile = Profile::getProfileByProfileId($pdo, $id);
			if($profile !== null) {
				$reply->data = $profile;
			}
		} else if(empty($profileAtHandle) === false) {
			$profile = Profile::getProfileByProfileAtHandle($pdo, $profileAtHandle);
			if($profile !== null) {
				$reply->data = $profile;
			}
		}
	} elseif($method === "PUT") {
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $id) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 403));
		}
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		$profile = Profile::getProfileByProfileId($pdo, $id);

		if($profile === null) {
			throw(new RuntimeException("Profile does not exist", 404));
		}
		if(empty($requestObject->newPassword) === true) {
			verifyXsrf();
			if(empty($requestObject->profileAtHandle) === true) {
				throw(new \InvalidArgumentException ("No profile at handle", 405));
			}
			$profile->setProfileAtHandle($requestObject->profileAtHandle);
			$profile->update($pdo);
			// update reply
			$reply->message = "Profile information updated";
		}
		/**
		 * update the password if requested
		 **/
		if(empty($requestObject->ProfilePassword) === false && empty($requestObject->profileConfirmPassword) === false && empty($requestContent->ConfirmPassword) === false) {
			if($requestObject->newProfilePassword !== $requestObject->profileConfirmPassword) {
				throw(new RuntimeException("New passwords do not match", 401));
			}
			$currentPasswordHash = hash_pbkdf2("sha512", $requestObject->currentProfilePassword, $profile->getProfileSalt(), 262144);
			if($currentPasswordHash !== $profile->getProfileHash()) {
				throw(new \RuntimeException("Old password is incorrect", 401));
			}
			$newPasswordSalt = bin2hex(random_bytes(16));
			$newPasswordHash = hash_pbkdf2("sha512", $requestObject->newProfilePassword, $newPasswordSalt, 262144);
			$profile->setProfileHash($newPasswordHash);
			$profile->setProfileSalt($newPasswordSalt);
		}
		$profile->update($pdo);
		$reply->message = "profile password successfully updated";
	} elseif($method === "DELETE") {
		verifyXsrf();
		$profile = Profile::getProfileByProfileId($pdo, $id);
		if($profile === null) {
			throw (new RuntimeException("Profile does not exist"));
		}
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $profile->getProfileId()) {
			throw(new \InvalidArgumentException("You are not allowed to access this profile", 403));
		}


		$profile->delete($pdo);
		$reply->message = "Profile Deleted";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP request", 400));
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);