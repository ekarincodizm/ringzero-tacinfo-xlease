<?php
include("../config/config.php");

$cid = $_POST['cid'];

$sql_select=pg_query("select A.*,B.* from  \"Fa1\" A 
LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\" 
where (A.\"CusID\" = '$cid') ");
if($res_cn=pg_fetch_array($sql_select)){
    $data['firstname'] = $res_cn["A_FIRNAME"];
    $data['name'] = $res_cn["A_NAME"];
    $data['surname'] = $res_cn["A_SIRNAME"];
    $data['reg'] = $res_cn["N_SAN"];
    $data['birthdate'] = $res_cn["N_AGE"];
    $data['pair'] = $res_cn["A_PAIR"];
    $data['card'] = $res_cn["N_CARD"];
    $data['address'] = $res_cn["A_NO"];
    $data['idcard'] = $res_cn["N_IDCARD"];
    $data['moo'] = $res_cn["A_SUBNO"];
    $data['dateidcard'] = $res_cn["N_OT_DATE"];
    $data['soi'] = $res_cn["A_SOI"];
    $data['bycard'] = $res_cn["N_BY"];
    $data['road'] = $res_cn["A_RD"];
    $data['contactadd'] = $res_cn["N_ContactAdd"];
    $data['tambon'] = $res_cn["A_TUM"];
    $data['ampur'] = $res_cn["A_AUM"];
    $data['province'] = $res_cn["A_PRO"];
    $data['success'] = true;
}else{
    $data['success'] = false;
}

echo json_encode($data);
?>