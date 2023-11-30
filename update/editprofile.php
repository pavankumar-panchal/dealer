<?php
	$userid = imaxgetcookie('dealeruserid');
	include('../inc/eventloginsert.php');

?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link rel="stylesheet" type="text/css" href="../style/global.css?dummy=<?php echo (rand());?>">
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/update.js?dummy=<?php echo (rand());?>"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid; text-align:left"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    
                    
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit  Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr><td colspan="3">&nbsp;</td></tr>
                                  <tr><td colspan="3"><div id="updatewarningmeg" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td class="errorbox">Your Profile Update Request is pending for approval by Relyon. This will be processed in few hours. If you wish to cancel this request,<div class="resendtext"> <a  onclick="cancelupdate(<?php echo($userid); ?>);">click here</a></div></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
</table></div><div id="cancelmeg"></div>
</td></tr>
<tr><td width="16%">&nbsp;</td>
  <td width="67%"><div id="profilepending" align="center"></div></td>
  <td width="16%">&nbsp;</td>
</tr>

                                    <tr>
                                      <td colspan="3" >
                                      
                                        <table width="100%" border="0" cellpadding="2" cellspacing="0">
                                          <tr><td></td><input type="hidden" name="lastslno" id="lastslno" /></tr>
                                          <tr>
                                            <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                             <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Business Name:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <input name="businessname" type="text" class="swifttext-mandatory" id="businessname" size="30" autocomplete="off" value=""/>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Contact Person:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <input name="contactperson" type="text" class="swifttext-mandatory" id="contactperson" size="30" autocomplete="off" value=""/>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Address:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <textarea name="address" cols="27" class="swifttextarea" id="address"><?php echo($address); ?></textarea>
                                                    <br />
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Place:</div></td>
                                                  <td valign="top" bgcolor="#F7FAFF"><div align="left">
                                                    <input name="place" type="text" class="swifttext-mandatory" id="place" size="30" autocomplete="off"  value=""/>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">State:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <select name="state" class="swiftselect-mandatory" id="state" onchange="districtcodeFunction();">
                                                      <option value="">Select A State</option>
                                                      <?php include('../inc/state.php'); ?>
                                                    </select>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                 <td valign="top" bgcolor="#EDF4FF"> <div align="left">District:</div></td>
                                                 <td valign="top" bgcolor="#F7FAFF" id="districtcodedisplay" align="left">
                                                    <select name="district" class="swiftselect-mandatory" id="district" >
                                                      <option value="">Select A State First</option>
                                                    </select>                                      </td> 
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Pin Code:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <input name="pincode" type="text" class="swifttext-mandatory" id="pincode" value="" size="30" maxlength="10"  autocomplete="off"/>
                                                  </div></td>
                                                </tr>
                                            </table></td>
                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Region:</div></td>
                                                  <td valign="top"><div align="left">
                                                    <select name="region" class="swiftselect-mandatory" id="region">
                                                      <option value="">Select A Region</option>
                                                      <?php 
											include('../inc/region.php');
											?>
                                                    </select>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">STD Code:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <input name="stdcode" type="text" class="swifttext" id="stdcode" size="30"  autocomplete="off" value=""/>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Phone:</div></td>
                                                  <td valign="top" bgcolor="#F7FAFF"><div align="left">
                                                    <input name="phone" type="text" class="swifttext-mandatory" id="phone" size="30" autocomplete="off"  value=""/>
                                                    <br />
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Cell:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <input name="cell" type="text" class="swifttext-mandatory" id="cell" size="30" autocomplete="off"  value=""/>
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Relyon Email:</div></td>
                                                  <td valign="top" bgcolor="#F7FAFF"><div align="left">
                                                    <input name="emailid" type="text" class="swifttext-mandatory" id="emailid" size="30" autocomplete="off" value=""/>
                                                    <br />
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Personal Email:</div></td>
                                                  <td valign="top" bgcolor="#F7FAFF"><div align="left">
                                                    <input name="personalemailid" type="text" class="swifttext" id="personalemailid" size="30" autocomplete="off" value=""/>
                                                    <br />
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Website:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left">
                                                    <input name="website" type="text" class="swifttext" id="website" size="30" autocomplete="off" value="" />
                                                  </div></td>
                                                </tr>   
                                                <tr bgcolor="#edf4ff">
                                                  <td valign="top" bgcolor="#EDF4FF"><div align="left">Updated Date:</div></td>
                                                  <td valign="top" bgcolor="#f7faff"><div align="left" id="createddate">Not Available
                                                   
                                                  </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td colspan="2" valign="top" bgcolor="#f7faff"><input type="checkbox" name="agreetoupdate" id="agreetoupdate" />&nbsp;&nbsp;&nbsp;&nbsp;Yes, I want to Update my Profile</td>
                                                </tr> 
                                            </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">
                                                                                              <tr>
                                                  <td width="75%" height="35" align="left" valign="middle"><div id="form-error" align="left">&nbsp;</div></td>
                                                  <td width="25%" height="35" align="right" valign="middle">&nbsp;
                                                    <input name="update" type="button" class="swiftchoicebutton" id="update" value="Update" onclick="formsubmit(<?php echo($userid) ?>);" />                                                    &nbsp;&nbsp;
                                                  <input name="clear" type="reset" class="swiftchoicebutton" id="reset" value="Reset"  onClick="document.getElementById('form-error').innerHTML = '';validate(<?php echo($userid); ?>)"/></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                        </table>                                    </td>
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
                                              <td width="14%" valign="top">Search Text: </td>
                                              <td width="86%" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="50" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td colspan="2" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="14%" valign="top">In:                                                      </td>
                                                    <td width="86%"><label>
                                                      <input type="radio" name="databasefield" id="databasefield0" value="id"/>
                                                      ID</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                      Business Name</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" id="databasefield2" value="region"/>
                                                      Region</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" value="contactperson" id="databasefield3" />
                                                      Contact Person</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" value="phone" id="databasefield4" />
                                                      Phone</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" id="databasefield5" value="place" />
                                                      Place</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                      Email</label>
                                                      <label>
                                                      <input type="radio" name="databasefield" value="scratchnumber" id="databasefield7" />
                                                      Scratch Card</label>
                                                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                      <label>
                                                      <input type="radio" name="databasefield" value="computerid" id="databasefield8" />
                                                      Computer ID</label></td>
                                                  </tr>
                                                </table>
                                                <label></label></td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td valign="top">Order By:</td>
                                              <td valign="top"><select name="orderby" id="orderby" onchange="javascript:nameloadsearch('nameloadform');" class="swiftselect">
                                                  <option value="id">Customer ID</option>
                                                  <option value="businessname" selected="selected">Business Name</option>
                                                  <option value="contactperson">Contact Person</option>
                                                  <option value="phone">Phone</option>
                                                  <option value="cell">Mobile</option>
                                                  <option value="place">Place</option>
                                                  <option value="email">Email</option>
                                                </select>                                              </td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="69%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                              <td width="31%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton" id="filter" value="Filter" onclick="searchfilter();" />
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
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
