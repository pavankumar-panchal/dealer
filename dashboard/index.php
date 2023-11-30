<?php
include('../inc/eventloginsert.php');
	//session_start();
	$userid = imaxgetcookie('dealeruserid');
	$query = "SELECT inv_mas_dealer.businessname AS businessname,inv_mas_dealer.contactperson AS contactperson,
inv_mas_dealer.region AS region,inv_mas_dealer.contactperson AS contactperson,
inv_mas_dealer.address AS address,inv_mas_dealer.place AS place,inv_mas_district.districtname AS district,
inv_mas_state.statename AS state,inv_mas_dealer.pincode AS pincode,
inv_mas_dealer.stdcode AS stdcode,inv_mas_dealer.phone AS phone,inv_mas_dealer.cell AS cell,
inv_mas_dealer.emailid AS emailid,inv_mas_dealer.website AS website,inv_mas_dealer.saifreepin
 FROM inv_mas_dealer LEFT JOIN inv_mas_district ON inv_mas_district.districtcode=inv_mas_dealer.district LEFT JOIN 
inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode WHERE inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$businessname = $fetch['businessname'];
	$region = $fetch['region'];
	$contactperson = $fetch['contactperson'];
	$address = $fetch['address'];
	$place = $fetch['place'];
	$district = $fetch['district'];
	$state = $fetch['state'];
	$pincode = $fetch['pincode'];
	$stdcode = $fetch['stdcode'];
	$phone = $fetch['phone'];
	$cell = $fetch['cell'];
	$emailid = $fetch['emailid'];
	$website = $fetch['website'];
	$contactperson = $fetch['contactperson'];
	$saifreepin = $fetch['saifreepin'];

 
?>
<script type="text/javascript" src="../functions/highcharts.js?dummy = <?php echo (rand());?>"></script>
<!--[if lt IE 7]>
<script type="text/javascript" src="../functions/excanvas.compiled.js?dummy = <?php echo (rand());?>"></script>
<![endif]-->
<link href="../style/shortkey.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/key_shortcut.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/dashboard-shortcut.js?dummy=<?php echo (rand());?>"></script>


<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2"></td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><div align="left"><strong style="font-size:16px; font-weight:bold" >Welcome <?php echo($businessname)?>....</strong></div></td>
                      <td><?php if($saifreepin == 'yes') {?><div align="right" style="padding-right:30px;"><a href="http://imax.relyonsoft.net/dealer/home/index.php?a_link=saralaccountscompcopy" class="editlink"><font color="#FF0000"><strong>SAI Free PIN</strong></font></a></div> <?php }?></td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="padding-left:20px;padding-right:20px" ><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;" align="center">
                          <tr>
                            <td class="header-line" style="padding:0">&nbsp;Your Profile:(<a href="./index.php?a_link=editprofile" class="editlink" ><font color="#000000">Edit</font></a>)</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td class="content-box"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="35%" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="50%"><table width="100%" border="0" cellspacing="1" cellpadding="3">
                                                            <tr>
                                                              <td width="27%" bgcolor="#EDF4FF"><div align="left">Business Name:</div></td>
                                                              <td width="73%" bgcolor="#f7faff"><div align="left"><?php echo($businessname);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Contact Person:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($contactperson);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Address:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($address);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Place:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($place);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Email:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($emailid);?></div></td>
                                                            </tr>
                                                          </table></td>
                                                        <td width="50%"><table width="100%" border="0" cellspacing="1" cellpadding="3">
                                                            <tr>
                                                              <td width="18%" bgcolor="#EDF4FF"><div align="left">District:</div></td>
                                                              <td width="82%" bgcolor="#f7faff"><div align="left"><?php echo($district);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">State:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($state);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Phone:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($phone);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Cell:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($cell);?></div></td>
                                                            </tr>
                                                            <tr>
                                                              <td bgcolor="#EDF4FF"><div align="left">Website:</div></td>
                                                              <td bgcolor="#f7faff"><div align="left"><?php echo($website);?></div></td>
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
                                </form>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="contbox-top" >&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="contbox-mid" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td >&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td align="center"><div id="container" style="width: 500px; height: 250px;">
                                            <script type="text/javascript" src="../functions/updationhighcharts.js?dummy = <?php echo (rand());?>"></script>
                                          </div></td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td align="center"><div id="graph2" style="width: 400px; height: 250px;">
                                            <script type="text/javascript" src="../functions/grouphighchart.js?dummy = <?php echo (rand());?>"></script>
                                          </div></td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr >
                            <td class="contbox-mid">&nbsp;</td>
                          </tr>
                          <tr>
                            <td class="contbox-btm">&nbsp;</td>
                          </tr>
                          <tr><td><form action="" method="post" name="detailform" id="detailform"><div style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="wa" id="shortcut-grid">
  <div  class="wc">
    <table cellpadding="0" class="cf wf">
      <tbody>
        <tr>
          <td class="wk Dp">Keyboard shortcuts</td>
          <td class="wj Dp"></td>
</tr>
        <tr>
          <td class="Dn"><table cellpadding="0" class="cf">
              <tbody>
                <tr>
                  <th class="Do"></th>
                  <th class="Do">Navigation</th>
                </tr>
                <tr>
                  <td class="wg Dn"><span class="wh">Alt + Shift + C</span> :</td>
                  <td class="we Dn">Customer Master Page</td>
                </tr>
                <tr>
                  <td class="wg Dn"><span class="wh">Alt + Shift + I</span>:</td>
                  <td class="we Dn">Invoicing Page</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                  <tr>
                  <td  colspan="2"><span class="wk Dp" style="font-weight:bold" >TIP:</span> <span class="we Dn">To view the list in a Drop-Down, press F4 button from the keyboard, while keeping the focus on that field.</span></td>
                </tr>
              </tbody>
          </table></td>
          <td class="Dn">&nbsp;</td>
        </tr>
      </tbody>
    </table>
  </div>
    </div>
</td>
  </tr>
</table>
 </div></form></td></tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td colspan="2"></td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center"></td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
