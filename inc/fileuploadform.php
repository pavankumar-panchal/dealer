<form name="fileuploadform"  id="fileuploadform" action="../inc/fileupload.php?id=1&divid=fileuploaddiv" method="post" enctype="multipart/form-data" target="upload_target1" onsubmit="startUpload('1');" >
    <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px #333333 solid">
      <tr>
        <td id="f1_upload_process1" style="color:#FFFFFF; text-align:center" height="20px" ></td>
      </tr>
      <tr>
        <td id="f1_upload_form1" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><label><strong>File</strong>:
                <input name="myfile1" type="file" size="30" />
                <br />
                <br />
                </label>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="hidden" id="span_downloadlinkfile1" name="span_downloadlinkfile1" value="" />
                <input type="hidden" id="text_filebox1" name="text_filebox1" value="" class="swiftchoicebutton" />
                <input type="hidden" id="cusid1" name="cusid1" value="" />
                <input type="hidden" id="link1" name="link1" value="" class="swiftchoicebutton"/>
                 <input type="hidden" id="deletelink1" name="deletelink1" value="" class="swiftchoicebutton"/>
                <input type="submit" name="submitBtn" class="dpButton" value="Upload" />
                &nbsp;
                <input name="cancel" type="button" class="dpButton" id="cancel" value="Cancel" onclick="document.getElementById('fileuploaddiv').style.display='none'" />
              </td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><iframe id="upload_target1" name="upload_target1" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>