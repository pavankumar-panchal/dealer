<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php'); 
include('../inc/checksession.php');

if(imaxgetcookie('dealeruserid')<> '') 
$userid = imaxgetcookie('dealeruserid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

$switchtytpe = $_POST['switchtype'];
switch($switchtytpe)
{
	case 'change':
	{
		$oldpassword = $_REQUEST['oldpassword'];
		$newpassword = $_REQUEST['newpassword'];
		$confirmpassword = $_REQUEST['confirmpassword'];
	
		$query="SELECT AES_DECRYPT(loginpassword,'imaxpasswordkey') as password,passwordchanged FROM inv_mas_dealer WHERE slno = '".$userid."';";
		$fetch = runmysqlqueryfetch($query);
		$dbpassword = $fetch['password'];
		if($dbpassword == $oldpassword)
		{
			if($oldpassword == $newpassword )
			echo("2^Existing password and New password should not be same");
			else
			{
				if($newpassword == $confirmpassword)
				{
					$query = "UPDATE inv_mas_dealer SET loginpassword = AES_ENCRYPT('".$newpassword."','imaxpasswordkey'),passwordchanged ='Y' WHERE slno ='".$userid."';";
					$result = runmysqlquery($query);
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','148','".date('Y-m-d').' '.date('H:i:s')."','Password changed successfully')";
					$eventresult = runmysqlquery($eventquery);
					echo("1^Your Password has been changed successfully");
				}
				else
				{
					echo("2^New Password does not match with the Confirm Password");
				}
			}
		}
		else
		{
			echo("2^Invalid Password");
		}
   }
   
}
?>