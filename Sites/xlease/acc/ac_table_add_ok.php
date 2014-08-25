<?php
session_start();
include("../config/config.php");

$acid = pg_escape_string($_POST['acid']);
$acname = pg_escape_string($_POST['acname']);
$actype = pg_escape_string($_POST['actype']);
$status = pg_escape_string($_POST['status']);
$delable = pg_escape_string($_POST['delable']);
$showonfs = pg_escape_string($_POST['showonfs']);

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$select_acid = pg_query("SELECT COUNT(\"AcID\") AS acid FROM \"account\".\"AcTable\" WHERE \"AcID\"='$acid';");
$res_acid=pg_fetch_result($select_acid,0);
if($res_acid > 0){
    echo '<div align="center">พบข้อมูลซ้ำ!<br>AcID '.$acid.' ได้ถูกเพิ่มไปแล้ว';
    echo '<br><input name="button" type="button" onclick="javascript:history.back();" value=" กลับ " /></div>';
    exit();
}

pg_query("BEGIN WORK");
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
$in_sql="insert into \"account\".\"AcTable\" (\"AcID\",\"AcName\",\"AcType\",\"Status\",\"Delable\",\"ShowOnFS\") values  ('$acid','$acname','$actype','$status','$delable','$showonfs')";
if($result=pg_query($in_sql)){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) สร้างเลขที่ระบบบัญชี', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "เพิ่มข้อมูลเรียบร้อยแล้ว<br><br>";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถเพิ่มข้อมูลได้<br><br>";
}
?>

<input name="button" type="button" onclick="window.location='ac_table_add.php'" value=" กลับ " />

</div>

</div>
        </td>
    </tr>
</table>

</body>
</html>