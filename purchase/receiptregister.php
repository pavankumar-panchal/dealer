<?php 
	include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$enablebilling = $resultfetch['enablebilling'];
	$branchhead = $resultfetch['branchhead'];
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
<script language="javascript" src="../functions/receiptregister.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/receiptbranchheadselectjs.php?dummy=<?php echo (rand());?>" ></script>

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
                            <td width="27%" class="active-leftnav">Report - Receipt Register</td>
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
                                    
                                    <tr bgcolor="#EDF4FF">
                                      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="7%" align="left" valign="top" bgcolor="#EDF4FF">All Time</td>
    <td width="6%" align="left" valign="top" bgcolor="#EDF4FF"><input name="alltime" type="checkbox" id="alltime" onclick="disablethedates()" /></td>
    <td width="9%" align="left" valign="top" bgcolor="#EDF4FF">From Date: </td>
    <td width="28%" align="left" valign="top" bgcolor="#EDF4FF"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly"/>
      <input type="hidden" name="flag" id="flag" value="true" />
      <input type="hidden" name="hiddentotalreceipts" id="hiddentotalreceipts" value="" />
      <input type="hidden" name="hiddentotalamount" id="hiddentotalamount" value="" /></td>
    <td width="7%" align="left" valign="top" bgcolor="#EDF4FF">To Date:</td>
    <td width="43%" align="left" valign="top" bgcolor="#EDF4FF"><label for="sto"></label>
      <label for="spp">
        <input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
      </label></td>
    </tr>
</table>
</td>
                                    </tr>
                                     <tr bgcolor="#EDF4FF">
                                      <td valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="7%" align="left" valign="top" bgcolor="#EDF4FF">Mode:</td>
    <td width="43%" align="left" valign="top" bgcolor="#EDF4FF"><select name="paymentmode" class="swiftselect" id="paymentmode"  style="width:200px">
      <option value="" selected="selected">---------------ALL---------------</option>
      <option value="cash">Cash</option>
      <option value="chequeordd">Cheque-DD</option>
      <option value="onlinetransfer">Online Transfer</option>
      <option value="creditordebit">Credit-Debit Card</option>
    </select></td>
    <td width="7%" align="left" valign="top" bgcolor="#EDF4FF">Dealer :</td>
    <td width="43%" align="left" valign="top" bgcolor="#EDF4FF" id="displaydealer">&nbsp;</td>
    </tr>
</table>
</td>
                                    </tr>
                                   <tr><td  bgcolor="#EDF4FF"> <?php if($branchhead == 'yes' ) {  ?> <table width="100%" border="0" cellspacing="0" cellpadding="0">


                                    <tr><td bgcolor="#EDF4FF"><div align="left" style="display:block;height:20px; padding-top:5px; " id="detailsdiv" >
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="87%" ><div style="border-top:dashed 1px #000000;" align="center"></div></td>
                                              <td width="13%" ><div align="right"  onclick="displayDiv('1','filterdiv')" class="resendtext"><strong style=" padding-right:10 px">Advanced Options</strong></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"></td>
                                            </tr>
                                          </table>
                                        </div></td></tr>
                                        <tr><td><div id="filterdiv" style="display:none1;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="2"  bgcolor="#EDF4FF">
                                            <tr>
                                              <td width="100%" valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                              
                                                  <tr>
                                                    <td width="60%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                        <tr valign="top" >
                                                          <td width="100%" height="3" style="padding:3px"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="7%" align="left" valign="top" >Text: </td>
                                                  <td colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="33" maxlength="150" class="swifttext"  autocomplete="off" value=""/>
                                                    <span style="font-size:8px; color:#939393;">(Leave Empty for all)</span></td>
                                                  <td width="27%">&nbsp;</td>
                                                </tr>
                                              </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="42%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2"><table width="99%" height="255" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #004000">
                                                                    <tr>
                                                                      <td align="left"><strong>Look in:</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" id="databasefield0" value="slno"/>
                                                                        Customer ID</label>                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                                        Business Name</label>                                                                      </td>
                                                                    </tr>
                                                                    <tr >
                                                                      <td style="border-top:solid 1px #999999"  height="1" align="left"></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="invoiceno" id="databasefield11" />
                                                                        Invoice No</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="receiptno" id="databasefield12" />
                                                                        Receipt Number</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="chequeno" id="databasefield14" />
                                                                         Cheque Number</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="chequedate" id="databasefield15" />
                                                                         Cheque Date</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="depositdate" id="databasefield16" />
                                                                        Deposit Date</label></td>
                                                                    </tr>
                                                                     <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="drawnon" id="databasefield17" />
                                                                        Drawn On</label></td>
                                                                    </tr>
                                                                     <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="paymentamt" id="databasefield18" />
                                                                        Payment amount</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="2"></td>
                                                                    </tr>
                                                                </table></td>
  </tr>
   <tr>
    <td style="padding:5px"><input name="cancelledinvoice" type="checkbox" id="cancelledinvoice" checked="checked"  /></td>
    <td ><label for="cancelledinvoice">Do not consider Cancelled  Invoices</label></td>
  </tr>
</table>
</td>
                                                                <td width="68%" valign="top" style="padding-left:3px"><table width="102%" border="0" cellspacing="0" cellpadding="6" style="border:solid 1px #cccccc;">
                                                                    <tr>
                                                                      <td colspan="2"><strong>Selections</strong>:</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td width="39%" height="10" align="left">Invoice Status:</td>
                                                                      <td width="61%"   height="10" align="left" valign="top" ><select style="width: 180px;" id="status" class="swiftselect" name="status">
                                                                        <option value="" selected="selected">ALL</option>
                                                                        <option value="ACTIVE">ACTIVE</option>
                                                                        <option value="EDITED">EDITED</option>
                                                                        <option value="CANCELLED">CANCELLED</option>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left">Receipt Status:</td>
                                                                      <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="receiptstatus" class="swiftselect" name="receiptstatus">
                                                                        <option value="" selected="selected">ALL</option>
                                                                        <option value="ACTIVE">ACTIVE</option>
                                                                        <option value="CANCELLED">CANCELLED</option>
                                                                        </select></td>
                                                                    </tr>
                                                                     <tr>
                                                                       <td height="10" align="left">Reconciliation:</td>
                                                                       <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="reconsilation" class="swiftselect" name="reconsilation">
                                                                         <option value="" selected="selected">ALL</option>
                                                                         <option value="notseen" >NOT SEEN</option>
                                                                         <option value="matched">MATCHED</option>
                                                                         <option value="unmatched">UNMATCHED</option>
                                                                         </select></td>
                                                                     </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                    <td width="40%"valign="top" style="padding-left:3px"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                      <tr>
                                                        <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:134px; overflow:scroll">
                                                          <?php include('../inc/productdetails.php'); ?>
                                                        </div></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Select: </td>
                                                        <td align="left"><strong>
                                                          <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:75px"   >
                                                            <option value="ALL"  selected="selected">ALL</option>
                                                            <option value="NONE">NONE</option>
                                                            <?php include('../inc/productgroup.php') ?>
                                                          </select>
                                                        </strong></td>
                                                        <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                            <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                          </tr>
                                                        </table></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="3" valign="top" align="left"><strong>Items: </strong></td>
                                                      </tr>
                                                      <tr >
                                                        <td  colspan="3" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:105px; overflow:scroll" >
                                                          <?php include('../inc/itemlist.php'); ?>
                                                        </div></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="3" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                              <tr>
                                                                <td width="6%"><input type="checkbox" name="selectitem" id="selectitem" checked="checked" onchange="selectdeselectcommon('selectitem','itemarray[]')" /></td>
                                                                <td width="94%"><label for="selectitem">Select All / None</label></td>
                                                              </tr>
                                                            </table></td>
                                                          </tr>
                                                        </table></td>
                                                      </tr>
                                                    </table></td>
                                                  </tr>
                                                  
                                                  <tr>
                                                    <td align="right" valign="middle" style="padding-right:3px;"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </div></td></tr></table> <?php }?> </td></tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter('');" />
                                              &nbsp;
                                            <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');"/></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getinvoicedetails(''); " style="cursor:pointer" class="grid-active-tabclass">Today-All</td>
                                                <td width="2px">&nbsp;</td>
                                                <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');displayreceipttotal()" style="cursor:pointer" class="grid-tabclass">Search Result</td>
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
                                                        <td colspan="2"><div id="tabgroupgridc1_1" align="center" ></div></td>
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
                                                          <td colspan="2"><div id="receiptdetails" align="left"> </div></td>
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
                                    <tr><td>&nbsp;</td></tr>
                                    <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr>
                                          <td width="12%" valign="top"><strong>Total Receipts:</strong></td>
                                          <td width="13%"><input name="totalreceipts" type="text" class="swifttext-readonly" id="totalreceipts" size="15" autocomplete="off" value="" readonly="readonly" style="text-align:right"/></td>
                                          <td width="13%" valign="top"><strong>Total Amount:</strong></td>
                                          <td width="14%"><input name="totalamount" type="text" class="swifttext-readonly" id="totalamount" size="15" autocomplete="off" value="" readonly="readonly" style="text-align:right"/></td>
                                          <td width="8%" valign="top">&nbsp;</td>
                                          <td width="14%">&nbsp;</td>
                                          <td width="11%" valign="top">&nbsp;</td>
                                          <td width="15%">&nbsp;</td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;</td>
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
<?php
}
?>