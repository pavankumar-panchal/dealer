<?php
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('dealeruserid');
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/transactionsummary.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                         
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
                            <td class="header-line" style="padding:0">Transaction Summary</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;" >
                                  <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                          
                                            <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td width="13%" bgcolor="#EDF4FF"><div align="left">From Date:</div></td>
                                                <td width="37%" bgcolor="#f7faff"><div align="left">
                                                  <input name="fromdate" type="text" class="swifttext" id="DPC_fromdate" size="30" autocomplete="off"   value="<?php echo(date('d-m-Y')); ?>"/>
                                                </div></td>
                                                <td width="10%" bgcolor="#EDF4FF"><div align="left">To Date: </div></td>
                                                <td width="40%" bgcolor="#f7faff"><div align="left">
                                                  <input name="todate" type="text" class="swifttext" id="DPC_todate" size="30" autocomplete="off"   value="<?php echo(date('d-m-Y')); ?>"/>
                                                </div></td>
                                              </tr>
                                            </table></td></tr>
                                            <tr>
                                              <td width="13%">Transaction type:</td>
                                              <td width="87%" bgcolor="#f7faff"><div align="left"><label><input type="radio" name="transactiontype" id="purchase" value="purchase" checked="checked">
                                                Purchase </label>
                                                &nbsp;
                                               <label> <input type="radio" name="transactiontype" id="credit" value="credit" >
                                                Credit</label>
                                                &nbsp;
                                               <label> <input type="radio" name="transactiontype" id="both" value="both">
                                                Both</label></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                                <tr>
                                                  <td width="70%"><div id="form-error">&nbsp;</div></td>
                                                  <td width="30%"><div align="center">
                                                    <input name="view"  value="View" type="submit" class="swiftchoicebutton" id="view"   onclick="formsubmit('view','<?php echo($userid) ?>','');"/>
                                                    &nbsp;
                                                    <input name="excel"  value="Excel" type="submit" class="swiftchoicebutton" id="excel" onclick="formsubmit('toexcel');" />
                                                  </div></td>
                                                </tr>
                                              </table></td>
                                            </tr>

                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
  <tr>
    <td class="header-line"><div id="tabdescription">Transaction Result</div></td>
    
  </tr>
  <tr>
    <td><div id="displaytransactionsummary" style="overflow:auto; height:150px; width:908px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link" ></div
><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:908px; padding:2px;" align="center">&nbsp;</div></td>
  </tr>
</table></div></td>
  </tr>
</table>
</td>
                                            </tr>
                                         
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
