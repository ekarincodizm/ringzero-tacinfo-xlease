<?php
session_start();
include("../config/config.php");
$id = pg_escape_string($_POST['id']);
$idno = pg_escape_string($_POST['idno']);
$chk = pg_escape_string($_POST['chk']);
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="  กลับ  " class="ui-button" onclick="window.location='old_receipt_live.php'"></div>
<div style="float:right"><input type="button" value="  Close  " class="ui-button" onclick="javascript:window.close();"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ตัดใบเสร็จประกันเก่า - คุ้มครองหนี้</B></legend>

<div align="center">

<div class="ui-widget">
<?php
$up_sql1=pg_query("UPDATE \"FOtherpay\" SET \"RefAnyID\"='$id' WHERE \"O_RECEIPT\"='$chk' AND \"IDNO\"='$idno';");
if($up_sql1){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ตัดใบเสร็จเก่าประกันภัยคุ้มครองหนี้', '$datelog')");
	//ACTIONLOG---
    echo "บันทึกเรียบร้อยแล้ว";
}else{
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>
</div>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>