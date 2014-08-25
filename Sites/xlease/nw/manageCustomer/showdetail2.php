<?php
session_start();
include("../../config/config.php");	

$user_id = $_SESSION["av_iduser"];

$CusID=pg_escape_string($_GET["CusID"]);

$qry_max_edit = pg_query("select max(\"add_date\") from \"Customer_Temp\" where \"CusID\"='$CusID' and \"statusapp\"='1'");
//echo "select max(\"add_date\") from \"Customer_Temp\" where \"CusID\"='$CusID' and \"statusapp\"='1'";
$rs_lastest_edit = pg_fetch_array($qry_max_edit);
$lastest_edit = $rs_lastest_edit["max"];
if($lastest_edit=="")
{
	$lastest_edit = "--";
}

$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$CusID' ");

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

// หาสิทธิการใช้งานเมนู "แก้ไขข้อมูลลูกค้า"
$qry_claim = pg_query("select * from \"f_usermenu\" where \"id_user\" = '$user_id' and \"id_menu\" = 'P40' and \"status\" = true ");
$row_claim = pg_num_rows($qry_claim);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>

<!-- Instance End Editable -->
<style type="text/css">
/*
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

*/
</style>

<!-- Instance Begin Editable name="head" -->
<style type="text/css">
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

</style>
<script>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
};
</script>
<!-- Instance End Editable -->
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1> 
	</div>
	<div style="position:absolute; top:35px; right:35px;">
		<?php
		// ถ้ามีสิทธิแก้ไขข้อมูลลูกค้า
		if($row_claim > 0)
		{
		?>
			<input type="button" name="popUp" value="ขอแก้ไขข้อมูลลูกค้า" onclick="javascript:popU('frm_Edit.php?CusID=<?php echo $CusID; ?>&showDetailCus=yes','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');" style="cursor:pointer;"></input>
		<?php
		}
		?>
		<input type="button" name="popUp" value="ประวัติการแก้ไขข้อมูลลูกค้า" onclick="javascript:popU('popUp_history_EditDetail.php?hidden=1&CusID=<?php echo $CusID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');" style="cursor:pointer;"></input>
	</div>
	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto; position:relative;"><b>แสดงข้อมูลลูกค้า (ปัจจุบัน)</b> 
    	<div style="position:absolute; top:5px; right:20px; font-family:Tahoma, Geneva, sans-serif; font-size:14px; color:#ff0000; font-weight:bold;">
        	วันที่แก้ไขข้อมูลครั้งล่าสุด : 
        	<?php
				echo $lastest_edit;
			?>
        </div>
    	<br /><hr />
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
			<td><input type="text" name="f_fri_name" value="<?php echo $fa1_firname; ?>" readonly="true"/></td>
			<td width="150">คำนำหน้าชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_fri_name_eng" value="<?php echo $fa1_firname_eng; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td width="144">ชื่อ(ไทย)</td>
			<td width="227"><input type="text" name="f_name" value="<?php echo $fa1_name; ?>" readonly="true"/></td>
			<td width="90">ชื่อ (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_name_eng" value="<?php echo $fa1_name_eng; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td>นามสกุล (ไทย)</td>
			<td><input type="text" name="f_surname" value="<?php echo $fa1_surname; ?>" readonly="true"/></td>
			<td>นามสกุล (อังกฤษ)</td>
			<td colspan="3"><input type="text" name="f_surname_eng" value="<?php echo $fa1_surname_eng; ?>" readonly="true" size="15">
			 เพศ <input type="text" value="<?php echo $fa1_A_SEX;?>" readonly="true" size="5"></td>
		</tr>
		
		<tr>
			<td>ชื่อเล่น</td>
			<td><input type="text" name="f_nickname" value="<?php echo $fa1_nickname; ?>" readonly="true"  /></td>
			<td width="90">วันเกิด</td>
			<td colspan="3"><input type="text" name="f_brithday" id="f_brithday" value="<?php echo $fa1_brithday; ?>" readonly="true"  size="15"/>
			อายุ
			<input type="text" name="f_age" id="f_age" value="<?php echo $N_AGE; ?>" readonly="true"   size="5"/></td>
		</tr>
		
		<tr>
			<td width="144">สัญชาติ</td>
			<td width="227"><input type="text" name="f_san" value="<?php echo $N_SAN; ?>" readonly="true"/></td>
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
				<input type="text" value="<?php echo $txtedu;?>" readonly="true">
			</td>
		</tr>
		<tr>
			<td width="144">รายได้ต่อเดือนประมาณ</td>
			<td width="227"><input type="text" name="f_revenue" id="f_revenue" value="<?php echo $fa1_revenue; ?>" readonly="true"/></td>
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
				<input type="text" value="<?php echo $txtstatus;?>" readonly="true">
			</td>
		</tr>
		<tr>
			<td width="144">ชื่อ คู่สมรส</td>
			<td width="227"><input type="text" name="f_pair" value="<?php echo $fa1_pair; ?>" readonly="true"/></td>
			<td width="90">อาชีพ</td>
			<td colspan="3"><input type="text" name="f_occ" value="<?php echo $N_OCC; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td width="144">เลขที่บัตรประชาชน</td>
			<td width="227"><input type="text" name="f_cardid" value="<?php echo $N_IDCARD; ?>" readonly="true"/></td>
			<td width="90">บัตรแสดงตัว</td>
			<td colspan="3"><input type="text" name="f_card" value="<?php echo $N_CARD; ?>" readonly="true"/></td>
			
		</tr>
		<tr>
			<td width="144">วันที่ออกบัตร</td>
			<td width="227"><input type="text" name="f_datecard" value="<?php echo $N_OT_DATE; ?>" readonly="true"/></td>
			<td width="90">ออกให้โดย</td>
			<td colspan="3"><input type="text" name="f_card_by" value="<?php echo $N_BY; ?>" readonly="true"/></td>
		</tr>
	<?php if($N_CARD != "บัตรประชาชน"){?>	
		<tr>
			<td width="144">เลขที่บัตรอื่นๆ</td>
			<td width="227"><input type="text" name="f_cardref" value="<?php echo $N_CARDREF; ?>" readonly="true"/></td>
		</tr>
	<?php } ?>	
		<tr>
			<td colspan="6"><hr></td>
		</tr>
		<tr>
			<td>เลขที่</td>
			<td><input type="text" name="f_no" value="<?php echo $fa1_no; ?>" readonly="true"/></td>
			<td>หมู่ที่</td>
			<td colspan="3"><input type="text" name="f_subno" value="<?php echo $fa1_subno; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td>ห้อง</td>
			<td><input type="text" name="A_ROOM" size="30" value="<?php echo $fa1_A_ROOM; ?>" readonly="true"></td>
			<td>ชั้น</td>
			<td colspan="3"><input type="text" name="A_FLOOR" size="30" value="<?php echo $fa1_A_FLOOR; ?>" readonly="true"></td>
		</tr>
		<tr>
			<td>อาคาร/สถานที่</td>
			<td><input type="text" name="A_BUILDING" size="30" value="<?php echo $fa1_A_BUILDING; ?>" readonly="true" ></td>
			<td>หมู่บ้าน</td>
			<td colspan="3"><input type="text" name="A_VILLAGE" size="30" value="<?php echo $fa1_A_VILLAGE; ?>" readonly="true"></td>
		</tr>
		<tr>
			<td>ซอย</td>
			<td><input type="text" name="f_soi" value="<?php echo $fa1_soi; ?>" readonly="true"/></td>
			<td>ถนน</td>
			<td colspan="3"><input type="text" name="f_rd" value="<?php echo $fa1_rd; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td>แขวง/ตำบล</td>
			<td><input type="text" name="f_tum" value="<?php echo $fa1_tum; ?>" readonly="true"/></td>
			<td>เขต/อำเภอ</td>
			<td colspan="3"><input type="text" name="f_aum" value="<?php echo $fa1_aum; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td>จังหวัด</td>
			<td><input type="text" value="<?php echo $fa1_pro; ?>" readonly="true"></td>
			<td>รหัสไปรษณีย์</td>
			<td colspan="3"><input type="text" name="f_post" value="<?php echo $fa1_post; ?>" maxlength="5" readonly="true"/></td>
		</tr>
		<tr>
			<td>ประเทศ</td>
			<td><input type="text" value="<?php echo $fa1_country; ?>" readonly="true" />
			</td>
			<td>โทรศัพท์มือถือ</td>
			<td colspan="3"><input type="text" name="f_mobile" value="<?php echo $fa1_mobile; ?>" readonly="true"/></td>
		</tr>
		<tr>
			<td>โทรศัพท์บ้าน</td>
			<td><input type="text" name="f_telephone" value="<?php echo $fa1_telephone; ?>" readonly="true"/></td>
			<td>E-mail</td>
			<td colspan="3"><input type="text" name="f_email" id="f_email" value="<?php echo $fa1_email ?>" size="30" readonly="true"/></td>
		</tr>
		<tr>
			<td valign="top">ที่อยู่ใช้ติดต่อ</td>
			<td>
				<textarea name="f_ext" cols="50" rows="5" readonly="true"><?php echo $ext_addr; ?></textarea>
			</td>
			<td colspan="4" valign="top" rowspan="2">
			
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="4">&nbsp;</td>
		</tr> 
		<tr>
			<td colspan="7" align="center">
				<input type="button" value="Close" onclick="window.close();"/>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td colspan="4">&nbsp;</td>
		</tr>
		</table>
	</div>
</div>
</body>
</html>
