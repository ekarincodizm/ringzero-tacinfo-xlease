<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);


$qry_name=pg_query("select a.\"IDNO\",b.\"full_name\",c.\"C_REGIS\" from \"Fp\" a  
	left join \"VSearchCus\" b on a.\"CusID\" = b.\"CusID\"
	left join \"VContact\" c on a.\"IDNO\"=c.\"IDNO\"
	WHERE a.\"IDNO\" LIKE '%$term%' OR b.\"full_name\" LIKE '%$term%' OR c.\"C_REGIS\" LIKE '$term' ORDER BY a.\"IDNO\" ASC");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=$res_name["IDNO"];
    $name=$res_name["full_name"];

    $dt['value'] = $IDNO;
    $dt['label'] = "{$IDNO}, {$name}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
