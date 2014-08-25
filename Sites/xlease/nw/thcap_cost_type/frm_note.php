<?php include('../../config/config.php');
$autoID=pg_escape_string($_GET["autoid"]);
$show=pg_escape_string($_GET["show"]);
if($show=='1'){
	/*$query_note = pg_query("select * from \"thcap_cost_type\"  where \"costtype\" ='$autoID'");*/
	$query_note = pg_query("select a.\"costname\" as \"costname\",a.\"note\" as \"note\",b.\"doerid\" as \"doerid\",b.\"doerstamp\" as \"doerstamp\" from \"thcap_cost_type\" a 
	left join \"thcap_cost_type_temp\" b on a.\"costtype\"=b.\"costtype\" where b.\"autoid\" in(select max(\"autoid\") from \"thcap_cost_type_temp\"  where \"costtype\" ='$autoID' and \"approved\"='1')");
}
else{
	$query_note = pg_query("select * from \"thcap_cost_type_temp\"  where \"autoid\" ='$autoID'");
}

$result = pg_fetch_array($query_note);
$numrows = pg_num_rows($query_note);
if($numrows >0){
	$note= $result["note"]; //หมายเหตุ
	$costname= $result["costname"]; //เลขที่สัญญา
	$doerid= $result["doerid"]; //ผู้อนุมัติการตรวจสอบ
	$doerstamp= $result["doerstamp" ]; 
}
$qry_appv_name = pg_query("select \"fullname\" from public.\"Vfuser\" where \"id_user\" = '$doerid'");
$rs_appv_name = pg_fetch_array($qry_appv_name);
$numrows = pg_num_rows($qry_appv_name);
if($numrows >0){
	$doername = $rs_appv_name["fullname"]; //ชื่อู้อนุมัติการตรวจสอบ
}
?>
<div style="text-align:center"><h2>หมายเหตุ</h2></div>
<div><b>ชื่อประเภทต้นทุนสัญญา :</b> <?php echo $costname;?></div>
<div><b>ผู้ทำรายการ :</b> <?php echo $doername;?></div>
<div><b>วันเวลาที่ทำรายการ :</b> <?php echo $doerstamp;?></div>
<fieldset><legend><b>หมายเหตุ </b></legend>
<textarea cols="60" rows="4" readonly><?php echo $note;?></textarea>
</fieldset>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>