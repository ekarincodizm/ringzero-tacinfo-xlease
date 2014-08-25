<?php
Ob_start();
session_start();
include("../config/config.php"); 
$idno = pg_escape_string($_GET['idno']);
$carid = pg_escape_string($_GET['carid']);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ลูกค้าเข้าร่วมทั้งหมด</title>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
	
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

<head>
<body>
<div>
	<fieldset><legend><B>ลูกค้าเข้าร่วมทั้งหมด</B></legend>
		<table>
				<tr align="center">
					<td><b>เลขที่สัญญา</b></td>
					<td><b>รหัสลูกค้า</b></td>
					<td><b>ชื่อ-นามสกุล</b></td>
					<td><b>วันที่เริ่มเก็บค่าเข้าร่วม</b></td>
				</tr>
			<?php
				$qry = pg_query("select \"cusid\",\"idno\",\"start_pay_date\" from \"ta_join_main\" where carid = '$carid' order by start_pay_date ");
				$n=0;
				while($res=pg_fetch_array($qry)){
					$n++;
					$cusId = trim($res['cusid']);
					$idno = $res['idno'];
					$startpay = $res['start_pay_date'];
					
					$qryname = pg_query(" select full_name from \"VSearchCusCorp\" where \"CusID\" = '$cusId' ");
					$cusname = pg_fetch_result($qryname,0);
					 
					echo "<tr align=center>";
						echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('frm_viewcuspayment.php?show=1&idno=$idno','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$idno</u></font></td>";
						echo "<td><u><font color=\"#FF1493\"><a style=\"cursor:pointer;\" onclick=\"javascript:popU('../nw/search_cusco/index.php?cusid=$cusId','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=680')\">$cusId</a></font></u></td>";
						echo "<td>$cusname</td>";
						echo "<td>$startpay </td>";
						echo "<td><u><font color=\"#1E90FF\"><a style=\"cursor:pointer;\" onclick=\"javascript:popU('../nw/join_payment/extensions/ta_join_payment/pages/ta_join_payment_view_new.php?idno=$idno&cusid=$cusId&pmenu=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=680')\">ข้อมูลค่าเข้าร่วม</a><font></u></td>";
					echo "</tr>";
				}
			?>
		</table>
	</fieldset>
</div>
<body>
</html>