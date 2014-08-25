<?php
session_start();
include("../../config/config.php");		
$numid = pg_escape_string($_GET["numid"]); 

$qry_linksecur=pg_query("select * from \"nw_linksecur\" where numid='$numid'");
$res_linksecur=pg_fetch_array($qry_linksecur);
$note=$res_linksecur["note"];
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
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">
<form>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+ แก้ไขการเชื่อมโยงหลักทรัพย์ค้ำประกัน+</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
	<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.location='frm_IndexLinkEdit.php'"><u><--ย้อนกลับ</u></span></div>
	<!--<form name="frm_edit" method="post" action="#">-->
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210">รหัสเชื่อมโยง : </td>
			<td bgcolor="#FFFFFF"><input type="text" name="numid" id="numid" value="<?php echo $numid;?>" readonly="true"></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">หลักทรัพย์  : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_sec=pg_query("select * from \"nw_linknumsecur\" a
						left join \"nw_securities\" b on a.\"securID\"=b.\"securID\"
						where a.numid='$numid'");
						$num_sec=pg_num_rows($qry_sec);
						
						$i=1;
						while($res_sec=pg_fetch_array($qry_sec)){
							$cancel=$res_sec["cancel"];
							$securID=$res_sec["securID"];
							if($cancel=="t"){
								$txtcancel="<font color=red><b>(คืนหลักทรัพย์ให้ลูกค้าแล้ว)</b></font>";
							}else{
								$txtcancel="";
							}
					?>
						<tr>
							<td>
								<input type="hidden" name="numsec" id="numsec" value="<?php echo $num_sec;?>">
								<input type="checkbox" name="delsecur<?php echo $i;?>" id="delsecur<?php echo $i;?>" value="<?php echo $res_sec["securID"];?>"><font color="red">ลบ</font> เลขที่โฉนด <span onclick="javascript:popU('showdetail2.php?securID=<?php echo $res_sec["securID"]; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><?php echo $res_sec["numDeed"];?></u></span> เลขที่ดิน <?php echo $res_sec["numLand"];?> <?php echo $txtcancel?>
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
					<td align="right" width="210">(เพิ่ม)หลักทรัพย์</td>
					<td colspan="3" bgcolor="#FFFFFF">
						<input type="button" value="+ เพิ่ม" id="addButton"><input type="button" value="- ลบ" id="removeButton">
					</td>
				</tr>
			</table>
		</div>
		</div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="210">เลขที่สัญญา  : </td>
			<td colspan="3" bgcolor="#FFFFFF">
				<table width="100%" border="0" cellpadding="3" cellspacing="0" border="0">
					<?php
						$qry_idno=pg_query("select * from \"nw_linkIDNO\" where numid='$numid'");
						$num_idno=pg_num_rows($qry_idno);
						
						$i=1;
						while($res_idno=pg_fetch_array($qry_idno)){
							$IDNO=$res_idno["IDNO"];
							$qry_fp=pg_query("select \"P_ACCLOSE\" from \"Fp\" where \"IDNO\"='$IDNO'");
							$res_fp=pg_fetch_array($qry_fp);
							$P_ACCLOSE=$res_fp["P_ACCLOSE"];
							if($P_ACCLOSE=="t"){
								$txtclose="<font color=red><b>(ปิดบัญชีแล้ว)</b></font>";
							}else{
								$txtclose="";
							}
						
					?>
						<tr>
							<td>
								<input type="hidden" name="numidno" id="numidno" value="<?php echo $num_idno;?>">
								<input type="checkbox" name="delidno<?php echo $i;?>" id="delidno<?php echo $i;?>" value="<?php echo $res_idno["IDNO"]?>"><font color="red">ลบ</font> เลขที่สัญญา <span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $res_idno["IDNO"]; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="แสดงรายละเอียด"><u><?php echo $res_idno["IDNO"];?></u></span> วันที่ค้ำประกัน <?php echo $res_idno["guaranteeDate"]." ".$txtclose;;?>
							</td>
						</tr>
						<?php $i++;}?>
				</table>
			</td>
		</tr>
		</table>
		<div id='TextGroup1'>
		<div id="TextDiv1">
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="210">(เพิ่ม)เลขที่สัญญา</td>
			<td colspan="3" bgcolor="#FFFFFF">
				<input type="button" value="+ เพิ่ม" id="addButton2"><input type="button" value="- ลบ" id="removeButton2">
			</td>
		</tr>
		</table>
		</div>
		</div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top" width="210">หมายเหตุ : </td>
			<td colspan="3" bgcolor="#FFFFFF"><textarea name="note" id="note" cols="40" rows="5"><?php echo $note?></textarea></td>
		</tr>
		<tr>
			<td colspan="4" height="40" bgcolor="#FFFFFF" align="center"><input type="button" value="บันทึกข้อมูล" id="submitButton"><input type="reset" value="ยกเิลิก"></td>
		</tr>
		</table>
	<!--</form>-->
	</div>
</div>
</form>
<script type="text/javascript">
var counter = 1;

$(document).ready(function(){
	$('#addButton').click(function(){
    counter++;
    console.log(counter);
	var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter);
    table = '<table width="785" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#969696; margin-bottom:3px">'
	+ '	<tr bgcolor="#E8E8E8">'
	+ '		<td align="right" width="206">ค้นจากเลขที่โฉนด</td>'
	+ '		<td colspan="3" bgcolor="#FFFFFF">'
	+ '			<input type="text" name="securID'+ counter +'" id="securID'+ counter +'" size="30"/>'
	+ '		</td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextBoxesGroup1");

		
		$("#securID"+counter).autocomplete({
			source: "s_secur2.php",
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
	+ '		<td align="right" width="206">ค้นจากเลขที่สัญญา</td>'
	+ '		<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="IDNO'+ counter2 +'" id="IDNO'+ counter2 +'" size="30">'
	+ '		<b>วันที่ค้ำประกัน:</b> <input type="text" id="guaranteeDate'+ counter2 +'" name="guaranteeDate'+ counter2 +'" value="" size="15" readonly="true" style="text-align:center;"></td>'
	+ '	</tr>'
	+ '	</table>'
	
        newTextBoxDiv.html(table);

        newTextBoxDiv.appendTo("#TextGroup1");

		$("#guaranteeDate"+counter2).datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
		});
		
		$("#IDNO"+counter2).autocomplete({
			source: "s_idno.php",
			minLength:1
		});
	
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
	
	$("#securID1").autocomplete({
			source: "s_secur2.php",
			minLength:1
	});
	
	$("#IDNO1").autocomplete({
			source: "s_idno.php",
			minLength:1
	});
		
	$("#guaranteeDate1").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
	});
    
	$("#submitButton").click(function(){
        $("#submitButton").attr('disabled', true);
		var payment = [];
		var payment2 = [];
		var payment3 = [];
		var payment4 = [];
		
		var numsec = $("#numsec").val();
		var numidno = $("#numidno").val();
		
		for( i=1; i<=counter; i++ ){
			if ( $("#securID"+i).val() == ""){
				alert('กรุณาระบุหลักทรัพย์ ');
				$('#securID'+ i).focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
			payment[i] = {securID : $("#securID"+ i).val()};
		}
		
		for( j=1; j<=counter2; j++ ){
			if ( $("#IDNO"+j).val() == ""){
				alert('กรุณาระบุเลขที่สัญญา ');
				$('#IDNO'+ j).focus();
				$("#submitButton").attr('disabled', false);
				return false;
			}
			payment2[j] = {IDNO : $("#IDNO"+ j).val(),guaranteeDate : $("#guaranteeDate"+ j).val()};
		}
        
		for( p=1; p<=numsec; p++ ){	
			if($('#delsecur'+p) .attr( 'checked' )){
				$("#submitButton").attr('disabled', false);
				payment3[p] = {delsecur : $("#delsecur"+ p).val()};	
			}	
		}
		
		for( t=1; t<=numidno; t++ ){	
			if($('#delidno'+t) .attr( 'checked' )){
				$("#submitButton").attr('disabled', false);
				payment4[t] = {delidno : $("#delidno"+ t).val()};	
			}	
		}
		$.post("process_linksecur.php",{
			cmd : "edit",
			numid : <?php echo $numid;?>, 
			note :$("#note").val(),
			payment : JSON.stringify(payment),
			payment2 : JSON.stringify(payment2),
			payment3 : JSON.stringify(payment3),
			payment4 : JSON.stringify(payment4)
		},
		function(data){
			if(data == "1"){
				alert("บันทึกรายการเรียบร้อย");
				location.href = "frm_IndexLinkEdit.php";
				$("#submitButton").attr('disabled', false);
			}else if(data == "2"){
				alert("ผิดผลาด ไม่สามารถบันทึกได้!");
				$("#submitButton").attr('disabled', false);
			}else if(data=="3"){
				alert("ลำดับที่นี้กำลังรออนุมัติหรือมีอยู่ในระบบแล้วค่ะ");
				$('#number_running').select();
				$("#submitButton").attr('disabled', false);
			}else if(data=="4"){
				alert("หลักทรัพย์บางตัวผิดพลาด !! เพื่อป้องกันการผิดพลาดกรุณาเลือกหลักทรัพย์ที่ระบบกำหนดให้ค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="5"){
				alert("หลักทรัพย์บางตัวซ้ำกัน กรุณาเลือกใหม่อีกครั้งค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="6"){
				alert("เลขที่สัญญาบางตัวไม่พบในระบบ!! เพื่อป้องกันการผิดพลาดกรุณาเลือกเลขที่สัญญาที่ระบบกำหนดให้ค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="7"){
				alert("เลขที่สัญญาบางตัวซ้ำกัน กรุณาเลือกใหม่อีกครั้งค่ะ");
				$("#submitButton").attr('disabled', false);
			}else if(data=="8"){
				alert("เลขที่สัญญาบางตัวซ้ำกับเลขที่สัญญาเดิม กรุณาเลือกใหม่อีกครั้งค่ะ");
				$("#submitButton").attr('disabled', false);
			}else{
				alert(data);
				$("#submitButton").attr('disabled', false);
			}
			
		});
    });
});
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

</script>
</body>
</html>
