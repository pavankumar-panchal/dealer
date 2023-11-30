<?
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/viewpurchase.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="text-align:left">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr >
                            <td class="header-line" style="padding:0">View purchases</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform"  onSubmit="return false">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                          
                                            <tr><td colspan="2"><div align="left">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td width="13%" bgcolor="#EDF4FF">From Date:</td>
                                                    <td width="37%" bgcolor="#f7faff"><input name="fromdate" type="text" class="swifttext" id="DPC_fromdate" size="30" autocomplete="off"   value="<? echo(date('d-m-Y')); ?>"/></td>
                                                    <td width="10%" bgcolor="#EDF4FF">To Date: </td>
                                                    <td width="40%" bgcolor="#f7faff"><input name="todate" type="text" class="swifttext" id="DPC_todate" size="30" autocomplete="off"   value="<? echo(date('d-m-Y')); ?>"/></td>
                                                  </tr>
                                              </table>
                                            </div></td></tr>
                                            <tr>
                                              <td width="13%"><div align="left">Product:</div></td>
                                              <td width="87%" bgcolor="#f7faff" align="left" >
                                                <select name="product" class="swiftselect-mandatory" id="product" style="width:180px;">
                                                  <option value="all">ALL</option>
                                                  <? 
											include('../inc/product.php');
											?>
                                                </select>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                  <td width="70%"><div id="form-error" align="left">&nbsp;</div></td>
                                                  <td width="30%"><div align="center">
                                                    <input name="view"  value="View" type="submit" class="swiftchoicebutton" id="view"  onClick="formsubmit(<? echo($userid) ?>,'');"/>
                                                    &nbsp;</div></td>
                                                </tr>
                                              </table></td>
                                            </tr>

                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
  <tr>
    <td width="37%" class="header-line"><div id="tabdescription">Purchases</div></td>
    <td width="30%" class="header-line"><span id="tabgroupgridwb1"></span></td>
    <td width="33%" class="header-line">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><div id="displaypurchases" style="overflow:auto; height:150px; width:908px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link" style="height:20px; padding:2px;">
</div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
  </tr>
</table></div></td>
  </tr>
</table></td>
                                            </tr>
                                         <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
  <tr>
    <td width="37%" class="header-line"><div id="tabdescription">Details<span id="displaybillno">&nbsp;</span></div></td>
    <td width="63%" class="header-line"><span id="tabgroupgridwb2"></span></td>
  </tr>
  <tr>
    <td colspan="3"><div id="displaypurchasedetails" style="overflow:auto; height:150px; width:908px; padding:2px; " align="center">
      <table width="107%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div id="tabgroupgridc2_1" align="center"></div></td>
        </tr>
        <tr>
          <td><div id="tabgroupgridc2link" > </div></td>
        </tr>
      </table>
    </div>
    <div id="resultpurchasegrid" style="overflow:auto; display:none; height:150px; width:908px; padding:2px;" align="center">&nbsp;</div></td>
  </tr>
</table></td></tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
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
