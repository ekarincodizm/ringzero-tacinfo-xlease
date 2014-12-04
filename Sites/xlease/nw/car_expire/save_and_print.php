<?php // Start Program
set_time_limit(0);
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$IDNO = pg_escape_string($_GET['IDNO']);
$CarID = pg_escape_string($_GET['CarID']);
$expireDate = pg_escape_string($_GET['expireDate']);
?>

<script>
	function popU(U,N,T){
		newWindow = window.open(U, N, T);
	}
</script>
	
<?php
pg_query("BEGIN");
$status = 0;

// ตรวจสอบก่อนว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_check = pg_query("select * from \"car_expire_print\" where \"IDNO\" = '$IDNO' and \"CarID\" = '$CarID' and \"expireDate\" = '$expireDate' ");
$row_check = pg_num_rows($qry_check);
if($row_check > 0)
{
	$status++;
	$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";
}
else
{
	// หาข้อมูล
	$qry_data = pg_query("
							SELECT
								a.\"IDNO\",
								a.\"CusID\",
								c.\"full_name\",
								c.\"full_address\",
								b.\"CarID\",
								b.\"C_REGIS\"
							FROM
								\"Fp\" a, \"Fc\" b, \"VSearchCusCorp\" c
							WHERE
								a.\"asset_id\" = b.\"CarID\" AND
								a.\"CusID\" = c.\"CusID\" AND
								a.\"IDNO\" = '$IDNO' AND
								b.\"CarID\" = '$CarID'
						");
	$CusID = pg_fetch_result($qry_data,1);
	$CusName = pg_fetch_result($qry_data,2);
	$CusAddress = pg_fetch_result($qry_data,3);
	$C_REGIS = pg_fetch_result($qry_data,5);

	$sql_save = "
					INSERT INTO \"car_expire_print\"(
						\"printDate\",
						\"IDNO\",
						\"CusID\",
						\"CusName\",
						\"CusAddress\",
						\"CarID\",
						\"C_REGIS\",
						\"C_REGIS_BY\",
						\"expireDate\"
					) VALUES(
						'$nowDateTime',
						'$IDNO',
						'$CusID',
						'$CusName',
						'$CusAddress',
						'$CarID',
						'$C_REGIS',
						'กรุงเทพมหานคร',
						'$expireDate'
					) RETURNING \"printID\"
				";
	if($result_save = pg_query($sql_save))
	{
		$printID = pg_fetch_result($result_save,0); // รหัสหนังสือเตือนรถหมดอายุและถอดป้าย
		
		// กลับประวัติ
		$sql_history = "
							INSERT INTO \"car_expire_print_history\"(
								\"printID\",
								\"doerID\",
								\"doerStamp\"
							) VALUES(
								'$printID',
								'$user_id',
								'$nowDateTime'
							)
						";
		if($result_history = pg_query($sql_history)){	
		}else{
			$status++;
		}
	}
	else
	{
		$status++;
		echo $sql_save;
	}
}

//$status++;
if($status == 0)
{
	pg_query("COMMIT");
	
	echo "<script>";
	echo "popU('takeoff_carplate_pdf.php?printID=$printID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700');";
	echo "window.opener.document.getElementById(\"s_data\").click();";
	echo "self.close();";
	echo "</script>";
}
else
{
	pg_query("ROLLBACK");
	
	echo "<center>";
	echo "<font color=\"red\">เกิดข้อผิดพลาด!! $error</font>";
	echo "<br/><input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
	echo "</center>";
}