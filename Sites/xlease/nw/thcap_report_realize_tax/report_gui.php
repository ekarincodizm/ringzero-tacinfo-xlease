<?php
include("../../config/config.php");
?>

<script>
	function popU(U,N,T)
	{
		newWindow = window.open(U, N, T);
	}
</script>

<?php
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

$textS = "ไตรมาส $tm ปี $yy";

// หาวันที่สิ้นปีก่อนหน้านี้
$b_y_yy = $yy-1; // ปีก่อนหน้านี้
$b_y_focusDate = "$b_y_yy-12-31"; // วันที่สิ้นปีก่อนหน้านี้
?>

<br/>
<fieldset><legend><B>ผลการค้นหา การรับรู้รายได้ (ทางภาษี)</B></legend>
	<font style="background-color:#CCCCCC;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font> แถบสีเทา คือสัญญาที่ปิดบัญชีไปแล้วไม่เกินไตรมาสที่เลือก (ปิดบัญชีไปแล้ว แต่ไม่เกินวันที่ <?php echo $focusDate; ?>)
	<br/><br/>
	<center>
		<table width="100%" bgcolor="#AAAAAA">
			<tr bgcolor="#FFFFFF">
				<td colspan="10"><b><?php echo "$textS"; ?></b></td>
			</tr>
			<tr bgcolor="#79BCFF">
				<th>รายการ</th>
				<th>เลขที่สัญญา</th>
				<th>ดอกเบี้ยคงเหลือในระบบ</th>
				<th>ดอกเบี้ยคงเหลือ<br/>(ส่วนที่ถือเป็นรายได้แล้ว)</th>
				<th>ดอกเบี้ยคงเหลือ<br/>(ส่วนที่ยังไม่ได้เป็นรายได้)</th>
				<th>ดอกเบี้ยที่รับรู้รายได้แล้ว<br/>(ตั้งแต่ต้นปี)</th>
				<th>ดอกเบี้ยรับ<br/>(ตั้งแต่ต้นปี)</th>
				<th>ส่วนลดดอกเบี้ย<br/>(ตั้งแต่ต้นปี)</th>
				<th>วันที่สนใจ</th>
				<th>รายละเอียด</th>
			</tr>
			<?php
			$qry_contract = pg_query("SELECT *, \"thcap_checkcontractcloseddate\"(\"contractID\") as \"conCloseDate\"
									FROM \"thcap_temp_int_tax\"
									WHERE \"inttax_ofdateclose\" = '$focusDate'
										AND (\"thcap_checkcontractcloseddate\"(\"contractID\") IS NULL OR \"thcap_checkcontractcloseddate\"(\"contractID\") > '$b_y_focusDate')
									ORDER BY \"contractID\" ");
			$i = 0;
			$sum_left_gen_amount = 0; // ผลรวม ดอกเบี้ยคงเหลือในระบบ
			$sum_left_realize_amount = 0; // ผลรวม ดอกเบี้ยคงเหลือ (ส่วนที่ถือเป็นรายได้แล้ว)
			$sum_left_unrealize_amount = 0; // ผลรวม ดอกเบี้ยคงเหลือ (ส่วนที่ยังไม่ได้เป็นรายได้)
			$sum_all_realize_amount = 0; // ผลรวม ดอกเบี้ยที่รับรู้รายได้แล้ว (ตั้งแต่ต้นปี)
			$sum_all_gen_amount_negative = 0; // ผลรวม ดอกเบี้ยที่รับรู้รายได้แล้ว (ตั้งแต่ต้นปี)
			$sum_all_discount_amount = 0; // ผลรวม ส่วนลดดอกเบี้ย (ตั้งแต่ต้นปี)
			while($res_contract = pg_fetch_array($qry_contract))
			{
				$i++;
				
				$contractID = $res_contract["contractID"]; // เลขที่สัญญา
				$left_gen_amount = $res_contract["left_gen_amount"]; // จำนวนเงินที่คงเหลือหลังรายการ
				$left_realize_amount = $res_contract["left_realize_amount"]; // ดอกเบี้ยที่ถือเป็นรายได้
				$left_unrealize_amount = $res_contract["left_unrealize_amount"]; // ดอกเบี้ยที่ไม่ถือเป็นรายได้
				$inttax_ofdateclose = $res_contract["inttax_ofdateclose"]; // รายการนี้เป็นรายการที่ปิดบัญชีในระบบของ ณ วันที่ ตามข้อมูล...
				$conCloseDate = $res_contract["conCloseDate"]; // วันที่ปิดบัญชี
				
				//---------- คำนวณ จำนวนเงินอื่นๆ ----------//
					// หารายการเริ่มต้น
					$qry_f_date = pg_query("select \"inttax_serial\" from \"thcap_temp_int_tax\" where \"contractID\" = '$contractID' and \"inttax_ofdateclose\" = '$b_y_focusDate' ");
					$f_inttax_serial = pg_fetch_result($qry_f_date,0);
					if($f_inttax_serial == "") // ถ้าไม่มีรายการตั้งแต่สิ้นปีที่แล้ว (ไตรมาส 4 ของปีที่แล้ว)
					{
						$whereOther = "AND \"inttax_date\" > '$b_y_focusDate' ";
					}
					else // ถ้ามีรายการตั้งแต่สิ้นปีที่แล้ว (ไตรมาส 4 ของปีที่แล้ว)
					{
						$whereOther = "AND \"inttax_serial\" > '$f_inttax_serial'";
					}
					
					// หา ดอกเบี้ยที่รับรู้รายได้แล้ว (ตั้งแต่ต้นปี) และ ส่วนลดดอกเบี้ย (ตั้งแต่ต้นปี)
					$qry_sum_contract_realize = pg_query("SELECT sum(\"realize_amount\") as \"sum_contract_realize_amount\", sum(\"discount_amount\") as \"sum_contract_discount_amount\"
											FROM \"thcap_temp_int_tax\"
											WHERE \"contractID\" = '$contractID'
												AND \"inttax_serial\" <= (select \"inttax_serial\" from \"thcap_temp_int_tax\" where \"contractID\" = '$contractID' and \"inttax_ofdateclose\" = '$focusDate')
												$whereOther ");
					$sum_contract_realize_amount = pg_fetch_result($qry_sum_contract_realize,0); // ดอกเบี้ยที่รับรู้รายได้แล้ว (ตั้งแต่ต้นปี)
					$sum_contract_discount_amount = pg_fetch_result($qry_sum_contract_realize,1); // ส่วนลดดอกเบี้ย (ตั้งแต่ต้นปี)
					
					// หา ดอกเบี้ยรับ (ตั้งแต่ต้นปี)
					$qry_sum_contract_negative = pg_query("SELECT sum(\"gen_amount\") as \"sum_contract_gen_amount_negative\"
											FROM \"thcap_temp_int_tax\"
											WHERE \"contractID\" = '$contractID'
												AND \"inttax_serial\" <= (select \"inttax_serial\" from \"thcap_temp_int_tax\" where \"contractID\" = '$contractID' and \"inttax_ofdateclose\" = '$focusDate')
												AND \"gen_amount\" < 0
												$whereOther ");
					$sum_contract_gen_amount_negative = pg_fetch_result($qry_sum_contract_negative,0);
					$sum_contract_gen_amount_negative *= -1; // ดอกเบี้ยที่รับรู้รายได้แล้ว (ตั้งแต่ต้นปี)
				//---------- จบการคำนวณ จำนวนเงินอื่นๆ ----------//
				
				// ถ้าสัญญาปิดบัญชีไม่เกินไตรมาสที่เลือก จะให้เป็นแถบสีเทา
				if($conCloseDate != "" && $conCloseDate <= $focusDate)
				{
					echo "<tr bgcolor=\"#CCCCCC\" style=\"font-size:11px;\">";
				}
				else
				{
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
				}
				
				echo "<td align=\"center\">".number_format($i,0)."</td>";
				echo "<td align=\"center\"><font color=\"#0000FF\" style=\"cursor:pointer;\" onClick=\"popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=700')\"><u>$contractID</u></font></td>";
				echo "<td align=\"right\">".number_format($left_gen_amount,2)."</td>";
				echo "<td align=\"right\">".number_format($left_realize_amount,2)."</td>";
				echo "<td align=\"right\">".number_format($left_unrealize_amount,2)."</td>";
				echo "<td align=\"right\">".number_format($sum_contract_realize_amount,2)."</td>";
				echo "<td align=\"right\">".number_format($sum_contract_gen_amount_negative,2)."</td>";
				echo "<td align=\"right\">".number_format($sum_contract_discount_amount,2)."</td>";
				echo "<td align=\"center\">$inttax_ofdateclose</td>";
				echo "<td align=\"center\"><img src=\"../thcap/images/detail.gif\" style=\"cursor:pointer;\" onClick=\"popU('popup_detail.php?idno=$contractID&tm=$tm&yy=$yy','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=700')\"></td>";
				echo "</tr>";
				
				$sum_left_gen_amount += $left_gen_amount;
				$sum_left_realize_amount += $left_realize_amount;
				$sum_left_unrealize_amount += $left_unrealize_amount;
				
				$sum_all_realize_amount += $sum_contract_realize_amount;
				$sum_all_gen_amount_negative += $sum_contract_gen_amount_negative;
				$sum_all_discount_amount += $sum_contract_discount_amount;
			}
			
			if($i > 0)
			{
				echo "<tr bgcolor=\"#FFCCCC\">";
				echo "<td colspan=\"2\" align=\"right\"><b>รวม</b></td>";
				echo "<td align=\"right\"><b>".number_format($sum_left_gen_amount,2)."</b></td>";
				echo "<td align=\"right\"><b>".number_format($sum_left_realize_amount,2)."</b></td>";
				echo "<td align=\"right\"><b>".number_format($sum_left_unrealize_amount,2)."</b></td>";
				echo "<td align=\"right\"><b>".number_format($sum_all_realize_amount,2)."</b></td>";
				echo "<td align=\"right\"><b>".number_format($sum_all_gen_amount_negative,2)."</b></td>";
				echo "<td align=\"right\"><b>".number_format($sum_all_discount_amount,2)."</b></td>";
				echo "<td colspan=\"2\"></td>";
				echo "</tr>";
			}
			else
			{
				echo "<tr bgcolor=\"#FFCCCC\">";
				echo "<td colspan=\"10\" align=\"center\"><b>-- ไม่พบข้อมูล --</b></td>";
				echo "</tr>";
			}
			?>
		</table>
	</center>
</fieldset>