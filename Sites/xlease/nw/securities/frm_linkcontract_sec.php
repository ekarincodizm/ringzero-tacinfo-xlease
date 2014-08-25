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
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<form name="frm" action="process_linkidno_secur.php" method="POST">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ เพิ่มการเชื่อมโยงสัญญากับหลักทรัพย์+</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.location='frm_IndexLink.php'"><u><--ย้อนกลับ</u></span></div>

		
		<div id='TextBoxesGroup1'>
		<div id="TextBoxDiv1">
			<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
				<tr bgcolor="#FFFFCC">
					<td align="right" width="210">หลักทรัพย์ที่ 1 (ค้นจากเลขที่โฉนด) : </td>
					<td colspan="3" bgcolor="#FFFFFF">
						<input type="text" name="securID[]" id="securID1" size="75"/><input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton">
					</td>
				</tr>
			</table>
		</div>
		
		
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210">ประเภท : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<select name="typecon[]" id="typecon1">
					<option value="1">ค้ำประกัน</option>
					<option value="2">จำนอง</option>
				</select>
			</td>				
		</tr>
		</table>
		<table width="785" border="0" id="tbhide" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
			<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210">ครั้งที่ : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<select name="time[]" id="time">
					<option value="0">ครั้งแรก</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
			</td>
			<td align="right"  bgcolor="#E8E8E8"> วงเงินจำนอง : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="text" name="money[]" id="money1" size="15"><font color="red">*</font>
			</td>
			<td align="right" bgcolor="#E8E8E8"> ดอกเบี้ยหากผิดนัด : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="text" name="interest[]"  id="interest1" size="15"><font color="red">*</font>
			</td>				
		</tr>
		<tr> 
			<td align="right"  bgcolor="#E8E8E8"> วันที่ประกัน : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="text" name="datein[]" id="datein1" size="10"><font color="red">*</font>
			</td>
			<td align="right" bgcolor="#E8E8E8"> วันที่ยกเลิกประกัน : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="text" name="dateout[]" id="dateout1" size="10">
			</td>
				
		</tr>
		</table>
		</div>
		<div id="TextDiv1">
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#66FFCC">
			<td align="right" width="210">เลขที่สัญญา (ค้นจากเลขที่สัญญา) : </td>
			<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="IDNO1" id="IDNO1" size="30"> </td>				
		</tr>
		</table>
		</div>
		
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="210">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5"></textarea></td>
		</tr>
		<tr>
			<td colspan="4" height="40" bgcolor="#FFFFFF" align="center"><input type="submit" value="บันทึกข้อมูล" id="submitButton"><input type="reset" value="ยกเลิก"></td>
		</tr>
		</table>
	<!--</form>-->
	</div>
</div>
</form>
<script type="text/javascript">
function check_num(evt) {
	//ให้เป็นตัวเลขเท่านั้น
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode < 48 || charCode >57) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		$('#number_running').focus();
		return false;
	}
	return true;
}




$(document).ready(function(){
var counter = 1;
	$('#addButton').click(function(){
    counter++;
    console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    table = '<table width="785" cellpadding="1" cellspacing="1" border="0" style="font-weight:bold;" bgcolor="#CECECE">'
	+ '	<tr bgcolor="#FFFFCC">'
	+ '		<td align="right" width="210"><b>หลักทรัพย์ที่ '+ counter +' (ค้นจากเลขที่โฉนด) :</b></td>'
	+ '		<td colspan="6" bgcolor="#FFFFFF">'
	+ '			<input type="text" name="securID[]" id="securID'+ counter +'" size="75"/>'
	+ '		</td>'
	+ '	</tr>'
	+'	<tr height="30" bgcolor="#E8E8E8">'
	+'		<td align="right" width="206">ประเภท : </td>'
	+'		<td colspan="6" bgcolor="#FFFFFF">'
	+'			<select name="typecon[]" id="typecon'+ counter +'" onchange="changetypena('+counter+')">'
	+'				<option value="1">ค้ำประกัน</option>'
	+'				<option value="2">จำนอง</option>'
	+'			</select>'
	+'		</td>		'		
	+'	</tr>'
	+'	</table>'	
	+'	<table width="785" border="0" id="tbhide'+ counter +'" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">'
	+'		<tr height="30" bgcolor="#E8E8E8">'
	+'		<td align="right" width="210">ครั้งที่ : </td>'
	+'		<td colspan="3" bgcolor="#FFFFFF">'
	+'			<select name="time[]" id="time">'
	+'				<option value="0">ครั้งแรก</option>'
	+'				<option value="1">1</option>'
	+'				<option value="2">2</option>'
	+'				<option value="3">3</option>'
	+'				<option value="4">4</option>'
	+'				<option value="5">5</option>'
	+'				<option value="6">6</option>'
	+'				<option value="7">7</option>'
	+'				<option value="8">8</option>'
	+'				<option value="9">9</option>'
	+'				<option value="10">10</option>'
	+'			</select>'
	+'		</td>'
	+'		<td align="right"  bgcolor="#E8E8E8"> วงเงินจำนอง : </td>'
	+'		<td colspan="3" bgcolor="#FFFFFF">'
	+'			<input type="text" name="money[]" id="money'+ counter +'" size="15"><font color="red">*</font>'
	+'		</td>'
	+'		<td align="right" bgcolor="#E8E8E8"> ดอกเบี้ยหากผิดนัด : </td>'
	+'		<td colspan="3" bgcolor="#FFFFFF">'
	+'			<input type="text" name="interest[]" id="interest'+ counter +'" size="15"><font color="red">*</font>'
	+'		</td>		'		
	+'	</tr>'
	+'	<tr> '
	+'		<td align="right"  bgcolor="#E8E8E8"> วันที่ประกัน : </td>'
	+'		<td colspan="3" bgcolor="#FFFFFF">'
	+'			<input type="text" name="datein[]" id="datein'+ counter +'" size="10"><font color="red">*</font>'
	+'		</td>'
	+'		<td align="right" bgcolor="#E8E8E8"> วันที่ยกเลิกประกัน : </td>'
	+'		<td colspan="3" bgcolor="#FFFFFF">'
	+'			<input type="text" name="dateout[]" id="dateout'+ counter +'" size="10">'
	+'		</td>	'			
	+'	</tr>'
	+'	</table>'
	+'  </div>'
	

	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup1");

		
		$("#securID"+counter).autocomplete({
			source: "s_secur2.php",
			minLength:1
		});
		
		$("#tbhide"+ counter).hide();		
		
		
		$("#datein"+ counter).datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#dateout"+ counter).datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
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

	$("#securID1").autocomplete({
				source: "s_secur2.php",
				minLength:1
		});
		
		$("#IDNO1").autocomplete({
				source: "s_contractID.php",
				minLength:1
		});
		
	$("#submitButton").click(function(){

		var messageeror = "";
		var messageeror1 = 0;
		var messageeror2 = 0;
		var messageeror3 = 0;
		for( i=1; i<=counter; i++ ){
			if($('#typecon'+i).attr('value')=='2'){
			
				if($('#money'+i).attr('value')==""){
					messageeror1++;
				}
				if($('#interest'+i).attr('value')==""){
					messageeror2++;
				}
				if($('#datein'+i).attr('value')==""){
					messageeror3++;
				}
				
			}	
		}	
			if(messageeror1 != 0){
				messageeror += '\n----> กรุณากรอก วงเงินจำนอง';
			}
			if(messageeror2 != 0){
				messageeror += '\n----> กรุณากรอก ดอกเบี้ยหากผิดนัด';
			}
			if(messageeror3 != 0){
				messageeror += '\n----> กรุณากรอก วันที่ประกัน ';
			}

				if(messageeror != ""){
					alert(messageeror);
					return false;
				}else{
					return true;
				}
					
	});	
		

		
});	

function changetypena(id){

	    if($('#typecon'+ id).attr('value')=='1'){
			$("#tbhide"+ id).hide();
		}
		else if($('#typecon'+ id).attr('value')=='2'){
			$("#tbhide"+ id).show();
		}
		else{
			$("#tbhide"+ id).hide();
		}
		
};




$(document).ready(function(){

	$("#datein1").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#dateout1").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });

$("#tbhide").hide();		
	$("#typecon1").change(function(){

		if($('#typecon1').attr('value')=='1'){
			$("#tbhide").hide();
		}
		else if($('#typecon1').attr('value')=='2'){
			$("#tbhide").show();
		}
		else{
			$("#tbhide").hide();
		}
		
	});
});
</script>