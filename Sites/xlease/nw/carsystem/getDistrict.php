<option value="0" selected>ไม่ระบุ</option>
<?php
include("../../config/config.php");
$province_id=$_GET['province_id'];
$sql="select * from \"amphur\" where \"PROVINCE_ID\"='$province_id' order by \"AMPHUR_NAME\" asc";
$dbquery=pg_query($sql);
while($rs=pg_fetch_assoc($dbquery))
{
	$amphur_id=$rs['AMPHUR_ID'];
	$amphur_name=$rs['AMPHUR_NAME']; ?>
	<option value="<?php echo $amphur_id; ?>"><?php echo $amphur_name; ?></option>
<?php
}
?>