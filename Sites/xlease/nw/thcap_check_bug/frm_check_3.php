<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

$qry_mytrans = pg_query("select * from \"thcap_check_myTransferMoney_with_useTransferMoney_data\"");
$rows_mytrans = pg_num_rows($qry_mytrans);
IF($rows_mytrans == 0){
	echo "<center><h2> ไม่พบข้อมูลที่ผิดปกติ </h2>";
	echo "<input type=\"button\" value=\" ปิด \"  onclick=\"window.close();\" style=\"width:70px;height:50px;\"></center>";
	exit();
}
$i = 1;	
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
	<h2>ตรวจสอบเงินโอนที่ถูกใช้ไปกับการนำเงินโอนไปใช้ผิด</h2>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>รหัสเงินโอน</th>
			<th>เงินโอนที่ถูกใช้ไป</th>
			<th>การนำเงินโอนไปใช้</th>
		</tr>
		<?php
					
				while($result_mytrans = pg_fetch_array($qry_mytrans)){
							
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
					$IDpopup = "<a onclick=\"javascript:popU('../thcap/popup_trans_receipt.php?revTranID=".$result_mytrans["revTranID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=550')\" style=\"cursor:pointer;\" ><u>".$result_mytrans["revTranID"]."</u></a>";
					
					IF($result_mytrans["used_amt"] != ""){
						$used_amt = number_format($result_mytrans["used_amt"],2);
					}else{
						$used_amt = "";
					}
					IF($result_mytrans["receipt_amt"] != ""){
						$receipt_amt = number_format($result_mytrans["receipt_amt"],2);
					}else{
						$receipt_amt = "";
					}
					
					
					echo "<td>$i</td>";
					echo "<td>".$IDpopup."</td>";
					echo "<td align=\"right\">".$used_amt."</td>";
					echo "<td align=\"right\">".$receipt_amt."</td>";
					echo "<tr>";
					
					$i++;
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"4\">รวม $rows_mytrans รายการ</td></tr>"		
		?>
	</table>
</div>
</body>
</html>