<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$contractID = pg_escape_string($_POST['contractID']);
$RcheckIni = pg_escape_string($_POST['RcheckIni']);
$status=0;

//รับค่าต้นทุนเริ่มแรก
		$rowiniCost = pg_escape_string($_POST["rowiniCost"]); // จำนวนรายการต้นทุนเริ่มแรก
		for($b=1;$b<=$rowiniCost;$b++)
		{
			$iniType[$b] = pg_escape_string($_POST["iniType$b"]); // ประเภทต้นทุนเริ่มแรก
			$sumIniCost[$b] = pg_escape_string($_POST["sumIniCost$b"]); // จำนวนเงิน
		}
	pg_query("Begin");
	
		if($RcheckIni==0){
			for($a=1;$a<=$rowiniCost;$a++){
						$qry_ins="insert into thcap_contract_inicost (\"contractID\",\"costtype\",\"netIniCost\",\"vatIniCost\",\"sumIniCost\",ini_add_user,ini_add_stamp,ini_appv_status) 
						values ('$contractID','0','0','0','0','$user_id','$datenow','2') ";
				if(pg_query($qry_ins)){
				} else {
					$status++;
					}
			}
		}else {
			for($c=1;$c<=$rowiniCost;$c++){
						$qry_ins="insert into thcap_contract_inicost (\"contractID\",\"costtype\",\"netIniCost\",\"vatIniCost\",\"sumIniCost\",ini_add_user,ini_add_stamp,ini_appv_status) 
						values ('$contractID','$iniType[$c]',cal_rate_or_money('VAT','$datenow'::date,$sumIniCost[$c],2),cal_rate_or_money('VAT','$datenow'::date,$sumIniCost[$c],1),'$sumIniCost[$c]','$user_id','$datenow','2') ";
				if(pg_query($qry_ins)){
				} else {
					$status++;
					}
			}
		}
						
	if($status == 0){
	pg_query("COMMIT");
	$alert="บันทึกข้อมูลสำเร็จแล้ว";
	}else{
	pg_query("ROLLBACK");
	$alert="บันทึกข้อมูลล้มเหลว";
	}
?>
<html>
	<form action="frm_Request.php" method="post">
		<center>
			<H1><?php echo $alert ?></H1><br>
			<input type="submit" name="OK" value="OK" onclick="refres();">
		</center>
	</form>
</html>