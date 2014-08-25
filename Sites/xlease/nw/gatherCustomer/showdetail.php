<?php
session_start();
include("../../config/config.php");	

$autoID=$_GET["autoID"];

$qry_cusTemp=pg_query("select * from \"change_cus_temp\" where \"autoID\" ='$autoID' ");
while($cusTemp = pg_fetch_array($qry_cusTemp))
{
	$Cus_old=$cusTemp["Cus_old"];
	$Cus_new=$cusTemp["Cus_new"];
}

//ข้อมูลเก่า
$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$Cus_old' ");

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


$qry_fn=pg_query("select * from \"Fn\" where \"CusID\" ='$Cus_old' ");
$res_fn=pg_fetch_array($qry_fn);

$N_STATE=trim($res_fn["N_STATE"]);
$N_SAN=trim($res_fn["N_SAN"]);
$N_AGE=trim($res_fn["N_AGE"]);
$N_CARD=trim($res_fn["N_CARD"]);
$N_IDCARD=trim($res_fn["N_IDCARD"]);
$N_OT_DATE=trim($res_fn["N_OT_DATE"]);
$N_BY=trim($res_fn["N_BY"]);
$N_OCC=trim($res_fn["N_OCC"]);
$ext_addr=trim($res_fn["N_ContactAdd"]);
$N_CARDREF=trim($res_fn["N_CARDREF"]);

//--------------------------------------------------------------------------------------------------

//ข้อมูลใหม่
$qry_fa1_new=pg_query("select * from \"Fa1\" where \"CusID\" ='$Cus_new' ");

$res_fa1_new=pg_fetch_array($qry_fa1_new);
$fa1_cusid_new=trim($res_fa1_new["CusID"]);
$fa1_firname_new=trim($res_fa1_new["A_FIRNAME"]);
$fa1_name_new=trim($res_fa1_new["A_NAME"]);
$fa1_surname_new=trim($res_fa1_new["A_SIRNAME"]);
$fa1_pair_new=trim($res_fa1_new["A_PAIR"]);
$fa1_no_new=trim($res_fa1_new["A_NO"]);
$fa1_subno_new=trim($res_fa1_new["A_SUBNO"]);
$fa1_soi_new=trim($res_fa1_new["A_SOI"]);
$fa1_rd_new=trim($res_fa1_new["A_RD"]);	
$fa1_tum_new=trim($res_fa1_new["A_TUM"]);	
$fa1_aum_new=trim($res_fa1_new["A_AUM"]);
$fa1_pro_new=trim($res_fa1_new["A_PRO"]);	
$fa1_post_new=trim($res_fa1_new["A_POST"]);

$fa1_firname_eng_new=trim($res_fa1_new["A_FIRNAME_ENG"]);
$fa1_name_eng_new=trim($res_fa1_new["A_NAME_ENG"]);
$fa1_surname_eng_new=trim($res_fa1_new["A_SIRNAME_ENG"]);
$fa1_nickname_new=trim($res_fa1_new["A_NICKNAME"]);
$fa1_status_new=trim($res_fa1_new["A_STATUS"]);
$fa1_revenue_new=trim($res_fa1_new["A_REVENUE"]);
$fa1_education_new=trim($res_fa1_new["A_EDUCATION"]);
$fa1_country2_new=trim($res_fa1_new["addr_country"]);
$fa1_mobile_new=trim($res_fa1_new["A_MOBILE"]);
$fa1_telephone_new=trim($res_fa1_new["A_TELEPHONE"]);
$fa1_email_new=trim($res_fa1_new["A_EMAIL"]);
$fa1_brithday_new=trim($res_fa1_new["A_BIRTHDAY"]);

$fa1_A_SEX2_new=trim($res_fa1_new["A_SEX"]);
if($fa1_A_SEX2_new=="1"){
	$fa1_A_SEX_new="หญิง";
}else if($fa1_A_SEX2_new=="2"){
	$fa1_A_SEX_new="ชาย";
}else{
	$fa1_A_SEX_new="ไม่ระบุ";
}
$fa1_A_ROOM_new=trim($res_fa1_new["A_ROOM"]);
$fa1_A_FLOOR_new=trim($res_fa1_new["A_FLOOR"]);
$fa1_A_BUILDING_new=trim($res_fa1_new["A_BUILDING"]);
$fa1_A_VILLAGE_new=trim($res_fa1_new["A_VILLAGE"]);

//ค้นหาชื่อประเทศ
$query_country_new=pg_query("select \"CountryName_THAI\" from \"Country_Code\" where \"CountryCode\" = '$fa1_country2_new'");
list($fa1_country_new)=pg_fetch_array($query_country_new);


$qry_fn_new=pg_query("select * from \"Fn\" where \"CusID\" ='$Cus_new' ");

$res_fn_new=pg_fetch_array($qry_fn_new);
$N_STATE_new=trim($res_fn_new["N_STATE"]);
$N_SAN_new=trim($res_fn_new["N_SAN"]);
$N_AGE_new=trim($res_fn_new["N_AGE"]);
$N_CARD_new=trim($res_fn_new["N_CARD"]);
$N_IDCARD_new=trim($res_fn_new["N_IDCARD"]);
$N_OT_DATE_new=trim($res_fn_new["N_OT_DATE"]);
$N_BY_new=trim($res_fn_new["N_BY"]);
$N_OCC_new=trim($res_fn_new["N_OCC"]);
$ext_addr_new=trim($res_fn_new["N_ContactAdd"]);
$N_CARDREF_new=trim($res_fn_new["N_CARDREF"]);

$edit_path = redirect($_SERVER['PHP_SELF'],'nw/manageCustomer');

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>

<title>อนุมัติจัดการรวมลูกค้าซ้ำ</title>

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
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">อนุมัติจัดการรวมลูกค้าซ้ำ<button id="btnclose" onClick="window.close();">ปิดหน้าต่าง</button></h1>
	</div>
	
	<table>
	<tr>
		<td>
			<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;"><b>แสดงข้อมูลลูกค้าที่จะถูกลบ</b> <br /><hr />
				<table width="785" border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td colspan="6" style="background-color:#FFFFCC;">ข้อมูลลูกค้า <?php echo $Cus_old; ?></td>
				</tr>
				<tr>
					<td>คำนำหน้าชื่อ (ไทย)</td>
					<td><input type="text" name="f_fri_name" value="<?php echo $fa1_firname; ?>" readonly="true" <?php if($fa1_firname==$fa1_firname_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
					<td colspan="3"><input type="text" name="f_fri_name_eng" value="<?php echo $fa1_firname_eng; ?>" readonly="true" <?php if($fa1_firname_eng==$fa1_firname_eng_new){ echo"style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td width="144">ชื่อ(ไทย)</td>
					<td width="227"><input type="text" name="f_name" value="<?php echo $fa1_name; ?>" readonly="true" <?php if($fa1_name==$fa1_name_new){ echo"style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">ชื่อ (อังกฤษ)</td>
					<td colspan="3"><input type="text" name="f_name_eng" value="<?php echo $fa1_name_eng; ?>" readonly="true" <?php if($fa1_name_eng==$fa1_name_eng_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>นามสกุล (ไทย)</td>
					<td><input type="text" name="f_surname" value="<?php echo $fa1_surname; ?>" readonly="true" <?php if($fa1_surname==$fa1_surname_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>นามสกุล (อังกฤษ)</td>
					<td colspan="3"><input type="text" name="f_surname_eng" value="<?php echo $fa1_surname_eng; ?>" readonly="true" <?php if($fa1_surname_eng==$fa1_surname_eng_new){ echo"style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?> size="15">
					 เพศ <input type="text" value="<?php echo $fa1_A_SEX;?>" readonly="true" <?php if($fa1_A_SEX==$fa1_A_SEX_new){ echo "style=\"background-color:#FFCCCC\"";}?> size="5">
					</td>
				</tr>
				<tr>
					<td>ชื่อเล่น</td>
					<td><input type="text" name="f_nickname" value="<?php echo $fa1_nickname; ?>" readonly="true" <?php if($fa1_nickname==$fa1_nickname_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?> /></td>
					<td width="90">วันเกิด</td>
					<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" value="<?php echo $fa1_brithday; ?>" readonly="true" <?php if($fa1_brithday==$fa1_brithday_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>  size="15"/>
					อายุ
					<input type="text" name="f_age" id="f_age" value="<?php echo $N_AGE; ?>" readonly="true" <?php if($N_AGE==$N_AGE_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>   size="5"/></td>
				</tr>
				
				<tr>
					<td width="144">สัญชาติ</td>
					<td width="227"><input type="text" name="f_san" value="<?php echo $N_SAN; ?>" readonly="true" <?php if($N_SAN==$N_SAN_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
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
						<input type="text" value="<?php echo $txtedu;?>" readonly="true" <?php if($fa1_education==$fa1_education_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>>
					</td>
				</tr>
				<tr>
					<td width="144">รายได้ต่อเดือนประมาณ</td>
					<td width="227"><input type="text" name="f_revenue" id="f_revenue" value="<?php echo $fa1_revenue; ?>" readonly="true" <?php if($fa1_revenue==$fa1_revenue_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
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
						<input type="text" value="<?php echo $txtstatus;?>" readonly="true" <?php if($fa1_status==$fa1_status_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>>
					</td>
				</tr>
				<tr>
					<td width="144">ชื่อ คู่สมรส</td>
					<td width="227"><input type="text" name="f_pair" value="<?php echo $fa1_pair; ?>" readonly="true" <?php if($fa1_pair==$fa1_pair_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">อาชีพ</td>
					<td colspan="3"><input type="text" name="f_occ" value="<?php echo $N_OCC; ?>" readonly="true" <?php if($N_OCC==$N_OCC_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td colspan="6"><hr></td>
				</tr>
				<tr>
					<td width="144">เลขที่บัตรประชาชน</td>			
					<td width="227"><input type="text" name="f_cardid" value="<?php echo $N_IDCARD; ?>" readonly="true" <?php if($N_IDCARD==$N_IDCARD_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">บัตรแสดงตัว</td>
					<td colspan="3"><input type="text" name="f_card" value="<?php echo $N_CARD; ?>" readonly="true" <?php if($N_CARD==$N_CARD_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					
				</tr>
				<tr>
					<td width="144">วันที่ออกบัตร</td>
					<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_OT_DATE; ?>" readonly="true" <?php if($N_OT_DATE==$N_OT_DATE_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">ออกให้โดย</td>
					<td colspan="3"><input type="text" name="f_card_by" value="<?php echo $N_BY; ?>" readonly="true" <?php if($N_BY==$N_BY_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<?php if($N_CARD != "บัตรประชาชน"){ ?>
				<tr>
					<td width="144">เลขที่บัตรอื่นๆ</td>
					<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_CARDREF; ?>" readonly="true" <?php if($N_CARDREF==$N_CARDREF_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					
				</tr>
				<?php } ?>
				<tr>
					<td colspan="6"><hr></td>
				</tr>
				<tr>
					<td>เลขที่</td>
					<td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>" readonly="true" <?php if($fa1_no==$fa1_no_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>หมู่ที่</td>
					<td colspan="3"><input type="text" name="f_subno" value="<?php echo $fa1_subno; ?>" readonly="true" <?php if($fa1_subno==$fa1_subno_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>ห้อง</td>
					<td><input type="text" name="A_ROOM" size="30" value="<?php echo $fa1_A_ROOM; ?>" readonly="true" <?php if($fa1_A_ROOM==$fa1_A_ROOM_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
					<td>ชั้น</td>
					<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo $fa1_A_FLOOR; ?>" readonly="true" <?php if($fa1_A_FLOOR==$fa1_A_FLOOR_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
				</tr>
				<tr>
					<td>อาคาร/สถานที่</td>
					<td><input type="text" name="A_BUILDING" size="30" value="<?php echo $fa1_A_BUILDING; ?>" readonly="true" <?php if($fa1_A_BUILDING==$fa1_A_BUILDING_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
					<td>หมู่บ้าน</td>
					<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo $fa1_A_VILLAGE; ?>" readonly="true" <?php if($fa1_A_VILLAGE==$fa1_A_VILLAGE_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
				</tr>
				<tr>
					<td>ซอย</td>
					<td><input type="text" name="f_soi" value="<?php echo $fa1_soi; ?>" readonly="true" <?php if($fa1_soi==$fa1_soi_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>ถนน</td>
					<td colspan="3"><input type="text" name="f_rd" value="<?php echo $fa1_rd; ?>" readonly="true" <?php if($fa1_rd==$fa1_rd_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>แขวง/ตำบล</td>
					<td><input type="text" name="f_tum" value="<?php echo $fa1_tum; ?>" readonly="true" <?php if($fa1_tum==$fa1_tum_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>เขต/อำเภอ</td>
					<td colspan="3"><input type="text" name="f_aum" value="<?php echo $fa1_aum; ?>" readonly="true" <?php if($fa1_aum==$fa1_aum_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>จังหวัด</td>
					<td><input type="text" value="<?php echo $fa1_pro; ?>" readonly="true" <?php if($fa1_pro==$fa1_pro_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
					<td>รหัสไปรษณีย์</td>
					<td colspan="3"><input type="text" name="f_post" value="<?php echo $fa1_post; ?>" maxlength="5" readonly="true" <?php if($fa1_post==$fa1_post_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>ประเทศ</td>
					<td><input type="text" value="<?php echo $fa1_country; ?>" readonly="true" <?php if($fa1_country==$fa1_country_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>>
					</td>
					<td>โทรศัพท์มือถือ</td>
					<td colspan="3"><input type="text" name="f_mobile" value="<?php echo $fa1_mobile; ?>" readonly="true" <?php if($fa1_mobile==$fa1_mobile_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>โทรศัพท์บ้าน</td>
					<td><input type="text" name="f_telephone" value="<?php echo $fa1_telephone; ?>" readonly="true" <?php if($fa1_telephone==$fa1_telephone_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>E-mail</td>
					<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo $fa1_email ?>" size="30" readonly="true" <?php if($fa1_email==$fa1_email_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				
				<tr>
					<td>ที่อยู่ใช้ติดต่อ</td>
					<td>
						<textarea name="f_ext" cols="50" rows="5" readonly="true" <?php if($ext_addr==$ext_addr_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>><?php echo $ext_addr; ?></textarea>
					</td>
					<td colspan="4" valign="top" rowspan="2">
					
					</td>
				</tr>
				</table>
			</div>
		</td>
		
		<!-------------------------------------  ข้อมูลที่จะใช้ ----------------------------------------------->
		
		<td>
			<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;"><b>แสดงข้อมูลลูกค้าที่จะแทนที่</b> <br /><hr />
				<table width="785" border="0" cellpadding="1" cellspacing="1">
				<tr>
					<td colspan="6" style="background-color:#FFFFCC;">ข้อมูลลูกค้า <?php echo $Cus_new; ?></td>
				</tr>
				<tr>
					<td>คำนำหน้าชื่อ (ไทย)</td>
					<td><input type="text" name="f_fri_name_new" value="<?php echo $fa1_firname_new; ?>" readonly="true" <?php if($fa1_firname==$fa1_firname_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
					<td colspan="3"><input type="text" name="f_fri_name_eng_new" value="<?php echo $fa1_firname_eng_new; ?>" readonly="true" <?php if($fa1_firname_eng==$fa1_firname_eng_new){ echo"style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td width="144">ชื่อ(ไทย)</td>
					<td width="227"><input type="text" name="f_name_new" value="<?php echo $fa1_name_new; ?>" readonly="true" <?php if($fa1_name==$fa1_name_new){ echo"style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">ชื่อ (อังกฤษ)</td>
					<td colspan="3"><input type="text" name="f_name_eng_new" value="<?php echo $fa1_name_eng_new; ?>" readonly="true" <?php if($fa1_name_eng==$fa1_name_eng_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>นามสกุล (ไทย)</td>
					<td><input type="text" name="f_surname_new" value="<?php echo $fa1_surname_new; ?>" readonly="true" <?php if($fa1_surname==$fa1_surname_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>นามสกุล (อังกฤษ)</td>
					<td colspan="3"><input type="text" name="f_surname_eng_new" value="<?php echo $fa1_surname_eng_new; ?>" readonly="true" <?php if($fa1_surname_eng==$fa1_surname_eng_new){ echo"style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?> size="15">
					 เพศ <input type="text" value="<?php echo $fa1_A_SEX_new;?>" readonly="true" <?php if($fa1_A_SEX==$fa1_A_SEX_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?> size="5">
					</td>
				</tr>
				<tr>
					<td>ชื่อเล่น</td>
					<td><input type="text" name="f_nickname_new" value="<?php echo $fa1_nickname_new; ?>" readonly="true" <?php if($fa1_nickname==$fa1_nickname_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?> /></td>
					<td width="90">วันเกิด</td>
					<td colspan="3"><input type="text" name="f_brithday_new" id="f_brithday_new" value="<?php echo $fa1_brithday_new; ?>" readonly="true" <?php if($fa1_brithday==$fa1_brithday_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>  size="15"/>
					อายุ
					<input type="text" name="f_age_new" id="f_age_new" value="<?php echo $N_AGE_new; ?>" readonly="true" <?php if($N_AGE==$N_AGE_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>   size="5"/></td>
				</tr>
				
				<tr>
					<td width="144">สัญชาติ</td>
					<td width="227"><input type="text" name="f_san_new" value="<?php echo $N_SAN_new; ?>" readonly="true" <?php if($N_SAN==$N_SAN_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">ระดับการศึกษา</td>
					<td colspan="3">
						<?php 
							if($fa1_education_new=="1"){
								$txtedu_new="ต่ำกว่ามัธยมศึกษาตอนต้น";
							}else if($fa1_education_new=="2"){
								$txtedu_new="มัธยมศึกษาตอนต้น";
							}else if($fa1_education_new=="3"){
								$txtedu_new="มัธยมศึกษาตอนปลาย";
							}else if($fa1_education_new=="4"){
								$txtedu_new="ปวช.";
							}else if($fa1_education_new=="5"){
								$txtedu_new="ปวส.";
							}else if($fa1_education_new=="6"){
								$txtedu_new="อนุปริญญา";
							}else if($fa1_education_new=="7"){
								$txtedu_new="ปริญญาตรี";
							}else if($fa1_education_new=="8"){
								$txtedu_new="ปริญญาโท";
							}else if($fa1_education_new=="9"){
								$txtedu_new="ปริญญาเอก";
							}
						?>
						<input type="text" value="<?php echo $txtedu_new;?>" readonly="true" <?php if($fa1_education==$fa1_education_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>>
					</td>
				</tr>
				<tr>
					<td width="144">รายได้ต่อเดือนประมาณ</td>
					<td width="227"><input type="text" name="f_revenue_new" id="f_revenue_new" value="<?php echo $fa1_revenue_new; ?>" readonly="true" <?php if($fa1_revenue==$fa1_revenue_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">สถานภาพ</td>
					<td colspan="3">
						<?php
						if($fa1_status_new=="0002"){
							$txtstatus_new="โสด";
						}else if($fa1_status_new=="0001"){
							$txtstatus_new="สมรส";
						}else if($fa1_status_new=="0005"){
							$txtstatus_new="สมรสไม่จดทะเบียน";
						}else if($fa1_status_new=="0004"){
							$txtstatus_new="หย่า";
						}else if($fa1_status_new=="0003"){
							$txtstatus_new="หม้าย";
						}else{
							$txtstatus_new="ไม่ระบุ";
						}
						?>
						<input type="text" value="<?php echo $txtstatus_new;?>" readonly="true" <?php if($fa1_status==$fa1_status_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>>
					</td>
				</tr>
				<tr>
					<td width="144">ชื่อ คู่สมรส</td>
					<td width="227"><input type="text" name="f_pair_new" value="<?php echo $fa1_pair_new; ?>" readonly="true" <?php if($fa1_pair==$fa1_pair_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">อาชีพ</td>
					<td colspan="3"><input type="text" name="f_occ_new" value="<?php echo $N_OCC_new; ?>" readonly="true" <?php if($N_OCC==$N_OCC_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td colspan="6"><hr></td>
				</tr>
				<tr>
					<td width="144">เลขที่บัตรประชาชน</td>			
					<td width="227"><input type="text" name="f_cardid_new" value="<?php echo $N_IDCARD_new; ?>" readonly="true" <?php if($N_IDCARD==$N_IDCARD_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">บัตรแสดงตัว</td>
					<td colspan="3"><input type="text" name="f_card_new" value="<?php echo $N_CARD_new; ?>" readonly="true" <?php if($N_CARD==$N_CARD_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					
				</tr>
				<tr>
					<td width="144">วันที่ออกบัตร</td>
					<td width="227"><input type="text" name="f_datecard_new" value="<?php echo $N_OT_DATE_new; ?>" readonly="true" <?php if($N_OT_DATE==$N_OT_DATE_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td width="90">ออกให้โดย</td>
					<td colspan="3"><input type="text" name="f_card_by_new" value="<?php echo $N_BY_new; ?>" readonly="true" <?php if($N_BY==$N_BY_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<?php if($N_CARD != "บัตรประชาชน"){ ?>
				<tr>
					<td width="144">เลขที่บัตรอื่นๆ</td>
					<td width="227"><input type="text" name="f_datecard_new" value="<?php echo $N_CARDREF_new; ?>" readonly="true" <?php if($N_CARDREF==$N_CARDREF_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					
				</tr>
				<?php } ?>
				<tr>
					<td colspan="6"><hr></td>
				</tr>
				<tr>
					<td>เลขที่</td>
					<td><input type="text" name="f_no_new" value="<?php echo $fa1_no_new; ?>" readonly="true" <?php if($fa1_no==$fa1_no_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>หมู่ที่</td>
					<td colspan="3"><input type="text" name="f_subno_new" value="<?php echo $fa1_subno_new; ?>" readonly="true" <?php if($fa1_subno==$fa1_subno_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>ห้อง</td>
					<td><input type="text" name="A_ROOM_new" size="30" value="<?php echo $fa1_A_ROOM_new; ?>" readonly="true" <?php if($fa1_A_ROOM==$fa1_A_ROOM_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
					<td>ชั้น</td>
					<td colspan="3"><input type="text" name="A_FLOOR_new" size="30" value="<?php echo $fa1_A_FLOOR_new; ?>" readonly="true" <?php if($fa1_A_FLOOR==$fa1_A_FLOOR_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
				</tr>
				<tr>
					<td>อาคาร/สถานที่</td>
					<td><input type="text" name="A_BUILDING_new" size="30" value="<?php echo $fa1_A_BUILDING_new; ?>" readonly="true" <?php if($fa1_A_BUILDING==$fa1_A_BUILDING_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
					<td>หมู่บ้าน</td>
					<td colspan="3"><input type="text" name="A_VILLAGE_new" size="30" value="<?php echo $fa1_A_VILLAGE_new; ?>" readonly="true" <?php if($fa1_A_VILLAGE==$fa1_A_VILLAGE_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
				</tr>
				<tr>
					<td>ซอย</td>
					<td><input type="text" name="f_soi" value="<?php echo $fa1_soi_new; ?>" readonly="true" <?php if($fa1_soi==$fa1_soi_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>ถนน</td>
					<td colspan="3"><input type="text" name="f_rd_new" value="<?php echo $fa1_rd_new; ?>" readonly="true" <?php if($fa1_rd==$fa1_rd_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>แขวง/ตำบล</td>
					<td><input type="text" name="f_tum_new" value="<?php echo $fa1_tum_new; ?>" readonly="true" <?php if($fa1_tum==$fa1_tum_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>เขต/อำเภอ</td>
					<td colspan="3"><input type="text" name="f_aum_new" value="<?php echo $fa1_aum_new; ?>" readonly="true" <?php if($fa1_aum==$fa1_aum_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>จังหวัด</td>
					<td><input type="text" value="<?php echo $fa1_pro_new; ?>" readonly="true" <?php if($fa1_pro==$fa1_pro_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>></td>
					<td>รหัสไปรษณีย์</td>
					<td colspan="3"><input type="text" name="f_post_new" value="<?php echo $fa1_post_new; ?>" maxlength="5" readonly="true" <?php if($fa1_post==$fa1_post_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>ประเทศ</td>
					<td><input type="text" value="<?php echo $fa1_country_new; ?>" readonly="true" <?php if($fa1_country==$fa1_country_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>>
					</td>
					<td>โทรศัพท์มือถือ</td>
					<td colspan="3"><input type="text" name="f_mobile_new" value="<?php echo $fa1_mobile_new; ?>" readonly="true" <?php if($fa1_mobile==$fa1_mobile_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				<tr>
					<td>โทรศัพท์บ้าน</td>
					<td><input type="text" name="f_telephone_new" value="<?php echo $fa1_telephone_new; ?>" readonly="true" <?php if($fa1_telephone==$fa1_telephone_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
					<td>E-mail</td>
					<td colspan="3"><input type="text" name="f_email_new" id="f_email_new" value="<?php echo $fa1_email_new ?>" size="30" readonly="true" <?php if($fa1_email==$fa1_email_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>/></td>
				</tr>
				
				<tr>
					<td>ที่อยู่ใช้ติดต่อ</td>
					<td>
						<textarea name="f_ext_new" cols="50" rows="5" readonly="true" <?php if($ext_addr==$ext_addr_new){ echo "style=\"background-color:#FFCCCC\"";}else{echo "style=\"background-color:#CCFFCC\"";}?>><?php echo $ext_addr_new; ?></textarea>
					</td>
					<td colspan="4" rowspan="2" valign="bottom">
						<div style="text-align:right;">
                        	<form action="<?php echo $edit_path."/frm_Edit.php"; ?>" target="submission" onsubmit="window.open('',this.target,'toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=800');return true;" method="post">
                            <input type="hidden" name="CusID" id="CusID" value="<?php echo $Cus_new; ?>" />
                            <input type="hidden" name="autoapp" id="autoapp" value="t" />
                            <input type="hidden" name="update_gather" id="update_gather" value="f" />
                            
                            <input type="submit" value="แก้ไขข้อมูล">
                            </form>
                        </div>
					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
	<center>
	<!--input type="button" value="อนุมัติ" onClick="parent.location.href='processGather.php?appv=1&autoID=<?php echo $autoID; ?>'">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="button" value="ไม่อนุมัติ" onClick="parent.location.href='processGather.php?appv=2&autoID=<?php echo $autoID; ?>'"-->
	<!--ส่งค่า แบบ POST-->
	<form method="post" action="processGather.php">
		<input type="hidden" name="autoID" id="autoID" value="<?php echo $autoID; ?>">
		<input name="appv" type="submit" value="อนุมัติ" />
		<input name="unappv" type="submit" value="ไม่อนุมัติ" />
	</form>
	</center>
</div>
</body>
</html>