<?php
if(($beanchhead != 'yes') && (($userid != '1780') && ($userid != '1701') && ($userid != '1369') && ($userid != '1316') && ($userid != '1795') && ($userid != '1526') && ($userid != '1428') && ($userid != '1429') && ($userid != '1567')  && ($userid != '1877') && ($userid != '1791')))
{ 
	$pagelink = getpagelink("unauthorised"); 
        include($pagelink);
        exit();
} 

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
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/mailtoamccustomer.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" ></script>

<script language="javascript" src="../functions/enter_keyshortcut.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/key_shortcut.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/customer-shortkey.js?dummy=<?php echo (rand());?>"></script>


<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:left">

    <td width="100%" valign="top" style="border-bottom:#1f4f66 1px solid;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap1">
   <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="16%" align="top" class="active-leftnav">Mail AMC Customers</td>
                            
                           
                       
                          </tr>
                        </table></td>
                    </tr>
                    <tb/>
                    <tr>
                      <td style="padding-top:3px"><div id="filterdiv" >
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                            <tr>
                              <td valign="top"><div>
                                  <form action="" method="post" name="searchfilterform" id="searchfilterform" 
                                  onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="100%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Filter Amc Customer</td>
                                      </tr>
                                      <tr>
                                        <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0"  style="border:dashed 1px #545429">
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
                                                                    Customer ID</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield1" value="businessname" />
                                                                    Business Name</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                                    Email</label></td>
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
                      include('../inc/region1.php');
                      ?>
                                                                  </select></td>
                                                              </tr>
      
                                                              <tr>
                                                                <td height="10" align="left"> Branch:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="branch2" class="swiftselect" id="branch2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/branch1.php');?>
                                                                  </select></td>
                                                              </tr>
       
                                                              <tr>
                                                                <td height="10" align="left"> Due For Renewal Form:</td>
                                                                <td align="left" valign="top"   height="10" ><input name="fromdate" type="text" class="diabledatefield" id="DPC_fromdate" size="30" autocomplete="off" value="" style="color:black !important;background-color:white !important;" /></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Due For Renewal To:</td>
                                                                <td align="left" valign="top"   height="10" ><input name="todate" type="text" class="diabledatefield" id="DPC_todate" size="30" autocomplete="off" value="" style="color:black !important;background-color:white !important;" /></td>
</tr>
<tr>
<td width="100%" colspan="2">
<table width="100%"><tr>
<td height="10" align="left"> Mail Counts:</td>
<td height="10" align="left" width="150px"> <select name="operatorr" class="swiftselect" id="operatorr" >
<option >Select</option>
<option value="="><b>=</b></option>
<option value=">"><b>></b></option>
<option value="<"><b><</b></option>
</select></td>
<td height="10" align="left"> <input name="mailcountsearch" type="text" id="mailcountsearch" size="5" maxlength="60" class="swifttext"  autocomplete="off" value=""/></td>
</tr>
</table>
</td>
</tr>
<tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="1" /></td>
                                                                <td height="10" align="left"> AMC Charges</td>
                                                                
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="2" /></td>
                                                                <td height="10" align="left"> Employee Information Portal (EIP- SPP)</td>

                                                              </tr>
                                                             
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="3" /></td>
                                                                <td height="10" align="left"> Employee Information Portal - AMC</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="4" /></td>
                                                                <td height="10" align="left"> Employee Information Portal Updation</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="5" /></td>
                                                                <td height="10" align="left"> SPP Customization Updation</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="6" /></td>
                                                                <td height="10" align="left"> SPP Customization</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="7" /></td>
                                                                <td height="10" align="left"> AMC Charges- Add-on Module(ARE/AI/FM)</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="8" /></td>
                                                                <td height="10" align="left"> Web Hosting Updation</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="9" /></td>
                                                                <td height="10" align="left"> Web Hosting</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top"   height="10" ><input name="servicetypeflag[]" type="checkbox" id="servicetypeflag[]" autocomplete="off" value="10" /></td>
                                                                <td height="10" align="left"> Employee Information Portal Mobile</td>
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
                                                    <td colspan="4" valign="top" style="padding:0"></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:230px; overflow:scroll">
                                                        <?php include('../inc/customproduct.php'); ?>
                                                      </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="20%" align="left">Select All: </td>
                                                    <td width="50%" align="left">
                                                      <input type='checkbox' id='select_all' class="pnames" name="All_check"  />
                                                      </td>
                                                    <td width="30%" align="left"></td>
                                                  </tr>
                                                  <tr >
                                                    <td  colspan="3" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="21%">&nbsp;</td>
                                                          
                                                          <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" height="25">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="20" colspan="4" ><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Filter" onclick="formsubmit('filter');" />
                                                      &nbsp;
                                                      <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);">
                                                 
                                                  </tr>
<tr><td colspan="2"><b><div id="total"></div></b></td></tr>
                                                </table></td>
 <tr><td colspan='2'><div id='mailmsg'><img src="../images/mail.gif" alt="Mail are being send.Please wait.."></div></td></tr>                                           </tr>
<tr><td colspan='2'><div id='showloadpic1' calss="showloadpic"><img src='../images/imax-loading-image.gif' alt='loading...' /></div></td></tr>
                                          </table>
</td>

                                      </tr>
                                      </table></td>
                                      </tr>
                                        </table></td>
                                      </tr>
                                        </table></td>
                                      </tr>

</table></td>
                                      </tr>
<tr><td><table style="border:1px solid #308ebc; border-top:none;" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tbody><tr class="header-line">
                                  <td width="45%"><span style="padding-left:4px;">Mail Details</span></td>
                                  <td width="24%"><span id="invoicedetailsgridwb1" style="text-align:center">&nbsp;</span><span id="tabgroupgridwb2" style="text-align:center">&nbsp;</span></td>
                                  <td width="31%"></td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:100%; padding:2px;" align="center">
                                      <table id="customertable" border="0" cellpadding="0" cellspacing="0" width="100%">
                                 <tbody><tr>

                                          <td align="center"> <div id="amccustomerfilter">

                                              <table class="table-border-grid" style="border-bottom:none;" cellpadding="3" cellspacing="0" width="100%">
                                                <tbody><tr class="tr-grid-header">
                                                   <td class="td-border-grid" align="left" nowrap="nowrap"><input type="checkbox" class="checkcustomer" id="checkcustomer"></td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Sl No</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Customer Name</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Dealer Name</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Pro-Forma Inv</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Total Amount</td>
                                                  <td class="td-border-grid" align="left" nowrap="nowrap">Mail Count</td>
                                            
                                                </tr>

                                              </tbody></table>
  
                                            </div>

</td>
                                        </tr>
                                       
                                      </tbody></table></td></tr>
<tr><td colspan="4" style="float:right; margin:5px 0px 5px 0px;"><input id="all-customer-filter" type="checkbox">Check All &nbsp;<input name="mailto" class="swiftchoicebutton" id="mailto" value="Mail" onclick="mailtoo();" type="submit"></td></tr>
                                                
</table></td></tr>                               
</table>


<?php } ?>