<?php
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>

<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/number.js"></script>
</head>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$appv = $_POST["appv"]; // 1 = อนุมัติ / 2 = ไม่อนุมัติ
$tempID = $_POST["tempID"];
$note = $_POST["app_note"];

pg_query("BEGIN");
$status = 0;



if($appv == "2") // ถ้าไม่อนุมัติ
{
	$qryYesAppvTemp ="SELECT \"thcap_process_changeintrate\"(null,null,null,null,null,null,'$id_user','REJECT','$tempID','$note')";
	if($resultYES = pg_query($qryYesAppvTemp)){}else{$status++;}
}
elseif($appv == "1") // ถ้าอนุมัติ
{
	//ดึงข้อมูล เลขที่สัญญาและวันที่อัตราดอกเบี้ยมีผลออกมา
	$chk1 = pg_query("SELECT \"contractID\",\"effectiveDate\" FROM \"thcap_changeRate_temp\" WHERE \"tempID\" = '$tempID' ");
	list($conid,$effectdate) = pg_fetch_array($chk1);
	//หาวันที่จ่ายค่างวดล่าสุด
	$chk2 = pg_query("SELECT MAX(\"receiveDate\") FROM thcap_v_receipt_otherpay where \"contractID\" = '$conid' AND \"typePayID\" = account.\"thcap_mg_getMinPayType\"('$conid')");
	list($max_receiveDate) = pg_fetch_array($chk2);
	//หากวันที่จ่ายล่าสุดเลยวันที่ขอเปลี่ยนอัตราดอกเบี้ยไปแล้วจะไม่สามารถเปลี่ยนได้
	IF($max_receiveDate > $effectdate){

		echo "<center><h2><font color=\"#0000FF\">ไม่สามารถอนุมัติรายการนี้ได้ <p>เนื่องจากมีการชำระในอัตราดอกเบี้ยเดิมหลังจาก<p>วันที่ขอเปลี่ยนแปลงอัตราดอกเบี้ยนี้แล้ว !!</font></h2></center>";
		$status++;
	}else{
		$qryYesAppvTemp ="SELECT \"thcap_process_changeintrate\"(null,null,null,null,null,null,'$id_user','APPROVE','$tempID','$note')";
		if($resultYES = pg_query($qryYesAppvTemp)){}else{$status++;}
	}	
}

if($status == 0)
{

	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<center><h3><font color=\"#FF0000\">$returnFunction</font></h3></center>";
	echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
}
?>