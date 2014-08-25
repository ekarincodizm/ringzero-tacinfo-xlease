<?php
session_start();
include("../../config/config.php");

$get_groupid = $_SESSION["av_usergroup"];
$get_userid = $_SESSION["av_iduser"];
//$arr_idno = $_SESSION["arr_idno"];
$get_idno = pg_escape_string($_GET["idno"]);
//$get_cusid = $_GET["scusid"];

//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
$qrychk=pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\" = '$get_idno'");
if(pg_num_rows($qrychk)==0){
	echo "<div align=center><h2>กรุณาระบุเลขที่สัญญาให้ถูกต้อง</h2></div>";
	exit;
}
//---- mysql
$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลักจาก mysql
$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$get_idno' and \"CusState\"='0'");
$nummain=pg_num_rows($qry_namemain);
if($nummain > 0)
{
	$i=1;
	while($resnamemain=pg_fetch_array($qry_namemain))
	{
		$name1=trim($resnamemain["thcap_fullname"]);
		if($i > 1)
		{
			$name3 = $name3." , ";
		}
		$name3 = $name3.$name1;
		$i++;
	}
}

//ค้นหาชื่อผู้กู้ร่วมจาก mysql
$qry_name=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$get_idno' and \"CusState\" > 0");
$numco=pg_num_rows($qry_name);
if($numco > 0)
{
	$i=1;
	while($resco=pg_fetch_array($qry_name))
	{
		$name2=trim($resco["thcap_fullname"]);
		if($i > 1)
		{
			$namemic = $namemic." , ";
		}
		$namemic = $namemic.$name2;
		$i++;
	}
}
//---- End mysql


$search_top = $get_idno;
do{
    $qry_top=pg_query("select \"CusID\", \"IDNO\" from \"Fp\" WHERE \"P_TransferIDNO\"='$search_top'");
    $res_top=pg_fetch_array($qry_top);
    $CusID=$res_top["CusID"];
    $arr_idno[$res_top["IDNO"]]=$CusID;
    $search_top=$res_top["IDNO"];
}while(!empty($search_top));

$qry_top=pg_query("select \"CusID\", \"P_TransferIDNO\" from \"Fp\" WHERE \"IDNO\"='$get_idno'");
$res_top=pg_fetch_array($qry_top);
$CusID=$res_top["CusID"];
$P_TransferIDNO=$res_top["P_TransferIDNO"];
$arr_idno[$get_idno]=$CusID;

if(!empty($P_TransferIDNO)){
    do{
        $qry_fp2=pg_query("select A.\"CusID\", A.\"P_TransferIDNO\" from \"Fp\" A LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\" = B.\"CusID\" where A.\"IDNO\" ='$P_TransferIDNO'");
        $res_fp2=pg_fetch_array($qry_fp2);
        $CusID=$res_fp2["CusID"];
        $arr_idno[$P_TransferIDNO]=$CusID;
        $P_TransferIDNO=$res_fp2["P_TransferIDNO"];
    }while(!empty($P_TransferIDNO));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['session_company_name']; ?></title>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $("#tabs").tabs();
    $("#tabs").tabs('select', '<?php echo $_SESSION["ses_idno"]; ?>');
});

function chkSelectSave() // ตรวจสอบการเลือกรูปแบบการบันทึก
{
	if(document.getElementById("svaeType1").checked == true)
	{
		document.getElementById("selectCus").value = "";
		document.getElementById("selectCus").disabled = true;
	}
	else if(document.getElementById("svaeType2").checked == true)
	{
		document.getElementById("selectCus").disabled = false;
	}
}

function validate() // ตรวจสอบการคีย์ข้อมูล
{
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.getElementById("followdetail").value == "") {
		theMessage = theMessage + "\n ->  กรุณาระบุ รายละเอียด";
	}
	
	if (document.getElementById("svaeType2").checked == true && document.getElementById("selectCus").value == "") {
		theMessage = theMessage + "\n ->  โปรดเลือกลูกค้าที่ต้องการบันทึก";
	}
	
	// If no errors, submit the form
	if (theMessage == noErrors)
	{
		return true;
	}
	else
	{
		// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>

<style type="text/css">
.ui-tabs{
    font-family:tahoma;
    font-size:12px
}
</style>

<style type="text/css">
body {
    font-family:tahoma;
    color : #333333;
    font-size:12px;
}
.title_top{
    font-family: tahoma;
    font-size:19px;
    font-weight: bold;
    margin: 0;
    padding: 0 0 3px 0;
    text-align: right;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	color: #3A3A3A;
}
H1 {
    font-size: 18px;
}
.title {
    text-align: center;
}
.TextTitle{
    color: #006600;
    font-size: 11px;
    font-weight: bold;
}
</style>

</head>
<body>

	<div class="title_top">บันทึกการติดตาม</div>

	<?php
		if(empty($get_groupid) OR empty($get_userid)){
			echo "<div align=center>ผิดผลาด ไม่พบข้อมูล แผนกหรือผู้ใช้งาน</div>";
		exit;
		}
	?>

	<div id="tabs"> <!-- เริ่ม tabs -->
		<ul>
	<?php
		//สร้าง list รายการ โอนสิทธิ์
		foreach($arr_idno as $ii => $v){
			if(empty($ii)){
				continue;
			}
			echo "<li><a href=\"#tabs-$ii\">$ii</a></li>";
		}
	?>
		</ul>

	<?php
		foreach($arr_idno as $i => $v){
			if(empty($i)){
				continue;
		}
    
			$get_cusid = $v;
			$get_idno = $i;
    
			//กำหนดสี ให้กับข้อมูลล่าสุด
			if($_SESSION["ses_idno"] == $get_idno){
				$bgcolor = "#FFFFFF";
			}else{
				$bgcolor = "#FFFFFF";
			}
			//จบ กำหนดสี
	?>

		<div id="tabs-<?php echo $get_idno; ?>">
			<div style="background-color:<?php echo $bgcolor; ?>">


<!-- ===== เลือก ===== -->
				<div align="right" style="padding-top: 5px;">
					<form name="frm_fuc2" method="post" action="follow_up_cus.php?idno=<?php echo $get_idno; ?>">
						Group : 
							<SELECT NAME="group" onchange="document.frm_fuc2.submit()";>
							<OPTION VALUE="ALL">ทั้งหมด
						<?php
							$qry_fg=pg_query("select \"dep_id\", \"dep_name\" from \"department\" ORDER BY dep_id ASC");
							while($res_fg=pg_fetch_array($qry_fg)){
								if($_POST['group'] == $res_fg["dep_id"]){
						?>
									<OPTION VALUE="<?php echo $res_fg["dep_id"]; ?>" selected><?php echo $res_fg["dep_name"]; ?>
						<?php
								}else{
						?>
									<OPTION VALUE="<?php echo $res_fg["dep_id"]; ?>"><?php echo $res_fg["dep_name"]; ?>
						<?php
								}
							}	
						?>
							</SELECT>
						User : 
							<SELECT NAME="userid" onchange="document.frm_fuc2.submit()";>
							<OPTION VALUE="ALL">ทั้งหมด
						<?php
							if( isset($_POST['userid']) ){
								if( $_POST['group'] == 'ALL' ){
									$qry_fu=pg_query("select id_user,fullname from \"Vfuser\" ORDER BY id_user ASC");
								}else{
									$qry_fu=pg_query("select id_user,fullname from \"Vfuser\" WHERE user_group='$_POST[group]' ORDER BY id_user ASC");    
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
				</div>
<!-- ===== จบ เลือก ===== -->

				<div align="right" style="padding-top:5px; padding-bottom:5px;">
					<img src="icoPrint.png" border="0" width="17" height="14">&nbsp;<a href="follow_up_cus_print.php?idno=<?php echo $get_idno; ?>" target="_blank">พิมพ์ข้อมูลทั้งหมด</a>
				</div>

				<fieldset><legend><b>เพิ่มข้อมูล</b></legend>

					<div style="float:left">ชื่อผู้กู้หลัก : <?php echo $name3; ?></div>
					<div style="float:right">วันที่ปัจจุบัน : <?php echo date('d-m-Y'); ?></div>
					<div style="clear:both;">&nbsp;</div>
					<div style="float:left">ชื่อผู้กู้ร่วม : <?php echo $namemic; ?></div>
					<div style="float:right">เลขที่สัญญา : <?php echo $get_idno; ?></div>
					<div style="clear:both;">&nbsp;</div>

					<div style="padding-top:5px;">
						<form name="frm_fuc" method="post" action="save_follow_up_cus.php">
							
							
							
							<span class="TextTitle">รายละเอียด</span><br />
							<TEXTAREA NAME="followdetail" ID="followdetail" ROWS="6" COLS="85"></TEXTAREA>
							
							<br />
							<INPUT TYPE="radio" name="svaeType" id="svaeType1" VALUE="1" onChange="chkSelectSave();" checked> บันทึกข้อมูลเฉพาะในสัญญานี้
							<br/>
							<INPUT TYPE="radio" name="svaeType" id="svaeType2" VALUE="2" onChange="chkSelectSave();"> บันทึกข้อมูลเข้าข้อมูลลูกค้า
							&nbsp;&nbsp;
							<select name="selectCus" id="selectCus">
								<option value="">–โปรดเลือกลูกค้าที่ต้องการบันทึก–</option>
								<?php
								$qry_allCus = pg_query("select * from \"thcap_ContactCus\" where \"contractID\" = '$get_idno' order by \"CusState\", \"FullName\" ");
								while($res_allCus = pg_fetch_array($qry_allCus))
								{
									$cus_CusID = $res_allCus["CusID"]; // รหัสลูกค้า
									$cus_FullName = $res_allCus["FullName"]; // ชื่อเต็มลูกค้า ณ วันที่ทำสัญญา
									
									echo "<option value=\"$cus_CusID\">$cus_FullName</option>";
								}
								?>
							</select>
							
							<br/>
							<INPUT TYPE="submit" VALUE="  บันทึก  " onClick="return validate();">
							<INPUT TYPE="hidden" NAME="GroupID" VALUE="<?php echo "$get_groupid"; ?>">
							<INPUT TYPE="hidden" NAME="userid" VALUE="<?php echo "$get_userid"; ?>">
							<INPUT TYPE="hidden" NAME="u_idno" VALUE="<?php echo "$get_idno"; ?>">
						</form>
					</div>
				</fieldset>

				<fieldset><legend><b>ข้อมูลที่ได้เจรจา</b></legend>

					<div style="background-color: #ffffff; padding: 2px">
					<?php
						if(isset($_POST['group']) OR isset($_POST['userid']))
						{
							//if($_POST['group'] != "" && $_POST['userid'] != ""){
							if($_POST['group'] == "ALL" AND $_POST['userid'] == "ALL")
							{
								$qry_fuc=pg_query("select \"userid\", \"FollowDate\", \"FollowDetail\", NULL as \"CusCorpID\"
													from \"thcap_FollowUpContract\"
													WHERE (\"contractID\"='$get_idno')
												union
													select \"userid\", \"FollowDate\", \"FollowDetail\", \"CusCorpID\"
													from \"thcap_FollowUpCusCorp\"
													where \"CusCorpID\" in(select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$get_idno')
												ORDER BY \"FollowDate\" DESC");
							}
							elseif($_POST['group'] == "ALL" AND $_POST['userid'] != "ALL")
							{
								$qry_fuc=pg_query("select \"userid\", \"FollowDate\", \"FollowDetail\", NULL as \"CusCorpID\"
													from \"thcap_FollowUpContract\"
													WHERE (\"userid\"='$_POST[userid]') AND (\"contractID\"='$get_idno')
												union
													select \"userid\", \"FollowDate\", \"FollowDetail\", \"CusCorpID\"
													from \"thcap_FollowUpCusCorp\"
													where (\"userid\"='$_POST[userid]') AND \"CusCorpID\" in(select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$get_idno')
												ORDER BY \"FollowDate\" DESC");
							}
							elseif($_POST['group'] != "ALL" AND $_POST['userid'] == "ALL")
							{
								$qry_fuc=pg_query("select \"userid\", \"FollowDate\", \"FollowDetail\", NULL as \"CusCorpID\"
													from \"thcap_FollowUpContract\"
													WHERE (\"GroupID\"='$_POST[group]') AND (\"contractID\"='$get_idno')
												union
													select \"userid\", \"FollowDate\", \"FollowDetail\", \"CusCorpID\"
													from \"thcap_FollowUpCusCorp\"
													where (\"GroupID\"='$_POST[group]') AND \"CusCorpID\" in(select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$get_idno')
												ORDER BY \"FollowDate\" DESC");
							}
							else
							{
								$qry_fuc=pg_query("select \"userid\", \"FollowDate\", \"FollowDetail\", NULL as \"CusCorpID\"
													from \"thcap_FollowUpContract\"
													WHERE (\"userid\"='$_POST[userid]') AND (\"GroupID\"='$_POST[group]') AND (\"contractID\"='$get_idno')
												union
													select \"userid\", \"FollowDate\", \"FollowDetail\", \"CusCorpID\"
													from \"thcap_FollowUpCusCorp\"
													where (\"userid\"='$_POST[userid]') AND (\"GroupID\"='$_POST[group]')
													AND \"CusCorpID\" in(select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$get_idno')
												ORDER BY \"FollowDate\" DESC");
							}
						}
						else
						{
							$qry_fuc=pg_query("select \"userid\", \"FollowDate\", \"FollowDetail\", NULL as \"CusCorpID\"
												from \"thcap_FollowUpContract\"
												WHERE (\"contractID\"='$get_idno')
											union
													select \"userid\", \"FollowDate\", \"FollowDetail\", \"CusCorpID\"
													from \"thcap_FollowUpCusCorp\"
													where \"CusCorpID\" in(select \"CusID\" from \"thcap_ContactCus\" where \"contractID\" = '$get_idno')
											ORDER BY \"FollowDate\" DESC"); // Not WHERE !!!
						}

						$numr=pg_num_rows($qry_fuc);
						if($numr==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }
						while($res_fuc=pg_fetch_array($qry_fuc))
						{
							$CusCorpID = $res_fuc["CusCorpID"]; // รหัสลูกค้า (ทั้งบุคคลธรรมดา และนิติบุคคล)
							
							if($CusCorpID != "")
							{
								$colorH = "#FF99FF"; // สีหัวรายการ
								$colorD = "#FFCCFF"; // สีรายละเอียดรายการ
								
								// หาชื่อลูกค้า
								$qry_cus = pg_query("select \"full_name\" from \"VSearchCusCorp\" WHERE (\"CusID\" = '$CusCorpID')");
								$cusName = pg_fetch_result($qry_cus,0);
								
								$showCus = "(รหัสลูกค้า : $CusCorpID - $cusName)";
							}
							else
							{
								$colorH = "#C0C0C0"; // สีหัวรายการ
								$colorD = "#F0F0F0"; // สีรายละเอียดรายการ
								
								$showCus = "";
							}
							
							$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[userid]')");
							$res_fun=pg_fetch_array($qry_fun);
					?>
						<div style="background-color:<?php echo $colorH; ?>;">
							<div style="float:left; padding:2px">User : <b><?php echo $res_fun["fullname"]; ?></b> <?php echo $showCus; ?></div>
							<div style="float:right; padding:2px">วันที่เจรจา : <b><?php echo $res_fuc["FollowDate"]; ?></b></div>
							<div style="clear:both;"></div>
						</div>
						<div style="background-color:<?php echo $colorD; ?>; padding:2px"><?php echo $res_fuc["FollowDetail"]; ?></div>
						<div style="background-color: #FFFFFF; clear:both; height:10px"></div>
					<?php
						}
					?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php
		}
	?>
	</div>
</body>

<script>
chkSelectSave();
</script>

</html>