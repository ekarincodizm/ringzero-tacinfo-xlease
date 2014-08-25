<?php
include("../../config/config.php");
$start=$_POST["begin"];
if($start=="begin"){
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<center>
	<fieldset style="width:700px;"><legend>Running DebtinvoiceID</legend>
				<center>
					<textarea rows="30" cols="120">
				
<?php
//Process
pg_query("begin");
	//ลบใบกำกับภาษีเดิม และเลข running เดิมออก
		//ลบรหัสใบกับกำในตาราง thcap_temp_otherpay_debt
		$qdel_step1 = pg_query("UPDATE thcap_temp_otherpay_debt SET \"debtInvID\"= null WHERE \"debtID\" is not null");
		if($qdel_step1){}else{ $status++; }
		
		//ลบเลข running ของใบกำกับทั้งหมดในตาราง thcap_running_receipt (ขึ้นต้นด้วย I)
		$qdel_step2 = pg_query("DELETE FROM thcap_running_receipt WHERE \"receiptType\" LIKE 'I%' ");
		if($qdel_step2){}else{ $status++; }
	
	//สร้างรหัสใบกำกับย้อนหลัง
		//ดึงข้อมูลเพื่อมาสร้างเลขใบกำกับ
		$query = pg_query("SELECT \"debtID\",\"contractID\",\"typePayRefDate\" FROM thcap_temp_otherpay_debt order by \"debtID\" ");
			while($re = pg_fetch_array($query)){
				$debtDate = $re["typePayRefDate"]; //วันที่ตั้งหนี้
				$contractID = $re["contractID"]; //เลขที่สัญญา
				$debtID = $re["debtID"]; //รหัสหนี้
				//เรียกใช้ function สร้างรหัสใบกำกับภาษีอยู่ใน config.php 
				$debtinvoiceID = gen_debtinvoiceID($debtDate, $contractID);
				//เพิ่มรหัสใบกำกับย้อนหลังลงในตาราง thcap_temp_otherpay_debt
				$qry_up = pg_query("UPDATE thcap_temp_otherpay_debt SET \"debtInvID\"='$debtinvoiceID' WHERE \"debtID\"='$debtID'");
				
				if($qry_up){
					echo "รหัส: ".$debtID." วันที่: ".$debtDate." สัญญา: ".$contractID."------ รหัสที่ได้: ".$debtinvoiceID."\n";
				}else{$status++; break; echo "UPDATE thcap_temp_otherpay_debt SET \"debtInvID\"='$debtinvoiceID' WHERE \"debtID\"='$debtID'";}				
			}
?>
				</textarea>
<?php				
if($status == 0){
	pg_query("COMMIT"); echo "<center><h1>Successful !</h1></center>";
}else{
	pg_query("ROLLBACK");echo "<center><h1>Failed !</h1></center>";
}
?>				
				</center>	
	</fieldset>
</center>	
<?php	
}else{
?>
<center>
	<form action="" method="POST">
		<fieldset style="width:500px;"><legend>Running DebtinvoiceID</legend>
					<center>
						<input type="hidden" name="begin" value="begin">
						<input type="submit" value="START" style="width:250px;height:100px;">
					</center>	
		</fieldset>
	</form>	
</center>
<?php } ?>