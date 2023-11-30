<form name="fileuploadform"  id="fileuploadform" action="../inc/fileupload.php?id=6&divid=seztaxuploaddiv" method="post" enctype="multipart/form-data" target="upload_target6" onsubmit="startUpload('6');" >
    <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px #333333 solid">
      <tr>
        <td id="f1_upload_process6" style="color:#FFFFFF; text-align:center" height="20px" ></td>
      </tr>
      <tr>
        <td id="f1_upload_form6" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><label><strong>File</strong>:
                <input name="myfile6" type="file" size="30" />
                <br />
                <br />
                </label>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="hidden" id="span_downloadlinkfile6" name="span_downloadlinkfile6" value="" />
                <input type="hidden" id="text_filebox6" name="text_filebox6" value="" class="swiftchoicebutton" />
                <input type="hidden" id="cusid6" name="cusid6" value="" />
                <input type="hidden" id="link6" name="link6" value="" class="swiftchoicebutton"/>
                 <input type="hidden" id="deletelink6" name="deletelink6" value="" class="swiftchoicebutton"/>
                <input type="submit" name="submitBtn" class="dpButton" value="Upload" />
                &nbsp;
                <input name="cancel" type="button" class="dpButton" id="cancel" value="Cancel" onclick="document.getElementById('seztaxuploaddiv').style.display='none'" />
              </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><iframe id="upload_target6" name="upload_target6" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>