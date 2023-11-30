<?php 
	include('../functions/phpfunctions.php');
	$lastslno = $_POST['lastslno'];
	
	if($_POST['invoicelastslno'] <> '')
	{
		$lastslno  = $_POST['invoicelastslno'];
	}
	if($_POST['onlineslno'] <> '')
	{
		$lastslno  = $_POST['onlineslno'];
	}
	
	if($lastslno == '')
	{
		$url = '../home/index.php?a_link=home_dashboard'; 
		header("location:".$url);
	}
	else
	{
		vieworgeneratedealerpdfinvoice($lastslno,'view');
	}
?>