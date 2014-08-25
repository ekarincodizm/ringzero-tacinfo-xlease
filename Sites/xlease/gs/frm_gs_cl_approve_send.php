<?php
include("../config/config.php");

$cid = pg_escape_string($_POST['cid']);
//$nowdate = date("Y/m/d");
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

<fieldset><legend><B>ยกเลิกรายการ</B></legend>

<div align="center">

<?php
$er = 0;
for($i=0;$i<count($cid);$i++){
    $in_sql="UPDATE \"GasPo\" SET \"bill\"='',\"invoice\"='',\"status_pay\"='false' WHERE \"poid\"='$cid[$i]'";
    if($result=pg_query($in_sql)){
        echo "$cid[$i] ยกเลิกรายการสำเร็จ<br>";
    }else{
        echo "$cid[$i] ยกเลิกรายการไม่สำเร็จ<br>";
    }
}
?>

<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_cl.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>