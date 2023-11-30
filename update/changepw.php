<?php
$userid = imaxgetcookie('dealeruserid');
include('../inc/eventloginsert.php');
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<SCRIPT src="../functions/javascript.js?dummy=<?php echo (rand());?>" type=text/javascript></SCRIPT>
<SCRIPT src="../functions/changepwd.js?dummy=<?php echo (rand());?>" type=text/javascript></SCRIPT>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td class="header-line" style="padding:0">Change Password</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform"  onsubmit="return false">
                                  <table width="400" border="0" align="center" cellpadding="2" cellspacing="0">
                                    <tr>
                                      <td>
                                        <table width="100%" border="0" cellpadding="3" cellspacing="0">
                                        <tr><td>&nbsp;</td></tr>
                                        <tr width="40%" height="40"><td colspan="2" id="form-error" align="centre">&nbsp;</td></tr>
                                        <tr><td colspan="2" >&nbsp;</td></tr>
                                          <tr>
                                            <td width="40%" align="left" bgcolor="#EDF4FF">Existing Password:</td>
                                            <td width="60%" bgcolor="#f7faff"><input name="oldpassword" size="25" class="swifttext" value="" type="password" id="oldpassword" /></td>
                                          </tr>
                                          <tr>
                                            <td width="40%" align="left" bgcolor="#EDF4FF">New Password:</td>
                                            <td  width="60%" bgcolor="#f7faff"><input name="newpassword"  id="newpassword" size="25" class="swifttext" value="" type="password" /></td>
                                          </tr>
                                          <tr>
                                            <td width="40%" align="left"  bgcolor="#EDF4FF">Confirm New Password:</td>
                                            <td width="60%" bgcolor="#f7faff"><input name="confirmpassword" size="25" class="swifttext" value="" type="password"  id="confirmpassword"  /></td>
                                          </tr>
                                        </table>
                                        <table width="100%" border="0" cellpadding="2" cellspacing="0">
                                          <tbody>
                                            <tr>
                                              <td>&nbsp;
                                                  <div align="center">
                                                    <input name="update"  value="Update" type="submit" class="swiftchoicebutton" id="update"  onclick="validating(<?php echo($userid); ?>);" />
                                                    &nbsp;
                                                    <input name="reset"  value="Clear" type="reset" class="swiftchoicebutton" id="reset" onClick="document.getElementById('form-error').innerHTML = '';"/>
                                                  </div></td>
                                            </tr>
                                          </tbody>
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
