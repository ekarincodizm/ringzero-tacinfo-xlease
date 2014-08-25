<?php
include("../../config/config.php");
$car = $_GET['car'];

if(empty($car)){
   $car = $_POST['car'];
}

//ตรวจสอบว่ามีการยกเลิกแล้วหรือไม่
$qrycancel=pg_query("select * from \"Cancel_Radio\" where \"CusID\"='$car'");
$numcancel=pg_num_rows($qrycancel);

//ดึงข้อมูลขึ้นมาแสดง
if($numcancel==0){ //แสดงว่ายังไม่ยกเลิก
	$sql=mssql_query("select a.CusID,a.PreName,a.Name,a.SurName,b.RadioID,a.CarRegis from TacCusDtl a
		left join TacRadio b on a.CusID=b.CusID 
		where RadioONID <> '0' and a.CusID='$car' order by a.CusID"); 
	if($res = mssql_fetch_array($sql)){
		$PreName=trim(iconv('WINDOWS-874','UTF-8',$res["PreName"]));
		$Name=trim(iconv('WINDOWS-874','UTF-8',$res["Name"]));
		$cusLName=trim(iconv('WINDOWS-874','UTF-8',$res["SurName"]));
		$cusFName=$PreName.$Name;
		$carRadio=trim(iconv('WINDOWS-874','UTF-8',$res["RadioID"]));
		$carRegis=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));	
		$startDate=nowDate();
	}
	$readonly="";
}else{ //กรณียกเลิกแล้ว
	$readonly="readonly";
	if($res=pg_fetch_array($qrycancel)){
		$cusFName=$res["cusFName"];
		$cusLName=$res["cusLName"];
		$carRegis=$res["carRegis"];
		$carRadio=$res["carRadio"];
		$startDate=$res["startDate"];
		$note=$res["note"];
	}
}
?>
<form method="post" name="form1" action="process_cancel.php">
<?php
if($numcancel>0){
?>
<div align="center"><font size="3" color="red"><b>สัญญาวิทยุนี้ยกเลิกแล้ว</b></font></div><br>
<?php } ?>
<table width="60%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFF0F0">
<tr>
	<td colspan="3" bgcolor="#FFCCCC"><b>รายละเอียดยกเลิกสัญญา</b></td>
</tr>
<tr>
	<td width="100">เลขที่สัญญา</td><td>:</td><td><input type="text" name="CusID" value="<?php echo $car;?>" <?php echo $readonly; ?>></td>
</tr>
<tr>
	<td>ชื่อลูกค้า</td><td>:</td><td><input type="text" name="cusFName" value="<?php echo $cusFName;?>" size="40" <?php echo $readonly; ?>></td>
</tr>
<tr>
	<td>นามสกุลลูกค้า</td><td>:</td><td><input type="text" name="cusLName" value="<?php echo $cusLName;?>" size="40" <?php echo $readonly; ?>></td>
</tr>
<tr>
	<td>ทะเบียนรถ</td><td>:</td><td><input type="text" name="carRegis" value="<?php echo $carRegis;?>" <?php echo $readonly; ?>></td>
</tr>
<tr>
	<td>รหัสวิทยุ</td><td>:</td><td><input type="text" name="carRadio" value="<?php echo $carRadio;?>" <?php echo $readonly; ?>></td>
</tr>
<tr>
	<td>วันที่ทำเรื่อง</td><td>:</td><td><input type="text" name="startDate" id="datepicker" value="<?php echo $startDate;?>" size="15" maxlength="10" <?php echo $readonly; ?>></td>
</tr>
<tr valign="top">
	<td>หมายเหตุ</td><td>:</td><td><textarea cols="50" rows="5" name="note" <?php echo $readonly; ?>><?php echo $note; ?></textarea></td>
</tr>
<tr valign="top">
	<td colspan="3" height="25"></td>
</tr>
</table>
<?php
if($numcancel==0){
?>
<div align="center" style="padding:10px;"><input type="submit" value="บันทึก" onclick="return check();"><input type="reset" value="ล้างข้อมูล"></div>
<?php
}
?>

</form>
<script type="text/javascript">
$(document).ready(function(){
	<?php if($numcancel==0){?>
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	<?php
	}
	?>
});
function check(){
	if(document.form1.CusID.value==""){
		alert("กรุณาระบุสัญญาวิทยุ");
		document.form1.CusID.focus();
		return false;
	}else{
		return true;
	}
}
</script>
