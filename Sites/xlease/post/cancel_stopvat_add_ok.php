<?php
session_start();
include("../config/config.php");
$idno = pg_escape_string($_POST['idno']);
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$payment_tmoney = pg_escape_string($_POST['payment_tmoney']);
$signDate = pg_escape_string($_POST['signDate']);
$payment_smoney = pg_escape_string($_POST['payment_smoney']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="JavaScript" type="text/javascript">
<!--
function RefreshMe(){
    opener.location.reload(true);
    //self.close();
}
// -->
</script>  

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>    

    </head>
<body>

<fieldset><legend><B>Cancel Stop VAT</B></legend>
<div align="center">
<?php
pg_query("BEGIN");
$status = 0;

// ตรวจสอบว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_fr=pg_query("select * from \"Fp\" WHERE \"IDNO\" = '$idno' AND \"P_StopVat\"='true' AND \"P_ACCLOSE\"='false' ");
$numrow = pg_num_rows($qry_fr);
if($numrow == 0)
{
	$status++;
	echo "ไม่สามารถทำรายการได้ มีการทำรายการไปก่อนหน้านี้แล้ว!!<br><br>";
}

$rs=pg_query("select \"need_rec_cancel_stopvat\"('$idno')");
$rt0=pg_fetch_result($rs,0);
if(!$rt0){
    $status++;
	echo "เกิดข้อผิดพลาด<br><br>";
}

$rs=pg_query("select \"accept_cancel_stopvat\"('$idno','$signDate','$payment_tmoney','$payment_smoney')");
$rt1=pg_fetch_result($rs,0);
if( empty($rt1) ){
	$status++;
    echo "ไม่สามารถ บันทึกข้อมูลได้<br><br>";
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ยกเลิก STOP VAT', '$add_date')");
	//ACTIONLOG---
    echo "บันทึกข้อมูลเรียบร้อยแล้ว<br><br>";
?>
<input name="button" type="button" onclick="javascript:popU('../ca/frm_recprint.php?id=<?php echo $rt1; ?>','<?php echo "$IDNO_recprint"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800'); javascript:RefreshMe();" value="พิมพ์ใบเสร็จ" />
<?php
}
else
{
	pg_query("ROLLBACK");
?>
	<input type="button" value="    ปิด    " onclick="javascript:window.close(); javascript:RefreshMe();">
<?php
}
?>
</div>
</fieldset> 

</body>
</html>