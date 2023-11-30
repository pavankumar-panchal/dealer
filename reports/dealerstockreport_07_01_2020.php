<?php
include('../inc/eventloginsert.php');
 $table = '<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="200px"><tr><td>&nbsp;</td></tr> </table>';
 ?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/dealerreport.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="text-align:left">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" class="active-leftnav">Report - Dealer Stock Details</td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                <?php
								$query ="select * from inv_dealercard left join inv_mas_scratchcard on  inv_dealercard.cardid = inv_mas_scratchcard.cardid where dealerid = '".$userid."' AND inv_mas_scratchcard.blocked = 'no';";
								$result = runmysqlquery($query);
								if(mysqli_num_rows($result) > 0)
								{
								?>
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td colspan="2"><div align="left"><strong>Attached Date</strong></div></td>
                                      </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top"><div align="left">From Date:</div></td>
                                            <td valign="top" bgcolor="#f7faff"><div align="left">
                                              <input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_attachfromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly"/>                                              
                                              <input type="hidden" name="flag" id="flag" value="true" />
                                              <br />
                                            </div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">To Date:</div></td>
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">
                                              <input name="todate" type="text" class="swifttext-mandatory" id="DPC_attachtodate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" />                                           
                                            </div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#f7faff"><div align="left">Product:</div></td>
                                            <td valign="top" bgcolor="#f7faff"><div align="left">
                                              <select name="productcode" class="swiftselect" id="productcode" style="width:140px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/firstproduct.php'); ?>
                                              </select>
                                            </div></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">Scheme:</div></td>
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">
                                              <select name="scheme" class="swiftselect" id="scheme" style="width:140px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/scheme.php'); ?>
                                              </select>
                                            </div></td>
                                          </tr>
                                       
                                         

                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"></td>
                                          </tr>
                                               <tr bgcolor="#f7faff">
                                            <td width="28%" valign="top" bgcolor="#EDF4FF"><div align="left">Usage Type:</div></td>
                                            <td width="72%" valign="top" bgcolor="#EDF4FF"><div align="left">
                                              <select name="usagetype" class="swiftselect" id="usagetype"   style="width:140px">
                                                <option value="" selected="selected">ALL</option>
                                                <option value="singleuser">Single User</option>
                                                <option value="multiuser">Multi User</option>
                                                <option value="additionallicense">Additonal License</option>
                                              </select>
                                            </div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">Purchse Type:</div></td>
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">
                                              <select name="purchasetype" class="swiftselect" id="purchasetype"    style="width:140px">
                                                <option value="" selected="selected">ALL</option>
                                                <option value="new">New</option>
                                                <option value="updation">Updation</option>
                                              </select>
                                            </div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">Free</div></td>
                                            <td valign="top" bgcolor="#EDF4FF"><div align="left">
                                              <select name="free" class="swiftselect" id="free"   style="width:140px">
                                                <option value="" selected="selected">ALL</option>
                                                <option value="yes" >Yes</option>
                                                <option value="no">No</option>
                                              </select>
                                            </div></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td valign="top" bgcolor="#F7FAFF"><div align="left">Registred:</div></td>
                                            <td valign="top" bgcolor="#F7FAFF"><div align="left">
                                              <select name="registered" class="swiftselect" id="registered"   style="width:140px">
                                                <option value="" selected="selected">ALL</option>
                                                <option value="yes" >Yes</option>
                                                <option value="no">No</option>
                                              </select>
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
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="formsubmit('view');" />
                                              &nbsp;
                                            <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit('toexcel');"/></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                  </table><?php } else { echo('<strong>No stock information available</strong>'); echo($table); } ?> 
                                </form>
                              </div></td>
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