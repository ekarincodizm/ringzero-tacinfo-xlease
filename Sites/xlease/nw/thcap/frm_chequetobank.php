<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$current_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) นำเช็คเข้าธนาคาร</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<link href="list_tab.css" rel="stylesheet" type="text/css" />
<script language="Javascript">
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_chequetobank.php?tabid='+tab_id+'&s=1');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_chequetobank').load('tab_chequetobank.php?s=1',function(){
		list_tab_menu('0',1);
	});
});
function ok(frm){
	var ele=$('input[name="cid[]"]');
	var numchk;
	numchk = 0;
	for(i = 0;i<ele.length;i++){	
		if($(ele[i]).is(':checked')){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		frm.action="add_chequetobank.php";
		frm.submit();	
	}
}
function list_tab_menu(tab_id,s){
	if(s==1){	//กรณีเป็น ใบแจ้งหนี้ที่ถึงกำหนดส่ง
		$('.tab.active').removeClass('active');
	}
	$('#'+tab_id).parent().addClass('active');
	
	//ให้ดึงรายการตาม tab มาแสดง
	if(s==1){
		$('.list_tab_menu').load('list_tab_chequetobank.php?tabid='+tab_id+"&s=1");
	}
}
function selectAll(select){
	frm=document.form2;
	var eleselect=$('input[name="result[]"]');
	var ele=$('input[name="cid[]"]');

	var num;
	num=0;
	for (i=0; i< ele.length; i++){
		if($(ele[i]).is(':checked')){
			num+=1;
		}
	}
		
	if(num>0 && ele.length!=num){
		for (i=0; i< ele.length; i++){
			$(ele[i]).attr ( "checked" ,"checked" );
			$(eleselect[i]).removeAttr('disabled');

		}
	}else if(num>0 && ele.length==num){
		for (i=0; i< ele.length; i++){
			 $(ele[i]).removeAttr('checked');
			$(eleselect[i]).attr('disabled','disabled');
		}
	}else{
		for (i=0; i< ele.length; i++){
			$(ele[i]).attr ( "checked" ,"checked" );
			$(eleselect[i]).removeAttr('disabled');
		}
	}
}

<!--
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function processclick(a){
	var ele=$('input[name="cid[]"]');
	
	for(i=0;i<ele.length;i++){
		if(document.getElementById("cid"+i).checked){	
			document.getElementById("result"+i).disabled=false;
			document.getElementById("result"+i).focus();
		}else{
			document.getElementById("result"+i).disabled=true;	
			document.getElementById("result"+i).value='';			
		}
	}
}

//-->
</script>
</head>

<body>
<form method="post" name="form2">
<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h2>(THCAP) นำเช็คเข้าธนาคาร</h2></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u>
					<div><font color="red"> <span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></div>
					<div style="padding-top:5px;"><font color="red"> <span style="background-color:#FFEBCD;">&nbsp;&nbsp;&nbsp;</span> รายการสีส้ม คือ เช็คคืนรอจัดการ</font></div>
				</td>
			</tr>
			</table>
			<div id="tab_chequetobank"></div>
		</div>
	</td>
</tr>
</table>
<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div style="padding-top:100px;"></div>
		<fieldset><legend align="center"><font size="5px;"><b>เช็ครอนำเข้าธนาคาร</b></font>  <input type="button" id="show" value="แสดง"/> </legend>
		<div id="wait_chequetobank"><h3 id="showmsg" align="center" style="color:red;"></h3></div>
		</fieldset>
	</td>
</tr>
</table>
</form>
<script>
$('#show').click(function(){
	$('#showmsg').html('กำลังโหลดข้อมูล...')
	$('#wait_chequetobank').load("frm_chequetobank_wait.php",{
	},function(data){
		$('#showmsg').hide();
	});
	$('#show').hide();
});
</script>
</body>
</html>
