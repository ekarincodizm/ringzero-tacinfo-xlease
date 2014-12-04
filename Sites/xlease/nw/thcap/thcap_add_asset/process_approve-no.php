<?php ?>
<?php
	session_start();
	include("../../../config/config.php");
	include("../../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
<?php
$autoapp = pg_escape_string($_POST["autoapp"]);
if($autoapp == 't'){
	$id_user = '000';
}else{
	$id_user = $_SESSION["av_iduser"];
}	
$cmd = pg_escape_string($_POST["cmd"]); //สถานะการอนุมัติ
$ascenID = pg_escape_string($_POST["ascenID"]);
$assetDetailID = pg_escape_string($_POST["assetDetailID"]);
$note = checknull(pg_escape_string($_POST["note"])); //เหตุผลการปฎิเสธการอนุมัติ
$frompage = pg_escape_string($_POST["frompage"]); //$frompage="appvdetail" มาจาก เมนู "(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย" 
  
pg_query("BEGIN");
$status = 0;
$Err_Msg = "";
 
// ตรวจสอบก่อนว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_chk = pg_query("select \"statusapp\" from \"thcap_asset_biz_detail_central\" where \"ascenID\" = '$ascenID' ");
$chk_statusapp_old = pg_fetch_result($qry_chk,0);

if($chk_statusapp_old == "1")
{
	$status++;
	$Err_Msg .= " มีการอนุมัติไปก่อนหน้านี้แล้ว";
}
elseif($chk_statusapp_old == "2")
{
	$status++;
	$Err_Msg .= " มีการปฎิเสธไปก่อนหน้านี้แล้ว";
}
else
{
	$Sql_Update = "	UPDATE 	\"thcap_asset_biz_detail_central\"
							SET  	\"statusapp\" = '2', 
									\"appID\" = '$id_user', 
									\"appDate\" = \"nowDateTime\"(),
									\"noteapp\" = $note
									
							WHERE 	\"ascenID\" = '$ascenID' AND
									\"statusapp\" = '0'
				  ";		  		
	$qry_up = pg_query($Sql_Update);
	IF($qry_up){}else{ $status++; }
}

if($status == 0)
{
	pg_query("COMMIT"); 
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อยแล้ว พร้อมอนุมัติโดยระบบ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"RefreshMe();\"></center>";
	}else{
		if($frompage =="appvdetail"){
			$script= '<script language=javascript>';
			$script.= " alert('บันทึกเหตุผลการไม่อนุมัติเรียบร้อยแล้ว');
					opener.location.reload(true);
					self.close();";
			$script.= '</script>';
			echo $script;
		}
		else{
			echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อยแล้ว</font></h2></center>";
			echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"RefreshMe();\"></center>";
		}
	}	
	
}
else
{
	pg_query("ROLLBACK");
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">ไม่สามารถอนุมัติข้อมูล โดยอัตโนมัติได้ จำเป็นต้องอนุมัติด้วยตัวบุคคล</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}else{
		if($frompage =="appvdetail"){
			$script= '<script language=javascript>';
			$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้ $Err_Msg');
					opener.location.reload(true);
					self.close();";
			$script.= '</script>';
			echo $script;
		}
		else{
			echo "<center><h2><font color=\"#0000FF\">ไม่สามารถบันทึกข้อมูลได้ $Err_Msg</font></h2></center>";
			echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
		}
	}	
}
?>