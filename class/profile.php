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
	public function __construct(?int $newProfileId, ?string $newProfileActivationToken, ?string $newProfileAtHandle){try{$this->setProfileId($newProfileId);
		$this->setProfileActivationToken($newProfileActivationToken);
	$this->setProfileAtHandle($newProfileAtHandle);}
	catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){$exceptionType = get_class($exception);throw(new $exceptionType($exception->getMessage(),0, $exception));
	}
	}