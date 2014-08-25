<?php
session_start();
include("../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];

$idno = pg_escape_string($_POST['gidno']);
$cusid = pg_escape_string($_POST['cus_id']);
$asset_id = pg_escape_string($_POST['asset_id']);
$insid = "";
$tempinsid = pg_escape_string($_POST['tempinsid']);
$company = pg_escape_string($_POST['company']);
$date_start = pg_escape_string($_POST['date_start']);
$date_end = pg_escape_string($_POST['date_end']);
$term = pg_escape_string($_POST['term']);
$invest = pg_escape_string($_POST['invest']);
$premium = pg_escape_string($_POST['premium']);
$nowdate = date("Y/m/d");
$discount = pg_escape_string($_POST['discount']);
$insuser = pg_escape_string($_POST['insuser']);
$collectcus = pg_escape_string($_POST['collectcus']);

$select_insid = pg_query("SELECT COUNT(\"TempInsID\") AS c_tempid FROM \"insure\".\"InsureLive\" WHERE \"TempInsID\"='$tempinsid';");
$res_insid=pg_fetch_result($select_insid,0);
if($res_insid > 0){
    echo '<div align="center">พบข้อมูลซ้ำ!<br>เลขรับแจ้ง '.$tempinsid.' ได้ถูกเพิ่มไปแล้ว';
    echo '<br><input name="button" type="button" onclick="javascript:history.back();" value=" กลับ " /></div>';
    exit();
}

pg_query("BEGIN WORK");

$oins=pg_query("select \"insure\".gen_co_insid('$nowdate',1,4)");
$res_oins=pg_fetch_result($oins,0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name'];?></title>
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
$in_sql = "insert into \"insure\".\"InsureLive\" (\"InsLIDNO\",\"IDNO\",\"CusID\",\"CarID\",\"TempInsID\",\"Company\",\"StartDate\",\"EndDate\",\"Code\",\"Invest\",\"Premium\",\"ConfirmDate\",\"Discount\",\"CollectCus\",\"InsUser\") values  ('$res_oins','$idno','$cusid','$asset_id','$tempinsid','$company','$date_start','$date_end','$term','$invest','$premium','$nowdate','$discount','$collectcus','$insuser')";
if($result=pg_query($in_sql)){
		
    pg_query("COMMIT");
	//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', 'เพิ่มข้อมูลประกันภัยคุ้มครองหนี้', '$datelog')");
		//ACTIONLOG---
    echo "เพิ่มข้อมูลเรียบร้อยแล้ว"; 
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถเพิ่มข้อมูลได้<br><br><INPUT TYPE=\"BUTTON\" VALUE=\"Back\" ONCLICK=\"history.go(-1)\">";
}
?>
<br>
</div>
 <div align="center"><br><INPUT TYPE="BUTTON" VALUE="Back" ONCLICK="window.location='frm_insure_live.php'"></div> 
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>



</body>
</html>