<?php
include("../../config/config.php");
$qry_receipt = pg_query("select * from thcap_check_guaranteed_money_date order by \"contractID\", \"Description\" ");
$rows_receipt = pg_num_rows($qry_receipt);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

IF($rows_receipt == 0){
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
	<h3>ตรวจสอบเรื่องเงินค้ำประกันว่าเป็นวันที่ที่ถูกต้องหรือไม่</h3>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>เลขที่สัญญา</th>
			<th>เรื่องที่ผิด</th>
			<th>เลขที่ใบเสร็จ</th>
		</tr>
		<?php
			$i = 0;			
				while($result_receipt = pg_fetch_array($qry_receipt))
				{
					$i++;
					
					$IDpopup = "<a onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=".$result_receipt["receiptID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=550')\" style=\"cursor:pointer;\" ><u>".$result_receipt["receiptID"]."</u></a>";
					$contractidpopup = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".$result_receipt["contractID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><u>".$result_receipt["contractID"]."</u></a>";

					
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
				
					echo "<td align=\"center\">".$i."</td>";
					echo "<td align=\"center\">".$contractidpopup."</td>";
					echo "<td align=\"left\">".$result_receipt["Description"]."</td>";
					echo "<td align=\"center\">".$IDpopup."</td>";
					echo "<tr>";
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"4\">รวม $rows_receipt รายการ</td></tr>";
		?>
	</table>
</div>
</body>
</html>