<?php
session_start();
include("../config/config.php");

$brand = $_GET['brand'];
$downs = $_GET['paks'];
if($brand == ""){
	echo "กรุณาเลือกรุ่นรถยนต์ด้วยครับ";
}else{
?>
	<select name="down_list1" id="down_list1" onchange="caldown2(),calbegin()">
	<option value=""> ------- เงินดาวน์ ------- </option>
<?php		
														
													
	$objQuery = pg_query("select distinct \"down_payment\" from \"Fp_package\" where \"numtest\" = '$brand' order by  \"down_payment\" DESC");
	while($objResuut = pg_fetch_array($objQuery))
	{ 

		$down=$objResuut["down_payment"];?>
		<option value="<?php echo $down ?>" <?php if($downs == $down){ echo "selected=\"selected\" "; } ?> ><?php echo $down ?></option>
<?php	}		


?>	</select>

<?php } ?>