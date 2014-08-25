<?php
$showall=$_GET["showall"];

if($showall==1){
	include("../../config/config.php");
	$limit="";
}else{
	$limit="limit (30)";
}

	$qhis = "SELECT b.\"debtInvID\",b.\"debtDueDate\",b.\"thcap_fullname\",c.\"fullname\" as \"pritnuser\",a.\"do_time\",sum(\"debtNet\") as \"debtNet\",sum(\"debtVat\") as \"debtVat\",
			sum(\"debtWht\") as \"debtWht\",sum(\"typePayAmt\") as \"typePayAmt\",\"intFineAmt\", b.\"contractID\"
			FROM \"thcap_print_debt_invoice_log\" a
			LEFT JOIN \"Vthcap_debt_invoice\" b ON b.\"debtInvID\" = a.\"thcap_debt_invoice_id\"
			LEFT JOIN \"Vfuser\" c ON a.\"doer\" = c.\"id_user\"
			group by b.\"debtInvID\",b.\"debtDueDate\",b.\"thcap_fullname\",c.\"fullname\",a.\"do_time\",\"intFineAmt\", b.\"contractID\"
			ORDER BY a.\"do_time\" DESC $limit";
	$qrhis = pg_query($qhis);
	if($qrhis)
	{
		$rowhis = pg_num_rows($qrhis);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติการพิมพ์ใบแจ้งหนี้</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
   <link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>	
	
	
<fieldset style="padding:15px;">
	<legend><font color="black">
	<?php 
	if($showall==1){
		?>
		<b>ประวัติการพิมพ์ใบแจ้งหนี้ทั้งหมด</b>
	<?php
	}else{
		?>
		<b>ประวัติการพิมพ์ใบแจ้งหนี้ 30 รายการล่าสุด ( <font color="blue"><a onclick="popU('frm_history_print_allinvoice.php?showall=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')" style="cursor:pointer;"><u> ทั้งหมด </u></a></font>)</b>
	<?php
	}
	?>
	</font>
	</legend>
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
				$contractID = $rshis['contractID']; // เลขที่สัญญา
				
				$typePayAmt =$rshis['typePayAmt']+$rshis['intFineAmt'];
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
						<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">
						<u>$contractID</u></span>
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
			}
				echo "
					<tr style=\"background-color:#CCCCCC;\">
						<td colspan=\"5\" align=\"center\"><b>ยอดรวม</b></td>
						<td align=\"right\"><b>".number_format($all_debtNet,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtVat,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_intFineAmt,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_typePayAmt,2,'.',',')."</b></td>
						<td align=\"right\"><b>".number_format($all_debtWht,2,'.',',')."</b></td>
						<td colspan=\"3\"></td>
					</tr>
				";		
		}
		?>
    </table>
	<br>
	<?php
	if($showall==1){
	?>
	<center><input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:70px;"></center>
	<?php
	}
	?>
</fieldset>

</body>
</html>