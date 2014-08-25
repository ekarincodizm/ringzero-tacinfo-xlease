<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");

$id_user=$_SESSION["av_iduser"];
//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

$id_user = $_SESSION["av_iduser"];
$hdassetDetailID = $_POST["hdassetDetailID"];
$bodyno = trim($_POST["bodyno"]);
$sopeg = $_POST["sopeg"];
$cceg = $_POST["cceg"];
$yearregis = $_POST["yearregis"];
$regis = checknull($_POST["regis"]);
$dateregis = checknull($_POST["dateregis"]);
$status = 0;
$appvauto = $_POST["appvauto"];
$Qry_bodyno = pg_query("select motorcycle_no from thcap_asset_biz_detail_10 where motorcycle_no = '$bodyno'");
$num_row = pg_num_rows($Qry_bodyno);
	if($num_row>0){
		echo "<center><h2><font color=\"#0000FF\">ไม่สามารถบันทึกข้อมูลได้ เนื่องจากมีเลขที่ตัวถัง $bodyno ในระบบแล้ว</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	} else {
		pg_query("BEGIN");
		//หากมีเลเวลน้อยกว่าหรือเท่ากับ 1 สามารถแก้ไขรหัสสินค้าได้
		IF($emplevel <= 1){
			$productcode_new = checknull($_POST["newproductcode"]);
			$qry_up = pg_query("	UPDATE thcap_asset_biz_detail
								SET \"productCode\" = $productcode_new
								WHERE \"assetDetailID\" = '$hdassetDetailID';
						  ");
			IF($qry_up){}else{ $status++ ;}					  
		}
		//ดูการแก้ไขว่าเป้นครั้งที่เท่าไหร่
		$qry_sel1 = pg_query("SELECT MAX(\"add_or_edit\") FROM \"thcap_asset_biz_detail_central\" where \"assetDetailID\" = '$hdassetDetailID'");
		list($maxedit) = pg_fetch_array($qry_sel1);
		if($maxedit == ""){
			$maxedit = '0';
		}else{
			$maxedit++;
		}

		//เก็บรายละเอียดผู้ทำรายการ
		$qry_in1 = pg_query("INSERT INTO thcap_asset_biz_detail_central( 
																\"assetDetailID\", 
																\"doerID\", 
																\"doerDate\", 
																statusapp,
																add_or_edit
																)
														VALUES (
																'$hdassetDetailID',
																'$id_user',
																LOCALTIMESTAMP(0),
																'0',
																'$maxedit' 
																);
						");
		IF($qry_in1){}else{ $status++ ;}	
	
		//ดึงรหัสการทำรายการมาอ้างอิง
		$qry_sel = pg_query("SELECT MAX(\"ascenID\") FROM \"thcap_asset_biz_detail_central\"");
		list($ascenID) = pg_fetch_array($qry_sel);
												
		//เก็บรายละเอียดสินค้า
		$qry_in2 = pg_query("INSERT INTO thcap_asset_biz_detail_10_temp(
																	\"ascenID\",
																	motorcycle_no, 
																	\"Pump_num\", 
																	\"EngineCC\", 
																	year_regis, 
																	regiser_no, 
																	register_date
																	)
															VALUES (
																	'$ascenID',
																	'$bodyno',
																	'$sopeg',
																	'$cceg',
																	'$yearregis',
																	$regis,
																	$dateregis	
																  )");
		IF($qry_in2){}else{ $status++ ;}



	if($status == 0)
	{
		pg_query("COMMIT");	
		IF($appvauto == 't'){
	?>
			<form name="frm" action="../thcap_add_asset/process_approve.php" method="POST">
				<input type="hidden" name="cmd" value="app">
				<input type="hidden" name="ascenID" value="<?php echo $ascenID; ?>">
				<input type="hidden" name="autoapp" value="t">
			</form>

<?php
	
			echo "<script type='text/javascript'>frm.submit();</script>";
		}else{
			echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย รอการอนุมัติ</font></h2></center>";
			echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"javascript:RefreshMe();\"></center>";
		}	
	}else{
		pg_query("ROLLBACK");
		echo "<center><h2><font color=\"#0000FF\">ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่ในภายหลัง</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"window.close();\"></center>";
	}
}

?>
<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>