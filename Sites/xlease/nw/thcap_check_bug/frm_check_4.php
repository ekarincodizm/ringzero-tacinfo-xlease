<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$qry_intrate = pg_query("select * from \"thcap_check_interestrate_of_payloan_data\"");
$rows_intrate = pg_num_rows($qry_intrate);
IF($rows_intrate == 0){
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
	<h2>ตรวจสอบอัตราดอกเบี้ยที่รับชำระในขณะนั้นๆของเงินกู้</h2>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>เลขที่สัญญา</th>
			<th>วันที่จ่าย</th>
			<th>อัตราดอกเบี้ยในตาราง</th>
			<th>อัตราดอกเบี้ยจาก function</th>
		</tr>
		<?php
					
				while($result_intrate = pg_fetch_array($qry_intrate)){
							
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
					$IDpopup = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".$result_intrate["contractID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><u>".$result_intrate["contractID"]."</u></a>";
					
					IF($result_intrate["int_rate_2012"] != ""){
						$int_rate_2012 = number_format($result_intrate["int_rate_2012"],2);
					}else{
						$int_rate_2012 = "";
					}
					IF($result_intrate["int_rate_func"] != ""){
						$int_rate_func = number_format($result_intrate["int_rate_func"],2);
					}else{
						$int_rate_func = "";
					}
					
					
					echo "<td>$i</td>";
					echo "<td>".$IDpopup."</td>";
					echo "<td>".$result_intrate["receivedate"]."</td>";
					echo "<td align=\"right\">".$int_rate_2012."</td>";
					echo "<td align=\"right\">".$int_rate_func."</td>";
					echo "<tr>";
					
					$i++;
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $rows_intrate รายการ</td></tr>"		
		?>
	</table>
</div>
</body>
</html>