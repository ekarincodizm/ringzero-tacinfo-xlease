<?php
include("../config/config.php");

$mm = $_POST['mm'];
$yy = $_POST['yy'];
$ty = $_POST['ty'];

pg_query("BEGIN WORK");
$status = 0;
$text_error = array();

$mlastdate = date("t",strtotime("$yy-$mm-01"));

for($i=1; $i<=$mlastdate; $i++){
    
    if(strlen($i) == 1){ $klmn = "0".$i; }else{ $klmn = $i; }
    $strdate = "$yy-$mm-$klmn";
    
    $cid = 0;
    $qry = pg_query("SELECT COUNT(\"auto_id\") as \"cid\" FROM account.\"AccountBookHead\" WHERE \"cancel\"='FALSE' AND \"type_acb\"='$ty' AND \"acb_date\"='$strdate'");
    if($res=pg_fetch_array($qry)){
        $cid = $res['cid'];
    }
    
    if($cid != 0){
        $up_sql="UPDATE account.\"RunningNo\" SET \"$ty\"='0' WHERE \"RunningDate\"='$strdate'";
        if(!$res_up_sql=@pg_query($up_sql)){
            $status++;
        }
        
        $qry3 = pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"cancel\"='FALSE' AND \"type_acb\"='$ty' AND \"acb_date\"='$strdate'");
        while($res3=pg_fetch_array($qry3)){
            $auto_id = $res3['auto_id'];
            
            $gen_no=@pg_query("select account.\"gen_no\"('$strdate','$ty')");
            $genid=@pg_fetch_result($gen_no,0);
            if(@empty($genid)){
                $status++;
            }
            
            $up_sql="UPDATE account.\"AccountBookHead\" SET \"acb_id\"='$genid' WHERE \"auto_id\"='$auto_id'";
            if(!$res_up_sql=@pg_query($up_sql)){
                $status++;
            }
        }
        
    }
    
}


if($status == 0){
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    $data['success'] = true;
    $data['message'] = "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้<br />$text_error[0]";
}

echo json_encode($data);
?>