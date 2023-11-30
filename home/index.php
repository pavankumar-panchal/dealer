<?php

include('../functions/phpfunctions.php');
include('../inc/session.php');

if(imaxgetcookie('dealeruserid') == false) { $url = '../index.php'; header("Location:".$url); }
else
$userid = imaxgetcookie('dealeruserid');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
<title><?php $pagetilte = getpagetitle($_GET['a_link']); echo($pagetilte); ?></title>
</head>
<?php include('../inc/scriptsandstyles.php'); ?>
<body onload=" bodyonload(<?php echo($userid);?>);">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="maincontainer">
  <tr>
    <td valign="top" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="headercontainer">
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
              <tr>
                <td align="center"  bgcolor="#FFFFFF"><?php include('../inc/header.php'); ?></td>
              </tr>
              <tr>
                <td align="center"><?php include('../inc/navigation.php'); ?></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainbg">
              <tr>
                <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="main">
                    <tr>
                      <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
                          <!--<tr>
        <td class="bannerbg" height="118">&nbsp;</td>
      </tr>-->
            <?php
	  $query = "Select businessname,dealerusername from inv_mas_dealer where slno = '".$userid."'";
	  $fetch = runmysqlqueryfetch($query);
	  $businessname =strtoupper($fetch['businessname']); 
	  $dealerusername = strtoupper($fetch['dealerusername']);
	   ?>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="youarehere">
                                <tr>
                                  <td width="50%" align="left"><img class="arrow" alt="arrow" src="../images/herearrow.gif" />
                                    <p>You are here: Saral iMax » <?php $pageheader = getpageheader($_GET['a_link']); echo($pageheader); ?></p></td>
                                  <td width="50%" align="left" class="logindisplay"><p align="right" >Logged in as: <?php echo( $businessname); echo(' ['.$dealerusername.']')?></p></td>
                                </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td><?php $pagelink = getpagelink($_GET['a_link']); include($pagelink); ?></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><?php include('../inc/footer.php'); ?></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
