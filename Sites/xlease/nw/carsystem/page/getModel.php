<option value="">ทุกรุ่น</option>
<?php
include("../../../config/config.php");
$subbrandID=$_GET['subbrandID'];
$sql="select * from carsystem.\"productModel\" where \"productSubBrandID\"='$subbrandID' order by \"productModelName\" asc";
$dbquery=pg_query($sql);
while($rs=pg_fetch_assoc($dbquery))
{
	$modelID=$rs['productModelID'];
	$modelName=$rs['productModelName']; ?>
	<option value="<?php echo $modelID; ?>"><?php echo $modelName; ?></option>
<?php
}
?>