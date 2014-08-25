<?php
session_start();
include("../config/config.php");

$brand = $_GET['brand'];
$down = $_GET['down'];
$times = $_GET['time'];

if($brand==""){

echo "กรุณาเลือกรุ่นรถยนต์ และ เงินดาวน์ ก่อนครับ";

}else{
?>
<select name="time_list" id="time_list" onchange="caldown3()">
<option value="">---- ระยะเวลา ----</option>
<?php
	
	$objQuery = pg_query("select  distinct \"month_payment\" from \"Fp_package\" where \"numtest\" = '$brand' AND \"down_payment\" = '$down' order by \"month_payment\"");
	$row = pg_num_rows($objQuery);
	
	while($objResuut = pg_fetch_array($objQuery)){
	
	$month = $objResuut["month_payment"];
?>		
	<option value="<?php echo $month; ?>" <?php if($times == $month){ echo "selected=\"selected\"" ; } ?> ><?php echo $month; ?></option>
<?php		
	}	
		
?>		
</select>
<?php } ?>
	
		
	
