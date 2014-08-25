<?php 
session_start();  
include("../../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$id = pg_escape_string($_GET["id"]);
$view = pg_escape_string($_GET["view"]);
if($id=="")
{
	$id = pg_escape_string($_POST["tpID2"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) จัดการประเภทค่าใช้จ่าย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script src="../../jqueryui/js/number.js" type="text/javascript"></script>

<script language=javascript>
function checkWHT(){
	var wht = document.getElementById('ableWHT')
	if(wht.checked==false){
		document.getElementById("curWHTRate").readOnly=true;
	} else {
		document.getElementById("curWHTRate").readOnly=false;
	}
}
</script>
</head>
<body>

<?php
// รายการใหม่
$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay_temp\" where \"tpAutoID\" = '$id' ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name))
{
	$tpID_temp = $res_name["tpID"];
	$tpCompanyID_temp = $res_name["tpCompanyID"];
	$tpConType_temp = $res_name["tpConType"];  
	$tpDesc_temp = $res_name["tpDesc"];
	$tpFullDesc_temp = $res_name["tpFullDesc"];
	$ableB_temp = $res_name["ableB"];
	$ableDiscount_temp = $res_name["ableDiscount"];
	$ableWaive_temp = $res_name["ableWaive"];
	$ableVAT_temp = $res_name["ableVAT"];
	$ableWHT_temp = $res_name["ableWHT"];
	
	$appvID1 = $res_name["appvID1"];
	
	//By Por
	$ableSkip_temp = $res_name["ableSkip"];
	$ablePartial_temp = $res_name["ablePartial"];
	$curWHTRate_temp = $res_name["curWHTRate"];
	$isServices_temp = $res_name["isServices"];
	$tpSort_temp = $res_name["tpSort"];
	$tpType_temp = trim($res_name["tpType"]); // เงื่อนไขในการเก็บ
	$tpRanking_temp = $res_name["tpRanking"];
	//End By Por
	
	//By Boz (เลียนแบบข้างบน)
	$whoSeen_temp = $res_name["whoSeen"]; //ALL-เปิดให้เห็นทุกส่วนงาน
	$tpRefType_temp = trim($res_name["tpRefType"]); //รูปแบบ Ref
	$isSubsti_temp = $res_name["isSubsti"]; //substitutional - รับแทน เช่น รับแทนค่าประกัน
	$isLeasing_temp = $res_name["isLeasing"];
	//End By Boz
		
	$curSBTRate_temp = $res_name["curSBTRate"];
	$isLockedVat_temp = $res_name["isLockedVat"];
	$ableInvoice_temp = $res_name["ableInvoice"];
	$curLTRate_temp = $res_name["curLTRate"];
}

// รายการเดิม
if($view != "v")
{ // ถ้าเป็นการอนุมัติแก้ไขรายการ ให้เปรียบเทียบข้อมูลจากตารางจริง
	$qry_name = pg_query("SELECT * FROM account.\"thcap_typePay\" where \"tpID\" = '$tpID_temp' ");
	$rows = pg_num_rows($qry_name);
}
elseif($view == "v")
{ // ถ้าเป็นการดูประวัติรายการ ให้เปรียบเทียบจากรายการที่อนุมัติก่อนหน้านั้น 1 รายการ
	$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay_temp\" where \"tpID\" = '$tpID_temp'
						and \"tpAutoID\" = (select max(\"tpAutoID\") from account.\"thcap_typePay_temp\" where \"tpID\" = '$tpID_temp' and \"tpAutoID\" < '$id')
						and \"appvStatus1\" = '1' and \"appvStatus2\" = '1' ");
	$rows = pg_num_rows($qry_name);
	
	if($rows == 0)
	{ // ถ้าไม่พบรายการ ให้ไปดูในตารางจริง
		$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay\" where \"tpID\" = '$tpID_temp' ");
		$rows = pg_num_rows($qry_name);
	}
}
while($res_name=pg_fetch_array($qry_name))
{
	$tpID = $res_name["tpID"];
	$tpCompanyID = $res_name["tpCompanyID"];
	$tpConType = $res_name["tpConType"];  
	$tpDesc = $res_name["tpDesc"];
	$tpFullDesc = $res_name["tpFullDesc"];
	$ableB = $res_name["ableB"];
	$ableDiscount = $res_name["ableDiscount"];
	$ableWaive = $res_name["ableWaive"];
	$ableVAT = $res_name["ableVAT"];
	$ableWHT = $res_name["ableWHT"];
	
	//By Por
	$ableSkip = $res_name["ableSkip"];
	$ablePartial = $res_name["ablePartial"];
	$curWHTRate = $res_name["curWHTRate"];
	$isServices = $res_name["isServices"];
	$tpSort = $res_name["tpSort"];
	$tpType = trim($res_name["tpType"]); // เงื่อนไขในการเก็บ
	$tpRanking = $res_name["tpRanking"];
	//End By Por
	
	//By Boz (เลียนแบบข้างบน)
	$whoSeen = $res_name["whoSeen"]; //ALL-เปิดให้เห็นทุกส่วนงาน
	$tpRefType = trim($res_name["tpRefType"]); //รูปแบบ Ref
	$isSubsti = $res_name["isSubsti"]; //substitutional - รับแทน เช่น รับแทนค่าประกัน
	$isLeasing = $res_name["isLeasing"];
	//End By Boz
		
	$curSBTRate= $res_name["curSBTRate"];
	$isLockedVat= $res_name["isLockedVat"];
	$ableInvoice= $res_name["ableInvoice"];
	$curLTRate= $res_name["curLTRate"];
}

// ตรวจสอบว่าเป็นการเพิ่มรายการ หรือการแก้ไขรายการ
if($tpID == "")
{
	$addOrEdit = "N"; // เพิ่มรายการใหม่
}
else
{
	$addOrEdit = "U"; // แก้ไขรายการ
}
?>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center" valign="top" style="background-repeat:repeat-y">
			<div class="wrapper">
				<!-- <div align="right"><a href="frm_thcap_show.php"><img src="images/full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div> -->
				<form id="frm_1" name="frm_1" method="post" action="process_thcap_edit.php">
					<fieldset><legend><B>ประเภทค่าใช้จ่าย</B></legend>
						<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
						   <tr align="left">
							  <td width="30%"><b>รหัสประเภทค่าใช้จ่าย <font color="red">*</font></b></td>
							  <td width="70%" class="text_gray"><input type="text" name="tpID" size="28" value="<?php echo $tpID_temp; ?>" readonly <?php if($tpID_temp != $tpID && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpID\" ";} ?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>รหัสประเภทบริษัท <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpCompanyID" size="28" value="<?php echo $tpCompanyID_temp; ?>" readonly <?php if($tpCompanyID_temp != $tpCompanyID && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpCompanyID\" ";} ?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>รหัสประเภทสัญญา <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpConType" size="28" value="<?php echo $tpConType_temp; ?>" readonly <?php if($tpConType_temp != $tpConType && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpConType\" ";} ?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>ชื่อประเภทค่าใช้จ่าย <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpDesc" size="28" value="<?php echo $tpDesc_temp; ?>" readonly <?php if($tpDesc_temp != $tpDesc && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpDesc\" ";} ?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>คำอธิบายประเภทค่าใช้จ่าย</b></td>
							  <td colspan="3" class="text_gray"><textarea name="tpFullDesc" readonly <?php if($tpFullDesc_temp != $tpFullDesc && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpFullDesc\" ";} ?>><?php echo $tpFullDesc_temp; ?></textarea></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถบันทึก B</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" <?php if($ableB_temp=="1"){echo "checked=\"checked\" ";} ?> name="ableB" size="20" disabled><?php if($ableB_temp != $ableB && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถทำส่วนลด</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" <?php if($ableDiscount_temp=="1"){echo "checked=\"checked\" ";} ?> name="ableDiscount" size="20" disabled><?php if($ableDiscount_temp != $ableDiscount && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถยกเว้น</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" <?php if($ableWaive_temp=="1"){echo "checked=\"checked\" ";} ?> name="ableWaive" size="20" disabled><?php if($ableWaive_temp != $ableWaive && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
						   <tr align="left">
							  <td><b>มี VAT</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" <?php if($ableVAT_temp=="1"){echo "checked=\"checked\" ";} ?> name="ableVAT" size="20" disabled><?php if($ableVAT_temp != $ableVAT && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
						   <tr align="left">
							  <td><b>มี ภาษีหัก ณ ที่จ่าย</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" <?php if($ableWHT_temp=="1"){echo "checked=\"checked\" ";} ?> name="ableWHT" id="ableWHT" size="20" onchange="checkWHT();" disabled><?php if($ableWHT_temp != $ableWHT && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
							<!-- เพิ่มเติม By Por-->
						   <tr align="left">
							  <td><b>สามารถข้ามเรื่องอันดับการจ่ายได้</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableSkip" size="20" <?php if($ableSkip_temp=="1") echo "checked";?> disabled><?php if($ableSkip_temp != $ableSkip && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถจ่ายบางส่วนได้</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ablePartial" size="20" <?php if($ablePartial_temp=="1") echo "checked";?> disabled><?php if($ablePartial_temp != $ablePartial && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
						   <tr align="left">
							  <td><b>% อัตราภาษีหัก ณ ที่จ่ายปัจจุบันของค่าใช้จ่ายประเภทนี้</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="curWHTRate" id="curWHTRate" size="20" value="<?php echo $curWHTRate_temp;?>" onkeypress="check_num(event);" readonly <?php if($curWHTRate_temp != $curWHTRate && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$curWHTRate\" ";} ?>> (ถ้ามีภาษีหัก ณ ที่จ่าย)</td>
						   </tr>
						   <tr align="left">
							  <td><b>สินค้าหรือค่าบริการ</b></td>
							  <td colspan="3" class="text_gray">
								<select name="isServices" disabled <?php if($isServices_temp != $isServices && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" ";} ?>>
									<option value="0" <?php if($isServices_temp=="0"){ echo "selected";}?>>ไม่เข้าข่ายทั้ง ค่าสินค้า หรือบริการ</option>
									<option value="1" <?php if($isServices_temp=="1"){ echo "selected";}?>>บริการ ยึดการคิด VAT ณ วันจ่าย (เพราะเป็นค่าบริการ VAT ส่งเมื่อจ่าย)</option>
									<option value="2" <?php if($isServices_temp=="2"){ echo "selected";}?>>สินค้า ยึดการคิด VAT ตาม invoice (เพราะเป็นสินค้า VAT ส่งหลวงแล้ว)</option>
								</select>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>เรียงอันดับการแสดงผล</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpSort" size="20" value="<?php echo $tpSort_temp;?>" onkeypress="check_num(event);" readonly <?php if($tpSort_temp != $tpSort_temp && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$tpSort\" ";} ?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>ประเภท <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray">
								<select name="tpType" disabled <?php if($tpType_temp != $tpType && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" ";} ?>>
									<option value="NONE" <?php if($tpType_temp=="NONE"){ echo "selected";}?>>NONE-ไม่มีเงื่อนไขในการเก็บ</option>
									<option value="LOCKED" <?php if($tpType_temp=="LOCKED"){ echo "selected";}?>>LOCKED-ไม่มีเงื่อนไขในการเก็บ แต่ว่าไม่ให้เพิ่มหนี้เข้าไปได้โดยทั่วไป</option>
									<option value="FIXED" <?php if($tpType_temp=="FIXED"){ echo "selected";}?>>FIXED-เก็บค่าตายตัวทุกสัญญาเหมือนกันหมด</option>
									<option value="VAR" <?php if($tpType_temp=="VAR"){ echo "selected";}?>>VAR-เก็บค่าไม่เหมือนกันแปรผันตามสัญญา</option>
									<option value="PER" <?php if($tpType_temp=="PER"){ echo "selected";}?>>PER-เก็บค่าเป็น percent จากยอดที่สนใจ</option>
								</select>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>ลำดับการจ่าย <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpRanking" size="20" value="<?php echo $tpRanking_temp;?>" onkeypress="check_num(event);" readonly <?php if($tpRanking_temp != $tpRanking && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" ";} ?>></td>
						   </tr>
						   <!-- จบเพิ่มเติม By Por-->
						   <!-- เพิ่มเติม By Boz-->
						   <tr align="left">
							  <td><b>การเข้าถึงข้อมูล</b></td>
							  <td colspan="3" class="text_gray">
								<select name="whoSeen" disabled <?php if($whoSeen_temp != $whoSeen && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" ";} ?>>
									<option value="ALL" <?php if($whoSeen_temp=="ALL"){ echo "selected";}?> readonly>เปิดให้เห็นทุกส่วนงาน</option>			
								</select>
							  </td>
						   </tr>
							<tr align="left">
							  <td><b>ประเภทที่ใช้อ้างอิง</b></td>
							  <td colspan="3" class="text_gray">
								<select name="tpRefType" disabled <?php if($tpRefType_temp != $tpRefType && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" ";} ?>>
									<option value="D" <?php if($tpRefType_temp=="D"){ echo "selected";}?>>D-วันที่</option>
									<option value="W" <?php if($tpRefType_temp=="W"){ echo "selected";}?>>W-สัปดาห์</option>	
									<option value="M" <?php if($tpRefType_temp=="M"){ echo "selected";}?>>M-รายเดือน</option>	
									<option value="Y" <?php if($tpRefType_temp=="Y"){ echo "selected";}?>>Y-รายปี</option>	
									<option value="L" <?php if($tpRefType_temp=="L"){ echo "selected";}?>>L-ช่วงใดๆ</option>	
									<option value="RUNNING" <?php if($tpRefType_temp=="RUNNING"){ echo "selected";}?>>RUNNING-ครั้งที่</option>	
									<option value="ID" <?php if($tpRefType_temp=="ID"){ echo "selected";}?>>ID-ตามหนังสือหรือรหัสใบ</option>	
									<option value="DUE" <?php if($tpRefType_temp=="DUE"){ echo "selected";}?>>DUE-Due หรือ งวดที่กำหนด</option>				
								</select>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>การรับแทน</b></td>
							  <td colspan="3" >
								<input type="radio" value="0" name="isSubsti" id="isSubsti1" <?php if($isSubsti_temp=="0"){ echo "checked";}?> disabled>ทั่วไป
								<input type="radio" value="1" name="isSubsti" id="isSubsti2" <?php if($isSubsti_temp=="1"){ echo "checked";}?> disabled>รับแทน
								 <?php if($isSubsti_temp != $isSubsti && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>เช่าทรัพย์สิน</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="isLeasing" size="20" value="<?php echo $isLeasing_temp;?>" onkeypress="check_num(event);" readonly <?php if($isLeasing_temp != $isLeasing && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$isLeasing\" ";} ?>></td>
						   </tr>
							 <tr align="left">
							  <td><b>อัตรา SBT ปัจจุบัน</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="curSBTRate" size="20" value="<?php echo $curSBTRate_temp;?>" onkeypress="check_num(event);" readonly <?php if($curSBTRate_temp != $curSBTRate && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$curSBTRate\" ";} ?>> </td>
						   </tr>
						   <tr align="left">
							  <td><b>Lock ค่า Vat </b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="isLockedVat" size="20" <?php if($isLockedVat_temp=="1") echo "checked";?> disabled><?php if($isLockedVat_temp != $isLockedVat && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
							<tr align="left">
							  <td><b>สามารถตั้งหนี้ </b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableInvoice" size="20" <?php if($ableInvoice_temp=="1") echo "checked";?> disabled><?php if($ableInvoice_temp != $ableInvoice && $addOrEdit == "U"){echo "<font color=\"red\">(มีการเปลี่ยนแปลง)</font>";} ?></td>
						   </tr>
							 <tr align="left">
							  <td><b>อัตรา LT ปัจจุบัน</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="curLTRate" size="20" value="<?php echo $curLTRate_temp;?>" onkeypress="check_num(event);" readonly <?php if($curLTRate_temp != $curLTRate && $addOrEdit == "U"){echo "style=\"background-color:#FF0000\" title=\"$curLTRate\" ";} ?>></td>
						   </tr>
						   <!-- จบเพิ่มเติม By Boz-->
						</table>
					</fieldset>
				</form>
			</div>
        </td>
    </tr>
</table>

</body>
</html>