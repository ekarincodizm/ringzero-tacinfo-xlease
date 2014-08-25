<?php
include("../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title><?php echo $_SESSION["session_company_name"]; ?></title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#tb_old").autocomplete({
        source: "update_refinance_list_idno.php?cmd=1",
        minLength:1,
        close: function(event, ui){
            $("#tb_new").val('');
            $("#tb_new").focus();
             var vv_old = $("#tb_old").val();
             var vresstr1=vv_old.split("#");
            $.post('update_refinance_list_idno.php',{
                cmd: 3,
                idno: vresstr1[0]
            },
            function(data){
                //
            },'html');
        }
    });
    
    $("#tb_new").autocomplete({
        source: "update_refinance_list_idno.php?cmd=2",
        minLength:1
    });
    
    $('#btn_show').click(function(){
        var vv_old = $("#tb_old").val();
        var vresstr1=vv_old.split("#");
        var vv_new = $("#tb_new").val();
        var vresstr2=vv_new.split("#");
        
        $("#panel").empty();
        $("#panel").html('<img src="../images/progress.gif" border="0" width="32" height="32" alt="กำลังโหลด...">');
        $("#panel").load('update_refinance_show.php?idno='+ vresstr1[0] +'&idno2='+ vresstr2[0]);
    });
});
</script>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>

<div style="float:left"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both"></div>
        
<fieldset><legend><B>ปรับปรุงรถยึด หรือ Re Finance</B></legend>

<div align="left" style="margin:5px">
<b>เลขที่สัญญาเก่า</b> <input type="text" name="tb_old" id="tb_old" size="50">
<b>เลขที่สัญญาใหม่</b> <input type="text" name="tb_new" id="tb_new" size="50">
<input type="submit" name="btn_show" id="btn_show" value="แสดงข้อมูล">
</div>

<div id="panel" style="margin:5px"></div>

</fieldset>

		</td>
	</tr>
</table>

</body>
</html>