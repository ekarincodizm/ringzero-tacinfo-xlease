<?php
session_start();
if($idno2!=""){
	$idno=$idno2;
}else{
	include("../../config/config.php");
	$idno=trim($_GET["idno"]);
}
	
$qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$idno'");
$numidno=pg_num_rows($qry_fp);
if($numidno==0){
	echo "<center><h2>ไม่พบเลขที่สัญญา<h2></center>";
	exit();
}

$res_fp=pg_fetch_array($qry_fp);
$fp_cusid=trim($res_fp["CusID"]);
$fp_carid=trim($res_fp["asset_id"]);
$fp_stdate=$res_fp["P_STDATE"];
$asset_type=$res_fp["asset_type"];
$asset_id=$res_fp["asset_id"];

//ค้นหา้ข้อมูลแก๊ส
$qry_gas=pg_query("select * from \"FGas\" where \"GasID\" ='$fp_carid' ");
$num_gas=pg_num_rows($qry_gas);
if($res_fc=pg_fetch_array($qry_gas)){
	$fc_carid=trim($res_fc["GasID"]);
	$fc_regis=trim($res_fc["car_regis"]);
	$fc_regis_by=trim($res_fc["car_regis_by"]);
	$fc_year=trim($res_fc["car_year"]);
	$fc_mar=trim($res_fc["marnum"]);
	$fc_num=trim($res_fc["carnum"]);
}

//กรณีไม่ใช่สัญญาแก็ส
if($num_gas==0){
	$qry_car=pg_query("select \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
		\"C_CARNUM\", \"C_MARNUM\",\"CarID\" from \"Carregis_temp\" where \"IDNO\" ='$idno' order by auto_id DESC limit 1");
	$res_fc=pg_fetch_array($qry_car);
	list($fc_regis,$fc_name,$fc_year,$fc_regis_by,$fc_num,$fc_mar,$fc_carid)=$res_fc;
}

//ตรวจสอบว่ารายการนี้รออนุมัติอยู่หรือไม่
$qrychk=pg_query("select * from \"Carnum_Temp\" where \"IDNO\"='$idno' and \"CarID\"='$fc_carid' and \"appStatus\"='2'");
if(pg_num_rows($qrychk)>0){
	echo "<center><h2>รายการนี้กำลังรออนุมัติ กรุณาทำรายการหลังจากได้รับการอนุมัติแล้ว<h2></center>";
	exit();
}

?>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language="javascript">
$(document).ready(function(){	
	$('#btn2').click(function(){
		if($('#fc_num').val()==""){
			alert("กรุณากรอกเลขตัวถังที่ต้องการ");
			$('#fc_num').val('<?php echo $fc_num;?>');
			$('#fc_num').select();
			return false;
		}else{
			$.post('process_carnum.php',{
				car_num: $('#fc_num').val(),
				car_num_old: '<?php echo $fc_num;?>',
				CarID: '<?php echo $fc_carid;?>',
				idno: '<?php echo $idno;?>'
			},
			function(data){
				if(data==1){
					alert("บันทึกข้อมูลเรียบร้อยแล้ว");
					window.location='frm_Index.php';
				}else if(data==2){
					alert("รายการนี้กำลังรออนุมัติ กรุณาทำรายการหลังจากได้รับการอนุมัติแล้ว");
				}else if(data==3){
					alert("ไม่มีการแก้ไขข้อมูล");
				}else{
					alert("ผิดพลาดไม่สามารถแก้ไขได้ "+data);
				}
			});
		}
	});
});

</script>
<fieldset><legend><B>แก้ไขตัวถังรถยนต์</B></legend>
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
<tr align="left">
	<td width="25%"><b>เลขที่สัญญา</b></td>
	<td width="75%" class="text_gray"><?php echo $idno; ?></td>
</tr>
<?php
if($asset_type == 1){
?>
<tr align="left">
	<td><b>ยี่ห้อรถ</b></td>
	<td class="text_gray"><?php echo $fc_name; ?></td>
</tr>
<?php
}
?>
<tr align="left">
	<td><b>รุ่นปี</b></td>
	<td class="text_gray"><?php echo $fc_year; ?></td>
</tr>
<tr align="left">
	<td><b>ทะเบียน</b></td>
	<td class="text_gray"><?php echo "$fc_regis"; ?></td>
</tr>
<tr align="left">
	<td><b>จังหวัดที่จดทะเบียน</b></td>
	<td class="text_gray"><?php echo $fc_regis_by; ?></td>
</tr>
<tr align="left">
	<td><b>เลขเครื่องยนต์</b></td>
	<td class="text_gray"><?php echo $fc_mar; ?></td>
</tr>
<tr align="left">
	<td><b>เลขตัวถัง</b></td>
	<td class="text_gray">
		<input type="text" name="fc_num" id="fc_num" value="<?php echo $fc_num; ?>" size="40">
	</td>
</tr>
<tr align="center">
	<td colspan=2><br><input name="btn2" id="btn2" type="button" value="บันทึก"/></td>
</tr>
</table>
</fieldset> 
