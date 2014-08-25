<?php
session_start();
include("../config/config.php");

pg_query("BEGIN WORK");
$status = 0;

$get_id_user = $_SESSION["av_iduser"];
$cid = pg_escape_string($_POST['cid']);
$comp = pg_escape_string($_POST['comp']);
$nowdate = date("Y/m/d");

$qry_pid=pg_query("select \"PayID\" from \"insure\".\"PayToInsure\" WHERE \"Company\"='$comp' AND \"kind\"='2' AND \"idauthority\" is null ");
if($res_pid=pg_fetch_array($qry_pid)){
    $res_oins = $res_pid["PayID"];
}else{
    $oins=pg_query("select \"insure\".gen_co_insid('$nowdate',1,3)");
    $res_oins=pg_fetch_result($oins,0);
    
    $in_sql="insert into \"insure\".\"PayToInsure\" (\"PayID\",\"idmaker\",\"DoDate\",\"kind\",\"Company\") values  ('$res_oins','$get_id_user','$nowdate','2','$comp')";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
}

for($i=0;$i<count($cid);$i++){
    
    $qry_name=pg_query("select \"Kind\",\"Premium\",\"NetPremium\",\"Company\" from \"insure\".\"InsureUnforce\" WHERE \"InsUFIDNO\"='$cid[$i]' ");
    if($res_name=pg_fetch_array($qry_name)){
         $Premium = $res_name["Premium"];
         $NetPremium = $res_name["NetPremium"];
         $Company = $res_name["Company"]; 
         $Kind = $res_name["Kind"];   
    }
    
    $c_com=pg_query("select \"insure\".cal_comm('$Kind','$Company','$NetPremium')");
    $res_comms=pg_fetch_result($c_com,0);
    $showcom = $Premium - $res_comms;
    
    $in_sql="UPDATE \"insure\".\"InsureUnforce\" SET \"CoPayInsAmt\"='$showcom',\"Commision\"='$res_comms',\"CoPayInsID\"='$res_oins' WHERE \"InsUFIDNO\"='$cid[$i]' ";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>
<div class="wrapper">

<?php
if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_maker_select_unforce.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>