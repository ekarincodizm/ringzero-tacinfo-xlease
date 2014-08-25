<?php
session_start();
include("../../config/config.php");	
$secur=$_POST["securID"];	
if($secur==""){
	$secur=$_GET["securID"];
}
$securID=explode("#",$secur);

$qry_temp=pg_query("select * from \"temp_securities\" where \"securID\"='$securID[0]' and \"statusApp\"='2'");
$num_temp=pg_num_rows($qry_temp);
if($num_temp>0){
	echo "<div align=center style=\"padding:30px;\"><b>อยู่ในระหว่างรออนุมัติแก้ไขข้อมูล</b></div>";
	echo "<meta http-equiv='refresh' content='3; URL=frm_IndexEdit.php'>";
}else{
$qry_sec=pg_query("select * from \"nw_securities\" where \"securID\" ='$securID[0]' ");
$res_sec=pg_fetch_array($qry_sec);
$guaranID=trim($res_sec["guaranID"]);
$numDeed=trim($res_sec["numDeed"]);
$numBook=trim($res_sec["numBook"]);
$numPage=trim($res_sec["numPage"]);
$numLand=trim($res_sec["numLand"]);
$pageSurvey=trim($res_sec["pageSurvey"]);
$district=trim($res_sec["district"]);
$proID=trim($res_sec["proID"]);	
$area_acre=trim($res_sec["area_acre"]);	
$area_ngan=trim($res_sec["area_ngan"]);
$area_sqyard=trim($res_sec["area_sqyard"]);	
$edittime=trim($res_sec["edittime"]);
$note=$res_sec["note"];

$condoroomnum=$res_sec["condoroomnum"];
$condofloor = $res_sec["condofloor"]; 
$condobuildingnum = $res_sec["condobuildingnum"];
$condobuildingname = $res_sec["condobuildingname"];
$condoregisnum = $res_sec["condoregisnum"];
$area_smeter=trim($res_sec["area_smeter"]);	

$qry_sec_addr=pg_query("select * from \"nw_securities_address\" where \"securID\" ='$securID[0]' "); // ดึงที่อยู่มาด้วยนะ
$res_sec_addr=pg_fetch_array($qry_sec_addr);
			$S_BUILDING=trim($res_sec_addr["S_BUILDING"]);
			$S_ROOM=trim($res_sec_addr["S_ROOM"]);
			$S_FLOOR=trim($res_sec_addr["S_FLOOR"]);
			$S_SOI=trim($res_sec_addr["S_SOI"]);
			$S_RD=trim($res_sec_addr["S_RD"]);
			$S_TUM=trim($res_sec_addr["S_TUM"]);	
			$S_AUM=trim($res_sec_addr["S_AUM"]);	
			$S_PRO=trim($res_sec_addr["S_PRO"]);	
			$S_POST=trim($res_sec_addr["S_POST"]);
			$S_NO=trim($res_sec_addr["S_NO"]);
			$S_SUBNO=trim($res_sec_addr["S_SUBNO"]);
			$S_VILLAGE=trim($res_sec_addr["S_VILLAGE"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
  <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
  <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>

<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
}
.style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
}

-->
</style>

<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #FF0000;
	font-weight: bold;
}


#warppage
{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
}

-->
</style>
<!-- InstanceEndEditable -->
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<form name="form1"  method="post" action="process_securities.php"  enctype="multipart/form-data">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ แก้ไขหลักทรัพย์ +</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"></div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="200">ประเภทหลักประกัน : </td>
			<td bgcolor="#FFFFFF">
				<?php
				if($guaranID=="1"){
					echo "ที่ดิน";
				}else if($guaranID=="3"){
					echo "ที่ดินพร้อมสิ่งปลูกสร้าง";
				}else{
					echo "ห้องชุด";
				}
				?>
			</td>
			<td align="right">โฉนดที่ดินเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numDeed" id="numDeed" value="<?php echo $numDeed?>" /></td>
		</tr>
		<?php
		if($guaranID=="1" || $guaranID=="3"){
		?>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เล่มที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numBook" id="numBook" value="<?php echo $numBook?>" onkeypress="return check_num(event);"/></td>
			<td align="right">หน้าที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numPage" id="numPage" value="<?php echo $numPage?>" onkeypress="return check_num(event);"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เลขที่ดิน : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numLand" id="numLand" value="<?php echo $numLand?>" /></td>
			<td align="right">หน้าสำรวจ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="pageSurvey" id="pageSurvey" value="<?php echo $pageSurvey?>"  onkeypress="return check_num(event);"/></td>
		</tr>
		<?php }else{?>
		<!--รายละเอียดทีห้องชุด -->
		<tr height="30" bgcolor="#E8E8E8" id="condo1">
			<td align="right">ห้องชุดเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoroomnum" id="condoroomnum"  value="<?php echo $S_ROOM?>"/></td>
			<td align="right">ชั้นที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condofloor" id="condofloor"  value="<?php echo $S_FLOOR?>"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo2">
			<td align="right">อาคารเลขที่ : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condobuildingnum" id="condobuildingnum"  value="<?php echo $condobuildingnum?>"/></td>
			<td align="right">ทะเบียนอาคารชุด : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="condoregisnum" id="condoregisnum"  value="<?php echo $condoregisnum?>"/></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8" id="condo3">
			<td align="right">ชื่ออาคารชุด : </td>
			<td bgcolor="#FFFFFF" colspan="3"><input type="text" name="condobuildingname" id="condobuildingname" size="50"  value="<?php echo $S_BUILDING?>"/></td>
		</tr>
		<?php }?>
		
		
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">เนื้อที่ : </td>
			<?php if($guaranID=="1"){?>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="text" name="area_acre" id="area_acre" size="10" value="<?php echo $area_acre?>" onkeypress="return check_num(event);"/> ไร่
				<input type="text" name="area_ngan" id="area_ngan" size="10" value="<?php echo $area_ngan?>" onkeypress="return check_num(event);"/> งาน
				<input type="text" name="area_sqyard" id="area_sqyard" size="10" value="<?php echo $area_sqyard?>" onkeypress="return check_num(event);"/> ตารางวา
				
			</td>
			<?php }else{?>
			<td colspan="3" bgcolor="#FFFFFF" id="condo4">
				<input type="text" name="area_smeter" id="area_smeter" size="10" value="<?php echo $area_smeter?>" onkeypress="return check_num(event);"/> ตารางเมตร
			</td>
			<?php }?>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">เจ้าของกรรมสิทธิ์ : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_cus=pg_query("select * from \"nw_securities_customer\" a
						left join \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\"
						where \"securID\" ='$securID[0]' ");
						$numcus=pg_num_rows($qry_cus);
						
						$i=1;
						while($res_cus=pg_fetch_array($qry_cus)){
					?>
						<tr>
							<td>
								<input type="hidden" name="numcus" id="numcus" value="<?php echo $numcus;?>">
								<input type="checkbox" name="delcus[]" id="Cus<?php echo $i;?>" value="<?php echo $res_cus["CusID"]?>"><font color="red">ลบ</font> <?php echo $res_cus["full_name"];?>
							</td>
						</tr>
						<?php $i++;}?>
				</table>
			</td>
		</tr>
		</table>
		<div id='TextBoxesGroup1'>
		<div id="TextBoxDiv1">
			<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" >
				<tr bgcolor="#E8E8E8">
					<td align="right" width="200">(เพิ่ม) เจ้าของกรรมสิทธิ์</td>
					<td colspan="3" bgcolor="#FFFFFF">
						<input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton">
					</td>
				</tr>
			</table>
		</div>
		</div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="200">ภาพโฉนด : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_up=pg_query("select * from \"nw_securities_upload\" where \"securID\" ='$securID[0]' ");	
						$numpic=pg_num_rows($qry_up);
						$i=1;
						while($res_up=pg_fetch_array($qry_up)){
						$upload=$res_up["upload"];
					?>
						<tr>
							<td>
								<input type="hidden" name="numpic" id="numpic" value="<?php echo $numpic;?>">
								<input type="checkbox" name="delpic[]" id="delpic<?php echo $i;?>" value="<?php echo $upload?>"><font color="red">ลบ</font><a href="<?php echo "upload/$upload";?>" target="_blank"> <u>โฉนดที่ <?php echo $i;?> (<?php echo $upload;?>)</u></a>
							</td>
						</tr>
					<?php
						$i++;
					}
					?>
				</table>
			</td>
		</tr>
		</table>
		<div id='TextGroup1'>
		<div id="TextDiv1">
			<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE">
				<tr bgcolor="#E8E8E8">
					<td align="right" width="200">(เพิ่ม) ภาพโฉนด</td>
					<td colspan="3" bgcolor="#FFFFFF">
						<input type="button" value="+ เพิ่ม" id="addButton2"><input type="button" value="- ลบ" id="removeButton2"><font color="red"> (ชื่อไฟล์เป็นภาษาอังกฤษ และขนาดไม่เกิน 2 MB)</font>
					</td>
				</tr>
			</table>
		</div>
		</div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		
		<tr>
			<td colspan="4">
				<table width="780" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
					<tr><td colspan="4" align="center"><h2> ที่อยู่หลักทรัพย์ </h2></td></tr>
					<tr height="30" bgcolor="#E8E8E8">
				<?php
		if($guaranID=="1" || $guaranID=="3"){
				?>	
					<tr height="30" bgcolor="#E8E8E8" id="land4">
						<td align="right" width="200">บ้านเลขที่ : </td>
						<td bgcolor="#FFFFFF" width="200"><input type="text" name="s_no" id="s_no" value="<?php echo $S_NO ?>"/></td>
						<td align="right" width="200">หมู่ : </td>					
						<td bgcolor="#FFFFFF"><input type="text" name="s_subno" id="s_subno" value="<?php echo $S_SUBNO ?>"/></td>
					</tr>
					<tr height="30" bgcolor="#E8E8E8"  id="land5">
						<td align="right" width="200">หมู่บ้าน : </td>
						<td bgcolor="#FFFFFF" width="200" colspan="3"><input type="text" name="s_village" id="s_village" size="35" value="<?php echo $S_VILLAGE ?>"/></td>
					</tr>
		<?php } ?>			
					
						<td align="right" width="200">ซอย : </td>
						<td bgcolor="#FFFFFF" width="200"><input type="text" name="soi" id="soi" value="<?php echo $S_SOI ?>"/></td>
						<td align="right" width="200">ถนน : </td>					
						<td bgcolor="#FFFFFF"><input type="text" name="rd" id="rd" value="<?php echo $S_RD ?>"/></td>
					</tr>
					<tr height="30" bgcolor="#E8E8E8">
					<type type="hidden" name="amphurhid" id="amphurhid" value="<?php echo $S_AUM ;?>">
					<type type="hidden" name="tumhid" id="tumhid" value="<?php echo $S_TUM ;?>">
					
						<td align="right">จังหวัด : </td>
						<td bgcolor="#FFFFFF">
							<select name="proID" id="proID" onchange="calamp()">
								<option value="">---เลือก---</option>
								<?php
									$qry_pro=pg_query("select * from \"nw_province\" order by \"proID\"");
									while($res_pro=pg_fetch_array($qry_pro)){
										$proName=$res_pro["proName"];
										$proID=$res_pro["proID"]; ?>
										<option value="<?php echo $proID; ?>" <?php if($proID == $S_PRO){ echo "selected"; } ?> ><?php echo $proName ;?></option>
								<?php	
									}
								?>
							</select>
							<b><font color="red">*</font></b>							
						<td align="right">อำเภอ : </td>					
						<td bgcolor="#FFFFFF"><span id="spamphur">---</span><font color="red">*</font></td>
					</tr>
					<tr height="30" bgcolor="#E8E8E8">	
						<td align="right" onclick="caldis()" style="cursor:pointer"><u>ตำบล</u> : </td>
						<td bgcolor="#FFFFFF" ><span id="spdistrict"><?php echo $S_TUM?></span><font color="red">*</font></td>
						</td>
						<td align="right">รหัสไปรษณีย์ : </td>
						<td bgcolor="#FFFFFF"><input type="text" name="post" id="post" value="<?php echo $S_POST ?>"/></td>
					</tr>
				</table>	
			</td>
		</tr>
		
		
		
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="200">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5" ><?php echo $note;?></textarea></td>
		</tr>
		<tr>
			<td colspan="4" height="40" bgcolor="#FFFFFF" align="center">
			<input type="hidden" name="securID" id="securID" value="<?php echo $securID[0]?>">
			<input type="hidden" name="cmd" value="save">
			<input type="hidden" name="method" value="edit">
			<input type="hidden" name="guaranID" value="<?php echo $guaranID;?>">
			<input type="hidden" name="checkcus" id="checkcus">
			<input type="submit" value="บันทึกข้อมูล" id="submitButton">
			<input type="button" value="BACK" onclick="window.location='frm_IndexEdit.php'">
			</td>
		</tr>
	</table>
	</div>
</div>
</form>

<!-- แก้ไขจากเดิมเน้อ เลือกจังหวัดอำเภอ ไรเงี้ย-->
<script type="text/javascript">
var provice = $('#proID option:selected').attr('value');
var aum = $('#amphurhid').attr('value');
	$("#spamphur").load("amphur_for_edit.php?proID="+provice+"&aum="+aum);

function calamp(){
var provice = $('#proID option:selected').attr('value');
var aum = $('#amphurhid').attr('value');
	$("#spamphur").load("amphur_for_edit.php?proID="+provice+"&aum="+aum);
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
	+ '		<td align="right" width="198"></td>'
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
    });
	
	var counter2=1;
	$('#addButton2').click(function(){
    counter2++;
    console.log(counter2);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextDiv' + counter2);
    table = '<table width="785" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr height="30" bgcolor="#E8E8E8">'
	+ '		<td align="right" width="198"></td>'
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
    });
	
	$("#CusID1").autocomplete({
			source: "s_cusid.php",
			minLength:1
	});
	
	$("#submitButton").click(function(){
		$("#submitButton").attr('disabled', true);
				
		if($("#numDeed").val()==""){
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

		for( i=2; i<=counter; i++ ){
			var cus1=$("#CusID"+i).val();
			if ( $("#CusID"+i).val() == ""){
				alert('กรุณาระบุเจ้าของกรรมสิทธิ์คนที่ '+i);
				$('#CusID'+ i).focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
			
		
			for(j=2;j<=counter;j++){
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
</script>
</body>
</html>
<?php } ?>
