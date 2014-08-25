<?php
include("../../config/config.php");
$qry_contract = pg_query("select \"BAccount\"||'-'||\"BName\" as namebank,\"receiveDate\",\"remark\" from thcap_check_statement_bank_data a
left join \"BankInt\" b on a.\"BankInt\"=b.\"BID\"
order by \"auto_id\"");
$rows_contract = pg_num_rows($qry_contract);

$rc_path = redirect($_SERVER['PHP_SELF'],'');

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
	<div style="padding-top:10px;float:left;">
		<input type="button" value="(THCAP) รายงานเงินโอน" onclick="javascript:popU('<?php echo $rc_path;?>nw/thcap/frm_report_trans_index.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=760')" style="cursor:pointer">
		<input type="button" value="(THCAP) รายงานเช็ค" onclick="javascript:popU('<?php echo $rc_path;?>nw/thcap/thcap_cheque_report/index.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=760')" style="cursor:pointer">
		<input type="button" value="(THCAP) LOAD STATEMENT BANK " onclick="javascript:popU('<?php echo $rc_path;?>nw/thcap_statement/frm_Index.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=760')" style="cursor:pointer">
	</div>
	<div style="padding-top:10px;float:right;"><input type="button" value=" พิมพ์ " onclick="window.print();" style="width:70px;height:50px;"><input type="button" value=" ปิด " onclick="window.close();" style="width:70px;height:50px;"></div>
	<div style="clear:both;"></div>
	<h3>ตรวจสอบความถูกต้องของรายการเดินบัญชีธนาคาร</h3>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>BankInt ที่มีปัญหา</th>
			<th>วันที่ที่มีปัญหา</th>
			<th>รายละเอียดที่มีปัญหา</th>
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
				
				if($i%2==0){
					$color="#EEE5DE";
				}else{
					$color="#FFF5EE";
				}
				echo "<tr bgcolor=$color onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '$color';\" align=center>";
			
				echo "<td align=\"center\">".$result_contract["namebank"]."</td>";
				echo "<td align=\"center\">".$result_contract["receiveDate"]."</td>";
				echo "<td align=\"left\">".$result_contract["remark"]."</td>";
				echo "<tr>";
				$contract_old=$contractidpopup;
				$color_old=$color;
			}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"3\">รวม ".$rows_contract." รายการ</td></tr>";
		?>
	</table>
</div>
</body>
</html>