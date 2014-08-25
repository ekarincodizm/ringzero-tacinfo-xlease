<?php 
include("config/config.php"); 

pg_query("BEGIN WORK");

$status = 0;
$nub = 0;
$qry=pg_query("SELECT * FROM account.\"AccountBookHead\" WHERE \"acb_detail\" LIKE '%PO%' ORDER BY \"auto_id\" ASC ");
while($res=pg_fetch_array($qry)){
    $nub++;
    $auto_id = "";
    $acb_detail = "";
    $auto_id = $res['auto_id'];
    $acb_detail = $res['acb_detail'];
    $arr_acb_detail = explode("PO",$acb_detail);

    $qry_update="UPDATE account.\"AccountBookHead\" SET \"ref_id\"='PO$arr_acb_detail[1]' WHERE \"auto_id\"='$auto_id';";
    if($result_update=pg_query($qry_update)){
        
    }else{
        $status+=1;
    }
    
}

if($status == 0){
    pg_query("COMMIT");
    echo "OK $nub rows";
}else{
    pg_query("ROLLBACK");
    echo "ERROR $nub rows";
}
?>