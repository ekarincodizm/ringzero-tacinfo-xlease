<?php
session_start();
include("../config/config.php");
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$cid = pg_escape_string($_GET['cid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
        
<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper" align="center">
 
<fieldset><legend><b>แก้ไขรายการรับเล่มเข้า</b></legend>

<?php
pg_query("BEGIN");
    $in_sql="UPDATE carregis.\"CarTaxDue\" SET \"BookIn\" = 'false',\"BookInDate\" = DEFAULT WHERE \"IDCarTax\"='$cid';";
    if($result=pg_query($in_sql)){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) แก้ไขรายการรับเล่มทะเบียนเข้า', '$currentdate')");
		//ACTIONLOG---
		pg_query("COMMIT");
        echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    }else{
		pg_query("ROLLBACK");
        echo "ไม่สามารถบันทึกข้อมูลได้";
    }
?>

</div>

        </td>
    </tr>
</table>

</body>
</html>

</body>
</html>