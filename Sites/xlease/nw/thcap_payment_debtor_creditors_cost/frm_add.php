<?php include('../../config/config.php');
$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root
$con_id=pg_escape_string($_GET["contractID"]);
$sendfrom_noconid=pg_escape_string($_GET["sendfrom_noconid"]);//ถ้า เป็นการที่ไม่มีเลขที่สัญญา ในระบบ เป็น 1

$query_detail = pg_query("select \"contractID\",\"CusID\",\"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$con_id'");
$result = pg_fetch_array($query_detail);
$numrows = pg_num_rows($query_detail);
if($numrows >0){
	$contractID= $result["contractID"];
	$CusID = $result["CusID"]; 
	$thcap_fullname= $result["thcap_fullname"];	
}
$qry_conStartDate_list = pg_query("SELECT \"conStartDate\"  FROM \"thcap_contract\" WHERE \"contractID\" ='$contractID' ");
list($conStartDate) = pg_fetch_array($qry_conStartDate_list);

// ตรวจสอบประเภทสัญญา ของสัญญานั้นๆ
$vcontype=pg_query("SELECT \"thcap_get_contractType\"('$contractID')");
$vcontype=pg_fetch_array($vcontype);
list($vcontype)=$vcontype;

?>
<html>
<head>
<title>(THCAP) ชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link> 
    <link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){

	$("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });	
});
$(function() {
$( document ).tooltip();
});
function chk_conif(){	
	$.post('../thcap_add_paymentpvoucher/chk_contractid.php',{					
			contractid:$('#contractid').val()
	},function(data){
		if(data == 0){
			document.getElementById("chk_chkcontractid").value= 0;
			document.getElementById("contractid").style.backgroundColor="#98FB98";
		}
		else{
			document.getElementById("chk_chkcontractid").value= 1;
			document.getElementById("contractid").style.backgroundColor="#FF6A6A";
		}
	});
}
function chk_con_in_sys(){	
	$("#u_key").html('');
	$.post('chk_contractid_insys.php',{					
			contractid:$('#contractid').val()
	},function(data){
		if(data == 0){
			document.getElementById("chk_chkcontractid_sys").value= 0;
			$("#u_key").html('');
		}
		else{
			document.getElementById("chk_chkcontractid_sys").value= 1;
			$("#u_key").css('color','#ff0000');
			$("#u_key").html('(เลขที่สัญญานี้มีอยู่ในระบบแล้ว)');	
		}
	});
}

function chkerror1(){	
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var chk=0;
	
	if($('#text_add').val()=="")
	{
		theMessage = theMessage + "\n -->  กรุณากรอก คำอธิบายรายการ";
        chk++;
	}
	if($('#datepicker').val()=="")
	{
		theMessage = theMessage + "\n -->  กรุณากรอก วันที่ทำรายการ";
        chk++;
	}
	if($('#contractid').val()=="")
	{
		theMessage = theMessage + "\n -->  กรุณากรอก รายการนี้สำหรับตั้งลูกหนี้ตามสัญญาเลขที่";
        chk++;
	}	
	else{
		if('<?php echo $sendfrom_noconid;?>'=='1'){
			if($('#chk_chkcontractid').val()=='1')
			{
				theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  รายการนี้สำหรับตั้งลูกหนี้ตามสัญญาเลขที่  ให้ถูกต้อง" ;
				chk++;
			}
			if($('#chk_chkcontractid_sys').val()=='1')
			{
				theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  ในรายการนี้สำหรับตั้งลูกหนี้ตามสัญญาเลขที่  ให้ถูกต้องเนื่องจากเลขที่สัญญานี้อยู่ในระบบแล้ว" ;
				chk++;
			}
		}
	}
	if(($('#noaddFile').val()==0)){
		theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูล  ข้อมูลช่องทางการจ่าย" ;
		chk++;
	}
	if($('#voucherPurpose').val()=="")
	{
		theMessage = theMessage + "\n -->  กรุณาเลือก จุดประสงค์";
        chk++;
	}	
	if(chk==0){ return true;}
	else{ alert(theMessage);return false;}
}
</script>
<body>
<form method="post" name="add" id="add" action="process_add.php">
<div style="text-align:center"><h2>(THCAP) ชำระเงินให้ลูกหนี้-เจ้าหนี้ต้นทุนสินค้า</h2></div>
<input  id="chk_chkcontractid" name="chk_chkcontractid" hidden>	
<input  id="chk_chkcontractid_sys" name="chk_chkcontractid_sys" hidden>
<input  id="sendfrom_noconid" name="sendfrom_noconid" value='<?php echo $sendfrom_noconid;?>' hidden>
<input type="hidden" name="noaddFile"  id="noaddFile" size="54">
<table width="90%" border="0" cellSpacing="1" cellPadding="2" align="center">
    <tr>
        <td align="right" width="25%">วันที่ทำรายการ :</td>
		<?php if($sendfrom_noconid !='1'){ ?>
			<td width="75%"><input type="text" id="datepicker" name="datepicker"  value="<?php echo $conStartDate; ?>" size="15">
		<?php }else {?>
			<td width="75%"><input type="text" id="datepicker" name="datepicker"  value="<?php echo nowDate(); ?>" size="15">
		<?php } ?>
		<font color="red">*<b></td>
    </tr>	
    <tr>
        <td align="right" width="25%" valign="top">คำอธิบายรายการ :</td>
        <td><textarea id="text_add" name="text_add" rows="4" cols="60"></textarea><font color="red">*<b></td>
    </tr>
	<tr>
		<td align="right" width="25%">จ่ายให้ :</td>
		<input  name="cusid_main"  id="cusid_main" size="54" readonly value='<?php if($sendfrom_noconid=='1' || ($vcontype == 'FL' || $vcontype == 'HP' || $vcontype == 'BH')){ echo '';} else{echo $CusID;}?>' hidden>
		<?php if($sendfrom_noconid=='1' || ($vcontype == 'FL' || $vcontype == 'HP' || $vcontype == 'BH')){?>
			<td><span id="payfull"><input  name="fullname_main"  id="fullname_main" size="54" title="กรอกผู้กู้/ผู้ซื้อหลัก/ตัวแทนจำหน่ายที่ซื้อสินค้า"></span></td>
		<?php }else { ?>
			<td><span id="payfull"><input  name="fullname_main"  id="fullname_main" size="54"  value='<?php echo $thcap_fullname;?>' readonly></span></td>
		<?php } ?>
	</tr>	
	<tr>
        <td align="right" width="25%" valign="15%">รายการนี้สำหรับตั้งลูกหนี้ตามสัญญาเลขที่ :</td>
        <?php if($sendfrom_noconid=='1'){?>
			<td><input type="text" id="contractid" name="contractid"  size="54" onblur="chk_conif();chk_con_in_sys()" onChange="chk_conif();chk_con_in_sys()" onKeyUp="chk_conif();chk_con_in_sys()" title="กรอกเลขที่สัญญา">
			<span id="u_key" name="u_key"></span><font color="red">*<b></td>
		<?php }else { ?>
			<td><input type="text" id="contractid" name="contractid"  size="54"  value='<?php echo $contractID;?>' readonly><font color="red">*<b></td>
		<?php } ?>		
	</tr>
	<tr>
	<td align="right" >จุดประสงค์:</td>
	<td>
	<select name="voucherPurpose" id="voucherPurpose">	
		<?php
			$qry_GenType = pg_query("select * from account.\"thcap_purpose\" where \"thcap_purpose_vouchertype\"='1' order by \"thcap_purpose_id\" ");
			echo "<option value=\"\">-- กรุณาเลือกจุดประสงค์ --</option>";			
			while($res_gentype=pg_fetch_array($qry_GenType)){
				$GenType = $res_gentype["thcap_purpose_id"];
				$GenName = $res_gentype["thcap_purpose_name"];				
				echo "<option value=\"$GenType\">$GenType : $GenName</option>";				
			}
			?>
	</select><font color="red">*<b>
	</td>
	</tr>
	<tr>
	<td colspan="2">	
		<div id="purpost">
			<?php $frm_send='payment_debtor_creditors_cost';	
			include('../thcap_add_paymentpvoucher/frm_save_channel_v2.php');?>	
		</div>
		</td>
	</tr>
	<tr><td colspan="2">
		<div id="ch">	
		</div>
		</td>
	</tr>	
	</table>
<div style="text-align:center;padding:20px">
<input type="submit" align="center" value="บันทึก" onclick="return chkerror1()">
<input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</form>
<?php 
$contractID=$con_id;
include("../thcap_app_payment_debtor_creditors/frm_list_approved.php");?>
</body>
</html>