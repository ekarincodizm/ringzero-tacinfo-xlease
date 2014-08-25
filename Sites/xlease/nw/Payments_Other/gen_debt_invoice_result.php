<?php
include("../../config/config.php");
$contractid = $_REQUEST['contractid'];
$statusshow = pg_escape_string($_GET['statusshow']); //มาจากแสดงตารางผ่อนชำระ

//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk=pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$contractid'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}
	
//รายการที่ค้างชำระ โดยไม่รวมค่าเบี้ยปรับ
$q = "select \"debtInvID\",\"debtDueDate\",\"thcap_fullname\",sum(\"debtNet\") as \"debtNet\",sum(\"debtVat\") as \"debtVat\",sum(\"debtWht\") as \"debtWht\",sum(\"typePayAmt\") as \"typePayAmt\",
(select b.\"debtNet\" from thcap_temp_invoice_otherpay b where b.\"typePayID\"=account.\"thcap_getIntFineType\"(\"contractID\") and b.\"invoiceID\"=a.\"debtInvID\") as \"intFineAmt\",
\"fullname\",\"doerStamp\" 
from \"Vthcap_debt_invoice\" a where \"contractID\"='$contractid' and \"debtStatus\"='1' and \"typePayID\"<>account.\"thcap_getIntFineType\"(\"contractID\")
group by \"debtInvID\",\"debtDueDate\",\"thcap_fullname\",\"intFineAmt\",\"fullname\",\"doerStamp\"
";
$qr = pg_query($q);
if($qr)
{
	$row = pg_num_rows($qr);
}

$q1 = "select \"debtInvID\",\"debtDueDate\",\"thcap_fullname\",sum(\"debtNet\") as \"debtNet\",sum(\"debtVat\") as \"debtVat\",sum(\"debtWht\") as \"debtWht\",sum(\"typePayAmt\") as \"typePayAmt\",
(select b.\"debtNet\" from thcap_temp_invoice_otherpay b where b.\"typePayID\"=account.\"thcap_getIntFineType\"(\"contractID\") and b.\"invoiceID\"=a.\"debtInvID\") as \"intFineAmt\",\"fullname\",\"doerStamp\"
from \"Vthcap_debt_invoice\" a where \"contractID\"='$contractid' and \"debtStatus\"='2'
group by \"debtInvID\",\"debtDueDate\",\"thcap_fullname\",\"intFineAmt\",\"fullname\",\"doerStamp\"";
$qr1 = pg_query($q1);
if($qr1)
{
	$row1 = pg_num_rows($qr1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ไทยเอซ แคปปิตอล จำกัด</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language="javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function print_debt_invoice(debt_id){
	popU('print_debt_invoice_pdf.php?invoiceID='+debt_id,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=750');	
}
</script>
</head>
<style type="text/css">
span.dropt {border-bottom: thin dotted; background: #ffeedd;}
span.dropt:hover {text-decoration: none; background: #ffffff; z-index: 6; }
span.dropt span {position: absolute; left: -9999px;
  margin: 20px 0 0 0px; padding: 3px 3px 3px 3px;
  border-style:solid; border-color:black; border-width:1px; z-index: 6;}
span.dropt:hover span {left: 50%; background: #ffffff;} 
span.dropt span {position: absolute; left: -9999px;
  margin: 4px 0 0 0px; padding: 3px 3px 3px 3px; 
  border-style:solid; border-color:black; border-width:1px;}
span.dropt:hover span {margin: 20px 0 0 170px; background: #FF6A6A; z-index:6;font-weight:bold;} 
</style>
<body>
<fieldset style="padding:15px;">
<legend><b>ข้อมูลใบแจ้งหนี้</b></legend>
<div align="center">
	<h3>เลขที่สัญญา : <?php echo $contractid; ?></h3>
</div>
<div style="text-align:left;padding:5px 0 20px;">
<span style="background-color:#FFB53D;">&nbsp;&nbsp;&nbsp;</span> : มีการชำระบางส่วน  |
<span style="background-color:#E8E8E8;">&nbsp;&nbsp;&nbsp;</span> : ชำระครบทั้งใบแจ้งหนี้  |
รายการสีฟ้า คือ ยังไม่ชำระแม้แต่รายการเดียว </div>

<fieldset style="padding:15px;">
	<legend><b>รายการที่ค้างชำระ</b></legend>
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    	<tr style="background-color:#FFCCDD;">
        	<th>ลำดับ</th>
            <th>เลขที่ใบแจ้งหนึ้</th>
            <th>วันที่ครบกำหนด</th>
			<th>เลขที่สัญญา</th>
            <th>ชื่อลูกค้า</th>
            <th>จำนวนเงิน</th>
            <th>ภาษีมูลค่าเพิ่ม</th>
			<th>เบี้ยปรับ</th>
			<th>รวม</th>
            <th>ภาษีหัก ณ ที่จ่าย</th>
			<th>ผู้ออกใบแจ้งหนี้</th>
			<th>วันเวลาที่ออกใบแจ้งหนี้</th>
			<th>สถานะการส่ง</th>
            <th>พิมพ์</th>
        </tr>
        <?php
		$i = 0;
		$n = 1;
		if($row==0)
		{
			echo "
				<tr class=\"odd\">
					<td colspan=\"14\" align=\"center\">********************************* ไม่มีข้อมูล *********************************</td>
				</tr>
			";
		}
		else
		{
			$all_debtNet = 0;
			$all_debtVat = 0;
			$all_debtWht = 0;
			$all_typePayAmt = 0;
			$all_intFineAmt = 0;
			while($rs = pg_fetch_array($qr))
			{
				$debtInvID = $rs['debtInvID'];
				$debtDueDate = $rs['debtDueDate'];
				$thcap_fullname = $rs['thcap_fullname'];
				$debtNet = $rs['debtNet'];
				$debtVat = $rs['debtVat'];
				$debtWht = $rs['debtWht'];
				$typePayAmt = $rs['typePayAmt'];
				$intFineAmt = $rs['intFineAmt'];
				$fullname = $rs['fullname'];
				$doerStamp = $rs['doerStamp'];
				
				$typePayAmt=$rs['typePayAmt']+$rs['intFineAmt'];
				
				
				$all_debtNet = $all_debtNet+$debtNet;
				$all_debtVat = $all_debtVat+$debtVat;
				$all_debtWht = $all_debtWht+$debtWht;
				$all_typePayAmt = $all_typePayAmt+$typePayAmt;
				$all_intFineAmt = $all_intFineAmt+$intFineAmt;
				
				
				if($debtNet != ""){
					$debtNetshow = number_format($debtNet,2);
				}else{
					$debtNetshow = 'ไม่มีข้อมูล';
				}
				if($debtVat != ""){				
					$debtVatshow = number_format($debtVat,2);
				}else{
					$debtVatshow = 'ไม่มีข้อมูล';
				}
				if($debtWht != ""){
					$debtWhtshow = number_format($debtWht,2);
				}else{
					$debtWhtshow = 'ไม่มีข้อมูล';
				}	
				if($typePayAmt != ""){	
					$typePayAmtshow = number_format($typePayAmt,2);
				}else{
					$typePayAmtshow = 'ไม่มีข้อมูล';
				}
				
				//ตรวจสอบว่าเลขใบแจ้งหนี้นี้มีการจ่ายไปบางส่วนหรือยัง
				$qrychk=pg_query("select \"debtInvID\" from \"Vthcap_debt_invoice\" where \"debtInvID\"='$debtInvID' and \"debtStatus\"='2'");
				$numchk=pg_num_rows($qrychk);
				
				//ตรวจสอบว่ามีการส่งจดหมายหรือยัง
				$qrysend=pg_query("select \"sendDate\" from vthcap_letter where \"detailRef\" ='$debtInvID' and \"contractID\" = '$contractid'");
				$numsend=pg_num_rows($qrysend);
				if($numsend>0){
					$txtsend="จัดส่งแล้ว";
					list($sendDate)=pg_fetch_array($qrysend);
					$txtsend="<span class=\"dropt\">จัดส่งแล้ว
					<span style=\"width:200px;\">ส่งแล้ววันที่ $sendDate</span>
					</span>";
				}else{
					$txtsend="ยังไม่จัดส่ง";
				}
				
				if($i%2==0)
				{
					if($numchk>0){ //แสดงว่ามีการจ่ายไปบางส่วนแล้วแต่ยังไม่ครบ
						echo "<tr bgcolor=#FFB53D align=center>";
					}else{
						echo "<tr class=\"odd\" align=center>";
					}
				}
				else
				{
					if($numchk>0){ //แสดงว่ามีการจ่ายไปบางส่วนแล้วแต่ยังไม่ครบ
						echo "<tr bgcolor=#FFB53D align=center>";
					}else{
						echo "<tr class=\"even\" align=center>";
					}
				}
				echo "
					<td>$n</td>
					<td>
						<span onclick=\"javascript:popU('../thcap/Channel_detail_i.php?debtInvID=$debtInvID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor: pointer;\">
						<u>$debtInvID</u></span>
					</td>
					<td>$debtDueDate</td>
					<td align=\"center\">
						<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">
						<u>$contractid</u></span>
					</td>
					<td>$thcap_fullname</td>
					<td align=\"right\">".$debtNetshow."</td>
					<td align=\"right\">".$debtVatshow."</td>
					<td align=\"right\">".number_format($intFineAmt,2)."</td>
					<td align=\"right\">".$typePayAmtshow."</td>
					<td align=\"right\">".$debtWhtshow."</td>
					<td align=\"left\">".$fullname."</td>
					<td>".$doerStamp."</td>
					<td>$txtsend</td>
					<td><img src=\"images/printer.png\" alt=\"พิมพ์ใบแจ้งหนี้\" width=\"16\" height=\"16\" style=\"cursor:pointer;\" onclick=\"print_debt_invoice('$debtInvID');\" /></td>
					</tr>
				";
				$i++;
				$n++;
			}
			if($row!=0)
			{
				echo "
					<tr style=\"background-color:#FFCCDD;\">
						<td colspan=\"5\" align=\"center\"><b>ยอดรวม</b></td>
						<td align=\"right\"><b>".number_format($all_debtNet,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtVat,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_intFineAmt,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_typePayAmt,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtWht,2,'.',',')."</b></td>
						<td colspan=4></td>
					</tr>
				";
			}
		}
		?>
    </table>
</fieldset>
<fieldset style="padding:15px;">
	<legend><b>รายการที่ชำระแล้ว</b></legend>
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    	<tr style="background-color:#0298c9;">
        	<th>ลำดับ</th>
            <th>เลขที่ใบแจ้งหนึ้</th>
            <th>วันที่ครบกำหนด</th>
			<th>เลขที่สัญญา</th>
            <th>ชื่อลูกค้า</th>
            <th>จำนวนเงิน</th>
            <th>ภาษีมูลค่าเพิ่ม</th>
			<th>เบี้ยปรับ</th>
            <th>รวม</th>
			<th>ภาษีหัก ณ ที่จ่าย</th>
			<th>ผู้ออกใบแจ้งหนี้</th>
			<th>วันเวลาที่ออกใบแจ้งหนี้</th>
            <th>พิมพ์</th>
        </tr>
        <?php
		$j = 0;
		$m = 1;
		if($row1==0)
		{
			echo "
				<tr class=\"odd\">
					<td colspan=\"13\" align=\"center\">********************************* ไม่มีข้อมูล *********************************</td>
				</tr>
			";
		}
		else
		{
			$all_debtNet1 = 0;
			$all_debtVat1 = 0;
			$all_debtWht1 = 0;
			$all_typePayAmt1 = 0;
			$all_intFineAmt1 = 0;
			while($rs1 = pg_fetch_array($qr1))
			{
				$debtInvID1 = $rs1['debtInvID'];
				$debtDueDate1 = $rs1['debtDueDate'];
				$thcap_fullname1 = $rs1['thcap_fullname'];
				//$namedetail1 = $rs1['namedetail']; //เนื่องจาก 1 ใบแจ้งหนี้อาจมีหลายรหัสหนี้จึงไม่ได้ใช้
				$debtNet1 = $rs1['debtNet'];
				$debtVat1 = $rs1['debtVat'];
				$debtWht1 = $rs1['debtWht'];
				$typePayAmt1 = $rs1['typePayAmt'];
				$intFineAmt1 = $rs1['intFineAmt'];
				$fullname1 = $rs1['fullname'];
				$doerStamp1 = $rs1['doerStamp'];
				
				$typePayAmt1 = $rs1['typePayAmt']+$rs1['intFineAmt'];
				
				$all_debtNet1 = $all_debtNet1+$debtNet1;
				$all_debtVat1 = $all_debtVat1+$debtVat1;
				$all_debtWht1 = $all_debtWht1+$debtWht1;
				$all_typePayAmt1 = $all_typePayAmt1+$typePayAmt1;
				$all_intFineAmt1 = $all_intFineAmt1+$intFineAmt1;
				
				
				if($debtNet1 != ""){
					$debtNetshow1 = number_format($debtNet1,2);
				}else{
					$debtNetshow1 = 'ไม่มีข้อมูล';
				}
				if($debtVat1 != ""){				
					$debtVatshow1 = number_format($debtVat1,2);
				}else{
					$debtVatshow1 = 'ไม่มีข้อมูล';
				}
				if($debtWht1 != ""){
					$debtWhtshow1 = number_format($debtWht1,2);
				}else{
					$debtWhtshow1 = 'ไม่มีข้อมูล';
				}	
				if($typePayAmt1 != ""){	
					$typePayAmtshow1 = number_format($typePayAmt1,2);
				}else{
					$typePayAmtshow1 = 'ไม่มีข้อมูล';
				}
				
				//ตรวจสอบว่าเลขใบแจ้งหนี้นี้มีรายการค้างชำระหรือไม่
				$qrychk=pg_query("select \"debtInvID\" from \"Vthcap_debt_invoice\" where \"debtInvID\"='$debtInvID1' and \"debtStatus\"='1'");
				$numchk=pg_num_rows($qrychk);
				
				if($numchk>0){ //แสดงว่ามีการจ่ายไปบางส่วนแล้วแต่ยังไม่ครบ
					echo "<tr bgcolor=#FFB53D>";
				}else{	
					echo "<tr bgcolor=#E8E8E8>";
				}
					
				echo "
					<td align=\"center\">$m</td>
					<td align=\"center\">
						<span onclick=\"javascript:popU('../thcap/Channel_detail_i.php?debtInvID=$debtInvID1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor: pointer;\">
						<u>$debtInvID1</u></span>
					</td>
					<td align=\"center\">$debtDueDate1</td>
					<td align=\"center\">
						<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">
						<u>$contractid</u></span>
					</td>
					<td>$thcap_fullname1</td>
					<td align=\"right\">".$debtNetshow1."</td>
					<td align=\"right\">".$debtVatshow1."</td>
					<td align=\"right\">".number_format($intFineAmt1,2)."</td>
					<td align=\"right\">".$typePayAmtshow1."</td>
					<td align=\"right\">".$debtWhtshow1."</td>
					<td align=\"left\">".$fullname1."</td>
					<td align=\"center\">".$doerStamp1."</td>
					<td align=\"center\"><img src=\"images/printer.png\" alt=\"พิมพ์ใบแจ้งหนี้\" width=\"16\" height=\"16\" style=\"cursor:pointer;\" onclick=\"print_debt_invoice('$debtInvID1');\" /></td>
				";
				$j++;
				$m++;
			}
			if($row1!=0)
			{
				echo "
					<tr style=\"background-color:#0298c9;\">
						<td colspan=\"5\" align=\"center\"><b>ยอดรวม</b></td>
						<td align=\"right\"><b>".number_format($all_debtNet1,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtVat1,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_intFineAmt1,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_typePayAmt1,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtWht1,2,'.',',')."</b></td>
						<td colspan=3></td>
					</tr>
				";
			}
		}
		?>
    </table>
</fieldset>



<!-- ประวัติการพิมพ์ 30 รายการล่าสุด -->
<?php
if($statusshow!=1){ //กรณีไม่ได้มาจากแสดงตารางผ่อนชำระ
	$qhis = "SELECT b.\"debtInvID\",b.\"debtDueDate\",b.\"thcap_fullname\",c.\"fullname\" as \"pritnuser\",a.\"do_time\",sum(\"debtNet\") as \"debtNet\",sum(\"debtVat\") as \"debtVat\",
			sum(\"debtWht\") as \"debtWht\",sum(\"typePayAmt\") as \"typePayAmt\",\"intFineAmt\"
			FROM \"thcap_print_debt_invoice_log\" a
			LEFT JOIN \"Vthcap_debt_invoice\" b ON b.\"debtInvID\" = a.\"thcap_debt_invoice_id\"
			LEFT JOIN \"Vfuser\" c ON a.\"doer\" = c.\"id_user\"
			WHERE a.\"thcap_debt_invoice_id\" IN (select \"debtInvID\" From \"Vthcap_debt_invoice\" where \"contractID\"='$contractid')
			group by b.\"debtInvID\",b.\"debtDueDate\",b.\"thcap_fullname\",c.\"fullname\",a.\"do_time\",\"intFineAmt\"
			ORDER BY a.\"do_time\" DESC LIMIT 30";
	$qrhis = pg_query($qhis);
	if($qrhis)
	{
		$rowhis = pg_num_rows($qrhis);
	}
?>
<fieldset style="padding:15px;">
	<legend><font color="black"><b>ประวัติการพิมพ์ใบแจ้งหนี้สัญญา <font color="red"><?php echo $contractid; ?></font> 30 รายการล่าสุด ( <font color="blue"><a onclick="popU('frm_history_print_invoice.php?contractid=<?php echo $contractid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')" style="cursor:pointer;"><u> ทั้งหมด </u></a></font>)</b></font></legend>
    <table width="100%" border="0" cellpadding="5" cellspacing="1">
    	<tr style="background-color:#CCCCCC;">
        	<th>ลำดับ</th>
            <th>เลขที่ใบแจ้งหนึ้</th>
            <th>วันที่ครบกำหนด</th>
			<th>เลขที่สัญญา</th>
            <th>ชื่อลูกค้า</th>
            <th>จำนวนเงิน</th>
            <th>ภาษีมูลค่าเพิ่ม</th>
			<th>เบี้ยปรับ</th>
            <th>รวม</th>
			<th>ภาษีหัก ณ ที่จ่าย</th>
            <th>ผู้พิมพ์</th>
			<th>วันที่/เวลาที่พิมพ์</th>
        </tr>
        <?php
		$i = 0;
		$n = 1;
		if($rowhis==0)
		{
			echo "
				<tr class=\"odd\">
					<td colspan=\"12\" align=\"center\">********************************* ไม่มีข้อมูล *********************************</td>
				</tr>
			";
		}
		else
		{
			$all_debtNet = 0;
			$all_debtVat = 0;
			$all_debtWht = 0;
			$all_typePayAmt = 0;
			$all_intFineAmt = 0;
			while($rshis = pg_fetch_array($qrhis))
			{
				$debtInvID = $rshis['debtInvID'];
				$debtDueDate = $rshis['debtDueDate'];
				$thcap_fullname = $rshis['thcap_fullname'];
				$namedetail = $rshis['namedetail'];
				$debtNet = $rshis['debtNet'];
				$debtVat = $rshis['debtVat'];
				$debtWht = $rshis['debtWht'];
				$typePayAmt = $rshis['typePayAmt'];
				$pritnuser = $rshis['pritnuser'];
				$printdatetime = $rshis['do_time'];
				$intFineAmt = $rshis['intFineAmt'];
				$typePayAmt = $rshis['typePayAmt']+$intFineAmt;
				
				$all_debtNet = $all_debtNet+$debtNet;
				$all_debtVat = $all_debtVat+$debtVat;
				$all_debtWht = $all_debtWht+$debtWht;
				$all_typePayAmt = $all_typePayAmt+$typePayAmt;
				$all_intFineAmt = $all_intFineAmt+$intFineAmt;
				
				if($debtNet != ""){
					$debtNetshow = number_format($debtNet,2);
				}else{
					$debtNetshow = 'ไม่มีข้อมูล';
				}
				if($debtVat != ""){				
					$debtVatshow = number_format($debtVat,2);
				}else{
					$debtVatshow = 'ไม่มีข้อมูล';
				}
				if($debtWht != ""){
					$debtWhtshow = number_format($debtWht,2);
				}else{
					$debtWhtshow = 'ไม่มีข้อมูล';
				}	
				if($typePayAmt != ""){	
					$typePayAmtshow = number_format($typePayAmt,2);
				}else{
					$typePayAmtshow = 'ไม่มีข้อมูล';
				}
				
				if($i%2==0){
					echo "<tr bgcolor=\"#EEEEEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\">";
				}else{
					echo "<tr bgcolor=\"#DDDDDD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\">";
				}
				echo "
					<td align=\"center\">$n</td>
					<td align=\"center\">
						<span onclick=\"javascript:popU('../thcap/Channel_detail_i.php?debtInvID=$debtInvID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\" style=\"cursor: pointer;\">
						<u>$debtInvID</u></span>
					</td>
					<td align=\"center\">$debtDueDate</td>
					<td align=\"center\">
						<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">
						<u>$contractid</u></span>
					</td>
					<td>$thcap_fullname</td>
					<td align=\"right\">".$debtNetshow."</td>
					<td align=\"right\">".$debtVatshow."</td>
					<td align=\"right\">".number_format($intFineAmt,2)."</td>
					<td align=\"right\">".$typePayAmtshow."</td>
					<td align=\"right\">".$debtWhtshow."</td>
					<td>$pritnuser</td>
					<td>$printdatetime</td>
					</tr>
				";
				$i++;
				$n++;
				unset($intFineAmt);
			}
				echo "
					<tr style=\"background-color:#CCCCCC;\">
						<td colspan=\"5\" align=\"center\"><b>ยอดรวม</b></td>
						<td align=\"right\"><b>".number_format($all_debtNet,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtVat,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_intFineAmt,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_typePayAmt,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtWht,2,'.',',')."</b></td>
						<td colspan=\"2\"></td>
					</tr>
				";		
		}
		?>
    </table>
</fieldset>
<?php
}
?>
</body>
</html>