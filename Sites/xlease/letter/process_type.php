<?php
include "../config/config.php";

$method = pg_escape_string($_POST["method"]);
$method2 = pg_escape_string($_GET["method2"]);
$type_name = pg_escape_string($_POST["type_name"]);
$is_use = pg_escape_string($_POST["is_use"]);
$auto_id = pg_escape_string($_POST["auto_id"]);
$auto_id2 = pg_escape_string($_GET["auto_id2"]);

$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

if($auto_id2 != ""){
	$auto_id = $auto_id2;
}
if($method2 != ""){
	$method = $method2;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จ่ายค่าปรับฝ่าฝืนสัญญาณไฟจราจร</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
<?php
	if($method == "add"){
?>
<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_type_letter.php'"></div>
<?php }else if($method == "edit"){?>
<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_edit_type_letter.php'"></div>
<?php }else if($method == "delete"){?>
<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_delete_type_letter.php'"></div>
<?php }?>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>        

<fieldset><legend><B>บันทึกรายการ</B></legend>

<div class="ui-widget" align="center" style="padding:50px;">
<?php
pg_query("BEGIN WORK");
$status = 0;



if($method == "add"){
	$ins = "insert into letter.\"type_letter\"(\"type_name\",\"is_use\") values ('$type_name','$is_use')";
}else if($method == "edit"){
	$ins = "update letter.\"type_letter\" SET \"type_name\"='$type_name',\"is_use\"='$is_use' WHERE \"auto_id\"='$auto_id'";
}else if($method == "delete"){
	$ins = "delete from letter.\"type_letter\" WHERE \"auto_id\"='$auto_id'";
}

if($result1=pg_query($ins)){
    
}else{
    $status += 1;
}

if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ทำการเพิ่มรูปแบบจดหมาย', '$currentdate')");
		//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง <hr>$ins";
}
?>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>