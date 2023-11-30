<?
## Created By Sarasati  ##
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
$query = "select * from inv_mas_dealer where slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$maindealers = $resultfetch['maindealers'];
$relyonexecutive = $resultfetch['relyonexecutive'];
if($maindealers == 'no')
{
		$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
		$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
		$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
		echo($grid);
}
else
{
	$query = "select * from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
	where inv_dealercard.dealerid = '".$userid."'; ";
	$result = runmysqlquery($query); 
	if(mysqli_num_rows($result) == 0)
	{
		$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
		$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
		$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">There are no Active PIN numbers available in your account</font></div></strong></td></tr>';
		echo($grid);
	}
	else
	{

?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<link href="../style/fSelect.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/dealerpincardallot.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/dealerpinallot.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<? echo (rand());?>"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script language="javascript" src="../functions/fSelect.js?dummy=<? echo (rand());?>"></script>

<div id="demolink" style="background: red;color: #fff;padding: 30px;font-size: 21px;text-align: center;">DEMO TEST LINK</div>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" valign="middle" class="active-leftnav">Dealer Selection</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" id="customerselectionprocess" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <div id="detailloadcustomerlist">
                          <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px"  onchange="selectfromlist();">
                                                      </select> 
                          </div>                       </td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount" align="left">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
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
                          <td width="27%" align="left" class="active-leftnav">Attach PIN Serial Numbers</td>
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
                            <td align="left" class="header-line" style="padding:0 ;">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td width="37%" align="left" valign="top" bgcolor="#EDF4FF">Business Name:
                                            <input type="hidden" name="lastslno" id="lastslno">
                                            <input type="hidden" name="purcheck" id="purcheck">
                                            <input type="hidden" name="licensepurchase" id="licensepurchase">
                                            <input type="hidden" name="yearcount" id="yearcount">
                                            <input type="hidden" name="lastyearusagecheck" id="lastyearusagecheck">
                                            </td>
                                            <!--<td width="63%" align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername">&nbsp;</td>--><td width="63%" align="left" valign="top" bgcolor="#EDF4FF">&nbsp;</td>
                                          </tr>
                        <tr bgcolor="#EDF4FF">
<td width="63%" align="left" colspan="2" valign="top" bgcolor="#EDF4FF" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                        
<!---------------- Added to Show Filters --------------------->


            <?php if($maindealers == 'yes') { ?>
                                           <tr>
                                                <td width="43%" height="34px;"><strong>Select Product</strong></td>
                                                <td width="57%" height="34px;">
                                                	<select name="selectedproduct" class="swiftselect" id="selectedproduct" style="width:180px;" onChange="selectedproductdealer();">
                                                        <option value="">ALL</option>
                                                        <? include('../inc/product-dealers.php');?>
                                                    </select>
                                				</td>
                                              </tr>
                                              
                                              <tr>
                                                <td width="43%" height="34px;"><strong>Usage Type</strong></td>
                                                <td width="57%" height="34px;">
                                                	 <select name="usagetype" class="swiftselect" id="usagetype" style="width:180px;" onChange="selectedusagetype();">
                                                  <option value="">Make A Selection</option>
                                                  <option value="S">Single User</option>
                                                  <option value="M">Multi User</option>
                                                </select>
                                				</td>
                                              </tr>
                                              
                                              <tr>
                                                <td width="43%" height="34px;"><strong>Purchase Type</strong></td>
                                                <td width="57%" height="34px;">
                                                <select name="purchasetype" class="swiftselect" id="purchasetype" style="width:180px;" onChange="selectedpurchasetype();">
                                                  <option value="">Make A Selection</option>
                                                  <option value="N">New</option>
                                                  <option value="U">Updation</option>
                                                </select>
                                				</td>
                                              </tr>
                                              
                                             <!-- <tr>
                                                <td width="43%" height="34px;"><strong>Quantity</strong></td>
                                                <td width="57%" height="34px;">
                                                 <select name="quantity" class="swiftselect-mandatory" id="quantity" style="width:180px;" onchange="getamount(<? echo($userid); ?>);">
                                                  <option value="">Make A Selection</option>
                                                  <option value="1">1</option>
                                                  <option value="2">2</option>
                                                  <option value="3">3</option>
                                                  <option value="4">4</option>
                                                  <option value="5">5</option>
                                                  <option value="6">6</option>
                                                  <option value="7">7</option>
                                                  <option value="8">8</option>
                                                  <option value="9">9</option>
                                                  <option value="10">10</option>
                                                </select> <input type="hidden" name="lastbillno" id="lastbillno" />
                                                <input type="hidden" name="productlastslno" id="productlastslno" />
                                				</td>
                                              </tr>-->
            <?php  } ?>

                                          
<!-------------- ./Ends Filters ---------------------------------->

                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td width="53%" height="34px;"><strong>Select a PIN Number </strong></td>
                                                <td width="47%" height="34px;"><div id="scratchcradloading"></div></td>
                                              </tr>
                                              <tr>
                                                <td colspan="2"><div id="dispreregcardlist">
                                                                
                                                              <div align="left">
                                                                <!--<input name="searchscratchnumber" type="text" class="swifttext" id="searchscratchnumber" style="width:249px" autocomplete="off" onkeyup="reg_cardsearch(event);"/><!-- onkeyup="reg_cardsearch(event);" -->
 <select multiple="multiple" name="scratchcardlist" size="5" class="swiftselect manualselect" id="scratchcardlist" style="width:254px; height:200px;display:none" onclick="reg_selectcardfromlist();
 scratchdetailstoform(document.getElementById('scratchcardlist').value); getcustomerdetails(document.getElementById('scratchcardlist').value);">
                                                                <!--onclick="reg_selectcardfromlist();scratchdetailstoform(document.getElementById('scratchcardlist').value);"-->
                                                                </select>
                                                      </div>
                                                  </div></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          
                                          
                                      </table>
            <table width="100%" style="margin-top: 240px;">
              <tr>
                <td width="20%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcountofcard" align="left"></td>
              </tr>
                                      </table><p><input name="description" type="hidden" class="swifttext" id="description"  style="width:191px" /><em style="color:#D00"><strong>NOTE:</strong> Once PIN number attached cannot be retrieve</em></p></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="currentdate" type="text" class="swifttext" id="currentdate" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off" value="<? echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?>"/></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Remarks:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks" ></textarea></td>
                                          </tr>
                                      <tr bgcolor="#f7faff">
                                        <td colspan="2" align="left" valign="top" bgcolor="#f7faff" height="275px;"><div id="detailsonscratch" style="display:none;">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3" class="diaplaytableborder">
                                                      <tr>
                                                        <td colspan="3" bgcolor="#EDF4FF" headers="40px"><strong>Card Info</strong></td>
                                                      </tr>
                                                        <tr>
                                                          <td width="38%" valign="top">PIN Serial Number</td>
                                                          <td width="4%" valign="top">:</td>
                                                          <td width="58%" valign="top" id="cardnumberdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">PIN Number</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="scratchnodisplay">&nbsp;</td>
                                                        </tr>
                                                         <tr>
                                                          <td valign="top">Product Name</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="productdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Purchase Type</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="purchasetypedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Usage Type</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="usagetypedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached To (Dealer)</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="attachedtodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached To (customer)</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="registeredtodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached Date</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="attachdatedisplay">&nbsp;</td>
                                                        </tr>
                                                                                                             <tr>
                                                          <td valign="top">PIN Status</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="cardstatusdisplay">&nbsp;</td>
                                                        </tr>
                                                         <tr>
                                                           <td valign="top">Customer card Attached Date:</td>
                                                           <td valign="top">:</td>
                                                           <td valign="top" id="cardattacheddate">&nbsp;</td>
                                                         </tr>
                                                         <tr>
                                                           <td valign="top">Sub Dealer Name:</td>
                                                           <td valign="top">:</td>
                                                           <td valign="top" id="subdealername">&nbsp;</td>
                                                         </tr>
                                                      </table>
                                            </div></td>
                                        </tr>
                                          
                                      </table></td>
                                    </tr> 
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="44%" align="right" valign="middle"><div align="center">
  <input name="attachcard" type="button" class= "swiftchoicebuttondisabledbig" id="attachcard" value="Attach PIN Number"  disabled="disabled" onClick="formsubmit('attachcard');" /> <!--  onClick="formsubmit('attachcard');" -->
  &nbsp;&nbsp;                                             </div></td>
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
                                <tr>
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Alloted PIN Numbers</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left"></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="custresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>                                   </td>
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
<div id="demolink" style="background: red;color: #fff;padding: 30px;font-size: 21px;text-align: center;">DEMO TEST LINK</div>
<script>
gettotalcustomercount();
gettotalcusattachcard();
</script>
<? }} ?>