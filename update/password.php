<?php
include('../functions/phpfunctions.php'); 
$requestkey = $_GET['key'];
$errormessage = "";

//Check if request key is a valid one
$query = "select slno,dealerusername,pwdresetkey,pwdresettime  from inv_mas_dealer where pwdresetkey = '".$requestkey."';";
$result = runmysqlquery($query);
if(mysqli_num_rows($result) == 0)
	$errormessage = "The Request Key is Invalid.";
else
{
	$fetch = mysqli_fetch_array($result);
	$currentime = date('Y-m-d').' '.date('H:i:s');
	$requesttime = $fetch['pwdresettime'];
    $interval = 48 * 60 * 60;
	$time2 = strtotime($currentime);
    $time3 = strtotime('+'.$interval.' second '.$requesttime);
    if($time2 >=$time3)
		$errormessage = "The Request Key has been expired. Please place a password request again.";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Password Retrival | Dealer Login</title>
<?php include('../inc/scriptsandstyles.php'); ?>
<script language="javascript" src="../functions/javascript.js?dummy = <?php echo (rand());?>"></script>
<script language="javascript" src="../functions/password.js?dummy = <?php echo (rand());?>"></script>
</head>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="maincontainer"  style="text-align:left">
  <tr>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="headercontainer">
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><div id=logo><span style="vertical-align:middle"></span></div>
                        <div id=relyonlogo><span style="vertical-align:middle"></span></div></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainbg">
              <tr>
                <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="main">
                    <tr>
                      <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
                          <tr>
                            <td class="bannerbg" height="118">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="youarehere">
                                <tr>
                                  <td class="homelink"><div align="right" style="padding-right:10px" ><a href="../index.php">Home</a></div></td>
                                </tr>
                                <tr>
                                  <td align="left"><img class="arrow" alt="arrow" 
src="../images/herearrow.gif" />
                                    <p>Retrive Your Password</p></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                <tr>
                                  <td width="23%" valign="top" style="border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
                                  <?php if($errormessage <> "") { ?>
                    <tr>
                      <td style="font-size:12px; font-family:Verdana; padding:4px; border:2px solid #FF0000; background-color:#FFFF99"><div align="center"><?php echo($errormessage); ?></div></td>
                    </tr>
                    <?php } ?> <tr>
                      <td style="font-size:12px; font-family:Verdana; padding:4px; padding-left:5px"><p>&nbsp;</p></td>
                    </tr>
                    <?php if($errormessage == "") { ?>
                                      <tr>
                                        <td><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
                                            <tr>
                                              <td align="center"><form id="submitpwdform" name="submitpwdform" method="post" action=""  >
                                                  <table width="500" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #A6D2FF">
                                                  
                                                    <tr>
                                                      <td valign="top"><table width="99%" border="0" cellspacing="0" cellpadding="5" align="center" >
                                                          <tr >
                                                            <td colspan="2" style="font-size:12px; color:#FF0000" align="left" ><span style="font-size:12px"><strong>Enter the Password</strong></span></td>
                                                         <tr>
                                          <td colspan="2" height="35px" >Please enter a new password for your login at Dealer Login Area.<br />
                                            <strong>Your Username: <?php echo($fetch['dealerusername']); ?></strong></td>
                                        </tr>
                                                            <td colspan="3" height="35px" ><div id="form-error" align="center"></div></td>
                                                          </tr>
                                                          <tr>
                                                            <td width="130" align="centre" valign="top" style="font-size:12px"><strong> Password</strong>:</td>
                                                            <td width="312" align="centre" valign="top"><input name="password" type="password" class="swifttext" id="password" size="30" maxlength="50" /></td>
                                                          </tr>
                                                          <tr>
                                                            <td align="centre" valign="top" style="font-size:12px"><strong> Confirm Password</strong>:</td>
                                                            <td align="centre" valign="top"><input name="confirmpwd" type="password" class="swifttext" id="confirmpwd" size="30" maxlength="50" />                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>&nbsp;</td>
                                                            <td  align="left" valign="top" style="font-size:12px"><input name="resetpassword" type="button" class="swiftchoicebutton" id="resetpassword" value="Proceed..." onclick="formsubmiting(<?php echo('\''.$_GET['key'].'\'') ?>)" />
                                                              &nbsp;&nbsp;&nbsp;
                                                              <input name="clearform" type="button" class="swiftchoicebutton" id="clearform" value="Clear" onclick="document.getElementById('form-error').innerHTML='';form.reset()" />                                                            </td>
                                                        </table></td>
                                                    </tr>
                                                  </table>
                                                </form></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <?php } ?>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="footer">
                          <tr>
                            <td align="left"><p>A product of Relyon Web Management | Copyright © 2009 Relyon Softech Ltd. All rights reserved.</p></td>
                            <td align="left"><div align="right"><font color="#FFFFFF">Version 1.01</font></div></td>
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
</html>
