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
	if($_POST['implonlineslno'] <> '')
	{
		$lastslno  = $_POST['implonlineslno'];
	}
	if($_POST['implementationslno'] <> '')
	{
		$lastslno  = $_POST['implementationslno'];
	}
	
	if($lastslno == '')
	{
		$url = '../home/index.php?a_link=home_dashboard'; 
		header("location:".$url);
	}
	else
	{
		vieworgeneratematrixpdfinvoice($lastslno,'view');
	}
?>