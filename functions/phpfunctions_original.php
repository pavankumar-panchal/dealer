<?php
//Include Database Configuration details
if(file_exists("../inc/dbconfig.php"))
	include('../inc/dbconfig.php');
elseif(file_exists("../../inc/dbconfig.php"))
	include('../../inc/dbconfig.php');
else
	include('./inc/dbconfig.php');

//Connect to host
$newconnection = mysqli_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");

/* -------------------- Get local server time [by adding 5.30 hours] -------------------- */
function datetimelocal($format)
{
//	$diff_timestamp = date('U') + 19800;
	$date = date($format);
	return $date;
}

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $newconnection;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysqli_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysqli_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query); //;
	
	//Return the result
	return $result;
}

/* -------------------- Run a query to database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch($query)
{
	global $newconnection;
	$dbname = 'relyon_imax';

	//Connect to Database
	mysqli_select_db($dbname,$newconnection) or die("Cannot connect to database");
	set_time_limit(3600);
	//Run the query
	$result = mysqli_query($query,$newconnection) or die(" run Query Failed in Runquery function1.".$query); //;
	
	//Fetch the Query to an array
	$fetchresult = mysqli_fetch_array($result) or die("Cannot fetch the query result.".$query);
	
	//Return the result
	return $fetchresult;
}

function runicicidbquery($query)
{
	global $newconnection;
	 $icicidbname = "relyon_icici";
 
	 //Connect to Database
	 mysqli_select_db($icicidbname,$newconnection) or die("Cannot connect to database");
	 set_time_limit(3600);
	 
	 //Run the query
	 $result = mysqli_query($query,$newconnection) or die(mysqli_error());
	 
	 //Return the result
	 return $result;
}


/* -------------------- To change the date format from DD-MM-YYYY to YYYY-MM-DD or reverse -------------------- */

function changedateformat($date)
{
	if($date <> "0000-00-00")
	{
		if(strpos($date, " "))
		$result = split(" ",$date);
		else
		$result = split("[:./-]",$date);
		$date = $result[2]."-".$result[1]."-".$result[0];
	}
	else
	{
		$date = "";
	}
	return $date;
}


function changetimeformat($time)
{
	if($time <> "00:00:00")
	{
		$result = split(":",$time);
		$time = $result[0].":".$result[1];
	}
	else
	{
		$time = "";
	}
	return $time;
}


function changedateformatwithtime($date)
{
	if($date <> "0000-00-00 00:00:00")
	{
		if(strpos($date, " "))
		{
			$result = split(" ",$date);
			if(strpos($result[0], "-"))
				$dateonly = split("-",$result[0]);
			$timeonly =split(":",$result[1]);
			$timeonlyhm = $timeonly[0].':'.$timeonly[1];
			$date = $dateonly[2]."-".$dateonly[1]."-".$dateonly[0]." ".'('.$timeonlyhm.')';
		}
			
	}
	else
	{
		$date = "";
	}
	return $date;
}



function cusidsplit($customerid)
{
	$strlen = strlen($customerid);
	if($strlen <> '17')
	{
		if(strpos($customerid, " "))
		$result = split(" ",$customerid);
		else
		$result = split("[:./-]",$customerid);
		$customerid = $result[0].$result[1].$result[2].$result[3];
	}
	/*else
	{
		$customerid = "";
	}*/
		return $customerid;
}

function cusidcombine($customerid)
{
	$result="";
	if($customerid!="")
	{
		$result1 = substr($customerid,0,4);
		$result2 = substr($customerid,4,4);
		$result3 = substr($customerid,8,4);
		$result4 = substr($customerid,12,5);
		$result = $result1.'-'.$result2.'-'.$result3.'-'.$result4;
	}
	return $result;
}

function generatepwd()
{
	$charecterset0 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$charecterset1 = "1234567890";
	for ($i=0; $i<4; $i++)
	{
		$usrpassword .= $charecterset0[rand(0, strlen($charecterset0))];
	}
	for ($i=0; $i<4; $i++)
	{
		$usrpassword .= $charecterset1[rand(0, strlen($charecterset1))];
	}
	return $usrpassword;
}

function getpagelink($linkvalue)
{
	switch($linkvalue)
	{
		case 'productstodealers': return '../purchase/producttodealers.php'; break;
		case 'editprofile': return '../update/editprofile.php'; break;
		case 'changepassword': return '../update/changepw.php'; break;
		case 'dashboard': return '../dashboard/index.php'; break;
		case 'transactionsummary': return '../purchase/transactionsummary.php'; break;
		case 'viewpurchase': return '../purchase/viewpurchase.php'; break;
		case 'unauthorised': return '../update/unauthorised.php'; break;
		case 'purchaseproduct': return '../purchase/purchaseproduct.php'; break;
		case 'simplepurchase': return '../purchase/simplepurchase.php'; break;
		case 'dealerstockreport': return '../reports/dealerstockreport.php'; break;
		case 'registrationreport': return '../reports/registration.php'; break;
		case 'customers': return '../purchase/customer.php'; break;
		case 'interaction': return '../purchase/dealerinteraction.php'; break;
		case 'labelcontactdetails': return '../purchase/labelsforcontactdetails.php'; break;
		case 'dealerpayment': return '../purchase/dealerpayment.php'; break;
		case 'dealerquotereq': return '../purchase/dealerquotereq.php'; break;
		case 'custpayment': return '../purchase/custpayment.php'; break;
		case 'cuscardattach': return '../purchase/cus-cardattach.php'; break;
		case 'dealerpinattach': return '../purchase/dealerpinattach.php'; break;
		case 'invoicing': return '../purchase/invoicing.php'; break;
		case 'cuscardattachreport': return '../reports/cuscardattachreport.php'; break;
		case 'crossproduct': return '../purchase/crossproductinfo.php'; break;
		case 'viewinvoice': return '../purchase/viewinvoice.php'; break;
		case 'receipts': return '../purchase/receipts.php'; break;
		case 'implementation': return '../purchase/implementation.php'; break;
		case 'invoiceregister': return '../purchase/invoiceregister.php'; break;
		case 'receiptregister': return '../purchase/receiptregister.php'; break;
		case 'outstandingregister': return '../purchase/outstandingregister.php'; break;
		case 'invoicingtest': return '../purchase/invoicingtest.php'; break;
		case 'saralaccountscompcopy': return '../purchase/saralaccountscompcopy.php'; break;
		case 'video': return '../video/video.php'; break;
		case 'customeranalysis': return '../purchase/custdata.php'; break;
		default: return '../dashboard/index.php'; break;
	}
}

function getpagetitle($linkvalue)
{
	switch($linkvalue)
	{
		case 'productstodealers': return 'Product Mapping to Dealers'; break;
		case 'editprofile': return 'Dealer : Profile Screen'; break;
		case 'changepassword': return 'Dealer : Change Password'; break;
		case 'Dashboard': return 'Dealer : DashBoard'; break;
		case 'transactionsummary': return 'Dealer : Transaction Summary'; break;
		case 'viewpurchase': return 'Dealer : View Purchase'; break;
		case 'purchaseproduct': return 'Dealer : Purchase Product'; break;
		case 'simplepurchase': return 'Dealer : Simple Product'; break;
		case 'dealerstockreport': return 'Dealer : Dealer Stock Details'; break;
		case 'customers': return 'Dealer : Customer Master'; break;
		case 'unauthorised': return 'Saral iMax : Unauthorsed Viewer'; break;
		case 'interaction': return 'Dealer : Customer Interaction Details'; break;
		case 'registrationreport': return 'Dealer : Registration Details'; break;
		case 'logout': return 'Dealer : Logout'; break;
		case 'dealerpayment': return 'Dealer : Debit/Credit Card Payment'; break;
		case 'dealerquotereq': return 'Dealer :Quote / Payment / Request Emails'; break;
		case 'custpayment': return 'Dealer : Customer Payment Request'; break;
		case 'cuscardattach': return 'Dealer : PIN Number Attach'; break;
		case 'dealerpinattach': return 'Dealer : PIN Number Attach'; break;
		case 'invoicing': return 'Dealer : Invoicing'; break;
		case 'cuscardattachreport': return 'Dealer : PIN Number Attached Details'; break;
		case 'crossproduct': return 'Dealer : Cross Product Information'; break;
		case 'viewinvoice': return 'Dealer : View Details'; break;
		case 'receipts': return 'Dealer : Receipts'; break;
		case 'video': return 'Dealer : Demo Videos'; break;
		case 'implementation': return 'Dealer : Implementation'; break;
		case 'invoiceregister': return 'Dealer : Invoice Register'; break;
		case 'receiptregister': return 'Dealer : Receipt Register'; break;
		case 'outstandingregister': return 'Dealer : Outstanding Register'; break;
		case 'labelcontactdetails': return 'Dealer : Labels for Customer Contact Details'; break;
		case 'saralaccountscompcopy': return 'Dealer : Complementary Copy'; break;
		case 'customeranalysis': return 'Report : Data Inaccuracy Report'; break;
		default: return 'Dealer : Dashboard'; break;
		
	}
}

function getpageheader($linkvalue)
{
	switch($linkvalue)
	{
		case 'productstodealers': return 'Product Mapping to Dealers'; break;
		case 'editprofile': return 'Profile Screen'; break;
		case 'changepassword': return 'Change Password'; break;
		case 'Dashboard': return 'DashBoard'; break;
		case 'transactionsummary': return 'Transaction Summary'; break;
		case 'viewpurchase': return 'View Purchase'; break;
		//case 'payment': return 'Debit/Credit Card Payment'; break;
		case 'purchaseproduct': return 'Purchase Product'; break;
		case 'unauthorised': return 'Unauthorised Viewer'; break;
		case 'simplepurchase': return 'Simple Product'; break;
		case 'dealerstockreport': return 'Dealer Stock Details'; break;
		case 'customers': return 'Dealer Customers Master'; break;
		case 'interaction': return 'Customer Interaction Details'; break;
		case 'registrationreport': return 'Registration Details'; break;
		case 'logout': return 'Logout'; break;
		case 'dealerpayment': return 'Debit/Credit Card Payment'; break;
		case 'dealerquotereq': return 'Dealer :Quote / Payment / Request Emails'; break;
		case 'custpayment': return 'Dealer : Customer Payment Request'; break;
		case 'cuscardattach': return 'Dealer : PIN Number Attach'; break;
		case 'dealerpinattach': return 'Dealer : PIN Number Attach'; break;
		case 'invoicing': return 'Dealer : Invoicing'; break;
		case 'cuscardattachreport': return 'Dealer : PIN Number Attached Details'; break;
		case 'crossproduct': return 'Dealer : Cross Product Information'; break;
		case 'viewinvoice': return 'Dealer : View Details'; break;
		case 'receipts': return 'Dealer : Receipts'; break;
		case 'video': return 'Dealer : Demo Videos'; break;
		case 'implementation': return 'Dealer : Implementation'; break;
		case 'invoiceregister': return 'Dealer : Invoice Register'; break;
		case 'receiptregister': return 'Dealer : Receipt Register'; break;
		case 'outstandingregister': return 'Dealer : Outstanding Register'; break;
		case 'labelcontactdetails': return 'Dealer : Labels for Customer Contact Details'; break;
		case 'customeranalysis': return 'Data Inaccuracy Report'; break;
		case 'saralaccountscompcopy': return 'Dealer : Complementary Copy'; break;
		default: return 'Dashboard'; break;
	}
}

 function downloadfile($filelink)
{
	$filename = basename($filelink);
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$filename);
	readfile($filelink);
}

function checkemailaddress($email) 
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) 
	{
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
	{ 
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) 
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) 
		{
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
			{
				return false;
			}
		}
	}
	return true;
}

function replacemailvariable2($content,$array)
{
	$arraylength = count($array);
	for($i = 0; $i < $arraylength; $i++)
	{
		$splitvalue = explode('%^%',$array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue,$newvalue,$content);
	}
	return $content;
}

function gridtrim($value)
{
	$desiredlength = 40;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}
function validateFormat($computerid)
{
	if(preg_match("/^\d{5}-\d{9}$/", $computerid))
	return true;
}

function replacemailvariable($content,$array)
{
	$arraylength = count($array);
	for($i = 0; $i < $arraylength; $i++)
	{
		$splitvalue = explode('%^%',$array[$i]);
		$oldvalue = $splitvalue[0];
		$newvalue = $splitvalue[1];
		$content = str_replace($oldvalue,$newvalue,$content);
	}
	return $content;
}

function sendwelcomeemail($customerslno,$userid)
{
$query = "select 
	inv_mas_customer.customerid AS customerid,
	inv_mas_customer.businessname AS businessname,
	inv_mas_customer.place AS place,
	inv_mas_customer.address AS address,
	inv_mas_customer.pincode AS pincode,
	inv_mas_customer.stdcode AS stdcode,
	inv_mas_customer.initialpassword AS password,
		inv_mas_customertype.customertype AS type,
	inv_mas_customercategory.businesstype AS category,
	inv_mas_district.districtname AS districtname,
	inv_mas_state.statename AS statename
	from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode left join inv_mas_customercategory on inv_mas_customer.category = inv_mas_customercategory.slno left join inv_mas_customertype on inv_mas_customer.type = inv_mas_customertype.slno where inv_mas_customer.slno = '".$customerslno."'";
	$result = runmysqlqueryfetch($query);
	

	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerslno."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		if(checkemailaddress($fetchres['emailid']))
		{
			if($fetchres['contactperson'] != '')
				$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
			else
				$emailids[$fetchres['emailid']] = $fetchres['emailid'];
		}
		$contactperson = $fetchres['contactperson'];
		
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$phoneres .= $phone;
		$phoneres .= appendcomma($phone);
		$cellres .= $cell;
		$cellres .= appendcomma($cell);
		$emailidres .= $emailids;
		$emailidres .= appendcomma($emailid);
		
	}
	
	$customerid = $result['customerid'];
	$businessname = $result['businessname'];
	$contactperson = trim($contactvalues,',');
	$place = $result['place'];
	$address = $result['address'];
	$pincode = $result['pincode'];
	$stdcode = $result['stdcode'];
	$phone =trim($phoneres,',');
	$cell = trim($cellres,',');
	$password = $result['password'];
	$type = $result['type'];
	$category = $result['category'];
	$districtname = $result['districtname'];
	$statename = $result['statename'];
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
		$emailid = 'bhumika.p@relyonsoft.com';
	else
		$emailid = trim($emailidres,',');
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/newcustomer.htm");
	$textmsg = file_get_contents("../mailcontents/newcustomer.txt");
	$pincode = ($pincode == '')?'Not Available':$pincode;
	$stdcode = ($stdcode == '')?'Not Available':$stdcode;
	$address = ($address == '')?'Not Available':$address;
	$type = ($type == '')?'Not Available':$type;
	$category = ($category == '')?'Not Available':$category;

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##DISTRICT##%^%".$districtname;
	$array[] = "##STATE##%^%".$statename;
	$array[] = "##CUSID##%^%".cusidcombine($customerid);
	$array[] = "##PINCODE##%^%".$pincode;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##TYPE##%^%".$type;
	$array[] = "##PHONE##%^%".$phone;
	$array[] = "##CELL##%^%".$cell;
	$array[] = "##PASSWORD##%^%".$password;
	$array[] = "##EMAIL##%^%".$emailid;
	$array[] = "##CATEGORY##%^%".$category;
	$array[] = "##EMAILID##%^%".$emailid;
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		//array('../images/icon_registration_lg.gif','inline','9999999999'),
		array('../images/customer_icon.gif','inline','7777777777'),
		array('../images/relyon-logo.jpg','inline','8888888888'),
		//array('../images/re-registration-icon.gif','inline','66666666666'),
		array('../images/contact-info.gif','inline','33333333333'),
		array('../images/customer-service.gif','inline','22222222222')
	
	);
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
	{
		$bccemailids['archana'] ='bhumika.p@relyonsoft.com';
	}
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['support'] ='support@relyonsoft.com';
		$bccemailids['info'] ='info@relyonsoft.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'New customer details registered with Customer ID "'.cusidcombine($customerid).'"'; 
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto);
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'support@relyonsoft.com,info@relyonsoft.com,bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}



function sendregistrationemail($customerproductslno,$userid)
{

	$query = "Select 
	inv_mas_customer.businessname as businessname,
	inv_mas_customer.place as place,
	inv_mas_customer.customerid as customerid,inv_mas_customer.slno as slno,
	inv_customerproduct.computerid as computerid,
	inv_customerproduct.softkey as softkey,inv_customerproduct.dealerid as dealerid,
	inv_mas_scratchcard.scratchnumber as pinno,
	inv_mas_product.productname as productname from inv_customerproduct Left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid
	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3)
	where inv_customerproduct.slno = '".$customerproductslno."'";
	$result = runmysqlqueryfetch($query);
	
	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid from inv_contactdetails where customerid = '".$result['slno']."'; ";
	
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		$contactperson = $fetchres['contactperson'];
		$emailid =  $fetchres['emailid'];
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
	}
	
	$contactperson = trim($contactvalues,',');
	$businessname = $result['businessname'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$customerslno = $result['slno'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$computerid = $result['computerid'];
	$softkey = $result['softkey'];
	$dealerid = $result['dealerid'];
	$emailid = trim($emailidres,',');
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = $emailid;

	$query = "Select emailid from inv_mas_dealer where slno = '".$dealerid."'";
	$fetch = runmysqlqueryfetch($query);
	$bcceallemailid = $fetch['emailid'];
	
	 //BCC to dealer
	$bccemailarray = explode(',',$bcceallemailid);
	$bccemailcount = count($bccemailarray);

	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	for($i = 0; $i < $bccemailcount; $i++)
	{
		if(checkemailaddress($bccemailarray[$i]))
		{
				$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/customerregistration.htm");
	$textmsg = file_get_contents("../mailcontents/customerregistration.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##COMPUTERID##%^%".$computerid;
	$array[] = "##SOFTKEY##%^%".$softkey;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
		array('../images/registration-icon.gif','inline','1234567890'),
		array('../images/relyon-logo.jpg','inline','8888888888'),
	);
	
	$toarray = $emailids;
//	$bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
	$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$bccarray = 'meghana.b@relyonsoft.com';
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccarray = $bccemailids;
	}
	
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Registration availed for ".$productname;
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto); 
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com'; 
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);				
}
// function to delete cookie and encoded the cookie name and value
function imaxdeletecookie($cookiename)
{
	 //Name Suffix for MD5 value
	 $stringsuff = "55";

	//Convert Cookie Name to base64
	$Encodename = encodevalue($cookiename);
	
	 //Append the encoded cookie name with 55(suffix ) for MD5 value
	 $rescookiename = $Encodename.$stringsuff;

	//Set expiration to negative time, which will delete the cookie
	setcookie($Encodename, "",time()-3600);
	setcookie($rescookiename, "",time()-3600);
	
	setcookie(session_name(), "",time()-3600);
}

// function to create cookie and encoded the cookie name and value
function imaxcreatecookie($cookiename,$cookievalue)
{
	 //Define prefix and suffix 
	 $prefixstring="AxtIv23";
	 $suffixstring="StPxZ46";
	 $stringsuff = "55";
	 
	 //Append Value with the Prefix and Suffix
	 $Appendvalue = $prefixstring . $cookievalue . $suffixstring;
	 
	 // Convert the Appended Value to base64
	 $Encodevalue = encodevalue( $Appendvalue);
	 
	 //Convert Cookie Name to base64
	 $Encodename = encodevalue($cookiename);

	 //Create a cookie with the encoded name and value
	 setcookie($Encodename,$Encodevalue);

 	 //Convert Appended encode value to MD5
	 $rescookievalue = md5($Encodevalue);

	 //Appended the encoded cookie name with 55(suffix )
	 $rescookiename = $Encodename.$stringsuff;

	 //Create a cookie
	 setcookie($rescookiename,$rescookievalue);
	 return false;
	 	 
}






//Function to get cookie and encode it and validate
function imaxgetcookie($cookiename)
{
	$suff = "55";

	// Convert the Cookie Name to base64
	$Encodestr = encodevalue($cookiename);

	//Read cookie name
	$stringret = $_COOKIE[$Encodestr];
	$stringret = stripslashes($stringret);

	//Convert the read cookie name to md5 encode technique
	$Encodestring = md5($stringret);
	
	//Appended the encoded cookie name to 55(suffix)
	$resultstr = $Encodestr.$suff;
	$cookiemd5 = $_COOKIE[$resultstr];
	
	//Compare the encoded value wit the fetched cookie, if the condition is true decode the cookie value
	if($Encodestring == $cookiemd5)
	{
		$decodevalue = decodevalue($stringret);
		//Remove the Prefix/Suffix Characters
		$string1 = substr($decodevalue,7);
		$resultstring = substr($string1,0,-7);
		return $resultstring;
	}
	elseif(isset($Encodestring) == '')
	{
		return false;
	}
	else 
	{
		return false;
	}
}
//Function to logout (clear cookies)
function imaxdealerlogout()
{
	session_start(); 
	session_unset();
	session_destroy(); 
	imaxdeletecookie('dealeruserid');
	imaxdeletecookie('sessionkind');
}

function imaxdealerlogoutredirect()
{
	imaxdealerlogout();
	//$url = "../index.php";
	$url = "../index.php?link=".fullurl();
	header("Location:".$url);
	exit();	
}

function fullurl()
{
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function isvalidhostname()
{
	if($_SERVER['HTTP_HOST'] == 'rashmihk' || $_SERVER['HTTP_HOST'] == 'meghanab' || $_SERVER['HTTP_HOST'] == 'vijaykumar' || $_SERVER['HTTP_HOST'] == 'imax.relyonsoft.net' || $_SERVER['HTTP_HOST'] == 'rwmserver')
		return true;
	else
		return false;
}

function isurl($url)
{
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}
//Function to generate random string of four digits
function rand_str()
{
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 0; $i <4; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    // Return the string
    return $string;

}

function modulegropname($shortname)
{
	switch($shortname)
	{
		case "user_module":
			return "User Module";
			break;
		case "dealer_module":
			return "Dealer Module";
			break;
		
	}
}

function sendpaymentreqemail($customerslno,$table,$userid)
{
	$query = "SELECT inv_mas_customer.businessname as businessname,inv_mas_customer.customerid as customerid,inv_mas_customer.slno as slno,inv_custpaymentreq.paymentstatus as paymentstatus,inv_mas_customer.place as place  FROM inv_custpaymentreq LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_custpaymentreq.custreferences WHERE inv_custpaymentreq.slno = '".$customerslno."' and inv_custpaymentreq.paymentstatus = 'UNPAID' ";
	$result = runmysqlqueryfetch($query);
	
	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$result['slno']."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		if(checkemailaddress($fetchres['emailid']))
		{
			if($fetchres['contactperson'] != '')
				$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
			else
				$emailids[$fetchres['emailid']] = $fetchres['emailid'];
		}
		$contactperson = $fetchres['contactperson'];
		
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
		
	}
	$customerid = $result['customerid'];
	$businessname = $result['businessname'];
	$slno = $result['slno'];
	$place = $result['place'];
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = trim($emailidres,',');

	

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/paymentreq.htm");
	$textmsg = file_get_contents("../mailcontents/paymentreq.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##TABLE##%^%".$table;
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	);
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	$toarray = $emailids;
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$toarray[] = 'archana.ab@relyonsoft.com';
		$bccemailids['archana'] = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['accounts'] ='accounts@relyonsoft.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Online Payment request";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'accounts@relyonsoft.com,bigmail@relyonsoft.com'; 
	inserttologs($userid,$slno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);
}

function inserttologs($userid,$id,$fromname,$emailfrom,$emailto,$ccmailids,$bccemailids,$subject)
{
	$module = 'dealer_module';
	$sentthroughip = $_SERVER['REMOTE_ADDR'];
	$query = "insert into inv_logs_mails(userid,id,fromname,emailfrom,emailto,ccmailids,bccmailids,subject,date,fromip,module) values('".$userid."','".$id."','".$fromname."','".$emailfrom."','".$emailto."','".$ccmailids."','".$bccemailids."','".$subject."','".date('Y-m-d').' '.date('H:i:s')."','".$sentthroughip."','".$module."');";
	$result = runmysqlquery($query);
}

function decodevalue($input)
{
	$input = str_replace('\\\\','\\',$input);
	$input = str_replace("\\'","'",$input);
	$length = strlen($input);
	$output = "";
	for($i = 0; $i < $length; $i++)
	{
		if($i % 2 == 0)
			$output .= chr(ord($input[$i]) - 7);
	}
	$output = str_replace("'","\'",$output);
	return $output;
}

function encodevalue($input)
{
	$length = strlen($input);
	$output1 = "";
	for($i = 0; $i < $length; $i++)
	{
		$output1 .= $input[$i];
		if($i < ($length - 1))
			$output1 .= "a";
	}
	$output = "";
	for($i = 0; $i < strlen($output1); $i++)
	{
		$output .= chr(ord($output1[$i]) + 7);
	}
	return $output;
}
//To set products for Buy online, by storing multiple products in same cookie name.	
function buyproduct($slno)
{
	if(imaxgetcookie('selectedproducts') <> '')
	{
		$arraylist  = array();
		$arraylist = imaxgetcookie('selectedproducts');
		$listvalue = explode('#',$arraylist );
		/*if(in_array($slno, $listvalue, true))
		{
			return false;
		}
		else
		{*/
		$value = imaxcreatecookie('selectedproducts',imaxgetcookie('selectedproducts').'#'.$slno);
		return true;
		//}
		
	}
	else
	{
		imaxcreatecookie('selectedproducts',$slno);
		return true;
	}
}

//Function to generate Online Bill In PDF format
function generatepdfbill($firstbillnumber,$custreference,$onlineinvoiceno,$invoicenoformat,$loggedindealername)
{
	require_once('../pdfbillgeneration/tcpdf.php');
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	
	//set font
	$pdf->SetFont('Helvetica', '',10);
	
	//add a page
	$pdf->AddPage();
	
	$query1 = "select * from inv_customerreqpending where customerid = '".$custreference."' and inv_customerreqpending.customerstatus = 'Pending' and requestfrom = 'dealer_module';";
	$result1 = runmysqlquery($query1);
	
	if(mysqli_num_rows($result1) > 0)
	{
		$query = "select inv_customerreqpending.businessname as companyname,inv_customerreqpending.place,inv_customerreqpending.address,inv_mas_region.category as region,inv_mas_branch.branchname as branchname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,
inv_customerreqpending.stdcode, inv_customerreqpending.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_customerreqpending on inv_customerreqpending.customerid = inv_mas_customer.slno left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$custreference."' and inv_customerreqpending.customerstatus = 'Pending';";
	}
	else
	{
		$query = "select inv_mas_customer.businessname as companyname,inv_mas_customer.place,inv_mas_customer.address,inv_mas_region.category as region,inv_mas_branch.branchname as branchname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,inv_mas_dealer.businessname as dealername,inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_customer.customerid  from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno = '".$custreference."';";
	}
	$fetchresult = runmysqlqueryfetch($query);
	
	// Fetch Contact Details 
	$querycontactdetails = "select customerid, GROUP_CONCAT(contactperson) as contactperson,  
GROUP_CONCAT(phone) as phone, GROUP_CONCAT(cell) as cell, GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$custreference."'  group by customerid ";
	$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
	
	$contactvalues = removedoublecomma($resultcontactdetails['contactperson']);
	$phoneres = removedoublecomma($resultcontactdetails['phone']);
	$cellres = removedoublecomma($resultcontactdetails['cell']);
	$emailidres = removedoublecomma($resultcontactdetails['emailid']);
	
	
	$query1 = "SELECT inv_mas_product.productcode as productcode , inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno, inv_mas_scratchcard.scratchnumber as pinno,inv_dealercard.addlicence FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode  WHERE inv_dealercard.cusbillnumber = '".$firstbillnumber."';";
	$result = runmysqlquery($query1);
		
	$query2 = "SELECT inv_billdetail.productamount,productquantity,`year` as financialyear from inv_billdetail
left join inv_mas_product on inv_mas_product.productcode = inv_billdetail.productcode where inv_billdetail.cusbillnumber = '".$firstbillnumber."';";
	$result2 = runmysqlquery($query2);
	
	while($fetch2 = mysqli_fetch_array($result2))
	{
		for($i=0;$i<$fetch2['productquantity'];$i++)
		{
			$amount[] = round($fetch2['productamount']/$fetch2['productquantity']);
			$financialyear[] = $fetch2['financialyear'];
		}
	}
	$query3 = "Select * from inv_bill where inv_bill.slno = '".$firstbillnumber."'";
	$result3 = runmysqlqueryfetch($query3);
	$query2 = "select paymentremarks as remarks,offerremarks,pricingtype,offertype,offerdescription,offeramount,inv_mas_dealer.businessname,invoiceremarks,service,serviceamount from  dealer_online_purchase left join inv_mas_dealer on inv_mas_dealer.slno = dealer_online_purchase.currentdealer where onlineinvoiceno = '".$onlineinvoiceno."';";
	$result2 = runmysqlqueryfetch($query2);
	$createddealername = $result2['businessname'];
	$service = $result2['service'];
	$serviceamount = $result2['serviceamount'];
	$offerremarks = $result2['offerremarks'];
	$remarks = ($result2['remarks'] == '')?'None':$result2['remarks'];
	$invoiceremarks = ($result2['invoiceremarks'] == '')?'None':$result2['invoiceremarks'];
	$offertype = $result2['offertype'];
	$offerdescription = $result2['offerdescription'];
	$offeramount = $result2['offeramount'];
	$offertypesplit = explode('~',$offertype);
	$offerdescriptionsplit = explode('~',$offerdescription);
	$offeramountsplit = explode('~',$offeramount);
	$serviceamountsplit = explode('~',$serviceamount);
	$servicesplit = explode('#',$service);
	if($service <> '')
		$servicesplitcount = count($servicesplit);
	else
		$servicesplitcount = '0';
	if($offerremarks <> '')
		$offerremarkscount = '1';
	else
		$offerremarkscount = '0';
	if($offertype == '')
		$offertypesplitcount = 0;
	else
		$offertypesplitcount = count($offertypesplit);
	$resultcount = mysqli_num_rows($result);
	$linecount =  $resultcount + $offertypesplitcount + $offerremarkscount + $servicesplitcount;
	$addline1 = '';
	$addline = '';
	$offerremarksrow ='';
	$appendzero = '.00';

	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	$k = 0;
	$descriptioncount = 0;
	$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
	while($fetch = mysqli_fetch_array($result))
	{
		$slno++;
		$grid .= '<tr>';
		$grid .= '<td width="10%" style="text-align:centre;">'.$slno.'</td>';
		if($fetch['purchasetype'] == 'new')
			$purchasetype = 'New';
		else
			$purchasetype = 'Updation';
		if($fetch['addlicence'] == 'yes')
		{
			$usagetype = 'Additional License';
		}
		else
		{
			if($fetch['usagetype'] == 'singleuser')
				$usagetype = 'Single User';
			else
				$usagetype = 'Multi User';
		}
			
		$grid .= '<td width="76%" style="text-align:left;">'.$fetch['productname'].' - ('.$financialyear[$k].')<br/><span style="font-size:+7" ><strong>Purchase Type</strong> : '.$purchasetype.'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$usagetype.'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number :<font color="#FF3300"> '.$fetch['pinno'].'</font></strong> (<strong>Serial</strong> : '.$fetch['cardno'].')</span></td>';
		$grid .= '<td  width="14%" style="text-align:right;" >'.$amount[$k].$appendzero.'</td>';
		$grid .="</tr>";
		if($slno == $resultcount)
			$grid .= $addline;
		if($descriptioncount > 0)
			$description .= '*';
		$description .= $slno.'$'.$fetch['productname'].' - ('.$financialyear[$k].')'.'$'.$purchasetype.'$'.$usagetype.'$'.$fetch['pinno'].'$'.$fetch['cardno'].'$'.$amount[$k];
		$k++;
		$descriptioncount++;
	  }
	  if($service <> '')
	  {
			$servicecount = 0;
			for($i=0; $i<$servicesplitcount; $i++)
			{
				$slno++;
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$slno.'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicesplit[$i].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$serviceamountsplit[$i].$appendzero.'</td>';
				$grid .= "</tr>";
				if($servicecount > 0)
					$servicegrid .= '*';
				$servicegrid .= $slno.'$'.$servicesplit[$i].'$'.$serviceamountsplit[$i];
				$k++;
				$servicecount++;
			}
	  }
	  if($offertype <> '')
	  {
			$offerdescriptioncount = 0;
			for($i=0; $i<$offertypesplitcount; $i++)
			{
				$slno++;
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offertypesplit[$i]).': '.$offerdescriptionsplit[$i].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$offeramountsplit[$i].$appendzero.'</td>';
				$grid .= "</tr>";
				if($i == ($offertypesplitcount-1))
					$grid .= $addline1;
				if($offerdescriptioncount > 0)
					$offerdescriptiongrid .= '*';
				$offerdescriptiongrid .= $offerdescriptionsplit[$i].'$'.$offertypesplit[$i].'$'.$offeramountsplit[$i];
				$k++;
				$offerdescriptioncount++;
			}
	  }
	  if($offerremarks <> '' )
	  {
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$offerremarks.'</td><td width="14%">&nbsp;</td></tr>';
	  }
	  if($linecount < 8)
	  {
	  	$grid .= addlinebreak($linecount);
	  }
	 $amountinwords = convert_number($result3['netamount']);
	 $grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right" valign="top">'.$result3['total'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" > '.$servicetaxdesc.'</span></td>  <td  width="30%" style="text-align:right"><strong>Service Tax @ 12.3%</strong></td> <td  width="14%" style="text-align:right">'.$result3['taxamount'].$appendzero.'</td></tr><tr>  <td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>  <td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$result3['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$amountinwords.' only</td></tr>';
	$grid .='</table></td></tr></table>';
		
	$emailid = explode(',', trim($emailidres,','));
	$emailidplit = $emailid[0];
	$phonenumber = explode(',', trim($phoneres,','));
	$phonenumbersplit = $phonenumber[0];
	$cellnumber = explode(',', trim($cellres,','));
	$cellnumbersplit = $cellnumber[0];
	$contactperson = explode(',', trim($contactvalues,','));
	$contactpersonplit = $contactperson[0];
	$stdcode = ($fetchresult['stdcode'] == '')?'':$fetchresult['stdcode'].' - ';
	$address = $fetchresult['address'].', '.$fetchresult['place'].', '.$fetchresult['districtname'].', '.$fetchresult['statename'].', Pin: '.$fetchresult['pincode'];
	$customertype = ($fetchresult['customertype'] == '')?'Not Available':$fetchresult['customertype'];
	$businesstype = ($fetchresult['businesstype'] == '')?'Not Available':$fetchresult['businesstype'];
	$invoiceheading = ($fetchresult['statename'] == 'Karnataka')?'Tax Invoice':'Bill Of Sale';
	
	$invoicequery = "update inv_invoicenumbers set description = '".$description."', amount = '".$result3['total']."',servicetax = '".$result3['taxamount']."', netamount = '".$result3['netamount']."', customerid = '".cusidcombine($fetchresult['customerid'])."',phone =  '".$phonenumbersplit."',cell = '".$cellnumbersplit."',emailid = '".$emailidplit."',contactperson = '".$contactpersonplit."',stdcode = '".$stdcode."',customertype = '".$customertype."',customercategory = '".$businesstype."',branch ='".$fetchresult['branchname']."',pincode = '".$fetchresult['pincode']."',address ='".addslashes($address)."', amountinwords = '".$amountinwords."', remarks = '".$remarks."', servicetaxdesc = '".$servicetaxdesc."', offerdescription = '".$offerdescriptiongrid."', offerremarks = '".$offerremarks."', invoiceremarks = '".$invoiceremarks."', servicedescription = '".$servicegrid."', invoiceheading = '".$invoiceheading."' where slno  ='".$onlineinvoiceno."';";
	$invoiceresult = runmysqlquery($invoicequery);
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$array[] = "##BILLDATE##%^%".date('d/m/Y');
	$array[] = "##BILLNO##%^%".$invoicenoformat;
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['companyname'];
	$array[] = "##CONTACTPERSON##%^%".$contactpersonplit;
	$array[] = "##PHONE##%^%".$phonenumbersplit;
	$array[] = "##CELL##%^%".$cellnumbersplit;
	$array[] = "##EMAILID##%^%".$emailidplit;
	$array[] = "##RELYONREP##%^%".$createddealername;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($fetchresult['customerid']);
	$array[] = "##BRANCH##%^%".$fetchresult['branchname'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##CUSTOMERTYPE##%^%".$customertype;
	$array[] = "##CUSTOMERCATEGORY##%^%".$businesstype;
	$array[] = "##PAYREMARKS##%^%".$remarks;
	$array[] = "##INVREMARKS##%^%".$invoiceremarks;
	$array[] = "##TABLE##%^%".$grid;
	$array[] = "##GENERATEDBY##%^%".$loggedindealername;
	$array[] = "##INVOICEHEADING##%^%".$invoiceheading;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filebasename = str_replace('/','-',$invoicenoformat).".pdf";
	$addstring ="/dealer";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab")
		$addstring = "/saralimax-dealer";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	$pdf->Output($filepath ,'F');
	return $filebasename;
	//$pdf->Output('example.pdf' ,'I');	
}

//Function to convert the Number to words 
function convert_number1($number) 
{ 
	if (($number < 0) || ($number > 999999999)) 
	{ 
		throw new Exception("Number is out of range");
	} 

    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
    $number -= $Gn * 1000000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 
    $res = ""; 
    if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Million"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 
	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
	"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
	"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
	"Nineteen"); 
	$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
	"Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 
        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 

//Function to delete the file 
function fileDelete($filepath,$filename) 
{
	$success = FALSE;
	if (file_exists($filepath.$filename)&&$filename!=""&&$filename!="n/a") {
		unlink ($filepath.$filename);
		$success = TRUE;
	}
	return $success;	
}

function vieworgeneratepdfinvoice111($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	
	// set font
	$pdf->SetFont('Helvetica', '',10);
	
	// add a page
	$pdf->AddPage();

	$query = "select * from inv_invoicenumbers 	where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);
	
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	while($fetch = mysqli_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";

			}
			else
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
		<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[6].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$servicedescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[1]).': '.$offerdescriptionline[0].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$offerdescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount ;
		if($rowcount < 8)
		{
			$grid .= addlinebreak($rowcount);

		}
		//echo($rowcount.$fetch['servicedescription'].'^'.$descriptioncount.'^'.$servicedescriptioncount); exit;
		$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 12.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	$fetchresult = runmysqlqueryfetch($query);
	//to fetch dealer email id 
	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
	
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".changedateformatwithtime($fetchresult['createddate']);
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".$fetchresult['address'];
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];
	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	
	$array[] = "##TABLE##%^%".$grid;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = str_replace('/','-',$fetchresult['invoiceno']);
	$filebasename = $filename.".pdf";
	$addstring ="/dealer";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab")
		$addstring = "/saralimax-dealer";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output('example.pdf' ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid;
	}
}



function vieworgeneratepdfinvoice_backup($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_invoicenumbers where slno = '".$slno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$invoicestatus = $resultfetch1['status'];
	if($invoicestatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'invoicing-cancelled-background.jpg';
			$this->Image($img_file, 0, 80, 820, 648, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	// remove default footer
	$pdf->setPrintFooter(false);
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	$query = "select * from inv_invoicenumbers 	where inv_invoicenumbers.slno = '".$slno."';";
	$result = runmysqlquery($query);
	
	$appendzero = '.00';
	$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
	$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div></td></tr>';
	while($fetch = mysqli_fetch_array($result))
	{
		$description = $fetch['description'];
		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($fetch['purchasetype'] == 'SMS')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[2].'</td>';
				$grid .= "</tr>";

			}
			else
			{
					$grid .= '<tr>';
					$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
					$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
			<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span></td>';
					$grid .= '<td  width="14%" style="text-align:right;" >'.$descriptionline[6].$appendzero.'</td>';
					$grid .= "</tr>";
			}
		}
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$servicedescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[1]).': '.$offerdescriptionline[0].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.$offerdescriptionline[2].$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount ;
		if($rowcount < 8)
		{
			$grid .= addlinebreak($rowcount);

		}
		//echo($rowcount.$fetch['servicedescription'].'^'.$descriptioncount.'^'.$servicedescriptioncount); exit;
		
		if($fetch['status'] == 'EDITED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' <br/>Remarks: '.$fetch['editedremarks'];
		}
		elseif($fetch['status'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  <br/>Remarks: '.$fetch['cancelledremarks'];

		}
		else
			$statusremarks = '';
			//echo($statusremarks); exit;
		$grid .= '<tr><td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$statusremarks.' </span></td><td  width="30%" style="text-align:right"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 12.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	//	echo($grid1); exit;
	//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	$fetchresult = runmysqlqueryfetch($query);
	//to fetch dealer email id 
	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
	
	$msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".changedateformatwithtime($fetchresult['createddate']);
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".$fetchresult['address'];
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];
	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	
	$array[] = "##TABLE##%^%".$grid;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = str_replace('/','-',$fetchresult['invoiceno']);
	$filebasename = $filename.".pdf";
	$addstring ="/dealer";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab")
		$addstring = "/saralimax-dealer";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output('example.pdf' ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid.'^'.$invoicestatus;
	}
	$pdf->writeHTML($html, true, false, true, false, '');

}

function vieworgeneratepdfinvoice($slno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	$query1 = "select * from inv_invoicenumbers where slno = '".$slno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$invoicestatus = $resultfetch1['status'];
	$invoicenewformate= changedateformat(substr($resultfetch1['createddate'],0,10));
	$newyeardate = "31-03-2014";
	if($invoicestatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'invoicing-cancelled-background.jpg';
			$this->Image($img_file, 0, 80, 820, 648, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	// remove default footer
	$pdf->setPrintFooter(false);
	
	// set font
	$pdf->SetFont('Helvetica', '', 10);
	
	// add a page
	$pdf->AddPage();
	
	$query = "select * from inv_invoicenumbers 	where slno = '".$slno."';";
	$result = runmysqlquery($query);
	
	$appendzero = '.00';
	if(strtotime($invoicenewformate) <= strtotime($newyeardate))
	{
		$grid .='<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" >';
		$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#CCCCCC" style="border:1px 
		solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div 
		align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div>
		</td></tr>';
	}
	else
	{
		$grid .='<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" >';
		$grid .='<tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC" style="border:1px 
		solid "><tr bgcolor="#CCCCCC"><td width="10%"><div align="center"><strong>Sl No</strong></div></td><td width="76%"><div 
		align="center"><strong>Description</strong></div></td><td width="14%"><div align="center"><strong>Amount</strong></div>
		</td></tr>';
	}
	while($fetch = mysqli_fetch_array($result))
	{
		$description = $fetch['description'];
		$productbriefdescription = $fetch['productbriefdescription'];
		$productbriefdescriptionsplit = explode('#',$productbriefdescription);

		$descriptionsplit = explode('*',$description);
		for($i=0;$i<count($descriptionsplit);$i++)
		{
			$productdesvalue = '';
			$descriptionline = explode('$',$descriptionsplit[$i]);
			if($productbriefdescription <> '')
				$productdesvalue = $productbriefdescriptionsplit[$i];
			else
				$productdesvalue = 'Not Avaliable';
			
			if($description <> '')
			{
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$descriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$descriptionline[1].'<br/>
		<span style="font-size:+7" ><strong>Purchase Type</strong> : '.$descriptionline[2].'&nbsp;/&nbsp;<strong>Usage Type</strong> :'.$descriptionline[3].'&nbsp;&nbsp;/ &nbsp;<strong>PIN Number : <font color="#FF3300">'.$descriptionline[4].'</font></strong> (<strong>Serial</strong> : '.$descriptionline[5].')</span><br/><span style="font-size:+6" ><strong>Product Description</strong> : '.$productdesvalue.' </span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($descriptionline[6]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		$itembriefdescription = $fetch['itembriefdescription'];
		$itembriefdescriptionsplit = explode('#',$itembriefdescription);
		$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
		$servicedescriptioncount = count($servicedescriptionsplit);
		if($fetch['servicedescription'] <> '')
		{
			for($i=0; $i<$servicedescriptioncount; $i++)
			{
				$itemdesvalue = '';
				$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
				if($itembriefdescription <> '')
					$itemdesvalue = $itembriefdescriptionsplit[$i];
				else
					$itemdesvalue = 'Not Avaliable';
				
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">'.$servicedescriptionline[0].'</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.$servicedescriptionline[1].'<br/><span style="font-size:+6" ><strong>Item Description</strong> : '.$itemdesvalue.' </span></td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($servicedescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}
		
		$offerdescriptionsplit = explode('*',$fetch['offerdescription']);
		$offerdescriptioncount = count($offerdescriptionsplit);
		if($fetch['offerdescription'] <> '')
		{
			for($i=0; $i<$offerdescriptioncount; $i++)
			{
				$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
				$grid .= '<tr>';
				$grid .= '<td width="10%" style="text-align:centre;">&nbsp;</td>';
				$grid .= '<td width="76%" style="text-align:left;">'.strtoupper($offerdescriptionline[0]).': '.$offerdescriptionline[1].'</td>';
				$grid .= '<td  width="14%" style="text-align:right;" >'.formatnumber($offerdescriptionline[2]).$appendzero.'</td>';
				$grid .= "</tr>";
			}
		}

		if($fetch['offerremarks'] <> '')
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:left;">'.$fetch['offerremarks'].'</td><td width="14%">&nbsp;</td></tr>';
		$descriptionlinecount = 0;
		if($description <> '')
		{
			//Add description "Internet downloaded software"
			$grid .= '<tr><td width="10%"></td><td width="76%" style="text-align:center;"><font color="#666666">INTERNET DOWNLOADED SOFTWARE</font></td><td width="14%">&nbsp;</td></tr>';
			$descriptionlinecount = 1;
		}
		if($fetch['description'] == '')
			$offerdescriptioncount = 0;
		else
			$offerdescriptioncount = count($descriptionsplit);
		if($fetch['offerdescription'] == '')
			$descriptioncount = 0;
		else
			$descriptioncount = count($descriptionsplit);
		if($fetch['servicedescription'] == '')
			$servicedescriptioncount = 0;
		else
			$servicedescriptioncount = count($servicedescriptionsplit);
		$rowcount = $offerdescriptioncount + $descriptioncount + $servicedescriptioncount + $descriptionlinecount;
		if($rowcount < 6)
		{
			$grid .= addlinebreak($rowcount);

		}
		//echo($rowcount.$fetch['servicedescription'].'^'.$descriptioncount.'^'.$servicedescriptioncount); exit;
		
		if($fetch['status'] == 'EDITED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['editedby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetch['editeddate']).' <br/>Remarks: '.$fetch['editedremarks'];
		}
		elseif($fetch['status'] == 'CANCELLED')
		{
			$query011 = "select * from inv_mas_users where slno = '".$fetch['cancelledby']."';";
			$resultfetch011 = runmysqlqueryfetch($query011);
			$changedby = $resultfetch011['fullname'];
			$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetch['cancelleddate']).'  <br/>Remarks: '.$fetch['cancelledremarks'];

		}
		else
			$statusremarks = '';
			//echo($statusremarks); exit;
			
		$invoicedatedisplay = substr($fetch['createddate'],0,10);
		$invoicedate =  strtotime($invoicedatedisplay);
		//$expirydate = strtotime('2012-04-01');
		$expirydate = strtotime('2012-04-01');
		$expirydate1 = strtotime('2015-06-01');
		$expirydate2 = strtotime('2015-11-15');
		$KK_Cess_date = strtotime('2016-05-31');
	
		if($fetch['seztaxtype'] == 'yes')
		{
			$sezremarks = 'TAX NOT APPLICABLE AS CUSTOMER IS UNDER SPECIAL ECONOMIC ZONE.<br/>';
			
			if($expirydate >= $invoicedate || $expirydate1 > $invoicedate)
			{
				$servicetax1 = 0;
				$servicetax2 = 0;
				$servicetax3 = 0;
			
				$servicetaxname = '<br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.
				formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate2 > $invoicedate)
			{
				$servicetax1 = 0;
				$totalservicetax = formatnumber($servicetax1).$appendzero;
			}
			else
			{
				$servicetax1 = 0;
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$servicetax2 = 0;
				$totalservicetax1 = $servicetax2.$appendzero;
				
				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';
			}
		}
		else
		{
			
			/*if($expirydate > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 10%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
			}
			else
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 12%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
			}*/
			if($expirydate >= $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.1);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 10% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.103) - (($servicetax1) + ($servicetax2)));
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate1 > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.12);
				$servicetax2 = roundnearestvalue($servicetax1 * 0.02);
				$servicetaxname = 'Service Tax @ 12% <br/>Cess @ 2%<br/>Sec Cess @ 1%';
				$servicetax3 = roundnearestvalue(($fetch['amount'] * 0.1236) - (($servicetax1) + ($servicetax2)));
				$totalservicetax = formatnumber($servicetax1).$appendzero.'<br/>'.formatnumber($servicetax2).$appendzero.'<br/>'.formatnumber($servicetax3).$appendzero;
			}
			else if($expirydate2 > $invoicedate)
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
				$servicetaxname = 'Service Tax @ 14%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
			}
			else
			{
				$servicetax1 = roundnearestvalue($fetch['amount'] * 0.14);
				$servicetax2 = roundnearestvalue($fetch['amount'] * 0.005);
				$servicetaxname = 'Service Tax @ 14%';
				$servicetaxname1 = 'SB Cess @ 0.5%';
				$totalservicetax = formatnumber($servicetax1).$appendzero;
				$totalservicetax1 = formatnumber($servicetax2).$appendzero;

				if($KK_Cess_date < $invoicedate)
				{
	               $KK_Cess_tax = roundnearestvalue($fetch['amount'] * 0.005);
				}
				
				$sbcolumn = '<tr><td  width="56%" style="text-align:left">&nbsp;</td>
				<td  width="30%" style="text-align:right"><strong>'.$servicetaxname1.'</strong></td>
				<td  width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax1.'</span>
				</td></tr>';

				$kkcolumn ='<tr><td  width="56%" style="text-align:right"></td><td  width="30%" style="text-align:right"><strong>KK Cess @ 0.5% </strong></td><td width="14%" style="text-align:right:font-size:+5">'.formatnumber($KK_Cess_tax).$appendzero.'</td></tr>';
			}
			
			$sezremarks = '';
		}
		$billdatedisplay = changedateformat(substr($fetch['createddate'],0,10));
		//echo($servicetax1.'#'.$servicetax2.'#'.$servicetax3); exit;
		$grid .= '<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].' </span></td>
		<td  width="30%" style="text-align:right"><strong>Net Amount</strong></td>
		<td  width="14%" style="text-align:right">'.formatnumber($fetch['amount']).$appendzero.'</td></tr>
		<tr>
		<td  width="56%" style="text-align:left"><span style="font-size:+6;color:#FF0000" >'.$sezremarks.'</span><span style="font-size:+6;color:#FF0000" >'.$statusremarks.'</span></td>
		<td  width="30%" style="text-align:right"><span style="font-size:+9" ><strong>'.$servicetaxname.'</strong></span></td><td width="14%" style="text-align:right"><span style="font-size:+9" >'.$totalservicetax.'</span></td></tr>'.$sbcolumn . $kkcolumn;



$grid .= '<tr>
<td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td>
<td  width="30%" style="text-align:right"><strong>Total</strong></td>
<td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.formatnumber($fetch['netamount']).$appendzero.'</td> 
</tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.convert_number($fetch['netamount']).' only</td></tr>';	//	echo($grid1); exit;
	//	$grid .= '<tr><td colspan="2" style="text-align:right" width="86%"><strong>Total</strong></td><td  width="14%" style="text-align:right">'.$fetch['amount'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:left"><span style="font-size:+6" >'.$fetch['servicetaxdesc'].$appendzero.' </span></td><td  width="30%" style="text-align:right"><strong>Service Tax @ 10.3%</strong></td><td  width="14%" style="text-align:right">'.$fetch['servicetax'].$appendzero.'</td></tr><tr><td  width="56%" style="text-align:right"><div align="left"><span style="font-size:+6" >E.&amp;O.E.</span></div></td><td  width="30%" style="text-align:right"><strong>Net Amount</strong></td><td  width="14%" style="text-align:right"><img src="../images/relyon-rupee-small.jpg" width="8" height="8" border="0" align="absmiddle"  />&nbsp;&nbsp;'.$fetch['netamount'].$appendzero.'</td> </tr><tr><td colspan="3" style="text-align:left"><strong>Rupee In Words</strong>: '.$fetch['amountinwords'].' only</td></tr>';
	  }

	$grid .='</table></td></tr></table>';
	$fetchresult = runmysqlqueryfetch($query);
	//to fetch dealer email id 
	$query0 = "select inv_mas_dealer.emailid as dealeremailid,cell as dealercell from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
	$dealercell = $fetch0['dealercell'];
	if($fetchresult['status'] == 'CANCELLED')
	{
		$color = '#FF3300';
		$invoicestatus = '( '.$fetchresult['status'].' )';
	}
	else if($fetchresult['status'] == 'EDITED')
	{
		$color = '#006600';
		$invoicestatus = '( '.$fetchresult['status'].' )';
	}
	else
	{
		$invoicestatus = '';
	}
	$podatepiece = (($fetchresult['podate'] == "0000-00-00") || ($fetchresult['podate'] == ''))?("Not Avaliable"):(changedateformat($fetchresult['podate']));
	$poreferencepiece = ($fetchresult['poreference'] == "")?("Not Avaliable"):($fetchresult['poreference']);
	if(strtotime($invoicenewformate) <= strtotime($newyeardate))
	{
	   $msg = file_get_contents("../pdfbillgeneration/bill-format-old.php");
	}
	else
	{
		 $msg = file_get_contents("../pdfbillgeneration/bill-format-new.php");
	}
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##BILLDATE##%^%".$billdatedisplay;
	$array[] = "##BILLNO##%^%".$fetchresult['invoiceno'];
	$array[] = "##STATUS##%^%".$invoicestatus;
	$array[] = "##DEALERDETAILS##%^%".'Email: '.$dealeremailid.' | Cell: '.$dealercell;
	$array[] = "##color##%^%".$color;
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##CONTACTPERSON##%^%".$fetchresult['contactperson'];
	$array[] = "##ADDRESS##%^%".stripslashes ( stripslashes ($fetchresult['address']));
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##EMAILID##%^%".$fetchresult['emailid'];
	$array[] = "##PHONE##%^%".$fetchresult['phone'];
	$array[] = "##CELL##%^%".$fetchresult['cell'];
	$array[] = "##STDCODE##%^%".$stdcode;
	$array[] = "##CUSTOMERTYPE##%^%".$fetchresult['customertype'];
	$array[] = "##CUSTOMERCATEGORY##%^%".$fetchresult['customercategory'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##REGION##%^%".$fetchresult['region'];
	$array[] = "##BRANCH##%^%".$fetchresult['branch'];
	$array[] = "##PAYREMARKS##%^%".$fetchresult['remarks'];
	$array[] = "##INVREMARKS##%^%".$fetchresult['invoiceremarks'];
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##INVOICEHEADING##%^%".$fetchresult['invoiceheading'];
	$array[] = "##PODATE##%^%".$podatepiece;
	$array[] = "##POREFERENCE##%^%".$poreferencepiece;
	
	$array[] = "##TABLE##%^%".$grid;
	$html = replacemailvariable($msg,$array);
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = str_replace('/','-',$fetchresult['invoiceno']);
	$filebasename = $filename.".pdf";
	$addstring ="/dealer";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "vijaykumar" ||  $_SERVER['HTTP_HOST'] == "archanaab")
		$addstring = "/saralimax-dealer";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output($filename ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$fetchresult['customerid'].'^'.$dealeremailid.'^'.$invoicestatus.'^'.$fetchresult['status'].'^'.$fetchresult['contactperson'].'^'.$fetchresult['netamount'];
	}
	$pdf->writeHTML($html, true, false, true, false, '');

}
function roundnearestvalue($amount)
{
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}

/*function generatebillnumber($dealerregion)
{
	$query4 = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where category = '".$dealerregion."'";
	$resultfetch4 = runmysqlqueryfetch($query4);
	$onlineinvoiceno = $resultfetch4['invoicenotobeinserted'];
	$invoicenoformat = 'RSL/'.$dealerregion.'/'.$onlineinvoiceno;
	return $invoicenoformat;
}*/

function addlinebreak($linecount)
{
	switch($linecount)
	{
		case '1':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '2':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '3':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '4':
		{
			$linebreak = '<tr><td width="10%"><br/><br/><br/><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		case '5':
		{
			$linebreak = '<tr><td width="10%"><br/></td><td width="76%">&nbsp;</td><td width="14%">&nbsp;</td></tr>';
		}
		break;
		
	}
	return $linebreak;
}


function generatebillnumber($dealerregion,$onlineinvoiceslno)
{
	$query4 = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_invoicenumbers where category = '".$dealerregion."'";
	$resultfetch4 = runmysqlqueryfetch($query4);
	$onlineinvoiceno = $resultfetch4['invoicenotobeinserted'];
	$invoicenoformat = 'RSL/'.$dealerregion.'/'.$onlineinvoiceno;
	return $invoicenoformat;
}

function appendcomma($value)
{
	if($value != '')
	{
		$append = ',';
	}
	else
	{
		$append = '';
	}
	return $append;
}


function removedoublecomma($string)
{
	$finalstring = $string;
	$commas =explode(',',$string);
	$countcomma = count($commas);
	for($i=0;$i<$countcomma;$i++)
	{
		$outputstring = str_replace(',,',',',$finalstring);
		$finalstring =  $outputstring;
	}
	return $outputstring;
}

function sendpurchasesummaryemail($dealerid,$recordreferencestring)
{
	$query1 = "select dealer_online_purchase.products,dealer_online_purchase.purchasetype,dealer_online_purchase.usagetype,dealer_online_purchase.quantity,dealer_online_purchase.productpricearray,inv_invoicenumbers.offerdescription,inv_invoicenumbers.servicedescription,inv_invoicenumbers.amount,inv_invoicenumbers.invoiceno,inv_invoicenumbers.createddate,inv_invoicenumbers.businessname,inv_invoicenumbers.createdby,inv_invoicenumbers.servicetax,inv_invoicenumbers.netamount from dealer_online_purchase left join inv_invoicenumbers on inv_invoicenumbers.slno = dealer_online_purchase.onlineinvoiceno where dealer_online_purchase.slno = '".$recordreferencestring."'; ";
	$fetch1 = runmysqlqueryfetch($query1);
	$onlineinvoiceno = $fetch1['onlineinvoiceno'];
	$productvalues = $fetch1['products'];
	$purchasetypevalues = $fetch1['purchasetype'];
	$usagetypevalues = $fetch1['usagetype'];
	$productquantityvalues = $fetch1['quantity'];
	$billedamount = $fetch1['productpricearray'];
	$offerdescription = $fetch1['offerdescription'];
	$servicedescription = $fetch1['servicedescription'];
	$totalamount = $fetch1['amount'];
	$netamount = $fetch1['netamount'];
	$servicetax = $fetch1['servicetax'];
	$createddate = changedateformatwithtime($fetch1['createddate']);
	$createdby = $fetch1['createdby'];
	$businessname = $fetch1['businessname'];
	$invoiceno = $fetch1['invoiceno'];
	//return ($query1); exit;
	if($productvalues <> '')
	{
	
		$actualamount = getactualprice($productvalues,$purchasetypevalues,$usagetypevalues,$productquantityvalues);
		$billedamountsplit = explode('*',$billedamount);
		$actualamountsplit = explode('*',$actualamount);
		$productvaluessplit = explode('#',$productvalues);
	}
	
	$grid = '<table width="100%" cellpadding="3" cellspacing="0"  border = "1px" class="table-border-grid" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;text-align:left" >';
	$grid .= '<tr class="tr-grid-header"  bgcolor ="#E9E9D1"><td>Sl No</td><td>Description</td><td>Billed Price</td><td>Actual Price</td><td>Variance</td></tr>';
	$slno = 0;
	if($productvalues <> '')
	{
		for($i=0;$i<count($productvaluessplit);$i++)
		{
			$query2 = "select * from inv_mas_product where productcode = '".$productvaluessplit[$i]."'";
			$resultfetch2 = runmysqlqueryfetch($query2);
			$productname = $resultfetch2['productname'];
			$slno++;
			$varience = calculatevarience($actualamountsplit[$i],$billedamountsplit[$i]);
			if($varience > 0)
				$varience = '+'.$varience.'%';
			else
				$varience = $varience.'%';
			$grid .= '<tr><td nowrap="nowrap" class="td-border-grid">'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">'.$productname.'</td><td nowrap="nowrap" class="td-border-grid">'.$billedamountsplit[$i].'</td><td nowrap="nowrap" class="td-border-grid">'.$actualamountsplit[$i].'</td><td nowrap="nowrap" class="td-border-grid"><div align="right">'.$varience.'</div></td></tr>';
		}
	}
	if($offerdescription <> '')
	{
		$offerdescriptionsplit = explode('*',$offerdescription);
		for($i=0;$i<count($offerdescriptionsplit);$i++)
		{
			$offerdescriptionline = explode('$',$offerdescriptionsplit[$i]);
			$slno++;
			$grid .= '<tr><td nowrap="nowrap" class="td-border-grid">'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">'.$offerdescriptionline[1].': '.$offerdescriptionline[0].'</td><td nowrap="nowrap" class="td-border-grid">'.$offerdescriptionline[2].'</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td></tr>';
		}
	}
	if($servicedescription <> '')
	{
		$servicedescriptionsplit = explode('*',$servicedescription);
		for($i=0;$i<count($servicedescriptionsplit);$i++)
		{
			$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
			$slno++;
			$grid .= '<tr><td>'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">'.$servicedescriptionline[1].'</td><td nowrap="nowrap" class="td-border-grid">'.$servicedescriptionline[2].'</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td></tr>';
		}
	}
	$slno++;
	$grid .= '<tr><td>'.$slno.'</td><td nowrap="nowrap" class="td-border-grid">Total</td><td nowrap="nowrap" class="td-border-grid">'.$netamount.'</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td><td nowrap="nowrap" class="td-border-grid">&nbsp;</td></tr>';
	$grid .= '</table>';
	
	$query = "select tlemailid,mgremailid,hoemailid,emailid,businessname from inv_mas_dealer where slno = '".$dealerid."';";
	$resultfetch = runmysqlqueryfetch($query);
	$tlemailid = $resultfetch['tlemailid'];
	$mgremailid = $resultfetch['mgremailid'];
	$hoemailid = $resultfetch['hoemailid'];
	$emailid = $resultfetch['emailid'];
	$dealername = $resultfetch['businessname'];
	
	//Dummy Emial ID
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$emailid = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$emailid = $emailid;
	}
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bcceallemailid = 'archana.ab@relyonsoft.com';
	}
	else
	{
		$bcceallemailid = $tlemailid.','.$mgremailid.','.$hoemailid;
	}
	
	$bccemailarray = explode(',',$bcceallemailid);
	$bccemailcount = count($bccemailarray);
	
	for($i = 0; $i < $bccemailcount; $i++)
	{
		if(checkemailaddress($bccemailarray[$i]))
		{
			if($bccemailarray[$i]!= "dealers@relyonsoft.com")
	        {
				if($i == 0)
					$bccemailids[$contactperson] = $bccemailarray[$i];
				else
					$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
			}
		}
	}
	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/purchasesummary.htm");
	$textmsg = file_get_contents("../mailcontents/purchasesummary.txt");
	$date = date('d-m-Y');
	$time = date('H:i:s');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##TIME##%^%".$time;
	$array[] = "##INVOICENO##%^%".$invoiceno;
	$array[] = "##COMPANYNAME##%^%".$businessname;
	$array[] = "##SALESPERSON##%^%".$createdby;
	$array[] = "##SALEVALUE##%^%".$totalamount;
	$array[] = "##TAX##%^%".$servicetax;
	$array[] = "##TOTALAMOUNT##%^%".$netamount;
	$array[] = "##TABLE##%^%".$grid;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
	array('../images/relyon-logo.jpg','inline','1234567890')
	);
	
	//Mail to customer
	$toarray = $emailids;
	
	//BCC to TL/Manager
	$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
	$bccarray = $bccemailids;
	
	// Get Userid
	
	
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$attachedfilename = explode('.',$filebasename);
	$subject = "Summary: Invoice (".$invoiceno.") generated for ".$dealername."";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'webmaster@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
	inserttologs(imaxgetcookie('dealeruserid'),$dealerid,$fromname,$fromemail,$emailid,null,$bcceallemailid,$subject);
}

function getactualprice($productvalues,$purchasetypevalues,$usagetypevalues,$productquantityvalues)
{
	$count = 0;
	$productssplit = explode('#',$productvalues);
	$purchasevaluesplit = explode(',',$purchasetypevalues);
	$usagevaluesplit = explode(',',$usagetypevalues);
	$productquantitysplit = explode(',',$productquantityvalues);
	for($i = 0; $i < count($productssplit); $i++)
	{
		$recordnumber = $productssplit[$i];
		$purchasetype = $purchasevaluesplit[$i];
		$usagetype = $usagevaluesplit[$i];
		$productquantity = $productquantitysplit[$i];
		if($purchasetype == 'new' && $usagetype == 'singleuser')
			$producttypeprice = 'newsuprice';
		else if($purchasetype == 'new' && $usagetype == 'multiuser')
			$producttypeprice = 'newmuprice';
		else if($purchasetype == 'updation' && $usagetype == 'singleuser')
			$producttypeprice = 'updatesuprice';
		else if($purchasetype == 'updation' && $usagetype == 'multiuser')
			$producttypeprice = 'updatemuprice';
		else if($purchasetype == 'new' && $purchasetype == 'addlic')
			$producttypeprice = 'newaddlicenseprice';
		else
			$producttypeprice = 'updationaddlicenseprice';
		$query = "select ".$producttypeprice." as productprice from inv_dealer_pricing  where product = '".$recordnumber."'";
		$fetch = runmysqlqueryfetch($query);
		$productprice = $fetch['productprice'] * $productquantity;
		if($count > 0)
				$countvalues .= '*';
		$countvalues .= $productprice;
		$count++;
	}
	return $countvalues;
}

function calculatevarience($actualamount,$billedamount)
{
	$varamount = ($billedamount-$actualamount);
	$varper = ($varamount*100)/$actualamount;
	return round($varper);
}

function getpaymentmode($mode)
{
	switch($mode)
	{
		case 'cash': $modereturned = 'Cash'; break;
		case 'onlinetransfer': $modereturned = 'Online Transfer'; break;
		case 'chequeordd': $modereturned = 'Cheque / DD'; break;
		case 'creditordebit': $modereturned = 'Credit / Debit Card'; break;
		case 'Netbanking': $modereturned = 'Online Transfer'; break;
		default: $modereturned = 'Cheque / DD';
	}
	return $modereturned;
}

function remove_duplicates($str) 
{
	//in an array called $results
  preg_match_all("([\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7})",$str,$results);
	//sort the results alphabetically
  sort($results[0]);
	//remove duplicate results by comparing it to the previous value
  $prev="";
  while(list($key,$val)=each($results[0])) 
  {
    if($val==$prev) unset($results[0][$key]);
    else $prev=$val;
  }
	//process the array and return the remaining email addresses
  $str = "";
  foreach ($results[0] as $value)
  {
     $str .= $value.",";
  }
  return trim($str,',');
}

function resendinvoice($invoiceno)
{
	$type = 'resend';
	$invoicedetails = vieworgeneratepdfinvoice($invoiceno,$type);
	$invoicedetailssplit = explode('^',$invoicedetails);
	$filebasename = $invoicedetailssplit[0];
	$businessname = $invoicedetailssplit[1];
	$invoiceno = $invoicedetailssplit[2];
	$emailid =  $invoicedetailssplit[3];
	$customerid =  $invoicedetailssplit[4];
	$dealeremailid =  $invoicedetailssplit[5];
	$invoicestatus = $invoicedetailssplit[6];
	$statuscheck = $invoicedetailssplit[7];
	$contactperson = $invoicedetailssplit[8];
	$netamount = $invoicedetailssplit[9];
	$slno = substr($customerid,15,20);
	
	$query1 = "select * from inv_mas_customer where slno = '".$slno."';";
	$resultfetch = runmysqlqueryfetch($query1);
;
	$place = $resultfetch['place'];
	
	// Fetch Contact Details
	$querycontactdetails = "select customerid,GROUP_CONCAT(emailid) as emailid from inv_contactdetails where customerid = '".$slno."'  group by customerid ";
	$resultcontactdetails = runmysqlqueryfetch($querycontactdetails);
	
	$emailidres = removedoublecomma($resultcontactdetails['emailid']);
	
	//fetch the details from customer pending table
	$query22 = "SELECT count(*) as count from inv_contactreqpending where customerid = '".$slno."' and customerstatus = 'pending' and editedtype = 'edit_type'";
	$result22 = runmysqlqueryfetch($query22);
	if($result22['count'] == 0)
	{
		$resultantemailid = $emailidres;
	}
	else
	{
		// Fetch of contact details, from pending request table if any
		$querycontactpending = "select GROUP_CONCAT(emailid) as pendemailid from inv_contactreqpending where customerid = '".$slno."' and customerstatus = 'pending' and editedtype = 'edit_type' group by customerid ";
		$resultcontactpending = runmysqlqueryfetch($querycontactpending);
		
		$emailidpending = removedoublecomma($resultcontactpending['pendemailid']);
		
		$finalemailid = $emailidres.','.$emailidpending;
		$resultantemailid = remove_duplicates($finalemailid);
	}
	if($filebasename <> '')
	{
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		
		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','1234567890'),array('../filecreated/'.$filebasename,'attachment','1234567891'),array('../images/relyon-rupee-small.jpg','inline','1234567892')
		);
		
		if($statuscheck == 'CANCELLED')
		{
			//Relyon Logo for email Content, as Inline [Not attachment]
			$filearray = array(
			array('../images/relyon-logo.jpg','inline','1234567890'),array('../filecreated/'.$filebasename,'attachment','1234567891')
			);
			$msg = file_get_contents("../mailcontents/invoicecancel.htm");
			$textmsg = file_get_contents("../mailcontents/invoicecancel.txt");
			$subject = "Relyon Online Invoice | ".$invoiceno." (Resent - CANCELLED)";
		}
		elseif($statuscheck == 'EDITED')
		{
			$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
			$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");
			$subject = "Relyon Online Invoice | ".$invoiceno." (Resent - EDITED)";
		}
		else
		{
			$msg = file_get_contents("../mailcontents/paymentinfo1.htm");
			$textmsg = file_get_contents("../mailcontents/paymentinfo1.txt");
			$subject = "Relyon Online Invoice | ".$invoiceno." (Resent)";
		}
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##COMPANYNAME##%^%".$businessname;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##INVOICENO##%^%".$invoiceno;
		$array[] = "##CONTACTPERSON##%^%".$contactperson;
		$array[] = "##TOTALAMOUNT##%^%".$netamount;
		$array[] = "##SUBJECT##%^%".$subject;
		$array[] = "##EMAILID##%^%".$resultantemailid;
		$array[] = "##CUSTOMERID##%^%".$customerid;
		#########  Mailing Starts -----------------------------------
		//$emailid = 'rashmi.hk@relyonsoft.com';
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$emailid = 'rashmi.hk@relyonsoft.com';
		}
		else
		{
			$emailid = $resultantemailid;
		}
		$emailarray = explode(',',$emailid);
		$emailcount = count($emailarray);

		for($i = 0; $i < $emailcount; $i++)
		{
			if(checkemailaddress($emailarray[$i]))
			{
					$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}
		
		//CC to Sales person
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$dealeremailid = 'archana.ab@relyonsoft.com';
		}
		else
		{
			$dealeremailid = $dealeremailid;
		}
		$ccemailarray = explode(',',$dealeremailid);
		$ccemailcount = count($ccemailarray);
		for($i = 0; $i < $ccemailcount; $i++)
		{
			if(checkemailaddress($ccemailarray[$i]))
			{
				if($i == 0)
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
				else
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			}
		}
		

		$toarray = $emailids;
		
		//CC to sales person
		$ccarray = $ccemailids;
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$bccemailids['rashmi'] ='meghana.b@relyonsoft.com';
			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccarray = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'bills@relyonsoft.com', 'Webmaster' => 'webmaster@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com', 'Usha' => 'dealers@relyonsoft.com', 'Madhuri H N' => 'madhuri.hn@relyonsoft.com');
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);

		$html = $msg;
		$text = $textmsg;
		$replyto = $ccemailids[$ccemailarray[0]];
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
		
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'bills@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com,dealers@relyonsoft.com,madhuri.hn@relyonsoft.com'; 
		inserttologs(imaxgetcookie('dealeruserid'),$slno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
		return('1^Sent successfully');
	}
}

function viewreceipt($receiptno,$type)
{
	ini_set('memory_limit', '2048M');
	require_once('../pdfbillgeneration/tcpdf.php');
	
	$query1 = "select * from inv_mas_receipt where slno = '".$receiptno."';";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$receiptstatus = $resultfetch1['status'];
	if($receiptstatus == 'CANCELLED')
	{
		// Extend the TCPDF class to create custom Header and Footer
		class MYPDF extends TCPDF {
		//Page header
		public function Header() {
			// full background image
			// store current auto-page-break status
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			$img_file = K_PATH_IMAGES.'receipt-cancelled-background.gif';
			$this->Image($img_file, 0, 70, 820, 419, '', '', '', false, 75, '', false, false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			}
		}
		
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	}
	else
	{
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		// remove default header
		$pdf->setPrintHeader(false);
	}	
	
	
	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
	
	//set some language-dependent strings
	$pdf->setLanguageArray($l); 
	
	
	// set font
	$pdf->SetFont('Helvetica', '',10);
	
	// add a page
	$pdf->AddPage();
	
	$query1 = "select * from inv_mas_receipt where slno = '".$receiptno."'";
	$result1 = runmysqlqueryfetch($query1);
	

	$query = "select * from inv_invoicenumbers 	where slno = '".$result1['invoiceno']."';";
	$fetchresult = runmysqlqueryfetch($query);
	
	$description = $fetch['description'];
	$descriptionsplit = explode('*',$description);
	$product = $descriptionsplit[1];
	
	if($result1['paymentmode'] == 'chequeordd' )
		$remarks = '<span> Cheque/DD No: '.$result1['chequeno'].' dated '.changedateformat($result1['chequedate']).', drawn on '.$result1['drawnon'].', for amount <img src="../images/relyon-rupee-small.jpg" alt="" width="8" height="8" border="0" align="absmiddle" /> '.$result1['receiptamount'].'. Cheques received are subject to realization.</span>';
	else if($result1['receiptremarks'] <> '')
	{
		$remarks = $result1['receiptremarks'];
	}
	else if($result1['receiptremarks'] == '')
	{
		$remarks = 'NONE';
	}
	
	//status of receipt
	 if($result1['status'] == 'CANCELLED')
	{
		$query011 = "select * from inv_mas_users where slno = '".$result1['cancelledby']."';";
		$resultfetch011 = runmysqlqueryfetch($query011);
		$changedby = $resultfetch011['fullname'];
		$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($result1['cancelleddate']).' <br/>'.$result1['cancelledremarks'];
	}
	elseif($result1['status'] == 'ACTIVE')
		$statusremarks = 'Not Avaliable.';
		
	// Fetch Dealer emailid 
	
	$query0 = "select inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer where inv_mas_dealer.slno = '".$fetchresult['dealerid']."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealeremailid = $fetch0['dealeremailid'];
		
	$msg = file_get_contents("../pdfbillgeneration/receipt-format.php");
	$array = array();
	$stdcode = $fetchresult['stdcode'];
	$array[] = "##RECEIPTDATE##%^%".changedateformatwithtime($result1['createddate']);
	$array[] = "##SLNO##%^%".$result1['slno'];
	$array[] = "##BUSINESSNAME##%^%".$fetchresult['businessname'];
	$array[] = "##ADDRESS##%^%".$fetchresult['address'];
	$array[] = "##CUSTOMERID##%^%".$fetchresult['customerid'];
	$array[] = "##RELYONREP##%^%".$fetchresult['dealername'];
	$array[] = "##RECEIPTREMARKS##%^%".$remarks;
	$array[] = "##GENERATEDBY##%^%".$fetchresult['createdby'];
	$array[] = "##AMOUNT##%^%".formatnumber($result1['receiptamount']);
	$array[] = "##AMOUNTINWORDS##%^%".convert_number($result1['receiptamount']);
	$array[] = "##INVOICENO##%^%".$fetchresult['invoiceno'];
	$array[] = "##MODE##%^%".getpaymentmode($result1['paymentmode']);
	$array[] = "##REMARKS##%^%".$remarks;
	$html = replacemailvariable($msg,$array);
	
	$pdf->WriteHTML($html,true,0,true);
		
	$localtime = date('His');
	$filename = 'Receipt-'.$result1['slno'];
	$filebasename = $filename.".pdf";
	$addstring ="/dealer";
	if($_SERVER['HTTP_HOST'] == "rashmihk" || $_SERVER['HTTP_HOST'] == "meghanab" || $_SERVER['HTTP_HOST'] == "archanaab")
		$addstring = "/saralimax-dealer";
		$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	
	if($type == 'view')
		$pdf->Output('example.pdf' ,'I');	
	else
	{
		$pdf->Output($filepath ,'F');
		return $filebasename.'^'.$fetchresult['businessname'].'^'.$fetchresult['invoiceno'].'^'.$fetchresult['emailid'].'^'.$result1['receiptamount'].'^'.$dealeremailid.'^'.$fetchresult['contactperson'].'^'.$fetchresult['place'].'^'.$fetchresult['customerid'].'^'.$result1['status'].'^'.$statusremarks;
	}
}


function sendreceipt($receiptno,$type)
{
	//$type = 'resend';
	$receiptdetails = viewreceipt($receiptno,'resend');
	
	$receiptdetailssplit = explode('^',$receiptdetails);
	$filebasename = $receiptdetailssplit[0];
	$businessname = $receiptdetailssplit[1];
	$invoiceno = $receiptdetailssplit[2];
	$emailid =  $receiptdetailssplit[3];
	$receiptamount =  $receiptdetailssplit[4];
	$dealeremailid =  $receiptdetailssplit[5];
	
	$contactperson = $receiptdetailssplit[6];
	$place = $receiptdetailssplit[7];
	$slno = substr($receiptdetailssplit[8],15,20);	
	if($type == 'cancelled')
	{
		$status = $receiptdetailssplit[9];
		$cancelledreason = $receiptdetailssplit[10];
	}
	
	if($filebasename <> '')
	{
		//Dummy line to override To email ID
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") ||($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab") )
			$emailid = 'meghana.b@relyonsoft.com';
		else
			$emailid = $emailid;

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		if($type == 'resend')
		{
			$msg = file_get_contents("../mailcontents/receipt.htm");
			$textmsg = file_get_contents("../mailcontents/receipt.txt");
		}
		elseif($type == 'cancelled')
		{
			$msg = file_get_contents("../mailcontents/cancelledreceipt.htm");
			$textmsg = file_get_contents("../mailcontents/cancelledreceipt.txt");
		}
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##COMPANYNAME##%^%".$businessname;
		$array[] = "##INVOICENO##%^%".$invoiceno;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##AMOUNT##%^%".$receiptamount;
		$array[] = "##CONTACTPERSON##%^%".$contactperson;
		$array[] = "##EMAILID##%^%".$emailid;
		if($type == 'cancelled')
		{
			$array[] = "##REASON##%^%".$cancelledreason;
			$array[] = "##RECEIPTNO##%^%".$receiptno;
		}
		
		#########  Mailing Starts -----------------------------------
		$emailid = $emailid;
		$emailarray = explode(',',$emailid);
		$emailcount = count($emailarray);

		for($i = 0; $i < $emailcount; $i++)
		{
			if(checkemailaddress($emailarray[$i]))
			{
					$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}
		
		//CC to Sales person
		if(($_SERVER['HTTP_HOST'] == "meghanab") ||($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab") )
			$dealeremailid = 'rashmi.hk@relyonsoft.com';
		else
			$dealeremailid = $dealeremailid;
		$ccemailarray = explode(',',$dealeremailid);
		$ccemailcount = count($ccemailarray);
		for($i = 0; $i < $ccemailcount; $i++)
		{
			if(checkemailaddress($ccemailarray[$i]))
			{
				if($i == 0)
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
				else
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			}
		}
		
		//Relyon Logo for email Content, as Inline [Not attachment]
		if($type == 'resend')
		{
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890'),
				array('../filecreated/'.$filebasename,'attachment','1234567891'),array('../images/relyon-rupee-small.jpg','inline','1234567892')
			);
		}
		elseif($type == 'cancelled')
		{
			$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890'),
				array('../filecreated/'.$filebasename,'attachment','1234567891'));
							
		}
		$toarray = $emailids;
		
		//CC to sales person
		$ccarray = $ccemailids;
		
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			$bccemailids['rashmi'] ='meghana.b@relyonsoft.com';
			//$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts'=> 'bills@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com');
		}
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		if($type == 'resend')
			$subject = "Payment Receipt against Invoice | ".$invoiceno." ";
		elseif($type == 'cancelled')
			$subject = " Receipt No ".$receiptno." (Cancelled)";
		$html = $msg;
		$text = $textmsg;
		$replyto = $ccemailids[$ccemailarray[0]];
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
			//Insert the mail forwarded details to the logs table
		$bccmailid = 'bills@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com'; 
		inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,$dealeremailid,$bccmailid,$subject);
		return('1^Receipt Sent successfully');
	}
}

// Function to display amount in Indian Format (Eg:123456 : 1,23,456)

function formatnumber($number)
{
	if(is_numeric($number))
	{
		$numbersign = "";
		$numberdecimals = "";
		
		//Retain the number sign, if present
		if(substr($number, 0, 1 ) == "-" || substr($number, 0, 1 ) == "+")
		{
			$numbersign = substr($number, 0, 1 );
			$number = substr($number, 1);
		}
		
		//Retain the decimal places, if present
		if(strpos($number, '.'))
		{
			$position = strpos($number, '.'); //echo($position.'<br/>');
			$numberdecimals = substr($number, $position); //echo($numberdecimals.'<br/>');
			$number = substr($number, 0, ($position)); //echo($number.'<br/>');
		}
		
		//Apply commas
		if(strlen($number) < 4)
		{
			$output =  $number;
		}
		else
		{
			$lastthreedigits = substr($number, -3);
			$remainingdigits = substr($number, 0, -3);
			$tempstring = "";
			for($i=strlen($remainingdigits),$j=1; $i>0; $i--,$j++)
			{
				if($j % 2 <> 0)
					$tempstring = ','.$tempstring;
				$tempstring = $remainingdigits[$i-1].$tempstring;
			}
			$output = $tempstring.$lastthreedigits;
		}
		$finaloutput = $numbersign.$output.$numberdecimals;
		return $finaloutput;	
	}
	else
	{
		$finaloutput = 0;
		return $finaloutput;
	}
}

//Function to convert the number to words
function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    	throw new Exception("Number is out of range");
    } 
	 
	$cn = floor($number / 10000000); /* Crores */
	$number -= $cn * 10000000;   
	
	$ln = floor($number / 100000);  /* Lakhs */
	$number -= $ln * 100000;
	
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
	
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
	
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;             /* Ones */ 

    $res = ""; 


	if($cn)
	{
		 $res .= convert_number($cn) . " Crore"; 
	}
	
	if($ln)
	{
		$res .= (empty($res) ? "" : " ") . 
            convert_number($ln) . " Lakh";
	}
    if ($kn) 
    { 
		
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 

function finalsplit($name)
{
	$array[]= split(',',$name);
	for($j=0;$j<count($array);$j++)
	{
		$splitarray = $array[$j][0];
	}
	return $splitarray;
}

function sendimplementationmail($slno,$customerreference,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$slno."' and imp_implementation.customerreference = '".$customerreference."' ";
	$result = runmysqlqueryfetch($query);
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$userid."'";
	$result11 = runmysqlqueryfetch($query11);
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	
	//To fetch Branch Head Emailid 
	
	$query12 = "select TRIM(BOTH ',' FROM group_concat(emailid))as branchemailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."' ";
	$result12 = runmysqlqueryfetch($query12);
	$branchemailid = $result12['branchemailid'];
	
	$resultemailid = $dealeremailid.','.$branchemailid;
	
	$businessname = $result['businessname'];
	$place = $result['place'];
	$status = implemenationstatus($slno);
	
	$statusssplit = explode('#@#',$status);
	$statusname1 = $statusssplit[0];
	$statusremarks1 = $statusssplit[1];
	
	if(strtoupper($result['customizationapplicable']) == 'NO')
	{
		$custstatusdisplay = 'Not Applicable';
		$custremarksdisplay = 'Not Available';
	}
	else
	{
		$custstatusdisplay = $result['customizationstatus'];
		$custremarksdisplay = $result['customizationremarks'];;
	}
	
	$resultcount = "SELECT count(*) as count from imp_customizationfiles where imp_customizationfiles.impref = '".$slno."';";
	$fetch10 = runmysqlqueryfetch($resultcount);
	$fetchresultcount = $fetch10['count'];
	
	
	
	$query = "SELECT imp_customizationfiles.slno,imp_customizationfiles.remarks,imp_customizationfiles.attachfilepath from imp_customizationfiles  WHERE imp_customizationfiles.impref = '".$slno."' order by createddatetime DESC ;";
	$custgrid = '<table width="100%" cellpadding="2" cellspacing="0" border ="1">';
	$custgrid .= '<tr  align ="left"><td nowrap = "nowrap" width="20%">Sl No</td><td nowrap = "nowrap" width="80%">Remarks</td></tr>';
	$i_n = 0;
	$result1 = runmysqlquery($query);
	$fetchcount = mysqli_num_rows($result1);
	if($fetchcount <> 0)
	{
		while($fetch23 = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$custgrid .= '<tr bgcolor='.$color.'  align ="left">';
			$custgrid .= "<td nowrap='nowrap' width='20%'>".$slno."</td>";
			$custgrid .= "<td nowrap='nowrap' width='80%'>".gridtrim($fetch23['remarks'])."</td>";

			$custgrid .= "</tr>";
		}
	}
	else
	{
		$custgrid .='<tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"  align="center"><font color="#FF4F4F" >No More Records</font></td></tr></table></td></tr>';
	}
	
	$custgrid .= "</table>";
	//Add-on's Grid Details  
	$query15 = "SELECT imp_addon.slno, imp_addon.customerid, imp_addon.refslno, imp_mas_addons.addonname as addon, imp_addon.remarks,imp_addon.addon as addonslno from imp_addon left join imp_mas_addons on imp_mas_addons.slno = imp_addon.addon where refslno  = '".$slno."';";
	$addongrid = '<table width="100%" cellpadding="5" cellspacing="0"><tr><td><table width="100%" cellpadding="2" cellspacing="0" border="1">';
	$addongrid .= '<tr align ="left"><td nowrap = "nowrap"  ><strong>Sl No</strong></td><td nowrap = "nowrap" ><strong>Add-on</strong></td><td nowrap = "nowrap" ><strong>Remarks</strong></td></tr>';
	$result15 = runmysqlquery($query15);
	$slno3 =0;
	while($fetch15 = mysqli_fetch_array($result15))
	{
		$slno3++;
		$addongrid .= '<tr align ="left">';
		$addongrid .= "<td nowrap='nowrap' >".$slno3."</td>";
		$addongrid .= "<td >".$fetch15['addon']."</td>";
		$addongrid .= "<td >".$fetch15['remarks']."</td>";
		$addongrid .= "</tr>";
	}
	$fetchcount2 = mysqli_num_rows($result15);
	if($fetchcount2 == '0')
		$addongrid .= "<tr><td colspan ='3' class='imp_td-border-grid'><div align='center'><font color='#FF0000'><strong>No Records to Display</strong></font></div></td></tr>";
		$addongrid .= "</table></td></tr></table>";
	
	
	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerreference."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		if(checkemailaddress($fetchres['emailid']))
		{
			if($fetchres['contactperson'] != '')
				$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
			else
				$emailids[$fetchres['emailid']] = $fetchres['emailid'];
		}
		$contactvalue .= $fetchres['contactperson'].',';
		$emailidvalues .= $fetchres['emailid'].',';
		
	}
	$contactperson = trim(removedoublecomma($contactvalue),',');
	$emailid = trim(removedoublecomma($emailidvalues),',');

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}

	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/newimplementation.htm");
	$textmsg = file_get_contents("../mailcontents/newimplementation.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##STATUS##%^%".$statusname1;
	$array[] = "##STATUSREMARKS##%^%".$statusremarks1;	
	$array[] = "##PODATE##%^%".$result['podetails'];
	$array[] = "##NOCOMPANY##%^%".$result['numberofcompanies'];
	$array[] = "##NOMONTH##%^%".$result['numberofmonths'];
	$array[] = "##PROMONTH##%^%".$result['processingfrommonth'];
	$array[] = "##STARTDATE##%^%".changedateformat($result['committedstartdate']);
	$array[] = "##ADDONTABLE##%^%".$addongrid;
	$array[] = "##CUSTSTATUS##%^%".$custstatusdisplay;
	$array[] = "##CUSTREMARKS##%^%".$custremarksdisplay;
	$array[] = "##CUSTTABLE##%^%".$custgrid;
	$array[] = "##EMAILID##%^%".$emailid;

	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
		array('../images/contact-info.gif','inline','33333333333')
	
	);
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$resultemailid1 = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$resultemailid1 = $resultemailid ;
	}
	$ccemailarray = explode(',',$resultemailid1);
	$ccemailcount = count($ccemailarray);
	for($i = 0; $i < $ccemailcount; $i++)
	{
		if(checkemailaddress($ccemailarray[$i]))
		{
			if($i == 0)
				$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			else
				$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
		}
	}
	$toarray = $emailids;
	$ccarray = $ccemailids;
	
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['vidyananda'] = 'vidyananda.csd@relyonsoft.com';
		$bccemailids['hariharan'] = 'hariharan.csd@relyonsoft.com';
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Implementation Request (Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
	return 'sucess';
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerreference,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}

function implemenationstatus($lastslno)
{
	$query = "SELECT imp_implementation.branchreject,imp_implementation.branchapproval, imp_implementation.coordinatorreject, imp_implementation.coordinatorapproval, imp_implementation.implementationstatus, inv_mas_implementer.businessname from  imp_implementation left join inv_mas_implementer on inv_mas_implementer.slno = imp_implementation.assignimplemenation 
where imp_implementation.slno = '".$lastslno."';";
	$fetch = runmysqlqueryfetch($query);
	
	$query1 = "Select iccattachmentpath,iccattachmentdate,inv_mas_implementer.businessname from imp_implementationdays
left join  inv_mas_implementer on inv_mas_implementer.slno = imp_implementationdays.iccattachmentby
where imp_implementationdays.impref = '".$lastslno."' and iccattachment = 'yes';";
	$result = runmysqlquery($query1);
	$fetchcount = mysqli_num_rows($result);
	if($fetchcount <> 0)
		$result1 = runmysqlqueryfetch($query1);
	if($fetch['branchreject'] == 'no'  && $fetch['branchapproval'] == 'no'  && $fetch['coordinatorreject'] == 'no' && $fetch['coordinatorapproval'] == 'no' && $fetch['implementationstatus'] == 'pending')	
	{
		$statusname = 'Awaiting Branch Head Approval.';
		$statusremarks = 'Your Implementation activity has been submitted in the system. It is awaiting the approval from respective head. This will be executed shortly.';
	}
	elseif($fetch['branchapproval'] == 'no'  && $fetch['branchreject'] == 'yes' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Fowarded back to Sales Person.';
		$statusremarks = 'As there were few clarifications needed, your implementation activity has been forwarded back to respective Sales person.';
	}
	elseif($fetch['branchapproval'] == 'yes'  && $fetch['coordinatorreject'] == 'no' && $fetch['coordinatorapproval'] == 'no' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Awaiting Co-ordinator Approval.';
		$statusremarks = 'Your Implementation activity has been approved by respective head and now is with Coordinator. It wil be shortly reveiwed and processed further.';
	}
	elseif($fetch['branchapproval'] == 'no' && $fetch['coordinatorreject'] == 'yes' && $fetch['coordinatorapproval'] == 'no' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Fowarded back to Branch Head.';
		$statusremarks = 'As there were few clarifications needed, your implementation activity has been forwarded back to respective head. It shall be processed soon.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no' && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Implementation, Yet to be Assigned.';
		$statusremarks = 'Your Implementation activity has been approved with all the levels. It will soon be assigned with Implementer and respective visits be scheduled.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no'  && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'pending')
	{
		$statusname = 'Assigned For Implementation.';
		$statusremarks = 'You have been assigned with Implementer  <font color="#178BFF"><strong> "'.$fetch['businessname'].'" </strong></font> . The visits scheduled shall be displayed here for your information / action.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no'  && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'progess')
	{
		$statusname = 'Implementation in progess.';
		$statusremarks = 'Visits are under progress for your Implementation. Our implmeneter has started his visits. The status remains the same until we receive "Implementation Completion Certificate.';
	}
	elseif($fetch['branchapproval'] == 'yes' && $fetch['coordinatorreject'] == 'no'  && $fetch['coordinatorapproval'] == 'yes' && $fetch['implementationstatus'] == 'completed')
	{
		$statusname = 'Implementation Completed.';
		$statusremarks = 'Your Implementation has been successfully completed. Please click here to view the "Implementation Completion Certificate.';
	}
		
		return $statusname.'#@#'.$statusremarks;
}

function sendbranchappovedmail($lastslno,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$lastslno."'";
	$result = runmysqlqueryfetch($query);
	
	$approvedreason = $result['approvalremarks'];
	$customerslno = $result['customerreference'];
	$businessname = $result['businessname'];
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$result['dealerid']."' and disablelogin='no'";
	$result11 = runmysqlqueryfetch($query11);
	
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	$dealername = $result11['businessname'];
	$regionid = $result11['region'];
	
	//To fetch Branch Head Emailid 
	$query12 = "select TRIM(BOTH ',' FROM group_concat(emailid))as branchemailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."' and disablelogin='no'";
	$result12 = runmysqlqueryfetch($query12);
	
	
	$branchname = $result12['businessname'];
	
	// Fetch coordination Emailid 
	$query13 = "select TRIM(BOTH ',' FROM group_concat(emailid))as coemailid from inv_mas_implementer where (branchid ='".$branchid."' or branchid ='all') and coordinator = 'yes' and region = '".$regionid."'; ";
	$result13 = runmysqlqueryfetch($query13);
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$branchemailid = 'archana.ab@relyonsoft.com';
		$coordinatoremaild = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$branchemailid = $result12['branchemailid']; 
		$coordinatoremaild = $result13['coemailid'];
	}
	$emailid = $dealeremailid;
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}
	// CC to branchhead and Coordinator
	$ccids = $branchemailid.','.$coordinatoremaild;
	$ccemailarray = explode(',',$ccids);
	$ccemailcount = count($ccemailarray);
	for($i = 0; $i < $ccemailcount; $i++)
	{
		if(checkemailaddress($ccemailarray[$i]))
		{
			if($i == 0)
				$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			else
				$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/approvedbybranchhead.htm");
	$textmsg = file_get_contents("../mailcontents/approvedbybranchhead.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##BUSINESSNAME##%^%".$businessname;
	$array[] = "##BRANCHEADNAME##%^%".$branchname;
	$array[] = "##SALESNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##APPREMARKS##%^%".$approvedreason;
	
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	
	$toarray = $emailids;
	$ccarray = $ccemailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Approval of Implementation Request by Branch Head (Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);

	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	
}

function sendupdateimpmail($slno,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$slno."'";
	$result = runmysqlqueryfetch($query);
	
	$customerslno = $result['customerreference'];
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$userid."'";
	$result11 = runmysqlqueryfetch($query11);
	
	$dealeremailid = $result11['emailid'];
	$branchid = $result11['branch'];
	$dealername = $result11['businessname'];
	
	//To fetch Branch Head Emailid 
	
	$query12 = "select TRIM(BOTH ',' FROM group_concat(emailid))as branchemailid,TRIM(BOTH ',' FROM group_concat(businessname))as businessname  from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."'";
	$result12 = runmysqlqueryfetch($query12);
	$branchemailid = $result12['branchemailid'];
	$branchname = $result12['businessname'];
	
	$emailid = $branchemailid;
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}


	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}

	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/updateimp.htm");
	$textmsg = file_get_contents("../mailcontents/updateimp.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##BRANCHEADNAME##%^%".$branchname;
	$array[] = "##SALESNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;

	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['vidyananda'] = 'vidyananda.csd@relyonsoft.com';
		$bccemailids['hariharan'] = 'hariharan.csd@relyonsoft.com';
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
	}

	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Update in the Implementation Request for the Invoice No '.$result['invoicenumber'].'(Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
	return 'sucess';
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
}


function sendshippmentmail($customerid,$type,$remarks)
{
	$query = "select count(*) as count from imp_implementation  where imp_implementation.customerreference = '".$customerid."'";
	$result = runmysqlqueryfetch($query);
	$fetchcount = $result['count'];
	if($fetchcount <> 0)
	{
		// Fetch Contact Details
		$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerid."'; ";
		$resultfetch = runmysqlquery($query1);
		$valuecount = 0;
		while($fetchres = mysqli_fetch_array($resultfetch))
		{
			if(checkemailaddress($fetchres['emailid']))
			{
				if($fetchres['contactperson'] != '')
					$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
				else
					$emailids[$fetchres['emailid']] = $fetchres['emailid'];
			}
			$contactvalue .= $fetchres['contactperson'].',';
			$emailidvalues .= $fetchres['emailid'].',';
			
		}
		$contactperson = trim(removedoublecomma($contactvalue),',');
		$emailid = trim(removedoublecomma($emailidvalues),',');
		
		$queryvalue = "Select * from inv_mas_customer where inv_mas_customer.slno = '".$customerid."'";
		$resultvalue = runmysqlqueryfetch($queryvalue);
		
		$query214 = "Select * from imp_implementation where imp_implementation.customerreference = '".$customerid."'";
		$result214 = runmysqlqueryfetch($query214);
		
		
		$busnessname = $resultvalue['businessname'];
		$place = $resultvalue['place'];
if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		{
			unset($emailids);
			$emailids[] = 'meghana.b@relyonsoft.com';
		}
	
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		if($type == 'invoice')
		{
			$msg = file_get_contents("../mailcontents/shippmentinvoice.htm");
			$textmsg = file_get_contents("../mailcontents/shippmentinvoice.txt");
		}
		elseif($type == 'manual')
		{
			$msg = file_get_contents("../mailcontents/shippmentmanual.htm");
			$textmsg = file_get_contents("../mailcontents/shippmentmanual.txt");
		}
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$contactperson;
		$array[] = "##COMPANY##%^%".$busnessname;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##INVOICENO##%^%".$result214['invoicenumber'];
		$array[] = "##REMARKS##%^%".$remarks;	
		$array[] = "##EMAILID##%^%".$emailid;

		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','8888888888'),
		
		);
		$toarray = $emailids;
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
		{
			$bccemailids['archana'] ='archana.ab@relyonsoft.com';
		}
		else
		{
			$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		}
	
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		if($type == 'invoice')
			$subject = 'Shippment Information (Invoice)(Beta)'; 
		elseif($type == 'manual')
			$subject = 'Shippment Information (Manual)(Beta)'; 
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray);
		return 'Mail has been sent Successfully.'.$result214['shipinvoiceremarks'];
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'bigmail@relyonsoft.com';
		inserttologs($userid,$customerid,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	}
	else
	return 'Implementation Request is not Created Yet.';
}

function sendadvcollectmail($lastslno,$customerid,$type,$userid)
{
	$query = "select count(*) as count from imp_implementation  where imp_implementation.customerreference = '".$customerid."' and
	imp_implementation.slno =  '".$lastslno."'";
	$result = runmysqlqueryfetch($query);
	$fetchcount = $result['count'];
	if($fetchcount <> 0)
	{
		// Fetch Contact Details
		$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$customerid."'; ";
		$resultfetch = runmysqlquery($query1);
		$valuecount = 0;
		while($fetchres = mysqli_fetch_array($resultfetch))
		{
			if(checkemailaddress($fetchres['emailid']))
			{
				if($fetchres['contactperson'] != '')
					$emailids[$fetchres['contactperson']] = $fetchres['emailid'];
				else
					$emailids[$fetchres['emailid']] = $fetchres['emailid'];
			}
			$contactvalue .= $fetchres['contactperson'].',';
			$emailidvalues .= $fetchres['emailid'].',';
			
		}
		$contactperson = trim(removedoublecomma($contactvalue),',');
		$emailid = trim(removedoublecomma($emailidvalues),',');
		
		$queryvalue = "Select * from inv_mas_customer where inv_mas_customer.slno = '".$customerid."'";
		$resultvalue = runmysqlqueryfetch($queryvalue);
		
		$query214 = "Select * from imp_implementation where imp_implementation.customerreference = '".$customerid."'";
		$result214 = runmysqlqueryfetch($query214);
		
		//To fetch Sales Person Emailid 
		$query11 = "select * from inv_mas_dealer where slno = '".$userid."'";
		$result11 = runmysqlqueryfetch($query11);
		
		$dealeremailid = $result11['emailid'];
		$branchid = $result11['branch'];
		$dealername = $result11['businessname'];
		
		//To fetch Branch Head Emailid 
		
		$query12 = "select TRIM(BOTH ',' FROM group_concat(emailid))as branchemailid,TRIM(BOTH ',' FROM group_concat(businessname))as businessname  from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."'";
		$result12 = runmysqlqueryfetch($query12);
		$branchemailid = $result12['branchemailid'];
		
		// CC to Sales person
		$ccids = $dealeremailid;
		$ccemailarray = explode(',',$ccids);
		$ccemailcount = count($ccemailarray);
		for($i = 0; $i < $ccemailcount; $i++)
		{
			if(checkemailaddress($ccemailarray[$i]))
			{
				if($i == 0)
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
				else
					$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			}
		}
		// BCC to Branch Head person
		$bccids = $branchemailid;
		$bccemailarray = explode(',',$bccids);
		$bccemailcount = count($bccemailarray);
		for($i = 0; $i < $bccemailcount; $i++)
		{
			if(checkemailaddress($bccemailarray[$i]))
			{
				if($i == 0)
					$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
				else
					$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
			}
		}
		
		if($type == 'receipt')
		{
			$query9 = "Select slno from inv_invoicenumbers where inv_invoicenumbers.invoiceno = '".$result214['invoicenumber']."'";
			$result9 = runmysqlqueryfetch($query9);	
			$query10 = "Select receiptamount from inv_mas_receipt where inv_mas_receipt.invoiceno = '".$result9['slno']."'";
			$result10 = runmysqlqueryfetch($query10);
			$amount = $result10['receiptamount'];
		}
		elseif($type == 'advamount')
		{
			$amount = $result214['advanceamount'];
		}
		
		
		$busnessname = $resultvalue['businessname'];
		$place = $resultvalue['place'];
if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") )
		{
			unset($emailids);
			$emailids[] = 'meghana.b@relyonsoft.com';
		}
	
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/advcollect.htm");
		$textmsg = file_get_contents("../mailcontents/advcollect.txt");
		
	
		//Create an array of replace parameters
		$array = array();
		$date = datetimelocal('d-m-Y');
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$contactperson;
		$array[] = "##COMPANY##%^%".$busnessname;
		$array[] = "##PLACE##%^%".$place;
		$array[] = "##INVOICENO##%^%".$result214['invoicenumber'];
		$array[] = "##AMOUNT##%^%".$amount;
		$array[] = "##EMAILID##%^%".$emailid;

		//Relyon Logo for email Content, as Inline [Not attachment]
		$filearray = array(
			array('../images/relyon-logo.jpg','inline','8888888888'),
		
		);
		$toarray = $emailids;
		$ccarray = $ccemailids;
		if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk"))
		{
			$bccemailids['archana'] ='rashmi.hk@relyonsoft.com';
		}
		else
		{
			$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		}
	
		$bccarray = $bccemailids;
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$subject = 'Advance Payment Information (Beta)'; 
		$html = $msg;
		$text = $textmsg;
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
		return 'sucess';
		
		//Insert the mail forwarded details to the logs table
		$bccmailid = 'bigmail@relyonsoft.com';
		inserttologs($userid,$customerid,$fromname,$fromemail,$emailid,$ccids,$bccids,$subject);
	}
	else
	return 'Implementation Request is not Created Yet.';
}
function matcharray($array1,$array2)
{
	$found = false;
	for($i = 0; $i < count($array1); $i++)
	{
		if(in_array($array1[$i],$array2))
		{
			$found = true;
			break;
		}
	}
	return $found;
}

function sendbranchrejectmail($lastslno,$userid)
{
	$query = "select * from imp_implementation left join inv_mas_customer on inv_mas_customer.slno = imp_implementation.customerreference  where imp_implementation.slno = '".$lastslno."'";
	$result = runmysqlqueryfetch($query);
	
	$rejectreason = $result['branchrejectremarks'];
	$customerslno = $result['customerreference'];
	$businessname = $result['businessname'];
	
	//To fetch Sales Person Emailid 
	$query11 = "select * from inv_mas_dealer where slno = '".$result['dealerid']."'";
	$result11 = runmysqlqueryfetch($query11);
	
	$dealeremailid = $result11['emailid'];
	$dealername = $result11['businessname'];
	
	//To fetch Branch Head Emailid 
	$query12 = "select TRIM(BOTH ',' FROM group_concat(emailid))as branchemailid from inv_mas_dealer where branchhead = 'yes' and branch = '".$branchid."'";
	$result12 = runmysqlqueryfetch($query12);
	$branchname = $result12['businessname'];

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		$branchemailid = 'rashmi.hk@relyonsoft.com';
	}
	else
	{
		$branchemailid = $result12['branchemailid']; 
	}
	$emailid = $dealeremailid;
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);

	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
		unset($emailids);
		$emailids[] = 'meghana.b@relyonsoft.com';
	}
	
	// CC to branchhead and Coordinator
	$ccids = $branchemailid;
	$ccemailarray = explode(',',$ccids);
	$ccemailcount = count($ccemailarray);
	for($i = 0; $i < $ccemailcount; $i++)
	{
		if(checkemailaddress($ccemailarray[$i]))
		{
			if($i == 0)
				$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
			else
				$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
		}
	}
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/rejectbybranchhead.htm");
	$textmsg = file_get_contents("../mailcontents/rejectbybranchhead.txt");

	//Create an array of replace parameters
	$array = array();
	$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##INVOICENO##%^%".$result['invoicenumber'];
	$array[] = "##BUSINESSNAME##%^%".$businessname;
	$array[] = "##BRANCHEADNAME##%^%".$branchname;
	$array[] = "##SALESNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	$array[] = "##REJREMARKS##%^%".$rejectreason;
	
	//Relyon Logo for email Content, as Inline [Not attachment]
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	
	);
	
	$toarray = $emailids;
	$ccarray = $ccemailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab"))
	{
		$bccemailids['archana'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
	}
	
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = 'Rejected Implementation Request by Branch Head (Beta)'; 
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray);
	return 'sucess';

	//Insert the mail forwarded details to the logs table
	$bccmailid = 'bigmail@relyonsoft.com';
	inserttologs($userid,$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid,$subject);
	
}
?>