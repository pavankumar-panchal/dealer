<?
include('../inc/eventloginsert.php');
$query = "select forcesurrender from inv_mas_dealer where slno = '".$userid."'";
$fetch = runmysqlqueryfetch($query);
$forcesurrender = $fetch['forcesurrender'];
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<link href="../style/shortkey.css?dummy=<? echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox-invoicing.css?dummy=<? echo (rand());?>"  />
<script language="javascript"  src="../functions/cus-cardsearch.js?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/javascript.js?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/cus-regcardsearch.js?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/customer.js?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/getdistrictjs.php?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/getdistrictfunction.php?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/clipboardcopy.js?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/colorbox.js?dummy=<? echo (rand());?>" ></script>
<script language="javascript" src="../functions/enter_keyshortcut.js?dummy=<? echo (rand());?>" ></script>
<script language="javascript" src="../functions/key_shortcut.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/customer-shortkey.js?dummy=<? echo (rand());?>"></script>
<? $userid = imaxgetcookie('dealeruserid');?>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="16%" align="top" class="active-leftnav">Customer Details</td>
                            <td width="43%" align="top"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="23%" valign="top"><div align="left" style="padding:2px">
                                <div align="right">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);"  maxlength="20"  autocomplete="off" style="width:125px"/>
                                  <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div>
                              </div></td>
                            <td width="18%" >&nbsp;
                              <input name="search" type="submit" class="swiftchoicebuttonbig" id="search" value="Advanced Search"  onclick="displayDiv('1','filterdiv')"  /></td>
                          </tr>
                        </table></td>
                    </tr>
                    <? $query = "select inv_mas_dealer.businessname as dealername,inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_state.statename from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$userid."'";
						$fetch = runmysqlqueryfetch($query);
						$dealername = $fetch['dealername'];
						$relyonexecutive = $fetch['relyonexecutive'];
						$statename = $fetch['statename'];
						if($relyonexecutive == 'no')
						$displaybusinessname = ' belonging to '.$dealername;
						else
						{
							$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') order by businessname;";
							$result = runmysqlquery($query);
							if(mysqli_num_rows($result) == 0)
							{
								$query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname 
					from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
					where inv_mas_district.statecode = '".$state."' order by businessname;";
								$result = runmysqlquery($query);
								$fetch = mysqli_fetch_array($result);
								$displaybusinessname = 'of '.$statename;
							}
							else
							$displaybusinessname = 'as per District Mapping';
						}
					 ?>
                    <tr>
                      <td style="padding-top:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px dashed #999999">
                          <tr>
                            <td bgcolor="#E1F0FF" ><div align="center"><strong>"You have been listed with Customers <? echo($displaybusinessname); ?>"</strong></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><div id="filterdiv" style="display:none;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                            <tr>
                              <td valign="top"><div>
                                  <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="100%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Search Option</td>
                                      </tr>
                                      <tr>
                                        <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFD3A8" style="border:dashed 1px #545429">
                                            <tr>
                                              <td width="57%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" style=" border-right:1px solid #CCCCCC">
                                                  <tr>
                                                    <td colspan="3" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="9%" align="left" valign="middle">Text: </td>
                                                          <td width="91%" colspan="3" align="left" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="35" maxlength="60" class="swifttext"  autocomplete="off" value=""/>
                                                            <span style="font-size:9px; color:#999999; padding:1px">(Leave Empty for all)</span></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr >
                                                    <td style="padding:1px" height="2">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="34%" valign="top" style="padding-right:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 1px #004000"  align="left">
                                                              <tr>
                                                                <td align="left"><strong>Look in:</strong></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield0" value="slno"/>
                                                                  </label>
                                                                  <label for="databasefield0">Customer ID</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                                  </label>
                                                                  <label for="databasefield1"> Business Name</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="contactperson" id="databasefield3" />
                                                                  </label>
                                                                  <label for="databasefield3">Contact Person</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield5" value="place" />
                                                                  </label>
                                                                  <label for="databasefield5"> Place</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="phone" id="databasefield4" />
                                                                  </label>
                                                                  <label for="databasefield4">Phone/ Cell</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                                  </label>
                                                                  <label for="databasefield6">Email</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="5"></td>
                                                              </tr>
                                                              <tr >
                                                                <td style="border-top:solid 1px #999999"  height="5"  ></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="cardid" id="databasefield9" />
                                                                  </label>
                                                                  <label for="databasefield9">Pin Serial No</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="scratchnumber" id="databasefield7" />
                                                                  </label>
                                                                  <label for="databasefield7">Pin No</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="billno" id="databasefield11" />
                                                                  </label>
                                                                  <label for="databasefield11">Bill No</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="computerid" id="databasefield8" />
                                                                  </label>
                                                                  <label for="databasefield8">Computer ID</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="softkey" id="databasefield10" />
                                                                  </label>
                                                                  <label for="databasefield10">Softkey</label></td>
                                                              </tr>
                                                            </table></td>
                                                          <td width="66%" valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="6" style="border-left:solid 1px #cccccc; border-bottom:solid 1px #cccccc; border-top:solid 1px #cccccc ">
                                                              <tr>
                                                                <td colspan="2" align="left"><strong>Selections</strong>:</td>
                                                              </tr>
                                                              <tr>
                                                                <td width="21%" height="10" align="left" valign="top">Region:</td>
                                                                <td width="79%" height="10" align="left" valign="top"><select name="region2" class="swiftselect" id="region2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? 
											include('../inc/region.php');
											?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top" height="10" >State:</td>
                                                                <td align="left" valign="top" height="10"><select name="state2" class="swiftselect" id="state2" onchange="getdistrictfilter('districtcodedisplaysearch',this.value);" onkeyup="getdistrictfilter('districtcodedisplaysearch',this.value);" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/state.php'); ?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> District:</td>
                                                                <td align="left" valign="top"  id="districtcodedisplaysearch" height="10" ><select name="district2" class="swiftselect" id="district2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Dealer:</td>
                                                                <td align="left" valign="top"   height="10"><select name="currentdealer2" class="swiftselect" id="currentdealer2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/firstdealer.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Branch:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="branch2" class="swiftselect" id="branch2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/branch.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left";> Type:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="type2" class="swiftselect" id="type2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <option value="Not Selected">Not Selected</option>
                                                                    <? include('../inc/custype.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Category:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="category2" class="swiftselect" id="category2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <option value="Not Selected">Not Selected</option>
                                                                    <? include('../inc/category.php');?>
                                                                  </select></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td height="35" align="left" valign="middle"  ><div id="filter-form-error"></div></td>
                                                  </tr>
                                                </table></td>
                                              <td width="43%" valign="top" style="padding-left:3px" align="left"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                  <tr>
                                                    <td colspan="4" valign="top" style="padding:0"></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:230px; overflow:scroll" align="left">
                                                        <? include('../inc/productdetails.php'); ?>
                                                      </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="20%" align="left">Select: </td>
                                                    <td width="51%" align="left"><strong>
                                                      <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                                        <option value="ALL"  selected="selected">ALL</option>
                                                        <option value="NONE">NONE</option>
                                                        <? include('../inc/productgroup.php') ?>
                                                      </select>
                                                      </strong></td>
                                                    <td width="29%" align="left"></td>
                                                  </tr>
                                                  <tr >
                                                    <td  colspan="3" align="left" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="21%">&nbsp;</td>
                                                          <td width="79%" align="left"><a onclick="selectdeselectall('one');"><strong class="resendtext" style="cursor:pointer">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                          <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" height="25">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="20" colspan="3" ><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="searchdealerarray();" />
                                                      &nbsp;
                                                      <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);">
                                                      &nbsp;
                                                      <input name="close" type="button" class="swiftchoicebutton" id="close" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" /></td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td width="100%" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td align="left" bgcolor="#F7FAFF" class="company_font">Business Name [Company]:</td>
                                                        <td align="left" bgcolor="#F7FAFF"><input name="businessname" type="text" class="swifttext-mandatory  type_enter focus_redclass reverser_class" id="businessname" size="76" maxlength="100"  autocomplete="off" /></td>
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
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="300px">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                              <tr>
                                                                <td colspan="2" valign="top"></td>
                                                              </tr>
                                                              <tr>
                                                                <td  colspan="2" valign="top" style="padding:0px" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="17%" align="left" bgcolor="#edf4ff" style="padding:4px">Address:</td>
                                                                      <td width="83%" align="left" bgcolor="#edf4ff" style="padding-bottom:3px; padding-right:3px; padding-top:3px; padding-left:1px"><input name="address" type="text" class="swifttext  type_enter focus_redclass" id="address" size="87" maxlength="200"  autocomplete="off" />
                                                                        <input type="hidden" name="lastslno" id="lastslno" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td width="51%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td bgcolor="#F7FAFF">Place:</td>
                                                                      <td bgcolor="#F7FAFF"><input name="place" type="text" class="swifttext-mandatory type_enter focus_redclass" id="place" size="30" maxlength="100"  autocomplete="off"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td width="33%" bgcolor="#edf4ff">State:</td>
                                                                      <td width="67%" bgcolor="#edf4ff"><select name="state" class="swiftselect-mandatory type_enter focus_redclass" id="state" onchange="getdistrict('districtcodedisplay',this.value);getgstcode(this.value);" onkeyup="getdistrict('districtcodedisplay',this.value);getgstcode(this.value);"  style="width:200px;">
                                                                          <option value="">Select A State</option>
                                                                          <? include('../inc/state.php'); ?>
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
                                                                    <tr>
                                                                      <td bgcolor="#F7FAFF">Website</td>
                                                                      <td bgcolor="#F7FAFF"><input name="website" type="text" class="swifttext type_enter focus_redclass" id="website" size="30" maxlength="80" autocomplete="off" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td  bgcolor="#edf4ff">Type:</td>
                                                                      <td  bgcolor="#edf4ff"><select name="type" class="swiftselect  type_enter focus_redclass" id="type" style="width:200px">
                                                                          <option value="" selected="selected">Type Selection</option>
 <? 
											//include('../inc/custype.php');

	$query = "SELECT slno,customertype FROM inv_mas_customertype ORDER BY customertype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
                if($fetch['customertype'] != 'Others') {
		echo('<option value="'.$fetch['slno'].'">'.$fetch['customertype'].'</option>');
               }
               else {
		echo('<option value="'.$fetch['slno'].'" disabled>'.$fetch['customertype'].'</option>');
              }
	}
											?>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td bgcolor="#F7FAFF">Categories:</td>
                                                                      <td bgcolor="#F7FAFF"><select name="category" class="swiftselect type_enter focus_redclass" id="category"  style="width:200px">
                                                                          <option value="">Category Selection</option>
                                            <? 
											//include('../inc/category.php');


	$query = "SELECT slno,businesstype FROM inv_mas_customercategory ORDER BY businesstype";
	$result = runmysqlquery($query);
	while($fetch = mysqli_fetch_array($result))
	{
                if($fetch['businesstype'] != 'Others') {
		   echo('<option value="'.$fetch['slno'].'">'.$fetch['businesstype'].'</option>');
                }
               else {
		echo('<option value="'.$fetch['slno'].'" disabled>'.$fetch['businesstype'].'</option>');
              }
	}


											?>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr bgcolor="#edf4ff">
                                                                      <td height="19" align="left" valign="top" bgcolor="#edf4ff">Created Date:</td>
                                                                      <td align="left" valign="top" bgcolor="#edf4ff" id="createddate">Not Available</td>
                                                                    </tr>
                                                                    <tr bgcolor="#edf4ff">
                                                                      <td align="left" valign="top" bgcolor="#F7FAFF">Branch:</td>
                                                                      <td align="left" valign="top" bgcolor="#F7FAFF" id="branchdisplay">&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                                <td width="49%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td bgcolor="#edf4ff">Remarks:</td>
                                                                      <td bgcolor="#edf4ff"><textarea name="remarks" cols="27" class="swifttextarea type_enter focus_redclass" id="remarks"></textarea></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td bgcolor="#F7FAFF">Customer ID:</td>
                                                                      <td bgcolor="#F7FAFF"><input name="customerid" type="text" class="swifttext" id="customerid" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                                                    </tr>
                                                                    <tr bgcolor="#EDF4FF">
                                                                      <td align="left" valign="top" bgcolor="#EDF4FF">Current Dealer:</td>
                                                                      <td align="left" valign="top" bgcolor="#EDF4FF"><input name="currentdealer" type="text" class="swifttext" id="currentdealer" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                                                    </tr>
                                                                    <tr height="20px">
                                                                      <td bgcolor="#F7FAFF">&nbsp;GSTIN:</td>
                                                                      <td bgcolor="#F7FAFF"><span id="state_gst" style="visibility:hidden"></span>
                                                                      <input name="gst_no" type="text" id="gst_no" style="float: left;" class="swiftselect type_enter focus_redclass" size="23" placeholder="Please Enter GSTIN number" onkeyup="changeToUpperCase(this)"/>
                                                                      </td>
                                                                    </tr>
                                                                        <tr height="20px">
                                                                            <td bgcolor="#F7FAFF">&nbsp;Effective From:</td>
                                                                            <td bgcolor="#F7FAFF">
                                                                                <input name="effective_from" type="date" id="effective_from" style="float: left;" class="swiftselect type_enter focus_redclass" size="23"/>
                                                                            </td>
                                                                        </tr>
                                                                        <tr height="20px">
                                                                            <td bgcolor="#F7FAFF">&nbsp;TAN NO:</td>
                                                                            <td bgcolor="#F7FAFF">
                                                                                <input name="tanno" type="text" id="tanno" style="float: left;" class="swiftselect type_enter focus_redclass" size="23" placeholder="Please Enter TAN number"/>
                                                                            </td>
                                                                        </tr>
                                                                    <tr height="20px">
                                                                      <td bgcolor="#F7FAFF">&nbsp;SEZ:</td>
                                                                      <td bgcolor="#F7FAFF">
                                                                          <input type="radio" name="sez_enabled" id="sez_enabled_yes" value="yes"> Yes  
                                                                          <input type="radio" name="sez_enabled" id="sez_enabled_no" value="no" checked> No
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                          <tr>
                                                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                                <tr bgcolor="#edf4ff">
                                                                                  <td height="19" align="left" valign="top" bgcolor="#edf4ff">Active Customer:</td>
                                                                                  <td align="left" valign="top" bgcolor="#edf4ff" id="activecustomer">&nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="77%" align="left" valign="top" bgcolor="#F7FAFF">Disable Login:</td>
                                                                                  <td width="23%" align="left" valign="top" bgcolor="#F7FAFF" id="disablelogin"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td bgcolor="#edf4ff">Company Closed:</td>
                                                                                  <td bgcolor="#edf4ff"><input type="checkbox" name="companyclosed" id="companyclosed" class="type_enter focus_redclass generallastfield" /></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td bgcolor="#F7FAFF">Promotional SMS:</td>
                                                                                  <td bgcolor="#F7FAFF"><input type="checkbox" name="promotionalsms" id="promotionalsms" disabled="disabled" class="focus_redclass" /></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td bgcolor="#edf4ff">Promotional Email:</td>
                                                                                  <td bgcolor="#edf4ff"><input type="checkbox" name="promotionalemail" id="promotionalemail" disabled="disabled" class="focus_redclass" /></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left" valign="top" bgcolor="#F7FAFF">Corporate Order:</td>
                                                                                  <td align="left" valign="top" bgcolor="#F7FAFF" id="corporateorder"></td>
                                                                                </tr>
                                                                              </table></td>
                                                                            <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                  <td height="15px" valign="top"  bgcolor="#F7FAFF"><div align="center"><strong>2021-22 Summary</strong></div></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td><div id="salessummary">
                                                                                      <table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid">
                                                                                        <tr class="tr-grid-header">
                                                                                          <td width="22%" class="td-border-grid">&nbsp;</td>
                                                                                          <td width="24%" class="td-border-grid" align="center"><strong>Bill</strong></td>
                                                                                          <td width="25%" class="td-border-grid" align="center"><strong>PIN</strong></td>
                                                                                          <td width="29%" class="td-border-grid" align="center"><strong>Regn</strong></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF"><strong>XBRL</strong></td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff"><strong>TDS</strong></td>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td>
                                                                                        </tr>
                                                                                        <!--<tr>
                                                                                          <td class="td-border-grid"  bgcolor="#F7FAFF"><strong>SVI</strong></td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff"><strong>SVH</strong></td>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td>
                                                                                        </tr>
                                                                                        -->
                                                                                        <tr>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF"><strong>STO</strong></td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff"><strong>SPP</strong></td>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF"><strong>GST</strong></td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff"><strong>SAC</strong></td>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td>
                                                                                          <td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table>
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
                                                    </div>
                                                    <div style="display:none;" align="justify" id="tabg1c2">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="300px">
                                                        <tr>
                                                          <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0"  >
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
                                                                      <td ><table width="100%" border="0" cellspacing="0" cellpadding="3"  id="adddescriptionrows">
                                                                          <tr id="removedescriptionrow1">
                                                                            <td width="5%"><div align="left"><strong>1</strong></div></td>
                                                                            <td width="11%"><div align="center">
                                                                                <select name="selectiontype1" id="selectiontype1" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass">
                                                                                  <option value="" selected="selected" >--Select--</option>
                                                                                  <option value="general" >General</option>
                                                                                  <option value="gm/director">GM/Director</option>
                                                                                  <option value="hrhead">HR Head</option>
                                                                                  <option value="ithead/edp">IT-Head/EDP</option>
                                                                                  <option value="softwareuser" >Software User</option>
                                                                                  <option value="financehead">Finance Head</option>
                                                                                   <option value="CA" >CA</option>
                                                                                   <option value="manager" >MANAGER</option>
                                                                                  <option value="others" >Others</option>
                                                                                </select>
                                                                              </div></td>
                                                                            <td width="16%"><div align="center">
                                                                                <input name="name1" type="text" class="swifttext type_enter focus_redclass" id="name1"  style="width:115px"  maxlength="70"  autocomplete="off"/>
                                                                              </div></td>
                                                                            <td width="18%"><div align="center">
                                                                                <input name="phone1" type="text"class="swifttext type_enter focus_redclass" id="phone1" style="width:110px" maxlength="100"  autocomplete="off" />
                                                                              </div></td>
                                                                            <td width="15%"><div align="center">
                                                                                <input name="cell1" type="text" class="swifttext type_enter focus_redclass" id="cell1" style="width:100px"  maxlength="10"  autocomplete="off"/>
                                                                              </div></td>
                                                                            <td width="27%"><div align="center">
                                                                                <input name="emailid1" type="text" class="swifttext type_enter focus_redclass default" id="emailid1" style="width:150px"  maxlength="200"  autocomplete="off"/>
                                                                              </div></td>
                                                                            <td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv1" onclick ="removedescriptionrows('removedescriptionrow1','1')" style="cursor:pointer;">X</a></strong></font>
                                                                              <input type="hidden" name="contactslno1" id="contactslno1" /></td>
                                                                          </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><div align="left" id="adddescriptionrowdiv">
                                                                          <div align="right"><a onclick="adddescriptionrows();" style="cursor:pointer" class="r-text">Add one More >></a></div>
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
                                            <td style="padding-top:5px"><table width="100%" border="0" cellspacing="0" cellpadding="0" height="70">
                                                <tr>
                                                  <td height="25" colspan="3" align="left" valign="middle"></span>
                                                    <div id="form-error"></div></td>
                                                </tr>
                                                <tr>
                                                  <td width="42%" height="35" align="left" valign="middle" id="profilepending"></td>
                                                  <td width="27%"  align="right" valign="middle"><textarea id="short_url" style="display:none;"></textarea>
                                                    <span id="info_copy_button"></span></td>
                                                  <td width="31%" align="center" valign="middle">&nbsp;&nbsp;&nbsp;
                                                    <input name="new" type="button" class= "swiftchoicebutton focus_redclass" id="new" value="New" onclick="newentry(); document.getElementById('form-error').innerHTML = '';cleargrid();rowwdelete()" />
                                                    &nbsp;
                                                    &nbsp;
                                                    <input name="save" type="button" class="swiftchoicebutton focus_redclass saveclass" id="save" value="Save" onclick="formsubmit('save');" />
                                                    &nbsp;</td>
                                                </tr>
                                              </table></td>
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
                      <td><form id="detailsform" name="detailsform" method="post" action="" onsubmit="return false">
                          <input type="hidden" name="onlineslno" id="onlineslno" />
                          <div align="left" style="display:block;background-color: #FFDFDF; height:20px; padding-top:5px;" id="detailsdiv"  >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td class="resendtext" style="width:150px"><div onclick="displayinvoicedetails('pinvoice')" ><a style="cursor:pointer"><strong style="padding-left:5px;" >View Invoice Details </strong></a></div></td>
                                <td class="resendtext"><div onclick="displayinvoicedetails('minvoice')" ><a style="cursor:pointer"><strong style="padding-left:5px;" >View Matrix Invoice Details </strong></a></div></td>
                              </tr>
                            </table>
                          </div>
                          <!-- Surrender details -->
                          <div style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr><!--  width:709px; -->
                                <td><div id="surrendergrid" style='background:#fff; font-size:11px;'>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC">
                                      <tr class="header-line">
                                        <td width="45%"><span style="padding-left:4px;">Surrender Details</span></td>
                                        <td width="24%"><span id="surrendergridwb1" style="text-align:center">&nbsp;</span></td>
                                        <td width="31%"><div align="right"></div></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3"><div style="overflow:auto;padding:0px; height:290px; width:709px;">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td align="center"><div id="surrendergridc7" > </div></td>
                                              </tr>
                                              <tr>
                                                <td ><div id="surrendergridc1link" style="height:20px;  padding:2px;" align="centre"> </div></td>
                                              </tr>
                                            </table>
                                          </div></td>
                                      </tr>
                                    </table>
                                    <!--<div id="surrendergridc7" style="overflow:auto; height:150px; width:704px; padding:2px; display:none;" align="center">No datas found to be displayed.</div>-->
                                    
                                    <div id="surrenderresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                      <div align="right" style="padding-top:15px; padding-right:25px">
                                        <input type="button" value="Close" id="closecolorboxbutton1"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>
                                      </div>
                                  </div></td>
                              </tr>
                            </table>
                          </div>
                          <div style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="invoicedetailsgrid" style='background:#fff; width:709px;'>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC">
                                      <tr class="header-line">
                                        <td width="45%"><span style="padding-left:4px;">Invoice Details</span></td>
                                        <td width="24%"><span id="invoicedetailsgridwb1" style="text-align:center">&nbsp;</span></td>
                                        <td width="31%"><div align="right"></div></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" align="center"><div style="overflow:auto;padding:0px; height:290px; width:709px; ">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td align="center"><div id="invoicedetailsgridc1_1" > </div></td>
                                              </tr>
                                              <tr>
                                                <td ><div id="invoicedetailsgridc1link" style="height:20px;  padding:2px;" align="centre"> </div></td>
                                              </tr>
                                            </table>
                                          </div></td>
                                      </tr>
                                    </table>
                                    <div id="invoicedetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                     <div align="right" style="padding-top:15px; padding-right:25px"><input type="button" value="Close" id="closecolorboxbutton2"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></div>
                                  </div></td>
                              </tr>
                            </table>
                          </div>
                          <div style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div class="wa" id="shortcut-grid">
                                    <div  class="wc">
                                      <table cellpadding="0" class="cf wf">
                                        <tbody>
                                          <tr>
                                            <td class="wk Dp">Keyboard shortcuts</td>
                                            <td class="wj Dp"></td>
                                          </tr>
                                          <tr>
                                            <td class="Dn"><table cellpadding="0" class="cf">
                                                <tbody>
                                                  <tr>
                                                    <th  class="Do"></th>
                                                    <th  class="Do">Customer Entry</th>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + N</span>:</td>
                                                    <td class="we Dn">New Entry</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + S</span>:</td>
                                                    <td class="we Dn">Save the Record</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + D</span>:</td>
                                                    <td class="we Dn">Delete the Record</td>
                                                  </tr>
                                                  <tr>
                                                    <th class="Do"></th>
                                                    <th class="Do">Tab operations</th>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + G</span>:</td>
                                                    <td class="we Dn">Current Registrations List</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + R</span>:</td>
                                                    <td class="we Dn">New Registration</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + P</span>:</td>
                                                    <td class="we Dn"> PIN Number Search</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + I</span>:</td>
                                                    <td class="we Dn"> Attached PIN Number</td>
                                                  </tr>
                                                  <tr>
                                                    <th class="Do"></th>
                                                    <th class="Do">Customer Selection</th>
                                                  </tr>
                                                  <!-- <tr>
                                                  <td class="wg Dn"><span class="wh">Alt + P</span></td>
                                                  <td class="we Dn">Focus on Customer Id search text</td>
                                                  </tr>-->
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + C</span></td>
                                                    <td class="we Dn">Focus on Customer selection box</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="wg Dn"><span class="wh">Alt + Shift + A</span></td>
                                                    <td class="we Dn">Advanced Search</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="2"><span class="wk Dp" style="font-weight:bold" >TIP:</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2"></span> <span class="we Dn">1. To view the list in a Drop-Down, press F4 button from the keyboard, while keeping the focus on that field.</span></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2"><span class="we Dn">2. Use
                                                      <Enter>
                                                      key to go to next field in customer master form. Use Shift + Enter to go reverse order.</span></td>
                                                  </tr>
                                                </tbody>
                                              </table></td>
                                            <td class="Dn">&nbsp;</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div></td>
                              </tr>
                            </table>
                          </div>
                        </form></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="140" align="center" id="tabgroupgridh1" onclick="gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations'); displayelement('tabgroupgridc1','transferscratchcarddiv');clearcarddetails();" style="cursor:pointer" class="grid-active-tabclass">Registration</td>
                                  <td width="2"></td>
                                  <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtabcus4('2','tabgroupgrid','&nbsp; &nbsp;Generate New Registration'); displayelement('tabgroupgridc2','transferscratchcarddiv');clearcarddetails();" style="cursor:pointer" class="grid-tabclass">New Registration</td>
                                  <td width="2"></td>
                                  <td width="140" align="center" id="tabgroupgridh3" onclick="gridtabcus4('3','tabgroupgrid','&nbsp; &nbsp;PIN Number Details'); displayelement('tabgroupgridc3','transferscratchcarddiv');" style="cursor:pointer" class="grid-tabclass">PIN  Search</td>
                                  <td width="2"><div id="gridprocessing" style="display:none;"></div></td>
                                  <td width="140" align="center" id="tabgroupgridh4" onclick="gridtabcus4('4','tabgroupgrid','&nbsp; &nbsp;PIN Number Details'); displayelement('tabgroupgridc4','transferscratchcarddiv');" style="cursor:pointer" class="grid-tabclass">Attached PIN Numbers</td>
                                  <?php if($forcesurrender == 'yes'){ ?>
                                    <td width="2"></td>
                                  <td width="140" align="center" id="tabgroupgridh5" onclick="gridtabcus4('5','tabgroupgrid','&nbsp; &nbsp;Force Surrender'); displayelement('tabgroupgridc5','transferscratchcarddiv');" style="cursor:pointer" class="grid-tabclass">Force Surrender</td>
                                  <?php } else { ?>
                                  <td width="2"></td>
                                  <td width="140" align="center" ></td>
                                  <?php } ?>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr>
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Current Registrations</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span><span id="tabgroupgridwb2"></span><span id="tabgroupgridwb3"></span></td>
                                  <td width="4%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                  <td width="8%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:704px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" style="height:20px; padding:2px;" align="left"> </div></td>
                                        </tr>
                                      </table>
                                      <div id="regresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center"></div>
                                    </div>
                                    <div id="tabgroupgridc2" style="overflow:auto; height:auto; width:704px; padding:2px; display:none;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><form id="registrationform" name = "registrationform" method="post" action="" onsubmit="return false;">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#f7faff">
                                                  <td colspan="3" align="left" valign="top" bgcolor="#f7faff" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td width="19%" align="left">Type of Registration:</td>
                                                        <td width="73%" ><label>
                                                            <input name="registrationfieldradio" type="radio" id="registrationfieldradio0" value="newlicence" checked="checked" onclick="document.getElementById('hiddenregistrationtype').value = 'newlicence'; validatemakearegistration();  clearregistrationform(); " disabled="disabled" />
                                                            New License</label>
                                                          <label>
                                                            <input type="radio" name="registrationfieldradio" id="registrationfieldradio1" value="updationlicense" onclick="document.getElementById('hiddenregistrationtype').value = 'updationlicense'; validatemakearegistration();   clearregistrationform(); " disabled="disabled" />
                                                            Updation License</label>
                                                          <input name="hiddenregistrationtype" type="hidden" id="hiddenregistrationtype" /></td>
                                                        <td width="8%"><span  ><a onclick="new_refreshcuscardarray();up_refreshcuscardarray();validatemakearegistration()" style="cursor:pointer; padding-left:5px;" ><img src="../images/refresh-card.gif"   alt="Refresh card" border="0" align="middle" title="Refresh card Details"  height="21" width="25" /></a></span></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td colspan="2" align="left" valign="top" style="padding:0px;"><div id="scratchdisplay" style="display:block;">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td width="30%" valign="top"><div align="left">PIN Serial Number:</div></td>
                                                          <td width="62%" valign="top"><div align="left">
                                                              <input name="scratchnumber" type="hidden" id="scratchnumber" />
                                                            </div>
                                                            <div id="dispreregcardlist">
                                                              <div align="left">
                                                                <input name="searchscratchnumber" type="text" class="swifttext" id="searchscratchnumber"  onkeyup="reg_cardsearch(event);" autocomplete="off" style="width:191px"/>
                                                                <select name="scratchcardlist" size="5" class="swiftselect" id="scratchcardlist" style="width:197px; height:75px" onclick="reg_selectcardfromlist();scratchdetailstoform(document.getElementById('scratchcardlist').value);"  >
                                                                </select>
                                                              </div>
                                                            </div></td>
                                                          <td width="8%" valign="top"><div align="left"></div></td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top"><div align="left">Product:</div></td>
                                                          <td><div align="left">
                                                              <input name="productname" type="text" class="swifttext" id="productname" size="30" readonly="readonly"  autocomplete="off"/>
                                                              <input name="productcode" type="hidden" id="productcode" />
                                                            </div></td>
                                                          <td><div align="left"></div></td>
                                                        </tr>
                                                        <tr bgcolor="#edf4ff">
                                                          <td align="left" valign="top"><div align="left">Dealer/Rep:</div></td>
                                                          <td align="left" valign="top"><div align="left">
                                                              <select name="delaerrep" class="swiftselect-mandatory" id="delaerrep" style="width:180px;" disabled="disabled">
                                                                <option value="">Make A Selection</option>
                                                                <? 
											include('../inc/firstdealer.php');
											?>
                                                              </select>
                                                            </div></td>
                                                          <td><div align="left"></div></td>
                                                        </tr>
                                                        <tr bgcolor="#edf4ff">
                                                          <td width="108" align="left" valign="top"><div align="left">Computer ID:</div></td>
                                                          <td width="260" align="left" valign="top"><div align="left">
                                                              <input name="computerid" type="text" class="swifttext" id="computerid" size="30"  autocomplete="off" maxlength="15"/>
                                                            </div></td>
                                                          <td><div align="left"></div></td>
                                                        </tr>
                                                        <tr bgcolor="#edf4ff">
                                                          <td align="left" valign="top"><div align="left">Bill Number:</div></td>
                                                          <td colspan="2" align="left" valign="top"><div align="left">
                                                              <input name="billno" type="text" class="swifttext" id="billno" size="30" maxlength="16"  autocomplete="off"/>
                                                            </div></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr bgcolor="#f7faff">
                                                          <td align="left" valign="top"><div align="left">Bill Amount</div></td>
                                                          <td colspan="2" align="left" valign="top"><div align="left">
                                                              <input name="billamount" type="text" class="swifttext" id="billamount" size="30" maxlength="12" autocomplete="off" />
                                                            </div></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr bgcolor="#f7faff">
                                                          <td align="left" valign="top"><div align="left">Remarks:</div></td>
                                                          <td colspan="2" align="left" valign="top"><div align="left">
                                                              <textarea name="regremarks" cols="30"  class="swifttext" id="regremarks"></textarea>
                                                            </div></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                  <td width="350" rowspan="6" align="left" valign="top"><div id="scratchcradloading"></div>
                                                    <div id="detailsonscratch" style="display:none;">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3" class="diaplaytableborder">
                                                        <tr>
                                                          <td width="38%" valign="top">PIN Serial Number</td>
                                                          <td width="4%" valign="top">:</td>
                                                          <td width="58%" valign="top" id="cardnumberdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">PIN Number </td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="scratchnodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Product Name</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="productdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Purchase Type</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="purchasetypedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Usage Type</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="usagetypedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached To</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="attachedtodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Registerd To</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="registeredtodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached Date</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="attachdatedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Registered Date</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="registerdatedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">PIN Status</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="cardstatusdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Scheme</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="schemedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">&nbsp;</td>
                                                          <td valign="top">&nbsp;</td>
                                                          <td valign="top" id="cardstatusdisplay2">&nbsp;</td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td colspan="3" valign="top" bgcolor="#F7FAFF"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">
                                                      <tr>
                                                        <td height="25" colspan="2" align="left" valign="middle"><div id="reg-form-error"></div></td>
                                                      </tr>
                                                      <tr>
                                                        <td width="43%" height="35" align="left" valign="middle">&nbsp;</td>
                                                        <td width="57%" height="35" align="right" valign="middle"><input name="generateregistration" type="button" class="swiftchoicebutton" id="generateregistration" value="Generate" onclick=" makearegistration();" />
                                                          &nbsp;&nbsp;
                                                          <input name="registrationclearall" type="reset" class="swiftchoicebutton" id="registrationclearall" value="Clear" onclick="$('#reg-form-error').html(''); clearregistrationform();" />
                                                          &nbsp;&nbsp;
                                                          <input name="closereg" type="button" class="swiftchoicebutton-red" id="closereg" value="Close" onclick="gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');" /></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table>
                                            </form></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="tabgroupgridc4" style="overflow:auto; height:150px; width:704px; padding:2px; display:none;" align="center">No datas found to be displayed.</div>
                                    <div id="tabgroupgridc3" style="overflow:auto; height:auto; width:704px; padding:2px; display:none;" align="center">
                                      <form action="" method="post" name="cardsearchfilterform" id="cardsearchfilterform" onsubmit="return false;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="45%" valign="top"><table width="86%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td height="34" id="cardselectionprocess" style="padding:0">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td align="center"><input name="cardsearchtext" type="text" class="swifttext" id="cardsearchtext" onkeyup="cardsearch(event);"  autocomplete="off" style="width:204px"/>
                                                    <span style="display:none">
                                                    <input name="cardlastslno" type="hidden" id="cardlastslno"  disabled="disabled"/>
                                                    </span>
                                                    <select name="cardlist" size="5" class="swiftselect" id="cardlist" style="width:210px; height:200px" onclick ="selectcardfromlist();" >
                                                    </select></td>
                                                </tr>
                                              </table></td>
                                            <td width="55%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><div id="carddetails" style="display:none;">
                                                      <table width="89%" border="0" cellspacing="0" cellpadding="3" style="border:2px solid #0066CC">
                                                        <tr>
                                                          <td width="38%" valign="top" align="left">PIN Serial Number</td>
                                                          <td width="4%" valign="top" align="left">:</td>
                                                          <td width="58%" valign="top" id="displaycardnumber" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">PIN Number</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayscratchno" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Product Name</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayproductname" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Purchase Type</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displaypurchasetype" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Usage Type</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayusagetype" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Attached To Dealer</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayattachedto" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Attached To Customer</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayattachedtocust" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Registerd To</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayregisteredto" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left"l>Attached Date</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayattachdate" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Registered Date</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayregisterdate" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Remarks</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displaycardremarks" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">PIN Status</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displaycardstatus" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top" align="left">Scheme</td>
                                                          <td valign="top" align="left">:</td>
                                                          <td valign="top" id="displayscheme" align="left">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">&nbsp;</td>
                                                          <td valign="top">&nbsp;</td>
                                                          <td valign="top" >&nbsp;</td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2">&nbsp;</td>
                                          </tr>
                                        </table>
                                      </form>
                                    </div>
                                    <!-- forcesurrender added -->
                                    <div id="tabgroupgridc5" style="overflow:auto; height:130px; width:704px; padding:2px; display:none;" align="center">
                                      <? if($forcesurrender == 'yes') { ?>
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><form id="forcesurrender" name ="forcesurrender" method="post" action="" onsubmit="return false;">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <!--<tr bgcolor="#f7faff">
                                                  <td colspan="3" align="left" valign="top" bgcolor="#f7faff" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td width="19%"  align="left" valign="top">Pin Numbers:</td>
                                                        <td width="77%"  valign="top"  ><input name="custslno" type="hidden" id="custslno" /></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>-->
                                                <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" >Pin Numbers:</td>
                                                  <td align="left" valign="top"><span id="disforcesurrender">
                                                    <select name="forsurrender" class="swiftselect-mandatory" id="forsurrender" style="width:180px;">
                                                      <option value="">Make A Selection</option>
                                                    </select>
                                                    </span></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td width="108" align="left" valign="top">Force Surrender:</td>
                                                  <td width="260" align="left" valign="top"><span id="countforcesurrender"></span></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td width="108" align="left" valign="top">Online Surrender:</td>
                                                  <td width="260" align="left" valign="top"><span id="countonlinesurrender"></span></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td width="108" align="left" valign="top">PIN Status:</td>
                                                  <td width="260" align="left" valign="top"><span id="pinstatussurrender"></span></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td width="108" align="left" valign="top">Remarks:</td>
                                                  <td width="260" align="left" valign="top">
                                                  <textarea name="forceremarks" cols="25" class="swifttextarea type_enter focus_redclass" id="forceremarks" style="width:169px;resize:auto"></textarea>
                                                  </td>
                                                </tr>
                                                <!-- <tr bgcolor="#edf4ff">
                                                  <td width="108" align="left" valign="top">Email to Customer:</td>
                                                  <td width="260" align="left" valign="top">
                                                 <input type="checkbox" name="emailtocustomer" id="emailtocustomer" class="type_enter focus_redclass generallastfield"/>
                                                  </td>
                                                </tr> -->
                                                <tr bgcolor="#f7faff">
                                                  <td width="108" align="left" valign="top">&nbsp;</td>
                                                  <td width="260" align="left" valign="top"><input name="savesur" type="button" class="swiftchoicebutton saveclass focus_redclass" id="savesur" value="Submit" onclick="forcesurrenderdetails('forcesurrender');" /></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td colspan="3" valign="top" bgcolor="#F7FAFF"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">
                                                      <tr>
                                                        <td height="25" colspan="2" align="left" valign="middle"><div id="surrender-form-error"></div></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table>
                                            </form></td>
                                        </tr>
                                      </table>
                                      <? } else { echo("You are not authorised to give the registration"); } ?>
                                    </div>
                                    <!-- end -->
                                    <div id="transferscratchcarddiv" style="overflow:auto; height:auto; width:704px; padding:2px; display:none;" align="center">
                                      <form id="transferscratchform" name="transferscratchform" method="post" action="" onsubmit="return false">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px #666666 solid">
                                                <tr>
                                                  <td width="18%">Transfer Card: </td>
                                                  <td><input name="transfercardfield" type="text" class="swifttext" id="transfercardfield" size="30" readonly="readonly" /></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                  <td><div align="center"><strong>From</strong></div></td>
                                                  <td colspan="2"><div align="center"><strong>To</strong></div></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top">Dealer:</td>
                                                  <td width="38%" valign="top"><input name="tfdealer" type="text" class="swifttext" id="tfdealer" size="30" disabled="disabled" /></td>
                                                  <td width="4%" valign="top"><input type="checkbox" name="ttdealercheck" id="ttdealercheck" onclick="document.getElementById('ttdealerto').disabled = false" /></td>
                                                  <td width="40%" valign="top"><label></label>
                                                    <select name="ttdealerto" class="swiftselect-mandatory" id="ttdealerto" style="width:180px;"  disabled="disabled">
                                                      <option value="">Make A Selection</option>
                                                      <? 
											include('../inc/firstdealer.php');
											?>
                                                    </select>
                                                    <input name="ttdealer" type="hidden" id="ttdealer" /></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top">Product:</td>
                                                  <td valign="top"><input name="tfproduct" type="text" class="swifttext" id="tfproduct" size="30" disabled="disabled" /></td>
                                                  <td valign="top"><input type="checkbox" name="ttproductcheck" id="ttproductcheck"  onclick="document.getElementById('ttproductto').disabled = false"  /></td>
                                                  <td valign="top"><select name="ttproductto" class="swiftselect-mandatory" id="ttproductto" style="width:180px;" disabled="disabled">
                                                      <option value="">Make A Selection</option>
                                                      <? 
											include('../inc/firstproduct.php');
											?>
                                                    </select>
                                                    <input name="ttproduct" type="hidden" id="ttproduct" /></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top">Purchase Type:</td>
                                                  <td valign="top"><input name="tfpurchasetype" type="text" class="swifttext" id="tfpurchasetype" size="30" disabled="disabled"/></td>
                                                  <td valign="top"><input type="checkbox" name="ttpurchasetypecheck" id="ttpurchasetypecheck" onclick="document.getElementById('ttpurchasetype').disabled = false"  /></td>
                                                  <td valign="top"><label></label>
                                                    <select name="ttpurchasetype" class="swiftselect" id="ttpurchasetype"  disabled="disabled" >
                                                      <option value="" selected="selected"></option>
                                                      <option value="new">New</option>
                                                      <option value="updation">Updation</option>
                                                    </select></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top">Usage Type:</td>
                                                  <td valign="top"><input name="tfusagetype" type="text" class="swifttext" id="tfusagetype" size="30" disabled="disabled"/></td>
                                                  <td valign="top"><input type="checkbox" name="ttusagetypecheck" id="ttusagetypecheck" onclick="document.getElementById('ttusagetype').disabled = false" /></td>
                                                  <td valign="top"><select name="ttusagetype" class="swiftselect" id="ttusagetype"  disabled="disabled" >
                                                      <option value="" selected="selected"></option>
                                                      <option value="singleuser">Single User</option>
                                                      <option value="multiuser">Multi User</option>
                                                      <option value="additionallicense">Additional License</option>
                                                    </select></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">
                                                <tr>
                                                  <td height="25" colspan="2" align="left" valign="middle">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td width="68%" height="35" align="left" valign="middle"><div id="tranfer-form-error"></div></td>
                                                  <td width="32%" height="35" align="right" valign="middle"><input name="transfer" type="button" class="swiftchoicebutton" id="transfer" value="Transfer" onclick="transferscratchdetails();" />
                                                    &nbsp;&nbsp;
                                                    <input name="cancel" type="reset" class="swiftchoicebutton" id="cancel" value="Cancel" onclick="displayelement('tabgroupgridc2','transferscratchcarddiv')" /></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td><div align="right">&nbsp;&nbsp;</div></td>
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
      </table></td>
  </tr>
</table>
<script>
gettotalcustomercount();
new_refreshcuscardarray();
up_refreshcuscardarray();
gettotalcardcount();
</script>