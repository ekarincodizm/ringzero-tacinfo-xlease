<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_dcNoteID=pg_query("select \"dcNoteID\" from account.v_thcap_dncn_active 
where \"dcNoteID\" like '%$term%' and \"dcType\" = 1 LIMIT(20)");

$numrows = pg_num_rows($qry_dcNoteID);
if($numrows>0){
	while($res_name=pg_fetch_array($qry_dcNoteID)){
		$dcNoteID=trim($res_name["dcNoteID"]);
  
		$dt['value'] = $dcNoteID;
		$dt['label'] = "{$dcNoteID}";
		$matches[] = $dt;
	}
}else{
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
