<?php
include("../../config/config.php");
include("../function/thaitxtdate.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

$fmenuid = $_GET['fmenuid'];
$state = $_GET['state'];


$sqlchk = pg_query("SELECT * FROM f_menu_warning where \"fmenuwarID\" = '$fmenuid' ");
$rowchk = pg_num_rows($sqlchk);
$rechk = pg_fetch_array($sqlchk);
$id_menu = $rechk['id_menu'];

$sql = pg_query("SELECT id_menu, name_menu, status_menu, path_menu FROM f_menu where id_menu = '$id_menu'");
$result = pg_fetch_array($sql);
$name_menu = $result['name_menu'];


$sstime = $rechk['s_time']; //วันที่เริ่มปิด
list($dates,$times) = explode(" ",$sstime);// แยกวันที่ และ เวลา
list($hours,$mins,$secs) = explode(":",$times);//แยก ชั่วโมง นาที วินาที

$eetime = $rechk['e_time']; //วันที่เปิดใช้อีกครั้ง
list($datee,$timee) = explode(" ",$eetime);// แยกวันที่ และ เวลา
list($houre,$mine,$sece) = explode(":",$timee);//แยก ชั่วโมง นาที วินาที
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>เลื่อนการปิดปรับปรุง</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script language=javascript>
$(document).ready(function(){	
<?php if($state != 'chgapp'){ ?>
	$("#datelimitstart").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
<?php } ?>	
	$("#datelimitend").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
});

function chklist(){

		var timestart = $("#datelimitstart").val()+" "+$("#hourstart").val()+":"+$("#minutsstart").val()+":00";
		var timeend = $("#datelimitend").val()+" "+$("#hourend").val()+":"+$("#minutsend").val()+":00";
		if(timestart >= timeend){
			alert("กรุณาเลือกวันที่เปิดใช้มากกว่าวันที่ปิดใช้");
			return false;		
		}else{
			if(confirm('ยืนยันการเปลี่ยนแปลง')==true){			
				return true;
			}else{ 
				return false;
			}
		}		
		
}
</script>
</head>
<form action="process_changetime.php" method="post">
<table width="700" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="#9AFF9A">
	<tr><td><br></td></tr>
	<?php if($state == 'chgapp'){
		$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
		$timecal = (strtotime($eetime) - strtotime($datenow));
		$timedetail = showUserSpend($timecal);
	?> <tr><td colspan="2" align="center"><font color="red" size="3px;"> เมนูนี้กำลังอยู่ในระหว่างการปิดปรับปรุง !<p>เหลือเวลาอีกประมาณ <?php echo $timedetail; ?></font></td></tr>
		<input type="hidden" id="datelimitstart" value="<?php echo $dates; ?>">
		<input type="hidden" id="hourstart" value="<?php echo $hours; ?>">
		<input type="hidden" id="minutsstart" value="<?php echo $mins; ?>">
		<tr><td><br></td></tr>	
		<?php } ?>
	<tr>
		<td align="right" width="40%"> เมนู : </td><td><b><?php echo $name_menu;?></b></td>
	</tr>
	<tr>
		<td align="right"> วันเวลาที่ปิดใช้งาน : </td><td><?php echo $rechk['s_time'];?></td>
	</tr>
	<tr>
		<td align="right"> วันเวลาที่เปิดใช้งาน : </td><td> <?php echo $rechk['e_time'];?></td>
	</tr>
	<tr><td><br></td></tr>
</table>
<table width="700" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="#7CCD7C">
	<tr>
		<td align="center" > ขอเลื่อนเป็น </td>
	</tr>
	</table>
	<table width="700" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="#9AFF9A">
	<tr><td><br></td></tr>
	<?php if($state != 'chgapp'){ ?>
	<tr>
	
		<td width="20%" align="right">จะปิดปรับปรุงวันที่ : </td>
		<td width="15%"><input type="text" name="datelimitstart" id="datelimitstart" size="10" value="<?php echo $dates; ?>"></td>
		<td width="60%">เวลา : <select name="hourstart" id="hourstart">		
	<?php		
			for($i=0;$i<=24;$i++){
				if($i < 10){
					$hh = "0".$i;
				}else{
					$hh = $i;
				}
	?>			
				<option value="<?php echo $hh ?>" <?php if($hours == $hh){ echo "selected"; } ?> ><?php echo $hh ?></option>
	<?php			
			}
	?>		
			</select> นาฬิกา 
		
			<select name="minutsstart" id="minutsstart">
	<?php		
			for($i=0;$i<60;$i++){
				if($i < 10){
					$mm = "0".$i;
				}else{
					$mm = $i;
				}
	?>
	<option value="<?php echo $mm ?>" <?php if($mins == $mm){ echo "selected"; } ?>><?php echo $mm ?></option>
	<?php
			}
	?>		
			</select> นาที
		</td>
	</tr>
	<?php } ?>
	<tr>	
		<td align="right">จะเปิดใช้วันที่ : </td>
		<td><input type="text" name="datelimitend" id="datelimitend" size="10" value="<?php echo $datee; ?>"></td>
		<td >เวลา : <select name="hourend" id="hourend">		
	<?php		
			for($i=0;$i<=24;$i++){
				if($i < 10){
					$hh = "0".$i;
				}else{
					$hh = $i;
				}
	?>
	<option value="<?php echo $hh ?>" <?php if($houre == $hh){ echo "selected"; } ?>><?php echo $hh ?></option>
	<?php
			}
	?>		
			</select> นาฬิกา 
		
			<select name="minutsend" id="minutsend">
	<?php		
			for($i=0;$i<60;$i++){
				if($i < 10){
					$mm = "0".$i;
				}else{
					$mm = $i;
				}
	?>
	<option value="<?php echo $mm ?>" <?php if($mine == $mm){ echo "selected"; } ?>><?php echo $mm ?></option>
	<?php
			}
	?>		
			</select> นาที
		</td>	
	</tr>
	<tr>
		<td colspan="2" align="right">คำเตือนที่จะแสดงให้ผู้ใช้เมนูเห็น :</td><td><input type="text" name="textdetail" size="65" value="<?php echo $rechk['detail_warning']; ?>"></td>
	</tr>
	<tr><td><br></td></tr>
</table>
<table width="700" frame="box" cellspacing="0" cellpadding="0"  align="center" bgcolor="#7CCD7C">
	<tr>
		<td align="center" ><input type="submit" value=" ดำเนินการ " onclick="return chklist();"></td>
	</tr>
</table>

<!-- Hidden field-->
<input type="hidden" name="hdmenuid" value="<?php echo $fmenuid; ?>">
<input type="hidden" name="state" value="<?php echo $state; ?>">
</form>	