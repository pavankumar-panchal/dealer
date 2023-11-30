<?php

class CustomerDetails
{
   var $mstrOfficeAddress;
   var $mstrHomeAddress;
   var $mstrMobileNo;
   var $mstrFirstName;
   var $mstrLastName;
   var $mstrRegDate;
   var $mstrIsBillNShipAddrMatch;
   var $custIsAvailable;

   function setCustomerDetails($astrFirstName,$astrLastName,$astrOfficeAddress, $astrHomeAddress, $astrMobileNo, $astrRegDate,$astrIsBillNShipAddrMatch)
   {
     $this->mstrOfficeAddress=$astrOfficeAddress;
     $this->mstrHomeAddress=$astrHomeAddress;
     $this->mstrMobileNo=$astrMobileNo;
     $this->mstrFirstName=$astrFirstName;
     $this->mstrLastName=$astrLastName;
     $this->mstrRegDate=$astrRegDate;
     $this->mstrIsBillNShipAddrMatch=$astrIsBillNShipAddrMatch;
     $this->custIsAvailable = "YES";
   }
   function getFirstName()
   {
	   return $this->mstrFirstName;
   }
   function getCustAvailFlag()
   {
	   return $this->custIsAvailable;
   }
   function getLastName()
   {
	   return $this->mstrLastName;
   }
   function getMobileNo()
   {
	   return $this->mstrMobileNo;
   }
   function getRegDate()
   {
	   return $this->mstrRegDate;
   }
   function getBillNShipAddrMatch()
   {
     return $this->mstrIsBillNShipAddrMatch;
   }

  function getOfficeAddress()
  {
    return $this->mstrOfficeAddress;
  }
  function getHomeAddress()
  {
    return $this->mstrHomeAddress;
  }

 function toString()
 	{

	return "The  Customer Details  is \n".
			"First Name : 	".$this->mstrFirstName."\n".
			"Last Name:  "	 .$this->mstrLastName."\n".
			"Reg Date: 	  ".$this->mstrRegDate ."\n".
			"Mobile No:     ".$this->mstrMobileNo ."\n".
			"Home Address:	 ".mstrHomeAddress."\n".
			"Office Address:  ".mstrOfficeAddress."\n".
			"IsBillNShip Addr Match : ".$this->mstrIsBillNShipAddrMatch."\n".
			"Cust is Available:	 	 ".$this->custIsAvailable;
	}
}

?>