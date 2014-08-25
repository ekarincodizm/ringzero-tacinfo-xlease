<?php include('../../config/config.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	</head>
<?php 
$autoIDCheck=$_GET["autoIDCheck"];
//ดึง ข้อมูล ต่าง ๆ
$query_note = pg_query("select * from \"thcap_contract_check_temp\"  where \"autoID\" ='$autoIDCheck'");
$result = pg_fetch_array($query_note);
$numrows = pg_num_rows($query_note);

$iduser = $_SESSION["av_iduser"];
	
	//ตรวจสอบ level ของ ผู้ใช้งาน
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];
		
if($numrows >0){
	$note= $result["note"]; //หมายเหตุ
	$ID= $result["contractID"]; //เลขที่สัญญา
	$appvID= $result["appvID"]; //ผู้อนุมัติการตรวจสอบ
	$appvStamp= $result["appvStamp" ]; 
	
	if($emplevel<=1 or $appvID==$iduser){
	$qry_appv_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$appvID'");
	$rs_appv_name = pg_fetch_array($qry_appv_name);
	$numrows = pg_num_rows($qry_appv_name);

	$appvname = $rs_appv_name["fullname"]; //ชื่อู้อนุมัติการตรวจสอบ
	}else{
		$appvname = "T".($appvID+2556);
	}
}
?>
<body >
	<div style="text-align:center"><h2>หมายเหตุ</h2></div>
	<div><b>เลขที่สัญญา :</b> <?php echo $ID;?></div>
	<div><b>ผู้ทำรายการตรวจสอบ :</b> <?php echo $appvname;?></div>
	<div><b>วันเวลาที่ตรวจสอบ :</b> <?php echo $appvStamp;?></div>
	<fieldset><legend><b>หมายเหตุ </b></legend>
		<textarea cols="60" rows="4" readonly><?php echo $note;?></textarea>
	</fieldset>
	<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้">
	</div>
</body >

