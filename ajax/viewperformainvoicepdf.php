<?php 
	include('../functions/phpfunctions.php');

	$lastslno = $_POST['invoicelastslno'];
	
	if($lastslno == '')
	{
		$url = '../home/index.php?a_link=home_dashboard'; 
		header("location:".$url);
	}
	else
	{
		vieworgeneratepdfperforminvoice($lastslno,'view');
	}
?>