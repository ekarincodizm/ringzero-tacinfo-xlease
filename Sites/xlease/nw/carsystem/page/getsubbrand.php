<option value="">ทุกยี่ห้อรอง</option>
<?php
include("../../../config/config.php");
$brandID=$_GET['brandID'];
$sql="select * from carsystem.\"productSubBrand\" where \"productBrandID\"='$brandID' order by \"productSubBrandName\" asc";
$dbquery=pg_query($sql);
while($rs=pg_fetch_assoc($dbquery))
{
	$subbrandID=$rs['productSubBrandID'];
	$subbrandName=$rs['productSubBrandName'];
	if($subbrandName!="")
	{
		echo "<option value=\"$subbrandID\">$subbrandName</option>";
	}
}
?>