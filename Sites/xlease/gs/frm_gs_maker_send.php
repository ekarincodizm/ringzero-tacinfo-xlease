<?php
session_start();
include("../config/config.php");
$get_id_user = $_SESSION["av_iduser"];
$cid = pg_escape_string($_POST['cid']);
$comp = pg_escape_string($_POST['comp']);
$nowdate = date("Y/m/d");

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
pg_query("BEGIN WORK");

$qry=pg_query("SELECT payid FROM gas.\"PoGas\" where payid is not null AND status_approve = 'f' AND idcompany='$comp' ORDER BY \"payid\" DESC LIMIT 1");
while($res=pg_fetch_array($qry)){
    $payid = $res["payid"];
}

if( !empty($payid) ){
    $res_g_id=$payid;
}else{
    $g_id=pg_query("select gas.gen_id('$nowdate',1,2)");
    $res_g_id=pg_fetch_result($g_id,0);
    
    $in_sql2="insert into gas.\"PayToGas\" (\"payid\",\"dodate\",\"idmaker\") values  ('$res_g_id','$nowdate','$get_id_user')";
    if($result2=pg_query($in_sql2)){
        $status = 0;
    }else{
        $status = 1;
    }
}       

for($i=0;$i<count($cid);$i++){
    $in_sql="UPDATE gas.\"PoGas\" SET \"status_pay\" = 'true',\"payid\" = '$res_g_id' WHERE \"poid\"='$cid[$i]'";
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
<input type="button" value="  Back  " onclick="location.href='frm_gs_maker.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>