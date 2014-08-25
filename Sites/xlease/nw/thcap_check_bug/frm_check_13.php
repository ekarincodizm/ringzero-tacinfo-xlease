<?php
include("../../config/config.php");

// ต่อ base ที่ตรวจสอบ
$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=postgres user=postgres password=". $_SESSION["session_company_dbpass"] ."";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");

$qry_data = pg_query("select * from check_process_job_data order by \"jstjobid\", \"jslstart\" ");
$numrow = pg_num_rows($qry_data);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

IF($numrow == 0){
	echo "<center><h2> ไม่พบข้อมูลที่ผิดปกติ </h2>";
	echo "<input type=\"button\" value=\" ปิด \"  onclick=\"window.close();\" style=\"width:70px;height:50px;\"></center>";
	exit();
}

?>

<title>(THCAP) ตรวจสอบรายการผิดปกติในระบบ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center" >
	<div style="padding-top:10px;" align="right"><input type="button" value=" พิมพ์ " onclick="window.print();" style="width:70px;height:50px;"><input type="button" value=" ปิด " onclick="window.close();" style="width:70px;height:50px;"></div>
	<h3>ตรวจสอบการ run process auto ในระบบ</h3>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>เลขงาน</th>
			<th>ชื่อ function</th>
			<th>วันที่เกิดข้อผิดพลาด</th>
			<th>ข้อผิดพลาด</th>
		</tr>
		<?php
			$i = 0;			
				while($result = pg_fetch_array($qry_data))
				{
					$i++;
					
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
				
					echo "<td align=\"center\">".$i."</td>";
					echo "<td align=\"center\">".$result["jstjobid"]."</td>";
					echo "<td align=\"left\">".$result["jstname"]."</td>";
					echo "<td align=\"center\">".$result["jslstart"]."</td>";
					echo "<td align=\"left\">".$result["jsloutput"]."</td>";
					echo "<tr>";
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $numrow รายการ</td></tr>";
		?>
	</table>
</div>
<?php
// กลับมาต่อ base หลักเหมือนเดิม
$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");
?>
</body>
</html>