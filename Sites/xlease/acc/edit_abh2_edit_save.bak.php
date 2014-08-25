<?php
include("../config/config.php");

$type = pg_escape_string($_POST['type']);

pg_query("BEGIN WORK");
$status = 0;

if($type == 1){

$perfs = explode("&", pg_escape_string($_POST['dt']));
foreach($perfs as $perf){
    $perf_key_values = explode("=", $perf);
    $key = urldecode($perf_key_values[0]);
    $values = urldecode($perf_key_values[1]);
    ${$key} = $values;
}

for($i = 1; $i<=$ct; $i++){
    if(${"dr".$i} == 0){
        $sum_cr+=round(${"cr".$i},2);
    }else{
        $sum_dr+=round(${"dr".$i},2);
    }
}

if(round($sum_cr,2) != round($sum_dr,2)){
    $status++;
    $err = "ยอดเงิน Dr Cr ไม่เท่ากัน $sum_cr / $sum_dr";
}else{
    
    $sql_update="UPDATE account.\"AccountBookHead\" SET \"acb_detail\"='$detail' WHERE \"auto_id\"='$hid'";
    $res_update=@pg_query($sql_update);
    if(!$res_update){
        $status++;
        $err = "ไม่สามารถ Update AccountBookHead !";
    }
    
    for($i = 1; $i<=$ct; $i++){
        $aid = ${"aid".$i};
        $acid = ${"acid".$i};
        $dr = ${"dr".$i};
        $cr = ${"cr".$i};
        $sql_update="UPDATE account.\"AccountBookDetail\" SET \"AcID\"='$acid',\"AmtDr\"='$dr',\"AmtCr\"='$cr' WHERE \"auto_id\"='$aid'";
        $res_update=@pg_query($sql_update);
        if(!$res_update){
            $status++;
            $err = "ไม่สามารถ Update AccountBookDetail !";
        }
    }
}

}elseif($type == 2){
    $hid = pg_escape_string($_POST['id']);
    $sql_update="UPDATE account.\"AccountBookHead\" SET \"cancel\"='TRUE' WHERE \"auto_id\"='$hid'";
    $res_update=@pg_query($sql_update);
    if(!$res_update){
        $status++;
        $err = "ไม่สามารถ Update AccountBookHead !";
    }
}

if($status == 0){
    pg_query("COMMIT");
    //pg_query("ROLLBACK");
    $data['success'] = true;
    $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกข้อมูลได้\n$err";
}
    
echo json_encode($data);
?>