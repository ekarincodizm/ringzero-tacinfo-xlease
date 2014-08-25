<?php
include("../config/config.php");
$idno=pg_escape_string($_POST['idno']);
$arr_idno = explode("#",$idno);

if(!empty($arr_idno[0])){

$qry_cc=pg_query("select \"P_MONTH\",\"P_VAT\" from \"Fp\" WHERE \"IDNO\"='$arr_idno[0]' ");
if($res_cc=pg_fetch_array($qry_cc)){
    $pm=$res_cc["P_MONTH"]+$res_cc["P_VAT"];
    $data['success'] = true;
    $data['message'] = "$pm";
}else{
    $data['success'] = false;
    $data['message'] = "ผิดผลาด ไม่สามารถหาจำนวนเงินค่างวดได้";
}
    
echo json_encode($data);

}
?>