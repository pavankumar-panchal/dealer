<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/javascript.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/saralaccountscompcopy.js?dummy=<? echo (rand());?>"></script>
<div style="left: -1000px; top: 597px;visibility: hidden; z-index:100" id="tooltip1"></div>
<script language="javascript" src="../functions/tooltip.js?dummy=<? echo (rand());?>"></script>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:left">
  <tr>
    <td valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
      <tr>
        <td valign="top"><form id="submitform" name="submitform" method="post" action="" onsubmit="return false;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
              </tr>
            <tr>
              <td><table width="70%" border="0" align="center" cellpadding="2" cellspacing="0" style=" border: 1px solid #E6F2FF">
                <tr style="background:#edf4ff">
                  <td width="31%">Customer ID (Optional):</td>
                  <td width="69%"><input name="customerid" type="text" class="swifttext" id="customerid" size="30" maxlength="40"  autocomplete="off"/><input name="lastslno" type="hidden"  id="lastslno" value=""/></td>
                  </tr>
                <tr style="background:#F7FAFF">
                  <td>Remarks:</td>
                  <td><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"  style="resize:none"></textarea></td>
                  </tr>
                <tr>
                  <td colspan="2" id="form-error" height="35px"></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><div align="left">
                    <input name="generatepin" type="button" class="swiftchoicebuttonbig" id="generatepin" value="Generate PIN" onclick="formsubmit();" />
                    &nbsp;
                    <input name="resetform" type="button" class="swiftchoicebutton" id="resetform" value="Reset" onclick="clearform()" />
                    </div></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                </table></td>
              </tr>
            </table>
          </form></td>
        </tr>
         <tr>
              <td>&nbsp;</td>
              </tr>
         <tr>
           <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr>
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">Recent Downloads</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span></span></td>
                                  <td width="4%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                  <td width="8%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:250px; width:950px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" style="height:20px; padding:2px;" align="left"> </div></td>
                                        </tr>
                                      </table>
                                      <div id="regresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center"></div>
                                    </div>
                                    
                                    
                                    
                                    </td>
                                </tr>
                              </table></td>
         </tr>
         <tr>
           <td>&nbsp;</td>
         </tr>
    </table></td>
  </tr>
</table>

