<?php
include("../../config/config.php");
$qry_transpay = pg_query("select * from thcap_check_duplicate_use_transfermoney_data");
$rows_transpay = pg_num_rows($qry_transpay);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
IF($rows_transpay == 0){
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
	<h2>รายการใช้เงินซ้ำในระบบเงินโอน</h2>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รหัสเงินโอน</th>
			<th>ยอดเงินโอน</th>
			<th>ยอดใช้เงิน</th>
			<th>ยอดคงเหลือ</th>
			<th>ยอดคงเหลือจากการคำนวณ</th>
		</tr>
		<?php
			$i = 0;			
				while($result_transpay = pg_fetch_array($qry_transpay)){
					
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}	
					$IDpopup = "<a onclick=\"javascript:popU('../thcap/popup_trans_receipt.php?revTranID=".$result_transpay["revTranID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=550')\" style=\"cursor:pointer;\" ><u>".$result_transpay["revTranID"]."</u></a>";
					
					IF($result_transpay["balanceAmt"] != ""){
						$balanceAmt = number_format($result_transpay["balanceAmt"],2);
					}else{
						$balanceAmt = "";
					}
					
					IF($result_transpay["usedAmt"] != ""){
						$usedAmt = number_format($result_transpay["usedAmt"],2);
					}else{
						$usedAmt = "";
					}
					
					IF($result_transpay["bankRevAmt"] != ""){
						$bankRevAmt = number_format($result_transpay["bankRevAmt"],2);
					}else{
						$bankRevAmt = "";
					}
					
					IF($result_transpay["cal_balanceAmt"] != ""){
						$cal_balanceAmt = number_format($result_transpay["cal_balanceAmt"],2);
					}else{
						$cal_balanceAmt = "";
					}
					
					echo "<td>".$IDpopup."</td>";
					echo "<td align=\"right\">".$bankRevAmt."</td>";
					echo "<td align=\"right\">".$usedAmt."</td>";
					echo "<td align=\"right\">".$balanceAmt."</td>";
					echo "<td align=\"right\">".$cal_balanceAmt."</td>";
					echo "<tr>";
					
					$i++;
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $rows_transpay รายการ</td></tr>"		
		?>
	</table>
</div>
</body>
</html>