<?php
include("../../config/config.php");
$q = $_GET["q"];

$sql = "select * from \"Vfuser\" WHERE \"fullname\" like '%$q%' or \"id_user\" like '%$q%'";
$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

if($nrows==0){
	  $name="ไม่พบข้อมูล";
	  $display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
	  echo "<li onselect=\"this.setText('$name'); \">$display_name</li>";
	 
}else{
	while($row = pg_fetch_array( $results )){ 
		$id_user=$row["id_user"];
		$fullname=$row["fullname"];
		
		// ป้องกันเครื่องหมาย '
		$name = $id_user.", ".$fullname;
		// กำหนดตัวหนาให้กับคำที่มีการพิมพ์
		$display_name = preg_replace("/(" . $q . ")/i", "<b>$1</b>", $name);
		echo "<li onselect=\"this.setText('$name').setValue('$id'); \">$display_name</li>";
	}
}
?>

