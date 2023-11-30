<?php 
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$enablebilling = $resultfetch['enablebilling'];
	if($enablebilling <> 'yes' )
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
<script language="javascript" src="../functions/outstandingregister.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/branchheadselectjs.php?dummy=<?php echo (rand());?>" ></script>

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
                            <td width="27%" class="active-leftnav">Report - Outstanding Register</td>
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
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr bgcolor="#f7faff">
                                          <td width="29%" align="left" valign="top" bgcolor="#EDF4FF">As On  Date: </td>
                                          <td width="71%" align="left" valign="top" bgcolor="#EDF4FF"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/> <input type="hidden" name="flag" id="flag" value="true" />
                                            <input type="hidden" name="hiddentotalinvoices" id="hiddentotalinvoices" value="" />
                                            <input type="hidden" name="hiddentotaloutstanding" id="hiddentotaloutstanding" value="" /></td>
                                        </tr>
                                        <tr bgcolor="#f7faff">
                                          <td align="left" valign="top" bgcolor="#EDF4FF">Aged Above (Days):</td>
                                          <td align="left" valign="top" bgcolor="#EDF4FF"><input name="aged" type="text" class="swifttext-mandatory" id="aged" value="0" size="30" maxlength="5" autocomplete="off" /></td>
                                        </tr>
                                         <tr bgcolor="#f7faff">
                                          <td width="22%" align="left" valign="top" bgcolor="#f7faff">Dealer: </td>
                                          <td width="78%" align="left" valign="top" bgcolor="#f7faff" id="displaydealer">&nbsp;</td>
                                        </tr>
                                         

                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td width="29%" align="left" valign="top" bgcolor="#EDF4FF">Sort By:</td>
                                            <td width="71%" align="left" valign="top" bgcolor="#EDF4FF"><select name="sortby" class="swiftselect" id="sortby"  style="width:200px">
                                              <option value="age" selected="selected">Age</option>
                                              <option value="outstandingamount">Outstanding Amount</option>
                                            </select>
                                              <br/>
                                            </label></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Sort:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><select name="sort" class="swiftselect" id="sort"  style="width:200px">
                                              <option value="asc" selected="selected">Ascending</option>
                                              <option value="desc">Descending</option>
                                            </select></td>
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
                                                <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getinvoicedetails(''); " style="cursor:pointer" class="grid-active-tabclass">All Time</td>
                                                <td width="2">&nbsp;</td>
                                                <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');displayoutstdingtotal()" style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                                <td width="2">&nbsp;</td>
                                                <td width="140" align="center" ></td>
                                                <td width="140" align="center" ></td>
                                                <td width="140" align="center" ></td>
                                                <td width="140" align="center" ></td>
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
                                                          <td><div id="invoicedetails" align="left"> </div></td>
                                                        </tr>
                                                      </table>
                                                      <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                                    </div></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                                                            <tr><td colspan="2">&nbsp;</td></tr>
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr>
                                          <td width="12%" valign="top"><strong>Total Invoices:</strong></td>
                                          <td width="13%"><input name="totalinvoices" type="text" class="swifttext-readonly" id="totalinvoices" size="15" autocomplete="off" value=""  readonly="readonly" style="text-align:right"/></td>
                                          <td width="15%" valign="top"><strong>Total Outstanding:</strong></td>
                                          <td width="16%"><input name="totaloutstanding" type="text" class="swifttext-readonly" id="totaloutstanding" size="15" autocomplete="off" value="" readonly="readonly" style="text-align:right"/></td>
                                          <td width="7%" valign="top">&nbsp;</td>
                                          <td width="11%">&nbsp;</td>
                                          <td width="11%" valign="top">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
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
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php }?>