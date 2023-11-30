<form name="sppdatauploadform"  id="sppdatauploadform" action="../inc/fileupload.php?id=4&divid=sppdata_fileuploaddiv" method="post" enctype="multipart/form-data" target="upload_target4" onsubmit="startUpload('4');" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px #333333 solid">
      <tr>
        <td id="f1_upload_process4" style="color:#FFFFFF;text-align:center" height="20px"></td>
      </tr>
      <tr>
        <td id="f1_upload_form4" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><label>File:
                <input name="myfile4" type="file" size="30" />
                <br />
                <br />
                </label>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="hidden" id="span_downloadlinkfile4" name="span_downloadlinkfile4" value="" />
                <input type="hidden" id="text_filebox4" name="text_filebox4" value="" class="swiftchoicebutton" />
                 <input type="hidden" id="cusid4" name="cusid4" value="" />
                  <input type="hidden" id="link4" name="link4" value="" class="swiftchoicebutton"/>
                   <input type="hidden" id="deletelink4" name="deletelink4" value="" class="swiftchoicebutton"/>
                <input type="submit" name="submitBtn" class="dpButton" value="Upload" />
                &nbsp;
                <input name="cancel" type="button" class="dpButton" id="cancel" value="Cancel" onclick="document.getElementById('sppdata_fileuploaddiv').style.display='none'" />
              </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><iframe id="upload_target4" name="upload_target4" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>