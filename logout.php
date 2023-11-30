<?php
	include('./functions/phpfunctions.php');
	if(imaxgetcookie('dealeruserid') <> '' || imaxgetcookie('dealeruserid') <> false )
	{
		$userid = imaxgetcookie('dealeruserid'); 
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','149','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
	}
	imaxdealerlogout();
	header('Location:./index.php');
	
?>