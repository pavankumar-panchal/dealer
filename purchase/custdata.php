<?php
include('../inc/eventloginsert.php');
?>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/custdata.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/colorbox-analysis.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getselectiontypejs.php?dummy=<?php echo (rand());?>"></script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid; text-align: center;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td height="15px"></td>
                          </tr>
                          <tr>
                            <td></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                          <tr>
                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                <tr>
                                  <td height="15px"></td>
                                </tr>
                                <tr>
                                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="4%" height="30px" class="imptabheadnone">&nbsp;</td>
                                        <td width="18%" onclick="tabopenimp2('1','tabg1')" style="cursor:pointer; text-align:center" class="imptabheadactive imp_fonttab" id="tabg1h1">Analysis</td>
                                        <td width="4%" class="imptabheadnone">&nbsp;</td>
                                        <td width="18%" onclick="tabopenimp2('2','tabg1')"style="cursor:pointer; text-align:center"  class="imptabhead imp_fonttab" id="tabg1h2">Customer Details</strong></td>
                                        <td width="56%" class="imptabheadnone">&nbsp;</td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div style="display:block" align="justify" id="tabg1c1" >
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="87%" height="35px" id="form-error" align="center"></td>
                                              <td width="13%" valign="top" align="right"><div onclick="getalldummydetails() ;cleardetailsform();displayalcustomer();" ><img src="../images/imax-customer-refresh.jpg" alt="Refresh Data" title="Refresh Data Data" style="cursor:pointer" /><span class="resendtext">Refresh Data</span></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr>
                                                    <td height="10px" colspan="3"></td>
                                                  </tr>
                                                  <tr valign="top">
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td align="left" style="font-size:12px" ><strong>Emailid Inaccuracy Report</strong></td>
                                                        </tr>
                                                        <input type="hidden" name="typeselected" id="typeselected" value="" />
                                                        <tr>
                                                          <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="3" class="imp_table-border">
                                                              <tr class="imp_tr-grid-header">
                                                                <td width="16%" class="imp_td-border imp_fontstyle2" >&nbsp;</td>
                                                                <td width="16%" align="center" class="imp_td-border" >Total IDs</td>
                                                                <td width="17%" align="center" class="imp_td-border" >Blank</td>
                                                                <td width="17%" align="center" class="imp_td-border" >Relyon ID</td>
                                                                <td width="17%" align="center" class="imp_td-border" >Dealer ID</td>
                                                                <td width="17%" align="center" class="imp_td-border" >Dummy ID</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" class="imp_td-border imp_fontstyle2">Email IDs:</td>
                                                                <td align="center" class="imp_td-border"><span id="type_totalemail" class="imp_fontstyle2"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_blankemail" class="imp_fontstyle1" onclick="detailedemailgrid('blank')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_relyonid" class="imp_fontstyle1" onclick="detailedemailgrid('relyon')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_dealerid" class="imp_fontstyle1" onclick="detailedemailgrid('dealer')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_dummyid" class="imp_fontstyle1" onclick="detailedemailgrid('dummy')"></span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                  </tr>
                                                  <tr valign="top">
                                                    <td width="49%"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td align="left" style="font-size:12px" ><strong>Cell Inaccuracy Report</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="3" class="imp_table-border">
                                                              <tr class="imp_tr-grid-header">
                                                                <td width="16%" class="imp_td-border imp_fontstyle2" >&nbsp;</td>
                                                                <td width="28%" align="center" class="imp_td-border" >Total </td>
                                                                <td width="26%" align="center" class="imp_td-border" >Blank </td>
                                                                <td width="30%" align="center" class="imp_td-border" >Dummy </td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" class="imp_td-border imp_fontstyle2">Cell:</td>
                                                                <td align="center" class="imp_td-border"><span id="type_totalcell" class="imp_fontstyle2"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_blankcell" class="imp_fontstyle1" onclick="detailedcellgrid('blank')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_dummycell" class="imp_fontstyle1" onclick="detailedcellgrid('dummy')"></span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                    <td width="2%"></td>
                                                    <td width="48%"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td align="left" style="font-size:12px" ><strong>Phone Inaccuracy Report</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="3" class="imp_table-border">
                                                              <tr class="imp_tr-grid-header">
                                                                <td width="16%" class="imp_td-border " >&nbsp;</td>
                                                                <td width="28%" align="center" class="imp_td-border" >Total</td>
                                                                <td width="26%" align="center" class="imp_td-border" >Blank</td>
                                                                <td width="30%" align="center" class="imp_td-border" >Dummy</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" class="imp_td-border imp_fontstyle2">Phone:</td>
                                                                <td align="center" class="imp_td-border"><span id="type_totalphone" class="imp_fontstyle2"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_blankphone" class="imp_fontstyle1" onclick="detailedphonegrid('blank')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_dummyphone" class="imp_fontstyle1" onclick="detailedphonegrid('dummy')"></span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                  </tr>
                                                  <tr valign="top">
                                                    <td width="49%"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td align="left" style="font-size:12px" ><strong>Type Inaccuracy Report</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3" class="imp_table-border">
                                                              <tr class="imp_tr-grid-header">
                                                                <td width="16%" class="imp_td-border" >&nbsp;</td>
                                                                <td width="24%" align="center" class="imp_td-border" >Total</td>
                                                                <td width="33%" align="center" class="imp_td-border" >Not Selected</td>
                                                                <td width="27%" align="center" class="imp_td-border" >Others</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" class="imp_td-border imp_fontstyle2">Type:</td>
                                                                <td align="center" class="imp_td-border"><span id="type_overtotaltype" class="imp_fontstyle2"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_totaltype"  class="imp_fontstyle1" onclick="detailedcustgrid('custtype')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_typeotherse"  class="imp_fontstyle1" onclick="detailedcustgrid('custtypeothers')"></span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                    <td width="2%"></td>
                                                    <td width="48%"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td align="left" style="font-size:12px" ><strong>Category Inaccuracy Report</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3" class="imp_table-border">
                                                              <tr class="imp_tr-grid-header">
                                                                <td width="16%" class="imp_td-border" >&nbsp;</td>
                                                                <td width="24%" class="imp_td-border" align="center">Total</td>
                                                                <td width="33%" align="center" class="imp_td-border" >Not Selected</td>
                                                                <td width="27%" align="center" class="imp_td-border" >Others</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" class="imp_td-border imp_fontstyle2">Category:</td>
                                                                <td align="center" class="imp_td-border"><span id="type_overtotalcategory" class="imp_fontstyle2"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_totalcategory"  class="imp_fontstyle1" onclick="detailedcustgrid('category')"></span></td>
                                                                <td align="center" class="imp_td-border"><span id="type_categoryothers"  class="imp_fontstyle1" onclick="detailedcustgrid('categoryothers')"></span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td height="10px" colspan="3"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <div style="display:none" align="justify" id="tabg1c2" >
                                          <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                            <tr>
                                              <td  colspan="2" align="center" >&nbsp;</td>
                                            <tr>
                                              <td width="23%" valign="top" style="border-right:#cccccc 1px solid;border-bottom:#cccccc 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
                                                  <tr>
                                                    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td align="left" valign="middle" class="header-line">Customer Selection</td>
                                                        </tr>
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                              <tr>
                                                                <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                                                                <td width="29%" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh customer Data"  /></a></div></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
                                                                  <div id="detailloadcustomerlist">
                                                                    <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();">
                                                                    </select>
                                                                  </div></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                                                              <tr>
                                                                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                                                                <td width="55%" id="totalcount" align="left">&nbsp;</td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table></td>
                                              <td width="77%" valign="top" style="border-bottom:#cccccc 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
                                                  <tr>
                                                    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                  </table></td>
                                                              </tr>
                                                              <tr >
                                                                <td align="left" class="header-line">&nbsp;View Details
                                                                  <input type="hidden" id="statecode" name="statecode"  />
                                                                  <input type="hidden" id="districtcode" name="districtcode" />
                                                                  <input type="hidden" id="regioncode" name="regioncode"  />
                                                                  <input type="hidden" id="branchcode" name="branchcode"  />
                                                                  <input type="hidden" id="typecode" name="typecode"  />
                                                                  <input type="hidden" id="categorycode" name="categorycode"  />
                                                                  <input type="hidden" id="dealerid" name="dealerid"  />
                                                                  <input type="hidden" id="checkboxvalue" name="checkboxvalue"  />
                                                                  <input type="hidden" id="lastslno" name="lastslno"  /></td>
                                                              </tr>
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="imp_fonttab" align="left" style="text-align:left">General Details</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:solid 1px #71B8FF">
                                                                          <tr>
                                                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                <tr>
                                                                                  <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" height="25px">
                                                                                      <tr >
                                                                                        <td width="25%" align="left" bgcolor="#edf4ff">Business Name [Company]:</td>
                                                                                        <td width="75%" align="left" bgcolor="#edf4ff" ><span id="businessnamedisplay" class="contacttext" onClick="selectedtype('businessname')" ></span></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td  colspan="2" valign="top" style="padding:0px" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                      <tr>
                                                                                        <td width="15%" align="left" bgcolor="#F7FAFF" style="padding:4px">Address:</td>
                                                                                        <td width="85%" align="left" bgcolor="#F7FAFF" style=" padding-right:3px; padding-top:3px; padding-left:1px" ><span id="addressdisplay" class="contacttext"  onClick="selectedtype('address')" ></span></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                      <tr height="25px">
                                                                                        <td  bgcolor="#edf4ff">Place:</td>
                                                                                        <td  bgcolor="#edf4ff" ><span id="placedisplay" class="contacttext" onClick="selectedtype('place')" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td width="30%" bgcolor="#F7FAFF">State:</td>
                                                                                        <td width="70%" bgcolor="#F7FAFF" ><span id="statedisplay" class="contacttext" onClick="typeselectbox()"></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">District:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="districtdisplay"  class="contacttext" onClick="typeselectbox()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Pin Code:</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="pincodedisplay" class="contacttext" onClick="selectedtype('pincode')" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Region:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="regiondisplay"  ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Branch:</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="branchdisplay"  ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Current Dealer:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="currentdealerdisplay"  ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">STD Code:</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="stdcodedisplay" class="contacttext" onClick="selectedtype('stdcode')" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Fax:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="faxdisplay" class="contacttext" onClick="selectedtype('fax')" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Type:</td>
                                                                                        <td bgcolor="#F7FAFF"><span  id="typedisplay" class="contacttext" onClick="getselectiontype('type')"></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Category:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="categorydisplay" class="contacttext" onClick="getselectiontype('category')" ></span></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                      <tr height="25px">
                                                                                        <td  bgcolor="#edf4ff">Website:</td>
                                                                                        <td  bgcolor="#edf4ff"><span id="websitedisplay" class="contacttext" onClick="selectedtype('website')" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td width="35%" bgcolor="#F7FAFF">Customer ID:</td>
                                                                                        <td width="65%" bgcolor="#F7FAFF"><span id="cusid" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Active Customer:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="activecustomerdisplay"class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Disable Login:</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="disablelogindisplay" class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Corporate Order:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="corporateorderdisplay" class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Company Closed:</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="companycloseddisplay" class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Is Dealer/Relyon:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="isdealerdisplay" class="contacttext" onClick="checkboxtype()"></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Display in Website:</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="displayinwebsitedisplay" class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff">Promotional SMS:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="promotionalsmsdisplay" class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#F7FAFF">Promotional Email</td>
                                                                                        <td bgcolor="#F7FAFF"><span id="promotionalemaildisplay" class="contacttext" onClick="checkboxtype()" ></span></td>
                                                                                      </tr>
                                                                                      <tr height="25px">
                                                                                        <td bgcolor="#edf4ff"> Created Date:</td>
                                                                                        <td bgcolor="#edf4ff"><span id="createddate" ></span></td>
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
                                                                    <tr>
                                                                      <td><span class="imp_fonttab" style="text-align:left">Contact Details</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                          <tr>
                                                                            <td valign="top" id="contactdetailsgrid" ><table width="100%" border="0" cellspacing="0" cellpadding="5"  class="table-border-grid">
                                                                                <tr class="tr-grid-header">
                                                                                  <td width="7%" align="center" class="td-border-grid">Slno</td>
                                                                                  <td width="15%" align="center" class="td-border-grid">Type</td>
                                                                                  <td width="19%" align="center" class="td-border-grid">Name</td>
                                                                                  <td width="18%" align="center" class="td-border-grid">Phone</td>
                                                                                  <td width="15%" align="center" class="td-border-grid">Cell</td>
                                                                                  <td width="26%" align="center" class="td-border-grid">Emailid</td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td colspan="6" class="td-border-grid" height="20px"  ><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                                                                      <tr>
                                                                                        <td align="center"><font color="#FF4F4F"><strong>No datas found to be displayed</strong></font></td>
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
                                                      </table></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2"><form id="detailsform" name="detailsform" method="post" action="" onsubmit="return false">
                                <div style="display:none">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div id="colorboxdatagrid" style='background:#fff;'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                            <tr >
                                              <td class="imp_fontheading"><div id="tabdescription" style="text-align:center;">&nbsp;</div></td>
                                            </tr>
                                            <tr>
                                              <td align="center"><div  >
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td colspan="2" ><div id="errormsg" align="center"></div>
                                                        <div id="displaygriddata" style="overflow:auto;padding:0px; height:290px; width:759px;"> </div></td>
                                                    </tr>
                                                    <tr>
                                                      <td width="50%" align="left" id="colorbox-error">&nbsp;</td>
                                                      <td  width="50%" align="right"  height="60px" id="displaybutton" ></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table>
                                </div>
                              </form></td>
                          </tr>
                          <tr>
                            <td colspan="2"><form id="colorboxeditform" name="colorboxeditform" method="post" action="" onsubmit="return false">
                                <div style="display:none">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div  id='displaygridtab' style='background:#fff; width:550px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                            <tr>
                                              <td colspan="2" id="companyname" align="center" style="font-size:14px; font-weight:bold; color:#F00"></td>
                                            </tr>
                                            <tr><td colspan="2"  height="10px"></td></tr>
                                            <tr bgcolor="#f7faff">
                                              <td width="21%" align="left" valign="top" id="headingname" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">&nbsp;</td>
                                              <td width="79%" valign="top" id="fieldtype" align="left">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="65%" height="35px" align="left" id="form-error1"></td>
                                                  <td width="45%" align="right"><input type="button" name="update" class="swiftchoicebutton" value="Update" id="update" onclick="updatedetails()"/>
                                                    &nbsp;&nbsp;
                                                    <input type="button" value="Close" id="closebutton" name="closebutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                </tr>
                                              </table></td>
                                            </tr>
                                          </table>
                                      </div></td>
                                    </tr>
                                  </table>
                                </div>
                                <div style="display:none">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div  id='displaystategrid' style='background:#fff; width:550px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                             <tr>
                                              <td colspan="2" id="selectcompanyname" align="center" style="font-size:14px; font-weight:bold; color:#F00"></td>
                                            </tr>
                                            <tr><td colspan="2" height="10px"></td></tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" id="headingname" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">STATE:</td>
                                              <td align="left" valign="top" ><select name="state" class="swiftselect-mandatory" id="state" onchange="getdistrict('districtcodedisplay',this.value);" onkeyup="getdistrict('districtcodedisplay',this.value);"  style="width:200px;">
                                                  <option value="">Select A State</option>
                                                  <?php include('../inc/state.php'); ?>
                                                </select></td>
                                            </tr>
                                            <tr>
                                              <td  align="left" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">DISTRICT:</td>
                                              <td valign="top" align="left" id="districtcodedisplay"><select name="district"  id="district" style="width:200px;">
                                                  <option value="">Select A State First</option>
                                                </select></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" style="font-weight:bold;">&nbsp;</td>
                                              <td valign="top" align="left">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="65%" height="35px" align="left" id="form-error1"></td>
                                                  <td width="45%" align="right"><input type="button" name="update" class="swiftchoicebutton" value="Update" id="update" onclick="updatedetails()"/>
                                                    &nbsp;&nbsp;
                                                    <input type="button" value="Close" id="closebutton" name="closebutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                </tr>
                                              </table></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table>
                                </div>
                                <div style="display:none">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div  id='displaychexckboxgrid' style='background:#fff; width:550px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                            <tr>
                                              <td colspan="2" id="checkcompanyname" align="center" style="font-size:14px; font-weight:bold; color:#F00"></td>
                                            </tr>
                                            <tr><td colspan="2"  height="10px"></td></tr>
                                            <tr bgcolor="#f7faff">
                                              <td width="27%" align="left" bgcolor="#f7faff" id="activeheading" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">ACTIVE CUSTOMER:</td>
                                              <td width="73%" align="left" valign="top" bgcolor="#f7faff" ><input type="checkbox" name="activecustomer" id="activecustomer" disabled= "disabled"/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" bgcolor="#edf4ff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="disableheading">DISABLE LOGIN:</td>
                                              <td align="left" valign="top" bgcolor="#edf4ff" ><input type="checkbox" name="disablelogin" id="disablelogin" disabled= "disabled"/></td>
                                            </tr>
                                            <tr>
                                              <td  align="left" bgcolor="#F7FAFF" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="corporateheading">CORPORATE ORDER:</td>
                                              <td align="left" valign="top" bgcolor="#F7FAFF"><input type="checkbox" name="corporateorder" id="corporateorder"  disabled= "disabled"/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" bgcolor="#edf4ff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="companyheading">COMPANY CLOSED:</td>
                                              <td align="left" valign="top" bgcolor="#edf4ff"><input type="checkbox" name="companyclosed" id="companyclosed"  /></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="isdealerheading">IS DEALER/RELYON</td>
                                              <td valign="top" align="left"><input type="checkbox" name="isdealer" id="isdealer" disabled= "disabled"/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" bgcolor="#edf4ff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="displayinwebsiteheading">DISPLAY IN WEBSITE</td>
                                              <td align="left" valign="top" bgcolor="#edf4ff"><input type="checkbox" name="displayinwebsite" id="displayinwebsite" disabled= "disabled"/ ></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="promotionalsmsheading">PROMOTIONAL SMS:</td>
                                              <td align="left" valign="top" bgcolor="#f7faff"><input type="checkbox" name="promotionalsms" id="promotionalsms" /></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" bgcolor="#edf4ff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px" id="promotionalemailheading">PROMOTIONAL EMAIL:</td>
                                              <td align="left" valign="top" bgcolor="#edf4ff"><input type="checkbox" name="promotionalemail" id="promotionalemail" /></td>
                                            </tr>
                                            <tr><td colspan="2" height="10px"></td></tr>
                                            <tr>
                                              <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="65%" height="35px" align="left" id="form-error3"></td>
                                                  <td width="45%" align="right"><input type="button" name="update3" class="swiftchoicebutton" value="Update" id="update3" onclick="updatecheckboxdetails()"/>
                                                    &nbsp;&nbsp;
                                                    <input type="button" value="Close" id="closebutton3" name="closebutton3"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                </tr>
                                              </table></td>
                                            </tr>
                                          </table>
                                      </div></td>
                                    </tr>
                                  </table>
                                </div>
                                <div style="display:none">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div  id='displaycontactgrid' style='background:#fff; width:550px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                             <tr>
                                              <td colspan="2" id="contactcompanyname" align="center" style="font-size:14px; font-weight:bold; color:#F00"></td>
                                            </tr>
                                            <tr><td colspan="2"  height="10px"></td></tr>
                                             <tr bgcolor="#f7faff">
                                              <td colspan="2" align="left" bgcolor="#edf4ff" class="imp_fonttab" style="text-align:left" ><span style="color:#F00">Contact Details <span id="contactslnocount"></span></span> </td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" bgcolor="#f7faff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">TYPE:</td>
                                              <td align="left" valign="top" bgcolor="#f7faff" ><select name="selectiontype" id="selectiontype" style="width:167px" class="swiftselect">
                                                <option value="" selected="selected" >------Select-----</option>
                                                <option value="general" >General</option>
                                                <option value="gm/director">GM/Director</option>
                                                <option value="hrhead">HR Head</option>
                                                <option value="ithead/edp">IT-Head/EDP</option>
                                                <option value="softwareuser" >Software User</option>
                                                <option value="financehead">Finance Head</option>
                                                <option value="others" >Others</option>
                                              </select><input type="hidden" id="contactslno" name="contactslno" /><input type="hidden" id="fieldslno" name="fieldslno" /></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" bgcolor="#edf4ff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">NAME:</td>
                                              <td align="left" valign="top" bgcolor="#edf4ff" ><input name="name" type="text" class="swifttext" id="name"  style="width:160px"  maxlength="100"  autocomplete="off"/></td>
                                            </tr>
                                            <tr>
                                              <td  align="left" bgcolor="#F7FAFF" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">PHONE:</td>
                                              <td align="left" valign="top" bgcolor="#F7FAFF"><input name="phone" type="text"class="swifttext" id="phone" style="width:160px" maxlength="100"  autocomplete="off" /></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" bgcolor="#edf4ff" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">CELL:</td>
                                              <td align="left" valign="top" bgcolor="#edf4ff"><input name="cell" type="text" class="swifttext" id="cell" style="width:160px"  maxlength="10"  autocomplete="off"/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" style="font-weight:bold;COLOR: #000000;FONT-FAMILY:calibri;FONT-SIZE: 13px">EMAILID:</td>
                                              <td valign="top" align="left"><input name="emailid" type="text" class="swifttext" id="emailid" style="width:160px"  maxlength="200"  autocomplete="off"/></td>
                                            </tr>
                                            <tr><td colspan="2" height="10px"></td></tr>
                                            <tr>
                                              <td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="65%" height="35px" align="left" id="form-error2"></td>
                                                  <td width="45%" align="right"><input type="button" name="update1" class="swiftchoicebutton" value="Update" id="update1" onclick="updatecontactdetails()"/>
                                                    &nbsp;&nbsp;
                                                    <input type="button" value="Close" id="closebutton1" name="closebutton1"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                                </tr>
                                              </table></td>
                                            </tr>
                                          </table>
                                      </div></td>
                                    </tr>
                                  </table>
                                </div>
                              </form></td>
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
