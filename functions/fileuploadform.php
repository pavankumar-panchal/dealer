<form name="fileuploadform"  id="fileuploadform" action="../inc/fileupload.php" method="post" enctype="multipart/form-data" target="upload_target" onSubmit="startUpload('1');" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px #333333 solid">
      <tr>
        <td id="f1_upload_process" style="color:#FFFFFF"></td>
      </tr>
      <tr>
        <td id="f1_upload_form" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><label>File:
                <input name="myfile" type="file" size="30" />
                <br />
                <br />
                </label>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="hidden" id="span_downloadlinkfile" name="span_downloadlinkfile" value="" />
                <input type="hidden" id="text_filebox" name="text_filebox" value="" class="swiftchoicebutton" />
                <input type="submit" name="submitBtn" class="dpButton" value="Upload" />
                &nbsp;
                <input name="cancel" type="button" class="dpButton" id="cancel" value="Cancel" onClick="document.getElementById('fileuploaddiv').style.display='none'" />
              </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>