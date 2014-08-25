<?php 
session_start();  
include("../../config/config.php"); 
$get_id_user = $_SESSION["av_iduser"];
$tpID = pg_escape_string($_POST["tpID2"]);
$tpCompanyID = pg_escape_string($_POST["tpCompanyID2"]);
$tpConType = pg_escape_string($_POST["tpConType2"]);
$tpDesc = pg_escape_string($_POST["tpDesc2"]);
$tpFullDesc = pg_escape_string($_POST["tpFullDesc2"]);
$ableB = pg_escape_string($_POST["ableB2"]);
$ableDiscount = pg_escape_string($_POST["ableDiscount2"]);
$ableWaive = pg_escape_string($_POST["ableWaive2"]);
$ableVAT = pg_escape_string($_POST["ableVAT2"]);
$ableWHT = pg_escape_string($_POST["ableWHT2"]);

//By Por
$ableSkip = pg_escape_string($_POST["ableSkip2"]);
$ablePartial = pg_escape_string($_POST["ablePartial2"]);
$curWHTRate = pg_escape_string($_POST["curWHTRate2"]);
$isServices = pg_escape_string($_POST["isServices2"]);
$tpSort = pg_escape_string($_POST["tpSort2"]);
$tpType = pg_escape_string($_POST["tpType2"]);
$tpRanking = pg_escape_string($_POST["tpRanking2"]);

//By Boz (เลียนแบบข้างบน)
	$whoSeen = pg_escape_string($_POST["whoSeen2"]); //ALL-เปิดให้เห็นทุกส่วนงาน
	$tpRefType = pg_escape_string(trim($_POST["tpRefType2"])); //รูปแบบ Ref
	$isSubsti = pg_escape_string($_POST["isSubsti2"]); //substitutional - รับแทน เช่น รับแทนค่าประกัน
	$isLeasing = pg_escape_string($_POST["isLeasing2"]);
	
	$curSBTRate = pg_escape_string($_POST["curSBTRate2"]);
	$isLockedVat = pg_escape_string($_POST["isLockedVat2"]);
	$ableInvoicet = pg_escape_string($_POST["ableInvoice2"]);
	$curLTRate = pg_escape_string($_POST["curLTRate2"]);
	
if($isLeasing=="'null'") {$isLeasing="";}
if($curSBTRate=="'null'") {$curSBTRate="";}
if($curLTRate=="'null'") {$curLTRate="";}
if($tpSort=="'null'") {$tpSort="";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) จัดการประเภทค่าใช้จ่าย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
	var curWHTRate = '';

	$(document).ready(function(){
		$("#tpBasis").autocomplete({
			source: "s_account.php",
			minLength:1
		});
		
		$("#tpAccrual").autocomplete({
			source: "s_account.php",
			minLength:1
		});
		
		$("#tpAmortize").autocomplete({
			source: "s_account.php",
			minLength:1
		});
	});


function checktpid(){
	
	$.post("check_tpid.php",{
			tpid : document.getElementById("tpID").value
		},
		function(data){	
				if(data == 'F'){
						document.getElementById("tpID").style.backgroundColor ="#FF0000";
						document.getElementById("valuechktpid").value=0;
						var textalert = 'รหัสค่าใช้จ่ายซ้ำ';
						$("#chktpid").css('color','#ff0000');
						$("#chktpid").html(textalert);
				}else if(data == 'T'){
						document.getElementById("tpID").style.backgroundColor ="#33FF33";
						document.getElementById("valuechktpid").value=1;
						$("#chktpid").html("");
				}
		});
};
function validate() {

var theMessage = "Please complete the following: \n-----------------------------------\n";
var noErrors = theMessage



if(document.form1.tpID.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก รหัสประเภทค่าใช้จ่าย";
}
if(document.form1.tpCompanyID.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก รหัสประเภทบริษัท";
}
if(document.form1.tpConType.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก รหัสประเภทสัญญา";
}
if(document.form1.tpDesc.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก ชื่อประเภทค่าใช้จ่าย";
}
if(document.form1.tpType.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอกประเภท";
}
if(document.form1.tpRanking.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก ลำดับการจ่าย";
}
if(document.form1.valuechktpid.value=='0'){
	theMessage = theMessage + "\n -->  กรุณาเปลี่ยนรหัสค่าใช้จ่าย";
}
if(document.form1.tpBasis.value==""){
	theMessage = theMessage + "\n -->  กรุณากรอก บัญชีพื้นฐาน";
}

if (theMessage == noErrors) {
    return true;
}else{
    alert(theMessage);
    return false;
}

}
function check_num(e)
{
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			
		}
		else
		{
			key = e.preventDefault();
		}
	}
}

function checkWHT()
{
	var wht = document.getElementById('ableWHT');
	if(wht.checked == false)
	{
		curWHTRate = document.getElementById("curWHTRate").value;
		document.getElementById("curWHTRate").value = '';
		document.getElementById("curWHTRate").readOnly = true;
	}
	else
	{
		document.getElementById("curWHTRate").value = curWHTRate;
		document.getElementById("curWHTRate").readOnly = false;
	}
}
</script>
	
</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td align="center" valign="top" style="background-repeat:repeat-y">

			<div class="header"><h1>(THCAP) จัดการประเภทค่าใช้จ่าย</h1></div>

			<div class="wrapper">
				<div align="right"><a href="frm_thcap_show.php"><img src="images/full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div>
				<form id="form1" name="form1" method="post" action="process_thcap_edit.php">
					<fieldset><legend><B>เพิ่มประเภทค่าใช้จ่าย</B></legend>
						<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
						   <tr align="left">
							  <td width="30%"><b>รหัสประเภทค่าใช้จ่าย <font color="red">*</font></b></td>
							  <td width="70%" class="text_gray"><input type="text" name="tpID" id="tpID" size="28" value="<?php echo $tpID;?>" onkeyup="checktpid();" onblur="checktpid();" onchange="checktpid();" autocomplete="off" /> <span id="chktpid" name="chktpid"></span></td>
								<input type="hidden" id="valuechktpid" name="valuechktpid" />
						   </tr>
						   <tr align="left">
							  <td><b>รหัสประเภทบริษัท <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpCompanyID" size="28" value="<?php echo $tpCompanyID;?>"></td>
						   </tr>
						   <tr align="left">
							  <td><b>รหัสประเภทสัญญา <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpConType" size="28" value="<?php echo $tpConType;?>"></td>
						   </tr>
						   <tr align="left">
							  <td><b>ชื่อประเภทค่าใช้จ่าย <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpDesc" size="28" value="<?php echo $tpDesc;?>"></td>
						   </tr>
						   <tr align="left">
							  <td><b>คำอธิบายประเภทค่าใช้จ่าย</b></td>
							  <td colspan="3" class="text_gray"><textarea name="tpFullDesc"><?php echo $tpFullDesc;?></textarea></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถบันทึกลงสมุด</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableB" size="20" <?php if($ableB=="1" || $ableB=="") echo "checked";?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถทำส่วนลด</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableDiscount" size="20" <?php if($ableDiscount=="1" || $ableDiscount=="") echo "checked";?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถยกเว้น</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableWaive" size="20" <?php if($ableWaive=="1" || $ableWaive=="") echo "checked";?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>มี VAT</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableVAT" size="20" <?php if($ableVAT=="1") echo "checked";?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>มี ภาษีหัก ณ ที่จ่าย</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableWHT" id="ableWHT" size="20" <?php if($ableWHT=="1") echo "checked";?> onchange="checkWHT();" ></td>
						   </tr>
						   <!-- เพิ่มเติม By Por-->
						   <tr align="left">
							  <td><b>สามารถข้ามเรื่องอันดับการจ่ายได้</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableSkip" size="20" <?php if($ableSkip=="1") echo "checked";?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>สามารถจ่ายบางส่วนได้</b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ablePartial" size="20" <?php if($ablePartial=="1") echo "checked";?>></td>
						   </tr>
						   <tr align="left">
							  <td><b>% อัตราภาษีหัก ณ ที่จ่ายปัจจุบันของค่าใช้จ่ายประเภทนี้</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="curWHTRate" id="curWHTRate" size="20" value="<?php echo $curWHTRate;?>" onkeypress="check_num(event);" readonly > (ถ้ามีภาษีหัก ณ ที่จ่าย)</td>
						   </tr>
						   <tr align="left">
							  <td><b>สินค้าหรือค่าบริการ</b></td>
							  <td colspan="3" class="text_gray">
								<select name="isServices">
									<option value="0" <?php if($isServices=="0"){ echo "selected";}?>>ไม่เข้าข่ายทั้ง ค่าสินค้า หรือบริการ</option>
									<option value="1" <?php if($isServices=="1"){ echo "selected";}?>>บริการ ยึดการคิด VAT ณ วันจ่าย (เพราะเป็นค่าบริการ VAT ส่งเมื่อจ่าย)</option>
									<option value="2" <?php if($isServices=="2"){ echo "selected";}?>>สินค้า ยึดการคิด VAT ตาม invoice (เพราะเป็นสินค้า VAT ส่งหลวงแล้ว)</option>
								</select>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>เรียงอันดับการแสดงผล</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpSort" size="20" value="<?php echo $tpSort;?>" onkeypress="check_num(event);"></td>
						   </tr>
						   <tr align="left">
							  <td><b>ประเภท <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray">
								<select name="tpType">
									<option value="NONE" <?php if($tpType=="NONE"){ echo "selected";}?>>NONE-ไม่มีเงื่อนไขในการเก็บ</option>
									<option value="LOCKED" <?php if($tpType=="LOCKED"){ echo "selected";}?>>LOCKED-ไม่มีเงื่อนไขในการเก็บ แต่ว่าไม่ให้เพิ่มหนี้เข้าไปได้โดยทั่วไป</option>
									<option value="FIXED" <?php if($tpType=="FIXED"){ echo "selected";}?>>FIXED-เก็บค่าตายตัวทุกสัญญาเหมือนกันหมด</option>
									<option value="VAR" <?php if($tpType=="VAR"){ echo "selected";}?>>VAR-เก็บค่าไม่เหมือนกันแปรผันตามสัญญา</option>
									<option value="PER" <?php if($tpType=="PER"){ echo "selected";}?>>PER-เก็บค่าเป็น percent จากยอดที่สนใจ</option>
								</select>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>ลำดับการจ่าย <font color="red">*</font></b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="tpRanking" size="20" value="<?php echo $tpRanking;?>" onkeypress="check_num(event);"></td>
						   </tr>
						   <!-- จบเพิ่มเติม By Por-->
							 <!-- เพิ่มเติม By Boz-->
						   <tr align="left">
							  <td><b>การเข้าถึงข้อมูล</b></td>
							  <td colspan="3" class="text_gray">
								<select name="whoSeen">
									<option value="ALL" <?php if($whoSeen=="ALL"){ echo "selected";}?>>เปิดให้เห็นทุกส่วนงาน</option>			
								</select>
							  </td>
						   </tr>
							<tr align="left">
							  <td><b>ประเภทที่ใช้อ้างอิง</b></td>
							  <td colspan="3" class="text_gray">
								<select name="tpRefType">
									<option value="D" <?php if($tpRefType=="D"){ echo "selected";}?>>D-วันที่</option>
									<option value="W" <?php if($tpRefType=="W"){ echo "selected";}?>>W-สัปดาห์</option>	
									<option value="M" <?php if($tpRefType=="M"){ echo "selected";}?>>M-รายเดือน</option>	
									<option value="Y" <?php if($tpRefType=="Y"){ echo "selected";}?>>Y-รายปี</option>	
									<option value="L" <?php if($tpRefType=="L"){ echo "selected";}?>>L-ช่วงใดๆ</option>	
									<option value="RUNNING" <?php if($tpRefType=="RUNNING"){ echo "selected";}?>>RUNNING-ครั้งที่</option>	
									<option value="ID" <?php if($tpRefType=="ID"){ echo "selected";}?>>ID-ตามหนังสือหรือรหัสใบ</option>	
									<option value="DUE" <?php if($tpRefType=="DUE"){ echo "selected";}?>>DUE-Due หรือ งวดที่กำหนด</option>				
								</select>
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>การรับแทน</b></td>
							  <td colspan="3" >
								<input type="radio" value="0" name="isSubsti" id="isSubsti1" <?php if($isSubsti=="0"){ echo "checked";}?> checked>ทั่วไป
								<input type="radio" value="1" name="isSubsti" id="isSubsti2" <?php if($isSubsti=="1"){ echo "checked";}?>>รับแทน
							  </td>
						   </tr>
						   <tr align="left">
							  <td><b>เช่าทรัพย์สิน</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="isLeasing" size="20" value="<?php echo $isLeasing;?>" onkeypress="check_num(event);"  ></td>
						   </tr>
							<tr align="left">
							  <td><b>อัตรา SBT ปัจจุบัน</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="curSBTRate" size="20" value="<?php echo $curSBTRate;?>" onkeypress="check_num(event);"> </td>
						   </tr>
						   <tr align="left">
							  <td><b>Lock ค่า Vat </b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="isLockedVat" size="20" <?php if($isLockedVat=="1") echo "checked";?>></td>
						   </tr>
							<tr align="left">
							  <td><b>สามารถตั้งหนี้ </b></td>
							  <td colspan="3" class="text_gray"><input type="checkbox" name="ableInvoice" size="20" <?php if($ableInvoice=="1") echo "checked";?>></td>
						   </tr>
							 <tr align="left">
							  <td><b>อัตรา LT ปัจจุบัน</b></td>
							  <td colspan="3" class="text_gray"><input type="text" name="curLTRate" size="20" value="<?php echo $curLTRate;?>" onkeypress="check_num(event);"></td>
						   </tr>
						   <!-- จบเพิ่มเติม By Boz-->
						</table>
					</fieldset>
					
					<fieldset><legend><B>เพิ่มความสัมพันธ์ทางบัญชี</B></legend>
						<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
							<tr align="left">
								<td><b>บัญชีพื้นฐาน <font color="red">*</font></b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpBasis" name="tpBasis" size="80" value="<?php echo $tpBasis_fullText; ?>"></td>
							</tr>
							<tr align="left">
								<td><b>บัญชีคงค้าง </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAccrual" name="tpAccrual" size="80" value="<?php echo $tpAccrual_fullText; ?>"></td>
							</tr>
							<tr align="left">
								<td><b>บัญชีทยอยรับรู้ </b></td>
								<td colspan="3" class="text_gray"><input type="text" id="tpAmortize" name="tpAmortize" size="80" value="<?php echo $tpAmortize_fullText; ?>"></td>
							</tr>
						</table>
					</fieldset>
					
					<br>
					<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
					   <tr align="center">
						  <td><br><input type="submit" name="submit" value="บันทึก" onclick="return validate()"></td>
						  <td><input type="button" name="back" value="กลับ" onClick="window.location='frm_thcap_show.php'"></td>
					   </tr>
					</table>
				</form>
			</div>
        </td>
    </tr>
</table>

</body>
</html>