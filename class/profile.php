<?php

namespace Edu\Cnm\DataDesign;
class profile implements \jsonSerializable {
	/**
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * @var string $profileAtHandle
	 **/
	private $profileAthandle;
	/**
	 * $var $profileActivationToken
	 **/
	private $profileActivationToken;

	/**
	 * constructor for profile
	 * @param int|null $newProfileId id of this profile or null if a new profile
	 * @param string $newProfileActivationToken
	 * @param string $newProfileAtHandle
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(?int $newProfileId, ?string $newProfileActivationToken, ?string $newProfileAtHandle) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileAtHandle($newProfileAtHandle);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for profile id
	 * @return int value of profile id (or null if new profile)
	 **/
	/**
	 * @return int
	 */
	public function getProfileId(): int {
		return $this->profileId;
	}
	/**
	 * mutator method for profile id
	 * @param int|null $newProfileId value of new profile id
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not positive
	 **/
	public function setProfileId(?int $newProfileId): void {if($newProfileId ===null){$this->profileId = null; return;} if($newProfileId <= 0){throw(new \RangeException("profile id is not positive"));}
	$this->profileId = $newProfileId;
	}
	/**
	 * accessor method for account activation token
	 * @return string value of the activation token
	 **/
	public function getProfileActivationToken() : ?string {return($this->profileActivationToken);}
	/*
	 * mutator method for account activation token
	 * @param string $newProfileActivationToken
	 * @throws \InvalidArgumentException if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setProfileActivationToken(?string $newProfileActivationToken): void{if($newProfileActivationToken ===null) {$this->profileActivationToken = null;return;}
	$newProfileActivationToken = strtolower(trim($newProfileActivationToken));if(ctype_xdigit($newProfileActivationToken) ===false)
		{throw(new\RangeException("user activation is not valid"));}
	if(strlen($newProfileActivationToken)!==32){throw(new\RangeException("user activation token has to be 32"));}
	$this->profileActivationToken = $newProfileActivationToken;}
	/**
	 * accessor method for at handle
	 * @return string value of at handle
	 **/
	public function getProfileAtHandle():string {return($this->profileAthandle);}
	/**
	 * mutator method for at handle
	 * @param string $newProfileAtHandle new value of at handle
	 * @throws \InvalidArgumentException if $newAtHandle is not a string or insecure
	 * @throws \RangeException if $newAtHandle is not a string or insecure
	 * @throws \ TypeError if $newAtHandle is not a string
	 **/
	public function setProfileAtHandle(string $newProfileAtHandle) : void{$newProfileAtHandle = trim($newProfileAtHandle);
	$newProfileAtHandle = filter_var($newProfileAtHandle,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);}

























}