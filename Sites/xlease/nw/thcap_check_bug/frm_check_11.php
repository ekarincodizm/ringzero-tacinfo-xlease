<?php
include("../../config/config.php");
$qry_contract = pg_query("select * from thcap_check_integrity_ncb_data order by \"contractID\" ");
$rows_contract = pg_num_rows($qry_contract);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

IF($rows_contract == 0){
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
	<h3>ตรวจสอบความถูกต้องของข้อมูล NCB</h3>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>เลขที่สัญญา</th>
			<th>เรื่องที่ผิด</th>
		</tr>
		<?php
			$i = 0;	
			$contract_old="";
			$color_old="";
			$color1="#EEE5DE";
			$color2="#FFF5EE";
			while($result_contract = pg_fetch_array($qry_contract))
			{
				$i++;
				$contractidpopup = "<a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".$result_contract["contractID"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><u>".$result_contract["contractID"]."</u></a>";
			
				if($i==1){
					$color=$color1;
				}else{
					if($contract_old==$contractidpopup){
						$color=$color_old;
					}else{
						if($color_old==$color1){
							$color=$color2;
						}else{
							$color=$color1;
						}	
					}	
				}
					
				echo "<tr bgcolor=$color onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '$color';\" align=center>";
			
				echo "<td align=\"center\">".$i."</td>";
				echo "<td align=\"center\">".$contractidpopup."</td>";
				echo "<td align=\"left\">".$result_contract["remark"]."</td>";
				echo "<tr>";
				$contract_old=$contractidpopup;
				$color_old=$color;
			}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"3\">รวม ".number_format($rows_contract,2)." รายการ</td></tr>";
		?>
	</table>
</div>
</body>
</html>