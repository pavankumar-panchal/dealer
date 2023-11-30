<?php
$userid = imaxgetcookie('userid');

$query = "select * from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_dealer.slno = '".$userid."' ";
$result = runmysqlqueryfetch($query);

$dealerid = $userid;
$businessname = $result['businessname'];
$contactperson = $result['contactperson'];
$place = $result['place'];
$district = $result['districtname'];
$state = $result['statename'];
$phone = $result['phone'];
$pincode = $result['pincode'];

$allemailids = $result['emailid'];
$emailarray = explode(",",$allemailids);
$emailid = trim($emailarray[0]);


?>
<link href="../style/main.css?dummy = <?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/javascript.js?dummy = <?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/debitcreditpayment.js?dummy = <?php echo (rand());?>" type="text/javascript"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td class="header-line" style="padding:0">Debit / Credit Card payment</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td><table width="100%" border="0" cellpadding="2" cellspacing="0">
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td width="20%" align="left" bgcolor="#EDF4FF">Enter the Amount:</td>
                                            <td width="80%" bgcolor="#f7faff">
                                            <input name="businessname" type="hidden" id="businessname" value="<?php echo($businessname); ?>" />
                                              <input name="contactperson" type="hidden" id="contactperson" value="<?php echo($contactperson); ?>" />
                                              <input name="address" type="hidden" id="address" value="<?php echo($place); ?>" />
                                              <input name="district" type="hidden" id="district" value="<?php echo($district); ?>" />
                                              <input name="state" type="hidden" id="state" value="<?php echo($state); ?>" />
                                              <input name="pincode" type="hidden" id="pincode" value="<?php echo($pincode); ?>" />
                                              <input name="phone" type="hidden" id="phone" value="<?php echo($phone); ?>" />
                                              <input name="emailid" type="hidden" id="emailid" value="<?php echo($emailid); ?>" />
                                              <input name="dealerid" type="hidden" id="dealerid" value="<?php echo($dealerid); ?>" />
                                              <input name="amount" type="text" class="swifttext" id="amount" value="" size="25" maxlength="7"  /></td>
                                          </tr>
                                        </table>
                                        <table width="100%" border="0" cellpadding="2" cellspacing="0">
                                          <tr>
                                            <td width="66%">&nbsp;
                                              <div id="form-error">&nbsp;</div></td>
                                            <td width="34%"><div align="center">
                                                <input name="update"  value="Proceed" type="submit" class="swiftchoicebutton" id="update" onclick="formsubmit();" />
                                              </div></td>
                                          </tr>
                                        </table></td>
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
