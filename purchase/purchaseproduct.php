<?php 
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$enablebilling = $resultfetch['enablebilling'];
	if($enablebilling == 'yes' )
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
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../inc/getproductjs.php?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../inc/getschemejs.php?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/purchaseproduct.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="JavaScript">
	if (navigator.platform.toString().toLowerCase().indexOf("linux") != -1)
	{
		document.write('<link type="text/css" rel="stylesheet" href="../style/datepickercontrol_lnx.css">');
	}
	else
	{
		document.write('<link type="text/css" rel="stylesheet" href="../functions/datepickercontrol.js">');
	}
</script>
<script>
  getinvoicedetails();
 </script> 
<?php
	$userid = imaxgetcookie('dealeruserid');
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="text-align:left">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                      <td><div id="maindiv"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td class="header-line" style="padding:7"><div >Purchase Product </div></td>
                            <td align="right" class="header-line" >&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top">
                                <form action="" method="post" name="submitform" id="submitform"  onSubmit="return false">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                      <tr>
                                        <td colspan="4" class="megbox">Current credit :&nbsp;<span id="currentcredit"> &nbsp;</span></td></tr>
                                        <tr>
                                          <td><div align="left">Scheme</div></td>
                                          <td id="displayschemecode"><div align="left"><select name="scheme" class="swiftselect-mandatory" id="scheme" style="width:180px" >
                                               <option value="">Select A scheme</option>
                                              </select></div></td><td><div align="left">Date:</div></td><td><div align="left">
                                                <input name="billdate" class="swifttext-mandatory" id="billdate"  value="<?php echo(datetimelocal('d-m-Y'))?>" readonly="readonly"/>
                                              </div></td></tr>
                                            <tr>
                                              <td width="12%"><div align="left">Product:</div></td>
                                              <td bgcolor="#f7faff" id="displayproductcode"><div align="left">
                                                <select name="product" class="swiftselect-mandatory" id="product" style="width:180px;" onchange="getamount( <?php echo($userid); ?> );">
                                                  <option value="">Select A product</option>                                               </select>
                                                
                                                
                                               
                                              </div></td><input type="hidden" name="lastslno" id="lastslno" />
                                              <td bgcolor="#f7faff"><div align="left">Purchase Type:</div></td>
                                              <td bgcolor="#f7faff"><div align="left">
                                                <select name="purchasetype" class="swiftselect-mandatory" id="purchasetype" style="width:180px;" onchange="getamount(<?php echo($userid); ?>);">
                                                  <option value="">Make A Selection</option>
                                                  <option value="new">New</option>
                                                  <option value="updation">Updation</option>
                                                </select>
                                              </div></td>
                                            </tr>
                                             <tr>
                                              <td width="12%"><div align="left">Quantity:</div></td>
                                              <td width="33%" bgcolor="#f7faff"><div align="left">
                                                <select name="quantity" class="swiftselect-mandatory" id="quantity" style="width:180px;" onchange="getamount(<?php echo($userid); ?>);">
                                                  <option value="">Make A Selection</option>
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
                                                </select> <input type="hidden" name="lastbillno" id="lastbillno" />
                                                <input type="hidden" name="productlastslno" id="productlastslno" />
                                              </div></td>
                                              <td width="12%"><div align="left">Usage:</div></td>
                                              <td width="43%" bgcolor="#f7faff"><div align="left">
                                                <select name="usagetype" class="swiftselect-mandatory" id="usagetype" style="width:180px;" onchange="getamount(<?php echo($userid); ?>);">
                                                  <option value="">Make A Selection</option>
                                                  <option value="singleuser">Single User</option>
                                                  <option value="multiuser">Multi User</option>
                                                </select>
                                              </div></td>
                                          </tr>
                                          <tr>
                                        </tr>
                                             <tr>
                                              <td width="12%">Remarks:</td>
                                              <td bgcolor="#f7faff"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks" style="width:173px;"></textarea></td>
                                              <td valign="top"><div align="left">Amount:</div></td>
                                              <td valign="top" bgcolor="#f7faff"><div align="left">
                                                <input name="amount" class="swifttext-mandatory" id="amount" readonly="readonly" />
                                                <input type="hidden" name="productrate" id="productrate">
                                              </div></td>
                                          </tr>
                                            <tr>
                                              <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                  <td width="63%" height="34px"><div id="form-error">&nbsp;</div></td>
                                                  <td width="37%"><div align="center">
                                                    <input name="new"  value="New Product" type="submit" class="swiftchoicebutton" id="new"  onClick="newproduct();"/>
                                                    &nbsp;
                                                    <input name="save"  value="Save Product" type="submit" class="swiftchoicebutton" id="save"  onClick="formsubmit('save',<?php echo($userid); ?>);"/>
                                                  &nbsp;
                                                  <input name="delete"  value="Del. Product" type="submit" class="swiftchoicebutton" id="delete"  onClick="formsubmit('delete',<?php echo($userid); ?>);"/>
                                                  </div></td>
                                                </tr>
                                              </table></td>
                                            </tr>

                                            <tr>
                                              <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                            <tr>
                                              <td class="header-line"><div id="tabdescription">Product Details</div></td>
                                            </tr>
                                            <tr>
                                              <td><div id="displayproductdetails" style="overflow:auto; height:150px; width:908px; padding:2px;" align="center">&nbsp;No datas found to be displayed.</div></td>
                                            </tr>
                                          </table></td>
                                            </tr>
                                            <tr><td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr>
                                                <td width="77%"><div align="right">Total Amount:</div></td>
                                                <td width="23%"><input name="totalamount" class="swifttext-mandatory" id="totalamount" readonly="readonly" /></td>
                                              </tr>
                                              <tr>
                                                <td><div align="right" id="taxname">&nbsp;</div></td>
                                                <td><input name="taxamount" class="swifttext-mandatory" id="taxamount"  readonly="readonly"/></td>
                                              </tr>
                                              <tr>
                                                <td><div align="right">Net Amount:</div></td>
                                                <td><input name="netamount" class="swifttext-mandatory" id="netamount"  readonly="readonly"/></td>
                                              </tr>
                                            </table></td></tr>
                                         <tr><td colspan="3"><div id="form-error-confirm">&nbsp;</div></td>
                                           <td><input name="newtransaction"  value="New Transaction" type="button" class="swiftchoicebuttonbig" id="newtransaction"   onclick="newentry();"/>
&nbsp;&nbsp;
<input name="confirm"  value="Confirm &amp; Proceed" type="submit" class="swiftchoicebuttonbig" id="confirm" onclick="checkforproducts('<?php echo($userid) ?>');" />&nbsp;</td>
                                         </tr>
                                        </table>
                                      </td>
                                    </tr>
                                    <tr>
                                    <td >
                                      <input type="hidden" name="invoicelastslno" id="invoicelastslno"  value=" "/>
                                      <div id="viewinvoicedisplaydiv"  overflow:auto;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                          <tr>
                                            <td width="93%" class="header-line" style="padding:0">Invoice details</td>
                                            <!-- <td width="7%" class="header-line" style="padding:0"><div align="right"><a  style="cursor:pointer;" onclick="document.getElementById('viewinvoicedisplaydiv').style.display = 'none';"><img src="../images/cancel5.jpg" width="10" height="10" align="absmiddle" /></a>&nbsp;&nbsp;</div></td> -->
                                          </tr>
                                          <tr>
                                            <td colspan="2" style="padding:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td><div id="displayinvoices" style="overflow:auto; height:150px; width:950px; padding:2px;" align="center"> No datas found to be displayed.</div></td>
                                                </tr>
                                        </table></td>
                                          </tr>
                                        </table>
                                      </div></td>
                                    </tr>
                                  </table>
                                </form>
                              </td>
                          </tr>
                        </table></div></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td><div id="carddetails" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="2" style="border:1px solid #308ebc; border-top:none;">
                  
  <tr>
    <td class="header-line"><div id="tabdescription">Transaction Successfull: Your Products</div></td>
  </tr>
  <tr>
    <td><div id="displayscratchcarddetails" style="overflow:auto; height:150px; width:908px; padding:2px;" align="center" >&nbsp;No datas found to be displayed.</div></td>
  </tr>
  
  <tr height="20px">
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td>&nbsp;
<input name="print"  value="Print" type="submit" class="swiftchoicebutton" id="print" onclick="window.print();" />
&nbsp;</td>
        <td>
          
            <div align="right">
            <a href="./index.php?a_link=dashboard">  <input name="gotodashboard"  value="Go to Dashboard" type="button" class="swiftchoicebuttonbig" id="gotodashboard"   onclick="gotodashboard();"/></a>
              &nbsp;
              <input name="newtransactionentry"  value="New Transaction" type="button" class="swiftchoicebuttonbig" id="newtransactionentry"   onclick="newtransactionentry();"/>
            </div></td>
      </tr>
    </table></td>
  </tr>
</table></div>
</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
    </table></td>
  </tr>
</table>
<?php
}
?>