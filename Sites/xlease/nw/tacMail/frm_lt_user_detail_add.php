<?php
session_start();
include("../../config/config.php");

$sid = $_SESSION["av_iduser"];
$nowdate = date("Y-m-d H:i:s");

$CusID = $_POST['CusID'];
$AddrType = $_POST['AddrType'];
$DocID = $_POST['DocID'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">  
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_lt.php'"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>        

<fieldset><legend><B>ทำรายการส่งจดหมาย</B></legend>

<div class="ui-widget" align="center">
<?php
pg_query("BEGIN WORK");


	
	
	$ins = "insert into \"TacMail\" (\"tmCusID\",\"tmDoc\",\"tmAddType\",\"tmUserID\",\"tmTimeStamp\") values ('$CusID','$DocID','$AddrType','$sid','$nowdate')";
	
	if($result1=pg_query($ins)){
    
	}else{
		$status += 1;
	}
	
	
	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$sid', '(TAL) ส่งจดหมาย 1681', '$nowdate')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" value=\"พิมพ์จดหมาย\" onclick=\"window.open('print_letter.php?cus_lid=$CusID&AddrType=$AddrType')\"><input type=\"button\" value=\"พิมพ์ใบเหลือง\" onclick=\"window.open('print_yellow.php?cus_lid=$CusID&AddrType=$AddrType&DocID=$DocID')\">";
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br />$ins";
	}




?>
</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>