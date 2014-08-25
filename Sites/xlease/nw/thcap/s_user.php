<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT a.\"id_user\",b.\"fullname\" FROM finance.thcap_receive_transfer_log a
left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\"
where a.\"id_user\" LIKE '%$term%' or b.\"fullname\" LIKE '%$term%' group by a.\"id_user\",b.\"fullname\" order by \"id_user\"");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $id_user = trim($res_name["id_user"]);
	$fullname = trim($res_name["fullname"]);
	
	$name = str_replace("'", "\'"," ".$id_user.""." - ".$fullname);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
    
	$dt['value'] = $id_user."-".$fullname;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
