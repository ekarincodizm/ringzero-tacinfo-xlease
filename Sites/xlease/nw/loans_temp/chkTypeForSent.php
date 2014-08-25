<?php
include('../../config/config.php');
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$main = pg_escape_string($_POST["main"]); // ผู้กู้หลัก
if($main == "")
{
	$main = pg_escape_string($_GET["main"]);
	//$main = str_replace("ThaiaceReplaceSharp","#",$main);
}

$contype = pg_escape_string($_GET["contype"]); // ประเภทสินเชื่อ
$conCompany = pg_escape_string($_GET["conCompany"]); // บริษัท
$selectSubtype = pg_escape_string($_GET["selectSubtype"]); // ประเภทสัญญาย่อย

$custype = pg_escape_string($_GET['custype']);

// ชื่อประเภทสินเชื่อแบบเต็ม
$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contype') ");
$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

// กำหนดไฟล์ที่จะส่งค่าไป
if($chk_con_type == "GUARANTEED_INVESTMENT")
{
	$sentURL = "guaranteed_investment_index.php";
}
elseif($chk_con_type == "JOINT_VENTURE")
{
	$sentURL = "joint_venture_index.php";
}
elseif($chk_con_type == "SALE_ON_CONSIGNMENT")
{
	$sentURL = "sale_on_consignment_index.php";
}
else
{
	$sentURL = "home_index.php";
}
?>

<form method="get" action="<?php echo $sentURL; ?>">
	<input type="hidden" name="main" value="<?php echo $main; ?>" />
	<input type="hidden" name="contype" value="<?php echo $contype; ?>" />
	<input type="hidden" name="conCompany" value="<?php echo $conCompany; ?>" />
	<input type="hidden" name="selectSubtype" value="<?php echo $selectSubtype; ?>" />
	<input type="hidden" name="custype" value="<?php echo $custype; ?>" />
	<input type="submit" name="btnSent" id="btnSent" hidden />
</form>

<script>
	document.getElementById("btnSent").click();
</script>