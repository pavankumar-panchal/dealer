
<?php
 class MerchanDise
{
	var $mstrItemPurchased;
	var $mstrBuyersName;
	var $mstrModelNumber;
	var $mstrBrand;
	var $mstrQuantity;
	var $mstrIsCardNameNBuyerNameMatch;
	var $mstrDiseIsAvailable;


	function setMerchanDiseDetails($astrItemPurchased,$astrQuantity,$astrBrand,
											 $astrModelNumber,$astrBuyersName,
											 $astrIsCardNameNBuyerNameMatch)
	{
	  $this->mstrItemPurchased       	      = $astrItemPurchased ;
	  $this->mstrQuantity                   = $astrQuantity;
	  $this->mstrBrand                   	  = $astrBrand;
	  $this->mstrModelNumber                = $astrModelNumber;
	  $this->mstrBuyersName                 = $astrBuyersName;
	  $this->mstrIsCardNameNBuyerNameMatch  = $astrIsCardNameNBuyerNameMatch;
	  $this->mstrDiseIsAvailable			  = "YES";
	}

	function getMerchantFlag()
	{
	  return $this->mstrDiseIsAvailable ;
	}
	function getItemPurchased()
	{
	  return $this->mstrItemPurchased ;
	}
	function getQuantity()
	{
	  return $this->mstrQuantity;
	}
	function  getBrand()
	{
	  return  $this->mstrBrand;
    }
    function getModelNumber()
    {
	  return $this->mstrModelNumber;
	}

	function getBuyersName()
	{
	  return   $this->mstrBuyersName;
	}
	function getIsCardNameNBuyerNameMatch()
	{
		return $this->mstrIsCardNameNBuyerNameMatch;
	}

   function toString()
   {
      return "The Merchat Dise  Details Details  is \n".
	  		 			"Item Purchased : ".$this->mstrItemPurchased."\n".
	  		 			"Buyers Name:  "	 .$this->mstrBuyersName."\n".
	  		 			"Model Number: 	 ".$this->mstrModelNumber ."\n".
	  		 			"Brand :".$this->mstrBrand ."\n".
	  		 			"Quantity:	 ".$this->mstrQuantity."\n".
	  		 			"Is Card Name N Buyer Name Match:  ".$this->mstrIsCardNameNBuyerNameMatch."\n".
	  		 			"Dise Is Available: ".$this->mstrDiseIsAvailable;

   }
}
?>