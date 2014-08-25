<?php
session_start();
include("../config/config.php");

$get_groupid = $_SESSION["av_usergroup"];
$get_userid = $_SESSION["av_iduser"];
$sescr_idno = $_SESSION["sescr_idno"];
$sescr_scusid = $_SESSION["sescr_scusid"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AV. leasing co.,ltd</title>

<style type="text/css">
<!--
BODY{
	font-family: Tahoma;
	font-size: 11px;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	font-size: 11px;
	color: #3A3A3A;
}
legend{
	font-family: Tahoma;
	font-size: 13px;	
	color: #0000CC;
}
fieldset{
	padding:3px;
}
.TextTitle{
	color: #006600;
	font-size: 12px;
	font-weight: bold;
}.TextTitle2{
	color: #006600;
	font-size: 12px;
	font-weight: bold;
}
.container {
   position: relative;
   height: 15px;
   background-color: #FFFFD7;
   padding:2px;
}
.left-element {
   position: absolute;
   left: 0;
   width: 50%;
   padding:2px;
}
.right-element {
   position: absolute;
   right: 0;
   width: 50%;
   text-align: right; /* depends on element width */
   padding:2px;
} 
.container-area {
   position: relative;
   height: 119px;
}
.left-element-area {
   position: absolute;
   left: 0;
   width: 20%;
}
.right-element-area {
   position: absolute;
   right: 0;
   width: 80%;
   text-align: right; /* depends on element width */
} 
.container-submit {
   position: relative;
   height: 22px;
}

.container-data {
   position: relative;
   height: 15px;
   background-color: #D5EFFD;
   padding:2px;
}
.left-element-data {
   position: absolute;
   left: 0;
   width: 50%;
   padding:2px;
}
.right-element-data {
   position: absolute;
   right: 0;
   width: 50%;
   text-align: right; /* depends on element width */
   padding:2px;
}
.container-data2 {
   position: relative;
   background-color: #EDF8FE;
   padding:2px;
   /*height: 80px;*/
}
.element-data {
	text-align:left;
}
-->
</style>

</head>

<body style="background-color:#ffffff; margin-top:0px;">

<div id="wmax" style="width:500px; border:#666666 solid 0px; margin-top:0px;">

<div style="height:50px; width:auto; text-align:center; opacity:20;"><h1>รายละเอียดการติดต่อ</h1></div>

<div style="height:50px; width:500px; text-align:left; margin:0px auto;">

<?php
if(empty($get_groupid) OR empty($get_userid)){
    echo "<div align=center>ผิดผลาด: ไม่พบข้อมูล แผนก หรือ ผู้ใช้งาน</div>";
}elseif(empty($sescr_idno) OR empty($sescr_scusid)){
    echo "<div align=center>ผิดผลาด: ไม่พบข้อมูล IDNO หรือ CUSID<br>กรุณาเข้าสู่หน้า<u>ใส่ข้อมูล</u> และเลือกรายการที่ต้องการติดตามก่อน</div>";
}else{
?>

<form name="frm_fuc1" method="post" action="follow_up_cus.php">

<div align="right">
Group : 
<SELECT NAME="group" onchange="document.frm_fuc1.submit()";>
	<OPTION VALUE="ALL">ทั้งหมด
<?php
	$qry_fg=pg_query("select * from \"f_groupuser\" ORDER BY id_qroup ASC");
	while($res_fg=pg_fetch_array($qry_fg)){
		if($_POST['group'] == $res_fg["id_qroup"]){
?>
			<OPTION VALUE="<?php echo $res_fg["id_qroup"]; ?>" selected><?php echo $res_fg["name_group"]; ?>
<?php
		}else{
?>
			<OPTION VALUE="<?php echo $res_fg["id_qroup"]; ?>"><?php echo $res_fg["name_group"]; ?>
<?php
		}
	}	
?>
</SELECT>
User : 
<SELECT NAME="userid" onchange="document.frm_fuc1.submit()";>
	<OPTION VALUE="ALL">ทั้งหมด
<?php
if( isset($_POST['userid']) ){
    if( $_POST['group'] == 'ALL' ){
        $qry_fu=pg_query("select id_user,fullname from \"Vfuser\" ORDER BY id_user ASC");
    }else{
        $qry_fu=pg_query("select id_user,fullname from \"Vfuser\" WHERE user_group='".pg_escape_string($_POST[group])."' ORDER BY id_user ASC");    
    }
}else{
    $qry_fu=pg_query("select id_user,fullname from \"Vfuser\" ORDER BY id_user ASC");
}    
	while($res_fu=pg_fetch_array($qry_fu)){
		if($_POST['userid'] == $res_fu["id_user"]){
?>
			<OPTION VALUE="<?php echo $res_fu["id_user"]; ?>" selected><?php echo $res_fu["fullname"]; ?>
<?php
		}else{
?>
			<OPTION VALUE="<?php echo $res_fu["id_user"]; ?>"><?php echo $res_fu["fullname"]; ?>
<?php
		}
	}
?>
</SELECT>
</form>

<div style="width:100%; text-align:left;">

<fieldset>

<?php

$qry_cn=pg_query("select \"IDNO\",\"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\",\"asset_id\" from \"VContact\"  WHERE (\"IDNO\"='$sescr_idno') AND (\"CusID\"='$sescr_scusid')");
$res_cn=pg_fetch_array($qry_cn);

if($res_cn["asset_type"] == 1){ 
    $regis = $res_cn["C_REGIS"]; 
} else { 
    $regis = $res_cn["car_regis"]; 
}

?>

<div class="container">
<div class="left-element"><span class="TextTitle">ชื่อ : </span> <?php echo $res_cn["full_name"]; ?></div>
<div class="right-element"><span class="TextTitle">วันที่ : </span> <?php echo date('d-m-Y'); ?></div>
</div>

<div class="container">
<div class="left-element"><span class="TextTitle">เลขที่สัญญา : </span> <?php echo $res_cn["IDNO"]; ?></div>
<div class="right-element"><span class="TextTitle">ทะเบียนรถ : </span> <?php echo $regis; ?></div>
</div>
<form name="frm_fuc" method="post" action="follow_up_cus_ok.php">

<div class="container-area">
<div style="font-size: 15px; color: #0000CC;"><B>เพิ่มข้อมูล</B></div>
<div><span class="TextTitle2">รายละเอียด : </span><br><TEXTAREA NAME="followdetail" ROWS="6" COLS="93"></TEXTAREA></div>
</div>

<div class="container-submit">
<div class="right-element"><INPUT TYPE="submit" VALUE="     บันทึก     "></div>
</div>

</form>

</fieldset>

</div>

<div align="left" valign="top">

<fieldset><legend><B>ข้อมูลที่ได้เจรจา</B></legend>
<?php

if(isset($_POST['group']) OR isset($_POST['userid'])){
	if($_POST['group'] == "ALL" AND $_POST['userid'] == "ALL"){
		$qry_fuc=pg_query("select \"userid\",\"FollowDate\",\"FollowDetail\" from \"FollowUpCus\" WHERE (\"IDNO\"='$sescr_idno') AND (\"CusID\"='$sescr_scusid') ORDER BY auto_id DESC");
	}elseif($_POST['group'] == "ALL" AND $_POST['userid'] != "ALL"){
		$qry_fuc=pg_query("select * from \"FollowUpCus\" WHERE (\"userid\"='".pg_escape_string($_POST[userid])."') AND (\"IDNO\"='$sescr_idno') AND (\"CusID\"='$sescr_scusid') ORDER BY auto_id DESC");
	}elseif($_POST['group'] != "ALL" AND $_POST['userid'] == "ALL"){
		$qry_fuc=pg_query("select \"userid\",\"FollowDate\",\"FollowDetail\" from \"FollowUpCus\" WHERE (\"GroupID\"='".pg_escape_string($_POST[group])."') AND (\"IDNO\"='$sescr_idno') AND (\"CusID\"='$sescr_scusid') ORDER BY auto_id DESC");
	}else{
		$qry_fuc=pg_query("select \"userid\",\"FollowDate\",\"FollowDetail\" from \"FollowUpCus\" WHERE (\"userid\"='".pg_escape_string($_POST[userid])."') AND (\"GroupID\"='".pg_escape_string($_POST[group])."') AND (\"IDNO\"='$sescr_idno') AND (\"CusID\"='$sescr_scusid') ORDER BY auto_id DESC");
	}
}else{
	$qry_fuc=pg_query("select \"userid\",\"FollowDate\",\"FollowDetail\" from \"FollowUpCus\" WHERE (\"IDNO\"='$sescr_idno') AND (\"CusID\"='$sescr_scusid') ORDER BY auto_id DESC"); // Not WHERE !!!
}

	$numr=pg_num_rows($qry_fuc);
	if($numr==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }
	while($res_fuc=pg_fetch_array($qry_fuc)){

		$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[userid]')");
		$res_fun=pg_fetch_array($qry_fun)
?>

<div class="container-data">
	<div class="left-element-data"><B>User :</B> <?php echo $res_fun["fullname"]; ?></div>
	<div class="right-element-data"><B>วันที่ :</B> <?php echo $res_fuc["FollowDate"]; ?></div>
</div>
<div class="container-data2">
	<div class="element-data"><?php echo $res_fuc["FollowDetail"]; ?></div>
</div>
<div style="clear:both; height:5px;">&nbsp;</div>
<?php
	}
?>
</fieldset>
<?php } ?>
</div>

</div>
</div>

</body>
</html>