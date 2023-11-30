<? 
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$enablebilling = $resultfetch['enablematrixbilling'];
	$relyonexecutive = $resultfetch['relyonexecutive'];
	if($enablebilling <> 'yes' && $relyonexecutive <> 'yes' )
	{
		$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
		$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
		$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
		echo($grid);
	}
	else
	{
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox-invoicing.css?dummy=<? echo (rand());?>"  />
<script language="javascript" src="../functions/matrixinvoiceregister.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/matrixbranchheadselectjs.php?dummy=<? echo (rand());?>" ></script>
<script language="javascript" src="../functions/matrixleftdealerselectjs.php?dummy=<? echo (rand());?>" ></script>
<script language="javascript"  src="../functions/colorbox.js?dummy=<? echo (rand());?>" ></script>

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
                            <td width="27%" class="active-leftnav">Report - Matrix Invoice Register</td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td width="22%" align="left" valign="top" bgcolor="#EDF4FF">From Date: </td>
                                            <td width="78%" align="left" valign="top" bgcolor="#EDF4FF"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                              <input type="hidden" name="flag" id="flag" value="true" />
                                              <input type="hidden" name="onlineslno" id="onlineslno" value="" />
                                              <input type="hidden" name="hiddentotalinvoices" id="hiddentotalinvoices" value="" />
                                              <input type="hidden" name="hiddentotalsalevalue" id="hiddentotalsalevalue" value="" />
                                              <input type="hidden" name="hiddentotaltax" id="hiddentotaltax" value="" />
                                              <input type="hidden" name="hiddentotalamount" id="hiddentotalamount" value="" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="22%" align="left" valign="top" bgcolor="#f7faff">All Time</td>
                                            <td width="78%" align="left" valign="top" bgcolor="#f7faff" ><input name="alltime" type="checkbox" id="alltime" onclick="disablethedates()" /></td>
                                          </tr>
                                        </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td width="22%" align="left" valign="top" bgcolor="#EDF4FF">To Date:</td>
                                            <td width="78%" align="left" valign="top" bgcolor="#EDF4FF"><label for="sto"></label>
                                              <label for="spp">
                                                <input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                              </label></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="22%" align="left" valign="top" bgcolor="#f7faff">Dealer: </td>
                                            <td width="78%" align="left" valign="top" bgcolor="#f7faff" id="displaydealer">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                                  <td width="22%" align="left" valign="top" bgcolor="#f7faff">Inactive Dealers: </td>
                                                  <td width="78%" align="left" valign="top" bgcolor="#f7faff" id="displayleftdealer" > </td>
                                              </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter('');" />
                                              &nbsp;
                                              <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');"/></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getinvoicedetails(''); " style="cursor:pointer" class="grid-active-tabclass">Today-All</td>
                                                  <td width="2px">&nbsp;</td>
                                                  <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');displayinvoicetotal()" style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                                  <td width="2">&nbsp;</td>
                                                  <td width="140px" align="center" ></td>
                                                  <td width="140px" align="center" ></td>
                                                  <td width="140px" align="center" ></td>
                                                  <td width="140px" align="center" ></td>
                                                  <td><div id="gridprocessing"></div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                                <tr class="header-line" >
                                                  <td width="222px"><div id="tabdescription"></div></td>
                                                  <td width="533px" align="center" style="left:16px;" ><span id="tabgroupgridwb1" ></span><span id="tabgroupgridwb2" ></span></td>
                                                  <td width="33px" >&nbsp;</td>
                                                  <td width="186px">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:270px; width:925px; padding:2px;" align="center">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td><div id="tabgroupgridc1_1" align="center" ></div></td>
                                                        </tr>
                                                        <tr>
                                                          <td><div id="tabgroupgridc1link" align="left" > </div></td>
                                                        </tr>
                                                      </table>
                                                      <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                                    </div>
                                                    <div id="tabgroupgridc2" style="overflow:auto;height:270px; width:925px; padding:2px; display:none;" align="center">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td colspan="2"><div id="tabgroupgridc2_1" ></div></td>
                                                        </tr>
                                                        <tr>
                                                          <td><div id="tabgroupgridc2link" align="left"> </div></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table>
                                                      <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" height="10px"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td width="12%" valign="top"><strong>Total Invoices:</strong></td>
                                            <td width="13%"><input name="totalinvoices" type="text" class="swifttext-readonly" id="totalinvoices" size="15" autocomplete="off" value="" readonly="readonly" style="text-align:right"/></td>
                                            <td width="13%" valign="top"><strong>Total Sale Value:</strong></td>
                                            <td width="14%"><input name="totalsalevalue" type="text" class="swifttext-readonly" id="totalsalevalue" size="15" autocomplete="off" value="" readonly="readonly"  style="text-align:right"/></td>
                                            <td width="8%" valign="top"><strong>Total Tax:</strong></td>
                                            <td width="14%"><input name="totaltax" type="text" class="swifttext-readonly" id="totaltax" size="15" autocomplete="off" value="" readonly="readonly"  style="text-align:right"/></td>
                                            <td width="11%" valign="top"><strong>Total Amount:</strong></td>
                                            <td width="15%"><input name="totalamount" type="text" class="swifttext-readonly" id="totalamount" size="15" autocomplete="off" value="" readonly="readonly"  style="text-align:right"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="8" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="border:1px solid #CCCCCC; border-top:none;">
                                                <tr bgcolor="#CCCCCC" height="22px">
                                                  <td width="2%" align="left"><img src="../images/plus.jpg" border="0" id="toggleimg2" name="toggleimg2" onClick="displayproductwisesummary('productwisesummary','toggleimg2');" align="absmiddle"  style="cursor:pointer; padding-left:2px"/></td>
                                                  <td width="98%" align="left"><strong><span onClick="displayproductwisesummary('productwisesummary','toggleimg2');" style="cursor:pointer;">Product Wise Summary</span></strong></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"><div id = "productwisesummary" style="display:none">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td  width="50%" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                              <tr>
                                                                <td><div align="center" ><strong>Items (Software)</strong></div></td>
                                                              </tr>
                                                              <tr>
                                                                <td ><div id="productsummarydiv" style="padding-left:4px"></div></td>
                                                              </tr>
                                                            </table></td>
                                                         
                                                        </tr>
                                                        <tr height="5px">
                                                          <td ></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" height="10px"></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2"><div style="display:none">
                                <form id="detailsform" name="detailsform" method="post" action="" onsubmit="return false">
                                  <input type="hidden" name="productslno" id="productslno"  value=" "/>
                                  <div style="display:none">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="productdetailsgrid" style='background:#fff;width:909px;'>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC">
                                              <tr class="header-line">
                                                <td width="45%"><span style="padding-left:4px;">Invoice Details</span></td>
                                                <td width="24%"><span id="productdetailsgridwb1" style="text-align:center">&nbsp;</span></td>
                                                <td width="31%"><div align="right"></div></td>
                                              </tr>
                                              <tr>
                                                <td colspan="3" align="center"><div style="overflow:auto;padding:0px; height:290px; width:909px; ">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td align="center"><div id="productdetailsgridc1_1" > </div></td>
                                                      </tr>
                                                      <tr>
                                                        <td ><div id="productdetailsgridc1link" style="height:20px;  padding:2px;" align="centre"> </div></td>
                                                      </tr>
                                                    </table>
                                                  </div></td>
                                              </tr>
                                            </table>
                                            <div id="productdetailsresultgrid" style="overflow:auto; display:none; height:290px; width:909px; padding:2px;" align="center">&nbsp;</div>
                                               <div align="right" style="padding-top:15px; padding-right:25px"><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/> </div>
                                          </div></td>
                                      </tr>
                                    </table>
                                  </div>
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
<? }?>
