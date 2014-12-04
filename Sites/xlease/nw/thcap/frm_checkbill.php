<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$id_user = $_SESSION["av_iduser"];
$revTranID=$_GET["revTranID"];
$tranActionID=$_GET["tranActionID"];
$app=$_REQUEST["app"];

$BID=$_GET["BID"];
$dateRevStamp=$_GET["dateRevStamp"];

//หา level ของพนักงานคนนั้น
$qrylevel=pg_query("select \"ta_get_user_emplevel\"('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

// หาข้อมูลว่าไม่ใช่เงินของลูกค้า หรือไม่ ถ้าเป็น 1 คือไม่ใช่เงินของลูกค้า
$qry_isAnonymous = pg_query("select \"isAnonymous\" from finance.thcap_receive_transfer where \"revTranID\" = '$revTranID' ");
$isAnonymous = pg_result($qry_isAnonymous,0);

//ตรวจสอบว่าได้อนุมัติไปก่อนหน้านี้หรือไม่
if($app==1){ //กรณีบัญชีอนุมัติ
	$qrycheck=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"appvXID\" is null and \"bankRevAccID\"='$BID' and date(\"bankRevStamp\")='$dateRevStamp'");
	$num_chk=pg_num_rows($qrycheck);
	$txtapp="บัญชี";
}else{ //กรณีการเงินอนุมัติ
	$qrycheck=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"appvYID\" is null and \"revTranID\"='$revTranID'");
	$num_chk=pg_num_rows($qrycheck);
	$txtapp="การเงิน";
}

// หาความเป็นระดับบริหาร
$qry_isadmin = pg_query("select \"isadmin\" from \"fuser\" where \"id_user\" = '$id_user'");
$isadmin = pg_fetch_result($qry_isadmin,0);

if($num_chk == 0 AND $revTranID != 'bid_1'){ //แสดงว่าอนุมัติไปก่อนหน้านี้แล้ว
	echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";
	echo "<div align=center><h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!!</h2></div>";
}else{ //กรณียังไม่ได้อนุมัติก่อนหน้านี้
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ยืนยันรายการเงินโอน(<?php echo $txtapp;?>)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
$(document).ready(function(){
	$("#show").hide();
	$(".showdata").hide();
	$(".showapp3").hide();
	$("#showapp4").hide();
	$("#showrevtranstatus_id").hide();
	$("#loadrevtranstatus_tr").hide();
	$("#loadrefund").hide();
	
	
	// กรณี เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ  (กรณีที่รับเป็นเช็ค)
	$("#result2").click(function(){
		$("#show").show();
		$("#showrevtranstatus_id").show();
		$("#loadrevtranstatus_tr").show();
		$(".showapp3").show();
		$("#showapp4").show();
		$("#loadrefund").hide();
		document.getElementById("btn_appv").disabled = false;
		<?php
		if($app==1){ //กรณีบัญชีอนุมัติ
		?>
		$("#showremark").hide();
		$(".showdata").show();
		<?php
		}else{
		?>
			$(".showapp2").hide();
		<?php
		}
		?>
	});
	
	// กรณี เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ  (กรณีที่รับเป็นเงินโอน/เช็คนอก)
	$("#result5").click(function(){
		$("#show").show();
		$("#showrevtranstatus_id").show();
		$("#loadrevtranstatus_tr").show();
		$(".showapp3").hide();
		$("#showapp4").hide();
		$("#loadrefund").hide();
		<?php
		if($app==1){ //กรณีบัญชีอนุมัติ
		?>
		$("#showremark").hide();
		$(".showdata").show();
		<?php
		}else{
		?>
			$(".showapp2").hide();
		<?php
		}
		?>
	});
	
	$("#result1").click(function(){
		$("#show").hide();
		$("#showrevtranstatus_id").hide();
		$("#loadrevtranstatus_tr").hide();
		$(".showapp3").hide();
		$("#showapp4").hide();
		$("#loadrefund").hide();
		
		checkAdminConfirm(); // ตรวจสอบว่าสามารถกดปุ่มบันทึกได้หรือไม่
		
		<?php
		if($app==1){
		?>
		$("#showremark").show();
		$(".showdata").hide();
		<?php
		}else{
		?>
			$(".showapp2").show();
		<?php
		}
		?>
	});
	
	//กรณีเช็ค
	$("#result3").click(function(){
		$("#show").hide();
		$("#showrevtranstatus_id").hide();
		$("#loadrevtranstatus_tr").hide();
		$("#loadrefund").hide();
		$(".showapp2").hide();
		$(".showapp3").show();
		$("#showapp4").show();
	});
	
	//กรณี คืนเงินบุคคลภายนอก
	$("#result4").click(function(){
		$("#show").show();
		$("#showrevtranstatus_id").hide();
		$("#loadrevtranstatus_tr").hide();
		$("#loadrefund").show();
		$(".showapp2").hide();
		$(".showapp3").hide();
		$("#showapp4").hide();
	});
	
	$("#contractID").autocomplete({
		source: "s_contractID.php",
		minLength:1
	});
	
	$("#revChqID").autocomplete({
		source: "s_chequeID.php",
		minLength:1
	});
	
	$("#revtranstatus_ref").autocomplete({
		source: "s_subtype_desc.php",
		minLength:1
	});
	
	$("#dateContact").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#pcashdate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
});
function processchk(i){
	if(document.getElementById("chk"+i).checked==true){	
		document.getElementById("noresult"+i).disabled=false;
		document.getElementById("noresult"+i).focus();			
	}else{
		document.getElementById("noresult"+i).disabled=true;
	}
}
function checkremark(){
var con = $("#chkchoise").val();
var numchk;
var numresult=0;
numchk = 0;

<?php
if($app==2){
?> 
	if(document.getElementById("result1").checked==false 
	&& document.getElementById("result2").checked==false 
	&& document.getElementById("result3").checked==false
	&& document.getElementById("result4").checked==false
	&& document.getElementById("result5").checked==false){
		alert("กรุณาเลือกผลการตรวจสอบ");
		return false;	
	}
	<?php
	if(($revTranID=='bid_1') and ($tranActionID==-1)){
	?> 
	if(document.getElementById("pcashdate").value==""){
		alert("กรุณาเลือกวันที่รับชำระเงินสด");
		return false;	
	}
	if(document.getElementById("pcashamt").value==""){
		alert("กรุณาระบุจำนวนเงินที่จะชำระด้วยเงินสด");
		return false;	
	}
	<?php
	}
	?> 
	
	
	if(document.getElementById("result1").checked ==true){
		if(document.getElementById("contractID").value==""){
			alert("กรุณาระบุเลขที่สัญญา");
			document.getElementById("contractID").focus();
			return false;	
		}
		
		//if($('input[name="numcontract"]').val()==0){  กรณีเปลี่ยนเป็นเลือกใช้ name แทน id
		if($("#numcontract").val()==0){
			alert("กรุณาระบุเลขที่สัญญาให้ถูกต้อง");
			document.getElementById("contractID").focus();
			return false;
		}
		
		if(document.getElementById("dateContact").value==""){
			alert("กรุณาระบุวันที่ติดต่อ");
			return false;
		
		}
		if(document.getElementById("each").checked){
			if(document.getElementById("cuseach").value==""){
				alert("กรุณาระบุชื่อผู้ติดต่อ");
				document.getElementById("cuseach").focus();
				return false;
			}
		}
	}else if(document.getElementById("result2").checked ==true){
		if(document.getElementById("remark").value==""){
			alert("กรุณาระบุเหตุผล");
			document.getElementById("remark").focus();
			return false;
		}
		
		if(document.getElementById("revtranstatus_ref").value==""){
			alert("กรุณาระบุ ประเภทรายการ");
			document.getElementById("revtranstatus_ref").focus();
			return false;
		}
	}else if(document.getElementById("result5").checked ==true){
		if(document.getElementById("remark").value==""){
			alert("กรุณาระบุเหตุผล");
			document.getElementById("remark").focus();
			return false;
		}
		
		if(document.getElementById("revtranstatus_ref").value==""){
			alert("กรุณาระบุ ประเภทรายการ");
			document.getElementById("revtranstatus_ref").focus();
			return false;
		}
	}else if(document.getElementById("result3").checked ==true){ //กรณีเลือกเช็ค
		//ตรวจสอบว่ามีข้อมูลในระบบหรือไม่
		if(document.getElementById("chkchqid").value=="1"){
				alert("ไม่มี รหัสเช็ค นี้อยู่ในระบบ กรุณาป้อนรหัสเช็คใหม่ค่ะ ");
				return false;
		}
	
		if(document.getElementById("revChqID").value==""){
			alert("กรุณาระบุรหัสเช็ค");
			document.getElementById("revChqID").focus();
			return false;
		}
		
		if(document.getElementById("bankChqNo").value==""){
			alert("กรุณาระบุรหัสเช็คให้ถูกต้อง!!");
			document.getElementById("revChqID").focus();
			return false;
		}
		
		var bankchqamt=document.getElementById("bankchqamt").value; //จำนวนเงินในเช็ค
		var bankamt=document.getElementById("bankamt").value; //จำนวนเงินที่มาจากรายการโอนเงิน
		var str=document.getElementById("tariffval").value; //ค่าธรรมเนียม
		var tarifamt=str.replace(",","");	
		
		if(document.getElementById("tariff").checked==true){
			if(parseFloat(tarifamt)==0){
				alert("กรุณาระบุค่าธรรมเนียม");
				return false;
			}
			//กรณีพนักงานที่มี emplevel > 1 จำนวนเงินโอนและจำนวนเงินในเช็คต้องเท่ากัน
			if(document.getElementById("emplevel").value>1){ 
				if(parseFloat(bankchqamt)!=(parseFloat(bankamt)+parseFloat(tarifamt))){
					alert("จำนวนเงินโอนกับจำนวนเงินในเช็คไม่เท่ากัน กรุณาตรวจสอบ");
					return false;
				}
			}
		
		}else{
			//กรณีพนักงานที่มี emplevel > 1 จำนวนเงินโอนและจำนวนเงินในเช็คต้องเท่ากัน
			if(document.getElementById("emplevel").value>1){ 
				if(parseFloat(bankchqamt)!=(parseFloat(bankamt))){
					alert("จำนวนเงินโอนกับจำนวนเงินในเช็คไม่เท่ากัน กรุณาตรวจสอบ");
					return false;
				}
			}
		}
	}else if(document.getElementById("result4").checked ==true){ //คืนเงินบุคคลภายนอก
		if(document.getElementById("dcNoteDate").value==""){
			alert("กรุณาระบุวันที่รายการออกมีผล");
			return false;
		}
		
		if(document.getElementById("byChannel").value==""){
			alert("กรุณาระบุช่องทางการจ่าย");
			return false;
		}
		
		//กรณี "isTranPay" = 1 ต้องตรวจสอบเพิ่มเติม
		if($('#tranpay').val()==1)
		{
			//ถ้าเลือกคืนโดยเงินโอนจะต้องกรอกข้อมูลให้สมบูรณ์
			if($('input:radio[name=proviso_return]:checked').val() == 1)
			{
			
				if(document.getElementById("someOne11").checked==false && document.getElementById("someOne12").checked==false)
				{
					alert("กรุณาระบุเจ้าของบัญชี");
					return false
				}
				else if(document.getElementById("someOne11").checked == true && document.getElementById("returnTranToCus_have").value == "")
				{
					alert("กรุณาระบุชื่อเจ้าของบัญชี");
					return false
				}
				else if(document.getElementById("someOne12").checked == true && document.getElementById("returnTranToCus_no").value == "")
				{
					alert("กรุณาระบุชื่อเจ้าของบัญชี");
					return false
				}
				else
				{
					//ถ้าไม่ว่างต้องตรวจสอบข้อมูลว่างทีระบุนั้นถูกต้องตามระบบกำหนดหรือไม่
					if($('#cusid').val()=='no'){
						alert('กรุณาระบุเจ้าของบัญชีให้ถูกต้องตามที่ระบบกำหนด');
						$('#returnTranToCus').select();
						return false;
					}
				}
				
				if($('#returnTranToBank').val()==""){
					alert("กรุณาระบุรหัสธนาคาร");
					$('#returnTranToBank').focus();
					return false
				}else{
					//ถ้าไม่ว่างต้องตรวจสอบข้อมูลว่างทีระบุนั้นถูกต้องตามระบบกำหนดหรือไม่
					if($('#bankid').val()=='no'){
						alert('กรุณาระบุรหัสธนาคารให้ถูกต้องตามที่ระบบกำหนด');
						$('#returnTranToBank').select();
						return false;
					}
				}
				
				if($('#returnTranToAccNo').val()==""){
					alert("กรุณาระบุเลขที่บัญชีปลายทาง");
					$('#returnTranToAccNo').focus();
					return false
				}
			}
			else
			{ //ถ้าเลือกคืนโดยเช็คจะต้องกรอกเลขที่เช็คและวันที่บนเช็คให้สมบูรณ์
				if($('#returnChqNo').val()==""){
					alert("กรุณาระบุเลขที่เช็ค");
					$('#returnChqNo').focus();
					return false
				}
				
				if($('#returnChqDate').val()==""){
					alert("กรุณาระบุวันที่บนเช็ค");
					$('#returnChqDate').focus();
					return false
				}
				
				if(document.getElementById("someOne21").checked==false && document.getElementById("someOne22").checked==false)
				{
					alert("กรุณาระบุออกเช็คให้");
					return false
				}
				else if(document.getElementById("someOne21").checked == true && document.getElementById("returnChqCus_have").value == "")
				{
					alert("กรุณาระบุออกเช็คให้ใคร");
					return false
				}
				else if(document.getElementById("someOne22").checked == true && document.getElementById("returnChqCus_no").value == "")
				{
					alert("กรุณาระบุออกเช็คให้ใคร");
					return false
				}
			}	
		}
	}
<?php
}else{
?>
	
	if(document.getElementById("result2").checked){
		//ตรวจสอบว่ามีการเลือกรายการหรือไม่
		for(var num = 0;num<con;num++){	
			if(document.getElementById("chk"+num).checked){
				numchk+=1;			
			}		
		}
		if(numchk == 0){
			alert("กรุณาเลือกรายการก่อน");
			return false;
		}else{
			//กรณีมีการเลือกรายการ
			for(var num = 0;num<con;num++){	
				if(document.getElementById("chk"+num).checked){
					//ตรวจสอบว่ามีการระบุเหตุผลหรือไม่
					
					if(document.getElementById("noresult"+num).value==""){
						
						numresult+=1;
					}		
				}		
			}
		
			if(numresult>0){
				alert("กรุณาระบุเหตุที่ไม่อนุมัติรายการ");
				return false;
			}
		}
	}
	
	if(document.getElementById("result5").checked){
		//ตรวจสอบว่ามีการเลือกรายการหรือไม่
		for(var num = 0;num<con;num++){	
			if(document.getElementById("chk"+num).checked){
				numchk+=1;			
			}		
		}
		if(numchk == 0){
			alert("กรุณาเลือกรายการก่อน");
			return false;
		}else{
			//กรณีมีการเลือกรายการ
			for(var num = 0;num<con;num++){	
				if(document.getElementById("chk"+num).checked){
					//ตรวจสอบว่ามีการระบุเหตุผลหรือไม่
					
					if(document.getElementById("noresult"+num).value==""){
						
						numresult+=1;
					}		
				}		
			}
		
			if(numresult>0){
				alert("กรุณาระบุเหตุที่ไม่อนุมัติรายการ");
				return false;
			}
		}
	}
<?php
}
?>
	if(!confirm('ยืนยันการทำรายการหรือไม่')){return false;}
}

function sentvalue(){
	$("#loadspec").load("showcustomer.php?contractID="+$('#contractID').val());
}

function sentvalueRevtranstatus(){
	$("#loadrevtranstatus").load("show_revtranstatus.php?revtranstatus="+$('#revtranstatus_ref').val());
}

function sentvaluechq(){	
	$("#showdetailchq").load("showcheque.php?revChqID="+document.form1.revChqID.value+"&bankRevAmt="+document.form1.bankRevAmt.value);
}
function valuechq(){
	//ฟังก์ชั้นตรวจสอบว่ามีข้อมูลในระบบหรือไม่
	$.post('s_chkchqid.php',{revChqID:document.form1.revChqID.value},		
	function(data){	
		if(data=='1'){	
			//มีข้อมูลจริง
			document.form1.chkchqid.value='0';
		}
		else 
		{ 	//ไม่มีข้อมูล
			document.form1.chkchqid.value='1';
		}
	});
	
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function checkAdminConfirm() // ตรวจสอบว่าสามารถกดปุ่มบันทึกได้หรือไม่
{
	if(document.getElementById("checkContract").value == 'noContract' && document.getElementById("isAdminConfirm").value == 'no')
	{
		document.getElementById("btn_appv").disabled = true;
	}
	else
	{
		document.getElementById("btn_appv").disabled = false;
	}
}
</script>
</head>
<body>
<form method="post" name="form1" action="process_checkbill.php" enctype="multipart/form-data"><!--process_checkbill.php-->
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>รหัสรายการเงินโอน</td>
    <td>ประเภทการนำเข้า</td>
	<td>REF1</td>
    <td>REF2</td>
    <td>เลขที่บัญชี</td>
	<td>สาขา</td>
    <td>วันที่และเวลาที่นำเงินเข้าธนาคาร</td>
	<td>วันเวลาที่บันทึกรายการ</td>
    <td>จำนวนเงิน</td>
	<td><div class="showdata">รายการที่ไม่อนุมัติ</div></td>
	<td><div class="showdata">เหตุผลไม่อนุมัติ <font color="red">*</font></div></td>
</tr>
<?php
if($app==1){
	$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"appvXID\" is null and \"bankRevAccID\"='$BID' and date(\"bankRevStamp\")='$dateRevStamp' and \"revTranStatus\"<> '5' order by \"bankRevStamp\"");
	$numrows=pg_num_rows($query);
}else{
	$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"tranActionID\"='$tranActionID' order by \"bankRevStamp\"");
	$numrows=pg_num_rows($query);
}
$p=0;
while($resvc=pg_fetch_array($query)){
	$revTranID = $resvc['revTranID'];
	$cnID = $resvc['cnID'];
	$bankRevRef1 = $resvc['bankRevRef1'];
	$bankRevRef2 = $resvc['bankRevRef2'];
	$BID = $resvc['bankRevAccID'];
	$bankRevBranch = trim($resvc['bankRevBranch']);
	$bankRevStamp = trim($resvc['bankRevStamp']);
	$bankRevAmt = trim($resvc['bankRevAmt']);
	$doerStamp = $resvc['doerStamp'];
	
	//----- หาค่า REF1 และ REF2
		$REF1 = $bankRevRef1;
		$REF2 = $bankRevRef2;
		
		$REF1_checknull = checknull($REF1);
		$REF2_checknull = checknull($REF2);
		
		$qryinv=pg_query("SELECT ta_array1d_get(thcap_decode_invoice_ref($REF1_checknull, $REF2_checknull),0) as \"contractID\",
								ta_array1d_get(thcap_decode_invoice_ref($REF1_checknull, $REF2_checknull),1) as \"invoiceID\"");
		list($REF1_decode, $REF2_decode) = pg_fetch_array($qryinv);
		
		if($REF1_decode != "")
		{
			// ตรวจสอบว่ามีเลขที่สัญญาในระบบหรือไม่
			$qry_checkContract = pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$REF1_decode'");
			$row_checkContract = pg_num_rows($qry_checkContract);
			
			if($row_checkContract > 0) // ถ้ามีสัญญาอยู่จริง
			{
				$REF1 .= "<br/>(<font color=\"blue\" style=\"cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$REF1_decode','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')\"><u>$REF1_decode</u></font>)";
			}
			else // ถ้าไม่มีเลขที่สัญญาดังกล่าวในระบบ
			{
				$REF1 .= "<br/>(<font color=\"red\" style=\"cursor:pointer;\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$REF1_decode','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')\"><u>$REF1_decode</u></font>)";
				
				if($app == "1") // ถ้าเป็นหน้า บัญชี อนุมัติ
				{
					$checkContract = "noContract";
					$canAppv = "title=\"มีเลขที่สัญญาที่ไม่มีอยู่จริงในระบบ\" disabled";
				}
			}
		}
		
		if($REF2_decode != "")
		{
			$REF2 .= "<br/>(<font color=\"blue\" style=\"cursor:pointer;\" onclick=\"javascript:popU('Channel_detail_i.php?debtInvID=$REF2_decode','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=550')\"><u>$REF2_decode</u></font>)";
		}
	//----- จบการหาค่า REF1 และ REF2

	//ตรวจสอบว่ามีในรายการอนุมัติไม่ผ่านหรือไม่ ถ้ามีให้ึขึ้นแถบสีแดงด้วย เพื่ีอให้รู้ว่าเป็นรายการที่ถูกแก้ไข
	$qrynoapp=pg_query("SELECT * FROM finance.thcap_receive_transfer_noapptemp
	where \"revTranID\"='$revTranID' and \"statusEnd\"='1'");
	$numnoapp=pg_num_rows($qrynoapp);

	$i+=1;
	if($i%2==0){
		if($numnoapp>0){
			$color="#FFAEB9";
		}else{
			$color="#EDF8FE";
		}
		echo "<tr bgcolor=$color align=\"center\">";
	}else{
		if($numnoapp>0){
			$color="#FFAEB9";
		}else{
			$color="#D5EFFD";
		}
		echo "<tr bgcolor=$color align=\"center\">";
	}
	$qry_acc = pg_query("select * from \"BankInt\" where \"BID\" = '$BID'");
	if($re_acc = pg_fetch_array($qry_acc)){
		$BAccount = $re_acc['BAccount'];
		$BName = $re_acc['BName'];
		$bankRevAccID="$BAccount-$BName";
	}
	
?>
        <td height="30"><?php echo $revTranID; ?></td>
        <td align="center"><?php echo $cnID; ?></td>
		<td align="center"><?php echo $REF1; ?></td>
        <td align="center"><?php echo $REF2; ?></td>
        <td align="center"><?php echo $bankRevAccID; ?></td>
        <td><?php echo $bankRevBranch; ?></td>
        <td><?php echo $bankRevStamp; ?></td>
        <td><?php echo $doerStamp; ?></td>        
		<td align="right"><input type="hidden" id="emplevel" value="<?php echo $emplevel?>"><input type="hidden" id="bankamt" value="<?php echo $bankRevAmt; ?>"><?php echo number_format($bankRevAmt,2); ?></td>
		<td><div class="showdata"><input type="checkbox" name="chk[]" id="chk<?php echo $p;?>" value="<?php echo $revTranID;?>" onclick="processchk('<?php echo $p;?>')"></div></td>
		<td><div class="showdata"><input type="text" name="noresult[]" id="noresult<?php echo $p;?>" size="50" disabled="true"></div></td>
		</tr>
<?php
	$p++;
	$sumbankRevAmt += $bankRevAmt;
}
$sumbank = number_format($sumbankRevAmt,2);
if($app==1){ //ให้แสดงกรณีบัญชีอนุมัติ เพราะมีหลายรายการจึงต้องแสดงผลรวม แต่ถ้าการเงินอนุมัติจะแสดงแค่รายการเดียว
	echo "<tr><td colspan=\"8\" align=\"right\"><b>รวม </b></td><td align=\"right\"><b>$sumbank</b></td></tr>";
}
?>
</table>
<div style="padding:10px;"><b><span style="background-color:#FFAEB9;">&nbsp;&nbsp;&nbsp;&nbsp;</span> คือ รายการที่ไม่ผ่านการอนุมัติและถูกแก้ไข</b></div>

<table width="750" border="0" cellSpacing="1" cellPadding="1" bgcolor="#F4FED6" align="center">
<tr><td bgcolor="#049746" height="25" colspan="2"><font color="#FFFFFF">&nbsp;<b>ผลการตรวจ</b></font></td></tr>
<?php

if($app==1){ //ถ้าบัญชีอนุมัติ สถานะ 0 คือไม่อนุมัติ
	$statusapp=0;
}else{ //ถ้าการเงินอนุมัติ สถานะ 2 คือไม่ใช่ค่าสินค้าหรือบริการ
	$statusapp=2;
}

?>
<tr>
	<td align="right" width="105"><b>ผลการตรวจ :</b></td>
	<td height="20">
		<input type="radio" name="result" id="result1" value="1" <?php if($app==1){ echo "checked"; }?> <?php if($revTranID=='bid_1'){ echo "disabled"; }?>><?php if($app==2){ echo "รายการที่เป็นเงินโอน"; }else{ echo "อนุมัติ"; } ?> &nbsp;&nbsp;&nbsp;
		<?php if($app==2){ ?><input type="radio" name="result" id="result3" value="3" <?php if($revTranID=='bid_1'){ echo "disabled"; }?>> รายการที่เป็นเช็ค &nbsp;&nbsp;&nbsp;
		<?php } ?>
		<input type="radio" name="result" id="result2" value="<?php echo $statusapp;?>"> <?php if($app==1){ echo "ไม่อนุมัติ"; }else{ echo "เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ (จ่ายเช็ค)"; }?>
		<input type="radio" name="result" id="result5" value="<?php echo $statusapp;?>" <?php if($app==1){echo "hidden";}?> /> <?php if($app==1){ echo ""; }else{ echo "เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ (เงินโอน/เช็คนอกระบบ)"; }?>
		<?php if($isAnonymous == "1"){ echo"&nbsp;&nbsp;&nbsp;"; } ?>
		<input type="radio" name="result" id="result4" value="8" <?php if($isAnonymous != "1"){ echo "hidden"; } ?>>
		<?php if($isAnonymous == "1"){ echo "คืนเงินบุคคลภายนอก"; } ?>
	</td>
</tr>
<?php

if($app==2){ //กรณีการเงินอนุมัติให้กรอกข้อมูลเพิ่มเติมในส่วนนี้ด้วย

?>
<tr class="showapp2">
	<td align="right"><b>เลขที่สัญญาที่โอน :</b></td>
	<td height="30"><input type="text" name="contractID" id="contractID" size="40" onfocus="javascript : sentvalue()" ><font color="red"><b>*</b></font></td>
</tr>
<tr class="showapp2">
	<td align="right" valign="top"><b>ผู้ติดต่อ :</b></td>
	<td height="30" valign="top">
	<div id="loadspec"></div>
	</td>
</tr>
<tr class="showapp2">
	<td align="right"><b>วันเวลาที่ติดต่อ :</b></td>
	<td height="30">
	<input type="text" name="dateContact" id="dateContact" size="15" readonly="true">
	<select name="hh">
	<?php
	for($h=0;$h<24;$h++){
		if($h<10){
			$hh="0".$h;
		}else{
			$hh=$h;
		}
		echo "
		<option value=$hh>$hh</option>
		";
	}
	?>
	</select>:
	<select name="mm">
	<?php
	for($m=0;$m<60;$m++){
		if($m%5==0){
			if($m<10){
				$mm="0".$m;
			}else{
				$mm=$m;
			}
			echo "
			<option value=$mm>$mm</option>
			";
		}
	}
	?>
	</select>(ชั่วโมง:นาที)
	<font color="red"><b>*</b></font>
	</td>
</tr>
<tr class="showapp3"><td>&nbsp;</td><td><div style="padding:20px 0 0">(ค้นเลขที่สัญญา เลขที่เช็ค หรือจำนวนเงิน)</div></td></tr>
<tr class="showapp3">
	<td align="right"><b>รหัสเช็ค :</b></td>
	<td height="30"><input type="text" name="revChqID" id="revChqID" size="40" onfocus="javascript : sentvaluechq();valuechq()" onkeyup="valuechq()" onchange="valuechq()"><font color="red"><b>*</b></font></td>
	<input hidden name="chkchqid" id="chkchqid" value="0">
</tr>

<tr id="showapp4">
<td colspan="2">
	<div id="showdetailchq">
	<table width="600" border="0" cellSpacing="1" cellPadding="1" bgcolor="#FFDAB9" align="center" id="showapp5">
		<tr>
			<td align="right" width="150"><b>เลขที่เช็ค :</b></td>
			<td height="30"><span>-</span></td>
		</tr>
		<tr>
			<td align="right"><b>เลขที่สัญญา :</b></td>
			<td height="30"><span>-</span></td>
		</tr>
		<tr>
			<td align="right"><b>จำนวนเงิน :</b></td>
			<td height="30"><span>-</span></td>
		</tr>
	</table>
	</div>
</td>
</tr>

<!--แสดงข้อมูลให้กรอก กรณีเลือกเป็น  "เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ "  เลือกประเภทเงินที่ได้รับ-->
<tr id="showrevtranstatus_id">
	<td align="right"><b>ประเภทรายการ :</b></td>
	<td>
		<select name="revtranstatus_ref" id="revtranstatus_ref" onChange="sentvalueRevtranstatus();">
			<option selected="selected" value=''>-- เลือกประเภทรากการที่จะรับ --</option>
<?php
			$qry=pg_query("
				SELECT
					revtranstatussubtype_id, revtranstatussubtype_desc
				FROM
					finance.thcap_receive_transfer_status_subtype
				WHERE
					revtranstatus_id = '2' -- เฉพาะประเภทรายการที่ เป็น เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ
			");
			while($res=pg_fetch_array($qry)){
				list($revtranstatussubtype_id_option,$revtranstatussubtype_desc_option)=$res;
				echo "<option value=$revtranstatussubtype_id_option>$revtranstatussubtype_desc_option</option>";
			}
			$qry = NULL;
			$res = NULL;
?>
		</select>
		<span id="required_revtranstatus_ref"><font color="red"><b>*</b></font></span>
	</td>
</tr>

<!--แสดงข้อมูลให้กรอก กรณีเลือกเป็น  "เงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ "  รายละเอียดเพื่มเติมสำหรับ process ในระบบ หรือการบันทึกบัญชี-->
<tr id="loadrevtranstatus_tr">
	<td></td><td><div id="loadrevtranstatus"></div></td>
</tr>

<!--แสดงข้อมูลให้กรอก กรณีเลือกเป็น  "คืนเงินให้บุคคลภายนอก " เรื่องช่องทางการการจ่ายคืน-->
<tr id="loadrefund">
	<td colspan="2">
		<div style="padding:20px 0px 10px"><b>วันที่รายการออกมีผล  (วันที่ทำเรื่องคืนเงิน): </b><input type="text" name="dcNoteDate" id="dcNoteDate" onChange="checkDateSelect()" size="15"><span style="color:red;font-weight:bold;">*</span></div>
		<div><b>ช่องทางการจ่าย : </b>
		<select name="byChannel" id="byChannel" onchange="javascript:checkTranPay()">
			<option value="">--เลือก--</option>
			<?php
			$qry=pg_query("select \"BID\",\"BAccount\"||'-'||\"BName\" from \"BankInt\" where \"isReturnChannel\"='1' order by \"BID\"");
			while($res=pg_fetch_array($qry)){
				list($bid,$bankname)=$res;
				echo "<option value=$bid>$bankname</option>";
			}
			?>
		</select><span style="color:red;"><b>*</b></span>
		<input type="hidden" name="tranpay" id="tranpay">
		</div>
		<div id="show11" style="background-color:#FFCCCC;padding:5px;width:450px;border:1px dashed #FF6A6A">
			<div><b>ระบุข้อมูลเพิ่มเติม</b></div>
			<div><input type="radio" name="proviso_return" id="proviso1" value="1" checked>คืนโดยโอนธนาคาร</div>
			<div id="show31" style="background-color:#FFFFE0;padding:5px;width:430px;font-weight:bold;border:1px dashed #FF6A6A">
			<div>
				เจ้าของบัญชี : <span style="color:red;">*</span>
				<input type="radio" name="someOne1" id="someOne11" value="have" onChange="chkCus1()"> มีข้อมูลในระบบ <input type="radio" name="someOne1" id="someOne12" value="no" onChange="chkCus1()"> ไม่มีข้อมูลในระบบ 
				<div id="divTranToCus_have"><input type="text" name="returnTranToCus_have" id="returnTranToCus_have" size="55" onfocus="check_customer();" onblur="check_customer();" onkeypress="check_customer();"><span id="chkTranHave" style="color:red;">*</span></div>
				<div id="divTranToCus_no"><input type="text" name="returnTranToCus_no" id="returnTranToCus_no" size="55"><span id="chkTranNo" style="color:red;">*</span></div>
			</div>
			<div id="chkcustomer"></div>
			<div>
				รหัสธนาคาร : 
				<!--input type="text" name="returnTranToBank" id="returnTranToBank" size="50" onfocus="check_bank();" onblur="check_bank();" onkeypress="check_bank();"><span style="color:red;">*</span-->
				<select name="returnTranToBank" id="returnTranToBank" onChange="check_bank();">
					<option value="">--เลือกรหัสธนาคาร--</option>
					<?php
					$qry_BankProfile = pg_query("select * from \"BankProfile\" order by \"sort\",\"bankName\" ");
					while($res_BankProfile = pg_fetch_array($qry_BankProfile))
					{
						$bankID = $res_BankProfile["bankID"]; // รหัสธนาคาร
						$bankName = trim($res_BankProfile["bankName"]); // ชื่อธนาคาร
						
						echo "<option value=\"$bankID#$bankName\">$bankID#$bankName</option>";
					}
					?>
				</select><span style="color:red;">*</span>
			</div>
			<div id="chkbank"></div>
			<div>เลขที่บัญชีปลายทาง : <input type="text" name="returnTranToAccNo" id="returnTranToAccNo"><span style="color:red;">*</span></div>
			</div>
			<div><input type="radio" name="proviso_return" id="proviso2" value="2">คืนโดยเช็ค</div>
			<div id="show21" style="background-color:#FFFFE0;padding:5px;width:430px;font-weight:bold;border:1px dashed #FF6A6A">
			<div>
				ออกเช็คให้ : <span style="color:red;">*</span>
				<input type="radio" name="someOne2" id="someOne21" value="have" onChange="chkCus2()"> มีข้อมูลในระบบ <input type="radio" name="someOne2" id="someOne22" value="no" onChange="chkCus2()"> ไม่มีข้อมูลในระบบ 
				<div id="divChqCus_have"><input type="text" name="returnChqCus_have" id="returnChqCus_have" size="55" onfocus="check_customer();" onblur="check_customer();" onkeypress="check_customer();"><span style="color:red;">*</span></div>
				<div id="divChqCus_no"><input type="text" name="returnChqCus_no" id="returnChqCus_no" size="55"><span style="color:red;">*</span></div>
			</div>
			<div id="chkcustomer_ChqCus"></div>
			<div>เลขที่เช็ค : <input type="text" name="returnChqNo" id="returnChqNo"> วันที่บนเช็ค : <input type="text" name="returnChqDate" id="returnChqDate" size="10"><font color="red"><b>*</b></font></div>
			</div>
		</div>
	</td>
</tr>

<?php
}
?>
<tr height="130" id="showremark">
	<td align="right" valign="top"><b>Remark :</b></td>
	<td valign="top"><textarea name="remark" id="remark" cols="50" rows="5"></textarea><span id="show"><font color="red"><b>*</b></font></span></td>
</tr>
<tr>
	<td colspan="2" align="center" bgcolor="#FFFFFF" height="50">
		<font color="red" size="3px;"><b>* กรุณาตรวจสอบรายการทั้งหมดอีกครั้ง ก่อนยืนยันการทำรายการ<br>หากยังมีรายการค้างอยู่หรือไม่ครบถ้วน กรุณาเพิ่มให้เรียบร้อยก่อนกดยืนยัน *</b></font>
	</td>
</tr>
<tr>
	<td colspan="2" align="center" bgcolor="#FFFFFF">
		<?php 
			if($revTranID=='bid_1'){ 
				echo 	"วันที่รับชำระเงินสด : <input type=\"text\" name=\"pcashdate\" id=\"pcashdate\" value=\"\" size=\"45\"><span style=\"color:red;\">*</span>
						<br>
						จำนวนเงินที่จะชำระด้วยเงินสด : <input type=\"text\" name=\"pcashamt\" id=\"pcashamt\" value=\"\" size=\"45\"><span style=\"color:red;\">*</span> บาท
						<br>
				";
			}
		?>
		<input type="hidden" value="<?php echo $p; ?>" id="chkchoise">
		<input type="hidden" name="revTranID" value="<?php echo $revTranID;?>">
		<input type="hidden" name="tranActionID" value="<?php echo $tranActionID;?>">
		<input type="hidden" name="BID" value="<?php echo $BID;?>">
		<input type="hidden" name="dateRevStamp" value="<?php echo $dateRevStamp;?>">
		<input type="hidden" name="app" value="<?php echo $app;?>">
		<input type="hidden" name="bankRevAmt" value="<?php echo $sumbankRevAmt;?>">
		<input type="hidden" id="checkContract" value="<?php echo $checkContract; ?>" />
		<input type="hidden" id="isAdminConfirm" name="isAdminConfirm" value="no" />
		<input type="button" hidden id="checkAdminConfirm" name="checkAdminConfirm" onClick="checkAdminConfirm();" />
		<input type="button" hidden id="showTextAdminConfirm" name="showTextAdminConfirm" onClick="$('#adminConfirmText').text('ยืนยันการทำรายการแล้ว');" />
		
		<input type="submit" id="btn_appv" name="btn_appv" value="บันทึก" style="cursor:pointer;" onclick="return checkremark();" <?php echo $canAppv; ?> />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="ปิด" style="cursor:pointer;" onclick="window.close();">
		
		<?php
		if($checkContract == "noContract" && $isadmin == "1") // ถ้ามีเลขที่สัญญาที่ไม่มีอยู่จริงในระบบ และผู้ที่ใช้เมนูเป็นผู้บริหาร
		{
		?>
			<br/><br/>
			<input type="button" id="adminConfirm" name="adminConfirm" value="ยืนยันการทำรายการ" style="cursor:pointer;" onClick="javascript:popU('popup_admin_confirm.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" />
			<font color="#green"><span id="adminConfirmText" name="adminConfirmText"></span></font>
		<?php
		}
		?>
	</td>
</tr>
</table>
</form>
</body>

<script>
$(document).ready(function(){
	$("#show11").hide(); //ซ่อนส่วนที่ต้องเลือกให้แสดงหลังจากเลือก ช่องทางการจ่าย  แล้ว isTranPay=1
	$("#show21").hide(); //ซ่อนส่วนที่ต้องกรอกเพิ่มให้แสดงหลังจากเลือก ช่องทางการจ่าย  แล้ว isTranPay=1 และเลือกคืนเช็ค
	$("#divTranToCus_have").hide();
	$("#divTranToCus_no").hide();
	$("#divChqCus_have").hide();
	$("#divChqCus_no").hide();
	
	$("#dcNoteDate").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});

	$("#returnChqDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#proviso1").click(function(){
		$("#show21").hide(); 
		$("#show31").show();
		$('#returnTranToCus').focus();
	});
	
	$("#proviso2").click(function(){
		$("#show21").show(); 
		$("#show31").hide(); 
		$('#returnChqNo').focus();
	});
	
	//ค้นหารหัสและชื่อลูกค้า สำหรับ คืนเงินบุคคลภายนอก คืนโดยโอนธนาคาร
	$("#returnTranToCus_have").autocomplete({
		source: "../thcap_dncn/s_customer.php",
        minLength:1
    });
	
	//ค้นหารหัสและชื่อลูกค้า สำหรับ คืนเงินบุคคลภายนอก คืนโดยเช็ค
	$("#returnChqCus_have").autocomplete({
		source: "../thcap_dncn/s_customer.php",
        minLength:1
    });
});

var num=document.getElementById("num").value;
var currentDate = '<?php echo $currentDate; ?>';

function processclick(a){
	for(i=1;i<=num;i++){
		if(document.getElementById("typecn"+i).checked){	
			document.getElementById("amt"+i).disabled=false;
			document.getElementById("amt"+i).focus();
		}else{
			document.getElementById("amt"+i).disabled=true;	
			document.getElementById("amt"+i).value='';			
		}
	}
}

function check_customer(){
	var arr =$('#returnTranToCus').val();
    var id=arr.split("#"); 

	$('#chkcustomer').load('check.php?chk=customer&id='+id[0]);
}
function check_customer_ChqCus(){
	var arr =$('#returnChqCus').val();
    var id=arr.split("#"); 

	$('#chkcustomer_ChqCus').load('check.php?chk=customer&id='+id[0]);
}	
function check_bank(){
	var arr =$('#returnTranToBank').val();
    var id=arr.split("#"); 

	$('#chkbank').load('check.php?chk=bank&id='+id[0]);
}

function checkDateSelect()
{
	if(document.getElementById('dcNoteDate').value > currentDate)
	{
		alert('ห้ามเลือก วันที่รายการออกมีผล มากกว่า วันที่ปัจจุบัน');
		document.getElementById('dcNoteDate').value = '';
	}
}

function checkDataSubmit()
{
	if(document.getElementById('dcNoteDate').value > currentDate)
	{
		alert('ห้ามเลือก วันที่รายการออกมีผล มากกว่า วันที่ปัจจุบัน');
		return false;
	}
}

function chkCus1()
{
	if(document.getElementById('someOne11').checked == true)
	{
		$("#divTranToCus_have").show();
		$("#divTranToCus_no").hide();
	}
	else if(document.getElementById('someOne12').checked == true)
	{
		$("#divTranToCus_have").hide();
		$("#divTranToCus_no").show();
	}
	else
	{
		$("#divTranToCus_have").hide();
		$("#divTranToCus_no").hide();
	}
}

function chkCus2()
{
	if(document.getElementById('someOne21').checked == true)
	{
		$("#divChqCus_have").show();
		$("#divChqCus_no").hide();
	}
	else if(document.getElementById('someOne22').checked == true)
	{
		$("#divChqCus_have").hide();
		$("#divChqCus_no").show();
	}
	else
	{
		$("#divChqCus_have").hide();
		$("#divChqCus_no").hide();
	}
}

//function สำหรับตรวจสอบว่า "public"."BankInt"."isTranPay" = 1 หรือไม่
function checkTranPay(){
	//กรณีมีการเลือกช่องทางการจ่าย
	if($("#byChannel").val() != "" ){
		//ส่งช่องทางที่เลือกไปตรวจสอบ
		$.get('../thcap_dncn/process_chktranpay.php?BID='+ $("#byChannel").val(), function(data){
			if(data==1){
				$("#show11").show(); 
				$("#show31").show();
				$("#show21").hide(); //ยังไม่ให้โชว์ส่วนระบุเลขที่เช็คต้องรอให้เลือกก่อน
				$('#proviso1').attr('checked', 'checked'); //defult ให้เลือก "คืนโดยโอนธนาคาร"
				$('#returnTranToCus').focus();
				
				//เคลียร์ค่าที่กรอกค้างไว้ (เลขที่เช็คและวันที่บนเช็ค)
				$('#returnChqNo').val(''); //เลขที่เช็ค
				$('#returnChqDate').val(''); //วันที่บนเช็ค
				$('#returnTranToCus').val(''); //เจ้าของบัญชี
				$('#returnTranToBank').val(''); //รหัสธนาคาร
				$('#returnTranToBank').val(''); //เลขที่บัญชีปลายทาง
			}else{
				$("#show11").hide();
				$("#show21").hide(); //ยังไม่ให้โชว์ส่วนระบุเลขที่เช็คต้องรอให้เลือกก่อน
				
				//เคลียร์ค่าที่กรอกค้างไว้ (เลขที่เช็คและวันที่บนเช็ค)
				$('#returnChqNo').val('');
				$('#returnChqDate').val('');
			}
			$('#tranpay').val(data);
		});
	}else{
		$("#show11").hide();
		$("#show21").hide();
		$('#tranpay').val('');
	}
}
</script>

</html>
<?php }?>