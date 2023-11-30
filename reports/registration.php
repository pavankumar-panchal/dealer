<?php
include('../inc/eventloginsert.php');
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/registration.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/lookout.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/dealerselectjs.php?dummy=<?php echo (rand());?>" ></script>

<?php $userid = imaxgetcookie('dealeruserid');?>
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
                            <td width="27%" class="active-leftnav">Registration Details</td>
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
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td valign="top">From Date:</td>
                                            <td valign="top" bgcolor="#f7faff"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" />                                              <input type="hidden" name="flag" id="flag" value="true" />
                                            <br /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF">To Date:</td>
                                            <td valign="top" bgcolor="#EDF4FF"><input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" />                                           </td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td colspan="2" valign="top" bgcolor="#F7FAFF"><fieldset style="border:1px solid #666666; padding:2px;">
    <legend>Customer Selection</legend>
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td><label>
      <input name="customerselection" type="radio" id="customerselection0" value="allcustomer" checked="checked" onclick="enablecustomersearch();" /> All Customer
    </label><label>
      </label></td>
    </tr>
</table>

                                            </fieldset></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" bgcolor="#EDF4FF"><fieldset style="border:1px solid #666666; padding:2px;">
                                            <legend>Geography</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100">
                                              <tr>
                                                <td valign="top"><label>
                                                    <input type="radio" name="geography" id="geography1" value="all"  checked="checked" onclick="enablegeography();" />
                                                      All </label><label>
                                                  <input name="geography" type="radio" id="geography0" value="region" onclick="enablegeography();" />
                                                  Region </label>
                                                    <label>
                                                    <input type="radio" name="geography" id="geography1" value="state" onclick="enablegeography();" />
                                                      State </label><label>
                                                    <input type="radio" name="geography" id="geography1" value="district" onclick="enablegeography();" />
                                                      District </label></td>
                                              </tr>
                                              <tr>
                                                <td valign="top"><div id="regiondiv" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td width="29%">Select A Region:&nbsp;&nbsp;</td>
                                                      <td width="71%"><select name="region" class="swiftselect-mandatory" id="region" disabled="disabled">
                                                        <option value="">Select A Region</option>
                                                        <?php include('../inc/region.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                  </table></div>
                                                <div id="statediv" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td width="29%">Select A State: </td>
                                                        <td width="71%"><select name="state" class="swiftselect-mandatory" id="state" onchange="getdistrict('districtcodedisplay',this.value);" disabled="disabled">
                                                          <option value="">Select A State</option>
                                                          <?php include('../inc/state.php'); ?>
                                                        </select></td>
                                                      </tr>
                                                    </table>
                                                    <div id="districtdiv" style="display:none;">
                                                      
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td width="29%">Select A District:</td>
                                                          <td width="71%" id="districtcodedisplay"><select name="district" class="swiftselect-mandatory" id="district" disabled="disabled">
                                                            <option value="">Select A State First</option>
                                                          </select></td>
                                                        </tr>
                                                      </table>
                                                    </div>
                                                </div></td>
                                              </tr>
                                            </table>
                                            </fieldset></td>
                                          </tr>
                                         

                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td  valign="top"><fieldset style="border:1px solid #666666; padding:2px;">
                                                  <legend>Order By</legend>
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td><label>
                                                        <input name="groupon" type="radio" id="groupon0" value="product" checked="checked" />
                                                        Product </label>
                                                          
                                                          <label>
                                                          <input type="radio" name="groupon" id="groupon1" value="generatedby" />
                                                            Generated By </label>
                                                        
                                                          <label>
                                                          <input type="radio" name="groupon" id="groupon2" value="dealer" />
                                                            Dealer</label>
                                                       
                                                          <label><input type="radio" name="groupon" id="groupon3" value="date" />
                                                          Date<br />
                                                        </label></td>
                                                    </tr>
                                                  </table>
                                                </fieldset>                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                    
                                                  </table></td>
                                                </tr>
                                            </table></td><td>&nbsp;</td>
                                          </tr>
                                          <tr><td colspan="2"><fieldset style="border:1px solid #666666; padding:2px;">
                                                  <legend> Options</legend>
                                              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                          <tr bgcolor="#f7faff">
                                            <td valign="top">Generated By:</td>
                                            <td valign="top">
                                              <select name="generatedby" class="swiftselect" id="generatedby"  style=" width:225px" >
                                                <option value="">ALL</option>
                                                <?php include('../inc/selectusers.php'); ?>
                                              </select>                                            </td>
                                          <!-- <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF">Product:</td>
                                            <td valign="top" bgcolor="#EDF4FF">
                                              <select name="productcode" class="swiftselect" id="productcode"  style=" width:225px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/firstproduct.php'); ?>
                                              </select>                                         </td>
                                          </tr> -->   
                                       
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#F7FAFF">Usage Type:</td>
                                            <td valign="top" bgcolor="#F7FAFF"><select name="usagetype" class="swiftselect" id="usagetype"   style=" width:225px">
                                                                <option value="" selected="selected">ALL</option>
                                                                <option value="singleuser">Single User</option>
                                                                <option value="multiuser">Multi User</option>
                                                                 <option value="additionallicense">Additonal License</option>
                                                              </select></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF">Purchase Type:</td>
                                            <td valign="top" bgcolor="#EDF4FF"><select name="purchasetype" class="swiftselect" id="purchasetype"    style=" width:225px">
                                                                <option value="" selected="selected">ALL</option>
                                                                <option value="new">New</option>
                                                                <option value="updation">Updation</option>
                                                              </select></td>
                                          </tr>
                                         <tr>
    <td bgcolor="#F7FAFF">PIN Number:</td>
    <td bgcolor="#F7FAFF"><select name="card" class="swiftselect" id="card"  style=" width:225px">
                                                <option value="" selected="selected">ALL</option>
                                                <option value="withcard">With Card</option>
                                                 <option value="withoutcard">Without Card</option>
                                              </select></td>
  </tr>
  <tr>
    <td bgcolor="#EDF4FF">Re-registration</td>
    <td bgcolor="#EDF4FF"><select name="reregistration" class="swiftselect" id="reregistration"  style=" width:225px" >
                                                <option value="" selected="selected">ALL</option>
                                                <option value="yes">Yes</option>
                                                 <option value="no">No</option>
                                              </select></td>
  </tr>
  <tr >
                                            <td valign="top" bgcolor="#F7FAFF">Dealer:</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="displayalldealer">&nbsp;                                                                                          </td>
                                          </tr>

                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF">Bill Number:</td>
                                            <td valign="top" bgcolor="#EDF4FF">
                                              
                                             <input name="billnumberfrom" type="text" class="swifttext" id="billnumberfrom" size="15"  autocomplete="off"/>
                                             &nbsp;
                                             To&nbsp;
                                             <input name="billnumberto" type="text" class="swifttext" id="billnumberto" size="15"  autocomplete="off"/></td>
                                          </tr>
</table></fieldset>
</td></tr><tr><td style="padding-left:5px; padding-right:5px"><fieldset style="border:1px solid #666666; padding:2px;">
                                                  <legend> Products</legend><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr bgcolor="#f7faff">
                                          <td colspan="4" valign="top" style="padding:0"></td>
                                        </tr>
                                        <tr bgcolor="#f7faff">
                                          <td colspan="4" valign="top" bgcolor="#f7faff" align="left"><div style="height:160px; overflow:scroll">
                                            <?php include('../inc/product-report.php'); ?>
                                          </div></td>
                                        </tr>
                                        <tr bgcolor="#EDF4FF">
                                          <td width="12%" align="left">Select: </td>
                                          <td width="32%" align="left"><strong>
                                            <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                              <option value="ALL" >ALL</option>
                                              <option value="NONE" selected="selected">NONE</option>
                                               <?php include('../inc/productgroup.php') ?>
                                            </select>
                                          </strong></td>
                                          <td width="56%" align="left"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                          <input type="hidden" name="groupvalue" id="groupvalue"  />
                                          </tr>
                                      </table>
                                      </fieldset></td></tr>
 

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
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View & Excel" onclick="formsubmit('view');" />
                                              <!-- &nbsp;
                                            <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit('toexcel');"/> --></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                  </table>
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