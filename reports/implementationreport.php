<?php
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$branch = $resultfetch['branch'];
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/imp-colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/implementationreport.js?dummy=<?php echo (rand());?>"></script>
<!-- <script language="javascript" src="../functions/getdistrictfunction.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictfunction-imp.php?dummy=<?php echo (rand());?>"></script> -->
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
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
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="4%" height="30px" class="imptabheadnone">&nbsp;</td>
                                  <td width="18%" onclick="tabopenimp2('1','tabg1')" style="cursor:pointer" class="imptabheadactive imp_fonttab" id="tabg1h1">Statistics</td>
                                  <td width="4%" class="imptabheadnone">&nbsp;</td>
                                  <td width="18%" onclick="tabopenimp2('2','tabg1')"style="cursor:pointer"  class="imptabhead imp_fonttab" id="tabg1h2">Customer History</strong></td>
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
                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div style="display:block" align="justify" id="tabg1c1" >
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="70%" height="35px" id="form-error" align="center"></td>
                                              <td width="17%" id="form-error" align="right"><input name="search" type="submit" class="swiftchoicebuttonbig" id="search" value="Advanced Search"  onclick="displayDivfilter('filterdiv')"  /></td>
                                              <td width="13%" valign="top" align="right"><div onclick="displaybranch('<?php echo $branch;?>');displaybranchsaleswise('<?php echo $branch;?>');getimpalldatadetails('all');" ><img src="../images/imax-customer-refresh.jpg" alt="Refresh Data" title="Refresh Data Data" style="cursor:pointer" /><span class="resendtext">Refresh Data</span></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="3"><div id="filterdiv" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                                                    <tr>
                                                      <td valign="top"><div>
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td width="100%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Search Option</td>
                                                            </tr>
                                                            <tr>
                                                              <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFD3A8" style="border:dashed 1px #545429">
                                                                  <tr>
                                                                    <td width="57%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" >
                                                                        <tr>
                                                                          <td colspan="2" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                              <tr>
                                                                                <td width="31%" valign="top" style="border-right:1px solid #CCCCCC"><table width="100%" border="0" cellspacing="0" cellpadding="6">
                                                                                    <tr>
                                                                                    <tr>
                                                                                      <td>Dealer:</td>
                                                                                      <td align="left" valign="top"   height="10"><select name="currentdealer" class="swiftselect" id="currentdealer" style="width:180px;">
                                                                                          <option value="">ALL</option>
                                                                                          <?php include('../inc/implementerdealer.php');?>
                                                                                        </select></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td>Implementer:</td>
                                                                                      <td align="left" valign="top"   height="10"><select name="implementer" class="swiftselect" id="implementer" style="width:180px;">
                                                                                          <option value="">ALL</option>
                                                                                          <?php include('../inc/implementer.php');?>
                                                                                        </select></td>
                                                                                    </tr>
                                                                                  </table></td>
                                                                                <td width="35%" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="6">
                                                                                    <tr>
                                                                                      <td>Type:</td>
                                                                                      <td align="left" valign="top"   height="10" ><select name="type" class="swiftselect" id="type" style="width:180px;">
                                                                                          <option value="">ALL</option>
                                                                                          <option value="Not Selected">Not Selected</option>
                                                                                          <?php include('../inc/custype.php');?>
                                                                                        </select></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td>Category:</td>
                                                                                      <td align="left" valign="top"   height="10" ><select name="category" class="swiftselect" id="category" style="width:180px;">
                                                                                          <option value="">ALL</option>
                                                                                          <option value="Not Selected">Not Selected</option>
                                                                                          <?php include('../inc/category.php');?>
                                                                                        </select></td>
                                                                                    </tr>
                                                                                  </table></td>
                                                                                <td width="34%" valign="top" style="border-left:1px solid #CCCCCC; padding-left:6px"><table width="96%" border="0" cellspacing="0" cellpadding="2" >
                                                                                    <tr>
                                                                                      <td colspan="3" valign="top" style="padding:0"></td>
                                                                                    </tr>
                                                                                    <tr >
                                                                                      <td colspan="3" valign="top" align="left"><strong>Status</strong></td>
                                                                                    </tr >
                                                                                    <tr >
                                                                                      <td colspan="3" valign="top" align="left" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8"><div style="height:120px; overflow:auto">
                                                                                      <!-- <input checked="checked" type="checkbox" name="summarize[]" id="status9" value="status9"/>
                                                                                          <label for="status9">Not created (Raw)</label>
                                                                                          <br /> -->
                                                                                          <input checked="checked" type="checkbox" name="summarize[]" id="status2" value="status2"/>
                                                                                          <label for="status2"> Awaiting Branch Head Approval</label>
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
                                                                                    <tr >
                                                                                      <td width="8%"><input type="checkbox" name="selectstatus" id="selectstatus" checked="checked" onchange="selectdeselectcommon('selectstatus','summarize[]')" /></td>
                                                                                      <td width="92%" align="left"><label for="selectstatus">Select All / None</label></td>
                                                                                    </tr>
                                                                                  </table></td>
                                                                              </tr>
                                                                            </table></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td colspan="3" height="5px"></td>
                                                                        </tr>
                                                                        <tr>
                                                                          <td width="68%" height="35" align="left" valign="middle"  ><div id="filter-form-error1"></div></td>
                                                                          <td width="32%" align="left" valign="middle"  ><input name="filter1" type="button" class="swiftchoicebutton-red" id="filter1" value="Search" onclick="advancesearchimplementer();" />
                                                                            &nbsp;
                                                                            <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);">
                                                                            &nbsp;
                                                                            <input name="close1" type="button" class="swiftchoicebutton" id="close1" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" /></td>
                                                                        </tr>
                                                                      </table></td>
                                                                  </tr>
                                                                </table></td>
                                                            </tr>
                                                            <tr>
                                                              <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"></td>
                                                            </tr>
                                                          </table>
                                                        </div></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr valign="top">
                                                    <td width="48%" ><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        
                                                        <tr>
                                                          <td align="left" style="font-size:12px" ><strong>All Data/Implementation Due</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="5" class="imp_table-border">
                                                              <tr class="imp_tr-grid-header">
                                                              <td width="20%" align="center" class="imp_td-border">Total Records</td>
                                                                <td width="25%" align="center" class="imp_td-border">Total Due</td>
                                                                <td width="25%" align="center" class="imp_td-border">With Sales Due</td>
                                                                <td width="45%" align="center" class="imp_td-border">With Implementation Due</td>
                                                              </tr>
                                                              <tr>
                                                              <td  align="center" class="imp_td-border" ><span id="alldata_total" class="imp_fontstyle"></span></td>
                                                                <td  align="center" class="imp_td-border" ><span id="implementation_total" class="imp_fontstyle"></span></td>
                                                                <td  align="center" class="imp_td-border"><span id="implementation_sale" class="imp_fontstyle" ></span></td>
                                                                <td  align="center" class="imp_td-border"><span id="implementation_withimp" class="imp_fontstyle" ></span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                </table></td>
                                            </tr></table><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr><td colspan=2>&nbsp;</td></tr>
                                            <tr>
                                              <td colspan=3><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td align="left" style="font-size:12px" ><strong>Status wise</strong>
                                                      <!-- <input type="hidden" id="branchcount"  name="branchcount" value="<?php //echo($branchcount); ?>"/></td> -->
                                                  </tr>
                                                  <tr>
                                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="10" class="imp_table-border">
                                                        <tr class="imp_tr-grid-header">
                                                          <td width="55%" align="center" class="imp_td-border">Status</td>
                                                          <td width="45%" align="center" class="imp_td-border">Count</td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Awaiting Branch Head Approval</td>
                                                          <td  align="center" class="imp_td-border" ><span id="status_branch" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Fowarded back to Sales Person</td>
                                                          <td  align="center" class="imp_td-border" ><span id="status_branchreject" class="imp_fontstyle1"></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Awaiting Co-ordinator Approval</td>
                                                          <td  align="center" class="imp_td-border"><span id="status_coordinatorapproval" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Fowarded back to Branch Head</td>
                                                          <td  align="center" class="imp_td-border"><span id="status_coordinatorreject" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Implementation, Yet to be Assigned</td>
                                                          <td  align="center" class="imp_td-border"><span id="status_pending" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Assigned For Implementation</td>
                                                          <td  align="center" class="imp_td-border"><span id="status_assigned" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Implementation in progess</td>
                                                          <td  align="center" class="imp_td-border"><span id="status_progess" class="imp_fontstyle1"></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" >Implementation Completed</td>
                                                          <td  align="center" class="imp_td-border"><span  id="status_completed" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" class="imp_td-border imp_fontstyle2" bgcolor="#D3D3D3"><strong>Total Due</strong></td>
                                                          <td  align="center" class="imp_td-border" bgcolor="#D3D3D3" style="font-weight:bold" ><span  id="status_totaldue" class="imp_fontstyle1" ></span></td>
                                                        </tr>
                                                      </table></td>
                                              </tr>
                                              </table></td>
                                            </tr>
                                                <tr>
                                                <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                  <td width="49%" height="35px" align="left"  valign="middle"  style="font-size:12px; padding-bottom:5px" ><span  id="tabdisplayerror"></span></td>
                                                  <td width="49%" height="35px" align="left"  valign="middle"  style="font-size:12px; padding-bottom:5px" ><span  id="tabdisplaymsg"></span></td>
                                                  </tr>
                                                  </table></td>
                                                </tr>
                                                        
                                                        <tr >
                                                          <td width="60%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td valign="top"><div id="tabgriddisplayimp" style="display:block;height:400px; overflow:auto"></div></td>
                                                              </tr>
                                                            </table></td>
                                                        
                                                          <td width="60%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td valign="top"><div id="tabgriddisplaysale" style="display:block; height:400px; overflow:auto"></div></td>
                                                              </tr>
                                                            </table></td>
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
                                              <td width="77%" valign="top" style="border-bottom:#cccccc 1px solid; text-align: right;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
                                                  <tr>
                                                    <td valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" class="header-line">&nbsp;View Details</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="right" style="padding-bottom:3px; padding-top:10px"><input name="search2" type="submit" class="swiftchoicebuttonbig" id="search2" value="Advanced Search"  onclick="displayDivfilter('filterdiv2')"  /></td>
                                                              </tr>
                                                              <tr>
                                                              <tr>
                                                                <td><div id="filterdiv2" style="display:none;">
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                                                                      <tr>
                                                                        <td valign="top"><div>
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                              <tr>
                                                                                <td width="100%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Search Option</td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" bgcolor="#FFD3A8" style="border:dashed 1px #545429">
                                                                                    <tr>
                                                                                      <td width="52%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" style=" border-right:1px solid #CCCCCC">
                                                                                          <tr>
                                                                                            <td style="padding:3px" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="6">
                                                                                                <tr>
                                                                                                  <td>Dealer:</td>
                                                                                                  <td align="left" valign="top"   height="10"><select name="currentdealer2" class="swiftselect" id="currentdealer2" style="width:180px;">
                                                                                                      <option value="">ALL</option>
                                                                                                      <?php include('../inc/firstdealer.php');?>
                                                                                                    </select></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td>Type:</td>
                                                                                                  <td align="left" valign="top"   height="10" ><select name="type2" class="swiftselect" id="type2" style="width:180px;">
                                                                                                      <option value="">ALL</option>
                                                                                                      <option value="Not Selected">Not Selected</option>
                                                                                                      <?php include('../inc/custype.php');?>
                                                                                                    </select></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td>Category:</td>
                                                                                                  <td align="left" valign="top"   height="10" ><select name="category2" class="swiftselect" id="category2" style="width:180px;">
                                                                                                      <option value="">ALL</option>
                                                                                                      <option value="Not Selected">Not Selected</option>
                                                                                                      <?php include('../inc/category.php');?>
                                                                                                    </select></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td>Implementer:</td>
                                                                                                  <td align="left" valign="top"   height="10"><select name="implementer2" class="swiftselect" id="implementer2" style="width:180px;">
                                                                                                      <option value="">ALL</option>
                                                                                                      <?php include('../inc/implementer.php');?>
                                                                                                    </select></td>
                                                                                                </tr>
                                                                                              </table></td>
                                                                                          </tr>
                                                                                        </table></td>
                                                                                      <td width="48%" valign="top" style="padding-left:3px; text-align: left;"><table width="84%" border="0" cellspacing="0" cellpadding="2" >
                                                                                          <tr >
                                                                                            <td colspan="3" valign="top" align="left"><strong>Status</strong></td>
                                                                                          </tr >
                                                                                          <tr >
                                                                                            <td colspan="3" valign="top" align="left" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8"><div style="height:13 0px; overflow:auto">
                                                                                            <!-- <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist9" value="statuslist9"/>
                                                                                                <label for="statuslist9">Not created (Raw)</label>
                                                                                                <br /> -->
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist2" value="statuslist2"/>
                                                                                                <label for="statuslist2"> Awaiting Branch Head Approval</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist1" value="statuslist1" />
                                                                                                <label for="statuslist1"> Fowarded back to Sales Person</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist3" value="statuslist3"/>
                                                                                                <label for="statuslist3">Awaiting Co-ordinator Approval</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist4" value="statuslist4"/>
                                                                                                <label for="statuslist4">Fowarded back to Branch Head</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist5" value="statuslist5" />
                                                                                                <label for="statuslist5">Implementation, Yet to be Assigned</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist6" value="statuslist6"/>
                                                                                                <label for="statuslist6"> Assigned For Implementation</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist7" value="statuslist7"/>
                                                                                                <label for="statuslist7">Implementation in progess</label>
                                                                                                <br />
                                                                                                <input checked="checked" type="checkbox" name="summarizelist[]" id="statuslist8" value="statuslist8"/>
                                                                                                <label for="statuslist8"> Implementation Completed</label>
                                                                                              </div></td>
                                                                                          </tr>
                                                                                          <tr >
                                                                                            <td align="left" width="8%"><input type="checkbox" name="selectstatuslist" id="selectstatuslist" checked="checked" onchange="selectdeselectcommon('selectstatuslist','summarizelist[]')" /></td>
                                                                                            <td width="92%" align="left"><label for="selectstatuslist">Select All / None</label></td>
                                                                                          </tr>
                                                                                        </table></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td width="52%" height="35" align="left" valign="middle"  ><div id="filter-form-error"></div></td>
                                                                                      <td width="48%" align="left" valign="middle"  ><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="advancesearch();" />
                                                                                        &nbsp;
                                                                                        <input type="button" name="reset_form1" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues2(this.form);">
                                                                                        &nbsp;
                                                                                        <input name="close" type="button" class="swiftchoicebutton" id="close" value="Close" onclick="document.getElementById('filterdiv2').style.display='none';" /></td>
                                                                                    </tr>
                                                                                  </table></td>
                                                                              </tr>
                                                                              <tr>
                                                                                <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"></td>
                                                                              </tr>
                                                                            </table>
                                                                          </div></td>
                                                                      </tr>
                                                                    </table>
                                                                  </div></td>
                                                              </tr>
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                          <tr>
                                                                            <td id="displaydetails"><table width="100%" border="0" cellspacing="0" cellpadding="5" >
                                                                                <tr>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class = "imp_tr-grid-header1 imp_title_bar">
                                                                                        <td valign="top" ><input type="hidden" name="filepath1" id="filepath1" />
                                                                                          <input type="hidden" name="lastslno" id="lastslno" />
                                                                                          <input type="hidden" name="filepath2" id="filepath2" />
                                                                                          <input type="hidden" name="filepath3" id="filepath3" />
                                                                                          <input type="hidden" name="filepath4" id="filepath4" />
                                                                                          <input type="hidden" name="filepath5" id="filepath5" />
                                                                                          <input type="hidden" name="filepath6" id="filepath6" />
                                                                                          <input type="hidden" name="filepath7" id="filepath7" />
                                                                                          <input type="hidden" name="implonlineslno" id="implonlineslno" />
                                                                                          <span>Current Status </span>
                                                                                        <td></td>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td valign="top" id="implementationstatus" height="12px" style="color:#FF0000; font-weight:bold">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr >
                                                                                              <td valign="top" ><strong>Remarks</strong> : <span id="implementationremarks">Not Avaliable</span></td>
                                                                                            </tr>
                                                                                            <tr >
                                                                                              <td ><div id="iccattachdisplay" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                                                    <tr>
                                                                                                      <td colspan="2" id="iccattachname">&nbsp;</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td width="18%"><strong>Date</strong> :</td>
                                                                                                      <td width="82%" id="iccattachdatedisplay">&nbsp;</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td><strong>By</strong> :</td>
                                                                                                      <td id="iccattachcreatedby">&nbsp;</td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td colspan="2">&nbsp;</td>
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
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1 imp_title_bar">
                                                                                        <td valign="top"><span>Visit Details </span></td>
                                                                                      </tr>
                                                                                      <tr >
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td height="12px"><strong>Total Visits: <span id="visittotal"></span></strong></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td height="10px"><strong>Schedule</strong>:</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td id="visitgriddisplay" valign="top" width="100%">&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1 imp_title_bar">
                                                                                        <td valign="top"><span>Shipment Information </span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td><div align="center" id="shippmentdisplaydiv1" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td valign="top" ><strong>Invoice Remarks :</strong></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td valign="top" id="shipinvoicedisplay">&nbsp;</td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"><div align="center" id="shippmentdisplaydiv2" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td valign="top"><strong>Manual Remarks :</strong></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td id="shipmanualdisplay" valign="top">&nbsp;</td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"><div id="shippmentdisplaydiv3" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td align="center"><strong>Not Applicable</strong></td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top">&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1"class="imp_dashboard_module" height="160px" >
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Status of Visits </span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td id="statuvisitdisplay" style="padding:2px" valign="top">&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1"class="imp_dashboard_module" height="160px" >
                                                                                      <tr  class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Additional Information </span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td valign="top" ><strong>Invoice No</strong>: <span id="invoiceno"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"><strong>PO No/Date</strong> : <span id="datedisplay"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"><strong>PO File</strong> : <span id="pofile"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"><strong>No of Companies to be processed </strong>: <span id="noofcompanydisplay"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"><strong>No of Months to be processed</strong> : <span id="noofmonthdisplay"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top" ><strong>Processing from month</strong> : <span id="processmonthdisplay"></span></td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr  class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td><span>Requriment Analysis</span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td colspan="2" id="raffilename">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td width="18%"  height="10px"><strong>Date</strong> :</td>
                                                                                              <td width="82%"  height="10px" id="rafdatedisplay">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td  height="10px"><strong>By</strong> :</td>
                                                                                              <td  height="10px" id="rafcreatedby">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td colspan="2">&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Add-on Module </span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td valign="top" id="addongriddisplay" >&nbsp;</td>
                                                                                            </tr >
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Customization Information </span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td valign="top"  height="10px"><strong>Status</strong> : <span id="custstatusdisplay"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"  ><strong>Remarks</strong> : <span id="custremarksdisplay"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"  ><strong>Delivered files</strong>:</td>
                                                                                            </tr>
                                                                                            <tr valign="top">
                                                                                              <td ><div id="tabgroupgridc2" style="overflow:auto;; padding:1px;" align="center">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td><div id="tabgroupgridc1_2" align="center"></div></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td><div id="tabgroupgridc2link" style="height:20px; padding:1px;" align="left"> </div></td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                  <div id="regresultgrid2" style="overflow:auto; display:none; padding:1px;" align="center"></div>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Web Implementation</span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" >
                                                                                            <tr>
                                                                                              <td valign="top"><div id="webimplementationdisplaydiv1" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td id="webimplementationdisplay" valign="top">&nbsp;</td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td>&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top" ><div id="webimplementationdisplaydiv2" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td align="center" valign="top"><strong>Not Applicable</strong></td>
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
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Attendance Integration</span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" >
                                                                                            <tr>
                                                                                              <td  height="10px" colspan="2" valign="top"><strong>Vendor Details</strong> : <span id="vendordisplay"></span></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td colspan="2"  height="10px" id="aiffilename" valign="top">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td width="18%" height="10px" valign="top"><strong>Date</strong> :</td>
                                                                                              <td width="82%" id="aifdatedisplay" valign="top"  height="10px">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td valign="top"  height="10px"><strong>By</strong> :</td>
                                                                                              <td id="aifcreatedby" valign="top"  height="10px">&nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td colspan="2">&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Invoice Details</span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td  valign="top" ><div id="invoicedisplay"  style="width:330px;overflow:auto"></div></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td>&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1" class="imp_dashboard_module" height="160px">
                                                                                      <tr class="imp_tr-grid-header1  imp_title_bar">
                                                                                        <td valign="top"><span>Requirement Analysis Format</span></td>
                                                                                      </tr>
                                                                                      <tr>
                                                                                        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                            <tr>
                                                                                              <td height="10px"><strong>Total : <span id="raftotal">Not Avaliable</span></strong></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td height="10px"><strong>Files</strong>:</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td id="rafgriddisplay" valign="top">&nbsp;</td>
                                                                                            </tr>
                                                                                          </table></td>
                                                                                      </tr>
                                                                                    </table></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td width="50%">&nbsp;</td>
                                                                                  <td width="50%">&nbsp;</td>
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
                                              <td class="imp_fontheading"><div id="tabdescription" style="text-align:center; width:709px;">&nbsp;</div></td>
                                            </tr>
                                            <tr>
                                              <td  align="center"><div style="overflow:auto;padding:0px; height:290px; width:709px; ">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td ><div id="displaygriddata"> </div></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                          </table>
                                          <div align="right" style="padding-top:15px; padding-right:25px">
                                            <input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>
                                          </div>
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