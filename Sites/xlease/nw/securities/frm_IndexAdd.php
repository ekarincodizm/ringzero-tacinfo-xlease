<?php
session_start();
include("../../config/config.php");		 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript">
$(document).ready(function(){
	$("#condo1").hide();
	$("#condo2").hide();
	$("#condo3").hide();
	$("#condo4").hide();
	
	$("#cid").autocomplete({
        source: "s_contractID.php",
        minLength:1
    });
	
	$("#guaranID").change(function(){
		var src = $('#guaranID option:selected').attr('value');
        if ( src == "1" ){
			$("#land1").show();
			$("#land2").show();
			$("#land3").show();
			$("#land4").show();
			$("#land5").show();

			$("#condo1").hide();
			$("#condo2").hide();
			$("#condo3").hide();
			$("#condo4").hide();			
		}else if(src == "2"){
			$("#land1").hide();
			$("#land2").hide();
			$("#land3").hide();
			$("#land4").hide();
			$("#land5").hide();
			
			
			$("#condo1").show();
			$("#condo2").show();
			$("#condo3").show();
			$("#condo4").show();
		}else{
			$("#land1").show();
			$("#land2").show();
			$("#land3").show();
			$("#land4").show();
			$("#land5").show();
			
			$("#condo1").hide();
			$("#condo2").hide();
			$("#condo3").hide();
			$("#condo4").hide();
		}
	});
});
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<form name="form1"  method="post" action="process_securities.php"  enctype="multipart/form-data">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เพิ่มหลักทรัพย์ +</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.close();"><u>x ปิดหน้านี้</u></span></div>
	<!--<form name="frm_edit" method="post" action="#">-->
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="200">ประเภทหลักประกัน : </td>
			<td bgcolor="#FFFFFF" width="200">
				<select name="guaranID" id="guaranID">
					<option value="">-----เลือก-----</option>
					<option value="1">ที่ดิน</option>
					<option value="3">ที่ดินพร้อมสิ่งปลูกสร้าง</option>
					<option value="2">ห้องชุด</option>
					
				</select>
			</td>
			<td align="right" width="200">โฉนดที่ดินเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numDeed" id="numDeed"/><b><font color="red">*</font></b></td>
		</tr>
		<!--รายละเอียดที่ดิน -->
		<tr height="30" bgcolor="#E8E8E8" id="land1">
			<td align="right">เล่มที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numBook" id="numBook" onkeypress="return check_num(event);"/></td>
			<td align="right">หน้าที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numPage" id="numPage" onkeypress="return check_num(event);"/></td>
		</tr>
		<!--รายละเอียดทีห้องชุด -->
		<tr height="30" bgcolor="#E8E8E8" id="condo1">
			<td align="right">ห้องชุดเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoroomnum" id="condoroomnum"/></td>
			<td align="right">ชั้นที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condofloor" id="condofloor"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="land2">
			<td align="right">เลขที่ดิน : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numLand" id="numLand"/></td>
			<td align="right">หน้าสำรวจ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="pageSurvey" id="pageSurvey" onkeypress="return check_num(event);"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo2">
			<td align="right">อาคารเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condobuildingnum" id="condobuildingnum"/></td>
			<td align="right">ทะเบียนอาคารชุด : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoregisnum" id="condoregisnum"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo3">
			<td align="right">ชื่ออาคารชุด : </td>
			<td bgcolor="#FFFFFF" colspan="3"><input type="text" name="condobuildingname" id="condobuildingname" size="50"/></td>
		</tr>
		
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เนื้อที่ : </td>
			<td colspan="3" bgcolor="#FFFFFF" id="land3">
				<input type="text" name="area_acre" id="area_acre" size="10" onkeypress="return check_num(event);"/> ไร่
				<input type="text" name="area_ngan" id="area_ngan" size="10" onkeypress="return check_num(event);"/> งาน
				<input type="text" name="area_sqyard" id="area_sqyard" size="10" onkeypress="return check_num(event);"/> ตารางวา
			</td>
			<td colspan="3" bgcolor="#FFFFFF" id="condo4">
				<input type="text" name="area_smeter" id="area_smeter" size="10" onkeypress="return check_num(event);"/> ตารางเมตร
			</td>
		</tr>
		</table>
		<div id='TextBoxesGroup1'>
		<div id="TextBoxDiv1">
			<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
				<tr bgcolor="#E8E8E8">
					<td align="right" width="198">เจ้าของกรรมสิทธิ์ : </td>
					<td colspan="3" bgcolor="#FFFFFF">
						<input type="text" name="CusID[]" id="CusID1" size="30"/> <b><font color="red">*</font></b> <input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton">
					</td>
				</tr>
			</table>
		</div>
		</div>
		<div id='TextGroup1'>
		<div id="TextDiv1">
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="198">Upload โฉนด : </td>
			<td colspan="3" bgcolor="#FFFFFF"><input type="file" name="my_field[]" id="upload1"><input type="button" value="+ เพิ่ม" id="addButton2"><input type="button" value="- ลบ" id="removeButton2"> <font color="red">(ชื่อไฟล์เป็นภาษาอังกฤษ และขนาดไม่เกิน 2 MB)</font></td>
		</tr>
		</table>
		</div>
		</div>
		
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
			<tr height="30" bgcolor="#E8E8E8">
				<td align="right" valign="top" width="198">หมายเหตุ : </td>
				<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5"></textarea></td>
			</tr>
			
			<tr>
				<td colspan="4">
					<table width="780" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
						<tr><td colspan="4" align="center"><h2> ที่อยู่หลักทรัพย์ </h2></td></tr>
						<tr height="30" bgcolor="#E8E8E8" id="land4">
							<td align="right" width="200">บ้านเลขที่ : </td>
							<td bgcolor="#FFFFFF" width="200"><input type="text" name="s_no" id="s_no"/></td>
							<td align="right" width="200">หมู่ : </td>					
							<td bgcolor="#FFFFFF"><input type="text" name="s_subno" id="s_subno"/></td>
						</tr>
						<tr height="30" bgcolor="#E8E8E8"  id="land5">
							<td align="right" width="200">หมู่บ้าน : </td>
							<td bgcolor="#FFFFFF" width="200" colspan="3"><input type="text" name="s_village" id="s_village" size="35"/></td>
						</tr>
						<tr height="30" bgcolor="#E8E8E8">
							<td align="right" width="200">ซอย : </td>
							<td bgcolor="#FFFFFF" width="200"><input type="text" name="soi" id="soi"/></td>
							<td align="right" width="200">ถนน : </td>					
							<td bgcolor="#FFFFFF"><input type="text" name="rd" id="rd"/></td>
						</tr>
						<tr height="30" bgcolor="#E8E8E8">
							<td align="right">จังหวัด : </td>
							<td bgcolor="#FFFFFF">
								<select name="proID" id="proID" onchange="calamp()">
									<option value="">---เลือก---</option>
									<?php
										$qry_pro=pg_query("select * from \"nw_province\" order by \"proID\"");
										while($res_pro=pg_fetch_array($qry_pro)){
											$proName=$res_pro["proName"];
											$proID=$res_pro["proID"];
											echo "<option value=$proID>$proName</option>";
										}
									?>
								</select>
								<b><font color="red">*</font></b>
							</td>
							<td align="right">อำเภอ/เขต : </td>					
							<td bgcolor="#FFFFFF"><span id="spamphur">---</span><font color="red">*</font></td>
						</tr>
						<tr height="30" bgcolor="#E8E8E8">	
							<td align="right">ตำบล/แขวง : </td>
							<td bgcolor="#FFFFFF"><span id="spdistrict">---</span><font color="red">*</font></td>
							<td align="right">รหัสไปรษณีย์ : </td>
							<td bgcolor="#FFFFFF"><input type="text" name="post" id="post"/></td>
						</tr>
						<tr height="30" bgcolor="#EEEE00"  id="land5">
							<td align="right" width="200">CID (เลขที่สัญญา แคปปิตอล) : </td>
							<td bgcolor="#FFFFFF" width="200" colspan="3"><input type="text" name="cid" id="cid" size="35"/></td>
						</tr>
					</table>	
				</td>
			</tr>
			
			<tr>
				<td colspan="4" height="40" bgcolor="#FFFFFF" align="center">
				<input type="hidden" name="cmd" value="save">
				<input type="hidden" name="method" value="add">
				<input type="submit" value="บันทึกข้อมูล" id="submitButton">
				<input type="reset" value="ยกเลิก">
				</td>
			</tr>
		</table>
	<!--</form>-->
	</div>
	<center>
	<fieldset style="width:50%;">
	<legend><B>รายการรออนุมัติข้อมูลหลักทรัพย์</B></legend>
	<?php 
	$chk_show = "true";
	include("frm_ApproveDetail.php");
	?>
	</fieldset>
	</center>
</div>
</form>
<!-- แก้ไขจากเดิมเน้อ เลือกจังหวัดอำเภอ ไรเงี้ย-->
<script type="text/javascript">
function calamp(){

var provice = $('#proID option:selected').attr('value');
	$("#spamphur").load("amphur.php?proID="+provice);
	$("#spdistrict").load("District.php");
};	

function caldis(){

var amphur = $('#amphur option:selected').attr('value');
	$("#spdistrict").load("District.php?ampID="+amphur);
	
};
</script>

<!------ ------>
<script type="text/javascript">
var counter = 1;

$(document).ready(function(){
	$('#addButton').click(function(){
    counter++;
    console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    table = '<table width="785" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr bgcolor="#E8E8E8">'
	+ '		<td align="right" width="198">'+ counter +'</td>'
	+ '		<td colspan="3" bgcolor="#FFFFFF">'
	+ '			<input type="text" name="CusID[]" id="CusID'+ counter +'" size="30"/>'
	+ '		</td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup1");

		
		$("#CusID"+counter).autocomplete({
			source: "s_cusid.php",
			minLength:1
		});

    });

	$("#removeButton").click(function(){
        if(counter==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextBoxDiv" + counter).remove();
        counter--;
        console.log(counter);
        updateSummary();
    });
	
	var counter2=1;
	$('#addButton2').click(function(){
    counter2++;
    console.log(counter2);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextDiv' + counter2);
    table = '<table width="785" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr height="30" bgcolor="#E8E8E8">'
	+ '		<td align="right" width="198">'+ counter2 +'</td>'
	+ '		<td colspan="3" bgcolor="#FFFFFF"><input type="file" name="my_field[]" id="upload'+ counter2 +'"></td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextGroup1");

	
    });

	$("#removeButton2").click(function(){
        if(counter2==1){
            alert("ห้ามลบ !!!");
            return false;
        }
        $("#TextDiv" + counter2).remove();
        counter2--;
        console.log(counter2);
        updateSummary();
    });
	
	$("#CusID1").autocomplete({
			source: "s_cusid.php",
			minLength:1
	});
    
	$("#submitButton").click(function(){
        $("#submitButton").attr('disabled', true);
		
		if($("#guaranID").val()==""){
			alert('กรุณาระบุประเภทหลักประกัน');
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#numDeed").val()==""){
			alert('กรุณาระบุเลขที่โฉนดที่ดิน');
			$('#numDeed').focus();
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#proID").val()==""){
			alert('กรุณาเลือกจังหวัด');
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#amphur").val()==""){
			alert('กรุณาเลือกอำเภอ/เขต');
			$("#submitButton").attr('disabled', false);
			return false;
		}else if($("#district").val()==""){
			alert('กรุณาเลือกตำบล/แขวง');
			$("#submitButton").attr('disabled', false);
			return false;
		}
		
		var a=0;
		var y=0;
		var payment = [];
		for( i=1; i<=counter; i++ ){
			var cus1=$("#CusID"+i).val();
			if ( $("#CusID"+i).val() == ""){
				alert('กรุณาระบุเจ้าของกรรมสิทธิ์คนที่ '+i);
				$('#CusID'+ i).focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
			
			for(j=1;j<=counter;j++){
				var cus2=$("#CusID"+j).val();
				if(i==j){
					continue;
				}else{
					if(cus1==cus2){
						a=1;
						break;
					}else{
						y++;
					}
				}
			}
			if(a==1){
				break;
			}
			payment[i] = {cus : $("#CusID"+ i).val()};
		}
		if(a==1){
			alert('เจ้าของกรรมสิทธิ์ต้องไม่ซ้ำกัน กรุณาตรวจสอบค่ะ');
			$("#submitButton").attr('disabled', false);
			return false;
		}
    });
});
function check_num(evt) {
	//ให้ใส่จุดได้  ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 46 || charCode == 47 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</body>
</html>
