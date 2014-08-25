<?php
include("../../config/config.php");
$voucherPurposetype="PV";
$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>(THCAP) ทำรายการใบสำคัญจ่าย</title>
  
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	

<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
/*function chk_conif(){
	$.post('chk_contractid.php',{					
			contractid:$('#contractid').val()
	},function(data){
		if(data == 0){
			document.getElementById("chk_chkcontractid").value= 0;
			}
			else{
				document.getElementById("chk_chkcontractid").value= 1;
			}
	});
}*/
function  chk_find(){
	var find;
	if(document.getElementById("to2").checked){
		find = "emp";	
	} else if (document.getElementById("to3").checked){
		find = "cus";
	} else if (document.getElementById("to4").checked){
		find = "cus_corp";
	} 
	return find; 
}
function KeyData(){
	var  cf = chk_find();	
	
	$("#topayfullin").autocomplete({
			source: "s_officer_v2.php?find="+cf,
			minLength:1
		});
	
}
function topayfull(no){
	
	document.getElementById("topayfullin").value= '';
	document.getElementById("topayfullout").value= '';	
	if(no=='0'){	
		
		$("#topayfullout").show();
		$("#topayfullin").hide();
		
		}
	else{
		$("#topayfullin").show();
		$("#topayfullout").hide();
	}

}
$(document).ready(function(){
	$("#topayfullout").show();
	$("#topayfullin").hide(); 
	$("#formula").autocomplete({
			source: "s_bookall_v2.php",
			minLength:1
	});
	$("#formula").change(function(){
        $('#myDiv').empty();
    });	
	
	$("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#datevat").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });

    //************************************************กด บันทึก
    $("#btn_submit").click(function(){
		var theMessage = "Please complete the following: \n-----------------------------------\n";
		var chk=0;
		var error="";
        if($("#text_add").val() == "" ){
            theMessage = theMessage + "\n -->  ไม่พบคำอธิบายรายการ";
            $("#text_add").focus();
			chk++;
        }
		
		if(($("#voucherPurpose").val() == "")){
			theMessage = theMessage + "\n -->  กรุณาเลือก จุดประสงค์ :";
            chk++;
		}
		
		/*ข้อมูลที่ จ่ายให้ :*/
		//บุคคลภายนอก
		if($("#to1:checked").val() == "0"){	
			if(($("#topayfullout").val() == "")){
				theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  บุคคลภายนอก" ;
				chk++;
			}
		}
		//พนักงานบริษัท//ลูกค้าบุคคล//บลูกค้านิติบุคคล
		else if(($("#to2:checked").val() == "3") ||($("#to3:checked").val() == "1") ||($("#to4:checked").val() == "2")){	
			if(($("#topayfullin").val() == "")){
				theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  ข้อมูล ให้จ่าย:" ;
				chk++;
			}
			else{
				var arr_idname = $("#topayfullin").val().split('#');			
				$("#topayfullin").val(arr_idname[0]);
			}
		}
		/*จบข้อมูลที่ จ่ายให้ :*/
		/*if($('#chk_chkcontractid').val()=='1')
		{
			theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  รายการนี้สำหรับตั้งลูกหนี้ตามสัญญาเลขที่  ให้ถูกต้อง" ;
			chk++;
		}*/
		
		//ส่วนบัญชี
		error=chk_inputdata_accdetail();
		if(error!=''){theMessage+=error;chk++;}
		//จบส่วนบัญชี
		
		//ส่วนหนี้ที่ต้องการจะตั้ง
		error=chk_inputdata_adddebt();
		if(error!=''){theMessage+=error;chk++;}
		
		//หาค่า1=รวมจำนวนเงินค่าสินค้าหรือบริการ+รวมยอดภาษีมูลค่าเพิ่ม-รวมจำนวนภาษีหัก ณ ที่จ่าย		
		var sum1 = (parseFloat($('#txt_amount').val()) + parseFloat($('#txt_sumvat').val())) - parseFloat($('#txt_amountwithhol').val());
		if($('#note').val()=='')
		{
			theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  หมายเหตุ" ;
			chk++;
		}
		
		//จบส่วนหนี้ที่ต้องการจะตั้ง		
		//************//
		error=chk_inputdata_save_channel_v2();
		if(error!=''){theMessage+=error;chk++;}		
		//***********//	
		//ตรวจสอบเงื่อนไข เรื่องจำนวนเงิน
		if(parseFloat($('#txt_amountaddchan').val()).toFixed(2) != parseFloat(sum1).toFixed(2)){
			theMessage = theMessage + "\n ->  ผลรวมของ ช่องทางการจ่าย มีค่าไม่เท่ากับ หนี้ที่ต้องการจะตั้ง" + " :: ช่องทางการจ่าย = " + parseFloat($('#txt_amountaddchan').val()).toFixed(2) +  " หนี้ที่ต้องการจะตั้ง = " + parseFloat(sum1).toFixed(2);
			chk++;
		}
		if(chk > 0){
			alert(theMessage);		
		}
		else{
			$("#add_acc").submit();
		}
    });
    //************************************************กด บันทึก

});
</script>

<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>	

</head>
<body>
<div class="header"><h1>(THCAP) ทำรายการใบสำคัญจ่าย</h1></div>
	<div align="center">
		<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
		<div style="clear:both;"></div>
		<form method="post" name="add_acc" id="add_acc" action="add_acc_manual_send_v2.php">
		<fieldset style="width:70%"><legend><B>ทำรายการ- Payment Voucher</B></legend>
			<table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td> 			
				<input type="hidden" id="chk_drcr" name="chk_drcr">
			
				<input type="hidden" name="noaddFile"  id="noaddFile" size="54">
				<?php 	include('frm_show_detail_v2.php');?>
				<!--บัญชี-->		
				<div>			
					<?php include('../thcap_ap/frm_accdetail.php');	?>			
				</div>	
				<!--จบบัญชี-->
				<br>
				</td>
			</tr>
		</table>
		<div align="center" name="showdebt" id="showdebt">
			<center>
			<?php include($rootpath.'nw/thcap_ap/frm_adddebt.php');?>	</center>	
			<input type="hidden" id="chk_chkcontractid" name="chk_chkcontractid" >			
		</div>	
		
		<div id="purpost">
		<?php 	include('frm_save_channel_v2.php');?>	
		</div>
		<div id="ch">	
		</div>
</fieldset>
</form>
</div>
<center>
	<input type="button" value="บันทึก" class="ui-button" id="btn_submit" name="btn_submit">
</center>

<?php 
	//รายการรออนุมัติทั้งหมด
	$sendfrom='1';
	include($rootpath.'/nw/thcap_appv_pv/pv_wait.php');
	?>
</body>
</html>