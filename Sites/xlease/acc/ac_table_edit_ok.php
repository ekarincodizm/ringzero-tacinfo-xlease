<?php
session_start();
include("../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$acid = pg_escape_string($_POST['acid']);
$acname = pg_escape_string($_POST['acname']);
$actype = pg_escape_string($_POST['actype']);
$status = pg_escape_string($_POST['status']);
$delable = pg_escape_string($_POST['delable']);
$showonfs = pg_escape_string($_POST['showonfs']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>


<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div class="wrapper">
 
<fieldset><legend><b>สร้างเลขที่บัญชี</b></legend>

<div align="center">
<?php
$in_sql="UPDATE \"account\".\"AcTable\" SET \"AcName\"='$acname',\"AcType\"='$actype',\"Status\"='$status',\"Delable\"='$delable',\"ShowOnFS\"='$showonfs' WHERE \"AcID\"='$acid'";
if($result=pg_query($in_sql)){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขเลขที่ระบบบัญชี', '$add_date')");
	//ACTIONLOG---
    echo "แก้ไขข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถแก้ไขข้อมูลได้";
}
?>

<br>
<input name="button" type="button" onclick="window.close();" value=" Close " />

</div>

</div>
        </td>
    </tr>
</table>

</body>
</html>