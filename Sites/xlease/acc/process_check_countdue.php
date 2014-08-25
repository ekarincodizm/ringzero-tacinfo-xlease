<?php
include("../config/config.php");
$idno=pg_escape_string($_POST['idno']);
$arr_idno = explode("#",$idno);

if(!empty($arr_idno[0])){

$qry_fr=pg_query("select COUNT(\"IDNO\") AS \"count_idno\" from \"VCusPayment\" WHERE  (\"IDNO\"='$arr_idno[0]') AND (\"R_Receipt\" IS NULL) ");
if($res_fr=pg_fetch_array($qry_fr)){
    $count_idno = $res_fr["count_idno"];
    
    $adata = "<select name=\"countpay\" id=\"countpay\" onchange=\"JavaScript:ChangeCount();\">";
    for($i=0; $i<=$count_idno; $i++){
        $adata .= "<option value=$i>$i</option>";
    }
    $adata .= "</select>";
    
    $dt['success'] = true;
    $dt['message'] = "$adata";
}else{
    $dt['success'] = false;
    $dt['message'] = "ผิดผลาด ไม่สามารถหาจำนวนงวดที่เหลือได้";
}

echo json_encode($dt);

}
?>