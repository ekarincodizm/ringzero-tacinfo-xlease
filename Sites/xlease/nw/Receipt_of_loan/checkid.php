<?php
include("../../config/config.php");

$term = $_POST['id'];

$sql = "SELECT \"threceiptID\" FROM \"temp_thcap_mg_3dreceipt\" where  \"threceiptID\" = '$term'";
		
$results=pg_query($sql);						 
$row = pg_num_rows($results);
$re = pg_fetch_array($results);


if($row == 0 || empty($row)){

echo "YES";

}else{
echo "No";
}
?>