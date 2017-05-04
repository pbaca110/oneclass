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
	$newProfileAtHandle = filter_var($newProfileAtHandle,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);if(empty($newProfileAtHandle) ===true){throw(new \InvalidArgumentException("profile at handle is empty or insecure"));
	}if(strlen($newProfileAtHandle)>32){throw(\RangeException("profile at handle is too large"));}
	$this->profileAthandle = $newProfileAtHandle;
	}
/**
 * gets profile by profile id
 * @param \PDO $pdo $pdo PDO connection object
 * @param int $profileId profile id to search for
 * @return Profile|null Profile or null if not found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not the correct data type
 **/
public static function getProfileByProfileId(\PDO $pdo,int $profileId){if($profileId <= 0 ){throw(new \PDOException("profile id is not positive"));
}
$query = "SELECT profileId,profileActivationToken,profileAthandle FROM profile WHERE profileId = :profileId"; $statement = $pdo->prepare($query);$parameters =["profileId" => $profileId];$statement->execute($parameters);
try{$profile = null;$statement->setFetchMode(\PDO::FETCH_ASSOC);$row = $statement->fetch();if(row !==false){$profile = new profile($row["profileId"],$row["profileActivationToken"],$row["profileAtHandle"]);
}
}
catch(\Exception $exception){throw(new \PDOException($exception->getMessage(),0,$exception));}
return($profile);
}
/**
 * gets profile by at handle
 * @param \PDO $pdo PDO connection object
 * @param string $profileAtHandle at handle to search for
 * @return \SPLFixedArray of all profiles found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not the correct data type
 **/
public static function getProfileByProfileAtHandle(\PDO $pdo, string $profileAtHandle) : \SplFixedArray{$profileAtHandle = trim($profileAtHandle);$profileAtHandle =filter_var($profileAtHandle,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);if(empty($profileAtHandle)===true){throw(new \PDOException("not a valid Handle"));
}
$query = "SELECT profileId,profileActivationToken,ProfileAtHandle FROM profile WHERE profileAtHandle = :profileAtHandle";$statement = $pdo->prepare($query);
$parameters = ["profileAtHandle" => $profileAtHandle];$statement->execute($parameters);$profiles = new \SplFixedArray($statement->rowCount());$statement->setFetchMode(\PDO::FETCH_ASSOC);
while(($row = $statement->fetch())!==false){try{$profile = new profile($row["profileId"],$row["profileActivationToken"],$row["profileAtHandle"]);$profiles[$profiles->key()] = $profile;
$profiles->next();}
catch(\Exception $exception){throw(new \PDOException($exception->getMessage(),0,$exception));}
}
return($profiles);
}
/**
 * get the profile by the profile activation token
 * @param string #profileActivationToken
 * @param \PDO object #pdo
 * @return Profile|null profile or null if not found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError when variables are not the correct data type
 **/
public static function getProfileByProfileActivationToken(\PDO $pdo,string $profileActivationToken) : ?profile {
	$profileActivationToken = trim($profileActivationToken); if(ctype_xdigit($profileActivationToken) ===false){throw(new \InvalidArgumentException("profile activation token is empty or in the wrong format"));
	}
	$query = "SELECT profileId,profileActivationToken,profileAtHandle FROM profile WHERE profileActivationToken = profileActivationToken";$statement = $pdo->prepare($query);$parameters = ["profileActivationToken" => $profileActivationToken];
	$statement->execute($parameters);try{$profile = null; $statement->setFetchMode(\PDO::FETCH_ASSOC);$row = $statement->fetch();if($row !== false){$profile = new profile($row["profileActivationToken"],$row{"profileAthandle"});
	}
	}catch(\Exception $exception){throw(new \PDOException($exception->getMessage(),0,$exception));
	} return($profile);
}
/**
 * formats the state variables for JSON serialization
 * @return array resulting state variables to serialize
 **/
public function jsonserialize(){return(get_object_vars($this));}
}