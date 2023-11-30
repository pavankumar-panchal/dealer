<?php
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
$query = "select inv_mas_dealer.businessname as dealername,inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_state.statename,inv_mas_dealer.enablebilling as enablebilling,inv_mas_dealer.telecaller ,inv_mas_dealer.branchhead from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$relyonexecutive = $resultfetch['relyonexecutive'];
$statecode = $resultfetch['statecode'];
$telecaller = $resultfetch['telecaller'];
$enablebilling = $resultfetch['enablebilling'];
$branchhead = $resultfetch['branchhead'];
$dealername = $resultfetch['dealername'];
if($enablebilling == 'yes')
{
	$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') order by businessname;";
	$result = runmysqlquery($query); 
	if(mysqli_num_rows($result) == 0)
	{
		$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
		from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
		where inv_mas_district.statecode = '".$statecode."' order by businessname;";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) == 0)
		{
			$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
			$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
			$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">There are no Customers available in your account</font></div></strong></td></tr></table>';
			echo($grid);
		}
		else
		{
			$flag = 'true';
		}
	}
	else
	{
		$flag = 'true';
	}
}
else
{
	$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
	$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
	$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
	echo($grid);
}
if($flag == 'true')
{
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox-invoicing.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/invoicing.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript"  src="../functions/getdistrictjs.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/enter_keyshortcut.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/key_shortcut.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/customer-shortkey.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/fileupload.js?dummy=<?php echo (rand());?>"></script>

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
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" border="0" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <span style="display:none1">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled" />
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
                <td width="55%" id="totalcount" align="left"></td>
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
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="14%" align="top" class="active-leftnav">Tax Invoice</td>
                            <td width="26%" align="top"><div align="right"><font color="#FF6B24">Invoice No?</font></div></td>
                            <td width="25%" align="top"><div align="center">
                                <input name="searchinvoiceno" type="text" class="swifttext" id="searchinvoiceno" onkeyup="searchbyinvoicenoevent(event);"  maxlength="20"  autocomplete="off" style="width:125px"/>
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbyinvoiceno(document.getElementById('searchinvoiceno').value);" style="cursor:pointer" /> </div></td>
                            <td width="12%" align="top"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="23%" valign="top"><div align="left" style="padding:2px">
                                <div align="center">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);"  maxlength="20"  autocomplete="off" style="width:125px"/>
                                  <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="3" style="padding-top:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                        </table></td>
                    </tr>
                    <tr>
                      <td height="5" colspan="3"></td>
                    </tr>
                    <tr>
                      <td  colspan="3"  style="text-align:right"><span align="right"><strong><a onclick="formsubmitsave()" style="text-decoration:none"  class="r-text" >Create New Customer</a></strong></span>&nbsp;&nbsp;<span align="center">|</span>&nbsp;&nbsp;<span align="center" ><a href="../home/index.php?a_link=receipts" style="text-decoration:none" class="r-text" ><strong>Receipts</strong></a></span></td>
                    </tr>
                    <tr>
                      <td height="5" colspan="3"></td>
                    </tr>
                    <tr>
                      <td colspan="3"><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #CEE7FF"  bgcolor="#FEFFE6">
                          <tr style="height:35px;"  bgcolor="#CBE0FA" >
                            <td width="17%" style="padding-left:8px; padding-top:5px;"><strong>Company Name:</strong></td>
                            <td width="78%" style="padding-left:8px; padding-top:5px;" height="30px"><span id="customerdetailsshow" style="display:none">
                              <input type="text" value="" size="60" class="swifttext-readonly-border-h"  name="displaycompanyname"  id="displaycompanyname" readonly="readonly" height="30px;" />
                              </span><span id="customerdetailshide" style="display:block"></span></td>
                            <td width="5%" style="padding-right:10px;"><div align="right"><img src="../images/plus.jpg" border="0" id="toggleimg1" name="toggleimg1"  align="absmiddle" onClick="displayDiv2('displaycustomerdetails','toggleimg1');" style="cursor:pointer"/></div></td>
                          </tr>
                          <tr>
                            <td colspan="3" valign="top" ><div id="displaycustomerdetails" style="display:none;">
                                <table width="99%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                  <tr >
                                    <td colspan="4" valign="top" style="border-right:#E6F0F9 1px solid; border-bottom:#E6F0F9 1px solid"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td width="43%"><strong>Customer ID: </strong></td>
                                          <td width="57%" id="displaycustomerid">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td height="50px" colspan="2" valign="top"><strong>Address:<br />
                                            <br />
                                            </strong>
                                            <textarea  cols="47" rows="2" autocomplete="off" id="displayaddress" class="swifttext-readonly-border" type="text" name="displayaddress" readonly="readonly"></textarea></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Contact Person: </strong></td>
                                          <td height="35px"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displaycontactperson"  id="displaycontactperson" readonly="readonly"/></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Email: </strong></td>
                                          <td height="35px" ><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displayemail"  id="displayemail" readonly="readonly" /></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Phone: </strong></td>
                                          <td height="35px;"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displayphone"  id="displayphone" readonly="readonly"/></td>
                                        </tr>
                                      </table></td>
                                    <td width="52%" colspan="2" valign="top"  style=" border-bottom:#E6F0F9 1px solid"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td><strong>Cell: </strong></td>
                                          <td  height="35px;"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displaycell"  id="displaycell" readonly="readonly"/></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Type of Customer: </strong></td>
                                          <td id="displaytypeofcustomer"></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Type of Category: </strong></td>
                                          <td id="displaytypeofcategory"></td>
                                        </tr>
                                        <tr>
                                          <td width="38%"><strong>Marketing Exe: </strong></td>
                                          <td width="62%" id="displaymarketingexe"><?php if(($telecaller == 'yes') || ($branchhead  == 'yes')) echo('Not Selected'); else echo($dealername); ?></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Current Dealer:</strong></td>
                                          <td id="displaydealer3">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td><strong>PO Date:</strong></td>
                                          <td  ><input type="text" class="swifttext-readonly-border" id="DPC_startdate" size="25" autocomplete="off" value="" readonly="readonly" /></td>
                                        </tr>
                                        <tr>
                                          <td><strong>PO Reference:</strong></td>
                                          <td ><input type="text" value="" size="30" class="swifttext-readonly-border"  name="poreference"  id="poreference" readonly="readonly"/></td>
                                        </tr>
                                      </table></td>
                                  <tr>
                                    <td colspan="4" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><input type="hidden" name="cusnamehidden" id="cusnamehidden" />
                                            <input type="hidden" name="addresshidden" id="addresshidden" />
                                            <input type="hidden" name="contactpersonhidden" id="contactpersonhidden" />
                                            <input type="hidden" name="emailhidden" id="emailhidden" />
                                            <input type="hidden" name="phonehidden" id="phonehidden" />
                                            <input type="hidden" name="cellhidden" id="cellhidden" />
                                            <input type="hidden" name="custypehidden" id="custypehidden" />
                                            <input type="hidden" name="cuscategoryhidden" id="cuscategoryhidden" />
                                            <input type="hidden" name="podatehidden" id="podatehidden" />
                                            <input type="hidden" name="poreferencehidden" id="poreferencehidden" /></td>
                                        </tr>
                                      </table></td>
                                    <td colspan="2"><div align="center">
                                        <input name="edit" type="button" class="swiftchoicebutton" id="edit" value="Edit" onclick="customerdetailsremovereadonly()"/>
                                        &nbsp;&nbsp;&nbsp;
                                        <input name="cancel" type="button" class="swiftchoicebutton" id="cancel" value="Cancel"  onclick="customerdetailsmakereadonly()"/>
                                      </div></td>
                                  </tr>
                                </table>
                              </div></td>
                          </tr>
                          <tr>
                            <td  colspan="3"></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="10px;"></td>
                    </tr>
                    <tr>
                      <td colspan="3" height="10px;"></td>
                    </tr>
                    <tr>
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; ">
                          <tr>
                            <td colspan="2"><?php if(($telecaller == 'yes') || ($branchhead == 'yes')) {?>
                              <table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:#CCCCCC 1px solid " bgcolor="#FFFFEC" >
                                <tr>
                                  <td width="31%"  height="42px;" valign="middle"><select name="dealer" class="swiftselect" id="dealer" style="width:195px;" disabled="disabled">
                                      <option value="" selected="selected">Select a Dealer</option>
                                      <?php 
											include('../inc/dealer-invoicing.php');
											?>
                                    </select></td>
                                  <td width="8%" valign="middle"><a  onclick="getdealerdetails1();"  class="r-text">Go &gt;&gt;</a></td>
                                  <td width="61%" valign="middle" ><div id="dealerdivdisplay" style="display:none1">
                                      <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"  >
                                        <tr>
                                          <td valign="top" ><strong>
                                            <div style="padding-top:6px;vertical-align:top"><span id="dealerdisplayheading"><font color="#426b8e">Dealer Name: </font></span><span id="displaydealername" ><font color="#FF0000">Not Selected</font></span>&nbsp;&nbsp;<span style="display:none" id="displaydealerdetailsicon"><img src="../images/help-image.gif" width="13" height="14"  onclick ="showdealerdetails();" title="Delaer Details"  align="absmiddle" style="cursor:pointer"/></span></div>
                                            </strong></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" ><div style="display:none">
                                              <div id="displaydealerdetails" style='background:#fff; width:450px; font-size:14px'></div>
                                            </div></td>
                                        </tr>
                                        <tr>
                                          <td></td>
                                        </tr>
                                      </table>
                                    </div></td>
                                </tr>
                                <tr>
                                  <td colspan="3"></td>
                                </tr>
                              </table>
                              <?php } else {?>
                              &nbsp;
                              <?php } ?></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td colspan="2"  valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="14%"><strong>Item</strong>(Software): </td>
                                                  <td width="29%"><select name="product" class="swiftselect" id="product" style="width:200px;">
                                                      <option value="" selected="selected">Select a Item</option>
                                                      <?php 
											include('../inc/productforpurchase.php');
											?>
                                                    </select></td>
                                                  <td width="8%"><a  onclick="addselectedproduct('software');" class="r-text"  >Add &gt;&gt; </a></td>
                                                  <td width="12%"><strong>Item</strong>(others):</td>
                                                  <td width="29%"><select name="product2" class="swiftselect" id="product2" style="width:200px;">
                                                      <option value="" selected="selected">Select a Item</option>
                                                      <?php 
											include('../inc/services.php');
											?>
                                                    </select></td>
                                                  <td width="8%"><a  onclick="addselectedproduct('others');" class="r-text" >Add >> </a></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><input type="hidden" name="productcounthidden" id="productcounthidden" />
                                              <input type="hidden" name="lastslno" id="lastslno" />
                                              <input type="hidden" name="onlineslno" id="onlineslno" />
                                              <input type="hidden" name="offerremarkshidden" id="offerremarkshidden" />
                                              <input type="hidden" name="rowslno" id="rowslno" />
                                              <input type="hidden" name="producthiddenslnoid" id="producthiddenslnoid" />
                                              <input type="hidden" name="dealeridhidden" id="dealeridhidden" /></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="left" bgcolor="#F7FAFF"><div style="overflow:auto;width:699px; display:none1;" id="productlistdiv" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-border-grid" >
                                                  <tr>
                                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="4" id="seletedproductgrid"  >
                                                        <tr class="tr-grid-header">
                                                          <td width="9%" nowrap="nowrap" class="td-border-grid">Sl No</td>
                                                          <td width="27%" nowrap="nowrap" class="td-border-grid">Item</td>
                                                          <td width="15%" nowrap="nowrap" class="td-border-grid">Purchase Type</td>
                                                          <td width="13%" nowrap="nowrap" class="td-border-grid">Usage Type</td>
                                                          <td width="10%" nowrap="nowrap" class="td-border-grid">Quantity</td>
                                                          <td width="15%" nowrap="nowrap" class="td-border-grid">Unit Price</td>
                                                          <td width="11%" nowrap="nowrap" class="td-border-grid">Remove</td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="7" nowrap = "nowrap" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Customer First</font></strong></div></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  class="td-border-grid"><table width="100%" border="0" cellspacing="0" cellpadding="3" id="adddescriptionrows">
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td style="border-left:#d1dceb 1px solid;"><div align="left" id="adddescriptionrowdiv" style="display:none; height:20px;">
                                                        <div align="center" ><a onclick="adddescriptionrows();" class="r-text">Add one More >></a></div>
                                                      </div></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td width="65%" height="25px;" ><div id="displayofferremarksdiv" style="display:none"></div></td>
                                            <td width="35%" ><div align ="left" style="display:none" id="removeoffermegdiv" ><font color = "#FF0000"><strong><a style="cursor:pointer; " onclick="removeofferremarksdiv();">X</a></strong></font></div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="left"><div id="resultdiv" style="display:none" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="5%">&nbsp;</td>
                                                    <td width="58%">&nbsp;</td>
                                                    <td width="37%">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                              <tr>
                                                                <td width="30%" >SEZ (special Economic Zone) tax:</td>
                                                                <td width="70%"><input type="checkbox" name="seztax" id="seztax" onclick="sezfunc()"/></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td><div id="sextaxdivdisplay" style="display:none">
                                                              <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                                <tr >
                                                                  <td width="30%" valign="top" >SEZ Attachment File:</td>
                                                                  <td width="70%" valign="top" style="border-right:1px solid  #C6E2FF"><input name="seztaxattachment" type="text" class="swifttext" id="seztaxattachment"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                                    <img src="../images/fileattach.jpg" name="myfileuploadimage1" border="0" align="absmiddle"  id="myfileuploadimage1" style="cursor:pointer; " onclick="fileuploaddivid('','seztaxattachment','seztaxuploaddiv','575px','35%','6',document.getElementById('customerlist').value,'file_link','');"/><br />
                                                                    <span class="textclass" >(Upload zip or rar file only)</span>
                                                                    <input type="hidden" value="" name="file_link" id="file_link" /></td>
                                                                </tr>
                                                              </table>
                                                            </div></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="5%">&nbsp;</td>
                                                    <td width="58%">&nbsp;</td>
                                                    <td width="37%">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="55%" valign="top"><div align="left">Invoice Remarks<br />
                                                                    <br />
                                                                    <input name="invoiceremarks" type="text" class="swifttextareanew" id="invoiceremarks" style="width:300px;" maxlength="100"/>
                                                                    <br />
                                                                    <br />
                                                                    Remarks for Relyon A/c's (Private note)<br />
                                                                    <br />
                                                                    <input name="privatenote" type="text" class="swifttextareanew" id="privatenote" style="width:300px;" maxlength="100"/>
                                                                  </div></td>
                                                                <td width="45%"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td>&nbsp;</td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td width="42%">Total:</td>
                                                                      <td width="58%"><input name="totalamount" type="text" class="swifttext-readonly" id="totalamount"   maxlength="10"  autocomplete="off" style="width:100px; text-align:right" disabled="disabled" readonly="readonly"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td>Service Tax:</td>
                                                                      <td><input name="sericetaxamount" type="text" class="swifttext-readonly" id="sericetaxamount"   maxlength="10"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled" readonly="readonly"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td>Net Amount:</td>
                                                                      <td><input name="netamount" type="text" class="swifttext-readonly" id="netamount"   maxlength="10"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled"  readonly="readonly"/></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><a class="r-text" onclick="pricingdivhideshow();">Pricing &#8250;&#8250;</a></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <tr>
                                                          <td colspan="2"><div id="pricingdiv" style="display:none">
                                                              <table width="100%" border="0" cellspacing="0" cellpadding="3" style="background-color:#FFFFE8; border:1px solid #CCCCCC">
                                                                <tr>
                                                                  <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                      <tr>
                                                                        <td width="10%"><label for="normal">
                                                                            <input type="radio" name="pricing" id="normal" value="normal"  checked="checked" onclick="validatepricingfield();"/>
                                                                            Empty</label></td>
                                                                        <td width="13%"><label for="default">
                                                                            <input type="radio" name="pricing" id="default" value="default" onclick="validatepricingfield();"/>
                                                                            Price List</label></td>
                                                                        <td width="10%"><label for="offer">
                                                                            <input type="radio" name="pricing" id="offer" value="offer" onclick="validatepricingfield();"  />
                                                                            Offer</label></td>
                                                                        <td width="67%"><label for="inclusivetax">
                                                                            <input type="radio" name="pricing" id="inclusivetax" value="inclusivetax"  onclick="validatepricingfield();" />
                                                                            Inclusive Tax</label></td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                              <td valign="top" height="20px;"><span id="offerdescriptiondiv" style="display:none">Offer Name:
                                                                                <input name="offerremarks" type="text" class="swifttext-mandatory" id="offerremarks"  autocomplete="off" style="width:225px"/>
                                                                                </span><span id="offeramtdiv" style="display:none;vertical-align:top">Offer Amount:
                                                                                <input name="offeramount" type="text" class="swifttext-mandatory" id="offeramount"   maxlength="150"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled" align="right"/>
                                                                                </span><span id="inclusivetaxamtdiv" style="display:none; vertical-align:top">Amount Inclusive Tax:
                                                                                <input name="inclusivetaxamount" type="text" class="swifttext-mandatory" id="inclusivetaxamount"   maxlength="150"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled" align="right"/>
                                                                                </span><span id="displayapplylink" style="vertical-align:top">&nbsp;</span></td>
                                                                            </tr>
                                                                          </table></td>
                                                                      </tr>
                                                                    </table></td>
                                                                </tr>
                                                              </table>
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC; height:80px" bgcolor="#FFF2F2"  >
                                                              <tr >
                                                                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td>Payment Information</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><input type="radio" name="modeofpayment" id="databasefield1" value="credit/debit" checked="checked" onclick="showhidepaymentdiv();"/>
                                                                        &nbsp;
                                                                        <label for="databasefield1"><strong>Credit Cards / Debit Cards</strong> [Master Card / VISA]</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><label for="databasefield2">
                                                                          <input type="radio" name="modeofpayment" id="databasefield2" value="others"  onclick="showhidepaymentdiv();"/>
                                                                          &nbsp;<strong>Others</strong> <strong ></strong> [Cheque / DD / NEFT / etc]</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td ><div id="paymentdiv" style="display:none">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #FFE1E1" bgcolor="#FFFFFF" height="170px;">
                                                                            <tr>
                                                                              <td width="100%" valign="top"><div>
                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                    <tr>
                                                                                      <td colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                          <tr>
                                                                                            <td width="23%" valign="top"><div align="left">
                                                                                                <label for="databasefield3">
                                                                                                  <input type="radio" name="paymentmodeselect" id="databasefield3" value="paymentmadenow" onclick="showhidepaymentinfodiv();" checked="checked"/>
                                                                                                  Payment made Now</label>
                                                                                              </div></td>
                                                                                            <td width="77%" colspan="2"><div align="left">
                                                                                                <label for="databasefield4">
                                                                                                  <input type="radio" name="paymentmodeselect" id="databasefield4" value="paymentmadelater"   onclick="showhidepaymentinfodiv();"/>
                                                                                                  Will Pay Later </label>
                                                                                              </div></td>
                                                                                          </tr>
                                                                                        </table></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td colspan="3"><div id="paymentmadenow" style="display:none">
                                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFBF9" >
                                                                                            <tr>
                                                                                              <td width="24%"><strong>Mode Of Payment:</strong></td>
                                                                                              <td><label for="databasefield5"> </label>
                                                                                                <label for="databasefield7">
                                                                                                  <input type="radio" name="paymentmode" id="databasefield7" value="chequeordd"   onclick="hideorshowpaymentdetailsdiv();" checked="checked"/>
                                                                                                  Cheque / DD</label>
                                                                                                <label for="databasefield6">
                                                                                                  <input type="radio" name="paymentmode" id="databasefield6" value="onlinetransfer"   onclick="hideorshowpaymentdetailsdiv();"/>
                                                                                                  Online Transfer </label>
                                                                                                <label for="databasefield5">
                                                                                                  <input type="radio" name="paymentmode" id="databasefield5" value="cash"   onclick="hideorshowpaymentdetailsdiv();"/>
                                                                                                  Cash </label></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                  <tr>
                                                                                                    <td width="18%">Payment Amount:</td>
                                                                                                    <td width="35%"><input name="paymentamount" type="text" class="swifttext-readonly" id="paymentamount" size="30" maxlength="12" autocomplete="off" readonly="readonly"/></td>
                                                                                                    <td width="47%"><input type="checkbox" name="partialpayment" id="partialpayment"  onclick="disableorenablepaymentamount()"/>
                                                                                                      &nbsp;Part Payment</td>
                                                                                                  </tr>
                                                                                                </table></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td colspan="2"><div id="paymentdetailsdiv1" style="display:block">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td width="18%" valign="top">Payment Details:</td>
                                                                                                      <td width="35%" valign="top"><input name="paymentremarks" type="text" class="swifttextareanew" id="paymentremarks" maxlength="100" style="width:190px"/></td>
                                                                                                      <td width="47%" valign="top"><div align="justify" id="cashwarningmessage" style="display:none">
                                                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:dashed 1px #FF0000" bgcolor="#FFFFCC">
                                                                                                            <tr>
                                                                                                              <td><div align="justify"><strong>Warning:</strong> Please collect the amount by Cheque / DD. Else, you may get an online transfer or even paid through credit/debit card. It is not recommended to collect cash, unless in exception.</div></td>
                                                                                                            </tr>
                                                                                                          </table>
                                                                                                        </div>
                                                                                                        <div align="justify" id="bankdetailstip" style="display:none">
                                                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:dashed 1px #FF0000" bgcolor="#FFFFCC">
                                                                                                            <tr>
                                                                                                              <td><ol>
                                                                                                                  <li><strong> Bank of India</strong></li>
                                                                                                                  Payee name: Relyon Softech Ltd<br />
                                                                                                                  Bank A/c No:   840730110000046<br />
                                                                                                                  Branch: J C Road, Bangalore<br />
                                                                                                                  NEFT/IFSC Code: BKID0008407<br />
                                                                                                                  <li><strong> ICICI Bank</strong><br />
                                                                                                                    Payee name: Relyon Softech Ltd<br />
                                                                                                                    Bank A/c No:   029605004918<br />
                                                                                                                    Branch: Rajajinagar, Bangalore<br />
                                                                                                                    NEFT/IFSC Code: ICIC0000296<br />
                                                                                                                  </li>
                                                                                                                </ol></td>
                                                                                                            </tr>
                                                                                                          </table>
                                                                                                        </div></td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div>
                                                                                                <div id="paymentdetailsdiv2" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                                    <tr>
                                                                                                      <td width="21%" style="text-align:left">Cheque Date: </td>
                                                                                                      <td width="33%" style="text-align:left"><input name="chequedate" type="text" class="swifttext-mandatory" id="DPC_chequedate" size="30" autocomplete="off" value=""  readonly="readonly"/></td>
                                                                                                      <td width="16%" style="text-align:left">Cheque No:</td>
                                                                                                      <td width="30%" style="text-align:left"><input name="chequeno" type="text" class="swifttext-mandatory" id="chequeno" size="30" maxlength="12" autocomplete="off" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td style="text-align:left">Drawn On:</td>
                                                                                                      <td style="text-align:left"><input name="drawnon" type="text" class="swifttext-mandatory" id="drawnon" autocomplete="off"  style="width:192px;"/></td>
                                                                                                      <td>Deposit Date:</td>
                                                                                                      <td><input name="depositdate" type="text" class="swifttext" id="DPC_depositdate" size="30" maxlength="12" autocomplete="off" /></td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                          </table>
                                                                                        </div></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td colspan="3"><div id="willpaylater" style="display:none">
                                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                            <tr>
                                                                                              <td width="256" valign="top">Due Date:<br />
                                                                                                <br />
                                                                                                <input name="DPC_duedate" type="text" class="swifttext-mandatory" id="DPC_duedate" size="20" maxlength="12" autocomplete="off"   value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly"/></td>
                                                                                              <td width="420">Reason:<br />
                                                                                                <br />
                                                                                                <input name="remarks" type="text" class="swifttextareanew" id="remarks" maxlength="100" style="width:450px"/></td>
                                                                                            </tr>
                                                                                          </table>
                                                                                        </div></td>
                                                                                    </tr>
                                                                                  </table>
                                                                                </div></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="64%" height="35px;"><div id="form-error"></div></td>
                                                          <td width="36%"><div align="right" style="display:none;">
                                                              <input type="checkbox" name="invoicedated" id="invoicedated" checked="checked" />
                                                              Invoice Dated 31-03-2011 </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="69%">&nbsp;</td>
                                                                <td width="31%"><input name="newentry" type="button" class= "swiftchoicebutton" id="newentry" value="New" onclick="newinvoiceentry();" />
                                                                  &nbsp;&nbsp;&nbsp;
                                                                  <input name="preview" type="button" class= "swiftchoicebutton" id="preview" value="Preview"  onclick="previewinvoice();"/></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                              </div>
                                              <div style="display:none;" >
                                                <div id="viewinvoicediv" style="width:650px; height:150px;" >
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3"  bgcolor="#FFEAEA" style="border:1px solid #FFA8A8" height="130px;">
                                                    <tr >
                                                      <td ><div align="center"><strong style="font-size:14px">Transaction Successfull click here to <a  class="resendtext" style="cursor:pointer" onclick="viewinvoice('');">view invoice</a></strong> </div></td>
                                                    </tr>
                                                  </table>
                                                    <div align="right" style="padding-top:15px; padding-right:25px"><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/> </div>
                                                </div>
                                              </div></td>
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
                      <td height="30px;" colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3"><div align="right"><a onclick="generateinvoicedetails('');" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh Invoice Data"  /></a></div></td>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                    </tr>
                    <tr>
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><div style="display:none">
                                <div id="invoicepreviewdiv" style="width:650px; height:600px; overflow:auto">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td colspan="2"><div align="center"><font color="#FF0000"><strong>PREVIEW (Please verify and Proceed)</strong></font></div></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><table width="98%" cellspacing="0" cellpadding="4" border="0" class="table-border-grid">
                                          <tbody>
                                            <tr class="tr-grid-header">
                                              <td valign="top" align="left" colspan="2" width="60%"><strong>Customer Details</strong></td>
                                              <td width="40%" valign="top" align="left"><strong>Invoice Details</strong></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Customer ID:</strong> <span id="customeridpreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><strong>Date:</strong> <span id="invoicedatepreview"> </span></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="left" class="td-border-grid" colspan="2" height="40px" id="companynamepreview" nowrap="nowrap">&nbsp;</td>
                                              <td width="49%" valign="top" align="left" class="td-border-grid"><span style="text-align: left;"><strong>Inv No:</strong> <span id="billnumber" style="text-align: right;">Not Available</span></span></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="left" class="td-border-grid" colspan="2" id="addresspreview">&nbsp;</td>
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Marketing Exe:</strong> <span  id="marketingexepreview"></span></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Contact Person:</strong><span id="contactpersonpreview"></span></td>
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Service Tax No:</strong> AABCR7796NST001</td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Email :</strong>&nbsp;<span id="emailpreview"></span></td>
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Region: </strong>**********<span id="branch"></span></td>
                                            </tr>
                                            <tr>
                                              <td width="29%" class="td-border-grid" style="text-align: left;"><strong>Phone:</strong>&nbsp;<span id="phonepreview" ></span></td>
                                              <td width="22%" class="td-border-grid" style="text-align: left;"><strong>Cell:</strong>&nbsp;<span id="cellpreview"> </span></td>
                                              <td class="td-border-grid" style="text-align: left;"><strong>Company's PAN:</strong> AABCR7796N</td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Type of Customer: </strong>&nbsp;<span id="custypepreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><strong>Company's VAT TIN:</strong> 29730052233</td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Category of Customer: </strong>&nbsp; <span id="cuscategorypreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><strong>CST No:</strong> 71684955 &nbsp; <strong>w.e.f. :</strong> 16/1/2001<br></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>PO Date: </strong>&nbsp; <span id="podatepreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><strong>PO Reference:</strong> &nbsp; <span id="poreferencepreview"></span></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="left" colspan="3"  class="td-border-grid"><table width="100%" cellspacing="0" cellpadding="0" border="0" >
                                                  <tbody>
                                                    <tr >
                                                      <td width="23%" ><table width="98%" border="0" cellspacing="0" cellpadding="4" id="previewproductgrid" align="center" class="grey-table-border-grid">
                                                        </table>
                                                        <br></td>
                                                    </tr>
                                                  </tbody>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" class="td-border-grid" style="text-align: left; " colspan="2"><table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                  <tbody>
                                                    <tr>
                                                      <td><div align="left"><strong>Invoice Remarks: </strong>&nbsp;<span id="invoiceremarkspreview"></span></div></td>
                                                    </tr>
                                                    <tr>
                                                      <td><div align="left"><strong> Payment Remarks: </strong>&nbsp;<span id="paymentremarkspreview"></span></div></td>
                                                    </tr>
                                                  </tbody>
                                                </table></td>
                                              <td class="td-border-grid" style="text-align: left; "><div align="center"><font color="#ff0000">For <strong>RELYON SOFTECH LTD</strong></font> <br>
                                                  <br>
                                                  <br>
                                                  <span id="generatedbypreview">
                                                  <?php  echo($dealername); ?>
                                                  </span></div></td>
                                            </tr>
                                          </tbody>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td width="51%" valign="top" style="text-align: center; padding-top:10px;"><div id="proceedprocessingdiv"></div></td>
                                      <td width="49%" valign="top" style="text-align: center; padding-top:10px;"><div align="center">
                                          <input name="proceed" type="button" class= "swiftchoicebutton" id="proceed" value="Proceed"  onclick="checkforproduct();"/>
                                          &nbsp;&nbsp;  &nbsp;&nbsp;
                                          <input name="cancel" type="button" class= "swiftchoicebutton" id="cancel" value="Cancel"  onclick="cancelpurchase();"/>
                                        </div></td>
                                    </tr>
                                  </table>
                                </div>
                              </div></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="141" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','   Existing Invoices'); " style="cursor:pointer" class="grid-active-tabclass"><span style="padding-left:4px;">Invoice Details</span></td>
                                        <td width="4">&nbsp;</td>
                                        <td width="5" ></td>
                                        <td width="141" align="center" id="tabgroupgridh2"  style="cursor:pointer" class="grid-tabclass" onclick="gridtab2('2','tabgroupgrid','   Registration');generatecustomerregistration('',''); ">Registration</td>
                                        <td width="408"><div id="gridprocessing" style="display:none;"></div></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td><form action="" method="post" name="submitformgrid" id="submitformgrid" onsubmit="return false;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                      <tr>
                                        <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Existing Invoices</div></td>
                                        <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span><span id="tabgroupgridwb2"></span><span id="tabgroupgridwb3"></span></td>
                                        <td width="4%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                        <td width="8%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:704px; padding:2px;" align="center">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                              <tr>
                                                <td align="center"><div id="invoicedetailsgridc1_1" >
                                                    <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;">
                                                      <tr class="tr-grid-header">
                                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
                                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
                                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
                                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
                                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td>
                                                        <td nowrap = "nowrap" class="td-border-grid" align="left">Action
                                                          <input type="hidden" name="invoicelastslno" id="invoicelastslno" /> <input type="hidden" name="filepathinvoicing" id="filepathinvoicing" /></td>
                                                     <td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
                                                      <td nowrap = "nowrap" class="td-border-grid" align="left">SEZ Download</td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="8" valign="top" class="td-border-grid"><div align="center">No datas found to display</div></td>
                                                      </tr>
                                                    </table>
                                                    <div id="invoicedetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                                  </div></td>
                                              </tr>
                                              <tr>
                                                <td ><div id="invoicedetailsgridc1link" style="height:20px;  padding:2px;" align="left"> </div></td>
                                              </tr>
                                            </table>
                                            <div id="invoicedetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center"></div>
                                          </div>
                                          <div id="tabgroupgridc2" style="overflow:auto; height:150px; width:704px; padding:2px; display:none;" align="center">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                              </tr>
                                              <tr>
                                                <td><div id="tabgroupgridc1link" style="height:20px; padding:2px;" align="left"> </div></td>
                                              </tr>
                                            </table>
                                            <div id="regresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center"></div>
                                          </div></td>
                                      </tr>
                                    </table> </form></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><div style="display:none">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td><div id='inline_example1' style='background:#fff; width:650px'>
                                        <form action="" method="post" name="savesubmitform" id="savesubmitform" onsubmit="return false;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                  <tr>
                                                    <td width="100%" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td width="28%" align="left" bgcolor="#F7FAFF" class="company_font">Business Name [Company]:</td>
                                                                      <td width="72%" align="left" bgcolor="#F7FAFF"><input name="businessname" type="text" class="swifttext-mandatory  type_enter focus_redclass reverser_class" id="businessname" size="70" maxlength="100"  autocomplete="off" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                    <tr>
                                                                      <td width="2%" height="25" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%" onclick="tabopen5('1','tabg1')" class="producttabheadactive" id="tabg1h1" style="cursor:pointer;"><div align="center"><strong>General Details</strong></div></td>
                                                                      <td width="2%" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%" onclick="tabopen5('2','tabg1')" class="producttabhead" id="tabg1h2" style="cursor:pointer;"><div align="center"><strong>Contact Details</strong></div></td>
                                                                      <td width="2%" class="producttabhead">&nbsp;</td>
                                                                      <td width="18%" class="producttabhead" >&nbsp;</td>
                                                                      <td width="2%" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%"  class="producttabhead" >&nbsp;</td>
                                                                      <td width="2%" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%"  class="producttabhead">&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td><div style="display:block; "  align="justify" id="tabg1c1" >
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="225px">
                                                                      <tr valign="top">
                                                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                            <tr>
                                                                              <td colspan="2" height="10px" ></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td  colspan="2" valign="top" style="padding:0px" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                  <tr>
                                                                                    <td width="17%" align="left" bgcolor="#edf4ff" style="padding:4px">Address:</td>
                                                                                    <td width="83%" align="left" bgcolor="#edf4ff" style="padding-bottom:3px; padding-right:3px; padding-top:3px; padding-left:1px"><input name="address" type="text" class="swifttext  type_enter focus_redclass" id="address" size="82" maxlength="200"  autocomplete="off" /></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                            <tr>
                                                                              <td width="51%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                  <tr>
                                                                                    <td bgcolor="#F7FAFF">Place:</td>
                                                                                    <td bgcolor="#F7FAFF"><input name="place" type="text" class="swifttext-mandatory type_enter focus_redclass" id="place" size="30" maxlength="100"  autocomplete="off"/>
                                                                                      <input type="hidden" id="cuslastslno" name="cuslastslno" />
                                                                                      <span style="padding-bottom:3px; padding-right:3px; padding-top:3px; padding-left:1px"> </span></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td width="33%" bgcolor="#edf4ff">State:</td>
                                                                                    <td width="67%" bgcolor="#edf4ff"><select name="state" class="swiftselect-mandatory type_enter focus_redclass" id="state" onchange="getdistrict('districtcodedisplay',this.value);" onkeyup="getdistrict('districtcodedisplay',this.value);"  style="width:200px;">
                                                                                        <option value="">Select A State</option>
                                                                                        <?php include('../inc/state.php'); ?>
                                                                                      </select></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td bgcolor="#F7FAFF">District:</td>
                                                                                    <td bgcolor="#F7FAFF" id="districtcodedisplay"><select name="district" class="swiftselect-mandatory type_enter focus_redclass" id="district" style="width:200px;">
                                                                                        <option value="">Select A State First</option>
                                                                                      </select></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td bgcolor="#edf4ff">Pin Code:</td>
                                                                                    <td bgcolor="#edf4ff"><input name="pincode" type="text" class="swifttext-mandatory type_enter focus_redclass" id="pincode" size="30" maxlength="10"  autocomplete="off"/></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td align="left" valign="top">STD Code:</td>
                                                                                    <td align="left" valign="top"><input name="stdcode" type="text" class="swifttext type_enter focus_redclass" id="stdcode" size="30" maxlength="10"  autocomplete="off"/></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td align="left" valign="top" bgcolor="#EDF4FF">Fax:</td>
                                                                                    <td align="left" valign="top" bgcolor="#EDF4FF"><input name="fax" type="text" class="swifttext type_enter focus_redclass" id="fax" size="30" maxlength="80"  autocomplete="off"/></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                              <td width="49%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                  <tr>
                                                                                    <td bgcolor="#F7FAFF">Website</td>
                                                                                    <td bgcolor="#F7FAFF"><input name="website" type="text" class="swifttext type_enter focus_redclass" id="website" size="30" maxlength="80" autocomplete="off" /></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td  bgcolor="#edf4ff">Type:</td>
                                                                                    <td  bgcolor="#edf4ff"><select name="type" class="swiftselect-mandatory  type_enter focus_redclass" id="type" style="width:170px">
                                                                                        <option value="" selected="selected">Type Selection</option>
                                                                                        <?php 
											include('../inc/custype.php');
											?>
                                                                                      </select></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td bgcolor="#F7FAFF">Category:</td>
                                                                                    <td bgcolor="#F7FAFF"><select name="category" class="swiftselect-mandatory type_enter focus_redclass" id="category"  style="width:170px">
                                                                                        <option value="">Category Selection</option>
                                                                                        <?php 
											include('../inc/category.php');
											?>
                                                                                      </select></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td width="40%" bgcolor="#edf4ff">Remarks:</td>
                                                                                    <td width="60%" bgcolor="#edf4ff"><textarea name="remarks" cols="20" class="swifttext type_enter focus_redclass generallastfield" id="remarks"></textarea></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td bgcolor="#F7FAFF">Customer ID:</td>
                                                                                    <td bgcolor="#F7FAFF">Not Avaliable</td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table></td>
                                                                      </tr>
                                                                    </table>
                                                                  </div>
                                                                  <div style="display:none;" align="justify" id="tabg1c2">
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="225px">
                                                                      <tr>
                                                                        <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0" >
                                                                            <tr>
                                                                              <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                  <tr>
                                                                                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#646464; font-weight:bold">
                                                                                        <tr>
                                                                                          <td width="2%">&nbsp;</td>
                                                                                          <td width="19%" ><div align="center">Type</div></td>
                                                                                          <td width="17%"><div align="center">Name</div></td>
                                                                                          <td width="20%"><div align="center">Phone</div></td>
                                                                                          <td width="13%"><div align="center">Cell</div></td>
                                                                                          <td width="25%"><div align="center">Email Id</div></td>
                                                                                          <td width="4%">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="3"  id="adddescriptioncontactrows">
                                                                                        <tr id="removedescriptioncontactrow1">
                                                                                          <td width="5%"><div align="left"><strong>1</strong></div></td>
                                                                                          <td width="11%"><div align="center">
                                                                                              <select name="selectiontype1" id="selectiontype1" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass ">
                                                                                                <option value="" selected="selected" >--Select--</option>
                                                                                                <option value="general" >General</option>
                                                                                                <option value="gm/director">GM/Director</option>
                                                                                                <option value="hrhead">HR Head</option>
                                                                                                <option value="ithead/edp">IT-Head/EDP</option>
                                                                                                <option value="softwareuser" >Software User</option>
                                                                                                <option value="financehead">Finance Head</option>
                                                                                                <option value="others" >Others</option>
                                                                                              </select>
                                                                                            </div></td>
                                                                                          <td width="16%"><div align="center">
                                                                                              <input name="name1" type="text" class="swifttext type_enter focus_redclass" id="name1"  style="width:110px"  maxlength="70"  autocomplete="off"/>
                                                                                            </div></td>
                                                                                          <td width="18%"><div align="center">
                                                                                              <input name="phone1" type="text"class="swifttext type_enter focus_redclass" id="phone1" style="width:95px" maxlength="100"  autocomplete="off" />
                                                                                            </div></td>
                                                                                          <td width="15%"><div align="center">
                                                                                              <input name="cell1" type="text" class="swifttext type_enter focus_redclass" id="cell1" style="width:95px"  maxlength="10"  autocomplete="off"/>
                                                                                            </div></td>
                                                                                          <td width="27%"><div align="center">
                                                                                              <input name="emailid1" type="text" class="swifttext type_enter focus_redclass default" id="emailid1" style="width:130px"  maxlength="100"  autocomplete="off"/>
                                                                                            </div></td>
                                                                                          <td width="8%"><font color = "#FF0000"><strong><a id="removecontactrowdiv1" onclick ="removedescriptionrowscontact('removedescriptioncontactrow1','1')" style="cursor:pointer;">X</a></strong></font>
                                                                                            <input type="hidden" name="contactslno1" id="contactslno1" /></td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td><div align="left" id="adddescriptioncontactrowdiv">
                                                                                        <div align="right"><a onclick="adddescriptioncontactrows();" style="cursor:pointer" class="r-text">Add one More >></a></div>
                                                                                      </div></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table></td>
                                                                      </tr>
                                                                    </table>
                                                                  </div></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td style="padding-top:5px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td colspan="2" height="5px"></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="35" align="left" valign="middle" id="form-error">&nbsp;</td>
                                                                <td align="right" valign="middle">&nbsp;&nbsp;&nbsp;
                                                                  <input name="new" type="button" class= "swiftchoicebutton focus_redclass" id="new" value="New" onclick="newentrycontact(); document.getElementById('form-error').innerHTML = '';rowwdelete()" />
                                                                  &nbsp;
                                                                  &nbsp;
                                                                  <input name="save" type="button" class="swiftchoicebutton focus_redclass saveclass" id="save" value="Save" onclick="formsubmitcontact('save');" />
                                                                  &nbsp; &nbsp;<input type="button" value="Close" id="closecolorboxbutton2"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
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
      </table></td>
  </tr>
</table>
<script>
gettotalcustomercount();
</script>
<div id="seztaxuploaddiv" style="display:none;">
  <?php include('../inc/seztaxuploaddiv.php'); ?>
</div>
<?php }?>
