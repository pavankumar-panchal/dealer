<?php
require_once("java/Java.inc");
//equire_once("java/Java.inc");
#include("com.opus.epg.sfa.java");

class PostLibPHP{

	var $epgURL;
	var $sslURL;
	var $motoURL;
	var $mstrKeyDir;
	var $mstrOSType;
	var $verbose;


	function PostLibPHP()
	{
		#PostLibPHP::readPropFile();

	}

	function readPropFile()
	{

		$fp = fopen ("sfa.properties","r");


		 $line1 = fgets($fp,1024);
		  $linearray = explode( "=",$line1);
		 if($linearray[0] == "motoURL")
		  $this->motoURL=$linearray[1];

		print "$this->motoURL"."<br>";


		$line2 = fgets($fp,1024);
		$linearray = explode( "=",$line2);
		if($linearray[0] == "sslURL")
		  $this->sslURL=$linearray[1];
		print "$this->sslURL"."<br>";


		$line3 = fgets($fp,1024);
		$linearray = explode( "=",$line3);
		if($linearray[0] == "verbose")
		$this->verbose=$linearray[1];

		print "$this->verbose"."<br>";

		$line4 = fgets($fp,1024);
		$linearray = explode( "=",$line4);
		if($linearray[0] == "Key.Directory")
		$this->mstrKeyDir=$linearray[1];
		print "$this->mstrKeyDir"."<br>";

		$line5 = fgets($fp,1024);
		$linearray = explode( "=",$line5);
		if($linearray[0] == "OS.Type")
		$this->mstrOSType=$linearray[1];
		print "$this->mstrOSType"."<br>";

		$line6 = fgets($fp,1024);
		$linearray = explode( "=",$line6);
		if($linearray[0] == "epgURL")
		$this->epgURL=$linearray[1];
		print "$this->epgURL"."<br>";

		fclose($fp);

	}

	###################################################################
	# postMoto function is used by MOTO Merchants this function
	# takes the php objects assign it to java object and call
	# the postMoto function of java SFA and return the
	# PGResponse object which contains the PGresponse receieved from java SFA
	###################################################################

 	function postMoto($aoBTA,$aoSTA,$aoMerchant,$aoMPI,$aoCInfo,$aoReserveData,$aoCustomerDetails,$aoSessionDetails,$aoAirLineTransaction,$aoMerchanDise) {


		#PostLibPHP::readPropFile();

		## Creating Instance of Java classes from the Java Sfa component.
		#
		#
		##
 		$ojBTA = new Java('com.opus.epg.sfa.java.BillToAddress');
 		$ojSTA = new Java('com.opus.epg.sfa.java.ShipToAddress');
 		$ojMerchant = new Java('com.opus.epg.sfa.java.Merchant');
 		$ojMPI = new Java('com.opus.epg.sfa.java.MPIData');
 		$ojCInfo = new Java('com.opus.epg.sfa.java.CardInfo');
 		$oPGResp = new Java('com.opus.epg.sfa.java.PGResponse');
 		$oPostLib = new Java('com.opus.epg.sfa.java.PostLib');
 		$ojReserveData = new Java('com.opus.epg.sfa.java.PGReserveData');

 	    # Added by Amit Paliwal on 17/10/2007 (Bharosa Phase II )ELTOPUSPRD-19918

        $ojHomeAddress = new Java('com.opus.epg.sfa.java.Address');
        $ojOfficeAddress = new Java('com.opus.epg.sfa.java.Address');

 		$ojSessionDetails=new Java('com.opus.epg.sfa.java.SessionDetail');
 		$ojAirlineTransaction=new Java('com.opus.epg.sfa.java.AirLineTransaction');
 		$ojMerchantDisc=new Java('com.opus.epg.sfa.java.MerchanDise');
 		$ojCustomerDetails=new Java('com.opus.epg.sfa.java.CustomerDetails');



 		$oPGResphp	=	new	PGResponse();

 		## Mandetory checks for Merchant objects
 		#  check for Merchant id and the Message type
 		#
 		##
 		if($aoMerchant == null ){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant object is null");
			return $oPGResphp;
		}

		if($aoMerchant->getMerchantID() == null || $aoMerchant->getMerchantID() == "" ){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant id is null or Invalid");
			return $oPGResphp;
		}

		if($aoMerchant->getMessageType() == null || $aoMerchant->getMessageType() == "") {
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Message type is null.");
			return $oPGResphp;

		}


 		#  Assigning Merchant object of php to Merchant object of java Sfa

 		$ojMerchant->setMerchantDetails( $aoMerchant->getMerchantID(), $aoMerchant->getVendor(), $aoMerchant->getPartner(),$aoMerchant->getCustIPAddress(), $aoMerchant->getMerchantTxnID(), $aoMerchant->getOrderReferenceNo(),$aoMerchant->getRespURL(),$aoMerchant->getRespMethod(),$aoMerchant->getCurrCode(),$aoMerchant->getInvoiceNo(), $aoMerchant->getMessageType(), $aoMerchant->getAmount(), $aoMerchant->getGMTTimeOffset(), $aoMerchant->getExt1(), $aoMerchant->getExt2(), $aoMerchant->getExt3(), $aoMerchant->getExt4(), $aoMerchant->getExt5());

 		#  Assigning Billto Address object of php to Billto Address object of java Sfa
 		#
 		if($aoBTA != null || $aoBTA != "")  {
 			$ojBTA->setAddressDetails($aoBTA->getCustomerId(),$aoBTA->getName(),$aoBTA->getAddrLine1(),$aoBTA->getAddrLine2(),$aoBTA->getAddrLine3(),$aoBTA->getCity(),$aoBTA->getState(),$aoBTA->getZip(),$aoBTA->getCountryAlphaCode(),$aoBTA->getEmail());
 		}

 		#  Assigning Ship To Address object of php to Ship to Address object of java Sfa
 		#

 		if($aoSTA != null || $aoSTA != "")  {
 			$ojSTA->setAddressDetails($aoSTA->getAddrLine1(),$aoSTA->getAddrLine2(),$aoSTA->getAddrLine3(),$aoSTA->getCity(),$aoSTA->getState(),$aoSTA->getZip(),$aoSTA->getCountryAlphaCode(),$aoSTA->getEmail());
 		}

 		#  Assigning MPI object of php to MPI object of java Sfa
		#

 		if($aoMPI != null || $aoMPI != "")  {
 			$ojMPI->setMPIResponseDetails($aoMPI->getECI(),$aoMPI->getXID(),$aoMPI->getVBVStatus(),$aoMPI->getCAVV(),$aoMPI->getShoppingContext(),$aoMPI->getPurchaseAmount(),$aoMPI->getCurrencyVal());
 		}

 		#   Assigning Card Info object of php to Card info object of java Sfa
 		#

 		if($aoCInfo != null || $aoCInfo != "")  {
 			$ojCInfo->setCardDetails($aoCInfo->getCardType(),$aoCInfo->getCardNum(),$aoCInfo->getCVVNum(),$aoCInfo->getExpDtYr(),$aoCInfo->getExpDtMon(),$aoCInfo->getNameOnCard(),$aoCInfo->getInstrType());
 		}

 		if($aoReserveData != null || $aoReserveData != ""){
 			$ojReserveData->setReserveObj($aoReserveData->getReserveField1(),$aoReserveData->getReserveField2(),$aoReserveData->getReserveField3(),$aoReserveData->getReserveField4(),$aoReserveData->getReserveField5(),$aoReserveData->getReserveField6(),$aoReserveData->getReserveField7(),$aoReserveData->getReserveField8(),$aoReserveData->getReserveField9(),$aoReserveData->getReserveField10());
 		}

       # Added by Amit Paliwal on 17/10/2007 (Bharosa Phase II )ELTOPUSPRD-19918

       if($aoCustomerDetails <> null){

	    $lPhpOffAdd = $aoCustomerDetails->getOfficeAddress() ;
		   if($lPhpOffAdd <> null){
				$ojOfficeAddress->setAddressDetails($lPhpOffAdd->getAddrLine1(), $lPhpOffAdd->getAddrLine2(), $lPhpOffAdd->getAddrLine3(), $lPhpOffAdd->getCity(), $lPhpOffAdd->getState(), $lPhpOffAdd->getZip(), $lPhpOffAdd->getCountryAlphaCode(), $lPhpOffAdd->getEmailId() ) ;
			}
		$lPhpHomeAdd = $aoCustomerDetails->getHomeAddress() ;
			if($lPhpHomeAdd <> null){
				$ojHomeAddress->setAddressDetails($lPhpHomeAdd->getAddrLine1(), $lPhpHomeAdd->getAddrLine2(), $lPhpHomeAdd->getAddrLine3(), $lPhpHomeAdd->getCity(), $lPhpHomeAdd->getState(), $lPhpHomeAdd->getZip(), $lPhpHomeAdd->getCountryAlphaCode(), $lPhpHomeAdd->getEmailId() ) ;
			}
		$ojCustomerDetails->setCustomerDetails($aoCustomerDetails->getFirstName(),$aoCustomerDetails->getLastName(), $ojOfficeAddress, $ojHomeAddress, $aoCustomerDetails->getMobileNo(),$aoCustomerDetails->getRegDate(),$aoCustomerDetails->getBillNShipAddrMatch());
		}
		if($aoAirLineTransaction <> null){
			$ojAirlineTransaction->setAirLineTransactionDetails($aoAirLineTransaction->getBookingDate(),$aoAirLineTransaction->getFlightDate(),$aoAirLineTransaction->getFlighttime(),$aoAirLineTransaction->getFlightNumber(),$aoAirLineTransaction->getPassengerName(),$aoAirLineTransaction->getNumberOfTickets(),$aoAirLineTransaction->getIsCardNameNCustomerNameMatch(),$aoAirLineTransaction->getPNR(),$aoAirLineTransaction->getSectorFrom(),$aoAirLineTransaction->getSecotrTo());
		}
		if($aoSessionDetails <> null){
			$ojSessionDetails->setSessionDetails($aoSessionDetails->getTransactionIPAddr(),$aoSessionDetails->getSecureCookie(),$aoSessionDetails->getBrowserCountry(),$aoSessionDetails->getBrowserLocalLang(),$aoSessionDetails->getBrowserLocalLangVariant(),$aoSessionDetails->getBrowserUserAgent());
		}
		if($aoMerchanDise <> null){
			$ojMerchantDisc->setMerchanDiseDetails($aoMerchanDise->getItemPurchased(),$aoMerchanDise->getQuantity(),$aoMerchanDise->getBrand(),$aoMerchanDise->getModelNumber(),$aoMerchanDise->getBuyersName(),$aoMerchanDise->getIsCardNameNBuyerNameMatch());
		}

 		# Calling the postMoto of java Sfa and passing java objects to it
 		# The function return PGResponse object of java Sfa.

 		$oPGResp=$oPostLib->postMOTO($ojBTA,$ojSTA,$ojMerchant,$ojMPI,$ojCInfo,$ojReserveData,$ojCustomerDetails,$ojSessionDetails,$ojAirlineTransaction,$ojMerchantDisc);

 		# Assigning PGResponse objects of java to PGResponse object of php

		$oPGResphp->setRespCode($oPGResp->getRespCode());
		$oPGResphp->setRespMessage($oPGResp->getRespMessage());
		$oPGResphp->setTxnId($oPGResp->getTxnId());
		$oPGResphp->setEpgTxnId($oPGResp->getEpgTxnId());
		$oPGResphp->setAuthIdCode($oPGResp->getAuthIdCode());
		$oPGResphp->setRRN($oPGResp->getRRN());

		$oPGResphp->setCVRespCode($oPGResp->getCVRespCode());
		$oPGResphp->setReserveFld1($oPGResp->getReserveFld1());
		$oPGResphp->setReserveFld2($oPGResp->getReserveFld2());
		$oPGResphp->setReserveFld3($oPGResp->getReserveFld3());
		$oPGResphp->setReserveFld4($oPGResp->getReserveFld4());
		$oPGResphp->setReserveFld5($oPGResp->getReserveFld5());
		$oPGResphp->setReserveFld6($oPGResp->getReserveFld6());
		$oPGResphp->setReserveFld7($oPGResp->getReserveFld7());
		$oPGResphp->setReserveFld8($oPGResp->getReserveFld8());
		$oPGResphp->setReserveFld9($oPGResp->getReserveFld9());
		$oPGResphp->setReserveFld10($oPGResp->getReserveFld10());

		# Added by Amit Paliwal on 17/10/2007 (Bharosa Phase II )ELTOPUSPRD-19918

		$oPGResphp->setCookie($oPGResp->getCookie());
		$oPGResphp->setFDMSResult($oPGResp->getFDMSResult());
		$oPGResphp->setFDMSScore($oPGResp->getFDMSScore());



 		# Returning PGResponse object of php

 		return $oPGResphp ;

 	}

	###################################################################
	# postSSL function is used by SSL Merchants this function
	# takes the php objects assign it to java object and call
	# the postSSL function of java SFA and return the
	# PGResponse object which contains the PGresponse receieved from java SFA
	###################################################################


 	function postSSL($aoBTA,$aoSTA,$aoMerchant,$aoMPI,$aoReserveData,$aoCustomerDetails,$aoSessionDetails,$aoAirLineTransaction,$aoMerchanDise) {


		## Creating Instance of Java classes from the Java Sfa component.
		#
		#
		##
		$ojBTA = new Java('com.opus.epg.sfa.java.BillToAddress');
		$ojSTA = new Java('com.opus.epg.sfa.java.ShipToAddress');
		$ojMerchant = new Java('com.opus.epg.sfa.java.Merchant');
		$ojMPI = new Java('com.opus.epg.sfa.java.MPIData');
		$ojCInfo = new Java('com.opus.epg.sfa.java.CardInfo');
		$oPGResp = new Java('com.opus.epg.sfa.java.PGResponse');
		$oPostLib = new Java('com.opus.epg.sfa.java.PostLib');

		$ojReserveData = new Java('com.opus.epg.sfa.java.PGReserveData');
		#$ojHttpResponse =  new Java('javax.servlet.http.HttpServletResponse');
		#$ojHttpResponse =  new Java('java.lang.Object');
		$oresponse = null;
		$oPGResphp	=	new	PGResponse();

	    # Added by Amit Paliwalon 17/10/2007 (Bharosa Phase II )ELTOPUSPRD-19918

		$ojHomeAddress = new Java('com.opus.epg.sfa.java.Address');
		$ojOfficeAddress = new Java('com.opus.epg.sfa.java.Address');

		$ojSessionDetails=new Java('com.opus.epg.sfa.java.SessionDetail');
		$ojAirlineTransaction=new Java('com.opus.epg.sfa.java.AirLineTransaction');
		$ojMerchantDisc=new Java('com.opus.epg.sfa.java.MerchanDise');
		$ojCustomerDetails=new Java('com.opus.epg.sfa.java.CustomerDetails');


		## Mandetory checks for Merchant objects
		#  check for Merchant id and the Message type
		#
 		##

		if($aoMerchant == null ){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant object is null");
			return $oPGResphp;
		}

		if($aoMerchant->getMerchantID() == null || $aoMerchant->getMerchantID() == "" ){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant id is null or Invalid");
			return $oPGResphp;
		}

		if($aoMerchant->getMessageType() == null || $aoMerchant->getMessageType() == "") {
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Message type is null.");
			return $oPGResphp;

		}


		## Assigning Merchant object of php to Merchant object of java Sfa
		#

		$ojMerchant->setMerchantDetails( $aoMerchant->getMerchantID(), $aoMerchant->getVendor(), $aoMerchant->getPartner(),$aoMerchant->getCustIPAddress(), $aoMerchant->getMerchantTxnID(), $aoMerchant->getOrderReferenceNo(),$aoMerchant->getRespURL(),$aoMerchant->getRespMethod(),$aoMerchant->getCurrCode(),$aoMerchant->getInvoiceNo(), $aoMerchant->getMessageType(), $aoMerchant->getAmount(), $aoMerchant->getGMTTimeOffset(), $aoMerchant->getExt1(), $aoMerchant->getExt2(), $aoMerchant->getExt3(), $aoMerchant->getExt4(), $aoMerchant->getExt5());

		#  Assigning Billto Address object of php to Billto Address object of java Sfa
 		#

		if($aoBTA != null || $aoBTA != "")  {
			$ojBTA->setAddressDetails($aoBTA->getCustomerId(),$aoBTA->getName(),$aoBTA->getAddrLine1(),$aoBTA->getAddrLine2(),$aoBTA->getAddrLine3(),$aoBTA->getCity(),$aoBTA->getState(),$aoBTA->getZip(),$aoBTA->getCountryAlphaCode(),$aoBTA->getEmail());
		}

		#  Assigning Ship To Address object of php to Ship to Address object of java Sfa
 		#

		if($aoSTA != null || $aoSTA != "")  {
			$ojSTA->setAddressDetails($aoSTA->getAddrLine1(),$aoSTA->getAddrLine2(),$aoSTA->getAddrLine3(),$aoSTA->getCity(),$aoSTA->getState(),$aoSTA->getZip(),$aoSTA->getCountryAlphaCode(),$aoSTA->getEmail());
		}

		#  Assigning MPI object of php to MPI object of java Sfa
		#

		if($aoMPI != null || $aoMPI != "")  {
			$ojMPI->setMPIRequestDetails($aoMPI->getPurchaseAmount(),$aoMPI->getDisplayAmount(),$aoMPI->getCurrencyVal(),$aoMPI->getExponent(),$aoMPI->getOrderDesc(),$aoMPI->getRecurFreq(),$aoMPI->getRecurEnd(),$aoMPI->getInstallment(),$aoMPI->getDeviceCategory(),$aoMPI->getWhatIUse(),$aoMPI->getAcceptHdr(),$aoMPI->getAgentHdr());
		}

		if($aoReserveData != null || $aoReserveData != ""){
			$ojReserveData->setReserveObj($aoReserveData->getReserveField1(),$aoReserveData->getReserveField2(),$aoReserveData->getReserveField3(),$aoReserveData->getReserveField4(),$aoReserveData->getReserveField5(),$aoReserveData->getReserveField6(),$aoReserveData->getReserveField7(),$aoReserveData->getReserveField8(),$aoReserveData->getReserveField9(),$aoReserveData->getReserveField10());
 		}

 		# Added by Amit Paliwal on 17/10/2007 (Bharosa Phase II )ELTOPUSPRD-19918

        if($aoCustomerDetails <> null){

			$lPhpOffAdd = $aoCustomerDetails->getOfficeAddress() ;
			if($lPhpOffAdd <> null){
			  	$ojOfficeAddress->setAddressDetails($lPhpOffAdd->getAddrLine1(), $lPhpOffAdd->getAddrLine2(), $lPhpOffAdd->getAddrLine3(), $lPhpOffAdd->getCity(), $lPhpOffAdd->getState(), $lPhpOffAdd->getZip(), $lPhpOffAdd->getCountryAlphaCode(), $lPhpOffAdd->getEmailId() ) ;
              }
			$lPhpHomeAdd = $aoCustomerDetails->getHomeAddress() ;
		    if($lPhpHomeAdd <> null){
				$ojHomeAddress->setAddressDetails($lPhpHomeAdd->getAddrLine1(), $lPhpHomeAdd->getAddrLine2(), $lPhpHomeAdd->getAddrLine3(), $lPhpHomeAdd->getCity(), $lPhpHomeAdd->getState(), $lPhpHomeAdd->getZip(), $lPhpHomeAdd->getCountryAlphaCode(), $lPhpHomeAdd->getEmailId() ) ;
              }

			$ojCustomerDetails->setCustomerDetails($aoCustomerDetails->getFirstName(),$aoCustomerDetails->getLastName(), $ojOfficeAddress, $ojHomeAddress, $aoCustomerDetails->getMobileNo(),$aoCustomerDetails->getRegDate(),$aoCustomerDetails->getBillNShipAddrMatch());
		}
        if($aoAirLineTransaction <> null){
			$ojAirlineTransaction->setAirLineTransactionDetails($aoAirLineTransaction->getBookingDate(),$aoAirLineTransaction->getFlightDate(),$aoAirLineTransaction->getFlighttime(),$aoAirLineTransaction->getFlightNumber(),$aoAirLineTransaction->getPassengerName(),$aoAirLineTransaction->getNumberOfTickets(),$aoAirLineTransaction->getIsCardNameNCustomerNameMatch(),$aoAirLineTransaction->getPNR(),$aoAirLineTransaction->getSectorFrom(),$aoAirLineTransaction->getSecotrTo());
		}
		if($aoSessionDetails <> null){
			$ojSessionDetails->setSessionDetails($aoSessionDetails->getTransactionIPAddr(),$aoSessionDetails->getSecureCookie(),$aoSessionDetails->getBrowserCountry(),$aoSessionDetails->getBrowserLocalLang(),$aoSessionDetails->getBrowserLocalLangVariant(),$aoSessionDetails->getBrowserUserAgent());
		}
		if($aoMerchanDise <> null){
			$ojMerchantDisc->setMerchanDiseDetails($aoMerchanDise->getItemPurchased(),$aoMerchanDise->getQuantity(),$aoMerchanDise->getBrand(),$aoMerchanDise->getModelNumber(),$aoMerchanDise->getBuyersName(),$aoMerchanDise->getIsCardNameNBuyerNameMatch());
		}

		# Calling the postSSL of java Sfa and passing java objects to it
		# The function return PGResponse object of java Sfa.

		$oPGResp=$oPostLib->postSSL($ojBTA,$ojSTA,$ojMerchant,$ojMPI,$oresponse,$ojReserveData,$ojCustomerDetails,$ojSessionDetails,$ojAirlineTransaction,$ojMerchantDisc);

		# Assigning PGResponse objects of java to PGResponse object of php

		$oPGResphp->setRespCode($oPGResp->getRespCode());
		$oPGResphp->setRespMessage($oPGResp->getRespMessage());
		$oPGResphp->setTxnId($oPGResp->getTxnId());
		$oPGResphp->setEpgTxnId($oPGResp->getEpgTxnId());
		$oPGResphp->setRedirectionTxnID($oPGResp->getRedirectionTxnId());
		$oPGResphp->setRedirectionUrl($oPGResp->getRedirectionUrl());

		$oPGResphp->setCVRespCode($oPGResp->getCVRespCode());
		$oPGResphp->setReserveFld1($oPGResp->getReserveFld1());
		$oPGResphp->setReserveFld2($oPGResp->getReserveFld2());
		$oPGResphp->setReserveFld3($oPGResp->getReserveFld3());
		$oPGResphp->setReserveFld4($oPGResp->getReserveFld4());
		$oPGResphp->setReserveFld5($oPGResp->getReserveFld5());
		$oPGResphp->setReserveFld6($oPGResp->getReserveFld6());
		$oPGResphp->setReserveFld7($oPGResp->getReserveFld7());
		$oPGResphp->setReserveFld8($oPGResp->getReserveFld8());
		$oPGResphp->setReserveFld9($oPGResp->getReserveFld9());
		$oPGResphp->setReserveFld10($oPGResp->getReserveFld10());

        # Added by Amit Paliwal on 17/10/2007 (Bharosa Phase II )ELTOPUSPRD-19918

		$oPGResphp->setCookie($oPGResp->getCookie());
		$oPGResphp->setFDMSResult($oPGResp->getFDMSResult());
		$oPGResphp->setFDMSScore($oPGResp->getFDMSScore());


		# Returning PGResponse Object of php

		return $oPGResphp ;



 	}

 	##########################################################################
 	# postRelatedTxn function is used for doing Online Related transaction
 	# it takes merchant object of php and assign it to java object and call
 	# the postRelated function of java sfa and this function return PGResponse
 	# object which contains the response receieved from java SFA
 	##########################################################################

 	function postRelatedTxn($aoMerchant) {

		## Creating Instance of Java classes from the Java Sfa component.
		#
		#
		##

		$ojMerchant = new Java('com.opus.epg.sfa.java.Merchant');
		$oPGResp = new Java('com.opus.epg.sfa.java.PGResponse');
		$oPostLib = new Java('com.opus.epg.sfa.java.PostLib');

		$oPGResphp	=	new	PGResponse();

		## Mandetory checks for Merchant and Merchant object containing
		#  checks for Merchant id , Root system Reference number
		#  Message type  in case of the related transaction.
		##

		if($aoMerchant == null ){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant object is null");
			return $oPGResphp;
		}

		if($aoMerchant->getMerchantID() == null || $aoMerchant->getMerchantID() == "" ){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant id is null or Invalid");
			return $oPGResphp;
		}
		if($aoMerchant->getRootTxnSysRefNum() ==  null || $aoMerchant->getRootTxnSysRefNum() == ""){
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Merchant Root System Reference number is null.");
			return $oPGResphp;
		}

		if($aoMerchant->getMessageType() == null || $aoMerchant->getMessageType() == "") {
			$oPGResphp->setRespCode("2");
			$oPGResphp->setRespMessage(" Message type is null.");
			return $oPGResphp;

		}

		# Setting Java Object of Merchant from Php Merchant object

		$ojMerchant->setMerchantRelatedTxnDetails($aoMerchant->getMerchantID(), $aoMerchant->getVendor(), $aoMerchant->getPartner(), $aoMerchant->getMerchantTxnID(), $aoMerchant->getRootTxnSysRefNum(), $aoMerchant->getRootPNRefNum(),$aoMerchant->getRootAuthCode(), $aoMerchant->getRespURL(),$aoMerchant->getRespMethod(), $aoMerchant->getCurrCode(), $aoMerchant->getMessageType(), $aoMerchant->getAmount(), $aoMerchant->getGMTTimeOffset(), $aoMerchant->getExt1(), $aoMerchant->getExt2(), $aoMerchant->getExt3(), $aoMerchant->getExt4(), $aoMerchant->getExt5());


		# Calling postRelated function of Java Sfa and passing java object.

		$oPGResp=$oPostLib->postRelated($ojMerchant);

		# Assigning PGResponse objects of java to PGResponse object of php

		$oPGResphp->setRespCode($oPGResp->getRespCode());
		$oPGResphp->setRespMessage($oPGResp->getRespMessage());
		$oPGResphp->setTxnId($oPGResp->getTxnId());
		$oPGResphp->setEpgTxnId($oPGResp->getEpgTxnId());
		$oPGResphp->setRedirectionTxnID($oPGResp->getRedirectionTxnId());
		$oPGResphp->setRedirectionUrl($oPGResp->getRedirectionUrl());
		$oPGResphp->setAuthIdCode($oPGResp->getAuthIdCode());
		$oPGResphp->setRRN($oPGResp->getRRN());
		$oPGResphp->setTxnType($oPGResp->getTxnType());
		$oPGResphp->setCVRespCode($oPGResp->getCVRespCode());


		# Returning PGResponse object of php

		return $oPGResphp ;



 	}

	###########################################################################
	# postTxnSearch function is used for Online search of Transactions
	# it takes merchant object of php and assign it to java object and call
	# the postTxnSearch function of java sfa and this function return PGResponse
 	# object which contains the response receieved from java SFA
	############################################################################

	function postTxnSearch($aoMerchant) {

		## Creating Instance of Java classes from the Java Sfa component.
		#
		#
		##
		$ojMerchant = new Java('com.opus.epg.sfa.java.Merchant');
		$oPGResp = new Java('com.opus.epg.sfa.java.PGResponse');
		$oPGSearchResp= new Java('com.opus.epg.sfa.java.PGSearchResponse');
		$oPostLib = new Java('com.opus.epg.sfa.java.PostLib');
		$oArraylist = new Java('java.util.ArrayList');

		$oPGSearchResphp = new PGSearchResponse();

		## Mandetory checks for Merchant and Merchant object containing
		#  checks for Merchant id
		#
		##


		if($aoMerchant == null ){
			$oPGSearchResphp->setRespCode("2");
			$oPGSearchResphp->setRespMessage("Merchant object is null");
			return $oPGSearchResphp;
		}
		if($aoMerchant->getMerchantID() == null || $aoMerchant->getMerchantID() == "" ){
			$oPGSearchResphp->setRespCode("2");
			$oPGSearchResphp->setRespMessage("Merchant id is null or Invalid");
			return $oPGSearchResphp;
		}

		# Setting Java Object of Merchant from Php Merchant object

		$ojMerchant->setMerchantTxnSearch($aoMerchant->getMerchantID(),$aoMerchant->getStartDate(),$aoMerchant->getEndDate());

		# Calling postTxnSearch of the Java Sfa

		$oPGSearchResp=$oPostLib->postTxnSearch($ojMerchant);

		# setting the Response code and response message recieved from the java Sfa
		# into the php PGSearchResponse Object

		$oPGSearchResphp->setRespCode($oPGSearchResp->getRespCode());
		$oPGSearchResphp->setRespMessage($oPGSearchResp->getRespMessage());

		$arraylistphp = array();

		# Calling function for assigning PGResponse objects of java to PGresponse object of php
		$arraylistphp = PostLibPHP::assignPGSearchResptophp($oPGSearchResp);

		$oPGSearchResphp->setPGResponseObjects($arraylistphp);

		return $oPGSearchResphp;


	}

	################################################################################
	# postStatusInq function is used for Online Inquiry of particular Transactions
	# it takes merchant object of php and assign it to java object and call
	# the postStatusInquiry function of java sfa and this function return PGResponse
	# object which contains the response receieved from java SFA
	################################################################################


	function postStatusInq($aoMerchant) {

		## Creating Instance of Java classes from the Java Sfa component.
		#
		#
		##

		$ojMerchant = new Java('com.opus.epg.sfa.java.Merchant');
		$oPGResp = new Java('com.opus.epg.sfa.java.PGResponse');
		$oPGSearchResp= new Java('com.opus.epg.sfa.java.PGSearchResponse');
		$oPostLib = new Java('com.opus.epg.sfa.java.PostLib');


		$oPGSearchResphp = new PGSearchResponse();

		# Mandetory checks for Merchant and Merchant object containing
		# checks for Merchant id  and and merchant transaction ID
		#
		#
		if($aoMerchant == null ){
			$oPGSearchResphp->setRespCode("2");
			$oPGSearchResphp->setRespMessage("Merchant object is null");
			return $oPGSearchResphp;
		}
		if($aoMerchant->getMerchantID() == null || $aoMerchant->getMerchantID() == "" ){
			$oPGSearchResphp->setRespCode("2");
			$oPGSearchResphp->setRespMessage("Merchant id is null or Invalid");
			return $oPGSearchResphp;
		}

		if($aoMerchant->getMerchantTxnID() == null || $aoMerchant->getMerchantTxnID() == "" ){
			$oPGSearchResphp->setRespCode("2");
			$oPGSearchResphp->setRespMessage("Merchant Transaction id is null or Invalid");
			return $oPGSearchResphp;
		}


		# Setting Java Object of Merchant from Php Merchant object

		$ojMerchant->setMerchantOnlineInquiry($aoMerchant->getMerchantID(),$aoMerchant->getMerchantTxnID());


		# calling status inquiry of java Sfa

		$oPGSearchResp=$oPostLib->postStatusInquiry($ojMerchant);

		$oPGSearchResphp->setRespCode($oPGSearchResp->getRespCode());
		$oPGSearchResphp->setRespMessage($oPGSearchResp->getRespMessage());

		$arraylistphp = array();

		# calling function for assigning PGResponse objects of java to PGResponse object of php

		$arraylistphp = PostLibPHP::assignPGSearchResptophp($oPGSearchResp);

		$oPGSearchResphp->setPGResponseObjects($arraylistphp);

		return $oPGSearchResphp;


	}

	###############################################################
	# function is used for assigning PGResponse objects of java
	# to PGResponse object of php storing the objects
	# into an array and returning it.
	################################################################
	function assignPGSearchResptophp($oPGSearchResp){

		$oArraylist = new Java('java.util.ArrayList');

		$oArraylist = $oPGSearchResp->getPGResponseObjects();

		if ($oArraylist == null){

		 return;
		}


		$size = (int)$oArraylist->size();

		$arraylistphp = array();


		for ( $i=0 ; $i < $size ; $i++ ){

			$oPGResp = new Java('com.opus.epg.sfa.java.PGResponse');
			$oPGResphp  =  new	PGResponse();

			$oPGResp = $oArraylist->get($i);


			$oPGResphp->setRespCode($oPGResp->getRespCode());
			$oPGResphp->setRespMessage($oPGResp->getRespMessage());
			$oPGResphp->setTxnId($oPGResp->getTxnId());
			$oPGResphp->setEpgTxnId($oPGResp->getEpgTxnId());
			$oPGResphp->setAuthIdCode($oPGResp->getAuthIdCode());
			$oPGResphp->setRRN($oPGResp->getRRN());
			$oPGResphp->setTxnType($oPGResp->getTxnType());
   			$oPGResphp->setTxnDateTime($oPGResp->getTxnDateTime());
   			$oPGResphp->setCVRespCode($oPGResp->getCVRespCode());

			#print  $oPGResp->getRespCode()." -- ".$oPGResp->getRespMessage()." -- ".$oPGResp->getTxnId()."<br>";

			array_push($arraylistphp , $oPGResphp);


		}

		return $arraylistphp ;

	}



}

?>
