<?
$userid = imaxgetcookie('userid');
$query = "select inv_mas_dealer.businessname as dealername,inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_state.statename from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$relyonexecutive = $resultfetch['relyonexecutive'];
$statecode = $resultfetch['statecode'];

if($relyonexecutive == 'no')
{
		$grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
		$grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
		$grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
		echo($grid);
}
else
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
if($flag == 'true')
{
?>

<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/viewinvoice.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>

<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td valign="top" style="border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">View Invoices </td>
                            <td width="60%">&nbsp;</td>
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
                      <td><table width="97%" border="0" cellspacing="0" cellpadding="0"  align="center">
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv"  >
                                <form id="submitform" name="submitform" action="" method="post">
                                  <table width="100%" border="0"  cellpadding="0" cellspacing="0">
                                    <tr height="280px">
                                      <td height="200px"><input type ="hidden" name = "onlineslno" id = "onlineslno"><div class="invoicetext" align="center"  id="displayinvoicetext" style="display:block; "> No Invoice Selected </div>
                                        <div id="displaygridinfo" style="display:none;" ></div></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td width="49%"  id="productselectionprocess">&nbsp;</td>
                            <td width="51%" height="25px" style="padding:5px"  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
              <td height="35" align="left" valign="middle">&nbsp;&nbsp;
                  <div align="right">
                    &nbsp;&nbsp;
                    <input name="view" type="button" class="swiftchoicebuttonbig" id="view" value="View Invoice" onclick="viewinvoice()"/>
                    &nbsp;&nbsp;
                    <input name="send" type="button" class="swiftchoicebuttonbig" id="send" value="Resend Invoice" onclick="resendinvoice();" />
                 </div>
                 
              </tr>

</table></td>
                          </tr>
                          <tr>
                            <td colspan="2"><div >
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                                <tr><td style="border-right:1px solid #d1dceb;"  class="header-line">Filter: <a onclick="showhidefilterdiv()" class="filterdiv" style="cursor:pointer">[show/hide]</a></td></tr>
                                  <tr>
                                    <td valign="top"><div id="filterdiv"  style=" display:none1">
                                      <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                <tr>
                                                  <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                      <tr bgcolor="#EDF4FF">
                                                        <td width="22%">Search Text: 
                                                          <input type="hidden" name="searchtexthiddenfield" id="searchtexthiddenfield"/></td>
                                                        <td width="78%"><input name="searchcriteria" type="text" id="searchcriteria" size="50" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:1px solid #CCCCCC">
                                                            <tr>
                                                              <td width="21%" valign="top">In:
                                                                <input type="hidden" name="subselectionhiddenfield" id="subselectionhiddenfield"/></td>
                                                              <td width="79%"><div align="left"><label>
                                                                <input type="radio" name="databasefield" value="invoiceno" id="databasefield3" />
                                                                Invoice No</label>
                                                                &nbsp;
                                                                <label>
                                                                  <input type="radio" name="databasefield" id="databasefield0" value="customerid"/>
                                                                  Customer ID </label>
                                                                <label> &nbsp;
                                                                  <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                                  Business Name</label>
                                                                <br />
                                                                <br />
                                                                <label>
                                                                 
                                                                    <input type="radio" name="databasefield" value="contactperson" id="databasefield2" />
                                                                  Contact Person                                                                </label></div>
�                                                                </td>
                                                            </tr>
                                                        </table></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td>Order By:
                                                          <input type="hidden" name="orderbyhiddenfield" id"orderbyhiddenfield"/></td>
                                                        <td><div align="left">
                                                          <select name="orderby" id="orderby" class="swiftselect">
                                                            <option value="invoiceno" selected="selected">Invoice number </option>
                                                            <option value="customerid">Customer ID</option>
                                                            <option value="businessname" >Business Name</option>
                                                            <option value="contactperson">Contact Person</option>
                                                            <option value="date">Date</option>
                                                          </select>
                                                        </div></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td bgcolor="#f7faff"><div align="left">From Date:
                                                          <input type="hidden" name="hiddenfromdate" id="hiddenfromdate" />
                                                        </div></td>
                                                        <td bgcolor="#f7faff"><div align="left">
                                                          <input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_attachfromdate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>" readonly="readonly"/>
                                                          <input type="hidden" name="flag" id="flag" value="true" />
                                                        </div></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td bgcolor="#EDF4FF"><div align="left">To Date:
                                                          <input type="hidden" name="hiddentodate" id="hiddentodate" />
                                                        </div></td>
                                                        <td><div align="left">
                                                          <input name="todate" type="text" class="swifttext-mandatory" id="DPC_attachtodate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" />
                                                        </div></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td valign="top" bgcolor="#f7faff" align="left">Type:
                                                          <input type="hidden" name="hiddenreporttype" id="hiddenreporttype" /></td>
                                                        <td valign="top" bgcolor="#f7faff" align="left"><select name="reporttype" id="reporttype" class="swiftselect" style=" width:200px;">
                                                            <option value="" selected="selected">All </option>
                                                            <option value="outstanding">Outstanding</option>
                                                        </select></td>
                                                      </tr>
                                                  </table></td>
                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr bgcolor="#f7faff">
                                                        <td colspan="4" valign="top" style="padding:0"></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td colspan="4" valign="top" bgcolor="#EDF4FF" align="left"><strong>Products 
                                                          <input type="hidden" name="hiddenproduct" id = "hiddenproduct"/>
                                                        </strong></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td colspan="4" valign="top" bgcolor="#f7faff" align="left"><div style="height:240px; overflow:scroll">
                                                            <? include('../inc/product-report.php'); ?>
                                                        </div></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td width="10%">Select: </td>
                                                        <td width="33%" align="left"><strong>
                                                          <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                                            <option value="ALL" >ALL</option>
                                                            <option value="NONE" selected="selected">NONE</option>
                                                            <option value="STO">STO</option>
                                                            <option value="SVH">SVH</option>
                                                            <option value="SVI">SVI</option>
                                                            <option value="SES">SES</option>
                                                            <option value="SPP">SPP</option>
                                                            <option value="TDS">TDS</option>
                                                            <option value="AIR">AIR</option>
                                                            <option value="SAC">SAC</option>
                                                            <option value="OTHERS">OTHERS</option>
                                                          </select>
                                                        </strong></td>
                                                        <td width="57%" align="left"><strong><a onclick="selectdeselectall('one');"  class="resendtext" style="cursor:pointer">Go &#8250;&#8250;</a></strong>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                        <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                      </tr>
                                                    </table>
                                                      <label></label>
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    </table></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" style="border-top:1px solid #d1dceb;"></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr>
                                            <td align="right" valign="middle" style="padding-right:15px; "><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="65%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                                  <td width="35%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton" id="filter" value="Search" onclick="searchfilter('');" />
                                                    &nbsp;&nbsp;
                                                    <input name="close" type="button" class="swiftchoicebutton-red" id="close" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" />
                                                    &nbsp;&nbsp;
                                                    <input name="toexcel" type="button" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');" /></td>
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
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getinvoicedetails(''); " style="cursor:pointer" class="grid-active-tabclass">Default</td>
                                  <td width="2">&nbsp;</td>
                                  <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');" style="cursor:pointer" class="grid-tabclass">Search Result</td>
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
                                  <td width="220px"><div id="tabdescription"></div></td>
                                  <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span></td>
                                  <td width="296px" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:904px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center" ></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link" align="left" >
</div></td>
  </tr>
</table><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></div>
                                    <div id="tabgroupgridc2" style="overflow:auto;height:150px; width:904px; padding:2px; display:none;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc2_1" ></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc2link" align="left">
</div></td>
  </tr>
</table><div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                    </div>                                    </td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
<? }?>