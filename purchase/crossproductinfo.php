<?php
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "Select businessname,dealerusername from inv_mas_dealer where slno = '".$userid."'";
	$fetch = runmysqlqueryfetch($query);
	$businessname =strtoupper($fetch['businessname']); 
	$dealerusername = strtoupper($fetch['dealerusername']);
	$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
from inv_mas_customer where inv_mas_customer.currentdealer  = '".$userid."';";
	$result = runmysqlquery($query);
	$count = mysqli_num_rows($result);
	if($count == '0')
	{
		$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
		$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
		$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
		echo($grid);
	}
	else
	{
	
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/getdistrictfunction.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/crossproductinfo.js?dummy=<?php echo (rand());?>"></script>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:left">
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
                        <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0"></td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh customer Data"  /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onkeyup="customersearch(event);"  autocomplete="off"  style="width:204px"/>
                          <input type="hidden" name="flag" id="flag" />
                          <span style="display:none">
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
                <td colspan="2"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong> </td>
                      <td width="55%" id="totalcount" align="left">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="2" >&nbsp;</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="38%" align="top" class="active-leftnav"> Cross Product Information</td>
                            <td width="21%" align="top"><div align="right"></div></td>
                            <td width="23%" valign="top"><div align="left" style="padding:2px">
                                <div align="right"></div>
                              </div></td>
                            <td width="18%" >&nbsp;
                              <input name="search" type="submit" class="swiftchoicebuttonbig" id="search" value="Advanced Search"  onclick="displayfilterDiv()"  /></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td style="padding-top:3px"><div id="filterdivdisplay" style="display:none;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                            <tr>
                              <td valign="top"><div>
                                  <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="100%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Search Option</td>
                                      </tr>
                                      <tr>
                                        <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFD3A8" style="border:dashed 1px #545429" >
                                            <tr>
                                              <td width="57%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" style=" border-right:1px solid #CCCCCC">
                                                  <tr>
                                                    <td colspan="3" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="9%" align="left" valign="middle" >Text: </td>
                                                          <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="35" maxlength="60" class="swifttext"  autocomplete="off" value=""/>
                                                            <span style="font-size:9px; color:#999999; padding:1px">(Leave Empty for all)</span></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr valign="top" >
                                                    <td style="padding:1px" height="2">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding:3px" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="33%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 1px #004000">
                                                              <tr>
                                                                <td align="left"><strong>Look in:</strong></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" id="databasefield0" value="slno"/>
                                                                  Customer ID</label>                                                                </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                                  Business Name</label>                                                                </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" value="contactperson" id="databasefield3" />
                                                                  Contact Person</label>                                                                </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" id="databasefield5" value="place" />
                                                                  Place</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" value="phone" id="databasefield4" />
                                                                  Phone</label>
                                                                  / Cell</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                                  Email</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="5"></td>
                                                              </tr>
                                                              <tr >
                                                                <td style="border-top:solid 1px #999999"  height="5" align="left"></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" value="cardid" id="databasefield9" />
                                                                  PIN Serial Number</label>                                                                </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" value="scratchnumber" id="databasefield7" />
                                                                  PIN Number</label>                                                                </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                  <input type="radio" name="databasefield" value="billno" id="databasefield11" />
                                                                  Bill No</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="2" align="left"><label>
                                                                  <input type="radio" name="databasefield" value="computerid" id="databasefield8" />
                                                                  Computer ID</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="2" align="left"><label>
                                                                  <input type="radio" name="databasefield" value="softkey" id="databasefield10" />
                                                                  Softkey</label></td>
                                                              </tr>
                                                            </table></td>
                                                          <td width="67%" valign="top" style="padding-left:3px"><table width="67%" border="0" cellspacing="0" cellpadding="6" style="border-left:solid 1px #cccccc; border-bottom:solid 1px #cccccc; border-top:solid 1px #cccccc ">
                                                              <tr>
                                                                <td colspan="2"><strong>Selections</strong>:</td>
                                                              </tr>
                                                              <tr>
                                                                <td width="21%" height="10" align="left" valign="top">Region:</td>
                                                                <td width="79%" height="10" align="left" valign="top"><select name="region2" class="swiftselect" id="region2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php 
											include('../inc/region.php');
											?>
                                                                  </select>                                                                </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top" height="10" >State:</td>
                                                                <td align="left" valign="top" height="10"><select name="state2" class="swiftselect" id="state2" onchange="getdistrictfilter('districtcodedisplaysearch',this.value);" onkeyup="getdistrictfilter('districtcodedisplaysearch',this.value);" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/state.php'); ?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> District:</td>
                                                                <td  valign="top"  id="districtcodedisplaysearch" height="10" align="left"><select name="district2" class="swiftselect" id="district2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Dealer:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="currentdealer2" class="swiftselect" id="currentdealer2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/firstdealer.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Branch:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="branch2" class="swiftselect" id="branch2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/branch.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Type:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="type2" class="swiftselect" id="type2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <option value="Not Selected">Not Selected</option>
                                                                    <?php include('../inc/custype.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Category:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="category2" class="swiftselect" id="category2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <option value="Not Selected">Not Selected</option>
                                                                    <?php include('../inc/category.php');?>
                                                                  </select></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td height="35" align="left" valign="middle" ><div id="filter-form-error"></div></td>
                                                  </tr>
                                                </table></td>
                                              <td width="43%" valign="top" style="padding-left:3px"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                  <tr>
                                                    <td width="100%" colspan="2" valign="top" style="padding:0"></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2" valign="top" align="left"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                      <tr>
                                                        <td colspan="5" valign="top" style="padding:0"></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="5" valign="top" align="left"><strong>Having: </strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="5" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left">
                                                          <input type="checkbox" name="groupname" id="groupname1" value="sto" />
<label for="groupname1">STO</label> <br />
<input type="checkbox" name="groupname" id="groupname2" value="svh"/>
<label for="groupname2">SVH</label><br />
<input type="checkbox" name="groupname" id="groupname3" value="svi"/>
<label for="groupname3">SVI</label> <br />
<input type="checkbox" name="groupname" id="groupname4" value="tds"/>
<label for="groupname4">TDS</label> <br />
<input type="checkbox" name="groupname" id="groupname5" value="spp" />
<label for="groupname5">SPP</label><br />
<input type="checkbox" name="groupname" id="groupname6" value="sac"/>
<label for="groupname6">SAI</label><br/>     
<input type="checkbox" name="groupname" id="groupname7" value="xbrl"/>
<label for="groupname7">XBRL</label><br/>                                                      </td>
                                                      </tr>
                                                      <tr>
                                                        
                                                        <td width="9%" align="center"><input type="checkbox" name="selectall" id="selectall" value="selectall" onclick="selectallfunc('groupname','selectall')"/></td>
                                                        <td width="81%"><label for="selectall"><strong>Select All</strong></label></td>
                                                        <td width="10%">&nbsp;</td>
                                                        </tr>

                                                    </table></td>
                                                  </tr>
                                                  <tr >
                                                    <td ><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                      <tr>
                                                        <td colspan="5" valign="top" style="padding:0"></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="5" valign="top" align="left"><strong>Not Having: </strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="5" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><input type="checkbox" name="groupdisplay" id="groupdisplay1" value="sto" />
                                                         <label for="groupdisplay1">STO</label> <br />
                                                          <input type="checkbox" name="groupdisplay" id="groupdisplay2" value="svh"/>
                                                          <label for="groupdisplay2">SVH</label><br />
                                                          <input type="checkbox" name="groupdisplay" id="groupdisplay3" value="svi"/>
                                                          <label for="groupdisplay3">SVI</label> <br />
                                                          <input type="checkbox" name="groupdisplay" id="groupdisplay4" value="tds"/>
                                                          <label for="groupdisplay4">TDS</label> <br />
                                                          <input type="checkbox" name="groupdisplay" id="groupdisplay5" value="spp" />
                                                         <label for="groupdisplay5">SPP</label><br />
                                                          <input type="checkbox" name="groupdisplay" id="groupdisplay6" value="sac"/>
                                                          <label for="groupdisplay6">SAI</label><br/>
                                                          <input type="checkbox" name="groupdisplay" id="groupdisplay7" value="xbrl"/>
                                                          <label for="groupdisplay7">XBRL</label><br/>                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td width="9%" align="center"><input type="checkbox" name="dataselectall" id="dataselectall" value="dataselectall" onclick="selectallfunc('groupdisplay','dataselectall')"/></td>
                                                        <td width="80%"><label for="dataselectall"><strong>Select All</strong></label></td>
                                                            <td width="11%">&nbsp;</td>
                                                      </tr>
                                                    </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td height="20" ><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="searchcustomerarray();" />
                                                      &nbsp;
                                                      <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);">
                                                      &nbsp;
                                                      <input name="close" type="button" class="swiftchoicebutton" id="close" value="Close" onclick="document.getElementById('filterdivdisplay').style.display='none';" /></td>
                                                  </tr>
                                              </table></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"></td>
                                      </tr>
                                    </table>
                                  </form>
                                </div></td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <tr>
                      <td height="5">&nbsp;</td>
                    </tr>
                    <tr><td><form action="" method="post" name="submitform" id="submitform" onsubmit="return false;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #379BFF"  bgcolor="#F0F5F7">
      <tr style="height:35px;" bgcolor="#CBE0FA" >
        <td width="17%" style="padding-left:8px;" ><strong>Company Name:</strong></td>
        <td width="78%" id="displaycompanyname" style="padding-left:8px;">&nbsp;</td>
        <td align="right" style="padding-right:10px;"><div align="right"><img src="../images/plus.jpg" border="0" id="toggleimg2" name="toggleimg2"  align="absmiddle" onClick="displayDiv2('filterdiv','toggleimg2');" style="cursor:pointer"/></div></td>
      </tr>
      <tr>
        <td colspan="3" valign="top" >
            <div id="filterdiv" style="display:none;">
            <table width="99%" border="0" cellspacing="0" cellpadding="4" align="center" >
              <tr>
                <td colspan="4" ><table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td width="41%"><strong>Customer ID: </strong></td>
                      <td id="displaycustomerid">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><strong>Contact Person: </strong></td>
                      <td id="displaycontactperson" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td valign="top"><strong>Address: </strong></td>
                      <td  id="displayaddress" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td><strong>Email: </strong></td>
                      <td  id="displayemail" >&nbsp;</td>
                    </tr>
                    <tr>
                      <td ><strong>Phone: </strong></td>
                      <td id="displayphone">&nbsp;</td>
                    </tr>
                </table></td>
                <td width="54%" colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                    <tr>
                      <td width="49%"><strong>Current Dealer: </strong></td>
                      <td width="51%"  id="displaydealer">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><strong>Region: </strong></td>
                      <td id="displayregion">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><strong>Branch: </strong></td>
                      <td id="displaybranch">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><strong>Type of Customer: </strong></td>
                      <td  id="displaytypeofcustomer">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><strong>Type of Category: </strong></td>
                      <td  id="displaytypeofcategory">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
            </table>
            </div></td>
      </tr>
      
    </table></td>
  </tr>
  <tr>
    <td><span style="padding-left:8px;">
      <input type="hidden" name="displaycompanynamehidden" id="displaycompanynamehidden" />
    </span></td>
  </tr>
  <tr>
    <td valign="top"> <div id="yearwisedisplay" > <table width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr>
        <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Yearwise Product Purchase</td>
        <td align="right" class="header-line" style="padding-right:10px"><div align="right"><img src="../images/plus.jpg" border="0" id="toggleimg1" name="toggleimg1"  align="absmiddle" onClick="displayDiv2('displaydetails','toggleimg1');" style="cursor:pointer"/></div></td>
      </tr>
      <tr>
        <td colspan="2" valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><div id="displaydetails" style="display:none">
                <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #308ebc; border-top:none;">
                <tr><td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="3"  class="grid-border">
                    <tr bgcolor="#e9967a">
                      <td class="grid-td-border"><div align="center"><strong>Groups</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2004-05</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2005-06</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2006-07</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2007-08</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2008-09</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2009-10</strong></div></td>
                      <td class="grid-td-border"><div align="center"><strong>2010-11</strong></div></td>
                    </tr>
                    <tr >
                      <td bgcolor="#e9967a" class="grid-td-border"><div align="center"><strong>SPP</strong></div></td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                    </tr>
                    <tr >
                      <td bgcolor="#e9967a" class="grid-td-border"><div align="center"><strong>STO</strong></div></td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e9967a" class="grid-td-border"><div align="center"><strong>TDS</strong></div></td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e9967a" class="grid-td-border"><div align="center"><strong>SVH</strong></div></td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e9967a" class="grid-td-border"><div align="center"><strong>SVI</strong></div></td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#e9967a" class="grid-td-border"><div align="center"><strong>SAI</strong></div></td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                      <td class="grid-td-border">&nbsp;</td>
                    </tr>
                  </table></td></tr></table>
                </div></td>
              </tr>
            </table>       </td>
      </tr>
    </table></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%"  cellspacing="0" cellpadding="1" style="border:solid 1px #999999">
      <tr height="20px">
        <td width="14%" align="center" id="tabgroupgridh1" onclick="gridtabcus6('1','tabgroupgrid','spp'); " style="cursor:pointer" class="grid-active-tabclass6"><strong>SPP</strong></td>
        <td width="14%" align="center" id="tabgroupgridh2" onclick="gridtabcus6('2','tabgroupgrid','sto');" style="cursor:pointer" class="grid-tabclass6"><strong>STO</strong></td>
        <td width="14%" align="center" id="tabgroupgridh3" onclick="gridtabcus6('3','tabgroupgrid','tds');" style="cursor:pointer" class="grid-tabclass6"><strong>TDS</strong></td>
        <td width="14%" align="center" id="tabgroupgridh4" onclick="gridtabcus6('4','tabgroupgrid','svh');" style="cursor:pointer" class="grid-tabclass6"><strong>SVH</strong></td>
        <td width="14%" align="center" id="tabgroupgridh5" onclick="gridtabcus6('5','tabgroupgrid','svi');" style="cursor:pointer" class="grid-tabclass6"><strong>SVI</strong></td>
        <td width="14%" align="center" id="tabgroupgridh6" onclick="gridtabcus6('6','tabgroupgrid','sac');" style="cursor:pointer" class="grid-tabclass6"><strong>SAI</strong></td>
        <td width="15%" align="center" id="tabgroupgridh7" onclick="gridtabcus6('7','tabgroupgrid','xbrl');" style="cursor:pointer" class="grid-tabclass6"><strong>XBRL</strong></td>
      </tr>
      <tr>
        <td colspan="7"  align="left" height="25px" ><div id="imagedisplay" ><img src="../images/tabhead-saral-paypack.gif" border="0" align="absmiddle" ></div></td>
       
      </tr>
      <tr>
        <td  colspan="7" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="2" >
            <tr>
              <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-bottom:solid 1px #308ebc;">
                <tr>
                  <td width="53%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                    <tr >
                      <td align="left" valign="top" bgcolor="#EDF4FF" >Overall Remarks:</td>
                      <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><textarea name="overallremarks" cols="30" class="swifttextarea" id="overallremarks" ></textarea></td>
                    </tr>
                  </table></td>
                  <td width="47%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2"   >
                    <tr >
                      <td width="34%" align="left" valign="top" bgcolor="#EDF4FF">Current Status:</td>
                      <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF"><select name="status" class="swiftselect-mandatory" id="status" style="width:180px">
                          <?php 
											include('../inc/statuslist.php');
							?>
                      </select></td>
                    </tr>
                    <tr><td colspan="3" height="5px"  bgcolor="#EDF4FF"></td></tr>
                    <tr >
                      <td align="right" valign="bottom" bgcolor="#EDF4FF" >&nbsp;</td>
                      <td width="21%" align="center"  bgcolor="#EDF4FF">&nbsp;</td>
                      <td width="45%" align="center"  bgcolor="#EDF4FF"><input name="update" type="button" class= "swiftchoicebutton" id="update" value="Update" onclick="updatestatus()" /></td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td style="padding:5px" colspan="2"><strong>Follow Up</strong></td>
            </tr>
            <tr>
              <td width="52%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                  <tr bgcolor="#f7faff">
                    <td width="34%" align="left" valign="top" bgcolor="#f7faff" >Followup By:</td>
                    <td width="66%" align="left" valign="top" bgcolor="#f7faff" id="displayenteredby"><?php echo($businessname)?></td>
                  </tr>
                  <tr bgcolor="#f7faff">
                    <td align="left" valign="top" bgcolor="#EDF4FF">Remarks:</td>
                    <td align="left" valign="top" bgcolor="#EDF4FF" ><textarea name="remarks" cols="27" class="swifttextarea" id="remarks" ></textarea></td>
                  </tr>
              </table></td>
              <td width="48%" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="0" >
                  <tr bgcolor="#f7faff">
                    <td width="32%" align="left" valign="top" bgcolor="#EDF4FF">Followup Date:</td>
                    <td width="68%" align="left" valign="top" bgcolor="#EDF4FF"  id="entereddate"><?php echo(datetimelocal('d-m-Y')); ?></td>
                  </tr>
                  <tr bgcolor="#f7faff">
                    <td align="left" valign="top">Next Followup:</td>
                    <td align="left" valign="top"><input name="enddate" type="text" class="swifttext" id="DPC_followupdate" size="27" maxlength="10"  autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"/></td>
                  </tr>
                  <tr bgcolor="#edf4ff">
                    <td align="left" valign="top"  bgcolor="#edf4ff">Dealer:</td>
                    <td align="left" valign="top" bgcolor="#edf4ff" ><select name="dealer" class="swiftselect-mandatory" id="dealer" style="width:180px;">
                        <option value="">Make A Selection</option>
                        <?php 
											include('../inc/firstdealer.php');
											?>
                    </select></td>
                  </tr>
                  <input type="hidden" name="lastslno" id="lastslno" />
                  <input type="hidden" name="cusslno" id="cusslno" />
                  <input type="hidden" name="gridslno" id="gridslno" />
                  <input type="hidden" name="productgroup" id="productgroup" />
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
            <tr>
              <td colspan="2"  style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="2" align="left" valign="middle" height="15"></td>
                  </tr>
                  <tr>
                    <td  width="50%" align="left" valign="middle" height="30"><div id="form-error"></div></td>
                    <td width="50%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onclick="newentry();document.getElementById('form-error').innerHTML = ''; " />
                      &nbsp;
                      <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save');" />
                      &nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
              </table></td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                      <tr>
                        <td colspan="2" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:700px ; padding:2px; " align="center">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td height="10px"><div id="tabgroupgridc1_1"  align="center">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="3"  class="table-border-grid" style="overflow:scroll">
                                    <tr class="tr-grid-header">
                                      <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
                                      <td nowrap = "nowrap" class="td-border-grid" align="left">Entered By</td>
                                      <td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td>
                                      <td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
                                      <td nowrap = "nowrap" class="td-border-grid" align="left">Next Followup Date</td>
                                      <td nowrap = "nowrap" class="td-border-grid" align="left">EnteredDate</td>
                                       <td nowrap = "nowrap" class="td-border-grid" align="left">Overall remarks</td>
                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Current Status</td>
                                    </tr>
                                  </table>
                                </div></td>
                              </tr>
                              <tr>
                                <td><div id="customerlink" align="center"></div>
                                    <div id="tabgroupgridc1link" align="center" >No datas found to be displayed.</div></td>
                              </tr>
                            </table>
                        </div>
                            <div id="regresultgrid" style="overflow:auto; display:none; height:200px;width:700px ;padding:2px;" align="center">&nbsp;</div></td>
                      </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</form></td></tr>
                </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script>
gettotalcustomercount();
</script>
<?php
}
?>