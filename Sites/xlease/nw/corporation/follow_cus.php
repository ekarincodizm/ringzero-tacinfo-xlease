<?php
session_start();
include("../../config/config.php");

$get_groupid = $_SESSION["av_usergroup"];
$get_userid = $_SESSION["av_iduser"];
$checkuser = pg_escape_string($_POST['checkuser']);

$corpIDsh_post= pg_escape_string($_POST['corpIDsh']);
$corpID_get = pg_escape_string($_GET['corpID']);
$corpID1 = trim($corpIDsh_post);
$corpID2 = trim($corpID_get);

if($corpID1 != ""){
$corpID = $corpID1;
}else if($corpID2 != ""){
$corpID = $corpID2;
}
$sqlsel2 = pg_query("SELECT \"corp_regis\",\"corpName_THA\" FROM th_corp where \"corpID\"::character varying = '$corpID' ");
$re2 = pg_fetch_array($sqlsel2);
$corp_regis= $re2['corp_regis'];
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
$( document ).ready(function() {
	$("#check0").click(function(){
		if(document.getElementById("check0").checked==true){
		if(document.getElementById("firstload").value=="1"){
			$("#follow_thcap").load("follow_cus_thcap.php?corpid="+<?php echo $corpID;?>);
			$("#firstload").val("2");
		} else {
			$("#follow_thcap").show();
		}
	} else {
		$("#follow_thcap").hide();
	}
	});
	
	//Xlead
	$("#checkX0").click(function(){
		if(document.getElementById("checkX0").checked==true){
			if(document.getElementById("firstloadX").value=="1"){
				$("#follow_xlead").load("follow_cus_xlead.php?corpid="+<?php echo $corpID;?>);
				$("#firstloadX").val("2");
			} else {
				$("#follow_xlead").show();
			}
		} else {
			$("#follow_xlead").hide();
		}
	});
});
$(function(){
		$("#tabs").tabs();
		$("#tabs").tabs('select', '<?php $corp_regis; ?>');
});
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
	if(empty($corp_regis)){
		echo "<div align=center>ผิดผลาด ไม่พบข้อมูล แผนกหรือผู้ใช้งาน</div>";
    exit;
	}
	?>

	<div id="tabs"> <!-- เริ่ม tabs -->
		<ul>
		<?php
		//สร้าง list รายการ โอนสิทธิ์
			echo "<li><a href=\"#\">$corp_regis</a></li>";
			$bgcolor = "#FFFFFF";
		?>
		</ul>
	<?php
		$sqlsel3 = pg_query("SELECT \"CusID\" FROM th_corp_share where \"corpID\"::character varying = '$corpID' ");
		$o=0;
	?>
	
		<div id="tabs-<?php echo $corp_regis; ?>">
			<div style="background-color:<?php echo $bgcolor; ?>">
				<div align="right" style="padding-top:5px; padding-bottom:5px;">
					<form name="sreach" method="POST" action="follow_cus.php">
						<select name="checkuser">
						<OPTION VALUE="" selected>--- เลือกพนักงาน ---</option>
						<?php $qry_fu=pg_query("select id_user,fullname from \"Vfuser\" ORDER BY id_user ASC"); 
							while($res_fu=pg_fetch_array($qry_fu)){
						?>		 		 
							<OPTION VALUE="<?php echo $res_fu["id_user"]; ?>"><?php echo $res_fu["fullname"]; ?></option>
						<?php		 
							}
						?>
						</select>
						<input type="hidden" name="corpIDsh" value="<?php echo $corpID ?>">
						<input type="submit" value="ค้นหา" >
					</form>
				</div>
				
				<div align="right" style="padding-top:5px; padding-bottom:5px;">
					<img src="icoPrint.png" border="0" width="17" height="14">&nbsp;<a href="follow_cus_print.php?corpID=<?php echo $corpID; ?>" target="_blank">พิมพ์ข้อมูลทั้งหมด</a>
				</div>
				
				<fieldset><legend><b>เพิ่มข้อมูล</b></legend>

					<div style="float:left">ชื่อลูกค้านิติบุคคล : <?php echo $re2['corpName_THA']; ?></div>
					<div style="float:right">วันที่ปัจจุบัน : <?php echo date('d-m-Y'); ?></div></br>

					<?php while($re3 = pg_fetch_array($sqlsel3)){ 
							$CusID = $re3['CusID'];
							$sqlsel4 = pg_query("SELECT  \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\" FROM \"Fa1\" where \"CusID\" = '$CusID'");
							$re4 = pg_fetch_array($sqlsel4);
							$o++;
					?>
					<div style="clear:both;">&nbsp;</div>
					<div style="float:left">ชื่อผู้ถือหุ้น <?php echo $o; ?>: <?php echo $re4['A_FIRNAME']." ".$re4['A_NAME']." ".$re4['A_SIRNAME']; ?></div>
					<?php } ?>

					<div style="float:right">เลขทะเบียนนิติบุคคล : <font size="2px"><b><?php echo $corp_regis; ?></b></font></div>
					<div style="clear:both;">&nbsp;</div>

					<div style="padding-top:5px;">
						<form name="frm_fuc" method="post" action="save_follow_cus.php">
							<span class="TextTitle">รายละเอียด</span><br />
							<TEXTAREA NAME="followdetail" ROWS="6" COLS="85"></TEXTAREA><br />
							<INPUT TYPE="submit" VALUE="  บันทึก  ">
							<INPUT TYPE="hidden" NAME="corpID" VALUE="<?php echo "$corpID"; ?>">
						</form>
					</div>
				</fieldset>

				<fieldset><legend><b>ข้อมูลที่ได้เจรจา</b></legend>
					<div style="background-color: #ffffff; padding: 2px">
					<?php
						if($checkuser == ""){
							$qry_fuc = pg_query("SELECT id_user, fol_detail, fol_date  FROM th_corp_follow_cus where \"corpID\" = '$corpID'");
						}else{
							$qry_fuc = pg_query("SELECT id_user, fol_detail, fol_date  FROM th_corp_follow_cus where \"corpID\" = '$corpID' and \"id_user\" = '$checkuser'");
						}
						$numr=@pg_num_rows($qry_fuc);
						if($numr==0){ echo "<div align=center>- ไม่พบข้อมูล -</div>"; }
						while($res_fuc=@pg_fetch_array($qry_fuc)){
							$qry_fun=pg_query("select fullname from \"Vfuser\" WHERE (\"id_user\"='$res_fuc[id_user]')");
							$res_fun=pg_fetch_array($qry_fun);
					?>
						<div style="background-color: #C0C0C0">
							<div style="float:left; padding:2px">User : <b><?php echo $res_fun["fullname"]; ?></b></div>
							<div style="float:right; padding:2px">วันที่เจรจา : <b><?php echo $res_fuc["fol_date"]; ?></b></div>
							<div style="clear:both;"></div>
						</div>
						<div style="background-color: #F0F0F0; padding:2px"><?php echo $res_fuc["fol_detail"]; ?></div>
						<div style="background-color: #FFFFFF; clear:both; height:10px"></div>
					<?php
						}
					?>
					</div>
				</fieldset>
				
				<div style="margin-bottom:10px;margin-top:10px;"><font size="2px"><b>บันทึกการติดตามของสัญญาที่เกี่ยวข้อง </b></font>
				<?php 
					//หาเลขที่สัญญาจาก CorpID
					$qry_contractID = pg_query("select \"contractID\" from \"thcap_ContactCus\" where \"CusID\"='$corpID'");
					$countlist = 0;
					while($res_idno = pg_fetch_array($qry_contractID )){
						
						$get_contractID = $res_idno["contractID"];
						
						if($checkuser != ""){
							$qry_fuc=pg_query("select auto_id from \"thcap_FollowUpContract\" WHERE (\"userid\"='$checkuser') AND (\"contractID\"='$get_contractID')ORDER BY auto_id DESC");
						}else{
							$qry_fuc=pg_query("select auto_id from \"thcap_FollowUpContract\" WHERE (\"contractID\"='$get_contractID') ORDER BY auto_id DESC"); // Not WHERE !!!
						}
						$num_thcap = pg_num_rows($qry_fuc);
						$countlist = $countlist+$num_thcap;
					}
				?>
				<fieldset style="background-color:#FFCC33;"><legend><input type="checkbox" id="check0"><b>(THCAP)ตารางแสดงผ่อนชำระ <font color="red"><?php echo "( ".$countlist." รายการ )";?></font></b></legend>
					<input type="hidden" id="firstload" value="1">
					<input type="hidden" id="corpid" value="<?php echo $corpID; ?>">
					<div id="follow_thcap"></div>
				</fieldset>
				<?php 
					//หาเลขที่สัญญาจาก CorpID
					$qry_idno = pg_query("select \"IDNO\" from \"VContact\" where \"CusID\"='$corpID'");
					$countlist_xlead = 0;
					while($res_idno = pg_fetch_array($qry_idno)){
						
						$get_idno = $res_idno["IDNO"];
						
						if($checkuser != ""){
							$qry_fuc_x=pg_query("select auto_id from \"FollowUpCus\" WHERE (\"userid\"='$checkuser') AND (\"IDNO\"='$get_idno') AND (\"CusID\"='$corpID'') ORDER BY auto_id DESC");
						}else{
							$qry_fuc_x=pg_query("select auto_id from \"FollowUpCus\" WHERE (\"IDNO\"='$get_idno') AND (\"CusID\"='$corpID') ORDER BY auto_id DESC"); // Not WHERE !!!
						}
						$num_xlead = pg_num_rows($qry_fuc_x);
						$countlist_xlead = $countlist_xlead+$num_xlead;
					}
				?>
				<fieldset style="background-color:#FF9900;"><legend><input type="checkbox" id="checkX0"><b>ตารางแสดงผ่อนชำระ <font color="red"><?php echo "( ".$countlist_xlead." รายการ )";?></font></b></legend>
					<input type="hidden" id="firstloadX" value="1">
					<input type="hidden" id="corpid" value="<?php echo $corpID; ?>">
					<div id="follow_xlead"></div>
				</fieldset>
				
			</div>
		</div>
	</div>
</body>
</html>