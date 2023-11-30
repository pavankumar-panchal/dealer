<form name="referenceuploadform"  id="referenceuploadform" action="../inc/fileupload.php?id=3&divid=references_fileuploaddiv" method="post" enctype="multipart/form-data" target="upload_target3" onsubmit="startUpload('3');" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px #333333 solid">
      <tr>
        <td id="f1_upload_process3" style="color:#FFFFFF;text-align:center" height="20px"></td>
      </tr>
      <tr>
        <td id="f1_upload_form3" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><label>File:
                <input name="myfile3" type="file" size="30" />
                <br />
                <br />
                </label>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="hidden" id="span_downloadlinkfile3" name="span_downloadlinkfile3" value="" />
                <input type="hidden" id="text_filebox3" name="text_filebox3" value="" class="swiftchoicebutton" />
                 <input type="hidden" id="cusid3" name="cusid3" value="" />
                  <input type="hidden" id="link3" name="link3" value="" class="swiftchoicebutton"/>
                   <input type="hidden" id="deletelink3" name="deletelink3" value="" class="swiftchoicebutton"/>
                <input type="submit" name="submitBtn" class="dpButton" value="Upload" />
                &nbsp;
                <input name="cancel" type="button" class="dpButton" id="cancel" value="Cancel" onclick="document.getElementById('references_fileuploaddiv').style.display='none'" />
              </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><iframe id="upload_target3" name="upload_target3" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>