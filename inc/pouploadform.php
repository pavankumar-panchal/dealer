<form name="pouploadform"  id="pouploadform" action="../inc/fileupload.php?id=5&divid=po_fileuploaddiv" method="post" enctype="multipart/form-data" target="upload_target5" onsubmit="startUpload('5');" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px #333333 solid">
      <tr>
        <td id="f1_upload_process5" style="color:#FFFFFF;text-align:center" height="20px"></td>
      </tr>
      <tr>
        <td id="f1_upload_form5" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><label>File:
                <input name="myfile5" type="file" size="30" />
                <br />
                <br />
                </label>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="hidden" id="span_downloadlinkfile5" name="span_downloadlinkfile5" value="" />
                <input type="hidden" id="text_filebox5" name="text_filebox5" value="" class="swiftchoicebutton" />
                 <input type="hidden" id="cusid5" name="cusid5" value="" />
                  <input type="hidden" id="link5" name="link5" value="" class="swiftchoicebutton"/>
                   <input type="hidden" id="deletelink5" name="deletelink5" value="" class="swiftchoicebutton"/>
                <input type="submit" name="submitBtn" class="dpButton" value="Upload" />
                &nbsp;
                <input name="cancel" type="button" class="dpButton" id="cancel" value="Cancel" onclick="document.getElementById('po_fileuploaddiv').style.display='none'" />
              </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><iframe id="upload_target5" name="upload_target5" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>