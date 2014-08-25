<?php
include("../config/config.php");
$auto_id = pg_escape_string($_GET["auto_id2"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รูปแบบจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function checkdata(){
	if(document.getElementById('type_name').value == ""){
		alert("กรุณากรอกชื่อประเภทจดหมาย");
		document.getElementById('type_name').focus();
		return false;
	}
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>
<?php
	$query_name = pg_query("select * from letter.\"type_letter\" where \"auto_id\" = '$auto_id'");
	$res_type=pg_fetch_array($query_name);
	$name = $res_type["type_name"];
	$status = $res_type["is_use"];
?>
<form method="post" name="form1" action="process_type.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<div style="float:left">
<input type="button" value="กลับ" onclick="window.location='frm_edit_type_letter.php'">
</div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>แก้ไขประเภทของรูปแบบจดหมาย</B></legend>
<div class="ui-widget" align="center">

<div style="margin:0; padding: 10px;">
<table cellSpacing="1" cellPadding="3" border="0" bgcolor="#F0F0F0" align="center" width="80%">
	<tr>
		<td width="150" align="right" bgcolor="#D5EFFD"><b>ชื่อประเภท :</b></td>
		<td bgcolor="#FFFFFF"><input type="text" name="type_name" id="type_name" size="60" value="<?php echo $name;?>"><input type="hidden" name="method" value="edit"><input type="hidden" name="auto_id" value="<?php echo $auto_id;?>"></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#D5EFFD"><b>สถานะการใช้งาน :</b></td>
		<td bgcolor="#FFFFFF">
			<select name="is_use">
				<option value="TRUE" <?php if($status == 't'){ echo "selected";}?>>อนุญาตให้ใช้</option>
				<option value="FALSE" <?php if($status == 'f'){ echo "selected";}?>>ไม่อนุญาตให้ใช้</option>
			</select>
		</td>
	</tr>
</table>
<table cellSpacing="1" cellPadding="3" border="0" align="center" width="80%">
	<tr>
		<td height="50" align="center"><input type="submit" value="บันทึก" onclick="return checkdata();"><input type="reset" value="ยกเลิก"></td>
	</tr>
</table>
</div>

</div>
</fieldset>

        </td>
    </tr>
</table>
</form>
</body>
</html>