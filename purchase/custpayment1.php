<?
include('../inc/eventloginsert.php');
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/custpayment.js?dummy=<? echo(rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<? echo(rand());?>"></script>

<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="text-align:left">
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
                        <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        <td width="29%" id="customerselectionprocess" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <div id="detailloadcustomerlist">
                          <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();">
                          </select> 
                          </div>                       </td>
                      </tr>
                    </table>
                  </form></td>
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
                            <td width="29%" align="left" valign="middle" class="active-leftnav">Customer Payment Details</td>
                            <td width="40%" align="top"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="31%" valign="top"><div align="left" style="padding:2px">
                                <div align="right">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);"  maxlength="20"  autocomplete="off" style="width:175px"/>
                                  <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div>
                              </div></td>
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
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="51%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td width="33%" align="left" valign="top">Business Name:</td>
                                            <td width="67%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername">&nbsp;</td><input type="hidden" name="customerid" id="customerid" />
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Product Desc:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="productdecription"  class="swifttextarea" id="productdecription" size="30"  autocomplete="off" style="background:#F0F0F0" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Bill Reference:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><input name="billref" type="text" class="swiftselect-readonly" id="billref" size="30"  autocomplete="off" value="" style="background:#F0F0F0"/></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top">Payment Amount:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="paymentamt" type="text" class="swiftselect-mandatory" id="paymentamt" size="30"   autocomplete="off" value="" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><textarea name="remarks" cols="27" class="swifttextarea" id="remarks" ></textarea></td>
                                          </tr> 
                                          
                                      </table></td>
                                      <td width="49%" valign="top"><table width="100%" height="143" border="0" cellpadding="3" cellspacing="0">
                                      <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Enter Date:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="createddate"><? echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?>
                                            </td>
                                          </tr>
                                             <?
	  $query = "Select businessname,dealerusername from inv_mas_dealer where slno = '".$userid."'";
	  $fetch = runmysqlqueryfetch($query);
	  $businessname =strtoupper($fetch['businessname']); 
	   ?>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Entered By:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="displayentereddate"><? echo( $businessname)?></td>
                                          </tr>
                                      <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" >Payment Status:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="paymentstatus" height="25px"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="32%" align="left" valign="top" bgcolor="#EDF4FF">Paid Date:</td>
                                            <td width="68%" align="left" valign="top" bgcolor="#EDF4FF" id="paymentdate" >
                                            </td>
                                          </tr><input type="hidden" name="lastslno" id="lastslno">
                                            <input type="hidden" name="cusslno" id="cusslno" />
                                           <tr  bgcolor="#f7faff">
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                      </tr>
                                       <tr  bgcolor="#f7faff">
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                      </tr>
                                         
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                      </table></td>
                                    </tr>
                                    
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                     <tr> <td colspan="2" width="54%" align="left" valign="middle" height="35"><div id="form-error"></div></td> 
                                           </tr>
                                          <tr>
                                        
                                            <td  width="23%" align="left" valign="left" >
                                              <div id="resendreq" class="resendtext" style="display:none" ><a  onclick="resendrequestemail();" >Resend Payment Request</a></div>
                                             
                                           </td> 
                                            <td width="46%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry(); document.getElementById('form-error').innerHTML = '';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit();" />
&nbsp;</td>
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
                                  <td width="31%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Customer Payment</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" ></div></td>
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
refreshcustomerarray();
</script>
