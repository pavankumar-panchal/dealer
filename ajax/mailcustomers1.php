<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('dealeruserid') <> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

include('../inc/checksession.php');

$passtype= $_POST['passtype'];

$id= Array();
if($passtype == mailcustomer)
{
  $p_slno = $_POST['pinv_slno'];
  
  $slno_array =  explode("," , $p_slno);

   
//$slno_array =14;
 //mailtopinvcustomers($slno_array);

for($i=0;$i<count($slno_array);$i++)
   {  
     
   
   $cus_query="SELECT slno,customerid FROM inv_spp_amc_pinv where slno=$slno_array[$i]";
   $cus_result=runmysqlquery($cus_query);
   $cus_fetch=mysqli_fetch_assoc($cus_result);
   $cusid=$cus_fetch['customerid'];
   $slnoo=$cus_fetch['slno'];
   $cusid=cusidsplit($cusid);
   $sent=$userid;
   $result=runmysqlquery("SELECT businessname FROM inv_mas_dealer where slno=$sent");
   $fetch=mysqli_fetch_assoc($result);
   $sentby=$fetch['businessname'];
   //$mail_date=date("Y-m-d");
$query="INSERT INTO `inv_spp_amc_mailer` ( `isap_id`, `maildate`, `sentby`) VALUES ( '$slnoo', NOW(), '$sentby')";

$query_amc="SELECT m_publishpinv FROM inv_spp_amc_customers WHERE customerid=$cusid";
$amc_result=runmysqlquery($query_amc);
$amc_fetch=mysqli_fetch_assoc($amc_result);
  if($amc_fetch['m_publishpinv']==1)
  {
runmysqlquery($query);
mailtopinvcustomers($slno_array[$i]);
  }
 else
  {
  echo "Mailing Option is not enable for this customer";
  } 
}


}
