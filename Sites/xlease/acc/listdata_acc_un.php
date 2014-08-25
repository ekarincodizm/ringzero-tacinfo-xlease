<?php
include("../config/config.php");

$term = pg_escape_string($_GET['term']);

$qry_name=pg_query("select * from \"UNContact_directly\" WHERE \"IDNO\" LIKE '%$term%' OR \"C_REGIS\" LIKE '%$term%' OR \"C_CARNUM\" LIKE '%$term%' OR \"full_name\" LIKE '%$term%' ");
$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $IDNO=trim($res_name["IDNO"]);
    $full_name=trim($res_name["full_name"]);
    $C_REGIS=trim($res_name["C_REGIS"]);
    $asset_type=trim($res_name["asset_type"]);
    $C_CARNUM=trim($res_name["C_CARNUM"]);

    $qry_lock=pg_query("select \"LockContact\" from \"Fp\" WHERE \"IDNO\"='$IDNO' ");
    if($LockContact=='t'){
		if($res_lock=pg_fetch_array($qry_lock)){
			$slock=" x Locked x ";
			$LockContact = $res_lock['LockContact'];
		}else{
			if($LockContact=='t'){
				$slock="";
				$slock=" x Locked x ";
			}else{
				$slock="";
			}
		}
	}

    
    if($asset_type == 1){
        $type = "CAR";
    }else{
        $type = "GAS";
    }

	$name = str_replace("'", "\'"," "." [$type $IDNO $slock] ".$C_REGIS.""." / ".$full_name.""." / เลขตัวถัง ".$C_CARNUM);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
	
    $dt['value'] = "$IDNO#$C_REGIS#$full_name#$C_CARNUM";
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>