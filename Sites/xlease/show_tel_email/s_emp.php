<?php
include("../config/config.php");
$term = pg_escape_string($_GET['term']);
$qry_name=pg_query("select
						\"id_user\",
						\"fullname\",
						\"nickname\"
					from
						\"Vfuser_active\"
					where
						(\"fullname\" like '%$term%' or \"nickname\" like '%$term%') and
						\"isadmin\" <> '1' and
						\"user_dep\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)"); //ยกเว้น คนที่เป็นระดับ admin

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$id_user=trim($res_name["id_user"]);
	$fullname=trim($res_name["fullname"]);
	$nickname=trim($res_name["nickname"]);
    
    $dt['value'] = "$id_user".'#'."$fullname".'#'."$nickname";
    $dt['label'] = "{$id_user} # {$fullname} # {$nickname}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>