<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$qry_intrate = pg_query("SELECT \"contractID\" FROM \"thcap_check_hp_vat_gen_correction_data\" GROUP BY \"contractID\" ORDER BY \"contractID\"");
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
	<h2>ตรวจสอบการสร้างใบกำกับภาษีในกำหนดชำระที่ผ่านมาแล้วว่าออกครบหรือไม่</h2>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการ</th>
			<th>เลขที่สัญญา</th>
			<th width="70%">รายละเอียด</th>
		</tr>
		<?php
			while($result_intrate = pg_fetch_array($qry_intrate)){
				$contractID_filed = $result_intrate["contractID"];
				$qry_intrate_data = pg_query("select \"Description\",\"DueNum\" from \"thcap_check_hp_vat_gen_correction_data\" WHERE \"contractID\" = '$contractID_filed' order by \"DueNum\" DESC");	
				while($result_intrate_data = pg_fetch_array($qry_intrate_data)){				
					$textdetail = "งวดที่ ".$result_intrate_data["DueNum"]." :: ".$result_intrate_data["Description"]."\n".$textdetail;
				}			
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
					$IDpopup = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".$contractID_filed."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><u>".$contractID_filed."</u></a>";
				
					echo "<td>$i</td>";
					echo "<td>".$IDpopup."</td>";
					echo "<td ><textarea cols=\"120\" rows=\"3\" readonly>".$textdetail."</textarea></td>";
					echo "<tr>";
					
					$i++;
					unset($textdetail);
				
			}	
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"5\">รวม $rows_intrate รายการ</td></tr>"		
		?>
	</table>
</div>
</body>
</html>