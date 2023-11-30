<?php 
	include('../functions/phpfunctions.php');
	//var_dump($_POST);
	//exit();
	$lastslno = $_POST['lastslno'];
	if(imaxgetcookie('dealeruserid') == false) { $url = '../index.php'; header("Location:".$url); }
	else
	$userid = imaxgetcookie('dealeruserid');
	if( $_POST['onlineslno'] <> '')
	{
		$lastslno = $_POST['onlineslno'];
	}
	if( $_POST['implementationslno'] <> '')
	{
		$lastslno = $_POST['implementationslno'];
	}
	if($_POST['implonlineslno'] <> '')
	{
		$lastslno  = $_POST['implonlineslno'];
	}
	if($lastslno == '')
	{
		$url = '../home/index.php?a_link=dashboard'; 
		header("location:".$url);
	}
	else
	{
		vieworgeneratepdfinvoice($lastslno,'view');
	}
?>