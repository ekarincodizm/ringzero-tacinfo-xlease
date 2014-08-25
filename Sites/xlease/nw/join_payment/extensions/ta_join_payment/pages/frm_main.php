<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$action = pg_escape_string($_REQUEST[action]);
$car_id_r = pg_escape_string($_REQUEST[car_id_r]);
$idno =pg_escape_string($_REQUEST[idno]);
$id =pg_escape_string($_REQUEST[id]);
$app = pg_escape_string($_REQUEST[app]);
$new_sp = pg_escape_string($_REQUEST[new_sp]);
$sp = pg_escape_string($_REQUEST[sp]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php if($action=='edit')echo "แก้ไข"; else if($app=='1')echo "อนุมัติ";else if($action=='view')echo "ดู";else if($action=='add')echo "เพิ่ม";  ?>ข้อมูลเข้าร่วม</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    

 <link type="text/css" href="../../../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>

	
<script type="text/javascript">

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
<?php if($new_sp=='1') { ?>
$("#panel").load("ta_join_main.php?car_no=<?php echo $car_id_r ?>&action=add&new_sp=1&id=<?php echo $id; ?>&idno=<?php echo $idno; ?>");
$("#s1").hide();
<?php } ?>
<?php if($new_sp=='2') { ?>



$("#panel").load("ta_join_main.php?car_no=<?php echo $car_id_r ?>&action=add&idno=<?php echo $idno; ?>");
$("#s1").hide();
<?php } ?>

<?php if($sp=='1') { ?>

$("#panel").load("ta_join_main.php?car_no=<?php echo $car_id_r ?>&action=edit&sp=1&id=<?php echo $id; ?>&idno=<?php echo $idno; ?>");
$("#s1").hide();
<?php } ?>
    $("#car_no").autocomplete({
		<?php if($action=='add') { ?>source: "car_data.php",<?php } else if($action=='edit' || $action=='view') { ?>source: "car_data_edit.php",<?php } ?>
        
        minLength:1,
		close: function(event, ui) { var x = $("#car_no").val().split('#');

        $("#panel").load("ta_join_main.php?car_no="+x[1]+"&idno="+x[2]+"&action=<?php if($action=='add') { ?>add&c="+x[3]+"<?php } else if($action=='edit') { ?>edit&id="+x[3]+"&c="+x[4]+"<?php } else if($action=='view') { ?>view&id="+x[3]+"&c="+x[4]+"<?php } ?>");
		}
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
		

			<fieldset><legend><B><?php if($action=='add') { ?>เพิ่ม<?php } else if($action=='edit') { ?>แก้ไข<?php  } else if($app=='1')echo "อนุมัติ";else if($action=='view') { ?>ดู<?php } ?>ข้อมูลเข้าร่วม</B></legend>

			<div class="ui-widget" align="center">

			<div style="margin:0" id="s1">
			<b>เลขที่สัญญา,ทะเบียนรถ, ชื่อ-นามสกุลลูกค้า</b>&nbsp;
			<input id="car_no" name="car_no" size="60" />&nbsp;
			
			</div>

			<div id="panel" style="padding-top: 20px;"></div>

			</div>

			 </fieldset>

        </td>
    </tr>
</table>


</body>
</html>