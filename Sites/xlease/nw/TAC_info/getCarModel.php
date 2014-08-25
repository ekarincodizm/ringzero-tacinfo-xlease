<?php
include("config.php");
$brandID=$_GET['brandID'];
$sql="select * from \"TrCarModel\" where \"brandName\"='$brandID' order by \"modelID\" asc";
$dbquery=pg_query($sql);
while($rs=pg_fetch_assoc($dbquery))
{
	$modelID=$rs['modelID'];
	$modelName=$rs['modelName']; ?>
	<option value="<?php echo $modelID; ?>"><?php echo $modelName; ?></option>
<?php
}
?>