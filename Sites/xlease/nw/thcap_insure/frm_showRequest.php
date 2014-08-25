<?php
session_start();
include("../../config/config.php");
$id_user=$_SESSION["av_iduser"];
$auto_id = $_GET['auto_id'];
$method=$_GET["method"];
	
$qrytemp=pg_query("SELECT a.\"ContractID\", b.\"full_name\" as cus1, c.\"full_name\" as cus2, d.\"full_name\" as cus3, e.\"full_name\" as cus4, \"addrCus\", \"startDate\", 
	\"endDate\", \"userBenefit\", \"userNotify\", \"dateNotify\", \"securdeID\", \"checkchipID\", \"statusInsure\",\"addrDeed\",\"resultnotapp\"
	FROM thcap_insure_temp a
	left join \"VSearchCus\" b on a.\"CusID1\"=b.\"CusID\"
	left join \"VSearchCus\" c on a.\"CusID2\"=c.\"CusID\"
	left join \"VSearchCus\" d on a.\"CusID3\"=d.\"CusID\"
	left join \"VSearchCus\" e on a.\"CusID4\"=e.\"CusID\"
	where auto_id='$auto_id'");
	
list($ContractID,$cus1,$cus2,$cus3,$cus4,$addrCus,$startDate,$endDate,$userBenefit,$userNotify,$dateNotify,$securdeID,$checkchipID,$statusInsure,$addrDeed,$resultnotapp)=pg_fetch_array($qrytemp);
if($statusInsure=="0"){
	$txtreq="ประกันใหม่";
}else if($statusInsure=="1"){
	$txtreq="ต่ออายุ";
}else if($statusInsure=="2"){
	$txtreq="แก้ไขข้อมูลให้ตรงกับกรมธรรม์";
}else if($statusInsure=="3"){
	$txtreq="แก้ไขข้อมูลโดยการสลักหลัง";
}	
	//หาข้อมูลค่าเบี้ยมาแสดง		
	$qrychip=pg_query("SELECT \"refDeedContract\",\"costBuilding\", \"costFurniture\", \"costEngine\", 
	\"costStock\", \"textOther\", \"costOther\", \"insureSpecial\", \"totalChip\", 
	\"numberQ\" FROM thcap_insure_checkchip where auto_id='$checkchipID'");
	list($refDeedContract,$costBuilding, $costFurniture, $costEngine, $costStock, $textOther, $costOther, $insureSpecial, $totalChip, $numberQ)=pg_fetch_array($qrychip);
	$summoney=$costBuilding+$costFurniture+$costEngine+$costStock+$costOther;
	
	//หาค่า $refDeedContract ได้ดังนี้
	if($statusInsure!="0"){
		$qrysecur=pg_query("select \"securID\",\"addrDeed\" from \"thcap_insure_main\" a
		left join thcap_insure_temp b on a.\"auto_tempID\"=b.\"auto_id\"
		left join \"nw_securities_detail\" c on b.\"securdeID\"=c.\"securdeID\"
		where \"ContractID\"='$ContractID'");
		
		list($refDeedContract,$addrDeed2)=pg_fetch_array($qrysecur);
	}
	//ดึงรายละเอียดในส่วนของ checker
	$qrychecker=pg_query("SELECT \"securdeID\", feature, feature_other, height, address, 
		wall_brick, wall_wood_brick, wall_wood, wall_other, wall_other_detail, 
		ground_top_con, ground_top_wood, ground_top_parquet, ground_top_ceramic, ground_top_other, ground_top_other_detail, 
		roof_frame_iron, roof_frame_con, roof_frame_wood, roof_frame_unknow, roof_frame_other, roof_frame_other_detail, 
		roof_zine, roof_deck, roof_tile_duo, roof_tile_monern, roof_other, roof_other_detail, 
		quan_cave, quan_unit, quan_room,quan_floor, floor_number, build_inside_area, 
		useful_home, useful_commerce, useful_rent, useful_stored, useful_industry, useful_agriculture, useful_other, useful_other_detail
		FROM nw_securities_detail where \"securID\"='$refDeedContract'");
	list($securdeID, $feature, $feature_other, $height, $address,
		$wall_brick, $wall_wood_brick, $wall_wood, $wall_other, $wall_other_detail, 
		$ground_top_con, $ground_top_wood, $ground_top_parquet, $ground_top_ceramic, $ground_top_other, $ground_top_other_detail, 
		$roof_frame_iron, $roof_frame_con, $roof_frame_wood, $roof_frame_unknow, $roof_frame_other, $roof_frame_other_detail, 
		$roof_zine, $roof_deck, $roof_tile_duo, $roof_tile_monern, $roof_other, $roof_other_detail, 
		$quan_cave, $quan_unit, $quan_room,$quan_floor, $floor_number, $build_inside_area, 
		$useful_home, $useful_commerce, $useful_rent, $useful_stored, $useful_industry, $useful_agriculture, $useful_other, $useful_other_detail)=pg_fetch_array($qrychecker);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"><link>
	<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<fieldset><legend><B>อนุมัติคำขอ <?php echo $ContractID;?> (<?php echo $txtreq;?>)</B></legend>
<form name="form1" method="post" action="process_insure.php">
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F2FBFF">
<tr height="25">
    <td colspan="4"></td>
</tr>
<tr height="25" bgcolor="#DFF5FF">
    <td width="50%">
		<table width="100%" border="0">
			<tr><td colspan="2"><b>1. ชื่อผู้เอาประกัน</b></td></tr>
			<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>1.<input type="text" name="CusID1" id="CusID1" size="40" value="<?php echo $cus1;?>" readonly="true"></b></td></tr>
			<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>2.<input type="text" name="CusID2" id="CusID2" size="40" value="<?php echo $cus2;?>" readonly="true"></b></td></tr>
			<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>3.<input type="text" name="CusID3" id="CusID3" size="40" value="<?php echo $cus3;?>" readonly="true"></b></td></tr>
			<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>4.<input type="text" name="CusID4" id="CusID4" size="40" value="<?php echo $cus4;?>" readonly="true"></b></td></tr>			
			<?php
			//ค้นหารายชื่อเพิ่มเติมว่ามีหรือไม่
			$i=5;
			$qryadd=pg_query("select \"full_name\" from thcap_insure_cus a
			left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\" where \"tempID\"='$auto_id'");
			while($resadd=pg_fetch_array($qryadd)){
				list($full_name)=$resadd;
				echo "<tr><td colspan=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$i.<input type=\"text\"  size=\"40\" value=\"$full_name\" readonly=\"true\"></b></td></tr>";	
			$i++;
			}
			?>
			<tr><td colspan="2"><hr></td></tr>
			<tr><td valign="top"><b>ที่อยู่ :</b></td><td><textarea name="addrCus" cols="40" rows="4" readonly="true"><?php echo $addrCus;?></textarea></td></tr>
		</table>
	</td>
    <td valign="top">
		<table width="100%" border="0">
			<tr><td><b>สถานที่ตั้งทรัพย์สินที่เอาประกันภัย</b></td></tr>
			<tr><td valign="top"><textarea cols="60" rows="4" readonly="true"><?php echo $addrDeed;?></textarea></td></tr>
		</table>
	</td>
</tr>
<tr height="25">
    <td colspan="2"><b>2. ระยะเวลาประกันภัย</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เริ่มวันที่ <input type="text" name="startDate" id="startDate" readonly="true" style="text-align:center" value="<?php echo $startDate;?>">  เวลา 16.00 สิ้นสุดวันที่  <input type="text" name="endDate" id="endDate" readonly="true" style="text-align:center" value="<?php echo $endDate;?>"> เวลา 16.00 น.</td>
</tr>

<tr height="25">
    <td colspan="2"><b>3. จำนวนเงินเอาประกันภัยตามกรมธรรม์ฉบับนี้  <input type="text" value="<?php echo number_format($totalChip,2);?>" readonly="true" style="text-align:right"></b> <b>เลขคิว</b><input type="text" value="<?php echo $numberQ;?>" readonly="true" style="text-align:center"></td>
</tr>
<tr height="25" bgcolor="#DFF5FF">
    <td colspan="2"><b>4. จำนวนเงินเอาประกันภัยทั้งสิ้น</b><br>
		<table width="100%" border="0">
			<tr><td width="60%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if($costBuilding!="") echo "checked";?> disabled="true"> สิ่งปลูกสร้าง (รากฐานฯไม่รวม)</td><td><input type="text" value="<?php echo number_format($costBuilding,2);?>" readonly="true" style="text-align:right"></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if($costFurniture!="") echo "checked";?> disabled="true"> เฟอร์นิเจอร์ เครื่องตกแต่งติดตั้งตรึงตรา และของใช้ต่างๆ </td><td><input type="text" value="<?php echo number_format($costFurniture,2);?>" readonly="true" style="text-align:right"></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if($costEngine!="") echo "checked";?> disabled="true"> เครื่องจักร</td><td><input type="text" value="<?php echo number_format($costEngine,2);?>" readonly="true" style="text-align:right"></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if($costStock!="") echo "checked";?> disabled="true"> สต๊อกสินค้า</td><td><input type="text" value="<?php echo number_format($costStock,2);?>" readonly="true" style="text-align:right"></tr>
			<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php if($costOther!="") echo "checked";?> disabled="true"> อื่นๆ...<?php echo $textOther;?></td><td><input type="text" value="<?php echo number_format($costOther,2);?>" readonly="true" style="text-align:right"></tr>		
			<tr><td align="right"><b>รวมทุนประกันภัยทั้งสิ้น</b></td><td><input type="text" value="<?php echo number_format($summoney,2);?>" readonly="true" style="text-align:right"></tr>		
			<tr>
				<td colspan="2" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ภัยเพิ่มพิเศษ</b><br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea cols="80" rows="4" readonly="true"><?php echo $insureSpecial?></textarea>
				</td>
			</tr>		
			<tr>
				<td colspan="2" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ผู้รับผลประโยชน์</b> <input type="text" name="userBenefit" value="<?php echo $userBenefit;?>" size="60" readonly="true"></td>
			</tr>
		</table>
	</td>
</tr>
<?php
if($useful_home=="1"){
	$txtuse="ที่อยู่อาศัย";
}else if($useful_commerce=="1"){
	$txtuse="พาณิชยกรรม";
}else if($useful_rent=="1"){
	$txtuse="ให้เช่า";
}else if($useful_stored=="1"){
	$txtuse="เก็บไว้เฉยๆ";
}else if($useful_industry=="1"){
	$txtuse="อุตสาหกรรม";
}else if($useful_agriculture=="1"){
	$txtuse="เกษตรกรรม";
}else if($useful_other=="1"){
	$txtuse=$useful_other_detail;
}

list($before,$behide)=explode(".",$height);
if($behide=="00"){
	$height2=$before;
}else{
	$height2=$height;
}
if($feature=="1"){
	$txtfeature="ตึกแถว $height2 ชั้น";
}else if($feature=="2"){
	$txtfeature="ทาวน์เฮ้าส์ $height2 ชั้น";
}else if($feature=="3"){
	$txtfeature="บ้านเดี่ยวตึก $height2 ชั้น";
}else if($feature=="4"){
	$txtfeature="บ้านแฝด $height2 ชั้น";
}else if($feature=="5"){
	$txtfeature="อาคารพาณิชย์ $height2 ชั้น";
}else{
	$txtfeature="$feature_other $height2 ชั้น";
}
?>
<tr bgcolor="#A5E2FA">
    <td colspan="2"><b>5. รายละเอียดของสิ่งปลูกสร้างที่เอาประกันและหรือที่เก็บหรือติดตั้งทรัพย์สินที่เอาประกันภัย</b><br>
		<table width="100%" border="0" cellSpacing="1" cellPadding="1" bgcolor="#A5E2FA">
			<tr bgcolor="#47C6F5">
				<th>จำนวนชั้น</th>
				<th>ฝาผนังด้านนอกเป็น</th>
				<th>พื้นชั้นบนเป็น</th>
				<th>โครงหลังคาเป็น</th>
				<th>หลังคาเป็น</th>
				<th>จำนวนคูหา/หลัง</th>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td valign="top">
					<table width="100%">
						<tr><td align="center"><?php echo $txtfeature;?></td></tr>
					</table>
				</td>
				<td valign="top">
					<table width="100%">
						<?php if($wall_brick=="1"){ echo "<tr><td width=15></td><td>- ก่ออิฐฯ</td></tr>";}?>
						<?php if($wall_wood_brick=="1"){ echo "<tr><td width=15><td>- ก่ออิฐฯ/ไม้</td></tr>";}?>
						<?php if($wall_wood=="1") echo "<tr><td width=15><td>- ไม้</td></tr>";?>
						<?php if($wall_other=="1") echo "<tr><td width=15><td>- $wall_other_detail</td></tr>";?>					
					</table>
				</td>
				<td valign="top">
					<table width="100%">
						<?php if($ground_top_con=="1") echo "<tr><td width=15><td>- คอนกรีต</td></tr>";?>
						<?php if($ground_top_wood=="1") echo "<tr><td width=15><td>- ไม้</td></tr>";?>
						<?php if($ground_top_parquet=="1") echo "<tr><td width=15><td>- ปาเก้</td></tr>";?>
						<?php if($ground_top_ceramic=="1") echo "<tr><td width=15><td>เซรามิค</td></tr>";?>
						<?php if($ground_top_other=="1") echo "<tr><td width=15><td>$ground_top_other_detail</td></tr>";?>
					</table>
				</td>
				<td valign="top">
					<table width="100%">
						<?php if($roof_frame_iron=="1") echo "<tr><td width=15><td>- เหล็ก</td></tr>";?>
						<?php if($roof_frame_con=="1") echo "<tr><td width=15><td>- คอนกรีต</td></tr>";?>
						<?php if($roof_frame_wood=="1") echo "<tr><td width=15><td>- ไม้</td></tr>";?>
						<?php if($roof_frame_other=="1") echo "<tr><td width=15><td>- $roof_frame_other_detail</td></tr>";?>					
						<?php if($roof_frame_unknow=="1") echo "<tr><td width=15><td>- ไม่สามารถตรวจสอบได้</td></tr>";?>									
					</table>
				</td>
				<td valign="top">
					<table width="100%">
						<?php if($roof_zine=="1") echo "<tr><td width=15><td>- สังกะสี</td></tr>";?>
						<?php if($roof_deck=="1") echo "<tr><td width=15><td>- ดาดฟ้า</td></tr>";?>
						<?php if($roof_tile_duo=="1") echo "<tr><td width=15><td>- กระเบื้องลอนคู่</td></tr>";?>
						<?php if($roof_tile_monern=="1") echo "<tr><td width=15><td>- กระเบื้องโมเนียร์</td></tr>";?>
						<?php if($roof_other=="1") echo "<tr><td width=15><td>- $roof_other_detail</td></tr>";?>					
					</table>
				</td>
				<td valign="top">
					<table width="100%">
						<?php if($quan_cave>0){ echo "<tr><td width=15><td>$quan_cave คูหา</td></tr>";}?>
						<?php if($quan_unit>0){ echo "<tr><td width=15><td>$quan_unit หลัง</td></tr>";}?>
						<?php if($quan_room>0){ echo "<tr><td width=15><td>$quan_room ห้อง</td></tr>";}?>
						<?php if($quan_floor>0){ echo "<tr><td width=15><td>$quan_floor ชั้น</td></tr>";}?>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr height="25">
    <td colspan="2"><b>6. พื้นที่ภายในอาคาร  <input type="text" value="<?php echo $build_inside_area;?>" readonly="true" style="text-align:center"> ตรม.</b></td>
</tr>
<tr height="25">
    <td colspan="2"><b>7. สถานที่ใช้เป็น <input type="text" value="<?php echo $txtuse;?>" readonly="true" size="40"></b></td>
</tr>
<?php
	//ชื่อผู้แจ้งลูกค้า
	$qrynameuser=pg_query("select \"fname\" from \"fuser\" where \"id_user\"='$id_user'");
	list($fname)=pg_fetch_array($qrynameuser);
?>
<tr height="25">
    <td colspan="2"><b>8. ชื่อผู้แจ้งลูกค้า <input type="text" name="userNotify" size="40" value="<?php echo "$userNotify";?>" readonly="true"> วันที่ <input type="text" name="dateNotify" value="<?php echo $dateNotify; ?>" readonly="true" style="text-align:center;"></b></td>
</tr>
<?php
	if($method=="noapp"){
?>
	<tr bgcolor="#FFCCCC">
		<td colspan="2"><b>:: เหตุผลที่ไม่อนุมัติ ::</b></td>
	</tr>
	<tr bgcolor="#FFCCCC">
		<td colspan="2"><textarea cols="60" rows="5" readonly="true"><?php echo $resultnotapp?></textarea></td>
	</tr>
<?php
	}
?>
<tr height="50" bgcolor="#FFFFFF">
    <td colspan="2" align="center">
		<input name="btnButton2" type="reset" value="  ปิด  " onclick="javascript:window.close();" />
	</td>
</tr>
</table>
</form>
</fieldset> 
</body>
</html>