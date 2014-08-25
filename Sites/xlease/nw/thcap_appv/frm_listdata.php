<?php
include("../../config/config.php");
include("../function/emplevel.php");

$user_id = $_SESSION['av_iduser'];
$em_level = emplevel($user_id);
$voucherID = pg_escape_string($_GET["voucherID"]);
$cur_path_ins = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดของเลขที่สัญญา</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>
	<div align="center"><h2>รายละเอียดของเลขที่สัญญา</h2></div>
	<center>	
		<table width="80%">
			<tr>
				<td>
				<table border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#777777" width="100%">
						<tr bgcolor="#79BCFF">
							<th align="center">รายการ</th>
							<th align="center">เลขที่สัญญา</th>	
							<?php if($em_level<=3){ 
								$colspan="3";
							?>
							<th align="center">ยกเลิก</th>	
							<?php }else{ $colspan="2";} ?>
													
						</tr>
						<?php
							$i=0;
							$qry_contract=pg_query("select \"contractID\" from \"thcap_temp_voucher_tag\" where \"voucherID\" ='$voucherID'  order by \"contractID\" ");
							
							while($res_contract=pg_fetch_array($qry_contract))
							{   $i++;
								$v_contract=$res_contract["contractID"];							
							   
								echo "<tr bgcolor=\"#FFFFFF\">";
								echo "<td align=\"center\">$i</td>";
								echo "<td align=\"center\"><a onclick=\"javascript:popU('$cur_path_ins/frm_Index.php?idno=$v_contract','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$v_contract</u></font></a>";									
								echo "</td>";
								if($em_level<=3){
									
									$nameform="form".$i;//ชื่อ form เพื่อไม่ให้ ชื่อ ซ้ำกัน
									echo "<form name=\"$nameform\" method=\"post\" action=\"process_cancel.php\">";
									echo "<input type=\"hidden\" name=\"idno\" id=\"idno\" value=\"$v_contract\">";
									echo "<input type=\"hidden\" name=\"voucherID\" id=\"voucherID\" value=\"$voucherID\">";
									echo "<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"if(confirm('ยืนยันการยกเลิกรายการ') == true)
									{ document.forms['$nameform'].submit();return false;}\" src=\"../thcap/images/del.png\" width=\"20px;\" height=\"20px;\"></td>";
									echo "</form>";
								}
								echo "</tr>";
							}
						?>
						<tr bgcolor="#79BCFF">							
							<td colspan="<?php echo $colspan;?>" align="right"><b> รวม <?php echo $i ?> รายการ</b></td>
							
						</tr>							
					</table>
				</td>
			</tr>
		</table>		
		<br><br>		
		<table width="80%">
			<tr>
				<td align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
			</tr>
		</table>
	</center>
</body>
</html>