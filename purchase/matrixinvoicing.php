<?
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');

$gst_tax_date = strtotime('2017-07-01');
$invoicecreated_date = date('Y-m-d');
$query_gst = "SELECT igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '".$invoicecreated_date."' AND to_date >= '".$invoicecreated_date."'";
$fetchrate_gst = runmysqlqueryfetch($query_gst);
//$gst_rate = $fetchrate['rate'];

$igst_tax_rate = $fetchrate_gst['igst_rate'];
$cgst_tax_rate = $fetchrate_gst['cgst_rate'];
$sgst_tax_rate = $fetchrate_gst['sgst_rate'];

$query = "select inv_mas_dealer.businessname as dealername,inv_mas_dealer.branch as branchid,inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_state.statename,inv_mas_dealer.enablematrixbilling as enablematrixbilling ,inv_mas_dealer.branchhead from inv_mas_dealer 
left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$userid."'";
$resultfetch = runmysqlqueryfetch($query);
$relyonexecutive = $resultfetch['relyonexecutive'];
$statecode = $resultfetch['statecode'];
$enablematrixbilling = $resultfetch['enablematrixbilling'];
$branchhead = $resultfetch['branchhead'];
$branchid = $resultfetch['branchid'];
$dealername = $resultfetch['dealername'];

if($enablematrixbilling == 'yes')
{
  if($relyonexecutive == 'yes')
  {
    if($branchid!= '1' && $branchid!= '4')
    {
      $query_branch_name = "select branch_gstin,branch_gst_code from inv_mas_branch where slno = $branchid ;";
      $fetch_branch_name = runmysqlqueryfetch($query_branch_name);
      $dealer_branch_name = $fetch_branch_name['branchname'];
      $branch_gstin = $fetch_branch_name['branch_gstin'];
      $branch_gst_code = $fetch_branch_name['branch_gst_code'];

      // $query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname from inv_mas_customer left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode where inv_mas_district.districtcode in( select districtcode from inv_districtmapping where dealerid = '".$userid."') order by businessname;";
      // $result = runmysqlquery($query); 
      // if(mysqli_num_rows($result) == 0)
      // {
        $query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname from inv_mas_customer where inv_mas_customer.branch = '".$branchid."' order by businessname;";
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
      // }
      // else
      // {
      //   $flag = 'true';
      // }
    }
    else
    {
      $grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
      $grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
      $grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
      echo($grid);
    }
  }
  else
  {
    $grid = '<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height = "200">';
    $grid .= ' <tr><td height = "60">&nbsp;</td></tr>';
    $grid .= '<tr><td valign="top" style="font-size:14px"><strong><div align="center"><font color="#FF0000">You are not authorised to view this page.</font></div></strong></td></tr>';
    echo($grid);
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
<link href="../style/main.css?dummy = <? echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox-invoicing.css?dummy=<? echo (rand());?>"  />
<script language="javascript" src="../functions/javascript.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/matrixinvoicing.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<!-- <script language="javascript">
  $(document).ready(function(){
    $('.producttype option').css({
  "color":"green"
});
//color group names gray:
$('.producttype optgroup').css({
  "color":"#555555"
});
//color first option red:
$('.producttype option').eq(0).css({
  "color":"red"
});
  });
</script> -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
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
    <td width="77%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                          <tr>
                            <td class="header-line" style="padding:0">Matrix Invoicing</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#f7faff">
                                    <tbody>
                                    <tr>
                                      <td  valign="top" style="border-right:#E6F0F9 1px solid; border-bottom:#E6F0F9 1px solid" bgcolor="#FEFFE6"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td width="43%"><strong>Customer GSTIN: </strong></td>
                                          <td width="57%"><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaycustomergst" id="displaycustomergst"  ></td>
                                        </tr>
                                        <tr>
                                          <td width="43%"><strong>Customer ID: </strong></td>
                                          <td width="57%" ><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaycustomerid" id="displaycustomerid"  ></td>
                                        </tr>
                                        <tr>
                                          <td height="50px" colspan="2" valign="top"><strong>Address:<br />
                                            <br />
                                            </strong>
                                            <textarea  cols="47" rows="2" autocomplete="off" id="displayaddress" class="swifttext-readonly-border" type="text" name="displayaddress" readonly="readonly"></textarea></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Contact Person: </strong></td>
                                          <td height="35px"><input type="text" value="" size="30" class="swifttext-mandatory"  name="displaycontactperson"  id="displaycontactperson"/></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Email: </strong></td>
                                          <td height="35px" ><input type="text" value="" size="30" class="swifttext-mandatory"  name="displayemail"  id="displayemail" /></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Phone: </strong></td>
                                          <td height="35px;"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displayphone"  id="displayphone" readonly="readonly"/></td>
                                        </tr>
                                        </table>
                                      </td>
                                      <td width="52%"  valign="top"  style=" border-bottom:#E6F0F9 1px solid" bgcolor="#FEFFE6"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                      <tr>
                                          <td width="43%"><strong>Customer Name: </strong></td>
                                          <td width="57%"><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaycompanyname" id="displaycompanyname"  ></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Cell: </strong></td>
                                          <td  height="35px;"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displaycell"  id="displaycell" readonly="readonly"/></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Type of Customer: </strong></td>
                                          <td><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaytypeofcustomer" id="displaytypeofcustomer"  ></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Type of Category: </strong></td>
                                          <td><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaytypeofcategory" id="displaytypeofcategory"  ></td>
                                        </tr>
                                        <tr>
                                          <td><strong>PO Date:</strong></td>
                                          <td  ><input type="text" class="swifttext-mandatory" id="DPC_startdate" size="25" autocomplete="off" value=""  /></td>
                                        </tr>
                                        <tr>
                                          <td><strong>PO Reference:</strong></td>
                                          <td ><input type="text" value="" size="30" class="swifttext-mandatory"  name="poreference"  id="poreference" /></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Pan No: </strong></td>
                                          <td ><input type="text" value="" size="30" class="swifttext-mandatory"  name="displaypanno"  id="displaypanno" /></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr><td colspan=2>&nbsp;</td></tr>
                                    <tr>
                                      <td colspan=2><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td  width="12%"><div align="left"><strong>Sales Person: </strong></div></td>
                                          <td width="30%" bgcolor="#f7faff" >
                                            <? if($branchhead == 'yes') {?>
                                              <div align="left">
                                                <select name="salesperson" class="swiftselect-mandatory" id="salesperson" style="width:180px;" onchange="getdealerdetails(this);">
                                                  <option value="">Make A Selection</option>
                                                    <? include('../inc/matrix-dealer-invoicing.php'); ?>
                                                </select>
                                              </div>
                                              <? } else { ?>
                                                <div align="left" id="displaymarketingexe"><? echo($dealername); ?>
                                                <input type="hidden" name="dealernamehidden" id="dealernamehidden" value="<? echo($dealername); ?>"/>
                                                <input type="hidden" name="dealeridhidden" id="dealeridhidden" value="<? echo($userid); ?>"/></div>
                                              <? } ?>
                                          </td> 
                                    </tr>
                                          
                                    <tr>
                                      <td  width="9%" ><strong>Product Details</strong><input type="hidden" name="productgrid" id="productgrid" value="1">
                                      <input type="hidden" name="igstrate" id="igstrate" value="<?php echo $igst_tax_rate; ?>"/>
                                            <input type="hidden" name="cgstrate" id="cgstrate" value="<?php echo $cgst_tax_rate; ?>"/>
                                            <input type="hidden" name="sgstrate" id="sgstrate" value="<?php echo $sgst_tax_rate; ?>"/>
                                            <input type="hidden" name="state_gst_code"  id="state_gst_code" />
                                            <input type="hidden" name="branchhidden" id="branchhidden" value="<? echo($branch_gst_code); ?>"/>
                                            <input type="hidden" name="branch_gstin" id="branch_gstin" value="<? echo($branch_gstin); ?>"/>
                                            <input type="hidden" name="cgst_tax_amount"  id="cgst_tax_amount" />
                                            <input type="hidden" name="sgst_tax_amount"  id="sgst_tax_amount" />
                                            <input type="hidden" name="igst_tax_amount"  id="igst_tax_amount" />
                                            <input type="hidden" name="lastslno"  id="lastslno" />
                                            <input type="hidden" name="address"  id="address" />
                                            <input type="hidden" name="pincodestatus"  id="pincodestatus" />
                                            <input type="hidden" name="pincode"  id="pincode" />
                                            <input type="hidden" name="invoicelastslno"  id="invoicelastslno" /></td>
                                            <input type="hidden" name="sez_enabled"  id="sez_enabled" />
                                      <!-- <td width="19%" align="left"><strong><a  name="additem" id="additem" onclick="additemgrid();" class= "r-text">Add >></a></strong></td> -->
                                    </tr>
                                    <tr>
                                      <td colspan=3 >
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3"  id="seletedproductgrid" >
                                          <tbody>
                                            <tr>
                                              <td width="3%">1</td>
                                              <td width="10%" ><select name="purchasetype[]" class="swiftselect-mandatory" id="purchasetype1" >
                                              <option value="">Select</option>
                                              <option value="new">New</option>
                                              <option value="updation">Updation</option></select>
                                              </td>
                                              <td width="9%"><select name="producttype[]" class="swiftselect-mandatory producttype" id="producttype1" style="width:140px;" onchange="getproductname(this)">
                                                <option value="">Select Product Type</option>
                                                <optgroup label="Hardware" >
                                                 <?php 
                                                    $query ="select * from inv_mas_matrixproduct where `group` = 'Hardware';";
                                                    $result = runmysqlquery($query);
                                                    while($fetch = mysqli_fetch_array($result))
                                                    {
                                                        echo "<option value='".$fetch['id']."'>".$fetch['productname']."</option>";
                                                      
                                                    }
                                                  ?>                                                  
                                                </optgroup>
                                                <optgroup label="Software">
                                                <?php 
                                                  $query ="select * from inv_mas_matrixproduct where `group` = 'Software';";
                                                  $result = runmysqlquery($query);
                                                    while($fetch = mysqli_fetch_array($result))
                                                    {
                                                        echo "<option value='".$fetch['id']."'>".$fetch['productname']."</option>";
                                                    }
                                                  ?>    
                                                </optgroup>
                                                </select>
                                                </td>
                                                  <td width="2%">
                                                  <input type="number" class="swifttext-mandatory" name="quantity[]" id="quantity1" placeholder="quantity" name="quantity" style="width:60px" min="1" onkeyup="getSerialDiv(this);" onchange="getSerialDiv(this);">
                                                    <!-- <select name="quantity[]" class="swiftselect-mandatory" id="quantity1"  onchange="getSerialDiv(this);">
                                                    <option value="">Qty</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                    <option value="8">8</option>
                                                    <option value="9">9</option>
                                                    <option value="10">10</option>
                                                    </select> -->
                                                  </td>
                                                  
                                                  <td width="10%">
                                                    <input name="unitamt[]" class="swifttext-mandatory" id="unitamt1" placeholder="Unit amt" size="12"  onkeyup="gettotalamount(this)"/>
                                                    <input type="hidden" name="productrate[]" id="productrate1">
                                                  </td>
                                                  <td width="10%">
                                                    <input name="invamount[]" class="swifttext-mandatory" id="invamount1" readonly placeholder="Invoice amt" size="12" />
                                                    <input type="hidden" name="productrate[]" id="productrate1">
                                                  </td>
                                                  <td width="10%" id="textboxDiv1"></td>
                                                  <td width="10%" ><input type="hidden" name="productnamehidden[]" id="productnamehidden1"></td>
                                                  <td width="10%"><strong><a   id="removeitem" onclick="removegrid(this);" class= "r-text"  title="Remove Item!">X</a></strong></td>
                                                </tr>
                                              </tbody>
                                                </table>
                                            </td>
                                          </tr>
                                          <tr><td colspan=2 align="left"><strong><a  name="additem" id="additem" onclick="additemgrid();" class= "r-text">Add More Products >></a></strong></td></tr>
                                          <tr><td >&nbsp;</td></tr>
                                          <tr>
                                          <td valign="top" width="18%"><div align="left">Invoice Remarks:</div></td>
                                          <td valign="top" width="30%" ><div align="left">
                                          <textarea name="invoiceremarks"  class="swifttextarea" id="invoiceremarks" style="width:173px; height: 50px;"></textarea>
                                            <input type="hidden" name="productrate" id="productrate">
                                          </div></td>
                                          </tr>
                                          <tr>
                                          <td valign="top" width="12%"><div align="left">Payment Amount:</div></td>
                                          <td valign="top" width="60%"><div align="left">
                                            <input name="paymentamount" class="swifttext-mandatory" id="paymentamount"  size="24" placeholder="Enter total received amount" value="0" /><span style="color:red"> (Enter Amount to mention in receipt.)</span>
                                          </div></td>
                                          </tr>
                                          <tr>
                                          <td valign="top" width="18%"><div align="left">Payment Remarks:</div></td>
                                          <td valign="top" width="30%" ><div align="left">
                                          <textarea name="paymentremarks"  class="swifttextarea" id="paymentremarks" style="width:173px; height: 50px;"></textarea>                                            <input type="hidden" name="productrate" id="productrate">
                                          </div></td>
                                          </tr>
                                          <tr>
                                            <td width="35%" ><div align="left">SEZ (special Economic Zone) tax:</div></td>
                                            <td width="70%"><div align="left"><input type="checkbox" name="seztax" id="seztax" /><span style="color:red"> (File will be uploaded during the acception of request.)</span></div></td>
                                          </tr>
                                          <tr>
                                            <td valign="top"  colspan=2><div id="form-error"></div></td>
                                            <td valign="top" ><div align="center">
                                                <input name="preview"  value="Preview" type="button" class="swiftchoicebutton" id="preview" onclick="previewinvoice();" />
                                              </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    </table>
                                      </td>  
                                    </tr>
                                    </tbody>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan=2>
                              <input type="hidden" name="invoicelastslno" id="invoicelastslno"  value=" "/>
                              <div id="viewinvoicedisplaydiv"  overflow:auto;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                  <tr>
                                    <td width="100%" class="header-line" >Invoice details</td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" style="padding:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="displayinvoices" style="overflow:auto; height:150px; width:704px; padding:2px;" align="center"> No datas found to be displayed.</div></td>
                                        </tr>
                                </table></td>
                                  </tr>
                                </table>
                            </div></td>
                          </tr>
                          <tr>
                            <td><div style="display:none">
                                <div id="invoicepreviewdiv" style="width:600px; height:600px; overflow:auto">
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
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Customer GSTIN:</strong> <span id="gstnidpreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
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
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Marketing Exe:</strong> <span  id="marketingexepreview"></span><br />
                                              <strong>Region: </strong>**********<span id="branch"></span></td>
                                            </tr>
                                           <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Contact Person:</strong><span id="contactpersonpreview"></span></td>
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>GSTIN:</strong> <span  id="branchgstinpreview"></span></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Email :</strong>&nbsp;<span id="emailpreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td width="29%" class="td-border-grid" style="text-align: left;"><strong>Phone:</strong>&nbsp;<span id="phonepreview" ></span></td>
                                              <td width="22%" class="td-border-grid" style="text-align: left;"><strong>Cell:</strong>&nbsp;<span id="cellpreview"> </span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Type of Customer: </strong>&nbsp;<span id="custypepreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Category of Customer: </strong>&nbsp; <span id="cuscategorypreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><br></td>
                                            </tr>
                                            <tr>
                                            <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>PO Reference:</strong> &nbsp; <span id="poreferencepreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;" ><strong>PO Date: </strong>&nbsp; <span id="podatepreview"></span></td>
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
                                                  <?  echo($dealername); ?>
                                                  </span></div></td>
                                            </tr>
                                          </tbody>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td width="51%" valign="top" style="text-align: center; padding-top:10px;"><div id="proceedprocessingdiv"></div></td>
                                      <td width="49%" valign="top" style="text-align: center; padding-top:10px;"><div align="center">
                                          <input name="proceed" type="button" class= "swiftchoicebutton" id="proceed" value="Proceed"  onclick="this.disabled=true;proceedforpurchase();disableproceedbutton();"/>
                                          &nbsp;&nbsp;  &nbsp;&nbsp;
                                          <input name="cancel" type="button" class= "swiftchoicebutton" id="cancel" value="Cancel"  onclick="cancelpurchase();"/>
                                        </div></td>
                                    </tr>
                                  </table>
                                </div>
                              </div></td>
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
<script>
gettotalcustomercount();
</script>
<? } ?>
