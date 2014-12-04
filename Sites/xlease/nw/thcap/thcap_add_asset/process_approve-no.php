<?php ?>
<?php
	session_start();
	include("../../../config/config.php");
	include("../../function/checknull.php"); 
	echo " Process Approve No ";  print_r($_POST);  
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$autoapp = pg_escape_string($_POST["autoapp"]);
if($autoapp == 't'){
	$id_user = '000';
}else{
	$id_user = $_SESSION["av_iduser"];
}	
$cmd = pg_escape_string($_POST["cmd"]); //สถานะการอนุมัติ
$ascenID = pg_escape_string($_POST["ascenID"]); //รหัสการใส่รายละเอียดสินทรัพย์ 
$ascenID_Real = pg_escape_string($_POST["ascenID_Real"]);  echo "***".$ascenID_Real; // exit();
$note = checknull(pg_escape_string($_POST["note"])); //เหตุผลการปฎิเสธการอนุมัติ
$frompage = pg_escape_string($_POST["frompage"]); //$frompage="appvdetail" มาจาก เมนู "(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย" 
$status = 0;
  
pg_query("BEGIN");

 
	$Sql_Update = "	UPDATE 	\"thcap_asset_biz_detail_central\"
							SET  	\"statusapp\" = '2', 
									\"appID\" = '$id_user', 
									\"appDate\" = \"nowDateTime\"(),
									\"noteapp\" = $note
									
							WHERE 	\"ascenID\" = '$ascenID_Real'   and
									\"statusapp\" = '0'
				  ";	
	echo $Sql_Update;			  		
	$qry_up = pg_query($Sql_Update);
							
    												
	IF($qry_up){}else{ $status++; }
	
	// Update ค่าใน Col add_or_edit
	

if($status == 0)
{
	pg_query("COMMIT"); 
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย พร้อมอนุมัติโดยระบบ</font></h2></center>";
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
			echo "1";
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
			$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้');
					opener.location.reload(true);
					self.close();";
			$script.= '</script>';
			echo $script;
		}
		else{
			echo "1";
		}
	}	
}
?>
<?php
if($autoapp == 't'){
?>
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
<?php
}
?>
