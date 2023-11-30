<?php
	include('../functions/phpfunctions.php');
	
	$receiptno = $_POST['receiptno'];
	$matrixreceiptno = $_POST['matrixreceiptno'];
	if($receiptno == '')
	{
		if($matrixreceiptno == '')
		{
			$url = '../home/index.php?a_link=home_dashboard'; 
			header("location:".$url);
		}
		else
		{
			viewreceipt('','view',$matrixreceiptno);
		}
	}
	else
	{
		viewreceipt($receiptno,'view');
	}

?>
