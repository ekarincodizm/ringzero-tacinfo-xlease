<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<?php
if($request!=1){
	include("../../config/config.php");
	$prebillID=$_GET["prebillID"];
	$prebillIDMaster=$_GET["prebillIDMaster"];
	$edittime=$_GET["edittime"];
	$stsprocess=$_GET["stsprocess"];
}

if($prebillIDMaster==""){
	$condition="\"prebillID\"='$prebillID'";
}else{
	$condition="\"prebillIDMaster\"='$prebillIDMaster'";
}

if($stsprocess=='I'){ //ถ้าเป็นการเพิ่มข้อมูลจะยังไม่มี prebillID จะำใช้เลข auto_id แทน
	$con2="AND \"prebillIDMaster\"=\"auto_id\"::text";
}else{
	$con2="AND \"prebillIDMaster\"=\"prebillID\"::text";
}

$close=$_GET["close"];

$qry=pg_query("SELECT \"userSalebill\",\"userDebtor\",\"userSalebillName\" as \"nameuserSalebill\",
\"userDebtorName\" as \"nameuserDebtor\",\"totalTaxInvoice\", \"dateInvoice\", \"numberInvoice\",\"dateBill\",\"placeReceiveChq\",\"note\",
\"userDebtor\",\"userSalebill\" as \"CusID2\", \"prebillIDMaster\",\"statusApp\" 
FROM \"vthcap_fa_prebill_temp\" 
where $condition and \"edittime\"='$edittime' $con2");
while($result=pg_fetch_array($qry)){
	$userSalebill_id = $result["userSalebill"];
	$userDebtor_id = $result["userDebtor"];
	$userSalebill=$result["nameuserSalebill"];
	$userDebtor=$result["nameuserDebtor"];
	$dateInvoice=$result["dateInvoice"];
	$numberInvoice=$result["numberInvoice"];
	$dateBill=$result["dateBill"];
	$placeReceiveChq=$result["placeReceiveChq"];
	$note=$result["note"];
	$CusID=$result["userDebtor"];
	$CusID2=$result["CusID2"];
	$totalTaxInvoice=$result["totalTaxInvoice"];
	$prebillIDMaster=$result["prebillIDMaster"];
	$statusApp=$result["statusApp"]; //สถานะการอนุมัติ
	
	$qry_regis_corp = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\"='$userDebtor_id'");
	$regis_corp11 = pg_fetch_result($qry_regis_corp,0);
	
	$qry_regis_corp = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\"='$userSalebill_id'");
	$regis_corp22 = pg_fetch_result($qry_regis_corp,0);
}

//ดึงรายละเอียดผู้บันทึกการติดต่อ (ผู้อนุมัติ)
$qryadduser=pg_query("select a.\"dateAssign\",b.\"fullname\",a.\"addStamp\",\"taxInvoice\" from thcap_fa_prebill_temp a left join \"Vfuser\" b on a.\"addUser\" = b.\"id_user\" where $condition and \"edittime\"='$edittime' order by a.\"dateAssign\"");

?>
<table width="750" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>       
		<div class="header"><h1></h1></div>
		<div class="wrapper">
			<div>
			<fieldset><legend><B>รายละเอียดบิลขอสินเชื่อ</B></legend>
			<table width="100%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#CDB7B5">
			<?php
				while($resadduser=pg_fetch_array($qryadduser)){
				?>
				<tr bgcolor="#FAF0E6" align="center">
					<td width="">วันที่นัดรับเช็ค: <b><?php echo $resadduser["dateAssign"]; ?></td>
					<td>ผู้บันทึกข้อมูล: <b><?php echo $resadduser["fullname"]; ?></td>
					<td>วัน/เวลาที่บันทึก: <b><?php echo $resadduser["addStamp"]; ?></b></td>														
				</tr>
				<?php
				}
				?>
			</table><br>
			<table width="80%" border="0" cellSpacing="0" cellPadding="0" align="center" bgcolor="#CDB7B5">
			<tr><td colspan="3" bgcolor="#EED5D2">&nbsp;</td></tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" width="156"><b>ผู้ขายบิล</b></td>
				<td width="10" align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" size="40" name="userSalebill" value="<?php echo $userSalebill;?>" readonly="true"> <a onclick="javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=<?php echo $regis_corp22; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"><img src="images/detail.gif" width="19" height="19" border="0" style="cursor:pointer;"></a></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>ชื่อลูกหนี้ของผู้ขายบิล</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" size="40" name="userDebtor" value="<?php echo $userDebtor;?>" readonly="true"> <a onclick="javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=<?php echo $regis_corp11; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')"><img src="images/detail.gif" width="19" height="19" border="0" style="cursor:pointer;"></a></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>วันที่ใบแจ้งหนี้</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="dateInvoice" value="<?php echo $dateInvoice;?>" size="15" readonly="true" style="text-align:center"> </td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>เลขที่ใบแจ้งหนี้</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="numberInvoice" value="<?php echo $numberInvoice;?>" readonly="true"></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>ยอดในใบแจ้งหนี้รวมภาษี</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="totalTaxInvoice" value="<?php echo number_format($totalTaxInvoice,2);?>" style="text-align:right" value="0.00" readonly="true"> <b>บาท</b></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right"><b>วันที่วางบิล</b></td>
				<td align="center">:</td>
				<td bgcolor="#FFE4E1"><input type="text" name="dateBill" value="<?php echo $dateBill;?>" size="15" readonly="true" style="text-align:center"></td>
			</tr>
			<?php
			$qryadd=pg_query("select \"dateAssign\",\"taxInvoice\" from thcap_fa_prebill_temp where $condition and \"edittime\"='$edittime' order by \"dateAssign\"");
			while($resadd=pg_fetch_array($qryadd)){
			?>
			<tr align="left" bgcolor="#EECBAD">
				<td align="right"><b>วันที่นัดรับเช็ค</b></td>
				<td align="center">:</td>
				<td bgcolor="#EECBAD"><input type="text" name="dateAssign" style="text-align:center" value="<?php echo $resadd["dateAssign"];?>" size="15" readonly="true"> <b>จำนวนเงิน :</b> <input type="text" name="taxInvoice" style="text-align:right" value="<?php echo number_format($resadd["taxInvoice"],2);?>" size="15" readonly="true"> <b>บาท</b></td>
			</tr>
			<?php
			}
			?>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" valign="top"><b>สถานที่รับเช็ค</b></td>
				<td align="center"valign="top">:</td>
				<td bgcolor="#FFE4E1" valign="top"><textarea name="placeReceiveChq" cols="50" rows="3" readonly="true"><?php echo $placeReceiveChq;?></textarea></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" valign="top"><b>หมายเหตุ</b></td>
				<td align="center" valign="top">:</td>
				<td bgcolor="#FFE4E1" valign="top"><textarea name="note" cols="50" rows="3" readonly="true"><?php echo $note;?></textarea></td>
			</tr>
			<tr align="left" bgcolor="#EED5D2">
				<td align="right" valign="top"><b>ไฟล์สแกนบิล</b></td>
				<td align="center" valign="top">:</td>
				<td bgcolor="#FFE4E1" valign="top">
				<?php
				//ค้นหาไฟล์ scan จากตาราง thcap_fa_prebill_file 
				if($statusApp=='2'){ //ถ้าสถานะอนุมัิติ จะยังไม่มีเลข prebillID
					$val="\"auto_temp\"";
				}else{
					$val="\"prebillID\"";
				}
				
				$qryfile=pg_query("SELECT *  from thcap_fa_prebill_file  where $val='$prebillIDMaster' and \"edittime\"='$edittime'");
				$numfile=pg_num_rows($qryfile);
				
				$i=1;
				while($resfile=pg_fetch_array($qryfile)){
					$file2=$resfile["file"];					
					
					if($file2!=""){
						$realpath = redirect($_SERVER['PHP_SELF'],'nw/upload/fa_prebill/'.$file2);
						echo "<a href=\"$realpath\" target=\"_blank\"><img src=\"images/open.png\" width=18 heigh=18 title=\"ไฟล์ $i\"></a>";
						if($i%5==0){
							echo "<br>";
						}
						$i++;
					}else{
						echo "พบปัญหาในการอัพโหลด";
					}
				}
				if($numfile==0){
					echo "<img src=\"images/noimage.png\" width=20 heigh=20 title=\"ไม่พบไฟล์\">";
				}
				?>
				</td>
			</tr>
			<tr><td colspan="3" bgcolor="#EED5D2">&nbsp;</td></tr>
			</table>
			</fieldset> 
			</div>
		</div>
	</td>
</tr>
</table>  

<div align="center">
<?php if($close==1){ echo "<input type=\"button\" value=\"   ปิด   \" onclick=\"window.close();\">"; }?>
</div>
</body>
</html>