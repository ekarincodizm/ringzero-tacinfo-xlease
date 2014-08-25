<?php
include("../config/config.php");
$add_name=$_POST['addname'];
$add_surname=$_POST['addsurname'];

if(empty($add_name) || empty($add_surname)){
    $data['success'] = false;
    $data['message'] = "ไม่พบข้อมูล ชื่อสกุล";
}else{

$sql_select_check=pg_query("select COUNT(\"CusID\") as \"cusnub\" from \"Fa1\" where \"A_NAME\" = '$add_name' AND \"A_SIRNAME\" = '$add_surname' ");
if($res_cn_check=pg_fetch_array($sql_select_check)){
    $cusnub = $res_cn_check["cusnub"];
    if($cusnub > 0){
        $data['success'] = false;
        $data['message'] = "ข้อมูลผู้รับโอน มีอยู่ในระบบแล้ว\nหากต้องการใช้ข้อมูลนี้ ให้ใช้วิธี ตรวจสอบชื่อ-สกุล แทน";
    }else{
        $data['success'] = true;
    }
}

}

echo json_encode($data);
?>