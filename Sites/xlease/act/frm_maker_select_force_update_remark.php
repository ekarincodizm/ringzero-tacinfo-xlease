<?php
session_start();
include("../config/config.php");

$remark = pg_escape_string($_POST['remark']);
$payid = pg_escape_string($_POST['payid']); 
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
    $in_sql="UPDATE \"insure\".\"PayToInsure\" SET \"Remark\"='$remark' WHERE \"PayID\"='$payid' ";
    if($result=pg_query($in_sql)){
          echo "บันทึกเรียบร้อยแล้ว"; 
    }else{
          echo "ไม่สามารถบันทึกได้";
    }

?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_maker_select_force_show.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>