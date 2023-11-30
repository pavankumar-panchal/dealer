<?php

date_default_timezone_set('Asia/Kolkata');
$dateTime= date('Y:m:d-H:i:s');

	function getDateTime() {
		global $dateTime;
		return $dateTime;
	
	}

	/*
		Function that calculates the hash of the following parameters:
		 - Store Id
		 - Date/Time(see $dateTime above)
		 - chargetotal
		 - shared secret
	*/
	function createHash($chargetotal,$Currency,$storeId,$sharedSecret) {
		// Please change the store Id to your individual Store ID

		// NOTE: Please DO NOT hardcode the secret in that script. For example read it from a database.
		
		$stringToHash = $storeId . getDateTime() . $chargetotal . $Currency . $sharedSecret;
		
		$ascii = bin2hex($stringToHash);

		return sha1($ascii);
	}

	
	
?>
