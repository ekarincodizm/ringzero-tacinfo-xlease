<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) ส่งใบแจ้งหนี้เงินกู้-ค่าเช่า</title>

<link type="text/css" rel="stylesheet" href="act.css"></link>
    
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>  
<link href="styles/list_tab.css" rel="stylesheet" type="text/css" />
<script language=javascript>
$(function(){
	var tab_id = $('.active').find('a').attr('id');
	$('.list_tab_menu').load('list_tab_debtnotice.php?tabid='+tab_id+'&s=1');
	$('.list_tab_menu2').load('list_tab_debtnotice.php?tabid='+tab_id+'&s=2');
	
	//ดึง tab ขึ้นมาแสดง
	$('#tab_debtnotice').load('tab_debtnotice.php?s=1',function(){
		list_tab_menu('0',1);
	});
	$('#tab_debtnotice2').load('tab_debtnotice.php?s=2',function(){
		list_tab_menu('01',2);
	});
});
function list_tab_menu(tab_id,s){
	if(s==1){	//กรณีเป็น ใบแจ้งหนี้ที่ถึงกำหนดส่ง
		$('.tab.active').removeClass('active');
	}else if(s==2){ //กรณีเป็น ใบแจ้งหนี้ที่พิมพ์แล้วรอส่ง
		$('.tab2.active').removeClass('active');
	}
	$('#'+tab_id).parent().addClass('active');
	
	//ให้ดึงรายการตาม tab มาแสดง
	if(s==1){
		$('.list_tab_menu').load('list_tab_debtnotice.php?tabid='+tab_id+"&s=1");
	}else if(s==2){
		$('.list_tab_menu2').load('list_tab_debtnotice.php?tabid='+tab_id+"&s=2");	
	}
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function selectAll(select,frm){
	var chkfrm=frm;
	if(frm=='frm'){
		frm=document.frm;
		var eleselect=$('select[name="addtxtdebt[]"]');
		var ele=$('input[name="checkdebt[]"]');
	}else{
		frm=document.frm2;
		var ele=$('input[name="checkdebt2[]"]');
	}
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
			if(chkfrm=='frm'){
				$(eleselect[i]).removeAttr('disabled');
			}
		}
	}else if(num>0 && ele.length==num){
		for (i=0; i< ele.length; i++){
			 $(ele[i]).removeAttr('checked');
			if(chkfrm=='frm'){
				$(eleselect[i]).attr('disabled','disabled');
			}
		}
	}else{
		for (i=0; i< ele.length; i++){
			$(ele[i]).attr ( "checked" ,"checked" );
			if(chkfrm=='frm'){
				$(eleselect[i]).removeAttr('disabled');
			}
		}
	}
}

function app(frm)
{

var con = $("#chkchoise").val();
var ele=$('input[name="checkdebt[]"]');

var numchk;
numchk = 0;
	for(i = 0;i<con;i++){	
		if($(ele[i]).is(':checked')){
			numchk+=1;			
		}		
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		frm.action="process_debtnotice.php";
		frm.submit();		
	}	
}
function app2(frm)
{

var con = $("#chkchoise2").val();
var ele=$('input[name="checkdebt2[]"]');
var numchk;
numchk = 0;
	for(i = 0;i<con;i++){	
		if($(ele[i]).is(':checked')){
			numchk+=1;					
		}	
	}
	if(numchk == 0){
		alert("กรุณาเลือกรายการก่อน");
	}else{
		frm.action="process_debtnotice.php";
		frm.submit();		
	}	
}
function chkclick(a){
	var ele=$('input[name="checkdebt[]"]');
	var eleselect=$('select[name="addtxtdebt[]"]');
	
	for(i=0;i<ele.length;i++){
		if($(ele[i]).is(':checked')){
			$(eleselect[i]).removeAttr('disabled');
			
		}else{
			$(eleselect[i]).attr('disabled','disabled');
		}
	}
}
</script>
</head>

<body>
<div>
    <div style="width:1100px;margin:0px auto;">
        <h1>(THCAP) ส่งใบแจ้งหนี้เงินกู้-ค่าเช่า</h1>
        <fieldset style="padding:15px;">
            <legend><b>ใบแจ้งหนี้ที่ถึงกำหนดส่ง</b></legend>
			<form name="frm" method="post">
				<div id="tab_debtnotice"></div>
			</form>
        </fieldset>
	</div>
	<div style="width:1100px;margin:0px auto;">
		<div style="padding-top:20px">
			<?php
			$showall=2;
			include ("frm_history_cancelsent_invoice.php");
			?>
		</div>
    </div>
	<div style="width:1100px;margin:0px auto;">
		<div style="padding-top:20px">
		<form name="frm2" method="post">
		<fieldset style="padding:15px;">
            <legend><b>ใบแจ้งหนี้ที่พิมพ์แล้วรอส่ง</b></legend>
			<div id="tab_debtnotice2"></div>
        </fieldset>
		</form>
		</div>
    </div>
</div>

</body>
</html>