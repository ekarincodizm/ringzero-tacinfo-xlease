<?php
session_start();
include("../../config/config.php");
$auto_id=trim($_GET["auto_id"]);

//ค้นหาข้อมูลที่แก้ไข
$qrydata=pg_query("select a.*,b.\"fullname\" from \"Carnum_Temp\" a
			left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
			where auto_id='$auto_id' and \"appStatus\"='2'");
$numidno=pg_num_rows($qrydata);

if($numidno==0){ //แสดงว่ารายการนี้อาจได้รับการอนุมัติไปก่อนหน้านี้แล้ว
	echo "<center><h2>ไม่พบเลขที่สัญญานี้รออนุมัติ อาจได้รับการอนุมัติก่อนหน้านี้ กรุณาตรวจสอบ<h2></center>";
	exit();
}

$res_app=pg_fetch_array($qrydata);
$auto_id=$res_app["auto_id"];
$idno=$res_app["IDNO"];
$CarID=$res_app["CarID"]; //รหัสรถยนต์
$CARNUM_OLD=$res_app["CARNUM_OLD"]; //เลขตัวถังเก่า
$CARNUM_NEW=$res_app["CARNUM_NEW"]; //เลขตัวถังที่แก้ไข
$addUser=$res_app["fullname"]; //ชื่อผู้ทำการแก้ไข
$addStamp=$res_app["addStamp"]; //วันเวลาที่แก้ไข

//หาเลขที่สัญญาอื่นที่มีรหัสรถยนต์เดียวกัน
$nub=1;
$sumIDNO="";
$qryfp=pg_query("select \"IDNO\" from \"Fp\" where asset_id='$CarID' and \"IDNO\"<>'$idno'");
$numfp=pg_num_rows($qryfp);
while($resfp=pg_fetch_array($qryfp)){
	$idnofp=$resfp["IDNO"];
	
	if($nub == $numfp){
		$txtidno = "<a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$idnofp','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=blue><U><span title=\"ดูตารางผ่อนชำระ\">$idnofp</span></U></font></a>";
		$sumIDNO= $sumIDNO.$txtidno;
	}else{
		$txtidno = "<a href=\"#\" onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$idnofp','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=blue><U><span title=\"ดูตารางผ่อนชำระ\">$idnofp</span></U></font></a>";
		if($nub%7 == 0){
			$addbr = "<br>";
		}else{
			$addbr = "";
		}
		$sumIDNO= $sumIDNO.$txtidno.",$addbr";
	}
}
if($sumIDNO==""){
	$sumIDNO="-ไม่มีเลขที่สัญญาที่มีรหัสรถยนต์เหมือนกัน-";
}
//ค้นหารายละเอียดรถ
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติแก้ไขตัวถังรถยนต์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

</head>
<body>
<form name="my" method="post" action="process_carnum.php">
	<div style="text-align:center;"><h2>อนุมัติแก้ไขตัวถังรถยนต์</h2></div>
	<fieldset><legend><B>รายละเอียด</B></legend>
	<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
	<tr align="left">
		<td width="25%"><b>เลขที่สัญญา</b></td>
		<td width="75%" class="text_gray"><a href="#" onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $idno;?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ"><font color="red"><u><?php echo $idno; ?></font></u></a></td>
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
		<td><b>เลขตัวถังเก่า</b></td>
		<td class="text_gray"><?php echo $CARNUM_OLD;?></td>
	</tr>
	<tr align="left">
		<td><b>เลขตัวถังที่แก้ไข</b></td>
		<td bgcolor="#FFCCCC"><b><?php echo $CARNUM_NEW;?></b></td>
	</tr>
	<tr align="left">
		<td><b>เลขที่สัญญาที่มีรหัสรถเหมือนกัน</b></td>
		<td bgcolor="#FFFFCC"><b><?php echo $sumIDNO;?></b></td>
	</tr>
	<tr align="left">
	<td valign="top"><b>หมายเหตุ</b></td>
	<td><textarea name="result" id="result" cols="40" rows="4"></textarea></td>
	</tr>
	<tr align="center">
		<td colspan=2><br>
		<!--input name="btn1" id="btn1" type="button" value="อนุมัติ"/>
		<input name="btn2" id="btn2" type="button" value="ไม่อนุมัติ"/-->
		<input name="btn1" id="btn1" type="submit" value="อนุมัติ"/>
		<input name="btn2" id="btn2" type="submit" value="ไม่อนุมัติ"/>
		<input type="hidden" name="auto_id" id="auto_id" value="<?php echo $auto_id;?>">
		</td>
	</tr>
	</table>
	</fieldset> 
</form>
