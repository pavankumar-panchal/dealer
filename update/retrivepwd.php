<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Password Retrival | Dealer Login</title>
<?php include('../inc/scriptsandstyles.php'); ?>
<script language="javascript" src="../functions/cookies.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/retrivepwd.js?dummy=<?php echo (rand());?>"></script>

</head>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="maincontainer">
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
        <tr><td class="homelink"><div align="right" style="padding-right:10px" ><a href="../index.php">Home</a></div></td></tr>
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
        <tr><td><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td align="center"><form id="submitform" name="submitform" method="post" action="" onsubmit="return false;"  >
              <table width="600" border="0" cellspacing="0" cellpadding="5" height="250px">
                <tr>
                  <td valign="top"><div style="display:block" id="tabc1">
                    <table width="99%" border="0" cellspacing="2" cellpadding="5" style="border:solid 1px #A6D2FF">
                        <tr >
                          <td colspan="3" style="font-size:12px; color:#FF0000" align="left" ><strong><span style="font-size:14px">Step 1</span>:</strong> </td>
                        </tr>
                                                <tr >
                          <td colspan="3" style="font-size:12px; color:#FF0000" align="left" >Please Enter Your Dealer Username to Proceed with.</td>
                        </tr>
                        <tr>
                          <td width="121" align="left" valign="top" style="font-size:12px"><strong> User Name</strong>:</td>
                          <input type="hidden" name="dealerid" id="dealerid"    />   
                          <td width="214" align="left" valign="top"><input name="dealerusername" type="text" class="swifttext" id="dealerusername" size="30" maxlength="50" />                          </td>
                          <td width="144" align="left" valign="top"><input name="next" type="button" class="swiftchoicebutton" id="next" value="Next"  onclick= "validation() ;" />                          </td>
                        </tr>
                      <tr>
                        <td colspan="3" style="height:35px;width:100%" id="form-error" align="left"></td>
                      </tr>
                      </table>
                  </div></td>
                  <td valign="top"><div style="display:none" id="tabc2">
                    <table width="99%" border="0" cellspacing="2" cellpadding="5" style="border:solid 1px #A6D2FF" height="100px">
                        <tr >
                          <td colspan="3" style="font-size:12px; color:#FF0000" align="left"><strong><span style="font-size:14px">Step 2</span>:</strong></td>
                        </tr>
                        <tr><td colspan="3" style="font-size:12px; color:#FF0000" align="left">Below are the email ID(s) associated with your Dealer Username (<span id="displayselecteddealerid"></span>). Please select your email account for sending password information</td></tr>
                        <tr>
                          <td width="152" align="left" valign="top" style="font-size:12px"><strong>Select your Email ID</strong>:</td>
                          <td width="220" align="left" valign="top"><!--<select name="email" id="email" class="swiftselect-mandatory" style="width: 200px;" ></select>-->
                            <div  id="emailresult" ></div></td>
                              
                          <td width="151" align="left" valign="top"><input name="send" type="button" class="swiftchoicebutton" id="send" value="Send"  onclick= "formsubmiting(form.dealerusername.value)" />                       </td>
                        </tr>
                      <tr>
                        <td colspan="3" style="height:35px;width:100%" id="form-error1" align="left"></td>
                      </tr>
                      <tr><td colspan="3" align="left" ><a onclick="document.getElementById('tabc1').style.display = 'block';document.getElementById('tabc2').style.display = 'none';disablenext();" class="passwd-font" style="cursor:pointer" >&lt;&lt; Back to Step 1</a></td></tr>
                      </table>
                  </div></td> 
                            
  <td valign="top"><div style="display:none" id="tabc3">
    <table width="99%" border="0" cellspacing="2" cellpadding="5" style="border:solid 1px #A6D2FF" height="100px">
      <tr >
        <td style="font-size:12px; color:#FF0000" align="left" ><strong><span style="font-size:16px">Step 3</span>:</strong>           </td>
      </tr>
      <tr>
        <td  align="left"  style="height:35px;width:100%; " id="form-error2"></td>
      </tr>
      <tr>
        <td align="right"><a href="../index.php" class="passwd-font" >Go to Login Page &gt;&gt;</a></td>
      </tr>
    </table>
  </div></td>
  </tr><tr><td colspan="3" height="40" id="dealerprocess" style="padding:7px" align="left" >&nbsp;</td></tr>
              </table>
             
            </form></td>
          </tr>
        </table></td></tr></table></td></tr></table></td>
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
</table>
</td>
</tr>
</table>
</td>
        </tr>
    </table></td>
  </tr>
</table>
</html>
