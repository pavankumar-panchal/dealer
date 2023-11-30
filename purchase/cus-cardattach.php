<?php
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
$query = "select * from inv_mas_dealer where slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$relyonexecutive = $resultfetch['relyonexecutive'];

#Added on 06/03/2018

    $maindealers = $resultfetch['maindealers'];
    $dealerhead = $resultfetch['dealerhead'];

#Added on 06/03/2018 Ends

if($relyonexecutive == 'no')
{
		$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
		$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
		$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
		echo($grid);
}
else
{
	$query = "select * from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 	where inv_dealercard.dealerid = '".$userid."'; ";
	
	#Added on 06/03/2018
	if(!is_null($dealerhead) && $dealerhead != '') {
    	$query = "select * from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
    	where inv_dealercard.sub_dealer = '".$userid."'; ";	    
	}
	#Added on 06/03/2018 Ends
		
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
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/cusattachcards.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/cus-cardattach.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" valign="middle" class="active-leftnav">Customer Selection</td>
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
                          <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();">
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
                                            <input type="hidden" name="lastslno" id="lastslno"></td>
                                            <td width="63%" align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                          
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td width="53%" height="34px;"><strong>Select a PIN Number</strong></td>
                                                <td width="47%" height="34px;"><div id="scratchcradloading"></div></td>
                                              </tr>
                                              <tr>
                                                <td colspan="2"><div id="dispreregcardlist">
                                                                
                                                              <div align="left">
                                                                <input name="searchscratchnumber" type="text" class="swifttext" id="searchscratchnumber"  style="width:191px" onkeyup="reg_cardsearch(event);" autocomplete="off"/>
                                                                <select name="scratchcardlist" size="5" class="swiftselect" id="scratchcardlist" style="width:197px; height:200px" onclick="reg_selectcardfromlist();scratchdetailstoform(document.getElementById('scratchcardlist').value);"  >
                                                                </select>
                                                      </div>
                                                  </div></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          
                                          
                                      </table>
                                      
<p><input name="description" type="hidden" class="swifttext" id="description"  style="width:191px" /></p>
                                                                            
                                      </td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="currentdate" type="text" class="swifttext" id="currentdate" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?>"/></td>
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
                                                      </table>
                                            </div></td>
                                        </tr>
                                          
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>--> 
                                      </table></td>
                                    </tr> 
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="44%" align="right" valign="middle"><div align="center">
  <input name="attachcard" type="button" class= "swiftchoicebuttondisabledbig" id="attachcard" value="Attach PIN Number"  disabled="disabled" onClick="formsubmit('attachcard');" />
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
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Attach PIN Numbers (Not Registered)</div></td>
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
<script>
gettotalcustomercount();
gettotalcusattachcard();
</script>
<?php }} ?>