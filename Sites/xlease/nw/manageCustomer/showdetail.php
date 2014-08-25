<?php
session_start();
include("../../config/config.php");	
$stsCus=pg_escape_string($_GET["stsCus"]);
$CusID=pg_escape_string($_GET["CusID"]);
$CustempID=pg_escape_string($_GET["CustempID"]);
$Uid = $_SESSION["av_iduser"];
$iduser=pg_escape_string($_GET['iduser']);
$emplevel=pg_escape_string($_GET['emplevel']);
$edittime=pg_escape_string($_GET['edittime']);

session_register("auth_refferer");
session_register("auth_cusid");

if($stsCus==0){ //ข้อมูลเก่า
	$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$CusID' ");
}else{ //ข้อมูลใหม่รอการอนุมัติ
	$qry_fa1=pg_query("select * from \"Customer_Temp\" where \"CustempID\" ='$CustempID' ");
}

$res_fa1=pg_fetch_array($qry_fa1);
$fa1_cusid=trim($res_fa1["CusID"]);
$fa1_firname=trim($res_fa1["A_FIRNAME"]);
$fa1_name=trim($res_fa1["A_NAME"]);
$fa1_surname=trim($res_fa1["A_SIRNAME"]);
$fa1_pair=trim($res_fa1["A_PAIR"]);
$fa1_no=trim($res_fa1["A_NO"]);
$fa1_subno=trim($res_fa1["A_SUBNO"]);
$fa1_soi=trim($res_fa1["A_SOI"]);
$fa1_rd=trim($res_fa1["A_RD"]);	
$fa1_tum=trim($res_fa1["A_TUM"]);	
$fa1_aum=trim($res_fa1["A_AUM"]);
$fa1_pro=trim($res_fa1["A_PRO"]);	
$fa1_post=trim($res_fa1["A_POST"]);

$fa1_firname_eng=trim($res_fa1["A_FIRNAME_ENG"]);
$fa1_name_eng=trim($res_fa1["A_NAME_ENG"]);
$fa1_surname_eng=trim($res_fa1["A_SIRNAME_ENG"]);
$fa1_nickname=trim($res_fa1["A_NICKNAME"]);
$fa1_status=trim($res_fa1["A_STATUS"]);
$fa1_revenue=trim($res_fa1["A_REVENUE"]);
$fa1_education=trim($res_fa1["A_EDUCATION"]);
$fa1_country2=trim($res_fa1["addr_country"]);
$fa1_mobile=trim($res_fa1["A_MOBILE"]);
$fa1_telephone=trim($res_fa1["A_TELEPHONE"]);
$fa1_email=trim($res_fa1["A_EMAIL"]);
$fa1_brithday=trim($res_fa1["A_BIRTHDAY"]);

$fa1_A_SEX2=trim($res_fa1["A_SEX"]);
if($fa1_A_SEX2=="1"){
	$fa1_A_SEX="หญิง";
}else if($fa1_A_SEX2=="2"){
	$fa1_A_SEX="ชาย";
}else{
	$fa1_A_SEX="ไม่ระบุ";
}
$fa1_A_ROOM=trim($res_fa1["A_ROOM"]);
$fa1_A_FLOOR=trim($res_fa1["A_FLOOR"]);
$fa1_A_BUILDING=trim($res_fa1["A_BUILDING"]);
$fa1_A_VILLAGE=trim($res_fa1["A_VILLAGE"]);

//ค้นหาชื่อประเทศ
$query_country=pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$fa1_country2'");
list($fa1_country)=pg_fetch_array($query_country);

if($stsCus==0){
	$qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$CusID' ");
	$res_fn1=pg_fetch_array($qry_Fn);
	$ext_addr=$res_fn1["N_ContactAdd"];	
	$N_SAN=$res_fn1["N_SAN"];
	$N_AGE=$res_fn1["N_AGE"];
	$N_CARD=$res_fn1["N_CARD"];
	$N_IDCARD=$res_fn1["N_IDCARD"];
	$N_OT_DATE=$res_fn1["N_OT_DATE"];
	$N_BY=$res_fn1["N_BY"];
	$N_OCC=$res_fn1["N_OCC"];
	$N_CARDREF=$res_fn1["N_CARDREF"];
	$statuscus=$res_fn1["statuscus"];  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
	$txtsts="(เก่า)";
}else{
	$ext_addr=$res_fa1["N_ContactAdd"];
	$N_SAN=$res_fa1["N_SAN"];
	$N_AGE=$res_fa1["N_AGE"];
	$N_CARD=$res_fa1["N_CARD"];
	$N_IDCARD=$res_fa1["N_IDCARD"];
	$N_OT_DATE=$res_fa1["N_OT_DATE"];
	$N_BY=$res_fa1["N_BY"];
	$N_OCC=$res_fa1["N_OCC"];
	$N_CARDREF=$res_fa1["N_CARDREF"];
	$statuscus=$res_fa1["statuscus"];  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
	$txtsts="(ใหม่)";
}

//เปรียบเีทียบกันเพื่อแสดงสีที่ข้อมูลแตกต่างกัน
	//สร้างไว้สำหรับเปรียบเทียบกับข้อมูลเก่า
	$qry_fa2=pg_query("select * from \"Customer_Temp\" where \"CustempID\" ='$CustempID' ");	
	$res_faNew=pg_fetch_array($qry_fa2);
	$faNew_cusid=trim($res_faNew["CusID"]);
	$faNew_firname=trim($res_faNew["A_FIRNAME"]);
	$faNew_name=trim($res_faNew["A_NAME"]);
	$faNew_surname=trim($res_faNew["A_SIRNAME"]);
	$faNew_pair=trim($res_faNew["A_PAIR"]);
	$faNew_no=trim($res_faNew["A_NO"]);
	$faNew_subno=trim($res_faNew["A_SUBNO"]);
	$faNew_soi=trim($res_faNew["A_SOI"]);
	$faNew_rd=trim($res_faNew["A_RD"]);	
	$faNew_tum=trim($res_faNew["A_TUM"]);	
	$faNew_aum=trim($res_faNew["A_AUM"]);
	$faNew_pro=trim($res_faNew["A_PRO"]);	
	$faNew_post=trim($res_faNew["A_POST"]);
	
	$faNew_firname_eng=trim($res_faNew["A_FIRNAME_ENG"]);
	$faNew_name_eng=trim($res_faNew["A_NAME_ENG"]);
	$faNew_surname_eng=trim($res_faNew["A_SIRNAME_ENG"]);
	$faNew_nickname=trim($res_faNew["A_NICKNAME"]);
	$faNew_status=trim($res_faNew["A_STATUS"]);
	$faNew_revenue=trim($res_faNew["A_REVENUE"]);
	$faNew_education=trim($res_faNew["A_EDUCATION"]);
	$faNew_country2=trim($res_faNew["addr_country"]);
	$faNew_mobile=trim($res_faNew["A_MOBILE"]);
	$faNew_telephone=trim($res_faNew["A_TELEPHONE"]);
	$faNew_email=trim($res_faNew["A_EMAIL"]);
	$faNew_brithday=trim($res_faNew["A_BIRTHDAY"]);
	
	$faNew_A_SEX=trim($res_faNew["A_SEX"]);
	$faNew_A_ROOM=trim($res_faNew["A_ROOM"]);
	$faNew_A_FLOOR=trim($res_faNew["A_FLOOR"]);
	$faNew_A_BUILDING=trim($res_faNew["A_BUILDING"]);
	$faNew_A_VILLAGE=trim($res_faNew["A_VILLAGE"]);

	$ext_addr2=trim($res_faNew["N_ContactAdd"]);
	$N_SAN2=trim($res_faNew["N_SAN"]);
	$N_AGE2=trim($res_faNew["N_AGE"]);
	$N_CARD2=trim($res_faNew["N_CARD"]);
	$N_IDCARD2=trim($res_faNew["N_IDCARD"]);
	$N_OT_DATE2=trim($res_faNew["N_OT_DATE"]);
	$N_BY2=trim($res_faNew["N_BY"]);
	$N_OCC2=trim($res_faNew["N_OCC"]);
	$N_CARDREF2=trim($res_faNew["N_CARDREF"]);
	
	//ค้นหาชื่อประเทศ
	$query_country2=pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$faNew_country2'");
	list($faNew_country)=pg_fetch_array($query_country2);
	
//สร้างไว้สำหรับเปรียบเทียบกับข้อมูลใหม่
	$qry_fa2=pg_query("select * from \"Fa1\" where \"CusID\" ='$faNew_cusid' ");
	$res_faOld=pg_fetch_array($qry_fa2);
	$faOld_cusid=trim($res_faOld["CusID"]);
	$faOld_firname=trim($res_faOld["A_FIRNAME"]);
	$faOld_name=trim($res_faOld["A_NAME"]);
	$faOld_surname=trim($res_faOld["A_SIRNAME"]);
	$faOld_pair=trim($res_faOld["A_PAIR"]);
	$faOld_no=trim($res_faOld["A_NO"]);
	$faOld_subno=trim($res_faOld["A_SUBNO"]);
	$faOld_soi=trim($res_faOld["A_SOI"]);
	$faOld_rd=trim($res_faOld["A_RD"]);	
	$faOld_tum=trim($res_faOld["A_TUM"]);	
	$faOld_aum=trim($res_faOld["A_AUM"]);
	$faOld_pro=trim($res_faOld["A_PRO"]);	
	$faOld_post=trim($res_faOld["A_POST"]);
	$faOld_firname_eng=trim($res_faOld["A_FIRNAME_ENG"]);
	$faOld_name_eng=trim($res_faOld["A_NAME_ENG"]);
	$faOld_surname_eng=trim($res_faOld["A_SIRNAME_ENG"]);
	$faOld_nickname=trim($res_faOld["A_NICKNAME"]);
	$faOld_status=trim($res_faOld["A_STATUS"]);
	$faOld_revenue=trim($res_faOld["A_REVENUE"]);
	$faOld_education=trim($res_faOld["A_EDUCATION"]);
	$faOld_country2=trim($res_faOld["addr_country"]);
	$faOld_mobile=trim($res_faOld["A_MOBILE"]);
	$faOld_telephone=trim($res_faOld["A_TELEPHONE"]);
	$faOld_email=trim($res_faOld["A_EMAIL"]);
	$faOld_brithday=trim($res_faOld["A_BIRTHDAY"]);
	
	$faOld_A_SEX=trim($res_faOld["A_SEX"]);
	$faOld_A_ROOM=trim($res_faOld["A_ROOM"]);
	$faOld_A_FLOOR=trim($res_faOld["A_FLOOR"]);
	$faOld_A_BUILDING=trim($res_faOld["A_BUILDING"]);
	$faOld_A_VILLAGE=trim($res_faOld["A_VILLAGE"]);
	
	//ค้นหาชื่อประเทศ
	$query_country3=pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$faOld_country'");
	list($faOld_country)=pg_fetch_array($query_country3);
	
	$qry_FnNew=pg_query("select * from \"Fn\" where \"CusID\" ='$faNew_cusid' ");
	$res_fn2=pg_fetch_array($qry_FnNew);
	$ext_addr1=trim($res_fn2["N_ContactAdd"]);	
	$N_SAN1=trim($res_fn2["N_SAN"]);
	$N_AGE1=trim($res_fn2["N_AGE"]);
	$N_CARD1=trim($res_fn2["N_CARD"]);
	$N_IDCARD1=trim($res_fn2["N_IDCARD"]);
	$N_OT_DATE1=trim($res_fn2["N_OT_DATE"]);
	$N_BY1=trim($res_fn2["N_BY"]);
	$N_OCC1=trim($res_fn2["N_OCC"]);
	$N_CARDREF1=trim($res_fn2["N_CARDREF"]);
	
	$edit_state=0;
	$start_time=date("H:i:s");
	
	$_SESSION["auth_refferer"] = 'pass';
	$_SESSION["auth_cusid"] = $CustempID;

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript">
var start_time = new Date();
var start_hour = start_time.getHours();
var start_minute = start_time.getMinutes();
var start_second = start_time.getSeconds();
var start_msec = start_time.getMilliseconds();
var start = (start_hour*60*60*1000)+(start_minute*60*1000)+(start_second*1000)+start_msec;
$(document).ready(function(){
	var settimmer = 0;
	window.setInterval(function() {
		var timeCounter = $("#countdown").html();
		var updateTime = eval(timeCounter)- eval(1);
		if(updateTime != -1)
		{
			$("#countdown").html(updateTime);	
		}
		else{
			$('#divwait').css({
				'border': '1px dotted green',
				'color':'green'
			});
		}
	}, 1000);	
});
function checkappv(){
	var time_to_use = $("#time_to_use").val();
		
		var end_time = new Date();
		var end_hour = end_time.getHours();
		var end_minute = end_time.getMinutes();
		var end_second = end_time.getSeconds();
		var end_msec = end_time.getMilliseconds();
		var end = (end_hour*60*60*1000)+(end_minute*60*1000)+(end_second*1000)+end_msec;
		var used_time = end - start;
		if(used_time<time_to_use)
		{
			alert('คุณใช้เวลาน้อยเกินไป  เวลาขั้นต่ำสำหรับใช้ในการอนุมัติเฉพาะรายการนี้คือ '+ (time_to_use/1000) + ' วินาที \r\n !! จำนวนเวลาขั้นต่ำอนุมัติ ขึ้นอยู่กับความมากน้อยของการเปลี่ยนแปลงแก้ไขข้อมูล');
		}
		else
		{
			//window.open('process_approve.php?CustempID=<?php echo $CustempID; ?>&edittime=<?php echo $edittime;?>&stsapp=1');
			/*window.location='process_approve.php?CustempID=<?php echo $CustempID; ?>&edittime=<?php echo $edittime;?>&stsapp=1';*/
			return true;
		}
}
function checkunappv(){
	var time_to_use = $("#time_to_use").val();
		
		var end_time = new Date();
		var end_hour = end_time.getHours();
		var end_minute = end_time.getMinutes();
		var end_second = end_time.getSeconds();
		var end_msec = end_time.getMilliseconds();
		var end = (end_hour*60*60*1000)+(end_minute*60*1000)+(end_second*1000)+end_msec;
		var used_time = end - start;
		if(used_time<time_to_use)
		{
			alert('คุณใช้เวลาน้อยเกินไป  เวลาขั้นต่ำสำหรับใช้ในการอนุมัติเฉพาะรายการนี้คือ '+ (time_to_use/1000) + ' วินาที \r\n !!จำนวนเวลาขั้นต่ำอนุมัติ ขึ้นอยู่กับความมากน้อยของการเปลี่ยนแปลงแก้ไขข้อมูล');
		}
		else
		{
			//window.open('process_approve.php?CustempID=<?php echo $CustempID; ?>&edittime=<?php echo $edittime;?>&stsapp=0');
			/*window.location='process_approve.php?CustempID=<?php echo $CustempID; ?>&edittime=<?php echo $edittime;?>&stsapp=0';*/
			return true;
		}}
</script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>

<style type="text/css">
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
}
.style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
}
.style6 {
	color: #FF0000;
	font-weight: bold;
}
#warppage
{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
}
button {
	width: 80px;
	margin-left: 5px;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 13px;
	line-height: 25px;
	font-weight: normal;
	color: #333;
	text-decoration: none;
	padding: 0px;
}
#btnclose {
	position: relative;
	top: -10px;
	right: -190px;
}
#divoperation {
	position: relative;
}
#divwait {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 17px;
	font-weight: normal;
	color: red;
	text-decoration: none;
	background-color: #fff;
	border: 1px dotted red;
	float: left;
	height: 30px;
	width: 300px;
	line-height: 30px;
	position: absolute;
	left: 0px;
	top: 0px;
}
</style>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?><button id="btnclose" onClick="window.close();">ปิดหน้าต่าง</button></h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;"><b>แสดงข้อมูลลูกค้า <?php echo $txtsts;?></b> <br /><hr />
		<input type="hidden" name="method" value="add" />
		<table width="785" border="0" cellpadding="1" cellspacing="1">
		<tr>
			<td colspan="6" style="background-color:#FFFFCC;"><b>ข้อมูลลูกค้า</b></td>
		</tr>
		<tr>
			<td colspan="6">
				<input type="radio" name="statuscus" id="cus1" value="0" <?php if($statuscus==0){ echo "checked"; } ?> disabled> คนไทย
				<input type="radio" name="statuscus" id="cus2" value="1" <?php if($statuscus==1){ echo "checked"; } ?> disabled> ชาวต่างชาติ
				<input type="radio" name="statuscus" id="cus3" value="2" <?php if($statuscus==2){ echo "checked"; } ?> disabled> บริษัท
				<input type="radio" name="statuscus" id="cus4" value="3" <?php if($statuscus==''){ echo "checked"; } ?> disabled> ไม่ระบุ
			</td>
		</tr>
		<tr>
			<td>คำนำหน้าชื่อ (ไทย)</td>
			<td><input type="text" name="f_fri_name" value="<?php echo $fa1_firname; ?>" readonly <?php if($faOld_firname!=$faNew_firname){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_fri_name_eng" value="<?php echo $fa1_firname_eng; ?>" readonly <?php if($faOld_firname_eng!=$faNew_firname_eng){ echo"style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td width="144">ชื่อ(ไทย)</td>
			<td width="227"><input type="text" name="f_name" value="<?php echo $fa1_name; ?>" readonly <?php if($faOld_name!=$faNew_name){ echo"style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="90">ชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_name_eng" value="<?php echo $fa1_name_eng; ?>" readonly <?php if($faOld_name_eng!=$faNew_name_eng){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td>นามสกุล (ไทย)</td>
			<td><input type="text" name="f_surname" value="<?php echo $fa1_surname; ?>" readonly <?php if($faOld_surname!=$faNew_surname){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td>นามสกุล (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_surname_eng" value="<?php echo $fa1_surname_eng; ?>" readonly <?php if($faOld_surname_eng!=$faNew_surname_eng){ echo"style=\"background-color:#FFCCCC\""; $edit_state++; }?> size="15">
			 เพศ <input type="text" value="<?php echo $fa1_A_SEX;?>" readonly <?php if($faOld_A_SEX!=$faNew_A_SEX){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?> size="5">
			</td>
		</tr>
		<tr>
			<td>ชื่อเล่น</td>
			<td><input type="text" name="f_nickname" value="<?php echo $fa1_nickname; ?>" readonly <?php if($faOld_nickname!=$faNew_nickname){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?> /></td>
			<td width="90">วันเกิด</td>
			<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" value="<?php echo $fa1_brithday; ?>" readonly <?php if($faOld_brithday!=$faNew_brithday){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>  size="15"/>
			อายุ
			<input type="text" name="f_age" id="f_age" value="<?php echo $N_AGE; ?>" readonly <?php if($N_AGE1!=$N_AGE2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>   size="5"/></td>
		</tr>
		
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227"><input type="text" name="f_san" value="<?php echo $N_SAN; ?>" readonly <?php if($N_SAN1!=$N_SAN2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="90">ระดับการศึกษา</td>
			<td colspan="3">
				<?php 
					if($fa1_education=="1"){
						$txtedu="ต่ำกว่ามัธยมศึกษาตอนต้น";
					}else if($fa1_education=="2"){
						$txtedu="มัธยมศึกษาตอนต้น";
					}else if($fa1_education=="3"){
						$txtedu="มัธยมศึกษาตอนปลาย";
					}else if($fa1_education=="4"){
						$txtedu="ปวช.";
					}else if($fa1_education=="5"){
						$txtedu="ปวส.";
					}else if($fa1_education=="6"){
						$txtedu="อนุปริญญา";
					}else if($fa1_education=="7"){
						$txtedu="ปริญญาตรี";
					}else if($fa1_education=="8"){
						$txtedu="ปริญญาโท";
					}else if($fa1_education=="9"){
						$txtedu="ปริญญาเอก";
					}
				?>
				<input type="text" value="<?php echo $txtedu;?>" readonly <?php if($faOld_education!=$faNew_education){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>>
			</td>
		</tr>
		<tr>
			<td width="144">รายได้ต่อเดือนประมาณ</td>
			<td width="227"><input type="text" name="f_revenue" id="f_revenue" value="<?php echo $fa1_revenue; ?>" readonly <?php if($faOld_revenue!=$faNew_revenue){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="90">สถานภาพ</td>
			<td colspan="3">
				<?php
				if($fa1_status=="0002"){
					$txtstatus="โสด";
				}else if($fa1_status=="0001"){
					$txtstatus="สมรส";
				}else if($fa1_status=="0005"){
					$txtstatus="สมรสไม่จดทะเบียน";
				}else if($fa1_status=="0004"){
					$txtstatus="หย่า";
				}else if($fa1_status=="0003"){
					$txtstatus="หม้าย";
				}else{
					$txtstatus="ไม่ระบุ";
				}
				?>
				<input type="text" value="<?php echo $txtstatus;?>" readonly <?php if($faOld_status!=$faNew_status){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>>
			</td>
		</tr>
		<tr>
			<td width="144">ชื่อ คู่สมรส</td>
			<td width="227"><input type="text" name="f_pair" value="<?php echo $fa1_pair; ?>" readonly <?php if($faOld_pair!=$faNew_pair){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="90">อาชีพ</td>
			<td colspan="3"><input type="text" name="f_occ" value="<?php echo $N_OCC; ?>" readonly <?php if($N_OCC1!=$N_OCC2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td width="144">เลขที่บัตรประชาชน</td>			
			<td width="227"><input type="text" name="f_cardid" value="<?php echo $N_IDCARD; ?>" readonly <?php if($N_IDCARD1!=$N_IDCARD2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="90">บัตรแสดงตัว</td>
			<td colspan="3"><input type="text" name="f_card" value="<?php echo $N_CARD; ?>" readonly <?php if($N_CARD1!=$N_CARD2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			
		</tr>
		<tr>
			<td width="144">วันที่ออกบัตร</td>
			<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_OT_DATE; ?>" readonly <?php if($N_OT_DATE1!=$N_OT_DATE2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td width="90">ออกให้โดย</td>
			<td colspan="3"><input type="text" name="f_card_by" value="<?php echo $N_BY; ?>" readonly <?php if($N_BY1!=$N_BY2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<?php if($N_CARD != "บัตรประชาชน"){ ?>
		<tr>
			<td width="144">เลขที่บัตรอื่นๆ</td>
			<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_CARDREF; ?>" readonly <?php if($N_CARDREF1!=$N_CARDREF2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			
		</tr>
		<?php } ?>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td>เลขที่</td>
			<td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>" readonly <?php if($faOld_no!=$faNew_no){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td>หมู่ที่</td>
			<td colspan="3"><input type="text" name="f_subno" value="<?php echo $fa1_subno; ?>" readonly <?php if($faOld_subno!=$faNew_subno){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30" value="<?php echo $fa1_A_ROOM; ?>" readonly <?php if($faOld_A_ROOM!=$faNew_A_ROOM){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo $fa1_A_FLOOR; ?>" readonly <?php if($faOld_A_FLOOR!=$faNew_A_FLOOR){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>></td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30" value="<?php echo $fa1_A_BUILDING; ?>" readonly <?php if($faOld_A_BUILDING!=$faNew_A_BUILDING){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo $fa1_A_VILLAGE; ?>" readonly <?php if($faOld_A_VILLAGE!=$faNew_A_VILLAGE){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>></td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td><input type="text" name="f_soi" value="<?php echo $fa1_soi; ?>" readonly <?php if($faOld_soi!=$faNew_soi){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td>ถนน</td>
			<td colspan="3"><input type="text" name="f_rd" value="<?php echo $fa1_rd; ?>" readonly <?php if($faOld_rd!=$faNew_rd){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td>แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" value="<?php echo $fa1_tum; ?>" readonly <?php if($faOld_tum!=$faNew_tum){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td>เขต/อำเภอ</td>
			<td colspan="3"><input type="text" name="f_aum" value="<?php echo $fa1_aum; ?>" readonly <?php if($faOld_aum!=$faNew_aum){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td>จังหวัด</td>
			<td><input type="text" value="<?php echo $fa1_pro; ?>" readonly <?php if($faOld_pro!=$faNew_pro){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>></td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3"><input type="text" name="f_post" value="<?php echo $fa1_post; ?>" maxlength="5" readonly <?php if($faOld_post!=$faNew_post){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td>ประเทศ</td>
			<td><input type="text" value="<?php echo $fa1_country; ?>" readonly <?php if($faNew_country2!=$faOld_country2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>>
			</td>
			<td>โทรศัพท์มือถือ</td>
			<td colspan="3"><input type="text" name="f_mobile" value="<?php echo $fa1_mobile; ?>" readonly <?php if($faOld_mobile!=$faNew_mobile){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" value="<?php echo $fa1_telephone; ?>" readonly <?php if($faOld_telephone!=$faNew_telephone){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo $fa1_email ?>" size="30" readonly <?php if($faOld_email!=$faNew_email){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>/></td>
		</tr>
		
		<tr>
			<td>ที่อยู่ใช้ติดต่อ</td>
			<td>
				<textarea name="f_ext" cols="50" rows="5" readonly <?php if($ext_addr1!=$ext_addr2){ echo "style=\"background-color:#FFCCCC\""; $edit_state++; }?>><?php echo $ext_addr; ?></textarea>
			</td>
			<td colspan="4" valign="top" rowspan="2">
			
			</td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="4">&nbsp;</td>
		</tr> 
		<?php
		if($stsCus==1){ //กรณีเป็นข้อมูลใหม่ให้มีการอนุมัติ ข้อมูลเก่าไม่ต้อง
		?>
		<tr>
			<td colspan="7" align="center">
            <div id="divoperation">
            <div id="divwait">เหลือเวลาอีก <span id="countdown"><?php echo $edit_state*2; ?></span> วินาที</div>
                <?php
				if($iduser!=$Uid || $emplevel<=1)
				{
					echo "<button id=\"pass\" onclick=\"if(checkappv()){ 
					document.forms['my_app'].ap.click();}\">อนุมัติ</button>";
					echo "<button id=\"dont_pass\" onclick=\"if(checkunappv()){ 
					document.forms['my_app'].unap.click();}\">ไม่อนุมัติ</button>";
				}
				?>
            </div>
			</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="4">&nbsp;</td>
		</tr>
		</table>
	</div>
</div>
<input type="hidden" id="time_to_use" value="<?php $time_to_use = $edit_state*2000; echo $time_to_use; ?>" />
<form name="my_app" method="post" action="process_approve.php">
	<input type="hidden" name="CustempID" id="CustempID" value="<?php echo $CustempID; ?>">
	<input type="hidden" name="edittime" id="edittime" value="<?php echo $edittime;?>">
	<input name="ap" type="submit" hidden />
	<input name="unap" type="submit" hidden />
</body>
</html>
