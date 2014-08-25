<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$action = $_REQUEST[action];
$car_id_r = $_REQUEST[car_id_r];
$idno =$_REQUEST[idno];
$id =$_REQUEST[id];
$app = $_REQUEST[app];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ขอส่วนลดเข้าร่วม</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    

 <link type="text/css" href="../../../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

	
<script type="text/javascript">
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function checkdata(){
	if(document.getElementById('idtfpen').value == ""){
			alert("กรุณากรอกเลขที่ใบสั่ง");
			document.getElementById('idtfpen').focus();
			return false;
	}else{
		return true;
	}
}

function check_number(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		alert("กรุณากรอกเป็นตัวเลขเท่าันั้น!!");
		return false;
	}
	return true;
}

$(document).ready(function(){
	<?php if($app=='1') { ?>
	$("#panel").load("ta_join_main.php?car_no=<?php echo $car_id_r ?>&action=view&app=1&id=<?php echo $id; ?>&idno=<?php echo $idno; ?>");
	$("#s1").hide();
	<?php } ?>
	
    $("#car_no").autocomplete({
		source: "car_data_edit.php",
        
        minLength:1,
		close: function(event, ui) { var x = $("#car_no").val().split('#');
		$("#panel").show();
        $("#panel").load("join_discount_payment.php?car_no="+x[1]+"&idno="+x[2]+"&id="+x[3]+"&change_pay_type="+$("#type1").val());
		}
    });
   
    $("#type1").change(function() {
		var x = $("#car_no").val().split('#');
        $("#panel").show();
        $("#panel").load("join_discount_payment.php?car_no="+x[1]+"&idno="+x[2]+"&id="+x[3]+"&change_pay_type="+$("#type1").val());
	});
		
});


</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="99%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<fieldset><legend><B>ขอส่วนลดเข้าร่วม</B></legend>

			<div class="ui-widget" align="center">

			<div style="margin:0" id="s1">
			<b>เลขที่สัญญา,ทะเบียนรถ, ชื่อ-นามสกุลลูกค้า</b>&nbsp;
			<input id="car_no" name="car_no" size="60" />&nbsp;
			
            
            <select name="type1" id="type1">
                <option value="1">รวมค่าแรกเข้า</option>
                <option value="0">เฉพาะค่าเข้าร่วม</option>
			</select> 
			</div>
			
			<div id="panel" style="padding-top: 20px;"></div>

			</div>
			</fieldset>
            <div id="history"></div>
        </td>
    </tr>
</table>
</body>
</html>