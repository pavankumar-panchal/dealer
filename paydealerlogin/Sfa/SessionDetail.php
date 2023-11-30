<?php
class SessionDetail
{
	var $mstrTransactionIPAddr;
	var $mstrBrowserCountry;
	var $mstrBrowserLocalLang;
	var $mstrBrowserLocalLangVariant ;
	var $mstrBrowserUserAgent;
	var $mstrSecureCookie;
	var $sessionDetailIsAvailable;

	function setSessionDetails($astrTransactionIPAddr,$astrSecureCookie,$astrBrowserCountry,$astrBrowserLocalLang,$astrBrowserLocalLangVariant
	   								,$astrBrowserUserAgent)
	   {
	     $this->mstrBrowserCountry			=	$astrBrowserCountry;
	     $this->mstrBrowserLocalLang			=	$astrBrowserLocalLang;
	     $this->mstrBrowserLocalLangVariant	=	$astrBrowserLocalLangVariant;
	     $this->mstrBrowserUserAgent			=	$astrBrowserUserAgent;
	     $this->mstrSecureCookie				=	$astrSecureCookie;
	     $this->mstrTransactionIPAddr			=	$astrTransactionIPAddr;
	     $this->sessionDetailIsAvailable		=	"YES";

	    }
	    function getSessionDetailFlag()
			{
			  return $this->sessionDetailIsAvailable ;
		}
		function getTransactionIPAddr()
		{
			return $this->mstrTransactionIPAddr;
		}
	   function getBrowserCountry()
	   {
		   return $this->mstrBrowserCountry;
	   }
	   function getBrowserLocalLang()
	   {
		   return $this->mstrBrowserLocalLang;
	   }
	   function getBrowserLocalLangVariant()
	   {
		   return $this->mstrBrowserLocalLangVariant;
	   }
	   function getBrowserUserAgent()
	   {
		   return $this->mstrBrowserUserAgent;
	   }
	   function getSecureCookie()
	   {
	     return $this->mstrSecureCookie;
   	   }
   	   function toString()
   	   {
         return "The Session Details Details  is \n".
		 			"TransactionIP Address : ".$this->mstrTransactionIPAddr."\n".
		 			"Browser Country:  "	 .$this->mstrBrowserCountry."\n".
		 			"Browser Local Lang: 	 ".$this->mstrBrowserLocalLang ."\n".
		 			"Browser Local Lang Variant :".$this->mstrBrowserLocalLangVariant ."\n".
		 			"Browser User Agent:	 ".$this->mstrBrowserUserAgent."\n".
		 			"Secure Cookie:  ".$this->mstrSecureCookie."\n".
		 			"Session Detail Is Available: ".$this->sessionDetailIsAvailable;


   	   }
}
?>