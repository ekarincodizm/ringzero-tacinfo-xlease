<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("SELECT \"accBookserial\", \"accBookID\", \"accBookName\"
					from account.\"all_accBook\"
					where \"accBookID\" LIKE '%$term%' or \"accBookName\" LIKE '%$term%' order by \"accBookName\"");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name))
{
    $accBookserial = trim($res_name["accBookserial"]);
	$accBookID = trim($res_name["accBookID"]);
	$accBookName = trim($res_name["accBookName"]);
	
	$display_name = "$accBookID#$accBookName";
    
	$dt['value'] = "$accBookserial#$accBookID#$accBookName";
    $dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>