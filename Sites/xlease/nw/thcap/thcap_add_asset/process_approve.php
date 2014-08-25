<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$autoapp = $_POST["autoapp"];
if($autoapp == 't'){
	$id_user = '000';
}else{
	$id_user = $_SESSION["av_iduser"];
}	
$cmd = $_POST["cmd"]; //สถานะการอนุมัติ
$ascenID = $_POST["ascenID"]; //รหัสการใส่รายละเอียดสินทรัพย์
$note = checknull($_POST["note"]); //เหตุผลการปฎิเสธการอนุมัติ
$frompage = $_POST["frompage"]; //$frompage="appvdetail" มาจาก เมนู "(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย" 
$status = 0;

pg_query("BEGIN");

if($cmd != "app") // ถ้ามาจากหน้าที่ fix ค่าอยู่แล้ว ไม่ต้องทำอะไร ถ้ามาจากหน้าที่ไม่ได้ fix ค่า ค่อยทำ
{
	if(isset($_POST["appv"])){
		$cmd="app";//อนุมัติ
	}else{
		$cmd="not";//ไม่อนุมัติ
	}
}


IF($cmd == 'app'){ //หากอนุมัติ

	$qry_astype = pg_query("
								select d2.\"astypeID\" from \"thcap_asset_biz_detail_central\" d1
								left join \"thcap_asset_biz_detail\" d2 on d1.\"assetDetailID\" = d2.\"assetDetailID\" 
								WHERE d1.\"ascenID\" = '$ascenID'
						 ");
	list($astypeapp) = pg_fetch_array($qry_astype);


	IF($astypeapp == '10'){ //หาก รหัสสินทรัพย์เป็น 10 = รถจักรยานยนต์
		$qry_sel = pg_query("	select * from \"thcap_asset_biz_detail_10_temp\" d1
								left join \"thcap_asset_biz_detail_central\" d2 on d1.\"ascenID\" = d2.\"ascenID\" 
								WHERE d2.\"ascenID\" = '$ascenID'
						");
		$resultdetail = pg_fetch_array($qry_sel);
		
		$assetDetailID = $resultdetail["assetDetailID"];
		$bodyno = $resultdetail["motorcycle_no"];
		$sopeg = $resultdetail["Pump_num"];
		$cceg = $resultdetail["EngineCC"];
		$yearregis = $resultdetail["year_regis"];
		$regis = checknull($resultdetail["regiser_no"]);
		$dateregis = checknull($resultdetail["register_date"]);
		$add_or_edit = $resultdetail["add_or_edit"];
		$car_type = $resultdetail["car_type"]; //ชนิดรถ
		$car_mileage = $resultdetail["car_mileage"]; //ระยะทางไมล์
		$car_color = $resultdetail["car_color"]; //สีรถ
		IF($add_or_edit == '0'){ //หากเป็น 0 แสดงว่าเป้นการเพิ่มข้อมูลใหม่
			$qry_in = pg_query("	INSERT INTO thcap_asset_biz_detail_10(
										\"assetDetailID\", motorcycle_no, \"Pump_num\", \"EngineCC\", 
										year_regis, regiser_no, register_date,car_type,car_mileage,car_color)
									VALUES ('$assetDetailID', '$bodyno', '$sopeg', '$cceg', 
										'$yearregis', $regis, $dateregis,'$car_type','$car_mileage','$car_color');
								");
			IF($qry_in){}else{ $status++; }
		}else if($add_or_edit > '0'){ //หากมากกว่า 0 แสดงว่าเป็นการแก้ไขข้อมูล
			
			$qry_seldata = pg_query("select * from \"thcap_asset_biz_detail_10\" WHERE \"assetDetailID\" = '$assetDetailID' ");
			$rowdata = pg_num_rows($qry_seldata);
			IF($rowdata == 0){
				$qry_in = pg_query("	INSERT INTO thcap_asset_biz_detail_10(
										\"assetDetailID\", motorcycle_no, \"Pump_num\", \"EngineCC\", 
										year_regis, regiser_no, register_date,car_type,car_mileage,car_color)
									VALUES ('$assetDetailID', '$bodyno', '$sopeg', '$cceg', 
										'$yearregis', $regis, $dateregis,'$car_type','$car_mileage','$car_color');
								");
				IF($qry_in){}else{ $status++; }					
			}else{
				$qry_up = pg_query("	UPDATE thcap_asset_biz_detail_10
									SET 	motorcycle_no = '$bodyno',
											\"Pump_num\" = '$sopeg',
											\"EngineCC\" = '$cceg',
											year_regis = '$yearregis',
											regiser_no = $regis,
											register_date = $dateregis,
											car_type='$car_type',
											car_mileage='$car_mileage',
											car_color='$car_color'
											
									WHERE \"assetDetailID\" = '$assetDetailID'
								");
				IF($qry_up){}else{ $status++; }					
			}					
							

		}else{ //หากเป็นค่า ติดลบแสดงว่าข้อมูลผิดแล้ว
			$status++;
		}
		
	}else{ //หากเป้นสินค้าอื่นๆยังไม่รองรับ
		$status++;
	}	
	

	$qry_up = pg_query("	UPDATE \"thcap_asset_biz_detail_central\"
							SET  	\"statusapp\" = '1', 
									\"appID\" = '$id_user', 
									\"appDate\" = LOCALTIMESTAMP(0)
							WHERE 	\"ascenID\" = '$ascenID' ");
	IF($qry_up){}else{ $status++; }

	
}else if($cmd == 'not'){ //หากไม่อนุมัติ

	$qry_up = pg_query("	UPDATE \"thcap_asset_biz_detail_central\"
							SET  	\"statusapp\" = '2', 
									\"appID\" = '$id_user', 
									\"appDate\" = LOCALTIMESTAMP(0),
									\"noteapp\" = $note
							WHERE 	\"ascenID\" = '$ascenID' ");
	IF($qry_up){}else{ $status++; }


}else{
	$status++;
}	
	
	
	
	
if($status == 0)
{
	pg_query("COMMIT");
	if($autoapp == 't'){
		echo "<center><h2><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อย พร้อมอนุมัติโดยระบบ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด \" onclick=\"RefreshMe();\"></center>";
	}else{
		if($frompage =="appvdetail"){
			$script= '<script language=javascript>';
			$script.= " alert('อนุมัติเรียบร้อยแล้ว');
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
