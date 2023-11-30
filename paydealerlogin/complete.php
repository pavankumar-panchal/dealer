<?php
ob_start("ob_gzhandler");
include("../functions/phpfunctions.php"); 
//Configuration
if(!empty($_POST))
{
 $TxnID = $_POST['ipgTransactionId'];
 $currency = $_POST['currency'];
 $chargetotal = $_POST['chargetotal'];
 $oid = $_POST['oid'];
 $status = $_POST['status'];
 $fail_rc = $_POST['fail_rc'];
 $approval_code = $_POST['approval_code'];
 $responseMessage = explode(":",$approval_code);
 
 if($status == 'FAILED')
 {
     $ResponseCode = '2';
 }
 elseif($status == 'APPROVED')
 {
      $ResponseCode = '0';
 }
 else {
     
    echo("Error Occurred in transaction");
	exit; 
 }
   $newResponseMessage = $responseMessage[2];
  
  if (is_numeric($newResponseMessage)) {
    $newResponseMessage = 'Transaction Successful';
  }
}
else
{
    echo("Invalid Entry");
	exit;
}


// new code to to restrict multiple entries in db

$test_query = "select responsecode,pgtxnid from transactions where orderid = '".$oid."'";
$result_test_query = runicicidbquery($test_query);

$fetchresult_test_query = mysqli_fetch_array($result_test_query);

$test_responsecode = $fetchresult_test_query['responsecode'];
$test_pgtxnid = $fetchresult_test_query['pgtxnid'];

if($test_responsecode != '' && $test_pgtxnid != '')
{
  echo "The Transaction has been completed and page has been expired";
  exit();
}

// new code to to restrict multiple entries in db ends

//Update the transactions table
$query = "update transactions set responsecode = '".$ResponseCode."', responsemessage = '".$newResponseMessage."', pgtxnid = '".$TxnID."', authidcode = '".$TxnID."', rrn = '".$TxnID."', cvrespcode = '".$CVRespCode."', fdmsscore = '".$FDMSScore."', fdmsresult = '".$FDMSResult."', cookievalue = '".$Cookie."' where orderid = '".$oid."'";
$result = runicicidbquery($query);

$query = "select * from transactions where orderid = '".$oid."'";
$result = runicicidbquery($query);
$transaction = mysqli_fetch_array($result);

$company = $transaction['company'];
$contactperson = $transaction['contactperson'];
$address = $transaction['address1'];
$place = $transaction['city'];
$phone = $transaction['phone'];
$slno = $transaction['recordreference'];


	
if($ResponseCode == 0)
{
	//Update payment details to SPP Customer table
	$query = "insert into `inv_credits`(dealerid, credittype, creditamount, createddate, remarks, createdby)values('".$transaction['recordreference']."', 'creditcard', '".$transaction['amount']."', '".$transaction['date']."', 'Payment made by credit card at ".$transaction['time']."', '2')";
	$result = runmysqlquery($query);
	
	//Calculate the Total credits avaliable for that particular dealer
	$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$slno."'";
	$resultfetch = runmysqlqueryfetch($query0);
	$totalcredit = $resultfetch['totalcredit'];
	$query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$slno."' AND billstatus ='successful'";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$billedamount =$resultfetch1['billedamount'];
	$totalcredit = $totalcredit - $billedamount;

	$amt = $transaction['amount'];
	$emailid = $transaction['emailid'];
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}


	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/paymentinfo.htm");
	$textmsg = file_get_contents("../mailcontents/paymentinfo.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$company;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##DEALERID##%^%".$slno;
	$array[] = "##ADDRESS##%^%".$address;
	$array[] = "##PHONE##%^%".$phone;
	$array[] = "##PAYMENTAMOUNT##%^%".$amt;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','1234567890'),array('../images/relyon-rupee-small.jpg','inline','1234567892')
	);
	
	//Mail to customer
	$toarray = $emailids;
	
	//Copy of email to Accounts / Vijay Kumar / Bigmails
	$bccarray = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts' => 'accounts@relyonsoft.com', 'webmaster' => 'webmaster@relyonsoft.com', 'Relyonimax' => 'relyonimax@gmail.com'); 

	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Payment Successfully made by ".$company;
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray); 
	
	//Insert the mail forwarded details to the logs table
	$bccmailid = 'vijaykumar@relyonsoft.com,accounts@relyonsoft.com,bigmail@relyonsoft.com,webmaster@relyonsoft.com'; 
	inserttologs(imaxgetcookie('dealeruserid'),$transaction['recordreference'],$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<meta name="keywords" content="Register with Relyon for free downloads, newsletters and many more..">
<meta name="description" content="Relyon user login pages.">
<link href="../style/main.css" rel="stylesheet" type="text/css">
<meta name="copyright" content="Relyon Softech Ltd. All rights reserved." />
</head>
<script language="javascript">
function returnhomepage()
{
	window.location = '../purchase/dealerpayment.php';
	return false;
}
</script>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="maincontainer">
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="headercontainer">
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><div id=logo><span style="vertical-align:middle"></span></div>
                        <div id=relyonlogo><span style="vertical-align:middle"></span></div></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainbg">
              <tr>
                <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="main">
                    <tr>
                      <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
                          <tr>
                            <td class="bannerbg" height="118">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="youarehere">
                                <tr>
                                  <td align="left">&nbsp;</td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                <tr>
                                  <td width="23%" valign="top"  ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
                                      <tr>
                                        <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                              <td align="center"><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                  <tr>
                                                    <td  ></td>
                                                  </tr>
                                                  <tr>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td><table width="750px" border="0" align="center" cellpadding="0" cellspacing="0" >
                                                        <td colspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                        <? if($ResponseCode == 0) { ?>
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 2px #272727">
                                                                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="5"  >
                                                                          <tr>
                                                                            <td colspan="2" class="subheading-font">Payment Status</td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10px" colspan="2"></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="3px" colspan="2" class="blueline" ></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="paymentbg">
                                                                                <tr>
                                                                                  <td width="61%" class="subfonts" style="padding-left:15px" >Transaction Successful</td>
                                                                                  <td width="39%" class="subfonts" height="37"></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2"><table width="90%" border="0" cellspacing="0" cellpadding="5" align="center">
                                                                                <tr>
                                                                                  <td width="45%" valign="top" class="displayfont"><strong>Payment from :</strong><br />
                                                                                    <? echo($company)?><br />
                                                                                    <? echo('('.$contactperson.')')?><br />
                                                                                    <? echo($address)?><br />
                                                                                    <? echo($place)?> </td>
                                                                                  <td width="45%"  valign="top" class="displayfont"><strong>Payment To :</strong><br />
                                                                                    Relyon Softech Ltd<br />
                                                                                    No. 73, Shreelekha Complex, <br />
                                                                                    WOC Road,Bangalore :560 086<br />
                                                                                    Phone: 1860-425-5570 <br />
                                                                                    Email: support@relyonsoft.com</td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2" class="fontstyle" ><p align="left">You have been successfully paid for <img src="../images/relyon-rupee-small.jpg" width="8" height="10" /><font color="#000000">&nbsp;<? echo($amt.'.00')?></font>. An email also have been sent to <font color="#FF0000"><? echo($emailid)?></font> with the confirmation.<br />
                                                                            </p></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2" height="10px"></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2"><table width="600px" border="0" cellspacing="0" cellpadding="5" bgcolor="#eeeeee" align="center">
                                                                                <tr>
                                                                                  <td><table width="400px" border="0" cellspacing="0" cellpadding="3" align="center" style="border:solid 1px #D4D4D4">
                                                                                      <tr>
                                                                                        <td class="displayfont"><p align="center"><strong>Transaction Status:</strong> <? echo($Message); ?><br />
                                                                                            <strong>Relyon Transaction ID:</strong> <? echo($TxnID); ?><br />
                                                                                            <strong>ICICI Transaction reference Number:</strong> <? echo($TxnID); ?><br />
                                                                                            <strong>Authorization ID: </strong> <? echo($TxnID) ?> <br />
                                                                                          </p></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2" class="displayfont" ><p align="left"><strong>Total credits available in your account after transaction: <font color="#FF0000"><img src="../images/relyon-rupee-small.jpg" width="8" height="10" /> <? echo($totalcredit.'.00')?></font>.</strong></p></td>
                                                                          <tr>
                                                                            <td colspan="2">&nbsp;</td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2" style="border-top:solid 2px #8e8e8e" height="10px"></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td width="65%">&nbsp;</td>
                                                                            <td width="35%"><div align="center">
                                                                                <input type="button" id="print" name="print" value="Print" onclick="window.print()"/>
                                                                                &nbsp;&nbsp;&nbsp;
                                                                                <input type="button" id="update" name="update" value="Go to Home Page" onclick="returnhomepage()"  />
                                                                              </div></td>
                                                                          </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <? }else{?>
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 2px #272727">
                                                              <tr>
                                                                <td ><table width="100%" border="0" cellspacing="0" cellpadding="5"  >
                                                                    <tr>
                                                                      <td colspan="2" class="subheading-font">Payment Status</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10px" colspan="2"></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="3px" colspan="2" class="blueline" ></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="paymentbg">
                                                                          <tr>
                                                                            <td width="61%" class="subfonts" style="padding-left:15px" >Transaction Failure</td>
                                                                            <td width="39%" class="subfonts" height="37"></td>
                                                                          </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2" class="fontstyle" ><p align="left">The transaction was NOT successful due to rejection by Gateway / Card issuing Authority. Please try again. </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2"><table width="600px" border="0" cellspacing="0" cellpadding="5" bgcolor="#eeeeee" align="center">
                                                                          <tr>
                                                                            <td height="10px"></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td><table width="400px" border="0" cellspacing="0" cellpadding="3" align="center" style="border:solid 1px #D4D4D4" >
                                                                                <tr>
                                                                                  <td class="displayfont"><p align="center"><strong>Transaction Status:</strong><? echo($Message); ?><br />
                                                                                      <strong>Relyon Transaction ID:</strong> <? echo($TxnID); ?><br />
                                                                                      <strong>ICICI Transaction reference Number:</strong> <? echo($TxnID); ?><br />
                                                                                    </p></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10px"></td>
                                                                          </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2" style="border-top:solid 2px #8e8e8e" height="10px"></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <? }?>
                                                        <tr>
                                                          <td></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr >
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="footer">
                          <tr>
                            <td align="left"><p>A product of Relyon Web Management | Copyright © 2009 Relyon Softech Ltd. All rights reserved.</p></td>
                            <td align="left"><div align="right"><font color="#FFFFFF">Version 1.03</font></div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>