<?php
if(($beanchhead != 'yes') && (($userid != '1780') && ($userid != '1701') && ($userid != '1369') && ($userid != '1316') && ($userid != '1795') && ($userid != '1526') && ($userid != '1428') && ($userid != '1429') && ($userid != '1567') && ($userid != '1877') && ($userid != '1791')))
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
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/customuser.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" ></script>

<script language="javascript" src="../functions/enter_keyshortcut.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/key_shortcut.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/customer-shortkey.js?dummy=<?php echo (rand());?>"></script>
<style>
.additional table tr th
{
    background: #a7a5a5;
    text-align: center;
    padding: 5px;
    color: #fff;
    font-size: 12px;
}
</style>

<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:left">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" >


<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
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
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="16%" align="top" class="active-leftnav">Customer Details</td>
                            <td width="43%" align="top"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="23%" valign="top"><div align="left" style="padding:2px">
                                <div align="right">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);" style="width:130px"  maxlength="20"  autocomplete="off"/>
                                  <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div>
                              </div></td>
                               <td width="18%" >&nbsp;
                              <input name="search" type="submit" class="swiftchoicebuttonbig" id="search" value="Advanced Search"  onclick="displayDiv('1','filterdiv')"  /></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td style="padding-top:3px"><div id="filterdiv" style="display:none;">
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
                                                          <td width="79%"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                          <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" height="25">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="20" colspan="3" ><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="searchcustomerarray();" />
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
                      <td colspan="3" style="padding-top:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="5" height="10px;"></td>
                    </tr>
                    <tr>
                      <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; ">
                          <tr>
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td align="left" style="padding:0">&nbsp;</td>
                            <td align="right"  style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top">


<!-- Main Section -->

<div id='invoiceslists'></div>

<br>
<div id="maindiv">
          <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">         

<table border="0" cellspacing="0" cellpadding="3" width="100%" id="fetchdatatable">
     <tbody>
     	  <tr>
               <td bgcolor="#edf4ff">Customer Name:</td>
               <td bgcolor="#edf4ff" id="displaycompanyname"  class="clearafter">&nbsp;</td>
               
               <td bgcolor="#edf4ff">Customer Id:</td>
               <td bgcolor="#edf4ff" id="displaycustomerid" class="clearafter">&nbsp;</td>
         </tr>
         <tr>
              <td><input type="hidden" id="hiddenid" name="hiddenid"></td>
         </tr>
         <tr height="10"></tr>
         <tr>
               <td bgcolor="#F7FAFF">Previous Invoice No.:</td>
               <td bgcolor="#F7FAFF" id="amcinvno" class="clearafter">&nbsp;</td>

               <td bgcolor="#F7FAFF">Email ID:</td>
               <td bgcolor="#F7FAFF">
                 <input type="text" name="amcemailid" id="amcemailid" class="swifttext" autocomplete="off">
               </td>               
         </tr>
         <tr>
               <td bgcolor="#edf4ff">Invoice Date:</td>
               <td bgcolor="#edf4ff" id="invoicedate" class="clearafter">&nbsp;</td>

               <td bgcolor="#edf4ff">Renewal Date:</td>
               <td bgcolor="#edf4ff" id="renewaldate" class="clearafter">&nbsp;</td>     
         </tr>
         <tr height="10"></tr>
         <tr>
               <td bgcolor="#F7FAFF">Ported Dealer:</td>
               <td bgcolor="#F7FAFF" id="displaycurrentdealer" class="clearafter">&nbsp;</td>

               <td bgcolor="#F7FAFF">Change Dealer:</td>
               <td valign="top" bgcolor="#EDF4FF" align="left">
                   <select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
                      <option value="">Select A Dealer</option>
                       <?php include('../inc/firstdealer.php'); ?>
                    </select>
               </td>
         </tr>
         </tbody>
</table>

<input type="hidden" name="amc_slno" id="amc_slno" autocomplete="off">
                <input type="hidden" name="lastslno" id="onlineslno" autocomplete="off">
                <input type="hidden" name="invoicelastslno" id="onlinepslno" autocomplete="off">

<table border="0" cellspacing="0" cellpadding="3" width="100%">
         <tbody>

     	  <tr>
            <td bgcolor="#edf4ff" width="25%">Invoice Remarks:</td>
            <td bgcolor="#edf4ff" width="25%"><textarea name="invoiceremarks" id="invoiceremarks" maxlength="100" rows="3" cols="22"></textarea>
        </td>
            <td bgcolor="#edf4ff" width="25%"></td>
            <td bgcolor="#edf4ff" width="25%"></td>
         </tr>

         <tr>
	      <td bgcolor="#F7FAFF" class="clearafter" ><lable>Enable Mailing:</lable><input type="checkbox" name="check_mobile" id="check_mobile" ></td>
          <td bgcolor="#F7FAFF" class="clearafter" ><lable>Enable Desktop Notification:</lable><input type="checkbox" name="check_desktop" id="check_desktop"></td>
           <td bgcolor="#edf4ff" width="25%"></td>
            <td bgcolor="#edf4ff" width="25%"></td>
         </tr>
       
         </tbody>
</table>

<br>
<hr>
<span style="padding:8px;">
<button id="show" class="swiftchoicebutton">Add More</button>
<button id="hide" class="swiftchoicebutton">Hide</button>
</span>
<div id="detailsprocessing" class="detailsprocessing" style="float: right;padding-right:20px"></div>

<div id="moreservices" class="additional">
 <center><h1><u>Add Services</u></h1></center>

<table width="100%" border="0">
  <tr>
    <th scope="col">Service Type</th>
    <th scope="col">Amount</th>
    <th scope="col">Description</th>
  </tr>

  <tr>
   <td>
<select name="servicenametypes[]" class="swiftselect" style="width:195px;">
     <option value="" selected="selected">Select a Item</option>
        <?php include('../inc/services.php'); ?>
    </select>
    </td>
    <td>
    <input type="text" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="servicetype_new[]" class="swifttext" id="updationprice">
    </td>
    <td><input type="text" class="swifttext" name="itembriefdescription_new[]" id="briefdescription" ></td>
  </tr>

  <tr>
   <td>
<select name="servicenametypes[]" class="swiftselect" style="width:195px;">
     <option value="" selected="selected">Select a Item</option>
        <?php include('../inc/services.php'); ?>
    </select>
    </td>
    <td>
    <input type="text" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="servicetype_new[]" class="swifttext" id="updationprice">
    </td>
    <td><input type="text" class="swifttext" name="itembriefdescription_new[]" id="briefdescription"></td>
  </tr>

  <tr>
   <td>
<select name="servicenametypes[]" class="swiftselect" style="width:195px;">
     <option value="" selected="selected">Select a Item</option>
        <?php include('../inc/services.php'); ?>
    </select>
    </td>
    <td>
    <input type="text" onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57" name="servicetype_new[]" class="swifttext" id="updationprice" value="">
    </td>
    <td><input type="text" class="swifttext" name="itembriefdescription_new[]" id="briefdescription"></td>
  </tr>

</table>

</div>

<br>
<hr>

<table border="0" cellspacing="0" cellpadding="3" width="100%">
         <tbody>
     	 <tr>
     	 	
            <td bgcolor="#edf4ff" style="width: 20%;"> <div id="viewpinvoice" style="font-size: 12px;"></div></td>  
           
           
         </tr>
         </tbody>
</table>

                         
         </form>
</div>

<div id="form-error"></div>
<div id="productselectionprocess"></div>
 <form action="" method="post" name="viewcustomerdetails" id="viewcustomerdetails" onsubmit="return false;">         
      <input type="hidden" name="custidtoview" id="custidtoview" value="">
  </form>
<!--/. Main Ends -->

</td>
                                  </tr>
                                </table>
                              </div></td>
                          </tr>
                          <tr height="5"></tr>
<tr>
 <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="140" align="center" id="tabgroupgridh1" style="cursor:pointer" class="grid-active-tabclass">Mail Details</td>
                                  <td width="2"></td>
                                  <td width="140px"></td>
                                  <td width="2"></td>
                                  <td width="140" a></td>
                                  <td width="2"><div id="gridprocessing" style="display:none;"></div></td>
                                  <td width="140"></td>
                                  <td width="2"></td>
                                  <td width="140" align="center" ></td>
                                </tr>
                              </table></td>
                          </tr>
</table>
</td>
</tr>

<!-- Filter -->
<tr id="maildetails">
                          
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="45%"><span style="padding-left:4px;">Mail Details</span></td>
                                  <td width="24%"><span id="invoicedetailsgridwb1" style="text-align:center">&nbsp;</span><span id="tabgroupgridwb2" style="text-align:center">&nbsp;</span></td>
                                  <td width="31%"></td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:100%; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="customertable">
                                 <tr>

                                          <td align="center"> <div id="customfilter" >

                                              <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;">
                                                <tr class="tr-grid-header">
                                                  
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Sent By</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Sent Date</td>
                                            
                                                </tr>
<tr id="customfilterdetails"></tr>
                                              </table>
  
                                            </div>

</td>
                                        </tr>
                                       
                                      </table>
                                      
     

                                    </div></td>
 
                                </tr>
</table>
                            </td>
                        </tr>


<!--/ .Ends Filter -->

<!-- Filter Invoice -->
<tr id="invoicedetails">
                          
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="45%"><span style="padding-left:4px;">Invoice Details</span></td>
                                  <td width="24%"><span id="invoicedetailsgridwb1" style="text-align:center">&nbsp;</span><span id="tabgroupgridwb2" style="text-align:center">&nbsp;</span></td>
                                  <td width="31%"></td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:100%; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="customertable">
                                 <tr>

                                          <td align="center"> <div id="invoicedetail" >

                                              <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;">
                                                <tr><td class="grid-tabclass">Purchage Details</td></tr>
                                               <tr class="tr-grid-header">
                                                <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">PIN No</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Product Code </td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Usages Type</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Amount </td>
                                                  
                                                </tr>

                                              </table>
                                             <br/>
                                           <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;">
                                                <tr><td class="grid-tabclass" colspan="2" style="text-align: left;
padding-left: 25px;">Service Details</td></tr>
                                               <tr class="tr-grid-header">
                                                <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Service Amount</td>
                                                  <td nowrap = "nowrap" class="td-border-grid" align="left">Service Type</td>
                                                  
                                                  
                                                </tr>

                                              </table>
  
                                            </div>

</td>
                                        </tr>
                                       
                                      </table>
                                      
     

                                    </div></td>
 
                                </tr>
</table>
                            </td>
                        </tr>


<!--/ .Ends Filter Invoice -->


                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table>
      </td>
  </tr>
</table>

<script>gettotalcustomercount();</script>
<div id="seztaxuploaddiv" style="display:none;">
  <?php include('../inc/seztaxuploaddiv.php'); ?>
</div>
<?php }?>
