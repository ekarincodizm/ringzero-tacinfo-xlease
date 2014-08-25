<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php"); 
$selectStatus = $_GET["selectStatus"];
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<option value="">-เลือกสถานะสินค้า-</option>
<?php
$qry_productStatus = pg_query("select * from public.\"ProductStatus\" where \"UseStatus\" = '1' order by \"ProductStatusName\" ");
while($res_productStatus = pg_fetch_array($qry_productStatus))
{
	$ProductStatusID = trim($res_productStatus["ProductStatusID"]);
	$ProductStatusName = trim($res_productStatus["ProductStatusName"]);
?>
	<option value="<?php echo $ProductStatusID; ?>" <?php if($selectStatus == $ProductStatusID){echo "selected";} ?>><?php echo $ProductStatusName; ?></option>
<?php
}
?>