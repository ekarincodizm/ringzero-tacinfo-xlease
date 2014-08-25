<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<?php

$user_id = $_SESSION['av_iduser']; // รหัสพนักงาน

// วันที่
$datepicker = pg_escape_string($_GET["datepicker"]);

//--- gen function เพื่อสร้างข้อมูล
	pg_query("BEGIN");
	$status = 0;
	
	$sqlaction = pg_query("select warehouse.thcap_update_wh_r_cash('$datepicker', '$user_id')");
	if($sqlaction){}else{$status++;}

	if($status == 0){pg_query("COMMIT");} else{pg_query("ROLLBACK");}
//--- จบการ gen function เพื่อสร้างข้อมูล

$qry_details = pg_query("select * from warehouse.thcap_wh_r_cash_details where \"thcap_wh_r_cash_date\" = '$datepicker' ");
while($res_details = pg_fetch_array($qry_details))
{
	$thcap_wh_r_cash_id = $res_details["thcap_wh_r_cash_id"]; // pk รายงานเงินสดประจำวัน
	$thcap_wh_r_cash_yesterdayamt = $res_details["thcap_wh_r_cash_yesterdayamt"]; // เงินสดคงเหลือยกมา
	$thcap_wh_r_cash_sumrecamt = $res_details["thcap_wh_r_cash_sumrecamt"]; // จำนวนเงินสดรวมรับทั้งวัน
	$thcap_wh_r_cash_sumpayamt = $res_details["thcap_wh_r_cash_sumpayamt"]; // จำนวนเงินสดรวมจ่ายทั้งวัน
	$thcap_wh_r_cash_todayamt = $res_details["thcap_wh_r_cash_todayamt"]; // เงินสดคงเหลือ ณ สิ้นวัน
	$thcap_wh_r_cash_changeamt = $res_details["thcap_wh_r_cash_changeamt"]; // เงินสดที่เปลี่ยนแปลง (วันนี้ลบด้วยเมื่อวาน)
}
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<fieldset><legend><B>รายละเอียด</B></legend>
	<center>
		<h2>บริษัท ไทยเอซ แคปปิตอล จำกัด</h2>
		<h2>(THCAP) รายงานเงินสดประจำวัน (ทางระบบดำเนินงาน)</h2>
		<h2>ณ วันที่ <?php echo $datepicker; ?></h2>
	
	<table width="90%" cellpadding="5" cellspacing="1" border="0" bgcolor="#D0D0D0">
		<tr bgcolor="#FFFFFF">
			<td align="right" colspan="2">บาท</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="left">เงินสดคงเหลือยกมา</td>
			<td align="right"><?php echo number_format($thcap_wh_r_cash_yesterdayamt,2); ?></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="left">บวก เงินสดรับ:</td>
			<td align="right"></td>
		</tr>
		<?php
		// หารายการรับ
		$qry_item = pg_query("select * from warehouse.thcap_wh_r_cash_item where \"thcap_wh_r_cash_id\" = '$thcap_wh_r_cash_id' and \"thcap_wh_r_cash_trantype\" = '1' ");
		while($res_item = pg_fetch_array($qry_item))
		{
			$thcap_wh_r_cash_doctype = $res_item["thcap_wh_r_cash_doctype"]; // ประเภทเอกสาร 0-ใบเสร็จรับเงิน 1-ใบสำคัญจ่าย 2-ใบสำคัญรับ 3-ใบสำคัญรายวันทั่วไป
			$thcap_wh_r_cash_docref = $res_item["thcap_wh_r_cash_docref"]; // เลขทีของเอกสารอ้างอิง เช่น เลขที่ใบเสร็จรับเงิน
			$thcap_wh_r_cash_amt = $res_item["thcap_wh_r_cash_amt"]; // จำนวนเงินที่เกิดขึ้นของรายการนี้
			
			if($thcap_wh_r_cash_doctype == "0" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบเสร็จรับเงิน
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			elseif($thcap_wh_r_cash_doctype == "1" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบสำคัญจ่าย
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			elseif($thcap_wh_r_cash_doctype == "2" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบสำคัญรับ
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			elseif($thcap_wh_r_cash_doctype == "3" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบสำคัญรายวันทั่วไป
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			else
			{
				$thcap_wh_r_cash_docref_click = $thcap_wh_r_cash_docref;
			}
			
			echo "<tr bgcolor=\"#AAFFAA\">";
			echo "<td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$thcap_wh_r_cash_docref_click</td>";
			echo "<td align=\"right\">".number_format($thcap_wh_r_cash_amt,2)."</td>";
			echo "</tr>";
		}
		?>
		<tr bgcolor="#55FF55">
			<td align="left">รวมรับประจำวัน</td>
			<td align="right"><?php echo number_format($thcap_wh_r_cash_sumrecamt,2); ?></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="left">หัก เงินสดจ่าย:</td>
			<td align="right"></td>
		</tr>
		<?php
		// หารายการรับ
		$qry_item = pg_query("select * from warehouse.thcap_wh_r_cash_item where \"thcap_wh_r_cash_id\" = '$thcap_wh_r_cash_id' and \"thcap_wh_r_cash_trantype\" = '2' ");
		while($res_item = pg_fetch_array($qry_item))
		{
			$thcap_wh_r_cash_doctype = $res_item["thcap_wh_r_cash_doctype"]; // ประเภทเอกสาร 0-ใบเสร็จรับเงิน 1-ใบสำคัญจ่าย 2-ใบสำคัญรับ 3-ใบสำคัญรายวันทั่วไป
			$thcap_wh_r_cash_docref = $res_item["thcap_wh_r_cash_docref"]; // เลขทีของเอกสารอ้างอิง เช่น เลขที่ใบเสร็จรับเงิน
			$thcap_wh_r_cash_amt = $res_item["thcap_wh_r_cash_amt"]; // จำนวนเงินที่เกิดขึ้นของรายการนี้
			
			if($thcap_wh_r_cash_doctype == "0" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบเสร็จรับเงิน
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			elseif($thcap_wh_r_cash_doctype == "1" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบสำคัญจ่าย
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			elseif($thcap_wh_r_cash_doctype == "2" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบสำคัญรับ
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			elseif($thcap_wh_r_cash_doctype == "3" && $thcap_wh_r_cash_docref != "")
			{ // ถ้าเป็น ใบสำคัญรายวันทั่วไป
				$thcap_wh_r_cash_docref_click = "<a href=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$thcap_wh_r_cash_docref','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\"><b><u>$thcap_wh_r_cash_docref</u></b></a>";
			}
			else
			{
				$thcap_wh_r_cash_docref_click = $thcap_wh_r_cash_docref;
			}
			
			echo "<tr bgcolor=\"#FFFFAA\">";
			echo "<td align=\"left\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$thcap_wh_r_cash_docref_click</td>";
			echo "<td align=\"right\">".number_format($thcap_wh_r_cash_amt,2)."</td>";
			echo "</tr>";
		}
		?>
		<tr bgcolor="#FFFF55">
			<td align="left">รวมจ่ายประจำวัน</td>
			<td align="right"><?php echo number_format($thcap_wh_r_cash_sumpayamt,2); ?></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="left">เงินสดคงเหลือ ณ สิ้นวัน</td>
			<td align="right"><u><?php echo number_format($thcap_wh_r_cash_todayamt,2); ?></u></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td align="left">เงินจำนวนเงินสดที่เปลี่ยนแปลง</td>
			<td align="right"><u><?php echo number_format($thcap_wh_r_cash_changeamt,2); ?></u></td>
		</tr>
	</table>
	
	<br><br>
	<table width="90%">
		<tr>
			<td align="left">..................................</td>
			<td align="right">..................................</td>
		</tr>
		<tr>
			<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ลงชื่อ ผู้ตรวจสอบ</td>
			<td align="right">ลงชื่อ ผู้อนุมัติ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
	</table>
	
	</center>
</fieldset>
