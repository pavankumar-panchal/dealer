<?php
include('../functions/phpfunctions.php');
$id = $_GET['id'];
$divid = $_GET['divid'];
$cusidval = 'cusid'.$id;
$cusid = $_REQUEST[$cusidval];
$date = datetimelocal('YmdHis-');
$namefile = "myfile".$id;
switch($id)
{
	case '1':
			{
				$filebasename = 'RAF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]['name']);
				$attachfilename = trim('RAF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]),'-');
			}
		break;
	case '2':
			{
				$filebasename = 'AIF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]['name']);
				$attachfilename = trim('AIF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]),'-');

			}
		break;
	case '3':
			{
				$filebasename = 'CRF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]['name']);
				$attachfilename = trim('CRF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]),'-');

			}
		break;
	case '4':
			{	
				$filebasename = 'SDB'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]['name']);
				$attachfilename = trim('SDB'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]),'-');

			}
		break;
	case '5':
			{	
				$filebasename = 'POF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]['name']);
				$attachfilename = trim('POF'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]),'-');

			}
		break;
	case '6':
			{	
				$filebasename = 'SEZ'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]['name']);
				$attachfilename = trim('SEZ'.'-'.$cusid.'-'.$date.basename($_FILES[$namefile]),'-');

			}
		break;
}
$ext = strtolower(substr($filebasename, strrpos($filebasename, '.') + 1));
$finalfilename = $attachfilename.'.'.$ext;


if($id == '1')
{
	if ($ext == "pdf" || $ext == "zip" || $ext == "rar") 
	{
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab")
			$addstring = "/saralimax-user";

		$destination_path = $_SERVER['DOCUMENT_ROOT'].$addstring.'/implementationuploads'.DIRECTORY_SEPARATOR;
		$result = 0;
		$target_path = $destination_path .$finalfilename;
		$downloadlink ='http://'.$_SERVER['HTTP_HOST'].$addstring.'/implementationuploads/'.$finalfilename;
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/implementationuploads/'.$finalfilename;
		if (!file_exists($target_path)) 
		{
			if(@move_uploaded_file($_FILES[$namefile]['tmp_name'], $target_path)) 
			{
	//			$result = 1;
				$result = $downloadlink . "|^|" . $finalfilename . "|^|" .$filepath;
			}
			
			else 
			{
				$result = 4; //Problem during Upload
			}
		} 
		else 
		{
			$result = 3; //File Already Exists by same name
		}
	} 
	else 
	{
		$result = 2; //Extension doesn't Match
	}
}
else if ($id == '5')
{	
	if ($ext == "zip" || $ext == "rar") 
	{
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab")
			$addstring = "/saralimax-user";

		$destination_path = $_SERVER['DOCUMENT_ROOT'].$addstring.'/implementationuploads'.DIRECTORY_SEPARATOR;
		$result = 0;
		$target_path = $destination_path .$finalfilename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/implementationuploads/'.$finalfilename;
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/implementationuploads/'.$finalfilename;
		if (!file_exists($target_path)) 
		{
			if(@move_uploaded_file($_FILES[$namefile]['tmp_name'], $target_path)) 
			{
	//			$result = 1;
				$result = $downloadlink . "|^|" . $finalfilename . "|^|" .$filepath;
			}
			
			else 
			{
				$result = 4; //Problem during Upload
			}
		} 
		else 
		{
			$result = 3; //File Already Exists by same name
		}
	} 
	else 
	{
		$result = 2; //Extension doesn't Match
	}
}	
else if($id == '6')
{	
	if ($ext == "zip" || $ext == "rar") 
	{
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk" )
			$addstring = "/saralimax-user";

		$destination_path = $_SERVER['DOCUMENT_ROOT'].$addstring.'/uploads'.DIRECTORY_SEPARATOR;
		$result = 0;
		$target_path = $destination_path .$finalfilename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/uploads/'.$finalfilename;
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/uploads/'.$finalfilename;
		if (!file_exists($target_path)) 
		{
			if(@move_uploaded_file($_FILES[$namefile]['tmp_name'], $target_path)) 
			{
	//			$result = 1;
				$result = $downloadlink . "|^|" . $finalfilename . "|^|" .$filepath;
			}
			
			else 
			{
				$result = 4; //Problem during Upload
			}
		} 
		else 
		{
			$result = 3; //File Already Exists by same name
		}
	} 
	else 
	{
		$result = 2; //Extension doesn't Match
	}
}
else
{	
	if ($ext == "zip") 
	{
		$addstring = "/user";
		if($_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "archanaab")
			$addstring = "/saralimax-user";

		$destination_path = $_SERVER['DOCUMENT_ROOT'].$addstring.'/implementationuploads'.DIRECTORY_SEPARATOR;
		$result = 0;
		$target_path = $destination_path .$finalfilename;
		$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/implementationuploads/'.$finalfilename;
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/implementationuploads/'.$finalfilename;
		if (!file_exists($target_path)) 
		{
			if(@move_uploaded_file($_FILES[$namefile]['tmp_name'], $target_path)) 
			{
	//			$result = 1;
				$result = $downloadlink . "|^|" . $finalfilename . "|^|" .$filepath;
			}
			
			else 
			{
				$result = 4; //Problem during Upload
			}
		} 
		else 
		{
			$result = 3; //File Already Exists by same name
		}
	} 
	else 
	{
		$result = 2; //Extension doesn't Match
	}
}
	
sleep($result);
?>

<script language="javascript" type="text/javascript">window.top.window.stopUpload('<? echo($result); ?>','<? echo($id); ?>','<? echo($divid); ?>');</script>
