<?php 
include("../../config/config.php");

$s_date = pg_escape_string($_REQUEST['s_date']); // วันที่สนใจ
$s_contractType = pg_escape_string($_REQUEST['s_contractType']); // ประเภทสัญญา

// ไม่เอาเลขที่สัญญาดังนี้ :: อ้างอิงเลขงาน #6529
$whereConNo = "and \"contractID\" not in('FA-BK01-5600002/0024', 'FA-BK01-5600002/0026', 'FA-BK01-5600002/0028', 'FA-BK01-5600002/0029', 'FA-BK01-5600002/0030',
'FA-BK01-5600002/0031', 'FA-BK01-5600002/0032', 'FA-BK01-5600002/0033', 'FA-BK01-5600002/0034', 'FA-BK01-5600002/0035', 'FA-BK01-5600002/0036',
'FA-BK01-5600002/0037', 'FA-BK01-5600002/0038', 'FA-BK01-5600002/0039', 'FA-BK01-5600002/0040', 'FA-BK01-5600002/0041', 'FA-BK01-5600002/0042',
'FA-BK01-5600002/0043', 'FA-BK01-5600002/0044', 'FA-BK01-5600002/0045', 'FA-BK01-5600002/0046', 'FA-BK01-5600009/0007', 'FA-BK01-5600009/0008',
'FA-BK01-5600009/0009', 'FA-BK01-5600009/0010', 'FA-BK01-5600009/0011', 'FA-BK01-5600009/0012', 'FA-BK01-5600009/0013', 'FA-BK01-5600009/0014',
'FA-BK01-5600009/0015', 'FA-BK01-5600009/0016', 'FA-BK01-5600009/0017', 'FA-BK01-5600009/0018', 'FA-BK01-5600009/0019', 'FA-BK01-5600009/0020')";

// หาเลขที่สัญญา
$qry = "SELECT \"contractID\"
		FROM thcap_contract
		WHERE \"conType\" = '$s_contractType' AND \"conCredit\" IS NULL AND \"conStartDate\" <= '$s_date'
		AND \"contractID\" in(select distinct \"contractID\" from thcap_temp_voucher_tag)
		$whereConNo
		ORDER BY \"contractID\" ";

if($s_contractType == "FA")
{
	$accBookID = "211501"; // สมุดบัญชีที่เกี่ยวข้อง :: เจ้าหนี้ ซื้อสิทธิเรียกร้อง FA 100
}
elseif($s_contractType == "FI")
{
	$accBookID = "211503"; // สมุดบัญชีที่เกี่ยวข้อง :: เจ้าหนี้ ซื้อสิทธิเรียกร้อง FI
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานเจ้าหนี้สิทธิเรียกร้อง</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		function popU(U,N,T) {
			newWindow = window.open(U, N, T);
		}
	</script >
</head>

<body>
<form name="frm"  method="post" target="_blank">
	<div style="margin-top:10px;"align="center" width="100%" align="center">
		<table cellpadding="5" cellspacing="1" border="0" width="100%" bgcolor="#000000" align="center">
			<tr bgcolor="white">
				<td colspan="4" align="left"><font size="3" color="blue"><b>รายงานเจ้าหนี้สิทธิเรียกร้อง</b></font></td>					
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#BEBEBE" align="center">
				<td>รายการ</td>
				<td>เลขที่สัญญา</td>
				<td>ชื่อลูกค้า</td>	
				<td>ส่วนของเงินที่ไม่ได้ให้ล่วงหน้า (เงินประกัน)</td>
				
			</tr>
			<?php 
				$query_list = pg_query($qry);
				$num_row = pg_num_rows($query_list);
				if($num_row>0)
				{
					// รายการบัญชีที่จะไม่ให้นำมาคำนวณ :: อ้างอิงเลขงาน #7348
					$where_abh_no = "and a.\"abh_id\" not in('AR14010600004', 'AR14010700004', 'AR14010900002', 'AR14012200003', 'AR14012700002',
															'AR14013000002', 'AR14020800019', 'AR14020800020', 'AR14022000008','AJ14033100062')";
															
					$i = 0;
					while($res_v = pg_fetch_array($query_list))
					{
						$contractID = $res_v['contractID'];
						
						// หา วันที่เริ่มบันทึกบัญชี และยอด เครดิต ทั้งหมด
						$qry_cr = "select min(a.\"abh_stamp\")::date, sum(b.\"abd_amount\")
									from account.\"all_accBookHead\" a
									left join account.\"all_accBookDetail\" b on a.\"abh_autoid\" = b.\"abd_autoidabh\"
									where a.\"abh_status\" <> '0' and a.\"abh_correcting_entries_abh_autoid\" is null and a.\"abh_is_correcting_entries\" <> '1'
									and a.\"abh_refid\" in (select \"voucherID\" from thcap_temp_voucher_tag where \"contractID\" = '$contractID')
									and b.\"abd_accBookID\" = '$accBookID' and b.\"abd_bookType\" = '2' $where_abh_no";
						$qry_cr_list = pg_query($qry_cr);
						$minDate = pg_result($qry_cr_list,0); // วันที่เริ่มบันทึกบัญชี
						$amountCr = pg_result($qry_cr_list,1); // ยอด เครดิต ทั้งหมด
						
						// หา วันที่สิ้นสุดบันทึกบัญชี และยอด เดบิต ทั้งหมด
						$qry_dr = "select max(a.\"abh_stamp\")::date, sum(b.\"abd_amount\")
									from account.\"all_accBookHead\" a
									left join account.\"all_accBookDetail\" b on a.\"abh_autoid\" = b.\"abd_autoidabh\"
									where a.\"abh_status\" <> '0' and a.\"abh_correcting_entries_abh_autoid\" is null and a.\"abh_is_correcting_entries\" <> '1'
									and a.\"abh_refid\" in (select \"voucherID\" from thcap_temp_voucher_tag where \"contractID\" = '$contractID')
									and b.\"abd_accBookID\" = '$accBookID' and b.\"abd_bookType\" = '1' $where_abh_no";
						$qry_dr_list = pg_query($qry_dr);
						$maxDate = pg_result($qry_dr_list,0); // วันที่สิ้นสุดบันทึกบัญชี
						$amountDr = pg_result($qry_dr_list,1); // ยอด เดบิต ทั้งหมด
						
						// ถ้ามีการบันทึกบัญชี Dr และ Cr เท่ากัน และวันที่สิ้นสุดการบัญทึกบัญชีผ่านไปแล้ว ไม่ต้องแสดงรายการนั้นๆแล้ว
						if($minDate != "" && $maxDate != "" && $amountCr == $amountDr && $maxDate <= $s_date)
						{
							continue;
						}
						
						if($amountCr != "")
						{
							$guaranteeamt = number_format($amountCr,2);
							
							$amountCrSum += $amountCr;
							$guaranteeamtSum = number_format($amountCrSum,2);
						}
						else
						{ // ถ้าไม่มียอด (ไม่มีบัญชีที่เกี่ยวข้อง)
							continue;
						}

						$i++;
						
						// หาชื่อผู้กู้หลัก
						$qry_cusMain = pg_query("select \"FullName\" from \"thcap_ContactCus\" where \"contractID\" = '$contractID' and \"CusState\" = '0'");
						$cusMain = pg_result($qry_cusMain,0);
						
						if($i%2==0){
							echo "<tr class=\"odd\" >";						
						}else{
							echo "<tr class=\"even\" >";
						}
						
						echo "<td align=\"center\">$i</td>";
						echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";						
						echo "<td align=\"left\">$cusMain</td>";
						echo "<td align=\"right\">$guaranteeamt</td>";
						echo "</tr>";
					}
					
					echo "<tr style=\"background-color:#FFAAAA;\" >";
					echo "<td align=\"right\" colspan=\"3\"><b>รวม</b></td>";
					echo "<td align=\"right\"><b>$guaranteeamtSum</b></td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr style=\"background-color:#FFAAAA;\"><td colspan=\"4\" align=\"center\">ไม่พบข้อมูลที่ค้นหา</td></tr>";
				}
			?>
		</table>
	</div>
</form>
</body>
</html>