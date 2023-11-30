<?
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select branchhead from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$branchhead = $resultfetch['branchhead'];
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<? echo (rand());?>" />
<script language="javascript" src="../functions/javascript.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/implementation.js?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/getdistrictjs.php?dummy=<? echo (rand());?>"></script>
<script language="javascript"  src="../functions/getdistrictfunction.php?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/fileupload.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<? echo (rand());?>"></script>
<div style="left: -1000px; top: 597px;visibility: hidden; z-index:100" id="tooltip1">dummy</div>
<script language="javascript" src="../functions/tooltip.js?dummy=<? echo (rand());?>"></script>
<!-- <script language="javascript">
$(document).ready(function(){
  
  $("#imp_statustype").change(function () {
    if($('#imp_statustype option:selected').text() == 'Others')
    {
      $("#remarksdiv").show();
    }
    else
    {
      $("#remarksdiv").hide();
      $("#imptype_remarks").val('');
    }
      
    });
  });
  </script> -->
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
                        <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:1px">&nbsp;</td>
                        <td width="29%"  style="padding:0"><div class="resendtext"><a onclick="displayfilterdiv()" style="cursor:pointer">Filter>></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2"><div id="displayfilter" style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f7faff" style=" border:1px solid #ADD8F1">
                              <tr>
                                <td width="28%" align="left" valign="top"><strong>Status:</strong><br /></td>
                                <td width="72%" align="left" valign="top"><select name="imp_status" class="swiftselect" id="imp_status"  style="width:140px;">
                                    <option value="">All</option>
                                    <option value="Fowarded back to Sales Person">Fowarded back to Sales Person</option>
                                    <option value="Awaiting Branch Head Approval">Awaiting Branch Head Approval</option>
                                    <option value="Awaiting Co-ordinator Approval">Awaiting Co-ordinator Approval</option>
                                    <option value="Fowarded back to Branch Head">Fowarded back to Branch Head</option>
                                    <option value="Implementation, Yet to be Assigned">Implementation, Yet to be Assigned</option>
                                    <option value="Assigned For Implementation">Assigned For Implementation</option>
                                    <option value="Implementation in progess">Implementation in progess</option>
                                    <option value="Implementation Completed">Implementation Completed</option>
                                  </select></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="right" class="resendtext"><a onclick="searchbystatus();" style="cursor:pointer">Go&gt;&gt;</a></div></td>
                              </tr>
                            </table>
                          </div></td>
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
                            <td width="28%" align="top" class="active-leftnav">Implemantation Details</td>
                            <td width="28%" align="top"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="25%" align="top"><div align="left" style="padding:2px">
                                <div align="right">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);"  maxlength="20"  autocomplete="off" style="width:125px"/>
                                  <img src="../images/search.gif" alt="" width="16" height="15" align="absmiddle" style="cursor:pointer"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" /> </div>
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
                                              <td width="57%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" >
                                                  <tr>
                                                    <td colspan="4" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="9%" align="left" valign="middle">Text: </td>
                                                          <td width="91%" colspan="3" align="left" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="35" maxlength="60" class="swifttext"  autocomplete="off" value=""/>
                                                            <span style="font-size:9px; color:#999999; padding:1px">(Leave Empty for all)</span></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="30%"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:solid 1px #004000"  align="left">
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
                                                            </table></td>
                                                          <td width="70%" valign="top" style="border-right:1px solid #CCCCCC"><table width="100%" border="0" cellspacing="0" cellpadding="6">
                                                              <tr>
                                                                <td width="25%">Region:</td>
                                                                <td width="75%"><select name="region2" class="swiftselect" id="region2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? 
                                                                      include('../inc/region.php');
                                                                    ?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td>State:</td>
                                                                <td><select name="state2" class="swiftselect" id="state2" onchange="getdistrictfilter('districtcodedisplaysearch',this.value);" onkeyup="getdistrictfilter('districtcodedisplaysearch',this.value);" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/state.php'); ?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td>District:</td>
                                                                <td align="left" valign="top"  id="districtcodedisplaysearch" height="10" ><select name="district2" class="swiftselect" id="district2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td>Dealer:</td>
                                                                <td align="left" valign="top"   height="10"><select name="currentdealer2" class="swiftselect" id="currentdealer2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/firstdealer.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td>Branch:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="branch2" class="swiftselect" id="branch2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/branch.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td>Type:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="type2" class="swiftselect" id="type2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <option value="Not Selected">Not Selected</option>
                                                                    <? include('../inc/custype.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td>Category:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="category2" class="swiftselect" id="category2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <option value="Not Selected">Not Selected</option>
                                                                    <? include('../inc/category.php');?>
                                                                  </select></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                    </table></td>
                                                          <td width="36%" valign="top" ><table width="96%" border="0" cellspacing="0" cellpadding="2" >
                                                            <tr>
                                                              <td colspan="3" valign="top" style="padding:0"></td>
                                                            </tr>
                                                            <tr >
                                                              <td colspan="3" valign="top" align="left"><strong>Status</strong></td>
                                                            </tr >
                                                            <tr >
                                                              <td colspan="3" valign="top" align="left" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8"><div style="height:140px; overflow:auto">
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status9" value="status9"/>
                                                                <label for="status9">Not created (Raw)</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status" value="status2"/>
                                                                <label for="status"> Awaiting Branch Head Approval</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status1" value="status1" />
                                                                <label for="status1"> Fowarded back to Sales Person</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status3" value="status3"/>
                                                                <label for="status3">Awaiting Co-ordinator Approval</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status4" value="status4"/>
                                                                <label for="status4">Fowarded back to Branch Head</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status5" value="status5" />
                                                                <label for="status5">Implementation, Yet to be Assigned</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status6" value="status6"/>
                                                                <label for="status6"> Assigned For Implementation</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status7" value="status7"/>
                                                                <label for="status7">Implementation in progess</label>
                                                                <br />
                                                                <input checked="checked" type="checkbox" name="summarize[]" id="status8" value="status8"/>
                                                                <label for="status8"> Implementation Completed</label>
                                                              </div></td>
                                                            </tr>
                                                            <tr>
                                                              <td colspan="2" height="3px"></td>
                                                            </tr>
                                                            <tr >
                                                              <td width="10%"  valign="top"><input type="checkbox" name="selectstatus" id="selectstatus" checked="checked" onchange="selectdeselectcommon('selectstatus','summarize[]')" /></td>
                                                              <td align="left" valign="top"><label for="selectstatus">Select All / None</label></td>
                                                            </tr>
                                                          </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="32%" height="35" align="right" valign="middle"  ><div id="filter-form-error"></div></td>
                                                    <td colspan="2" align="right" valign="middle"  ><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="advancesearchimplementer();" />
&nbsp;
<input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" />
&nbsp;
<input name="close2" type="button" class="swiftchoicebutton" id="close2" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" /></td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td height="22" colspan="2" align="center" valign="middle"><div id="form-error"></div></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td width="100%" ><div   id="displaytext" style="display:block; ">
                                          <table width="100%" border="0"  cellpadding="0" cellspacing="0" style="border:#D1D1D1 solid 1px">
                                            <tr height="280px">
                                              <td height="200px" class="textmsg" align="center" id="displa">No Customer Selected </td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div id=displaydetails style="display:none">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6"  bgcolor="#FFFFF0">
                                                  <tr style="height:35px;"  bgcolor="#D6D6D6" >
                                                    <td width="17%" style="padding-left:8px;" valign="middle"><strong>Company Name:</strong></td>
                                                    <td width="78%" bgcolor="#D6D6D6" id="displaycompanyname" style="padding-left:8px;"  valign="middle"><span style="padding-right:10px"> </span></td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"><img src="../images/plus.jpg" border="0" id="toggleimg1" name="toggleimg1"  align="absmiddle" onClick="divdisplay('displaycustomerdetails','toggleimg1');" style="cursor:pointer"/></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><div id="displaycustomerdetails" style="display:none;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                          <tr>
                                                            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
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
                                                                  <td ><strong>Cell: </strong></td>
                                                                  <td id="displaycell">&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                  <td width="49%"><strong>Relyon Representative: </strong></td>
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
                                                          <tr>
                                                            <td colspan="4" valign="top"></td>
                                                            <td colspan="2"></td>
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
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6"  >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>Select Type Of Implemetation</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                <td width="19%">Implemenation Type<strong>: </strong></td>
                                                                <td width="33%"><select name="imp_statustype" class="swiftselect" id="imp_statustype"  style="width:175px;">
                                                                  <option value="" selected="selected">Select Implemetation Type</option>
                                                                    <? include('../inc/implementationtype.php')?>
                                                                </select></td>
                                                                <td width="35%">
                                                                <!-- <div id="remarksdiv" style="display:none"> -->
                                                                <div id="remarksdiv">
                                                                  <textarea name="imptype_remarks" cols="27" class="swifttextarea" id="imptype_remarks" maxlength="500" style="resize: none;" placeholder="Enter Remarks"/></textarea></div></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6"   >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="23%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>1.	Invoice Information</strong></td>
                                                    <td width="8%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;"><input type="hidden" id="finalinvoice" name="finalinvoice"/>
                                                      &nbsp;
                                                      <input type="hidden" id="implementationslno" name="implementationslno"/>
                                                      <input type="hidden" id="productcode" name="productcode"/></td>
                                                      <input type="hidden" id="producttype" name="producttype"/>
                                                    <td width="64%" bgcolor="#D6D6D6"  style="padding:5px;" id="invoice_error">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"><img src="../images/minus.jpg" border="0" id="toggleimg2" name="toggleimg2"  align="absmiddle" onClick="divdisplay('displayinvoicedetails','toggleimg2');" style="cursor:pointer"/></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" ><div id="displayinvoicedetails" style="display:block;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                          <tr>
                                                            <td colspan="3"><div id="displayinvoicecfdiv" style="display:none">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="4" id="adddescriptioncfrows">
                                                                  <tr >
                                                                    <td width="19%"><strong>Select a Invoice: </strong></td>
                                                                    <td width="20%" id="invoicedetailsdisplay"><select class="swiftselect"  style="width:125px;" id="invoicedetails">
                                                                        <option value="" selected="selected" >Select a Invoice</option>
                                                                      </select></td>
                                                                    <td width="13%"><input name="confirm" type="button" class="swiftchoicebutton" id="confirm" value="Confirm" onclick="confirmation('invoicedetails')" /></td>
                                                                    <td width="17%">(or) <strong>C/F Entries: </strong></td>
                                                                    <td width="19%"><select name="cfdetails" class="swiftselect" id="cfdetails"  style="width:125px;">
                                                                        <option value="" selected="selected" >Select an Entry</option>
                                                                      </select></td>
                                                                    <td width="12%"><span style="padding-right:10px">
                                                                      <input name="cfconfirm" type="button" class="swiftchoicebutton" id="cfconfirm" value="Confirm" onclick="confirmation('cfinvoice')" />
                                                                      </span></td>
                                                                  </tr>
                                                                </table>
                                                              </div></td>
                                                          </tr>
                                                          <tr>
                                                            <td colspan="3"><div id="displayinvoicediv" style="display:block">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="4" id="adddescriptionrows">
                                                                  <tr>
                                                                    <td width="20%"><strong>Select a Invoice: </strong></td>
                                                                    <td width="23%" id="invoicedetailsdisplay"><select class="swiftselect"  style="width:125px;" id="invoicedetails">
                                                                        <option value="" selected="selected" >Select a Invoice</option>
                                                                      </select></td>
                                                                    <td width="57%"><span style="padding-right:10px">
                                                                      <input name="confirm" type="button" class="swiftchoicebutton" id="confirm" value="Confirm" onclick="confirmation('invoicedetails')" />
                                                                      </span></td>
                                                                  </tr>
                                                                </table>
                                                              </div></td>
                                                          </tr>
                                                        </table>
                                                      </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="4"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6"  >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="26%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>2. Payment information</strong></td>
                                                    <td width="69%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="50%" valign="top" style="border-right:solid 1px #B7DBFF"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr >
                                                                      <td width="7%" bgcolor="#f7faff" style="padding-left:5px"><input type="checkbox" name="collectedamt" id="collectedamt" onclick="checkboxvalidation('displaypayment')" /></td>
                                                                      <td width="93%" bgcolor="#f7faff">Advance Collected </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2" ><div id="cfentrydiv" style="display:none">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                            <tr bgcolor="#EDF4FF">
                                                                              <td width="21%" align="left" valign="top">Amount:</td>
                                                                              <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" ><input name="paymentamt" type="text" class="diabledatefield" id="paymentamt" size="30"   autocomplete="off" maxlength="30" disabled="disabled"/></td>
                                                                            </tr>
                                                                            <tr bgcolor="#f7faff">
                                                                              <td align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                                                              <td colspan="2" align="left" valign="top" bgcolor="#f7faff" ><input name="paymentremarks" type="text" class="diabledatefield cf" id="paymentremarks" size="30"   autocomplete="off"  maxlength="500" disabled="disabled"/></td>
                                                                            </tr>
                                                                            <tr bgcolor="#EDF4FF">
                                                                              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                  <tr>
                                                                                    <td id="advance-error1" align="left" valign="top" bgcolor="#EDF4FF"  height="35px">&nbsp;</td>
                                                                                    <td  width="27%" align="right" valign="top" bgcolor="#EDF4FF" ><a  onclick="advcollectedmail('advamount')"  class="r-text">Send Email &#8250;&#8250;</a></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div>
                                                                        <div id="invoiceentrydiv" style="display:none"  >
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                            <tr bgcolor="#EDF4FF">
                                                                              <td width="23%" align="left" valign="top">Receipt :</td>
                                                                              <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" id="receiptdisplay"><select name="receipt" class="diabledatefield" id="receipt" style="width: 201px;" disabled="disabled">
                                                                                  <option selected="selected" value="" >---Select---</option>
                                                                                </select></td>
                                                                            </tr>
                                                                            <tr bgcolor="#f7faff">
                                                                              <td align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                                                              <td colspan="2" align="left" valign="top" bgcolor="#f7faff" ><input name="paymentremarks" type="text" class="diabledatefield invoice" id="paymentremarks" size="30"   autocomplete="off" value="" maxlength="500" disabled="disabled"/></td>
                                                                            </tr>
                                                                            <tr bgcolor="#EDF4FF">
                                                                              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                  <tr>
                                                                                    <td  id="advance-error2" align="left" valign="top" bgcolor="#EDF4FF"  height="35px">&nbsp;</td>
                                                                                    <td width="29%" align="right" valign="top" bgcolor="#EDF4FF" ><a  onclick="advcollectedmail('receipt')"  class="r-text">Send Email &#8250;&#8250;</a></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                                <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr bgcolor="#EDF4FF">
                                                                      <td align="left" valign="top" bgcolor="#EDF4FF">Balance Recovery Schedule:</td>
                                                                      <td align="left" valign="top" bgcolor="#EDF4FF" ><textarea name="balancerecovery" cols="27" class="swifttextarea" id="balancerecovery" maxlength="500" style="resize: none;"></textarea></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6"   >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="35%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>3.	Requirement Analysis Format</strong></td>
                                                    <td width="60%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr bgcolor="#f7faff">
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                    <tr bgcolor="#edf4ff">
                                                                      <td width="16%" valign="top">Attachment File:</td>
                                                                      <td width="35%" valign="top" bgcolor="#edf4ff" style="border-right:1px solid  #C6E2FF"><input name="attachement_raf" type="text" class="swifttext" id="attachement_raf"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                                        <img src="../images/fileattach.jpg" name="myfileuploadimage1" border="0" align="absmiddle" id="myfileuploadimage1" style="cursor:pointer" onclick="fileuploaddivid('','attachement_raf','fileuploaddiv','595px','35%','1',document.getElementById('customerlist').value,'link_value','')"/> <span class="textclass">(Upload pdf or zip file only)</span>
                                                                        <input type="hidden" value="" name="link_value" id="link_value" />
                                                                        <input type="hidden" value="" name="filepath1" id="filepath1" /></td>
                                                                      <td width="12%"   align="left"  valign="top" bgcolor="#EDF4FF" >Remarks:</td>
                                                                      <td width="37%"   align="left"  valign="top" bgcolor="#EDF4FF" id="downloadlinkfile1" ><input name="requrimentremarks" class="swifttext-mandatory" id="requrimentremarks"  size="40" maxlength="100" autocomplete="off"/>
                                                                        <input type="text" name="impraflastslno" id="impraflastslno" />
                                                                        <input type="text" name="rafslno" id="rafslno" />
                                                                        <input type="hidden" name="deleterafslno" id="deleterafslno" />
                                                                        <input type="hidden" name="raflag" id="raflag" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td style="border-top:1px solid  #C6E2FF"><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                                                    <tr>
                                                                      <td width="55%"><div id="form-error2" align="left">&nbsp;</div></td>
                                                                      <td width="45%"><div align="center">
                                                                          <input name="new"  value="New" type="submit" class="swiftchoicebutton" id="new"  onclick="rafnewentry();document.getElementById('form-error2').innerHTML = '';"/>
                                                                          &nbsp;
                                                                          <input name="saveraf"  value="Save" type="submit" class="swiftchoicebutton" id="saveraf"  onclick="rafilesupdate('saveraf');"/>
                                                                          &nbsp;
                                                                          <input name="deleteraf"  value="Delete" type="submit" class="swiftchoicebutton" id="deleteraf"  onclick="rafilesupdate('deleteraf');"/>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td colspan="2"><div id="tabgroupgridc1_1"  align="center">
                                                                          <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">
                                                                            <tr class="tr-grid-header" align ="left">
                                                                              <td width="20%" nowrap = "nowrap" class="td-border-grid" >Sl No</td>
                                                                              <td width="25%" nowrap = "nowrap" class="td-border-grid" >File name</td>
                                                                              <td width="25%" nowrap = "nowrap" class="td-border-grid">Remarks</td>
                                                                              <td width="30%" nowrap = "nowrap" class="td-border-grid">Download</td>
                                                                            </tr>
                                                                            <tr align ="left">
                                                                              <td colspan="4" nowrap = "nowrap" class="td-border-grid" ><div align="center"><font color="#FF0000"><strong>No Records to Display</strong></font></div></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2"><div id="getmorerecordslink" align="left"></div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6" >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="26%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>4.	Sales Person Inputs</strong></td>
                                                    <td width="69%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="2" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr bgcolor="#EDF4FF">
                                                                <td width="28%" align="left" valign="top" bgcolor="#EDF4FF">PO Number :</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#EDF4FF" ><input name="podate" type="text" class="swiftselect-mandatory" id="podate" size="30"   autocomplete="off" maxlength="100"/></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td width="28%" align="left" valign="top" bgcolor="#f7faff">PO Date :</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#f7faff" ><input name="DPC_podatedetails" type="text" class="swifttext-mandatory" id="DPC_podatedetails" size="30" autocomplete="off" value="" readonly="readonly" /></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td width="28%" align="left" valign="top" bgcolor="#EDF4FF">PO Uploads :</td>
                                                                <td width="32%" valign="top" bgcolor="#edf4ff"><input name="po_uploads" type="text" class="swifttext" id="po_uploads"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                                  <img src="../images/fileattach.jpg" name="myfileuploadimage5" border="0" align="absmiddle" id="myfileuploadimage5" style="cursor:pointer" onclick="fileuploaddivid('downloadlinkfile5','po_uploads','po_fileuploaddiv','825px','35%','5',document.getElementById('customerlist').value,'po_link','')
                                                                    "/>
                                                                  <input type="hidden" id="po_link" name="po_link" />
                                                                  <input type="hidden" value="" name="filepath5" id="filepath5" /></td>
                                                                <td width="20%" valign="top" bgcolor="#edf4ff" ><span class="textclass">(Upload zip file only)</span></td>
                                                                <td width="20%" valign="top" bgcolor="#edf4ff" id="downloadlinkfile5"></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#f7faff">Number of Companies to be processed :</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#f7faff" ><input name="sales_company" type="text" class="swiftselect-mandatory" id="sales_company" size="30"   autocomplete="off" maxlength="30"/></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#EDF4FF">Number of Months to be processed  :</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#EDF4FF" ><input name="sales_tomonths" type="text" class="swiftselect-mandatory" id="sales_tomonths" size="30"   autocomplete="off"maxlength="30"/></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#f7faff">Processing from month  :</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#f7faff" ><input name="sales_frommonth" type="text" class="swiftselect-mandatory" id="sales_frommonth" size="30"   autocomplete="off" maxlength="30"/></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#EDF4FF">Additional Training Commitment:</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#EDF4FF" ><input name="sales_training" type="text" class="swiftselect-mandatory" id="sales_training" size="30"   autocomplete="off" maxlength="30"/></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#EDF4FF">Commitment of Start date:</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#EDF4FF" ><input name="startdate" type="text" class="swifttext-mandatory" id="DPC_startdate" size="30" autocomplete="off" value="" readonly="readonly" /></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#f7faff">Any Free Deliverables:</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#f7faff" ><input name="sales_deliver" type="text" class="swiftselect-mandatory" id="sales_deliver" size="30"   autocomplete="off" maxlength="30" /></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#EDF4FF">Any Scheme Applicable:</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#EDF4FF" ><input name="sales_scheme" type="text" class="swiftselect-mandatory" id="sales_scheme" size="30"   autocomplete="off" maxlength="30" /></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td colspan="4" align="left" valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr >
                                                                      <td width="4%" bgcolor="#f7faff" style="padding-left:5px"><input type="checkbox" name="sales_checkbox" id="sales_checkbox" onclick="checkboxvalidation('displaysales')" /></td>
                                                                      <td width="96%" bgcolor="#f7faff">Any  commission to be paid </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2" ><div id="displaysales"  >
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                            <tr bgcolor="#EDF4FF">
                                                                              <td width="21%" align="left" valign="top">Name :</td>
                                                                              <td width="79%" align="left" valign="top" bgcolor="#EDF4FF" ><input name="sales_name" type="text" class="diabledatefield" id="sales_name" size="30"   autocomplete="off" disabled="disabled" maxlength="100"/></td>
                                                                            </tr>
                                                                            <tr bgcolor="#f7faff">
                                                                              <td align="left" valign="top" bgcolor="#f7faff">Email  ID :</td>
                                                                              <td align="left" valign="top" bgcolor="#f7faff" ><input name="sales_emailid" type="text" class="diabledatefield" id="sales_emailid" size="30"   autocomplete="off" maxlength="100" disabled="disabled"/></td>
                                                                            </tr>
                                                                            <tr bgcolor="#EDF4FF">
                                                                              <td align="left" valign="top">Mobile  :</td>
                                                                              <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="sales_mobile" type="text" class="diabledatefield" id="sales_mobile" size="30"   autocomplete="off" maxlength="50" disabled="disabled"/></td>
                                                                            </tr>
                                                                            <tr bgcolor="#f7faff">
                                                                              <td align="left" valign="top" bgcolor="#f7faff">Commission&nbsp;  :</td>
                                                                              <td align="left" valign="top" bgcolor="#f7faff" ><input name="sales_commission" type="text" class="diabledatefield" id="sales_commission" size="30"   autocomplete="off" maxlength="100" disabled="disabled"/>
                                                                                <input type="hidden" name="customerid" id="customerid"/>
                                                                                <input type="hidden" name="lastslno" id="lastslno"/></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td colspan="4" align="left" valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr >
                                                                      <td width="4%" bgcolor="#EDF4FF" style="padding-left:5px"><input type="checkbox" name="sales_masterdata" id="sales_masterdata" onclick="checkboxvalidation('displaymasterdata')" /></td>
                                                                      <td width="96%" bgcolor="#EDF4FF">Master data in Excel by Relyon </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2"><div id="displaymasterdata" >
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                            <tr bgcolor="#f7faff">
                                                                              <td width="36%" align="left" valign="top">Number of Employees to be Entered:</td>
                                                                              <td width="64%" align="left" valign="top" bgcolor="#f7faff" ><input name="sales_noofemployee" type="text" class="diabledatefield" disabled="disabled"id="sales_noofemployee" size="30"   autocomplete="off" maxlength="30" /></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#EDF4FF">
                                                                <td align="left" valign="top" bgcolor="#f7faff">Commitments of Sales Person:</td>
                                                                <td colspan="3" align="left" valign="top" bgcolor="#f7faff" ><textarea name="sales_remarks" cols="67" maxlength="500"   class="swifttextarea" id="sales_remarks" style="resize: none;"></textarea></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6" >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>5. Attendance Integration Information</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr bgcolor="#EDF4FF">
                                                                <td width="100%" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                          <tr >
                                                                            <td width="4%" bgcolor="#f7faff" style="padding-left:5px"><input type="checkbox" name="attendance" id="attendance" onclick="checkboxvalidation('displayattendance')" /></td>
                                                                            <td width="96%" bgcolor="#f7faff">Attendance  Integration applicable </td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2" ><div id="displayattendance" >
                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td width="22%" align="left" valign="top" bgcolor="#f7faff">Vendor Details :</td>
                                                                                    <td colspan="3" align="left" valign="top" bgcolor="#f7faff" ><input name="attendance_vendor" type="text" disabled="disabled"   class="diabledatefield" id="attendance_vendor" style="width:520px" size="30" maxlength="500"  autocomplete="off">
                                                                                      </input></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td colspan="4" ><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td width="22%" valign="top">Reference File :</td>
                                                                                          <td width="34%" valign="top" bgcolor="#edf4ff"><input name="attendance_errorfile" type="text" disabled="disabled" class="swifttext" id="attendance_errorfile"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                                                            <img src="../images/fileattach.jpg" name="myfileuploadimage2" border="0" align="absmiddle" id="myfileuploadimage2" style="cursor:pointer" onclick="fileuploaddivid('downloadlinkfile2','attendance_errorfile','attendance_fileuploaddiv','1300px','35%','2',document.getElementById('customerlist').value,'attach_link','delete_link')"/> <span class="textclass">(Upload zip file only)</span>
                                                                                            <input type="hidden" id="attach_link" name="attach_link" />
                                                                                            <input type="hidden" id="filepath2" name="filepath2" />
                                                                                            <input type="hidden" id="delete_link" name="delete_link" /></td>
                                                                                          <td width="44%"   align="right"  valign="top" bgcolor="#EDF4FF" id="downloadlinkfile2" ></td>
                                                                                        </tr>
                                                                                      </table></td>
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
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6"  >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>6. Add- On Modules</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                <td width="19%">Add-on<strong>: </strong></td>
                                                                <td width="32%"><select name="addon" class="swiftselect" id="addon" style="width:225px;">
                                                                    <option value="" selected="selected">Select a Product</option>
                                                                    <? include('../inc/addon.php')?>
                                                                  </select></td>
                                                                <td width="9%">Remarks:</td>
                                                                <td width="23%"><input name="addon_remarks" type="text" class="swifttextarea" id="addon_remarks"  size="30" maxlength="200"  autocomplete="off"/></td>
                                                                <td width="17%"><div align="right">
                                                                    <input name="add" type="button" class= "swiftchoicebutton" id="add" value="Add" onclick="addselectedaddons();" />
                                                                  </div></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="5">&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="5" ><table width="100%" border="0" cellspacing="0"  cellpadding="3" id="seletedaddongrid" class="table-border-grid" >
                                                                    <tr class="tr-grid-header">
                                                                      <td width="11%" nowrap = "nowrap" class="td-border-grid" align="left">Slno</td>
                                                                      <td width="30%" nowrap = "nowrap" class="td-border-grid" align="left">Add - On</td>
                                                                      <td width="46%" nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
                                                                      <td width="13%" nowrap = "nowrap" class="td-border-grid" align="left">Remove</td>
                                                                    </tr>
                                                                    <tr id="messagerow">
                                                                      <td colspan="4" class="td-border-grid" ><div align="center" style="height:15px;"><strong><font color="#FF0000">No Records Avaliable</font></strong></div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6" >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>7. Shipment Details</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr >
                                                                      <td width="4%" bgcolor="#EDF4FF" style="padding-left:5px"><input type="checkbox" name="shipment_invoice" id="shipment_invoice" onclick="checkboxvalidation('displayshipmentinvoice')" /></td>
                                                                      <td width="96%" bgcolor="#EDF4FF">Invoice</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2" ><div id="displayshipmentinvoice">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                            <tr bgcolor="#f7faff">
                                                                              <td width="112"  align="left" valign="top" bgcolor="#f7faff">Remarks :</td>
                                                                              <td width="594" align="left" valign="top" bgcolor="#f7faff" ><input name="shipment_remarks" type="text" class="diabledatefield" id="shipment_remarks" style="width:520px" size="30" maxlength="500"  autocomplete="off" /></td>
                                                                            </tr>
                                                                            <tr id="invoice_link" style="display:none">
                                                                              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                  <tr bgcolor="#EDF4FF" height="23px" >
                                                                                    <td width="87%"  align="left" valign="middle" bgcolor="#f7faff" id="error-invoice">&nbsp;</td>
                                                                                    <td width="13%"  align="left" valign="middle" bgcolor="#f7faff" ><a  onclick="shippmentmail('invoice')"  class="r-text">Send Email &#8250;&#8250;</a></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr >
                                                                      <td width="5%" bgcolor="#EDF4FF" style="padding-left:5px"><input type="checkbox" name="shipment_manual" id="shipment_manual" onclick="checkboxvalidation('displayshipmentmanual')" /></td>
                                                                      <td width="100%" bgcolor="#EDF4FF">Manual</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td colspan="2" ><div id="displayshipmentmanual"  >
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                            <tr bgcolor="#f7faff">
                                                                              <td width="112" align="left" valign="top" bgcolor="#f7faff">Remarks :</td>
                                                                              <td width="593" align="left" valign="top" bgcolor="#f7faff" ><input name="manual_remarks" type="text" class="diabledatefield" id="manual_remarks"  style="width:520px" value="" size="30" maxlength="500"   autocomplete="off" /></td>
                                                                            </tr>
                                                                            <tr id="manual_link" style="display:none">
                                                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                  <tr bgcolor="#f7faff"  height="23px">
                                                                                    <td width="87%"  align="left" valign="top" bgcolor="#f7faff"  id="error-manual">&nbsp;</td>
                                                                                    <td width="13%"  align="left" valign="top" bgcolor="#f7faff" ><a  onclick="shippmentmail('manual')"  class="r-text">Send Email &#8250;&#8250;</a></td>
                                                                                  </tr>
                                                                                </table></td>
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
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6">
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>8. Customization Details</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr bgcolor="#EDF4FF">
                                                                <td width="100%" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                          <tr >
                                                                            <td width="4%" bgcolor="#f7faff" style="padding-left:5px"><input type="checkbox" name="customization" id="customization" onclick="checkboxvalidation('displaycustomization')" /></td>
                                                                            <td width="96%" bgcolor="#f7faff">Customization  applicable along with purchase </td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td colspan="2" ><div id="displaycustomization" >
                                                                                <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td width="22%" align="left" valign="top" bgcolor="#f7faff">Customization Remarks:</td>
                                                                                    <td colspan="2" align="left" valign="top" bgcolor="#f7faff" ><input name="customization_remarks" type="text" class="diabledatefield" id="customization_remarks" style="width:520px" size="30" maxlength="500"  autocomplete="off"/>
                                                                                      </input></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top">References Files:</td>
                                                                                    <td valign="top" bgcolor="#edf4ff"><input name="customization_references" type="text" class="swifttext" id="customization_references"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                                                      <img src="../images/fileattach.jpg" name="myfileuploadimage3" border="0" align="absmiddle" id="myfileuploadimage3" style="cursor:pointer" onclick="fileuploaddivid('','customization_references','references_fileuploaddiv','1850px','35%','3',document.getElementById('customerlist').value,'cust_link','')"/><span class="textclass"> (Upload zip file only)</span></td>
                                                                                    <td valign="top" bgcolor="#edf4ff"><input type="hidden" id="cust_link" name="cust_link" /></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top" bgcolor="#f7faff">SPP Data Backup:</td>
                                                                                    <td valign="top" bgcolor="#f7faff"><input name="customization_sppdata" type="text" class="swifttext" id="customization_sppdata"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                                                      <img src="../images/fileattach.jpg" name="myfileuploadimage4" style="cursor:pointer" border="0" align="absmiddle" id="myfileuploadimage4" onclick="fileuploaddivid('','customization_sppdata','sppdata_fileuploaddiv','1860px','35%','4',document.getElementById('customerlist').value,'spp_link','')"/> <span class="textclass">(Upload zip file only)</span></td>
                                                                                    <td valign="top" bgcolor="#f7faff"><input type="hidden" id="spp_link" name="spp_link" /></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top" bgcolor="#edf4ff">Current Status:</td>
                                                                                    <td colspan="2" valign="top" bgcolor="#edf4ff" id="customizationstatus">Pending</td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                          <td width="5%" valign="top" bgcolor="#edf4ff"><span style="padding-left:5px">
                                                                                            <input type="checkbox" name="customizationcustomerview" id="customizationcustomerview"  />
                                                                                            </span></td>
                                                                                          <td width="95%" colspan="2" valign="top" bgcolor="#edf4ff" id="customizationstatus">Customization Customer View</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td colspan="3" valign="top" bgcolor="#f7faff">Delivered Files:</td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td colspan="3" valign="top" bgcolor="#f7faff"><div id="tabgroupgridc2" style="overflow:auto;; padding:1px;" align="center">
                                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                          <tr>
                                                                                            <td><div id="tabgroupgridc1_2" align="center">
                                                                                                <table width="100%" cellpadding="5" cellspacing="0" class="table-border-grid">
                                                                                                  <tr class="tr-grid-header" align ="left">
                                                                                                    <td nowrap = "nowrap" class="td-border-grid" >Sl No</td>
                                                                                                    <td nowrap = "nowrap" class="td-border-grid">Remarks</td>
                                                                                                    <td nowrap = "nowrap" class="td-border-grid">Downloadlink</td>
                                                                                                  </tr>
                                                                                                  <tr>
                                                                                                    <td align="center" class="td-border-grid" colspan="3"><font color="#FF4F4F"><strong>No Records Records</strong></font>
                                                                                                      <div></div></td>
                                                                                                  </tr>
                                                                                                </table>
                                                                                              </div></td>
                                                                                          </tr>
                                                                                          <tr >
                                                                                            <td><div id="tabgroupgridc2link" style="height:20px; padding:1px;" align="left"> </div></td>
                                                                                          </tr>
                                                                                        </table>
                                                                                        <div id="regresultgrid2" style="overflow:auto; display:none; padding:1px;" align="center"></div>
                                                                                      </div></td>
                                                                                  </tr>
                                                                                </table>
                                                                              </div></td>
                                                                          </tr >
                                                                        </table></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6">
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>9. Web Implementation Details</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                              <tr >
                                                                <td width="5%" bgcolor="#EDF4FF" style="padding-left:5px"><input type="checkbox" name="web_implementation" id="web_implementation" onclick="checkboxvalidation('displaywebimplementation')" /></td>
                                                                <td width="100%" bgcolor="#EDF4FF">Web  Implementation applicable </td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="2" ><div id="displaywebimplementation"  >
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                      <tr bgcolor="#f7faff">
                                                                        <td width="112" align="left" valign="top" bgcolor="#f7faff">Remarks :</td>
                                                                        <td width="593" align="left" valign="top" bgcolor="#f7faff" ><input name="web_remarks" type="text" disabled="disabled"   class="diabledatefield" id="web_remarks" style="width:520px" size="30" maxlength="500" /></td>
                                                                      </tr>
                                                                    </table>
                                                                  </div></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td >&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #D6D6D6" >
                                                  <tr style="height:25px;"  bgcolor="#D6D6D6" >
                                                    <td width="42%" style="padding-left:8px; padding-top:5px;" valign="top"><strong>10. Implementation Status</strong></td>
                                                    <td width="53%" bgcolor="#D6D6D6"  style="padding-left:8px; padding-top:5px;">&nbsp;</td>
                                                    <td width="5%" style="padding-right:10px;"><div align="right"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                                        <tr>
                                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                <td width="119" valign="top" bgcolor="#edf4ff">Current Status :</td>
                                                                <td width="266" valign="top" bgcolor="#edf4ff" id="implementationid">Pending.</td>
                                                                <td width="315" valign="top" bgcolor="#edf4ff" ><span id="assigndiv" style="display:none"><img src="../images/tooltip-arrow-image.gif"  onmouseover="tooltip()" onmouseout="hidetooltip()" style="cursor:pointer" /></span>
                                                                  <input type="hidden" name="assignid" id="assignid" /></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="3" valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td width="29%"  id="remarksname" align="left">&nbsp;</td>
                                                                      <td width="71%" id="implementationremarks" align="left">&nbsp;</td>
                                                                    </tr>
                                                                    <tr id="advdisplay" style="display:none">
                                                                      <td width="29%" align="left">Advance Collected Remarks: </td>
                                                                      <td width="71%" id="advremarksid" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td ><table width="100%" border="0" cellspacing="0" cellpadding="0" height="70">
                                                  <tr>
                                                    <td height="25" colspan="2" align="left" valign="middle">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td width="50%" height="35" align="left" valign="middle" ></td>
                                                    <td width="50%" height="35" align="right" valign="middle"><? if($branchhead == 'yes') {?>
                                                      <input name="approvereject" type="button" class= "swiftchoicebuttonbig" id="approvereject" value="Approve / Reject" onclick="approvevalidate()" />
                                                      <? }?>
                                                      &nbsp;
                                                      &nbsp;
                                                      <input name="save" type="button" class= "swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save',' ')" />
                                                      &nbsp;
                                                      &nbsp;
                                                      <input name="resetvalue" type="button" class="swiftchoicebutton" id="resetvalue" value="Reset" onclick="resetfunc()" />
                                                      &nbsp;&nbsp; </td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                    <tr>
                                      <td ><div  id="displayimplementation" style="display:none; height:280px;border:#D1D1D1 solid 1px" >
                                          <table width="100%" border="0"  cellpadding="5" cellspacing="0"  >
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr >
                                              <td width="11%" align="center"  ><img src="../images/impl_warning_icon.gif" width="50" height="50" /></td>
                                              <td width="89%" align="center" class="imp_textmsg" ><div align="left">No Implementation created for this Customer&nbsp;</div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><div align="center"><span style="padding:5px">
                                                  <input name="create" type="button" class= "swiftchoicebuttonsuperbig" id="create" value="Implementation Request" onclick="validateimplementation(document.getElementById('customerlist').value);" />
                                                  </span></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><div id="error-msg"></div></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td></td>
                          </tr>
                          <tr>
                            <td><div style="display:none">
                                <form action="" method="post" name="approverejectform" id="approverejectform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div id='inline_example1' style='background:#fff; width:650px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                            <tr>
                                              <td><div id="displaytab1" style="display:block">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td colspan="2" style="font-size:14px; color:#F00"><strong>To approve / reject the Implementation request,  click Appropriate button.</strong></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" style="height:15px"></td>
                                                    </tr>
                                                    <tr>
                                                      <td align="right" id="approval-error">&nbsp;</td>
                                                      <td align="right"><input type="button" name="approve1" class="swiftchoicebutton" value="Approve" id="approve1" onclick="displayapproverejdiv('approvetype');"/>
                                                        &nbsp;&nbsp;
                                                        <input type="button" value="Reject" id="reject1" name="reject1"  onclick="displayapproverejdiv('rejecttype')" class="swiftchoicebutton"/>
                                                        &nbsp;&nbsp;
                                                        <input type="button" value="Close" id="close" name="close"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                    </tr>
                                                  </table>
                                                </div>
                                                <div id="displaytab2" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                    <tr>
                                                      <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#f7faff">
                                                      <td><strong>Approval Remarks</strong></td>
                                                      <td><textarea name="appremarks" cols="80" class="swifttextareanew" id="appremarks" rows="3" style="resize: none;"></textarea></td>
                                                    </tr>
                                                    <div >
                                                      <tr bgcolor="#edf4ff" id="displayadvanceremarks1" style="display:none">
                                                        <td ><strong>No Advance Remarks</strong></td>
                                                        <td ><textarea name="advremarks" cols="80" class="swifttextareanew" id="advremarks" rows="3" style="resize: none;"></textarea></td>
                                                      </tr>
                                                    </div>
                                                    <tr>
                                                      <td align="left" id="app-approval-error">&nbsp;</td>
                                                      <td align="right"><input type="button" name="update" class="swiftchoicebutton" value="Update" id="update" onclick="approvedetails('approved')"/>
                                                        &nbsp;&nbsp;
                                                        <input type="button"  value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                    </tr>
                                                  </table>
                                                </div>
                                                <div id="displaytab3" style="display:none">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3" s>
                                                    <tr>
                                                      <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr bgcolor="#f7faff">
                                                      <td><strong>Reject Remarks</strong></td>
                                                      <td><textarea name="rejremarks" cols="80" class="swifttextareanew" id="rejremarks" rows="3" style="resize: none;"></textarea></td>
                                                    </tr>
                                                    <div >
                                                      <tr bgcolor="#edf4ff" id="displayadvanceremarks2" style="display:none">
                                                        <td ><strong>No Advance Remarks</strong></td>
                                                        <td ><textarea name="advremarks" cols="80" class="swifttextareanew" id="advremarks" rows="3" style="resize: none;"></textarea></td>
                                                      </tr>
                                                    </div>
                                                    <tr>
                                                      <td align="left" id="rej-approval-error">&nbsp;</td>
                                                      <td align="right"><input type="button" name="update" class="swiftchoicebutton" value="Update" id="update" onclick="approvedetails('rejected')"/>
                                                        &nbsp;&nbsp;
                                                        <input type="button" value="Close" id="closepreviewbutton1"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                          </table>
                                        </div></td>
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
<div id="fileuploaddiv" style="display:none;">
  <? include('../inc/fileuploadform.php'); ?>
</div>
<div id="attendance_fileuploaddiv" style="display:none;">
  <? include('../inc/attendanceuploadform.php'); ?>
</div>
<div id="references_fileuploaddiv" style="display:none;">
  <? include('../inc/referenceuploadform.php'); ?>
</div>
<div id="sppdata_fileuploaddiv" style="display:none;">
  <? include('../inc/sppdatauploadform.php'); ?>
</div>
<div id="po_fileuploaddiv" style="display:none;">
  <? include('../inc/pouploadform.php'); ?>
</div>
<script>gettotalcustomercount()</script> 
