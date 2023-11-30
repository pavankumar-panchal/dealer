<?php
include('../inc/eventloginsert.php');
	$userid = imaxgetcookie('dealeruserid');
	$query = "select * from inv_mas_dealer where slno = '".$userid."'";
	$resultfetch = runmysqlqueryfetch($query);
	$blockcancel = $resultfetch['blockcancel'];
	if($blockcancel == 'no' )
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
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/blockcancel.js?dummy=<?php echo (rand());?>"></script>
<style rel=stylesheet>
.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>

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
                            <td width="27%" class="active-leftnav">Block or Cancel PIN Numbers</td>
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
                            <td class="header-line" style="padding:0">&nbsp;Enter / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="cardsearchfilterform" id="cardsearchfilterform"  onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="100%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="1" cellpadding="3">
                                          <tr>
                                            <td valign="top" style=""><!--<table width="99%" border="0" cellspacing="0" cellpadding="3">
                     
                                              <tr>
                                                <td align="center">
                            
                                                <input name="cardsearchtext" type="text" class="swifttext" id="cardsearchtext" onkeyup="cardsearch(event);"  autocomplete="off" style="width:204px"/>
                                                  <span style="display:none1">
                                                  <input name="cardlastslno" type="hidden" id="cardlastslno"  disabled="disabled"/>
                                                  </span>
                                                  <select name="cardlist" size="5" class="swiftselect" id="cardlist" style="width:210px; height:200px" onclick ="selectcardfromlist();" onchange="selectcardfromlist();"  >
                                                  </select>                 </td>
                                              </tr>
                                            </table>-->
                                              
                                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr >
                                                  <td width="53%" height="34px;" colspan="2" bgcolor="#edf4ff"><strong>Enter PIN/Card</strong></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td width="20%">Pin Number:</td>
                                                  <td ><input name="pinno" type="text" id="pinno" 
                                            size="25" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                                </tr>
                                                <tr  bgcolor="#edf4ff">
                                                  <td width="5%"  align="left" valign="top" >CardId: </td>
                                                  <td width="55%" align="left" valign="top"><input name="cardid" type="text" id="cardid" size="25" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value=
                                            "Filter" onclick="carddetailstoform(); griddata();" /></td>
                                                </tr>
                                              </table></td>
                                            <!-- <td width="14%" height="34" valign="top" id="cardselectionprocess" style="padding:0">&nbsp;</td> -->
                                            <td width="61%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #BFDFFF">
                                                <tr  bgcolor="#EDF4FF">
                                                  <td width="27%"  bgcolor="#EDF4FF" align="left"> PIN Serial Number:</td>
                                                  <td width="73%"  bgcolor="#EDF4FF" align="left"><input name="scratchnumber" type="text" class="swifttext" id="scratchnumber" size="30" maxlength="6"  autocomplete="off" readonly="readonly"/></td>
                                                </tr>
                                                    <!-- <tr>
                                                        <td width="27%"  bgcolor="#EDF4FF" align="left"> PIN Remarks Status:</td>
                                                        <td width="73%"  bgcolor="#EDF4FF" align="left">
                                                            <select name="pinremarksstatus" id="pinremarksstatus" class="swiftselect">
                                                                <option value=" ">Select PIN Remarks Status</option>
                                                            <?php //include('../inc/pinremarks.php'); ?>
                                                            </select>
                                                        </td>
                                                    </tr> -->
                                                <tr bgcolor="#f7faff">
                                                  <td bgcolor="#f7faff" align="left">Remarks:</td>
                                                  <td bgcolor="#f7faff" align="left"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"></textarea></td>
                                                </tr>
                                                <tr>
                                                  <td  bgcolor="#EDF4FF" align="left">Attached:</td>
                                                  <td id="cardattached"  bgcolor="#EDF4FF" align="left">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff" align="left">Registered:</td>
                                                  <td id="cardregistered" bgcolor="#f7faff" align="left">&nbsp;</td>
                                                </tr>
                                                <tr  bgcolor="#EDF4FF">
                                                  <td  bgcolor="#EDF4FF" align="left">Action:</td>
                                                  <td  bgcolor="#EDF4FF" align="left">
                                                    <!-- <label for="actiontype0">
                                                      <input name="actiontype" type="radio" id="actiontype0" value="none" checked="checked" />
                                                      Active </label> -->
                                                    <label for="actiontype1">
                                                      <input type="radio" name="actiontype" id="actiontype1" value="block" />
                                                      Block </label>
                                                    <label for="actiontype2">
                                                      <input type="radio" name="actiontype" id="actiontype2" value="cancel" />
                                                      Cancel</label></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff" align="left" >PIN Status:</td>
                                                  <td id="cardstatus" bgcolor="#f7faff" align="left">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td >&nbsp;</td>
                                                  <td >&nbsp;</td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="40%" height="35" align="left" valign="middle"><div id="form-error"></div></td>
                                            <td width="60%" height="35" align="right" valign="middle"><div align="center">
                                                <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save');" />
                                                &nbsp;&nbsp;
                                                <input name="reset" type="button" class="swiftchoicebutton" id="reset" value="Reset" onclick="newentry();" />
                                              </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="15%" class="header-line" style="padding:0"><div id="tabdescription">Results</div></td>
                                </tr>
                                <tr>
                                  <td style="padding:0"><div id="displaysearchresult" style="width:928px; padding:2px;" 
                                            align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc3_1" align="center"></div></td>
                                        </tr>
                                        
                                      </table>
                                    </div>
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
      </table></td>
  </tr>
</table>
<!--<script>refreshcardarray();</script>-->
<?php } ?>