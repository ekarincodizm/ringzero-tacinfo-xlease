<?php include('../../config/config.php');?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$dcNoteID=pg_escape_string($_GET["dcNoteID"]);
$query_Reason = pg_query("select a.\"contractID\",a.\"dcNoteDescription\",b.\"doerID\",b.\"doerStamp\" from  \"account\".\"thcap_dncn\" a
				left join \"account\".\"thcap_dncn_details\" b on a.\"dcNoteID\"=b.\"dcNoteID\"
				where a.\"dcNoteID\" ='$dcNoteID'");
$result = pg_fetch_array($query_Reason);
$numrows = pg_num_rows($query_Reason);
if($numrows >0){
	$reason= $result["dcNoteDescription"]; //เหตุผล
	$contractID= $result["contractID"]; //เลขที่สัญญา
	$doerid= $result["doerID"];
	$doerStamp= $result["doerStamp"];
}
$qry_appv_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$doerid'");
$rs_appv_name = pg_fetch_array($qry_appv_name);
$numrows = pg_num_rows($qry_appv_name);
if($numrows >0){
	$doername = $rs_appv_name["fullname"]; //ผู้ทำรายการขอส่วนลด
}
?>
<div style="text-align:center"><h2>เหตุผล</h2></div>
<div><b>เลขที่สัญญา :</b> <?php echo $contractID;?></div>
<div><b>ผู้ทำรายการขอส่วนลด :</b> <?php echo $doername;?></div>
<div><b>วันเวลาที่ทำการขอส่วนลด :</b> <?php echo $doerStamp;?></div>
<fieldset><legend><b>เหตุผล</b></legend>
<textarea cols="60" rows="4" readonly><?php echo $reason;?></textarea>
</fieldset>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>