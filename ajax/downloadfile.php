<?php 
	include('../functions/phpfunctions.php');
	
	if(imaxgetcookie('dealeruserid') == false) { $url = '../index.php'; header("Location:".$url); }
	else
	$userid = imaxgetcookie('dealeruserid');
	$id = $_GET['id'];
	$filename = 'filepath'.$id;
	$filepath = $_REQUEST[$filename];
	if($filepath == '')
	{
		$url = '../home/index.php?a_link=dashboard'; 
		header("location:".$url);
	}
	else
	{
		viewfilepath($filepath);
	}
	
function viewfilepath($filepath)
{
	$filename = explode('/',$filepath);
				
	$fp = fopen($filename[5],"wa+");
	if($fp)
	{
		downloadfile($filepath);
		fclose($fp);
	}
}
?>