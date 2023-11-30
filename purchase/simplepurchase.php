<?
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
?>
<link href="../style/main.css?dummy = <? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/datepickercontrol.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/javascript.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/simplepurchase.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                      <td><div id="maindiv"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td class="header-line" style="padding:0">Product</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top">
                                <form action="" method="post" name="submitform" id="submitform"  onSubmit="return false">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                            <tr>
                                              <td width="12%"><div align="left">Product:</div></td>
                                              <td bgcolor="#f7faff"><div align="left">
                                                <select name="product" class="swiftselect-mandatory" id="product" style="width:180px;" onchange="generatebillnumber();">
                                                  <option value="">Make A Selection</option>
                                                  <? 
											include('../inc/product.php');
											?>
                                                </select>
                                                <input type="hidden" name="lastslno" id="lastslno" />
                                                <input type="hidden" name="productlastslno" id="productlastslno" />
                                                <input type="hidden" name="lastbillno" id="lastbillno" />
                                              </div></td>
                                              <td bgcolor="#f7faff"><div align="left">Date:</div></td>
                                              <td bgcolor="#f7faff"><div align="left">
                                                <input name="datetime" class="swifttext-mandatory" id="datetime"  value="<? echo(datetimelocal('d-m-Y'))?>"/>
                                              </div></td>
                                            </tr>
                                             <tr>
                                              <td width="12%"><div align="left">Purchase Type:</div></td>
                                              <td width="33%" bgcolor="#f7faff"><div align="left">
                                                <select name="purchasetype" class="swiftselect-mandatory" id="purchasetype" style="width:180px;">
                                                  <option value="">Make A Selection</option>
                                                  <option value="new">New</option>
                                                  <option value="updation">Updation</option>
                                                </select>
                                              </div></td>
                                              <td width="18%"><div align="left">Usage:</div></td>
                                              <td width="37%" bgcolor="#f7faff"><div align="left">
                                                <select name="usagetype" class="swiftselect-mandatory" id="usagetype" style="width:180px;">
                                                  <option value="">Make A Selection</option>
                                                  <option value="singleuser">Single User</option>
                                                  <option value="multiuser">Multi User</option>
                                                </select>
                                              </div></td>
                                            </tr>
                                             <tr>
                                              <td width="12%"><div align="left">Quantity:</div></td>
                                              <td bgcolor="#f7faff"><div align="left">
                                                <input name="quantity" class="swifttext-mandatory" id="quantity" />
                                              </div></td>
                                              <td><div align="left"></div></td>
                                              <td bgcolor="#f7faff"><div align="left"></div></td>
                                             </tr>
                                            <tr>
                                              <td colspan="4"><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                  <td width="63%"><div id="form-error" align="left">&nbsp;</div></td>
                                                  <td width="37%"><div align="center">
                                                    <input name="new"  value="New Product" type="submit" class="swiftchoicebutton" id="new"  onClick="newproduct();"/>
                                                    &nbsp;
                                                    <input name="save"  value="Save Product" type="submit" class="swiftchoicebutton" id="save"  onClick="formsubmit('save','<? echo($userid) ?>');"/>
                                                  &nbsp;
                                                  <input name="delete"  value="Del. Product" type="submit" class="swiftchoicebutton" id="delete"  onClick="formsubmit('delete');"/>
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
                                            <tr>
                                              <td colspan="4">&nbsp;</td>
                                            </tr>
                                         <tr><td colspan="4"><div align="center">
                                           <input name="newtransaction"  value="New Transaction" type="button" class="swiftchoicebuttonbig" id="newtransaction"   onclick="newentry();"/>
                                           &nbsp;
                                           <input name="confirm"  value="Confirm &amp; Proceed" type="submit" class="swiftchoicebuttonbig" id="confirm" onclick="attachcards(<? echo($userid) ?>);" />
                                           &nbsp;</div></td></tr>
                                        </table>
                                      </td>
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
  
  <tr height="20px"><td><div align="center">
    <input name="print"  value="Print" type="submit" class="swiftchoicebutton" id="print" onclick="window.print();" />
  </div></td></tr>
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
