<?php
session_start();
include("../../config/config.php");
$id_user=$_SESSION["av_iduser"];
$IDNO = $_REQUEST['idno'];
$auto_id = $_REQUEST['auto_id'];
$statusreq = $_REQUEST['statusreq'];
if($statusreq=="0"){
	$txtreq="ประกันใหม่";
}else{
	$txtreq="ต่ออายุ";
}
//นำ IDNO มาค้นหาว่ามีอยู่ในระบบหรือไม่
$qrycheckid=pg_query("select * from thcap_mg_contract where \"contractID\"='$IDNO'");
$numcheckid=pg_num_rows($qrycheckid);
//หาข้อมูลค่าเบี้ย
	$qrychip=pg_query("SELECT \"refDeedContract\",\"costBuilding\", \"costFurniture\", \"costEngine\", 
	\"costStock\", \"textOther\", \"costOther\", \"insureSpecial\", \"totalChip\", 
	\"numberQ\" FROM thcap_insure_checkchip where auto_id='$auto_id'");
		
	list($refDeedContract,$costBuilding, $costFurniture, $costEngine, $costStock, $textOther, $costOther, $insureSpecial, $totalChip, $numberQ)=pg_fetch_array($qrychip);
	$summoney=$costBuilding+$costFurniture+$costEngine+$costStock+$costOther;
	
	//หาค่า $refDeedContract ได้ดังนี้
	if($statusreq!="0"){
		$qrysecur=pg_query("select \"securID\",\"addrDeed\",\"addrCus\" from \"thcap_insure_main\" a
		left join thcap_insure_temp b on a.\"auto_tempID\"=b.\"auto_id\"
		left join \"nw_securities_detail\" c on b.\"securdeID\"=c.\"securdeID\"
		where \"ContractID\"='$IDNO'");
		
		list($refDeedContract,$addrDeed2,$addrCus2)=pg_fetch_array($qrysecur);
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

	if($statusreq!="0"){
		$address=$addrDeed2;
	}
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
    
<script type="text/javascript">
$(document).ready(function(){
    $("#CusID1").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	$("#CusID2").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	$("#CusID3").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	$("#CusID4").autocomplete({
        source: "s_user.php",
        minLength:1
    });
	
	$("#startDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
	});
	$("#endDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
	});
	$("#dateNotify").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
	});
	$("#selectcus1").click(function(){
		popU('<?php echo $IDNO;?>','CusID1');
	});
	$("#selectcus2").click(function(){
		popU('<?php echo $IDNO;?>','CusID2');
	});
	$("#selectcus3").click(function(){
		popU('<?php echo $IDNO;?>','CusID3');
	});
	$("#selectcus4").click(function(){
		popU('<?php echo $IDNO;?>','CusID4');
	});
	
	$("#cancel1").click(function(){
		$("#CusID1").val('');
	});
	$("#cancel2").click(function(){
		$("#CusID2").val('');
	});
	$("#cancel3").click(function(){
		$("#CusID3").val('');
	});
	$("#cancel4").click(function(){
		$("#CusID4").val('');
	});
	
	var counter = 4;
	$('#addButton').click(function(){
		counter++;
		console.log(counter);
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
		table = '<table width="100%" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
		+ '	<tr bgcolor="#F4F4FF">'
		+ '		<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b>'+ counter +'.<input type="text" name="Cus[]" id="CusID'+ counter +'" size="40" readonly="true"></b><input type="button" id="selectcus'+ counter +'" value="เลือก"></td>'
		+ '	</tr>'
		+ '	</table>'
		
			newTextBoxDiv.html(table);

			newTextBoxDiv.appendTo("#TextBoxesGroup1");

			$("#selectcus"+counter).click(function(){
			popU('<?php echo $IDNO;?>','CusID'+counter);
		});
    });
	$('#addButton2').click(function(){
		counter++;
		console.log(counter);
		var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
		table = '<table width="100%" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
		+ '	<tr bgcolor="#F4F4FF">'
		+ '		<td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<b>'+ counter +'.<input type="text" name="Cus[]" id="CusID'+ counter +'" size="40" ></b></td>'
		+ '	</tr>'
		+ '	</table>'
		
			newTextBoxDiv.html(table);

			newTextBoxDiv.appendTo("#TextBoxesGroup1");

			
			$("#CusID"+counter).autocomplete({
				source: "s_user.php",
				minLength:1
			});
			
			$("#selectcus"+counter).click(function(){
			popU('<?php echo $IDNO;?>','CusID'+counter);
		});
    });

	$("#removeButton").click(function(){
        if(counter==4){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
        updateSummary();
    });
});

function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.CusID1.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุผู้เอาประกันตามลำดับ";
	}
	
	if (document.form1.addrCus.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุที่อยู่ผู้เอาประกัน";
	}
	
	if (document.form1.addrDeed.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุสถานที่ตั้งทรัพย์สินที่เอาประกันภัย";
	}
	
	if (document.form1.startDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุวันที่เริ่มต้นเอาประกันภัย";
	}
	
	if (document.form1.endDate.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุวันที่สิ้นสุดเอาประกันภัย";
	}
	
	if(document.form1.startDate.value!="" && document.form1.endDate.value!=""){
		if(document.form1.endDate.value < document.form1.startDate.value){
			theMessage = theMessage + "\n -->  วันที่สิ้นสุดเอาประกันภัยต้องมากกว่าวันที่เริ่มต้น";
		}
	}
	if (document.form1.userBenefit.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุผู้รับผลประโยชน์";
	}
	if (document.form1.userNotify.value=="") {
	theMessage = theMessage + "\n -->  กรุณาระบุชื่อผู้แจ้งลูกค้า";
	}
	// If no errors, submit the form
	if (theMessage == noErrors) {
		return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
function popU(id,status) {
	var cusid;
	var cusary1=$("#CusID1").val();
	var cus1=cusary1.split("#");
	
	var cusary2=$("#CusID2").val();
	var cus2=cusary2.split("#");
	
	var cusary3=$("#CusID3").val();
	var cus3=cusary3.split("#");
	
	var cusary4=$("#CusID4").val();
	var cus4=cusary4.split("#");
	
	if(status=="CusID1"){
		cusid=cus2[0];
		cusid3=cus3[0];
		cusid4=cus4[0];
	}else if(status=="CusID2"){
		cusid=cus1[0];
		cusid3=cus3[0];
		cusid4=cus4[0];
	}else if(status=="CusID3"){
		cusid=cus1[0];
		cusid3=cus2[0];
		cusid4=cus4[0];
	}else{
		cusid=cus1[0];
		cusid3=cus2[0];
		cusid4=cus3[0];
	}
	var U="selectcustomer.php?IDNO="+id+"&status="+status+"&cus="+cusid+"&cus3="+cusid3+"&cus4="+cusid4;
	var N="";
	var T="toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300";
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="text-align:right;"><input type="button" value=" กลับ " onclick="location='frm_IndexChip.php'"></div>
<fieldset><legend><B>สร้างคำขอ <?php echo $IDNO;?> (<?php echo $txtreq;?>)</B></legend>
<form name="form1" method="post" action="process_insure.php">
<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F2FBFF">
<tr height="25">
    <td colspan="4"></td>
</tr>
<tr height="25" bgcolor="#DFF5FF">
    <td width="50%">
		<table width="100%" border="0">
			<tr><td colspan="2"><b>1. ชื่อผู้เอาประกัน</b></td></tr>
			<?php
			if($numcheckid>0){
			?>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>1.<input type="text" name="CusID1" id="CusID1" size="40" readonly="true"></b><input type="button" name="selectcus1" id="selectcus1" value="เลือก"><input type="button" name="cancel1" id="cancel1" value="ยกเลิก"><font color="red">*</font></td></tr>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>2.<input type="text" name="CusID2" id="CusID2" size="40" readonly="true"></b><input type="button" name="selectcus2" id="selectcus2" value="เลือก"><input type="button" name="cancel2" id="cancel2" value="ยกเลิก"></td></tr>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>3.<input type="text" name="CusID3" id="CusID3" size="40" readonly="true"></b><input type="button" name="selectcus3" id="selectcus3" value="เลือก"><input type="button" name="cancel3" id="cancel3" value="ยกเลิก"></td></tr>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>4.<input type="text" name="CusID4" id="CusID4" size="40" readonly="true"></b><input type="button" name="selectcus4" id="selectcus4" value="เลือก"><input type="button" name="cancel4" id="cancel4" value="ยกเลิก"><input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton"></td></tr>
			
			<?php
			}else{
			?>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>1.<input type="text" name="CusID1" id="CusID1" size="40"></b><font color="red">*</font></td></tr>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>2.<input type="text" name="CusID2" id="CusID2" size="40"></b></td></tr>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>3.<input type="text" name="CusID3" id="CusID3" size="40"></b></td></tr>
				<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>4.<input type="text" name="CusID4" id="CusID4" size="40"></b><input type="button" value="+ เพิ่ม" id="addButton2"><input type="button" value="- ลบ" id="removeButton"></td></tr>
			<?php
			}
			?>
			<tr>
				<td colspan="2">
					<div id='TextBoxesGroup1'>
						<div id="TextBoxDiv1"></div>
					</div>
					<br><hr>
				</td>
			</tr>
			<?php
			//แสดงที่อยู่ของผู้กู้หลัก
			$qry_name=pg_query("select \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\" from \"thcap_mg_contract\" a
			left join \"thcap_ContactCus\" b on a.\"contractID\"=b.\"contractID\"
			left join \"Fa1\" c on b.\"CusID\"=c.\"CusID\"
			where a.\"contractID\"='$IDNO' and b.\"CusState\" ='0'");
			if($res_name=pg_fetch_array($qry_name)){
				$A_NO=trim($res_name["A_NO"]);
				$A_SUBNO=trim($res_name["A_SUBNO"]);
				$A_SOI=trim($res_name["A_SOI"]);
				$A_RD=trim($res_name["A_RD"]);
				$A_TUM=trim($res_name["A_TUM"]);
				$A_AUM=trim($res_name["A_AUM"]);
				$A_PRO=trim($res_name["A_PRO"]);
				$A_POST=trim($res_name["A_POST"]);
				
				
			}
			if($A_NO=="" and $A_SUBNO=="" and $A_SOI=="" and $A_RD=="" and $A_TUM=="" and $A_AUM=="" and $A_PRO=="" and $A_POST==""){
					//ถ้าว่างทั้งหมดแสดงว่าอาจเป็นลูกค้านิติบุคคลให้ไปค้นที่อยู่จากที่อื่นแทน
					$qry_name2=pg_query("select \"HomeNumber\", room, \"LiveFloor\", \"Moo\", \"Building\", \"Village\", \"Lane\", \"Road\", 
					   \"District\", \"State\", \"proName\", \"Postal_code\" from \"thcap_mg_contract\" a
					left join \"thcap_ContactCus\" b on a.\"contractID\"=b.\"contractID\"
					left join \"th_corp_adds\" c on b.\"CusID\"=cast(c.\"corpID\" as character varying(12))
					left join \"nw_province\" d on c.\"ProvinceID\"=d.\"proID\"
					where a.\"contractID\"='$IDNO' and b.\"CusState\" ='0' order by c.\"addsType\" limit 1");
					
					if($res_name2=pg_fetch_array($qry_name2)){
						$A_NO=trim($res_name2["HomeNumber"]);
						$A_SUBNO=trim($res_name2["Moo"]);
						$A_SOI=trim($res_name2["Lane"]);
						$A_RD=trim($res_name2["Road"]);
						$A_TUM=trim($res_name2["District"]);
						$A_AUM=trim($res_name2["State"]);
						$A_PRO=trim($res_name2["proName"]);
						$A_POST=trim($res_name2["Postal_code"]);
						
						$room=trim($res_name2["room"]);
						$LiveFloor=trim($res_name2["LiveFloor"]);
						$Building=trim($res_name2["Building"]);
						$Village=trim($res_name2["Village"]);
					}
			}
			//นิติบุคคล
				if($room=="" || $room=="-" || $room=="--"){ //ห้อง
					//ไม่ต้องทำอะไร
				}else{
					$room="ห้อง $room";
				}
				if($LiveFloor=="" || $LiveFloor=="-" || $LiveFloor=="--"){ //ห้อง
					//ไม่ต้องทำอะไร
				}else{
					$LiveFloor="ชั้น $LiveFloor";
				}
				if($Building=="" || $Building=="-" || $Building=="--"){ //ห้อง
					//ไม่ต้องทำอะไร
				}else{
					$Building="อาคาร/สถานที่ $Building";
				}
				if($Village=="" || $Village=="-" || $Village=="--"){ //ห้อง
					//ไม่ต้องทำอะไร
				}else{
					$Village="หมู่บ้าน$Village";
				}
			//จบนิติบุคคล			
			if($A_SUBNO=="" || $A_SUBNO=="-" || $A_SUBNO=="--"){ //ม.
				//ไม่ต้องทำอะไร
			}else{
				$subno="ม.$A_SUBNO";
			}
			if($A_SOI=="" || $A_SOI=="-" || $A_SOI=="--"){ //ซ.
				//ไม่ต้องทำอะไร
			}else{
				$soi="ซ.$A_SOI";
			}
			if($A_RD=="" || $A_RD=="-" || $A_RD=="--"){ //ถ.
				//ไม่ต้องทำอะไร
			}else{
				$road="ถ.$A_RD";
			}
			if($A_POST=="" || $A_POST=="-" || $A_POST=="--"){ //รหัสไปรษณีย์
				$A_POST="";
			}
			if($A_PRO=="กรุงเทพมหานคร" || $A_PRO=="กรุงเทพ" || $A_PRO=="กรุงเทพฯ" || $A_PRO=="กทม."){
				$txttum="แขวง".$A_TUM; //แขวง
				$txtaum="เขต".$A_AUM; //เขต
				$txtpro="$A_PRO"; //เขต
			}else{
				$txttum="ต.".$A_TUM; //ต.
				$txtaum="อ.".$A_AUM; //อำเภอ
				$txtpro="จ.".$A_PRO; //จังหวัด
			}	
if($numcheckid>0){
	if($addrCus2==""){
	$address1 = "$A_NO $room$subno $LiveFloor $Building $Village $soi $road $txttum $txtaum $txtpro $A_POST";
	}else{
	$address1 = $addrCus2;
	}
}else{
	$address1="";
}
			?>
			<tr><td valign="top"><font color="red">*</font><b>ที่อยู่ :</b></td><td><textarea name="addrCus" cols="40" rows="4"><?php echo $address1;?></textarea></td></tr>
		</table>
	</td>
    <td valign="top">
		<table width="100%" border="0">
			<tr><td><font color="red">*</font><b>สถานที่ตั้งทรัพย์สินที่เอาประกันภัย</b></td></tr>
			<tr><td valign="top"><textarea cols="60" rows="4" name="addrDeed"><?php echo $address;?></textarea></td></tr>
		</table>
	</td>
</tr>
<tr height="25">
    <td colspan="2"><font color="red">*</font><b>2. ระยะเวลาประกันภัย</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เริ่มวันที่ <input type="text" name="startDate" id="startDate" readonly="true" style="text-align:center">  เวลา 16.00 สิ้นสุดวันที่  <input type="text" name="endDate" id="endDate" readonly="true" style="text-align:center"> เวลา 16.00 น.</td>
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
				<td colspan="2" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ผู้รับผลประโยชน์</b> <input type="text" name="userBenefit" value="บริษัท ไทยเอซ แคปปิตอล จำกัด" size="60"><font color="red">*</font></td>
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
    <td colspan="2"><b>6. พื้นที่ภายในอาคาร  <input type="text" value="<?php echo $build_inside_area;?>" readonly="true" style="text-align:center"> ตรว.</b></td>
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
    <td colspan="2"><font color="red">*</font><b>8. ชื่อผู้แจ้งลูกค้า <input type="text" name="userNotify" size="40" value="<?php echo "คุณ$fname";?>"> วันที่ <input type="text" name="dateNotify" id="dateNotify" value="<?php echo nowDate(); ?>" readonly="true" style="text-align:center;"></b></td>
</tr>
<tr><td colspan="2"><b><font color="red">* หมายถึงต้องระบุข้อมูล</font></b></td></tr>
<tr height="50" bgcolor="#FFFFFF">
    <td colspan="2" align="center">
		<input type="hidden" name="checkchipID" value="<?php echo $auto_id; ?>">
		<input type="hidden" name="securdeID" value="<?php echo $securdeID; ?>">
		<input type="hidden" name="ContractID" value="<?php echo $IDNO; ?>">
		<input type="hidden" name="statusInsure" value="<?php echo $statusreq; ?>">
		<input type="hidden" name="cmd" value="addRequest">
		<input name="btnButton1" type="submit" value="บันทึก"onclick="return checkdata()" /><input name="btnButton2" type="reset" value="ยกเลิก">
	</td>
</tr>

</table>
</form>
</fieldset> 
</body>
</html>