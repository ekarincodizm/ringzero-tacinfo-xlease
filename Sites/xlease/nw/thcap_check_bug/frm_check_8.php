<?php
include("../../config/config.php");
$qry_data = pg_query("select * from thcap_check_billpayment_with_transferpayment_date order by \"billChkDate\" ");
$rows_data = pg_num_rows($qry_data);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

IF($rows_data == 0){
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
	<h3>ตรวจสอบรายการ Bill Payment และความสอดคล้องกับข้อมูลการโอนเงิน</h3>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>วันที่รายการมีปัญหา</th>
			<th>ช่องทางการตรวจสอบ</th>
			<th>ตัวคุมเลขอ้างอิง</th>
			<th>เลขอ้างอิง</th>
			<th>รายละเอียดข้อผิดพลาด</th>
		</tr>
		<?php
			$i = 0;			
				while($result_data = pg_fetch_array($qry_data))
				{
					$i++;
					
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
				
					echo "<td align=\"center\">".$i."</td>";
					echo "<td align=\"center\">".$result_data["billChkDate"]."</td>";
					echo "<td align=\"center\">".$result_data["billChkChannel"]."</td>";
					echo "<td align=\"center\">".$result_data["billChkRefHead"]."</td>";
					echo "<td align=\"center\">".$result_data["billChkRef"]."</td>";
					echo "<td align=\"left\">".$result_data["billChkErrDetails"]."</td>";
					echo "<tr>";
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"6\">รวม $rows_data รายการ</td></tr>";
		?>
	</table>
</div>
</body>
</html>