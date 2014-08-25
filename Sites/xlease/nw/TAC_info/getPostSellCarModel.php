<option>ทุกรุ่น</option>
<?php
include("config.php");
$brandID=$_GET['brandID'];
$sql="select * from \"TrCarModel\" where \"brandName\"='$brandID' order by \"modelID\" asc";
$dbquery=pg_query($sql);
while($rs=pg_fetch_assoc($dbquery))
{
	$modelID=$rs['modelID'];
	$modelName=$rs['modelName'];
	if($modelName!="")
	{
		echo "<option value=\"$modelID\">$modelName</option>";
	}
	else
	{
		echo "<option value=\"\">ไม่พบข้อมูล</option>";
	}
}
?>