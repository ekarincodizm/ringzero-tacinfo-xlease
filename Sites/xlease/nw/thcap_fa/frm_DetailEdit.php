<?php
include("../../config/config.php");
$prebillID=$_GET["prebillIDMaster"];
$edittime=$_GET["edittime"]; //ครั้งที่แก้ไข
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>รายละเอียดบิลที่แก้ไข</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<script language="javascript" type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery-ui-1.8.21.custom.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/ui-lightness/jquery-ui.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="js/jquery.coolfieldset.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.coolfieldset.css" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<!---- หน้าต่าง Popup รูปภาพ ---->
	<!-- Add jQuery library -->
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="source/jquery.fancybox.js?v=2.0.6"></script>
	<link rel="stylesheet" type="text/css" href="source/jquery.fancybox.css?v=2.0.6" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
	<script type="text/javascript" src="source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>
<script language="javascript">
$(document).ready(function(){
	$(".pdforpic").fancybox({
	   minWidth: 500,
	   maxWidth: 800,
	   'height' : '600',
	   'autoScale' : true,
	   'transitionIn' : 'none',
	   'transitionOut' : 'none',
	   'type' : 'iframe'
	});
	
});
</script>
</head>
<div align="center"><h2>รายละเอียดบิลขอสินเชื่อที่แ้ก้ไข</h2></div>
<table width="80%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
<?php
	$color1="#EED5D2";
	$color2="#FFE4E1";

	$qrydata=pg_query("SELECT * FROM vthcap_fa_prebill_temp WHERE \"prebillID\"='$prebillID' AND \"prebillIDMaster\"='$prebillID' AND \"edittime\"='$edittime'");
	if($resdata=pg_fetch_array($qrydata)){
		$dateInvoice = $resdata["dateInvoice"]; // วันที่ใบแจ้งหนี้
		$numberInvoice = $resdata["numberInvoice"]; // เลขที่ใบแจ้งหนี้
		$userDebtorID= $resdata["userDebtor"]; // รหัสลูกหนี้
		$userDebName = $resdata["userDebtorName"]; // ชื่อลูกหนี้
		$userSalebillID = $resdata["userSalebill"]; // รหัสผู้ขาย
		$userSaleName = $resdata["userSalebillName"]; // ชื่อผู้ขาย
		$totalTaxInvoice = $resdata["totalTaxInvoice"]; // จำนวนเงินในบิล
		$dateBill = $resdata["dateBill"]; // วันที่วางบิล
		$placeReceiveChq = $resdata["placeReceiveChq"]; // สถานที่รับเช็ค
		$note = $resdata["note"]; // หมายเหตุ
		
		$userSalebill=$userSalebillID."#".$userSaleName; //ชื่อผู้ขาย
		$userDebtor=$userDebtorID."#".$userDebName; //ชื่อลูกหนี้
		
		?>
		<td valign="top">
		<table width="100%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#CDB7B5">
		<tr><td colspan="3" bgcolor="<?php echo $color1; ?>">&nbsp;</td></tr>
		<tr align="left" bgcolor="<?php echo $color1;?>">
			<td align="right" width="156"><b>ผู้ขายบิล</b></td>
			<td width="10" align="center">:</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" size="50" name="userSalebill" id="userSalebill" value="<?php echo $userSalebill; ?>" readonly="true"></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>ชื่อลูกหนี้ของผู้ขายบิล</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" size="50" name="userDebtor" id="userDebtor" value="<?php echo $userDebtor; ?>" readonly="true"></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>วันที่ใบแจ้งหนี้</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="dateInvoice" id="dateInvoice" size="15" value="<?php echo $dateInvoice;?>" readonly="true"> <b>(ปี ค.ศ.)</b></font></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>เลขที่ใบแจ้งหนี้</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="numberInvoice" id="numberInvoice" value="<?php echo $numberInvoice;?>" readonly="true"></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>ยอดในใบแจ้งหนี้รวมภาษี</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="totalTaxInvoice" id="totalTaxInvoice" value="<?php echo number_format($totalTaxInvoice,2); ?>" style="text-align:right;" readonly="true"> <b>บาท</b></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>วันที่วางบิล</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="dateBill" id="dateBill" size="15"value="<?php echo $dateBill; ?>" readonly="true"> <b>(ปี ค.ศ.)</b></td>
		</tr>
		<?php
		//วันที่นัดรับเช็ค
		$qryeach=pg_query("SELECT *  FROM thcap_fa_prebill_temp
		WHERE \"prebillIDMaster\"='$prebillID' and \"edittime\"='$edittime' ORDER BY \"prebillID\"");
		$numeach=pg_num_rows($qryeach);
		$p=0;
		$text="";
		$color1_1=$color1;
		$color2_2=$color2;
		while($reseach=pg_fetch_array($qryeach)){	
			$p++;
			$prebillID_each=$reseach["prebillID"];
			$dateAssign_each=$reseach["dateAssign"];
			$taxInvoice_each=$reseach["taxInvoice"];
			$stsprocess=$reseach["stsprocess"]; //สถานะการแก้ไข 
			
			if($stsprocess=="I"){ //กรณีเป็นการเพิ่มข้อมูลใหม่
				$color1_1="#F4A460";
				$text="(เพิ่มข้อมูล)";
				$color2_2="";
			}else if($stsprocess=="D"){ //กรณีลบข้อมูล
				$color1_1="#EE6363";
				$color2_2="";
			}
		?>
		<tr align="left" bgcolor="<?php echo $color1_1;?>">
			<td align="right" width="155"><b>วันที่นัดรับเช็คครั้งที่ <?php echo $p; ?></b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2_2; ?>">
				<?php
				if($stsprocess=="D"){
					echo "<b>------รายการนี้ถูกลบ------</b>";
					$p--;
				}else{
				?>
				<input type="text" name="dateAssign[]" size="15" value="<?php echo $dateAssign_each; ?>" <?php echo $color_dateAssign; ?> readonly="true"> จำนวนเงิน <input type="text" name="recmoney[]" value="<?php echo number_format($taxInvoice_each,2); ?>" <?php echo $color_taxInvoice; ?> readonly="true"><?php echo $text; ?>
				<?php } ?>
			</td>
		</tr>
		<?php
		} //จบแสดงวันที่ันัดรับเช็ค
		?>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right" valign="top"><b>สถานที่รับเช็ค</b></td>
			<td align="center" valign="top">:</td>
			<td bgcolor="<?php echo $color2; ?>"><textarea name="placeReceiveChq" id="placeReceiveChq" cols="30" rows="3" readonly="true" ><?php echo $placeReceiveChq; ?></textarea></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right" valign="top"><b>หมายเหตุ</b></td>
			<td align="center" valign="top">:</td>
			<td bgcolor="<?php echo $color2; ?>" valign="top"><textarea name="note" id="note" cols="30" rows="3" readonly="true"><?php echo $note;?></textarea></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right" valign="top"><b>ไฟล์บิล</b></td>
			<td align="center" valign="top">:</td>
			<td bgcolor="<?php echo $color2; ?>" valign="top">
			<?php
			if($i==0){
				$con="";
			}
			$qryfile=pg_query("SELECT *  FROM thcap_fa_prebill_file WHERE \"prebillID\"='$prebillID' AND \"edittime\"='$edittime' ORDER BY \"file\"");
			$t=1;
			while($resfile=pg_fetch_array($qryfile)){
				$filename=$resfile["file"]; //ชื่อบิล
				echo "<a class=\"pdforpic\" href=\"../upload/fa_prebill/$filename\" data-fancybox-group=\"gallery\" title=\"แสดงบิล\"><font color=blue><u>ไฟล์บิล $t</u></font></a><br>";
				$t++;
			}
			?>
			
			</td>
		</tr>
		<tr><td colspan="3" bgcolor="<?php echo $color1; ?>">&nbsp;</td></tr>
		</table>
		</td>
	<?php
	} //end if
	
?>
</tr>
</table> 
<div style="text-align:center;padding-top:20px;"><input type="button" value="ปิด" onclick="window.close();"></div>

</body>
</html>