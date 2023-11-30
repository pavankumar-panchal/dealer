<?php
 class PGSearchResponse {

		var $mstrRespCode=null;
		var $mstrRespMessage=null;
		var $arrayPGResponseObjects=null;
		



	// default constructor
	function PGSearchResponse() {
	}

	function getRespCode(){
		return $this->mstrRespCode;
	}

	function setRespCode($astrRespCode) {
		$this->mstrRespCode = $astrRespCode;
	}

	function getRespMessage(){
		return $this->mstrRespMessage;
	}

	function setRespMessage($astrRespMessage) {
		$this->mstrRespMessage = $astrRespMessage;
	}

	function setPGResponseObjects($arrlist){
		$this->arrayPGResponseObjects = $arrlist;
	}
	
	function getPGResponseObjects(){
		return  $this->arrayPGResponseObjects;
	}
	
	
}


?>