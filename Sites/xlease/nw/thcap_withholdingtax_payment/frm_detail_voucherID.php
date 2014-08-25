<?php 
include("../../config/config.php");
$voucherID=pg_escape_string($_GET["voucherID"]);
//ดึงข้อมูลต่าง ๆ ที่ต้องการแสดง
$sql=pg_query("SELECT \"voucherThisDetails\",\"voucherDate\",\"doerFull\" FROM \"v_thcap_withholdingtax_payment\"
	WHERE  \"voucherID\" = '$voucherID'");	
$result = pg_fetch_array($sql);
$numrows = pg_num_rows($sql);
//มีข้อมูลจริง
if($numrows >0){
	$voucherThisDetails= $result["voucherThisDetails"]; 
	$voucherDate= $result["voucherDate"]; 
	$doerID= $result["doerFull"];	
}
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<div style="text-align:center"><h2>รายละเอียดของ  <?php echo $voucherID;?></h2></div>
<div><b>เลขที่ voucher :</b> <?php echo $voucherID;?></div>
<div><b>วันที่มีผล :</b> <?php echo $voucherDate;?></div>
<div><b>ผู้ทำรายการ :</b> <?php echo $doerID;?></div>
<fieldset><legend><b>รายละเอียด </b></legend>
<textarea cols="60" rows="4" readonly><?php echo $voucherThisDetails;?></textarea>
</fieldset>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>