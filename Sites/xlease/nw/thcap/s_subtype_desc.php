<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("SELECT \"revtranstatussubtype_id\", \"revtranstatussubtype_desc\" FROM finance.\"thcap_receive_transfer_status_subtype\" 
where  \"revtranstatussubtype_desc\" like '%$term%' and \"revtranstatus_id\" = '2' order by \"revtranstatussubtype_desc\" ");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name))
{
    $revtranstatussubtype_id = $res_name["revtranstatussubtype_id"];
	$revtranstatussubtype_desc = trim($res_name["revtranstatussubtype_desc"]);
	
	$dt['value'] = "$revtranstatussubtype_id#$revtranstatussubtype_desc";
    $dt['label'] = "$revtranstatussubtype_desc";
    $matches[] = $dt;
}
	
if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>