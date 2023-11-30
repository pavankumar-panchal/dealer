<?php
include('../functions/phpfunctions.php');
if(isset($_POST['update']))
{
	$userid = imaxgetcookie('dealeruserid');
	$oldpassword = $_REQUEST['oldpassword'];
	$newpassword = $_REQUEST['newpassword'];
	$confirmpassword = $_REQUEST['confirmpassword'];
	if($oldpassword == ''|| $newpassword == ''|| $confirmpassword == '')
	{
		$message = 'Enter the password';
	}
	else
	{
	$query="SELECT password,passwordchanged FROM inv_mas_dealer WHERE slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$dbpassword = $fetch['password'];
	if($dbpassword == $oldpassword)
	{
		if($newpassword == $confirmpassword)
		{
			$query = "UPDATE inv_mas_dealer SET password = '".$newpassword."',passwordchanged ='Y' WHERE slno ='".$userid."';";
			$result = runmysqlquery($query);
			$message = 'Your Password has been changed successfully';
		}
		else
		{
			$message = 'New Password does not match with the Confirm Password';
		}
	}
	else
	{
		$message = "New Password does not match with the Old Password";
	}
	}
}
	
?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2" class="content-table-border">
  <tr>
    <td><form name="loginform" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="content-box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><fieldset class="swiftfieldset" style="padding:0px;">
          <legend><img src="../images/icon_lock.gif" alt="lock" align="absmiddle" border="0" /> Change Password</legend>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <tr>
              <td width="20%" align="left"><span class="smalltext">Old Password:</span></td>
              <td width="80%"><input name="oldpassword" size="25" class="swifttext" value="" type="password" /></td>
            </tr>
            <tr>
              <td width="20%" align="left"><span class="smalltext">New Password:</span></td>
              <td  width="80%"><input name="newpassword" size="25" class="swifttext" value="" type="password" /></td>
            </tr>
            <tr>
              <td width="20%" align="left"><span class="smalltext">Confirm Password:</span></td>
              <td width="80%"><input name="confirmpassword" size="25" class="swifttext" value="" type="password" id="confirmpassword" /></td>
            </tr>
          </table>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <tbody>
              <tr>
                <td width="30%">&nbsp;
                    <input name="update"  value="Update" type="submit" class="swiftchoicebutton" id="update" /></td>
                <td width="70%"><?php echo($message); ?></td>
              </tr>
            </tbody>
          </table>
        </fieldset></td>
      </tr>
    </table></td>
  </tr>
</table>
    </form>
</td>
  </tr>
</table>
</td>
  </tr>
</table>
</td>
  </tr>
</table>

