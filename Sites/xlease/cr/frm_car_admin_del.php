<?php 
include("../config/config.php");
$cid = pg_escape_string($_GET['cid']);
$mm = pg_escape_string($_GET['mm']);
$yy = pg_escape_string($_GET['yy']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>    
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">

<fieldset><legend><B>ลบข้อมูล</B></legend>

<?php

pg_query("BEGIN WORK");

$del_sql1 = "delete  from carregis.\"CarTaxDue\" where \"IDCarTax\"='$cid' ";
if(pg_query($del_sql1)){
    $status = 0;
}else{
    $status = 1;
}

$qry_dt = pg_query("SELECT \"IDDetail\" FROM carregis.\"DetailCarTax\" WHERE \"IDCarTax\"='$cid' ");
$rows_dt = pg_num_rows($qry_dt);

if($rows_dt>0){
    $del_sql2 = "delete  from carregis.\"DetailCarTax\" where \"IDCarTax\"='$cid' ";
    if(pg_query($del_sql2)){
        $status = 0;
    }else{
        $status = 1;
    }
}

if($status == 0){
    pg_query("COMMIT");
    echo "ลบข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถลบข้อมูล";
}

?>

<div align="center">
<br>
<input type=button value="  Back  " onclick=history.back()>
</div>


</fieldset>

</div>
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>