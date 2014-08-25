<?php
include("../../config/config.php");
$term = $_GET['term'];

$qry_name = pg_query("select a.\"fullname\",a.\"id_user\" as iduser,a.\"nickname\" as nick,b.\"u_idnum\" from \"Vfuser\" a left join \"fuser_detail\" b on a.\"id_user\" = b.\"id_user\"

WHERE a.\"fullname\" like '%$term%' or a.\"id_user\" like '%$term%' or b.\"nickname\" like '%$term%' or b.\"u_idnum\" like '%$term%' ");						 
$numrows = pg_num_rows($qry_name);

while($res_name = pg_fetch_array($qry_name)){ 
		$id_user=$res_name["iduser"];
		$fullname=$res_name["fullname"];
		$nickname=$res_name["nick"];
		if($nickname == ""){ $nickname="-";}
		$u_idnum=$res_name["u_idnum"];
		if($u_idnum == ""){ $u_idnum="-";}
		$dt['value'] = $id_user."-$fullname, $nickname, $u_idnum";
		$dt['label'] = "{$id_user}, {$fullname}, {$nickname}, {$u_idnum}";
		$matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>

