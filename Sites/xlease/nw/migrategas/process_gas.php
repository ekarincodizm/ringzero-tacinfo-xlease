<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

$query = pg_query("SELECT * FROM pmain.fc where \"C_CARNAME\" like '%ยี่ห้อ%' or \"C_CARNUM\" like '%เลขถัง%'"); 

//$query = pg_query("SELECT * FROM pmain.fc where \"IDNO\" = '510-01002'"); 

$numrows = pg_num_rows($query);

pg_query("BEGIN WORK");
$status = 0;

$inputValue=0;
$digit=0;
$num_gas1=0;
while($result = pg_fetch_array($query)){
	$IDNO = $result["IDNO"];
	$C_CARNAME = $result["C_CARNAME"]; //ยี่ห้อแก๊ส
	$C_COLOR = $result["C_COLOR"]; //เลขถังแก๊สที่ติด
	$C_REGIS = $result["C_REGIS"]; //ทะเบียนรถที่ติดถังแก๊ส
	$C_REGIS_BY = $result["C_REGIS_BY"]; //รถที่ติดถังจดทะเบียนจังหวัด
	$C_YEAR = $result["C_YEAR"]; //ปีของรถทีติด
	
	$querymar = pg_query("select * from \"Fc\" where \"C_REGIS\" = '$C_REGIS'");
	$resultmar = pg_fetch_array($querymar);
	$C_MARNUM = $resultmar["C_MARNUM"]; //เลขเครื่องยนต์ของรถที่ติดถังแก๊ส
	$C_CARNUM = $resultmar["C_CARNUM"]; // เลขตัวถังรถยนต์ที่ติดถังแก๊ส
	
	//นำ IDNO ไปค้นในตาราง Fp เพื่อดูว่ามีข้อมูลหรือไม่ จะกระทำก็ต่อเมื่อมีข้อมูลใน Fp แล้วเท่านั้น
	$query_fp = pg_query("select * from \"Fp\" where \"IDNO\" ='$IDNO'");
	$num_fp = pg_num_rows($query_fp);
	
	if($num_fp != 0){ //กรณีพบข้อมูล
		//ตรวจสอบข้อมูลใน FGas ว่ามีข้อมูลนี้หรือยัง ถ้ายังไม่มีให้ insert
		$query_gas = pg_query("select * from \"FGas\" a
		left join \"Fp\" b on a.\"GasID\" = b.\"asset_id\"
		where b.\"IDNO\" = '$IDNO'");
		$num_gas = pg_num_rows($query_gas);	
		
		if($num_gas == 0){ //กรณียังไม่มีข้อมูล
			//Gen ID แก๊สขึ้นมาใหม่เพื่อ insert
			$qry_gcid=pg_query("select count(\"GasID\") AS gcount from \"FGas\"");
			$res_glast=pg_fetch_array($qry_gcid);
			$res_g=$res_glast[gcount];
			if($res_g==0){
				$res_gn=1;
			}else{
				$res_gn=$res_g+1;
			}
			
			$pre_idsn="GAS".insertZero_id($res_gn,5);
			$ins_gas = "insert into \"FGas\" (\"GasID\",\"gas_name\",\"gas_number\",\"gas_type\",\"car_regis\",\"car_regis_by\",\"car_year\",\"carnum\",\"marnum\")
										values ('$pre_idsn','$C_CARNAME','$C_COLOR',' ','$C_REGIS','$C_REGIS_BY','$C_YEAR','$C_CARNUM','$C_MARNUM')";
			if($result_gas=pg_query($ins_gas)){
			}else{
				$status += 1;
			}
			
			$update = "update \"Fp\" set \"asset_id\" = '$pre_idsn' where \"IDNO\" = '$IDNO'";
			if($result_up=pg_query($update)){
			}else{
				$status += 1;
			}	
			$num_gas1 = $num_gas1 + 1;
		}

	}	
} //end while
function insertZero_id($inputValue,$digit){
	$str = "" . $inputValue;
	while (strlen($str) < $digit){
		$str = "0" . $str;
	}
	return $str;
}

if($status == 0){
	if($num_gas1 == 0){
		echo "<div align=center><h1>ไม่พบรายการที่ยังไม่เพิ่มในตาราง FGas</h1></div>";
	}else{
		pg_query("COMMIT");
		echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
	}
}else{
	pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
