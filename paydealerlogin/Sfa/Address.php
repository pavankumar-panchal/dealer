<?php

class Address
{

	var $mstrCountryAlphaCode;
	var $mstrAddLine1;
	var $mstrAddLine2;
	var $mstrAddLine3;
	var $mstrCity;
	var $mstrState;
	var $mstrZip;
	var $mstrEmail;

	function setAddressDetails($astrAddrLine1, $astrAddrLine2, $astrAddrLine3, $astrCity, $astrState, $astrZip, $astrCountryAlpha,$astrEmail)
	{
		$this->mstrAddLine1 = $astrAddrLine1 ;
		$this->mstrAddLine2 = $astrAddrLine2 ;
		$this->mstrAddLine3 = $astrAddrLine3 ;
		$this->mstrCity=$astrCity;
		$this->mstrState=$astrState;
		$this->mstrZip = $astrZip;
		$this->mstrCountryAlphaCode = $astrCountryAlpha;
		$this->mstrEmail	=	$astrEmail;
	}

    #default constructor of  Address
	function Address()
	{

	}
	function getAddrLine1()
	{
		return $this->mstrAddLine1 ;
	}

	function getAddrLine2()
	{
		return $this->mstrAddLine2 ;
	}

	function getAddrLine3()
	{
		return $this->mstrAddLine3 ;
	}

	function getCity()
	{
		return $this->mstrCity;
	}

	function getState()
	{
		return $this->mstrState;
	}

	function getZip()
	{
		return $this->mstrZip;
	}

	function getCountryAlphaCode()
	{
		return $this->mstrCountryAlphaCode;
	}
	function getEmailId()
	{
		return $this->mstrEmail;
	}

	function toString()
	{

		return "The  address is \n".
							"Country Alpha Code : ".$this->mstrCountryAlphaCode."\n".
							"Address Line 1: ".$this->mstrAddLine1."\n".
							"Address Line 2: ".$this->mstrAddLine2 ."\n".
							"Address Line 3:  ".$this->mstrAddLine3 ."\n".
							"City:		 ".$this->mstrCity."\n".
							"State:	 	 ".$this->mstrState."\n".
							"Zip :		 ".$this->mstrZip."\n".
							"Email:	 	 ".$this->mstrEmail;
	}
}

?>