<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["idno"]); // เลขที่สัญญา
$tm = pg_escape_string($_GET["tm"]); // ไตรมาส
$yy = pg_escape_string($_GET["yy"]); // ปี

// กำหนดเดือนวัน ในรูปแบบ mm-dd
if($tm == "1")
{
	$md = "03-31"; // ไตรมาส 1
	
	$b_md = "12-31"; // ไตรมาสก่อนหน้า ไตรมาส 4
	$b_yy = $yy - 1; // ปีก่อนหน้านี้ 1 ไตรมาส
}
elseif($tm == "2")
{
	$md = "06-30"; // ไตรมาส 2
	
	$b_md = "03-31"; // ไตรมาสก่อนหน้า ไตรมาส 1
	$b_yy = $yy; // ปีก่อนหน้านี้ 1 ไตรมาส
}
elseif($tm == "3")
{
	$md = "09-30"; // ไตรมาส 3
	
	$b_md = "06-30"; // ไตรมาสก่อนหน้า ไตรมาส 2
	$b_yy = $yy; // ปีก่อนหน้านี้ 1 ไตรมาส
}
elseif($tm == "4")
{
	$md = "12-31"; // ไตรมาส 4
	
	$b_md = "09-30"; // ไตรมาสก่อนหน้า ไตรมาส 3
	$b_yy = $yy; // ปีก่อนหน้านี้ 1 ไตรมาส
}

// กำหนด วันที่ ในรูปแบบ yyyy-mm-dd
$focusDate = "$yy-$md"; // วันสิ้นไตรมาสที่เลือก
$b_focusDate = "$b_yy-$b_md"; // วันสิ้นไตรมาส ก่อนหน้านี้ 1 ไตรมาส

// หาวันที่สิ้นปีก่อนหน้านี้
$b_y_yy = $yy-1; // ปีก่อนหน้านี้
$b_y_focusDate = "$b_y_yy-12-31"; // วันที่สิ้นปีก่อนหน้านี้

// ข้อความที่จะแสดงในหัวตาราง
$textS = "ไตรมาส $tm ปี $yy เลขที่สัญญา <font color=\"#0000FF\" style=\"cursor:pointer;\" onClick=\"popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=700')\"><u>$contractID</u></font>";

// หารายการเริ่มต้น
$qry_f_date = pg_query("select \"intacc_serial\" from \"thcap_temp_int_acc\" where \"contractID\" = '$contractID' and \"intacc_ofdateclose\" = '$b_y_focusDate' ");
$f_intacc_serial = pg_fetch_result($qry_f_date,0);
if($f_intacc_serial == "") // ถ้าไม่มีรายการตั้งแต่สิ้นปีที่แล้ว (ไตรมาส 4 ของปีที่แล้ว)
{
	$whereOther = "AND \"intacc_date\" > '$b_y_focusDate' ";
}
else // ถ้ามีรายการตั้งแต่สิ้นปีที่แล้ว (ไตรมาส 4 ของปีที่แล้ว)
{
	$whereOther = "AND \"intacc_serial\" > '$f_intacc_serial'";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการรับรู้รายได้ (ทางบัญชี)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		function popU(U,N,T)
		{
			newWindow = window.open(U, N, T);
		}
	</script>
</head>
<body>
	<fieldset><legend><B>รายละเอียด การรับรู้รายได้ (ทางบัญชี)</B></legend>
		<center>
			<table width="100%" bgcolor="#AAAAAA">
				<tr bgcolor="#FFFFFF">
					<td colspan="9"><b><?php echo "$textS"; ?></b></td>
				</tr>
				<tr bgcolor="#79BCFF">
					<th>ลำดับ</th>
					<th>วันที่ทางบัญชี</th>
					<th>คำอธิบาย</th>
					<th>ดอกเบี้ย</th>
					<th>ดอกเบี้ยที่รับรู้รายได้แล้ว<br/>(ตั้งแต่ต้นปี)</th>
					<th>ส่วนลดดอกเบี้ย</th>
					<th>ดอกเบี้ยคงเหลือในระบบ</th>
					<th>ดอกเบี้ยคงเหลือ<br/>(ส่วนที่ถือเป็นรายได้แล้ว)</th>
					<th>ดอกเบี้ยคงเหลือ<br/>(ส่วนที่ยังไม่ได้เป็นรายได้)</th>
				</tr>
				<?php
				$qry_contract = pg_query("SELECT *
										FROM \"thcap_temp_int_acc\"
										WHERE \"contractID\" = '$contractID'
											AND \"intacc_serial\" <= (select \"intacc_serial\" from \"thcap_temp_int_acc\" where \"contractID\" = '$contractID' and \"intacc_ofdateclose\" = '$focusDate')
											$whereOther
										ORDER BY \"intacc_serial\" ");
				$i = 0;
				$sum_gen_amount = 0;
				$sum_realize_amount = 0;
				$sum_discount_amount = 0;
				while($res_contract = pg_fetch_array($qry_contract))
				{
					$i++;
					
					$contractID = $res_contract["contractID"]; // เลขที่สัญญา
					$intacc_date = $res_contract["intacc_date"]; // วันที่ทางบัญชี...
					$gen_amount = $res_contract["gen_amount"]; // จำนวนเงินที่ gen ดอกเบี้ยได้ หรือถูกลดดอกเบี้ย กรณีใดๆ
					$realize_amount = $res_contract["realize_amount"]; // จำนวนเงินที่นำไปรับรู้รายได้ในรอบนี้
					$discount_amount = $res_contract["discount_amount"]; // ส่วนลด
					$left_gen_amount = $res_contract["left_gen_amount"]; // จำนวนเงินที่คงเหลือหลังรายการ
					$left_realize_amount = $res_contract["left_realize_amount"]; // ดอกเบี้ยที่ถือเป็นรายได้
					$left_unrealize_amount = $res_contract["left_unrealize_amount"]; // ดอกเบี้ยที่ไม่ถือเป็นรายได้
					$intacc_ofdateclose = $res_contract["intacc_ofdateclose"]; // รายการนี้เป็นรายการที่ปิดบัญชีในระบบของ ณ วันที่ ตามข้อมูล...
					$intacc_type = $res_contract["intacc_type"]; // ประเภทรายการ
					
					if($intacc_type == "0"){$intacc_type_text = "ยอดยกมา / ยอดปิดสิ้นเดือน";}
					elseif($intacc_type == "1"){$intacc_type_text = "gen ดอกเบี้ย";}
					elseif($intacc_type == "2"){$intacc_type_text = "ตั้งดอกเบี้ยค้างรับ";}
					elseif($intacc_type == "3"){$intacc_type_text = "ส่วนลดดอกเบี้ย";}
					elseif($intacc_type == "4"){$intacc_type_text = "ลูกค้าชำระดอกเบี้ย";}
					else{$intacc_type_text = "";}
					
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
					
					echo "<td align=\"center\">".number_format($i,0)."</td>";
					echo "<td align=\"center\">$intacc_date</td>";
					echo "<td align=\"left\">$intacc_type_text</td>";
					echo "<td align=\"right\">".number_format($gen_amount,2)."</td>";
					echo "<td align=\"right\">".number_format($realize_amount,2)."</td>";
					echo "<td align=\"right\">".number_format($discount_amount,2)."</td>";
					echo "<td align=\"right\">".number_format($left_gen_amount,2)."</td>";
					echo "<td align=\"right\">".number_format($left_realize_amount,2)."</td>";
					echo "<td align=\"right\">".number_format($left_unrealize_amount,2)."</td>";
					echo "</tr>";
					
					$sum_gen_amount += $gen_amount;
					$sum_realize_amount += $realize_amount;
					$sum_discount_amount += $discount_amount;
				}
				
				if($i > 0)
				{
					echo "<tr bgcolor=\"#FFCCCC\">";
					echo "<td colspan=\"3\" align=\"right\"><b>รวม</b></td>";
					echo "<td align=\"right\"><b>".number_format($sum_gen_amount,2)."</b></td>";
					echo "<td align=\"right\"><b>".number_format($sum_realize_amount,2)."</b></td>";
					echo "<td align=\"right\"><b>".number_format($sum_discount_amount,2)."</b></td>";
					echo "<td colspan=\"3\"></td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr bgcolor=\"#FFCCCC\">";
					echo "<td colspan=\"9\" align=\"center\"><b>-- ไม่พบข้อมูล --</b></td>";
					echo "</tr>";
				}
				?>
			</table>
		</center>
	</fieldset>
</body>
</html>