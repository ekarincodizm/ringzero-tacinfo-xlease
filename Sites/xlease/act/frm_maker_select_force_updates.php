<?php
session_start();
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);
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
$er = 0;
for($i=0;$i<count($cid);$i++){
    $in_sql="UPDATE \"insure\".\"InsureForce\" SET \"Commision\"=0,\"CoPayInsAmt\"=0,\"CoPayInsID\"=NULL WHERE \"InsFIDNO\"='$cid[$i]' ";
    if($result=pg_query($in_sql)){
           
    }else{
        $er+=1;
        $rp .= $cid[$i].",";
    }
}

if($er > 0){
    echo "<br>บันทึกข้อมูลผิดผลาด $er รายการ [$rp]";
}else{
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}

?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_maker_select_force_edit.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>