<?php
$fybegin = (date('m') >= '04')?(date('Y').'-04-01'):((date('Y')-1).'-04-01');
$userid = imaxgetcookie('dealeruserid');
$query = "select * from inv_mas_dealer where slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$branch = $resultfetch['branch'];
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/implementationstatus.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td  class="active-leftnav1">Report - Implementation Detailed</td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report </td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  
                                    <tr><td colspan="2" height="10px" ></td></tr>         
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr>
                                          <td align="left" bgcolor="#EDF4FF">From Date:</td>
                                          <td align="left">
                                          <input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(changedateformat($fybegin)); ?>" /></td>
                                        </tr>
                                        <tr>
                                          <td align="left" bgcolor="#f7faff">To Date:</td>
                                          <td align="left" bgcolor="#f7faff"><input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  /></td>
                                        </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Sales Person:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="salesperson" class="swiftselect-mandatory" id="salesperson" style=" width:225px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/implementerdealer.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Implementer:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="implementer" class="swiftselect-mandatory" id="implementer" style=" width:225px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/implementer.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#f7faff" align="left">Branch head Approval:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="branchapproval" class="swiftselect-mandatory" id="branchapproval" style=" width:120px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="pending">PENDING</option>
                                                <option value="approved">APPROVED</option>
                                                <option value="rejected">REJECTED</option>
                                            </select>                                            </td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Coordinator Approval :</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="coordinatorapproval" class="swiftselect-mandatory" id="coordinatorapproval" style=" width:120px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="pending">PENDING</option>
                                                <option value="approved">APPROVED</option>
                                                <option value="rejected">REJECTED</option>
                                               
                                             </select>                                            </td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Customization:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="customization" class="swiftselect-mandatory" id="customization" style=" width:80px">
                                                <option value="" selected="selected">ALL</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                               
                                              </select>                                            </td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#f7faff" align="left">Advance Collected:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="advcollected" class="swiftselect-mandatory" id="advcollected" style=" width:80px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                             </select>                                            </td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Attendance Integration:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="attendance" class="swiftselect-mandatory" id="attendance" style=" width:80px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                               
                                              </select>                                            </td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#f7faff" align="left">Commission Payable:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="commission" class="swiftselect-mandatory" id="commission" style=" width:80px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                             </select>                                            </td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Web Implementation :</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="webimpl" class="swiftselect-mandatory" id="webimpl" style=" width:80px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                               
                                              </select>                                            </td>
                                          </tr>
                                           
                                           <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#f7faff" align="left">ICC Collected:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="icccolllected" class="swiftselect-mandatory" id="icccolllected" style=" width:80px">
                                                <option value=""  selected="selected">ALL</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                             </select>                                            </td>
                                          </tr>
                                        </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" bgcolor="#EDF4FF" align="left"><strong>Products </strong></td>
                                          </tr >
                                          <tr bgcolor="#f7faff" >
                                            <td colspan="2" valign="top" bgcolor="#f7faff" align="left"><div style="height:110px; overflow:scroll">
                                                <?php include('../inc/imp-product.php'); ?>
                                              </div></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr>
                                                <td width="5%"><input type="checkbox" name="selectproduct" id="selectproduct" checked="checked" onchange="selectdeselectcommon('selectproduct','productname[]')" /></td>
                                                <td width="95%"><label for="selectproduct">Select All / None</label></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" bgcolor="#f7faff" align="left"><strong>Status</strong></td>
                                          </tr >
                                          <tr bgcolor="#EDF4FF" >
                                            <td colspan="2" valign="top" bgcolor="#EDF4FF" align="left"><div style="height:140px; overflow:scroll">  
                                             
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status2" value="status2"/>
                                                   <label for="status2"> Awaiting Branch Head Approval</label><br />
                                                   <input checked="checked" type="checkbox" name="summarize[]" id="status1" value="status1" />
                                                   <label for="status1"> Fowarded back to Sales Person</label><br />
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status3" value="status3"/>
                                                    <label for="status3">Awaiting Co-ordinator Approval</label><br />
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status4" value="status4"/>
                                                    <label for="status4">Fowarded back to Branch Head</label><br />
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status5" value="status5" />
                                                    <label for="status5">Implementation, Yet to be Assigned</label><br />
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status6" value="status6"/>
                                                   <label for="status6"> Assigned For Implementation</label><br />
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status7" value="status7"/>
                                                    <label for="status7">Implementation in progess</label><br />
                                                    <input checked="checked" type="checkbox" name="summarize[]" id="status8" value="status8"/>
                                                   <label for="status8"> Implementation Completed</label>
                                            </div></td>
                                          </tr>
                                         <tr bgcolor="#f7faff">
                                            <td valign="top">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                <tr>
                                                  <td width="5%"><input type="checkbox" name="selectstatus" id="selectstatus" checked="checked" onchange="selectdeselectcommon('selectstatus','summarize[]')" /></td>
                                                  <td width="95%"><label for="selectstatus">Select All / None</label></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                          
                                          
                                        </table>
                                        <label></label>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      </table></td>
                                    </tr>
                                    <tr><td colspan="2"><fieldset style="border:1px solid #8AC5FF; padding:3px;">
                                          <legend><strong>Worksheets</strong> </legend>
                                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr>
                                                 <td width="13%" align="left"><label for="customerwise"><strong>
                                                    <input checked="checked" name="checkvalue[]" type="checkbox" id="customerwise" value="customerwise"/>
                                                  </strong>Customer List</label></td>
                                                  <!-- <td width="16%" align="left"><label for="regionwise"><strong>
                                                    <input checked="checked" name="checkvalue[]" type="checkbox" id="regionwise" value="regionwise"/>
                                                  </strong>Region wise Status</label></td> -->
                                                  <td width="16%" align="left"><input checked="checked" name="checkvalue[]" type="checkbox" id="branchwise"  value="branchwise"/>
                                                    <label for="branchwise">Branch wise Status</label></td>
                                                  <td width="19%" align="left"><input checked="checked" name="checkvalue[]" type="checkbox"  
                                                  id="implementerwise" value="implementerwise"/>
                                                     <label for="implementerwise">Implementer Wise Status</label>                                                  </td>
                                                  <td width="36%" align="left"><input checked="checked" name="checkvalue[]" type="checkbox"  id="salespersonwise" value="salespersonwise"  />
                                                    <label for="salespersonwise">Sales Person Wise Status</label></td>
                                                </tr>
                                              </table>
                                              </fieldset></td></tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="toexcel" type="button" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit();" />
                                              &nbsp;&nbsp;<input name="clear" type="button" class="swiftchoicebutton" id="clear" value="Reset" onclick="implementationresetfunc()" /></td>
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