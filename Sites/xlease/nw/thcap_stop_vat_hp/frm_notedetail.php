<?php include('../../config/config.php');
$idno=$_GET["idno"];

$query_note = pg_query("select * from \"thcap_contract_vatcontrol\" where \"autoID\" = '$idno'");
$result = pg_fetch_array($query_note);
$numrows = pg_num_rows($query_note);
if($numrows >0){
	$contractID= $result["contractID"]; //หมายเหตุ
	$appvid = $result["appvid"]; 
	$appvstamp= $result["appvstamp"];	
	$note= $result["note"];
	//ชื่อผู้ที่ทำรายการ
	$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvid' ");
	$fullnameuser = pg_fetch_array($query_fullnameuser);
	$empfullname=$fullnameuser ["fullname"];
	
	if($note=="null"){$note="";}
	
}
?>
<div style="text-align:center"><h2>เหตุผลการอนุมัติ /ไม่อนุมัติ STOP VAT HP</h2></div>
<div><b>เลขที่สัญญา :</b> <?php echo $contractID;?></div>
<div><b>ผู้ที่ทำรายการ:</b> <?php echo $empfullname;?></div>
<div><b>วันเวลาที่ทำรายการ:</b> <?php echo $appvstamp;?></div>
<div style="padding-top:10px;width:400px;">
<fieldset><legend><b>เหตุผล</b></legend>
<textarea cols="60" rows="4" readonly><?php echo $note;?></textarea>
</fieldset>
</div>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>