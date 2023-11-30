<link href="../style/main.css" rel=stylesheet>
<script language="javascript" src="../functions/javascript.js"></script>
<script language="javascript" src="../functions/dealerquotereq.js"></script>
<?php $userid = imaxgetcookie('dealeruserid');?>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" border="0" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" size="32" onkeyup="customersearch(event);"  autocomplete="off"/>
                          <span style="display:none1">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                          </span>
                          <div id="detailloadcustomerlist">
                            <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount">&nbsp; </td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
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
                            <td width="59%"  align="left" class="active-leftnav">Quote / Payment / Request Details</td>
                            <td width="16%"  align="left" >&nbsp;</td>
                            <td width="25%"  align="centre" > <input name="save" type="button" class="swiftchoicebuttonbig" id="save" value="Send Mail" onClick="" /></td>
                          </tr>
                        </table></td>
                    </tr>
                   
                    <tr>
                      <td>
</td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter  / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%"  border="0" cellpadding="5" cellspacing="0">
                                          <tr bgcolor="#f7faff">
                                            <td width="36%" align="left" valign="top">Business Name:</td>
                                            <td width="64%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername"><input type="hidden" name="lastslno" id="lastslno" />&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top">Final Amount:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><input type="text" name="finalamt" id="finalamt" size="25"  autocomplete="off" class="swifttext-mandatory"  /></td>
                                          </tr>
                                          
                                          
                                          
                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="0">
                                          <tr bgcolor="#f7faff">
                                            <td width="43%" align="left" valign="top" bgcolor="#f7faff">Recommended Amount:</td>
                                            <td width="57%" align="left" valign="top" bgcolor="#f7faff" ><input type="text" name="recommendedamt" id="recommendedamt" size="25"   autocomplete="off" class="swifttext-mandatory"/></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="right" valign="top" bgcolor="#EDF4FF" ><input type="checkbox" name="authorization" id="authorization"  /></td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" >Authorization </td>
                                          </tr>
                                          
                                          
                                          
                                      </table></td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                      </tr>
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div><div id="productselectionprocess"></div></td>
                                            <td width="44%" align="right" valign="middle">
                                              &nbsp;&nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
&nbsp;&nbsp; <input name="clear" type="reset" class="swiftchoicebutton" id="clear" value="Clear" onClick="" />                                     </td>
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
                      <td><div id="filterdiv" style="display:none;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                            <tr>
                              <td valign="top"><div>
                                  <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="100%" valign="top" style="border-right:1px solid #d1dceb;">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                            <tr bgcolor="#edf4ff">
                                              <td width="14%" align="left" valign="top">Search Text: </td>
                                              <td colspan="3" align="left" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="50" maxlength="50" class="swifttext"  autocomplete="off" value=""/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td colspan="4" align="left" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="49%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" style=" border:1px solid #CCCCCC">
                                                      <tr>
                                                        <td colspan="2"><strong>Contact Details</strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td width="56%"><label>
                                                      <input type="radio" name="databasefield" id="databasefield0" value="id"/>
                                                      </label><label for="databasefield0">Customer ID</label></td>
                                                        <td width="44%">  <label>
                                                      <input type="radio" name="databasefield" id="databasefield5" value="place" />
                                                      </label><label for="databasefield5">Place</label></td>
                                                      </tr>
                                                      <tr>
                                                        <td>  <label>
                                                      <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                      </label><label for="databasefield1">Business Name</label></td>
                                                        <td>  <label>
                                                      <input type="radio" name="databasefield" value="phone" id="databasefield4" />
                                                     </label>
                                                         <label for="databasefield4">Phone / Cell</label></td>
                                                      </tr>
                                                      <tr>
                                                        <td>  <label>
                                                      <input type="radio" name="databasefield" value="contactperson" id="databasefield3" />
                                                      </label> <label for="databasefield3">Contact Person</label></td>
                                                        <td>
                                                      <label>
                                                      <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                      </label><label for="databasefield6">Email</label></td>
                                                      </tr>
                                                    </table></td>
                                                    <td width="51%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" style=" border: 1px solid #CCCCCC">
                                                      <tr>
                                                        <td colspan="2"><strong>Registration Details</strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td width="49%">  
                                                      <label>
                                                      <input type="radio" name="databasefield" value="cardid" id="databasefield9" />
                                                      </label><label for="databasefield9">Card ID</label>                                                    </td>
                                                        <td width="51%"><label><input type="radio" name="databasefield" value="computerid" id="databasefield8" />                                                    
</label><label for="databasefield8">Computer ID</label></td>
                                                      </tr>
                                                      <tr>
                                                        <td> <label>
                                                      <input type="radio" name="databasefield" value="scratchnumber" id="databasefield7" />
                                                     </label><label for="databasefield7"> Pin No</label> </td>
                                                        <td>  <label>
<input type="radio" name="databasefield" value="softkey" id="databasefield10" />                                                    
</label><label for="databasefield10"> Softkey</label></td>
                                                      </tr>
                                                      <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                      </tr>
                                                    </table></td>
                                                  </tr>
                                                </table>
                                              <label></label></td>
                                            </tr>
                                                  <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top">Region:</td>
                                              <td width="35%" align="left" valign="top"><select name="region" class="swiftselect" id="region">
                                                <option value="">ALL</option>
                                                <?php 
											include('../inc/region.php');
											?>
                                              </select>                                              </td>
                                              <td width="15%" align="left" valign="top">Order By:</td>
                                              <td width="36%" align="left" valign="top"><select name="orderby" id="orderby" onchange="javascript:nameloadsearch('nameloadform');" class="swiftselect">
                                                <option value="id">Customer ID</option>
                                                <option value="businessname" selected="selected">Business Name</option>
                                                <option value="contactperson">Contact Person</option>
                                                <option value="phone">Phone</option>
                                                <option value="cell">Mobile</option>
                                                <option value="place">Place</option>
                                                <option value="email">Email</option>
                                              </select></td>
                                            </tr>
                                        </table></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="69%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                              <td width="31%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton" id="filter" value="Filter" onclick="searchfilter('');" />
                                                &nbsp;&nbsp;
                                                <input name="close" type="button" class="swiftchoicebutton-red" id="close" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" /></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table>
                                  </form>
                                </div></td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                     <tr>
                      <td>&nbsp;</td>
                    </tr>
                     <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr>
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left" ></div></td>
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