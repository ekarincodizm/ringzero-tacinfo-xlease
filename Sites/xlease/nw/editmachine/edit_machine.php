<?php
session_start();
include("../../config/config.php");
$fs_carid=$_POST["fcar_id"];
$fs_mar=$_POST["f_mar"];
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$assettype = $_POST["assettype"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขเครื่องยนต์</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">
<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
<fieldset><legend><B>แก้ไขเครื่องยนต์</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

if($assettype == 1){
    $update_Fc="Update \"Fc\" SET \"C_MARNUM\"='$fs_mar' where \"CarID\"='$fs_carid' ";
    if($result=pg_query($update_Fc)){
    }else{
        $status = $status+1;
    }
}else{
    $update_Fc="Update \"FGas\" SET \"car_regis\"='$fs_mar' where \"GasID\"='$fs_carid' ";
    if($result=pg_query($update_Fc)){
    }else{
        $status = $status+1;
    }
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขเลขเครื่อง', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=index.php'>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
</div>
 </fieldset> 

</div>
        </td>
    </tr>
</table>          

</body>
</html>