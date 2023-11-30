<?
	include('../inc/eventloginsert.php');
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/labelcontactdetails.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#f7faff" align="left">Type:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="type" class="swiftselect-mandatory" id="type" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/custype.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Category:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="category" class="swiftselect-mandatory" id="category" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/category.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                          
                                          <tr>
                                            <td colspan="2" bgcolor="#EDF4FF"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 1px #8AC5FF">
                                                <tr>
                                                  <td width="7%" align="left"><label>
                                                    <input type="checkbox" name="reregenable" id="reregenable" value="reregenable"  onclick="enabledisablereregistartion()"/>
                                                    </label></td>
                                                  <td width="93%" align="left"><strong> Consider Registrations</strong></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td align="left">From Date:</td>
                                                        <td align="left"><input name="fromdate" type="text" class="diabledatefield" id="DPC_fromdate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" disabled="disabled" /></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">To Date:</td>
                                                        <td align="left"><input name="todate" type="text" class="diabledatefield" id="DPC_todate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly" disabled="disabled" /></td>
                                                      </tr>
                                                      <tr>
                                                        <td width="27%" align="left">Usage Type</td>
                                                        <td width="73%" align="left"><select name="usagetype" class="sdiabledatefield" id="usagetype"  style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="singleuser">Single User</option>
                                                            <option value="multiuser">Multi User</option>
                                                            <option value="additionallicense">Additional License</option>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Purchase Type</td>
                                                        <td align="left"><select name="purchasetype" class="sdiabledatefield" id="purchasetype"  style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="new">New</option>
                                                            <option value="updation">Updation</option>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Scheme</td>
                                                        <td align="left"><select name="scheme" class="sdiabledatefield" id="scheme" style=" width:200px" disabled="disabled" >
                                                            <option value="">ALL</option>
                                                            <? include('../inc/listscheme.php'); ?>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Re-registration</td>
                                                        <td align="left"><select name="rereg" class="sdiabledatefield" id="rereg"   style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Pin Serial Number</td>
                                                        <td align="left"><select name="card" class="sdiabledatefield" id="card"  style=" width:200px" disabled="disabled" >
                                                          <option value="" selected="selected">ALL</option>
                                                          <option value="withcard">With PIN Number</option>
                                                          <option value="withoutcard">Without PIN Number</option>
                                                        </select></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                                
                                              </table></td>
                                          </tr>
                                          <tr><td colspan="2" height="5px"></td></tr>
                                          <tr><td colspan="2" bgcolor="#f7faff" ><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #8AC5FF">
                                            <tr>
                                              <td align="left"><strong>Label Options</strong></td>
                                            </tr>
                                            <tr>
                                              <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                  <tr>
                                                    <td width="33%" align="left"><label>
                                                      <input name="contactperson" type="checkbox" id="contactperson" onclick="opendiv('contactdiv')" checked="checked"/>
                                                      Contact Person</label>
                                                      &nbsp;</td>
                                                    <td width="67%" align="left"><div id="contactdiv" style="display:block;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td align="left"><label> [
                                                              <input type="radio" name="databasefield_contact" value="contactone" id="databasefield1" checked="checked"/>
                                                              Only First</label>
                                                              &nbsp;&nbsp;
                                                              <label>
                                                                <input type="radio" name="databasefield_contact" value="contactall" id="databasefield2"  />
                                                                All ]</label>
                                                            </td>
                                                          </tr>
                                                        </table>
                                                    </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left"><label>
                                                      <input name="phone" type="checkbox" id="phone" onclick="opendiv('phonediv')" checked="checked" />
                                                      Phone </label>
                                                      &nbsp;</td>
                                                    <td><div id="phonediv" style="display:block">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td align="left">[
                                                              <label>
                                                                <input type="radio" name="databasefield_phone" value="phoneone" id="databasefield3" checked="checked"/>
                                                                Only First</label>
                                                              &nbsp;&nbsp;
                                                              <label>
                                                                <input type="radio" name="databasefield_phone" value="phoneall" id="databasefield4"  />
                                                                All</label>
                                                              ] </td>
                                                          </tr>
                                                        </table>
                                                    </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left"><label>
                                                      <input name="fax" type="checkbox" id="fax" onclick="opendiv('faxdiv')" checked="checked" />
                                                      Fax</label></td>
                                                    <td><div id="faxdiv" style="display:block">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td>[
                                                              <label>
                                                                <input type="radio" name="databasefield_fax" value="faxone" id="databasefield5" checked="checked"/>
                                                                Only First</label>
                                                              &nbsp;&nbsp;
                                                              <label>
                                                                <input type="radio" name="databasefield_fax" value="faxall" id="databasefield6"  />
                                                                All</label>
                                                              ]</td>
                                                          </tr>
                                                        </table>
                                                    </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left"><label>
                                                      <input name="cell" type="checkbox"  id="cell" onclick="opendiv('celldiv')" checked="checked" />
                                                      Cell</label></td>
                                                    <td><div id="celldiv" style="display:block">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td>[
                                                              <label>
                                                                <input type="radio" name="databasefield_cell" value="cellone" id="databasefield7"  checked="checked" />
                                                                Only First</label>
                                                              &nbsp;&nbsp;
                                                              <label>
                                                                <input type="radio" name="databasefield_cell" value="cellall" id="databasefield8"/>
                                                                All</label>
                                                              ]</td>
                                                          </tr>
                                                        </table>
                                                    </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left"><label>
                                                      <input name="emailid" type="checkbox"  id="emailid"  onclick="opendiv('emaildiv')" checked="checked" />
                                                      Emailid</label></td>
                                                    <td><div id="emaildiv" style="display:block">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td>[
                                                              <label>
                                                                <input type="radio" name="databasefield_emailid" value="emailone" id="databasefield9" checked="checked" />
                                                                Only First</label>
                                                              &nbsp;&nbsp;
                                                              <label>
                                                                <input type="radio" name="databasefield_emailid" value="emailall" id="databasefield10" />
                                                                All</label>
                                                              ]</td>
                                                          </tr>
                                                        </table>
                                                    </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2" align="left" ><label>
                                                      <input name="customerid" type="checkbox"  id="customerid" checked="checked" />
                                                      Print Customer ID</label></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left"><label>
                                                      <input name="border" type="checkbox"  id="border" checked="checked"  onclick="opendiv('colordiv')"/>
                                                      Print Border</label></td>
                                                    <td align="left"><div id="colordiv" style="display:block">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td align="left">[
                                                              <label>
                                                                <input type="radio" name="databasefield_color" value="grey" id="databasefield11" checked="checked" />
                                                                Grey </label>
                                                              &nbsp;&nbsp;
                                                              <label>
                                                                <input type="radio" name="databasefield_color" value="black" id="databasefield12" />
                                                                Black </label>
                                                              ]</td>
                                                          </tr>
                                                        </table>
                                                    </div></td>
                                                  </tr>
                                              </table></td>
                                            </tr>
                                          </table></td></tr>
                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" bgcolor="#EDF4FF" align="left"><strong>Products </strong></td>
                                          </tr >
                                          <tr bgcolor="#f7faff" >
                                            <td colspan="4" valign="top" bgcolor="#f7faff" align="left"><div style="height:425px; overflow:scroll">
                                                <? include('../inc/product-report.php'); ?>
                                              </div></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td width="10%">Select: </td>
                                            <td width="33%" align="left"><strong>
                                              <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                                <option value="ALL" >ALL</option>
                                                <option value="NONE" selected="selected">NONE</option>
                                               <? include('../inc/productgroup.php') ?>
                                              </select>
                                              </strong></td>
                                             <td width="57%" align="left"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                          <input type="hidden" name="groupvalue" id="groupvalue"  />
                                          </tr>
                                        </table>
                                        <label></label>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebuttonbig" id="view" value="Generate PDF" onclick="formsubmit();" />
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
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>