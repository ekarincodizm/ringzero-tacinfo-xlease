<?php
session_start();
include("../config/config.php");
$get_id_user = $_SESSION["av_iduser"];
$cid = pg_escape_string($_POST['cid']);
$nowdate = date("Y/m/d");

pg_query("BEGIN WORK");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>Maker</B></legend>

<div align="center">

<?php

for($i=0;$i<count($cid);$i++){
    
    $qry_payid=pg_query("SELECT payid FROM gas.\"PoGas\" WHERE \"poid\"='$cid[$i]'");
    if($res_payid=pg_fetch_array($qry_payid)){
        $payid = $res_payid["payid"];
        
        $qry_payid=pg_query("SELECT COUNT(payid) AS c_id FROM gas.\"PoGas\" WHERE \"payid\"='$payid'");
        if($res_payid=pg_fetch_array($qry_payid)){
            $c_id = $res_payid["c_id"];
        }
        
    }
            
    if($c_id == 1){
        $in_sql2="UPDATE gas.\"PayToGas\" SET \"Cancel\" = 'true' WHERE \"payid\"='$payid'";
        if($result2=pg_query($in_sql2)){
            $status = 0;
        }else{
            $status = 1;
        }
    }
        
    $in_sql="UPDATE gas.\"PoGas\" SET \"status_pay\" = 'false',\"payid\"=default WHERE \"poid\"='$cid[$i]'";
    if($result=pg_query($in_sql)){
        $status = 0;
    }else{
        $status = 1;
    }

}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
    ?>

<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_maker_cl.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>