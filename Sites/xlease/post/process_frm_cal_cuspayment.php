<?php
include("../config/config.php");
include("../nw/function/checknull.php");
pg_query("BEGIN");
$status = "0";

$datenow=nowDateTime();
$user_id = $_SESSION["av_iduser"]; //ดึง รหัส ของผู้ใช้

$typeDep = pg_escape_string($_POST["typeDep"]);
$IdCarTax = pg_escape_string($_POST["idcarTax"]);
//$permit = pg_escape_string($_POST["permit"]); // ถ้าเป็น yes คือยอมให้ลบได้ แม้ ยอดค้างชำระนี้มีการคิดต้นทุนไว้แล้ว ก็ตาม
$remark_doer = pg_escape_string($_POST["note"]); // หมายเหตุการขอยกเลิก
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
   
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8">
    <meta http-equiv="Pragma" content="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
window.onbeforeunload = WindowCloseHanlder;
function WindowCloseHanlder()
{    
    opener.location.reload(true);
}
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
</head>
<body>

<?php

//ตรวจสอบว่ามีหมายเหตุการขอยกเลิก
if($remark_doer !=''){
	$remark_doer=checknull($remark_doer);
}
else{
	$status = "3";
}
// ดึง ข้อมูล TypeID 
$qry_typeid=pg_query("select \"TypeID\" from \"TypePay\" WHERE \"TName\" = '$typeDep'");
$res_typeid=pg_fetch_array($qry_typeid);
$TName = $res_typeid["TypeID"];

//ดึง ข้อมูล เดิมทั้งหมด เก็บไว้ก่อน
$qry_dataold=pg_query("select * from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDCarTax\"='$IdCarTax'");
$res_dataold=pg_fetch_array($qry_dataold);

$IDCarTax_dataold= checknull($res_dataold["IDCarTax"]);
$IDNO_dataold = checknull($res_dataold["IDNO"]);
$TaxDueDate_dataold = checknull($res_dataold["TaxDueDate"]);
$ApointmentDate_dataold = checknull($res_dataold["ApointmentDate"]);
$userid_dataold= checknull($res_dataold["userid"]);
$remark_dataold = checknull($res_dataold["remark"]);
$CusAmt_dataold = checknull($res_dataold["CusAmt"]);
$cuspaid_dataold= checknull($res_dataold["cuspaid"]);
$TypeDep_dataold = checknull($res_dataold["TypeDep"]);
$BookIn_dataold = checknull($res_dataold["BookIn"]);
$BookInDate_dataold = checknull($res_dataold["BookInDate"]);

$qry_IDCarTax_chk = pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue_reserve\" where   \"IDCarTax\" = $IDCarTax_dataold AND \"IDNO\" =$IDNO_dataold  and  \"Approved\"='9'");
$numrows2=pg_num_rows($qry_IDCarTax_chk);
if($numrows2 > 0){$status="1";}

// บันทึกข้อมูลไว้ที่ตาราง  carregis.CarTaxDue_reserve รออนุมัติ
$insdetail="INSERT INTO carregis.\"CarTaxDue_reserve\"(\"IDCarTax\", \"IDNO\", \"TaxDueDate\", \"ApointmentDate\" ,\"userid\",\"remark\",\"CusAmt\",\"cuspaid\",\"TypeDep\",\"BookIn\",\"BookInDate\",\"doerID\" ,\"doerStamp\",\"Approved\",\"remark_doer\")
VALUES ($IDCarTax_dataold,$IDNO_dataold,$TaxDueDate_dataold,$ApointmentDate_dataold,$userid_dataold,$remark_dataold,$CusAmt_dataold,$cuspaid_dataold,$TypeDep_dataold,$BookIn_dataold,$BookInDate_dataold,'$user_id','$datenow',9,$remark_doer)";
$resinsdetail=pg_query($insdetail);
if($resinsdetail){}else{ $status="1";}

//ดึง ข้อมูล  ที่ตาราง carregis."DetailCarTax" เพื่อ ทดสอบ ว่าจะ ลบ ข้อมูล หริอไม่
/*$qry_dataDetailCarTax=pg_query("select \"IDCarTax\",\"Cancel\" from carregis.\"DetailCarTax\" WHERE \"Cancel\" = 'false' AND \"IDCarTax\"='$IdCarTax'");
$numrow_dataDetailCarTax=pg_num_rows($qry_dataDetailCarTax);

if($numrow_dataDetailCarTax > 0 && $permit != "yes")
{
	$status="2";
	
} */ 
/*else
{
	// ลบ ข้อมูลทั้งหมด  รออนุมัติ
	/*$Delete_CarTaxDue="DELETE FROM carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDCarTax\" ='$IdCarTax'";
	$resu_CarTaxDue=pg_query($Delete_CarTaxDue);
	if($resu_CarTaxDue){}else{ $status="1";}
	
}*/

//ตรวจสอบว่าสามารถ บันทึกข้อมูลไว้ที่ตาราง  carregis.CarTaxDue_reserve
if($status == "0")
{
	pg_query("COMMIT");	
	echo "<center><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></center><br><br>";		
	
}
else
{
	pg_query("ROLLBACK");
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";

}
?>
<center><input type="button" value="ปิด" onClick="RefreshMe();"></center>
</body>
</html>