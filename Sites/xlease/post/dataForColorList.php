<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../config/config.php");
$selectColor = $_GET["selectColor"];
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

	<?php
		$qry_carcolor = pg_query("select \"auto_id\",\"elementsName\" from \"tal_elements_car\" where \"elementsType\" = '3'");
		
	while($objResuut = pg_fetch_array($qry_carcolor)){
			$carcolorname = trim($objResuut["elementsName"]);
			if($carcolorname == $selectColor){ $selected = "selected"; }else{ $selected = ""; }
	echo "<option value=\"$carcolorname\" $selected>$carcolorname</option>";
	}?>