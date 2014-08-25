<?php
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>อนุมัติ</B></legend>

<div align="center">

<?php
for($i=0;$i<count($cid);$i++){
    $in_sql="UPDATE gas.\"PoGas\" SET \"status_approve\" = 'true' WHERE \"poid\"='$cid[$i]'";
    if($result=pg_query($in_sql)){
        echo "$cid[$i] อนุมัติสำเร็จ<br>";
    }else{
        echo "$cid[$i] อนุมัติไม่สำเร็จ<br>";
    }
}
?>

<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_approve_ap.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>