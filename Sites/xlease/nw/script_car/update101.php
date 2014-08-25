<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

pg_query("BEGIN WORK");
$status = 0;

$query=pg_query("select a.\"IDNO\",c.\"C_TAX_MON\" from carregis.\"CarTaxDue\" a
left join \"Fp\" b on a.\"IDNO\"=b.\"IDNO\"
left join \"VCarregistemp\" c on b.\"IDNO\"=c.\"IDNO\"
where a.\"TypeDep\"='101' and c.\"C_TAX_MON\" is not null");

while($result=pg_fetch_array($query)){
	$IDNO=$result["IDNO"];
	$C_TAX_MON = $result["C_TAX_MON"];
	$update = "update carregis.\"CarTaxDue\" set \"CusAmt\" = '$C_TAX_MON' where \"TypeDep\"='101' and \"IDNO\" = '$IDNO'";
	if($result_up=pg_query($update)){
	}else{
		echo $update;
		$status += 1;
	}	
}

		
if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";

}else{
	pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
