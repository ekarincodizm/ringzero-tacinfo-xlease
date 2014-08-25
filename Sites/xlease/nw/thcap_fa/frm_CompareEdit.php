<?php
include("../../config/config.php");
$prebillID=$_GET["prebillIDMaster"];
$edittimenew=$_GET["edittime"]; //ครั้งที่แก้ไข

//หาครั้งที่แก้ไขก่อนหน้านี้
//$qryedittime=pg_query("select \"edittime\" from vthcap_fa_prebill_edit where \"prebillID\"='$prebillID'");
$qryedittime = pg_query("select max(\"edittime\") from \"vthcap_fa_prebill_temp\" where \"prebillID\"='$prebillID' and \"prebillIDMaster\"='$prebillID' and \"statusApp\" = '1'");
list($edittimeold) = pg_fetch_array($qryedittime);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>เปรียบเทียบระหว่างข้อมูลเก่าและข้อมูลใหม่</title>
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
function approve(con){
	if(confirm('ยืนยันผลการอนุมัติ')){ return true;}
	else{return false;}	
	}
}
</script>
</head>
<div align="center"><h2>อนุมัติแก้ไขรายละเอียดบิลขอสินเชื่อ</h2></div>
<fieldset><legend><b>เปรียบเทียบระหว่างข้อมูลเก่าและข้อมูลใหม่</b></legend>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr><td colspan="2">
	<span style="background-color:#98F5FF;">&nbsp;&nbsp;&nbsp;</span> คือรายการที่แก้ไข  
	<span style="background-color:#F4A460;">&nbsp;&nbsp;&nbsp;</span> คือรายการที่เพิ่มเติม
	<span style="background-color:#EE6363;">&nbsp;&nbsp;&nbsp;</span> คือรายการที่ถูกลบ
</td></tr>
<tr>
<?php
for($i=0;$i<2;$i++){	
	if($i==0){ //==================ข้อมูลเก่า
		$text="ข้อมูลเก่า";
		$conplus="AND \"statusApp\"<>'2'";
		$edittime=$edittimeold;
		$color1="#DDDDDD";
		$color2="#EEEEEE";
	}else{ //==================ข้อมูลใหม่
		$text="ข้อมูลใหม่";
		$edittime=$edittimenew;
		$conplus="AND \"statusApp\"='2'";
		$color1="#EED5D2";
		$color2="#FFE4E1";
	}
	
	$qrydata=pg_query("SELECT * FROM vthcap_fa_prebill_temp WHERE \"prebillID\"='$prebillID' AND \"prebillIDMaster\"='$prebillID' and \"edittime\"='$edittime' $conplus");
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
		
		if($i==0){
			$olddateInvoice = $resdata["dateInvoice"]; // วันที่ใบแจ้งหนี้เก่า
			$oldnumberInvoice = $resdata["numberInvoice"]; // เลขที่ใบแจ้งหนี้เก่า
			$olduserDebtor= $resdata["userDebtor"]; // รหัสลูกหนี้เก่า
			$olduserSalebill = $resdata["userSalebill"]; // รหัสผู้ขายเก่า
			$oldtotalTaxInvoice = $resdata["totalTaxInvoice"]; // จำนวนเงินในบิลเก่า
			$olddateBill = $resdata["dateBill"]; // วันที่วางบิลเก่า
			$oldplaceReceiveChq = $resdata["placeReceiveChq"]; // สถานที่รับเช็คเก่า
			$oldnote = $resdata["note"]; // หมายเหตุ เก่า
		}
		
		//=========ตั้งค่าเริ่มต้น
		$color_userSalebillID="";
		$color_userDebtor="";
		$color_dateInvoice="";
		$color_numberInvoice="";
		$color_totalTaxInvoice="style=\"text-align:right\""; //ตั้งค่าเริ่มต้นให้ชิดขวา
		$color_dateBill=""; 
		$color_placeReceiveChq=""; 
		$color_note=""; 
		//=========สิ้นสุดตั้งค่าเริ่มต้น
		
		//เปรียบเทียบกับข้อมูลใหม่ว่าข้อมูลตรงกันหรือไม่
		if($i==1){ //เน้นสีที่แตกต่างเฉพาะที่เป็นข้อมูลใหม่เท่านั้น
			if($userSalebillID!=$olduserSalebill){ //กรณีรหัสผู้ขายไม่เหมือนกัน 
				$color_userSalebillID="style=\"background-color:#98F5FF;\"";
			}
			
			if($userDebtorID!=$olduserDebtor){ //กรณีลูกหนี้ไม่เหมือนกัน
				$color_userDebtor="style=\"background-color:#98F5FF;\"";
			}
			
			if($dateInvoice!=$olddateInvoice){ //กรณีวันที่ใบแจ้งหนี้ไม่เหมือนกัน
				$color_dateInvoice="style=\"background-color:#98F5FF;\"";
			}
			
			if($numberInvoice!=$oldnumberInvoice){ //กรณีเลขที่ใบแจ้งหนี้ไม่เหมือนกัน
				$color_numberInvoice="style=\"background-color:#98F5FF;\"";
			}
			
			if($totalTaxInvoice!=$oldtotalTaxInvoice){ //กรณีจำนวนเงินในบิลไม่เหมือนกัน
				$color_totalTaxInvoice="style=\"background-color:#98F5FF;text-align:right\"";
			}
			
			if($dateBill!=$olddateBill){ //กรณีวันที่วางบิลไม่เหมือนกัน
				$color_dateBill="style=\"background-color:#98F5FF;\"";
			}
						
			if($placeReceiveChq!=$oldplaceReceiveChq){ //กรณีสถานที่รับเช็คไม่เหมือนกัน
				$color_placeReceiveChq="style=\"background-color:#98F5FF;\"";
			}
			
			if($note!=$oldnote){ //กรณีหมายเหตุไม่เหมือนกัน
				$color_note="style=\"background-color:#98F5FF;\"";
			}
		}
		?>
		<td valign="top">
		<table width="100%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#CDB7B5">
		<tr><td colspan="3" bgcolor="#FFFFFF"><span style="font-weight:bold;background-color:yellow;"><?php echo $text; ?></span></td></tr>
		<tr><td colspan="3" bgcolor="<?php echo $color1; ?>">&nbsp;</td></tr>
		<tr align="left" bgcolor="<?php echo $color1;?>">
			<td align="right" width="156"><b>ผู้ขายบิล</b></td>
			<td width="10" align="center">:</td>
			<td bgcolor="<?php echo $color2;?>"><input type="text" size="50" name="userSalebill" id="userSalebill" value="<?php echo $userSalebill; ?>" <?php echo $color_userSalebillID; ?> readonly="true"></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>ชื่อลูกหนี้ของผู้ขายบิล</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" size="50" name="userDebtor" id="userDebtor" value="<?php echo $userDebtor; ?>" <?php echo $color_userDebtor; ?> readonly="true"></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>วันที่ใบแจ้งหนี้</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="dateInvoice" id="dateInvoice" size="15" value="<?php echo $dateInvoice;?>" <?php echo $color_dateInvoice; ?> readonly="true"> <b>(ปี ค.ศ.)</b></font></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>เลขที่ใบแจ้งหนี้</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="numberInvoice" id="numberInvoice" value="<?php echo $numberInvoice;?>"  <?php echo $color_numberInvoice; ?> readonly="true"></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>ยอดในใบแจ้งหนี้รวมภาษี</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="totalTaxInvoice" id="totalTaxInvoice" value="<?php echo number_format($totalTaxInvoice,2); ?>"  <?php echo $color_totalTaxInvoice; ?> readonly="true"> <b>บาท</b></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right"><b>วันที่วางบิล</b></td>
			<td align="center">:</td>
			<td bgcolor="<?php echo $color2; ?>"><input type="text" name="dateBill" id="dateBill" size="15"value="<?php echo $dateBill; ?>"  <?php echo $color_dateBill; ?> readonly="true"> <b>(ปี ค.ศ.)</b></td>
		</tr>
		<?php
		//วันที่นัดรับเช็ค
		$color1_1=$color1;
		$color2_2=$color2;
		
		if($i == 0)
		{
			$qryeach=pg_query("SELECT *  FROM thcap_fa_prebill_temp
			WHERE \"prebillIDMaster\"='$prebillID' and \"edittime\"='$edittime' and \"stsprocess\" <> 'D' $conplus ORDER BY \"prebillID\"");
		}
		else
		{
			$qryeach=pg_query("SELECT *  FROM thcap_fa_prebill_temp
			WHERE \"prebillIDMaster\"='$prebillID' and \"edittime\"='$edittime' $conplus ORDER BY \"prebillID\"");
		}
		
		$numeach=pg_num_rows($qryeach);
		$p=0;
		while($reseach=pg_fetch_array($qryeach)){	
			$p++;
			$prebillID_each=$reseach["prebillID"];
			$dateAssign_each=$reseach["dateAssign"];
			$taxInvoice_each=$reseach["taxInvoice"];
			
			$colorstyle="";
			$color_dateAssign="";
			$color_taxInvoice="style=\"text-align:right\"";
			if($i==1){ //กรณีเป็นข้อมูลใหม่
				$stsprocess=$reseach["stsprocess"]; //สถานะการแก้ไข 
				
				if($stsprocess=="I"){ //กรณีเป็นการเพิ่มข้อมูลใหม่
					$color1_1="#F4A460";
					$color2_2="";
				}else if($stsprocess=="D"){ //กรณีลบข้อมูล
					$color1_1="#EE6363";
					$color2_2="";
				}else{ //กรณีแก้ไขข้อมูล
					//ให้ดึงข้อมูลเก่ามาตรวจสอบ
					$qrychkdata=pg_query("SELECT \"dateAssign\",\"taxInvoice\"  FROM thcap_fa_prebill_temp 
					WHERE \"prebillID\"='$prebillID_each' and \"edittime\"='$edittimeold' ORDER BY \"prebillID\"");
					if($reschkdata=pg_fetch_array($qrychkdata)){
						list($dateAssign,$taxInvoice)=$reschkdata;
					}
					
					if($dateAssign!=$dateAssign_each){
						$color_dateAssign="style=\"background-color:#98F5FF;\"";
					}
					
					if($taxInvoice!=$taxInvoice_each){
						$color_taxInvoice="style=\"background-color:#98F5FF;text-align:right\"";
					}	
				}
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
				<input type="text" name="dateAssign[]" size="15" value="<?php echo $dateAssign_each; ?>" <?php echo $color_dateAssign; ?> readonly="true"> จำนวนเงิน <input type="text" name="recmoney[]" value="<?php echo number_format($taxInvoice_each,2); ?>" <?php echo $color_taxInvoice; ?> readonly="true">
				<?php } ?>
			</td>
		</tr>
		<?php
		} //จบแสดงวันที่ันัดรับเช็ค
		?>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right" valign="top"><b>สถานที่รับเช็ค</b></td>
			<td align="center" valign="top">:</td>
			<td bgcolor="<?php echo $color2; ?>"><textarea name="placeReceiveChq" id="placeReceiveChq" cols="30" rows="3" readonly="true"  <?php echo $color_placeReceiveChq; ?>><?php echo $placeReceiveChq; ?></textarea></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right" valign="top"><b>หมายเหตุ</b></td>
			<td align="center" valign="top">:</td>
			<td bgcolor="<?php echo $color2; ?>" valign="top"><textarea name="note" id="note" cols="30" rows="3" <?php echo $color_note; ?> readonly="true"><?php echo $note;?></textarea></td>
		</tr>
		<tr align="left" bgcolor="<?php echo $color1; ?>">
			<td align="right" valign="top"><b>ไฟล์บิล</b></td>
			<td align="center" valign="top">:</td>
			<td bgcolor="<?php echo $color2; ?>" valign="top">
			<?php
			$qryfile=pg_query("SELECT *  FROM thcap_fa_prebill_file WHERE \"prebillID\"='$prebillID' and \"edittime\"='$edittime' ORDER BY \"file\"");
			$t=1;
			while($resfile=pg_fetch_array($qryfile)){
				$filename=$resfile["file"]; //ชื่อบิล
				//ตรวจสอบว่ามีบิลนี้ในข้อมูลเก่าหรือไม่
				$qrychkfile=pg_query("select * from thcap_fa_prebill_file where file='$filename' and \"edittime\"='$edittimeold'");
				if(pg_num_rows($qrychkfile)==0){ //กรณีมีข้อมูล
					$txtfile="<font color=\"red\"><b>(ไฟล์ใหม่)</b></font>";
				}else{
					$txtfile="";
				}
				echo "<a class=\"pdforpic\" href=\"../upload/fa_prebill/$filename\" data-fancybox-group=\"gallery\" title=\"แสดงบิล\"><font color=blue><u>ไฟล์บิล $t</u></font></a> $txtfile <br>";
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
	
}
?>
</tr>
</table> 
</fieldset> 
<!--input type="button" value="อนุมัติ" onclick="approve('yes')">
<input type="button" value="ไม่อนุมัติ" onclick="approve('no')" /-->
<form name="my" method="post" action="process_fa.php">
	<input type="submit" name="appv" value="อนุมัติ" onclick=" return approve('yes')">
	<input type="submit" name="notappv" value="ไม่อนุมัติ" onclick=" return approve('no')" />	
	<input type="hidden" name="edittime" id="edittime" value="<?php echo $edittimenew; ?>">
	<input type="hidden" name="prebillID" id="prebillID" value="<?php echo $prebillID; ?>">
</form>
</body>
</html>