<?
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
$query = "select inv_mas_dealer.businessname as dealername,inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_state.statename,inv_mas_dealer.enablebilling as enablebilling  from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$relyonexecutive = $resultfetch['relyonexecutive'];
$statecode = $resultfetch['statecode'];
$enablebilling = $resultfetch['enablebilling'];
if($enablebilling == 'yes')
{
	if($relyonexecutive == 'no')
	{
			$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
			$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
			$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
			echo($grid);
	}
	else
	{
		$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') order by businessname;";
		$result = runmysqlquery($query); 
		if(mysqli_num_rows($result) == 0)
		{
			$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
			from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
			where inv_mas_district.statecode = '".$statecode."' order by businessname;";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
				$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
				$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">There are no Customers available in your account</font></div></strong></td></tr></table>';
				echo($grid);
			}
			else
			{
				$flag = 'true';
			}
		}
		else
		{
			$flag = 'true';
		}
	}
}
else
{
	$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
	$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
	$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
	echo($grid);
}
if($flag == 'true')
{
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/receipts.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="middle" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34"  id="customerselectionprocess" align="left"  style="padding:0">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onKeyUp="customersearch(event);"  autocomplete="off" style="width:200px"/>
                          <div id="detailloadcustomerlist">
                            <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();">
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong> </td>
                      <td width="55%" id="totalcount" align="left">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="30%" align="left" valign="middle" class="active-leftnav"> Receipt</td>
                            <td width="37%"><div align="right"></div></td>
                            <td width="33%"><div align="right"></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td width="66%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td width="34%" align="right" class="header-line" style="padding-right:7px">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                    <tr>
                                      <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                          <tr bgcolor="#f7faff">
                                            <td width="18%" align="left" valign="top">Customer Name:<input type="hidden" name="receiptno" id="receiptno" /><input type="hidden" name="cusslno" id="cusslno" /></td>
                                            <td width="82%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" height="71" border="0" cellpadding="3" cellspacing="0">
                                          <tr bgcolor="#f7faff">
                                            <td width="36%" align="left" valign="top">Invoice No:</td>
                                            <td width="64%" align="left" valign="top" bgcolor="#f7faff" ><div id="smsaccountlist">
                                                <select name="invoivcelist" class="swiftselect-mandatory" id="invoivcelist" style="width:200px;" >
                                                  <option value="">Select a Invoice</option>
                                                </select>
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td valign="top" align="left">Receipt  Date:</td>
                                            <td valign="top"><input name="receiptdate" type="text" class="swifttext" id="DPC_receiptdate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly" disabled="disabled"/>
                                              </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#f7faff"> Receipt Amount:
                                              <div align="right"></div></td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><input name="receiptamount" type="text" class="swifttext" id="receiptamount" size="30" maxlength="12" autocomplete="off" />
                                              <input name="lastslno" id="lastslno" type="hidden" />
                                              <input type="hidden" name="onlineslno" id="onlineslno" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Payment Mode:</td>
                                            <td align="left" valign="top"  bgcolor="#f7faff"><label for="cash"> </label>
                                              <label for="chequeordd">
                                              <input type="radio" name="paymentmode" id="chequeordd" checked="checked"  value="chequeordd"  onclick="hideorshowremarksdiv();"/>
                                              Cheque / DD </label>
                                              <br />
                                              <label for="onlinetransfer">
                                              <input type="radio" name="paymentmode" id="onlinetransfer" value="onlinetransfer" onclick="hideorshowremarksdiv();"/>
                                              Online Transfer (NEFT / RTGS) </label>
                                              <br />
                                              <label for="creditordebit">
                                              <input type="radio" name="paymentmode" id="creditordebit" value="creditordebit"  onclick="hideorshowremarksdiv();"/>
                                              Credit / Debit Card </label>
                                              <label for="cash"><br />
                                              <input type="radio" name="paymentmode" id="cash" value="cash"  onclick="hideorshowremarksdiv();"/>
                                              Cash<br />
                                              </label>                                            </td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td  align="left" valign="top" bgcolor="#f7faff">Private Note:<br/></td>
                                            <td align="left"><div align="left">
                                                <input name="privatenote" type="text" class="swifttext" id="privatenote" size="30" autocomplete="off"/>
                                              </div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td  align="left" valign="top" bgcolor="#f7faff">Entered Date:</td>
                                            <td align="left" id="entereddate">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td  align="left" valign="top" bgcolor="#f7faff">Entered By:</td>
                                            <td align="left" id="enteredby">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top"><font color="#FF0000">STATUS:</font></td>
                                            <td align="left" valign="top" id="displayreceiptstatus">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Remarks:</td>
                                            <td  align="left" valign="top" id="displayreceiptremarks">Not Available</td>
                                          </tr>
                                          
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2"  align="left" valign="top" bgcolor="#f7faff">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2"  align="left" valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="31%"><div id="viewthereceipt" style="display:none"><a onclick="viewreceipt();" class="resendtext" style = "cursor:pointer">View Receipt</a></div></td>
                                                  <td width="69%"><div id="sendthereceipt" style="display:none"><a onclick="sendreceipt('resend');" class="resendtext" style = "cursor:pointer"> Send Receipt</a></div><div id="sendcancelledreceipt" style="display:none"><a onclick="sendreceipt('cancelled');" class="resendtext" style = "cursor:pointer"> Send Cancelled Receipt </a></div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellpadding="4" cellspacing="0">
                                          <tr>
                                            <td width="100%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFF2F2" style="border:1px solid #FFDFDF">
                                                <tr>
                                                  <td colspan="3"><strong>Invoicing Summary</strong></td>
                                                </tr>
                                                <tr>
                                                  <td width="19%">Total:</td>
                                                  <td width="27%" ><div style="text-align:right" id="invoiceamount">Not Available</div></td>
                                                  <td width="54%">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td>Received:</td>
                                                  <td ><div align="right" style="text-align:right" id="amountreceived" >Not Available</div></td>
                                                  <td >&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td>Balance:</td>
                                                  <td ><div  style="text-align:right" id="balanceamount">Not Available</div></td>
                                                  <td><div id="viewtheinvoice" style="display:none"><a onclick="viewinvoice();" class="resendtext" style = "cursor:pointer"> View Invoice</a></div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFF2F2" style="border:1px solid #FFDFDF" height="180px;">
                                                <tr>
                                                  <td width="100%" valign="top" height="20px;"><strong>Payment Details</strong></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top"><div id="paymentdetailsdiv" style=" display:none">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3"  bgcolor="#FFF9F9" style="border:1px solid #FFE8E8" >
                                                        <tr>
                                                          <td width="31%" valign="top" >Remarks:</td>
                                                          <td width="68%" ><div align="left" >
                                                              <textarea name="remarks" cols="27" rows="4" class="swifttextarea" id="remarks" style="resize:none"></textarea>
                                                            </div></td>
                                                          <td width="1%">&nbsp;</td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top"><div id="paymentremarksdiv" style="display:block;">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFF9F9" style="border:1px solid #FFE8E8">
                                                        <tr>
                                                          <td width="44%" valign="top">Cheque Date: </td>
                                                          <td width="56%" valign="top"><input name="chequedate" type="text" class="swifttext" id="DPC_chequedate" size="30" autocomplete="off" value=""  readonly="readonly"/></td>
                                                        </tr>
                                                        <tr >
                                                          <td valign="top">Cheque No:</td>
                                                          <td valign="top"><input name="chequeno" type="text" class="swifttext" id="chequeno" size="30" maxlength="12" autocomplete="off" /></td>
                                                        </tr>
                                                        <tr >
                                                          <td valign="top">Drawn On:</td>
                                                          <td valign="top"><input name="drawnon" type="text" class="swifttext" id="drawnon"  autocomplete="off"  style="width:192px;"/></td>
                                                        </tr>
                                                        <tr >
                                                          <td valign="top">Deposit Date:</td>
                                                          <td valign="top"><input name="depositdate" type="text" class="swifttext" id="DPC_depositdate" size="30" autocomplete="off" value="" readonly="readonly"/></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div>
                                              <div id="productselectionprocess"></div></td>
                                            <td width="44%" align="right" valign="middle">&nbsp;&nbsp;
                                              <div align="center">
                                                <input name="new" type="button" class="swiftchoicebutton" id="new" value="New" onclick="newcreditentry();document.getElementById('form-error').innerHTML ='';"/>
                                                &nbsp;&nbsp;
                                                <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit();" />
                                                &nbsp;&nbsp;</div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="131" align="left"  style="padding:0"><div id="tabdescription">&nbsp; Receipt Details</div></td>
                                  <td width="427"  style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="35" align="left"  style="padding:0">&nbsp;</td>
                                  <td width="139" align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;"> </div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
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
</table>
<script>
gettotalcustomercount();
</script>
<?
 } ?>
