<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name=pg_query("select id_user,fullname,nickname from \"Vfuser\" WHERE \"ac\" LIKE '%$term%' ORDER BY \"fullname\" desc LIMIT(20) ");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $id_user=$res_name["id_user"];
    $fullname=$res_name["fullname"];

    $nickname=$res_name["nickname"];

    
    $dt['value'] = $id_user."#".$fullname."#".$nickname;
    $dt['label'] = "{$id_user}, {$fullname} {$nickname} ";
    $matches[] = $dt;

}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 20);
print json_encode($matches);
?>
