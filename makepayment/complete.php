<?php

include("../functions/phpfunctions.php"); 


//Configuration
require_once("java/Java.inc");
$strMerchantId="00004074";
$astrFileName="/usr/jb_manju/00004074.key";
$astrClearData;

//Validation by ICICI
function validateEncryptedData($astrResponseData,$astrFileName,$strMerchantId)
{
	$fp = fopen ($astrFileName,"r");
	$strModulus = fgets($fp,1024);
	$strModulus=decryptMerchantKey(trim($strModulus),$strMerchantId);
	$strExponent =fgets($fp,1024);
	$strExponent =decryptMerchantKey(trim($strExponent),$strMerchantId);
	$oEncryptionLib =new Java('com.opus.epg.sfa.java.EPGMerchantEncryptionLib');
	$astrClearData=$oEncryptionLib->decryptDataWithPrivateKeyContents($astrResponseData,$strModulus,$strExponent);
	return $astrClearData;
}

//Validation by ICICI
function decryptMerchantKey($strData, $strMerchantId)
{
	$strMerchantId=$strMerchantId.$strMerchantId;
	$strDecryptData=decryptData($strData,$strMerchantId);
	return $strDecryptData;
}

//Validation by ICICI
function decryptData($strData,$strKey)
{
	$oEPGCryptLib= new Java('com.opus.epg.sfa.java.EPGCryptLib');
	$decryptData=$oEPGCryptLib->Decrypt($strKey,$strData);
	return $decryptData;
}

//Validation by ICICI and Relyon
if($_POST)
{
	if($_POST['DATA']==null)
	{
		print "null is the value";
		exit;
	}

	$astrResponseData=$_POST['DATA'];
	$astrClearData=validateEncryptedData($astrResponseData,$astrFileName,$strMerchantId);
	#print $astrClearData;
	parse_str($astrClearData, $output);
	$ResponseCode = java_values($output['RespCode']);
	$Message = java_values($output['Message']);
	$TxnID=java_values($output['TxnID']);
	$ePGTxnID=java_values($output['ePGTxnID']);
	$AuthIdCode=java_values($output['AuthIdCode']);
	$RRN = java_values($output['RRN']);
	$CVRespCode=java_values($output['CVRespCode']);
	$FDMSScore=java_values($output['FDMSScroe']);
	$FDMSResult=java_values($output['FDMSResult']);
	$Cookie=java_values($output['Cookie']);
	
	//Validation of Refresh or Back button
	if(isset($_COOKIE['relyoncctransaction']))
	{
		if($_COOKIE['relyoncctransaction'] == $TxnID)
		{
			echo("You have either hit on Refresh or Back. The page has been expired.");
			exit;
		}
	}
	
	//Set the cookie for Refresh or Back button validation
	setcookie(relyoncctransaction,$TxnID,time()+3600, "/", ".relyonsoft.com");
	
}
else
{
	echo("Invalid Entry");
	exit;
}

//Update the transactions table
$query = "update transactions set responsecode = '".$ResponseCode."', responsemessage = '".$Message."', pgtxnid = '".$ePGTxnID."', authidcode = '".$AuthIdCode."', rrn = '".$RRN."', cvrespcode = '".$CVRespCode."', fdmsscore = '".$FDMSScore."', fdmsresult = '".$FDMSResult."', cookievalue = '".$Cookie."' where id = '".$TxnID."'";
$result = runicicidbquery($query);

//Select the values from transation table
$query = "select * from transactions where id = '".$TxnID."'";
$result = runicicidbquery($query);
$fetchresult5 = mysqli_fetch_array($result);
$recordreferencestring = $fetchresult5['recordreference'];
$amount = $fetchresult5['amount'];

if($ResponseCode == 0) //Success
{
	//Update the Payment status 
	$query1 = "UPDATE dealer_online_purchase SET paymentdate = '".date('Y-m-d')."', paymenttime = '".date('H:i:s')."',paymenttypeselected = 'paymentmadenow',paymentmode = 'creditordebit' WHERE onlineinvoiceno = '".$recordreferencestring."'";
	$result = runmysqlquery($query1);
	
	//Check the dealer name 
	$query2 = "SELECT * from dealer_online_purchase  where onlineinvoiceno = '".$recordreferencestring."' ";
	$transaction = runmysqlqueryfetch($query2);
	
	$company = $transaction['businessname'];
	$contactperson = $transaction['contactperson'];
	$address = $transaction['address'];
	$place = $transaction['place'];
	$district = $transaction['district'];
	$pincode = $transaction['pincode'];
	$stdcode = $transaction['stdcode'];
	$phone = $transaction['phone'];
	$fax = $transaction['fax'];
	$cell = $transaction['cell'];
	$emailid = $transaction['emailid'];
	$purchasetype = $transaction['purchasetype'];
	$usagetype = $transaction['usagetype'];
	$custreference = $transaction['customerreference'];
	$paymenttype = $transaction['paymenttype'];
	$netamount = $transaction['netamount'];
	$servicetax = $transaction['servicetax'];
	$dealerid = $transaction['currentdealer'];
	$createdby = $transaction['createdby'];
	$onlineinvoiceslno = $transaction['onlineinvoiceno'];
	
	//Update invoicenumbers with payment status details
	$query2 = "update inv_invoicenumbers set paymenttypeselected = 'paymentmadenow', paymentmode = 'creditordebit' where slno =  '".$onlineinvoiceslno."'";
	$result2 = runmysqlquery($query2);
	
	$query0 = "select businessname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '".$dealerid."';";
	$fetch0 = runmysqlqueryfetch($query0);
	$dealername = $fetch0['businessname'];
	$dealerregion = $fetch0['region'];
	$dealeremailid = $fetch0['dealeremailid'];
	
	
	//Check the amount to be payed
	$query = "select sum(receiptamount) as receivedamount,netamount from inv_invoicenumbers left join inv_mas_receipt on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where inv_invoicenumbers.slno = '".$onlineinvoiceslno."' group by inv_invoicenumbers.slno";
	$resultfetch = runmysqlqueryfetch($query);
	$receivedamount = $resultfetch['receivedamount'];
	$totalamount = $resultfetch['netamount'];
	if($receivedamount == '')
		$balanceamount = $totalamount;
	else
		$balanceamount = $totalamount - $receivedamount;
	
	//Get the customer id and invoice no from invoicenumber table
	$query = "select * from inv_invoicenumbers where slno ='".$onlineinvoiceslno."';";
	$fetchresult = runmysqlqueryfetch($query);
	$customerid = $fetchresult['customerid'];
	$invoiceno = $fetchresult['invoiceno'];
	$loggedindealername = $fetchresult['createdby'];
	

	//Insert Receipt Details 
	$query = "INSERT INTO inv_mas_receipt(invoiceno,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) values('".$onlineinvoiceslno."','".$balanceamount."','creditordebit','','','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$createdby."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d').' '.datetimelocal('H:i:s')."','".$createdby."','".$_SERVER['REMOTE_ADDR']."','".$custreference."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','dealer_module','no');";
	$result = runmysqlquery($query);
	

	
	#########  Mailing Starts -----------------------------------
	//$emailid = 'rashmi.hk@relyonsoft.com';
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
	
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/paymentcomplete.htm");
	$textmsg = file_get_contents("../mailcontents/paymentcomplete.txt");
	$date = date('d-m-Y');
	$time = date('H:i:s');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##TIME##%^%".$time;
	$array[] = "##COMPANY##%^%".$company;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##AMOUNT##%^%".$balanceamount;
	$array[] = "##CUSTOMERID##%^%".$customerid;
	$array[] = "##INVOICENO##%^%".$invoiceno;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
	array('../images/relyon-logo.jpg','inline','1234567890'),array('../images/relyon-rupee-small.jpg','inline','1234567892')
	);
	
	
	//Mail to customer
	$toarray = $emailids;
	
	//Copy of email to Accounts / Vijay Kumar / Bigmails 
	$bccarray = array('Bigmail' => 'bigmail@relyonsoft.com', 'Accounts' => 'accounts@relyonsoft.com','Relyonimax' => 'relyonimax@gmail.com'); 
	//$bccarray = array('meghana' => 'meghana.b@relyonsoft.com'); 
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$attachedfilename = explode('.',$filebasename);
	$subject = "Payment made successfully by ".$company." (".$loggedindealername.")";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'accounts@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
	fileDelete('../filecreated/',$filebasename);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta  name="description" content="Products page of Relyon - House of eTDS, Payroll, salary and attendance management software" />
<meta content="Form100, VAT Offices,Service Tax Returns,Online Filing,Indian Taxation,Inventory,PF software,Computerized Accounting" name="keywords" />
<title>Relyon: Buy Online</title>
<link href="../style/main.css" rel="stylesheet" type="text/css">
<script language="javascript">
function returnhomepage()
{
	window.location = '../purchase/invoicing.php';
	return false;
}

</script>
</head>
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
                                                                            <td colspan="2" class="fontstyle" ><p align="left">You have been successfully paid for <img src="../images/relyon-rupee-small.jpg" width="8" height="10" /><font color="#000000">&nbsp;<? echo($balanceamount.'.00')?></font>. An email also have been sent to <font color="#FF0000"><? echo($emailid)?></font> with the confirmation.<br />
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
                                                                                            <strong>ICICI Transaction reference Number:</strong> <? echo($ePGTxnID); ?><br />
                                                                                            <strong>Authorization ID: </strong> <? echo($AuthIdCode) ?> <br />
                                                                                          </p></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
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
                                                                                      <strong>ICICI Transaction reference Number:</strong> <? echo($ePGTxnID); ?><br />
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
