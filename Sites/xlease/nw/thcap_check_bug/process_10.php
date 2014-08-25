<?php
include("../../config/config.php");
include("../function/checknull.php");

$contractID = pg_escape_string($_POST["contractID"]); // เลขที่สัญญา
$conFinAmtExtVat = pg_escape_string($_POST["conFinAmtExtVat"]); // ยอดจัด หรือเงินลงทุน (ก่อนภาษีมูลค่าเพิ่ม)
$conResidualValue1 = pg_escape_string($_POST["conResidualValue1"]); // ยอดเงินต้นคงเหลือในงวดสุดท้ายที่ต้องการ
$conResidualValue2 = pg_escape_string($_POST["conResidualValue2"]); // ยอดค่าซาก
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
pg_query("BEGIN");
$status = 0;

//----- เริ่มการสร้างตาราง EIR
	$qry_realize_eff = "select thcap_process_gen_acc_filease_realize_eff('$contractID', '$conFinAmtExtVat', '$conResidualValue1', '$conResidualValue2'); ";
	$qry_realize_eff_acc = "select thcap_process_gen_acc_filease_realize_eff_acc('$contractID', '$conFinAmtExtVat', '$conResidualValue1', '$conResidualValue2'); ";

	if($result_qry_realize_eff = pg_query($qry_realize_eff)){
	}else{
		$status++;
	}

	if($result_qry_realize_eff_acc = pg_query($qry_realize_eff_acc)){
	}else{
		$status++;
	}
//----- จบการสร้างตาราง EIR

// ตรวจสอบการสร้างตารางลดต้นลดดอกของสัญญาเช่า และเช่าซื้อ และตั๋วเงิน ใหม่
$qry_thcap_check_leasing_gen_effectivetable = "select thcap_check_leasing_gen_effectivetable();";
if($result_thcap_check_leasing_gen_effectivetable = pg_query($qry_thcap_check_leasing_gen_effectivetable)){
}else{
	$status++;
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h1><font color=\"#0000FF\">สร้างตาราง EIR สำเร็จ</font></h1></center>";
	echo "<br><center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h1><font color=\"#FF0000\">เกิดข้อผิดพลาด!!</font></h1></center>";
	echo "<br><center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
}
?>