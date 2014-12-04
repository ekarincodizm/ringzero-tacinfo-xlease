<?php // Start Program
set_time_limit(0);
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$printID = pg_escape_string($_POST['printID']);
$doerNote = pg_escape_string($_POST['doerNote']);
?>

<script>
	function popU(U,N,T){
		newWindow = window.open(U, N, T);
	}
</script>
	
<?php
pg_query("BEGIN");
$status = 0;

$sql_history = "
					INSERT INTO \"car_expire_print_history\"(
						\"printID\",
						\"doerID\",
						\"doerStamp\",
						\"doerNote\"
					) VALUES(
						'$printID',
						'$user_id',
						'$nowDateTime',
						'$doerNote'
					)
				";
if($result_history = pg_query($sql_history)){	
}else{
	$status++;
	echo $sql_history;
}

if($status == 0)
{
	pg_query("COMMIT");
	
	echo "<script>";
	echo "popU('takeoff_carplate_pdf.php?printID=$printID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700');";
	echo "self.close();";
	echo "</script>";
}
else
{
	pg_query("ROLLBACK");
	
	echo "<center>";
	echo "<font color=\"red\">เกิดข้อผิดพลาด!!</font>";
	echo "<br/><input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
	echo "</center>";
}