<?php 
$userid = imaxgetcookie('dealeruserid');
include('../inc/eventloginsert.php');

?>

<link href="../style/style.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/dealerinteraction.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid; text-align:left" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="middle" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0">&nbsp;</td>
                        <td width="29%" id="customerselectionprocess" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
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
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount" align="left"></td>
              </tr>
</table>
</td>
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
                            <td width="27%" align="left" valign="middle" class="active-leftnav">Customer Interaction Details</td>
                            <td width="40%"><div align="right"></div></td>
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
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="54%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" height="73" border="0" cellpadding="3" cellspacing="0">
                                          <tr bgcolor="#f7faff">
                                            <td width="37%" align="left" valign="top">Business Name:</td>
                                            <td width="63%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                             <?php
	  $query = "Select businessname,dealerusername from inv_mas_dealer where slno = '".$userid."'";
	  $fetch = runmysqlqueryfetch($query);
	  $businessname =strtoupper($fetch['businessname']); 
	  $dealerusername = strtoupper($fetch['dealerusername']);
	   ?>
       <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top">Interaction Category:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><select name="interaction" class="swiftselect-mandatory" id="interaction" style="width:200px">
                                                <?php 
											include('../inc/interactiontype.php');
											?>
                                              </select></td>
                                          </tr>
                                          
                                          
                                          
                                      </table></td>
                                      <td width="46%" valign="top"><table width="99%" height="75" border="0" cellpadding="3" cellspacing="0">
                                      <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top">Entered By:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="displayenteredby"> <?php echo( $businessname); 
											?></td>
                                          </tr><tr>
                                            <td align="left" valign="top">Entered Through:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="enteredthrough"></td>
                                          </tr>
                                          
                                          <tr bgcolor="#f7faff">
                                            <td width="35%" align="left" valign="top" bgcolor="#EDF4FF">Entered Date:</td>
                                            <td width="65%" align="left" valign="top" bgcolor="#EDF4FF" id="interactiondate"><?php echo(datetimelocal('d-m-Y')." "."(".datetimelocal('H:i')).")"; ?></td>
                                          </tr>
                                          
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                      </table></td>
                                    </tr>
                                    <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr bgcolor="#f7faff">
                                            <td width="18%" align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                            <td width="82%" align="left" valign="top" bgcolor="#f7faff" ><textarea name="remarks" cols="75" class="swifttextarea" id="remarks" rows="1"  ></textarea>
                                              <input type="hidden" name="lastslno" id="lastslno" value=" " />
                                              <input type="hidden" name="cusinteractionslno" id="cusinteractionslno" value=" "/>
                                            <input type="hidden" name="hiddendate" id="hiddendate" /></td>
                                    </table></td></tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                      </tr>
                                          <tr>
                                            <td width="71%" align="left" valign="middle" height="35"><div id="form-error"></div><div id="productselectionprocess"></div></td>
                                            <td width="29%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry(); document.getElementById('form-error').innerHTML = '';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                                              &nbsp;
                                            </td>
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
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Customer Interactions</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="customerlink" align="center"></div><div id="tabgroupgridc1link" ></div></td>
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