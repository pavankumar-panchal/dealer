<?php
class AirLineTransaction
{
	var $mstrBookingDate;
	var $mstrFlightDate;
	var $mstrFlightTime;
	var $mstrFlightNumber;
	var $mstrPassengerName;
	var $mstrNumberOfTickets;
	var $mstrIsCardNameNCustomerNameMatch;
	var $msrtPNR;
	var $mstrSectorFrom;
	var $mstrSectorTo;
	var $airLineInfoIsAvailable;

	function setAirLineTransactionDetails($astrBookingDate,$astrFlightDate,$astrFlightTime,
											 $astrFlightNumber,$astrPassengerName,$astrNumberOfTickets,
											 $astrIsCardNameNCustomerNameMatch,$asrtPNR,$astrSectorFrom,
											 $astrSectorTo)

	{
	  $this->mstrBookingDate                  = $astrBookingDate ;
	  $this->mstrFlightDate                   = $astrFlightDate;
	  $this->mstrFlightTime                   = $astrFlightTime;
	  $this->mstrFlightNumber                 = $astrFlightNumber;
	  $this->mstrPassengerName                = $astrPassengerName;
	  $this->mstrNumberOfTickets              = $astrNumberOfTickets;
	  $this->mstrIsCardNameNCustomerNameMatch = $astrIsCardNameNCustomerNameMatch;
	  $this->msrtPNR                          = $asrtPNR;
	  $this->mstrSectorFrom                   = $astrSectorFrom;
	  $this->mstrSectorTo                     = $astrSectorTo;
	  $this->airLineInfoIsAvailable			= "YES";
	}

	function getBookingDate()
	{
	  return $this->mstrBookingDate ;
	}
	function getAirLineFlag()
	{
	  $this->airLineInfoIsAvailable ;
	}
	function getFlightDate()
	{
	  return $this->mstrFlightDate;
	}
	function getFlighttime()
	{
	  return  $this->mstrFlightTime;
    }
    function getFlightNumber()
    {
	  return $this->mstrFlightNumber;
	}
	function getPassengerName()
	{
		return   $this->mstrPassengerName;
	}
	function getNumberOfTickets()
	{
	  return   $this->mstrNumberOfTickets;
	}
	function getIsCardNameNCustomerNameMatch()
	{
		return $this->mstrIsCardNameNCustomerNameMatch;
	}
	function getPNR()
	{
		return  $this->msrtPNR;
	}
	function getSectorFrom()
	{
	  return  $this->mstrSectorFrom;
	}
	function getSecotrTo()
	{
		return $this->mstrSectorTo;
	}
}

function toString()
 	{

	return "The  Air Line Transctions Details  is \n".
			"Booking Date : 	".$this->mstrBookingDate."\n".
			"Flight Date:  "	 .$this->mstrFlightDate."\n".
			"Flight Time: 	  ".$this->mstrFlightTime ."\n".
			"FlightNumber:     ".$this->mstrFlightNumber ."\n".
			"Passenger Name:	 ".$this->mstrPassengerName."\n".
			"NumberOfTickets:  ".$this->mstrNumberOfTickets."\n".
			"Is Card Name and Customer Name Match : ".$this->mstrIsCardNameNCustomerNameMatch."\n".
			"PNR:	 	 ".$this->msrtPNR."\n".
			"Sector From:	 	 ".$this->mstrSectorFrom."\n".
			"SectorTo:	 	 ".$this->mstrSectorTo."\n".
			"AirLineInfo Is Available:	 	 ".$this->airLineInfoIsAvailable;

	}
?>