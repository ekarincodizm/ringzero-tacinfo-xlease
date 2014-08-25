<?php
session_start();
include("../config/config.php");

$id = pg_escape_string($_POST['id']);
$name = pg_escape_string($_POST['name']);
$model = pg_escape_string($_POST['model']);
$cost = pg_escape_string($_POST['cost']);
$price_tank = pg_escape_string($_POST['price_tank']);
$price_device = pg_escape_string($_POST['price_device']);
$address = pg_escape_string($_POST['address']);
$phone = pg_escape_string($_POST['phone']);
$memo = pg_escape_string($_POST['memo']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div align="right"><a href="frm_typegas_show.php"><img src="full_page.png" border="0" width="16" height="16" align="absmiddle"> แสดงรายการ</a></div>
<fieldset><legend><B>แก้ไขบริษัท Gas</B></legend>
<div align="center">
<?php
    
    $in_sql="UPDATE \"GasCompany\" SET \"coid\"='$id',\"coname\"='$name',\"model\"='$model',\"cocost\"='$cost',\"price_tank\"='$price_tank',\"price_device\"='$price_device',\"address\"='$address',\"phone\"='$phone',\"memo\"='$memo' WHERE \"coid\" = '$id' AND \"model\"='$model' ";
    if($result=pg_query($in_sql)){
          echo "บันทึกเรียบร้อยแล้ว";
    }else{
          echo "ไม่สามารถบันทึกได้";
    }

?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_typegas_show.php'">
</div>

</fieldset>

<div align="center"><br><input type="button" value="กลับหน้าหลัก" onclick="location.href='../list_menu.php'"></div>

        </td>
    </tr>
</table>

</body>
</html>