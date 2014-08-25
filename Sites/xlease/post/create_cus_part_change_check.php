<?php
include("../config/config.php");

$regis = $_POST['regis'];
$idno = $_POST['idno'];
$idno = substr($idno,0,strlen($idno)-1);

$sum_outstanding1 = 0;
$sum_outstanding2 = 0;
$status = 0;
$msg_error = "ไม่สามารถทำรายการได้ พบยอดค้างดังนี้:\n";

//check ประกัน ภาคบังคับ
$qry_inf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsForceDetail\" WHERE \"outstanding\" >= '0.01' AND \"C_REGIS\"='$regis' ");
if($res_inf=pg_fetch_array($qry_inf)){
    $sum_outstanding1 = $res_inf["sum_outstanding"];
}
if($sum_outstanding1 > 0){
    $status++;
    $msg_error .= "ประกันภัยภาคบังคับ (พรบ.) : ".number_format($sum_outstanding1,2)."\n";
}

//check ประกัน ภาคสมัครใจ
$qry_inuf=pg_query("select SUM(outstanding) AS sum_outstanding from insure.\"VInsUnforceDetail\" WHERE \"outstanding\" >= '0.01' AND \"C_REGIS\"='$regis' ");
if($res_inuf=pg_fetch_array($qry_inuf)){
    $sum_outstanding2 = $res_inuf["sum_outstanding"];
}
if($sum_outstanding2 > 0){
    $status++;
    $msg_error .= "ประกันภัยภาคสมัครใจ : ".number_format($sum_outstanding2,2)."\n";
}

//check ภาษี
$arr_idno = explode(",",$idno);
foreach($arr_idno as $v_idno){
    $qry_amt=pg_query("select \"CusAmt\",\"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"cuspaid\" = 'false' AND \"IDNO\"='$v_idno' ");
    $nub_amt = pg_num_rows($qry_amt);
    if($nub_amt > 0){
        while($res_amt=pg_fetch_array($qry_amt)){
            $CusAmt = $res_amt["CusAmt"];
            $TypeDep = $res_amt["TypeDep"];
            
            if($CusAmt > 0){
                $qry_nn=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
                if($res_nn=pg_fetch_array($qry_nn)){
                    $TName = $res_nn["TName"];
                }
                $status++;
                $msg_error .= "$TName : ".number_format($CusAmt,2)."\n";
            }
        }
    }
}

if($status == 0){
    $data['success'] = true;
}else{
    //$data['success'] = true;
    $data['success'] = false;
    $data['message'] = "$msg_error";    
}

echo json_encode($data);
?>