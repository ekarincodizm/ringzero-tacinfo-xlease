<?php
include("../../config/config.php");

$term = $_POST['name'];

$sql = "SELECT \"nickname\"
		FROM \"Vfuser\"
		where  \"fullname\" like '%$term%'";
		
$results=pg_query($sql);						 
$row = pg_num_rows($results);
$re = pg_fetch_array($results);


if($row == 0 || empty($row)){

		echo "";

	}else{

	$nickname = $re['nickname'];
	echo  $nickname;

}
?>