<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../nw/function/checknull.php");

$n_year=date("Y");
$n_mo=date("m");
$n_day='01';
$id_user = $_SESSION["av_iduser"];
$nowdate=$n_year."-".$n_mo."-".$n_day;
$startKeyDate = nowDateTime();
$contactnote = $_POST["contactnote"];
$creditID = $_POST["creditID"];

//begin //
pg_query("BEGIN");
$status=0;

$newidcard = $_POST["newidcard"]; //กรณีเลือกข้อมูลเก่าจะเท่ากับ 1 ถ้าข้อมูลใหม่จะเท่ากับ 2
$f_idcard = $_POST["f_idcard"]; // เลขบัตรประชาชน
$befor_f_idcard = str_replace(" ","",$f_idcard); // ตัดช่องว่างในเลขที่บัตรประชาชนออก
$lenID = strlen($befor_f_idcard);//นับเลขบัตรหลังจากตัดช่องว่างออกแล้ว
if($lenID == 13) //ถ้าพบว่าเลขบัตรเป็นตัวเลขทั้ง 13 หลักตรวจสอบต่อว่าเป็นตัวเลขทั้งหมดหรือไม่ ถ้าใช่ก็ให้แทนตัวแปรเพื่อนำไปใช้ถัดไป
{
	if(is_numeric($befor_f_idcard))
	{ 
		$f_idcard = $befor_f_idcard;
	}
}

//นำเข้า function เพื่อใส่ เครื่องหมาย '' ให้กับค่าที่ได้ โดยถ้าไม่มีค่า ตัวแปร $f_idcard จะเท่ากับ null
$f_idcard = checknull($f_idcard);

if($newidcard == 1) //กรณีเลขบัตรเก่า
{
	$idold = $_POST["s_val"];
	$cus_len = substr($idold,0,6);
	$cusid_upid = $cus_len;
	
	$sql_se_cus = pg_query("select * from \"Fn\" where \"CusID\"='$cusid_upid' ");
	while($resultcard = pg_fetch_array($sql_se_cus))
	{
		$oldcard = $resultcard["N_IDCARD"];
	}
	
	$oldcard2 = str_replace(" ","",$oldcard);
	$oldcard2 = str_replace("-","",$oldcard2);	//ให้ update เลขบัตรโดยไม่ให้มีช่องว่างและอักขระ นอกจากตัวเลข
	
	$sql_upid="update public.\"Fn\" set \"N_IDCARD\"='$oldcard2' , \"N_CARDREF\"='$oldcard' where \"CusID\"='$cusid_upid'";
	if($resultUpid=pg_query($sql_upid))
	{}
	else
	{
		$status++;
	}
}


if($newidcard == 2) //กรณีคีย์เลขบัตรใหม่
{
	$idold = $_POST["s_val"];
	$cus_len = substr($idold,0,6);
	$cusid_upid = $cus_len;
	
	$sql_se_cus = pg_query("select * from \"Fn\" where \"CusID\"='$cusid_upid' ");
	while($resultcard = pg_fetch_array($sql_se_cus))
	{
		$oldcard = $resultcard["N_IDCARD"];
	}	
	
	$sql_upid="update public.\"Fn\" set \"N_IDCARD\"=$f_idcard , \"N_CARDREF\"='$oldcard' where \"CusID\"='$cusid_upid'";
	if($resultUpid=pg_query($sql_upid))
	{}
	else
	{
		$status++;
	}
}

$ss_cusid=$_POST["sta_cusid"];
if($ss_cusid==1){ //กรณีที่เรียกข้อมูลเก่ามาใช้
	$fp_cus_val=$_POST["txtnames"];
	list($str_cus,$fullname,$idcard)=explode("#",$fp_cus_val);
	//$str_cus=substr($fp_cus_val,0,6);
	
	// ตรวจสอบก่อนว่า มีลูกค้าคนดังกล่าวอยู่ในระบบจริงหรือไม่
	$qry_chkCusID = pg_query("select count(*) from \"Fa1\" where \"CusID\" = '$str_cus' ");
	$chkCountCusID = pg_fetch_result($qry_chkCusID,0);
	
	// ถ้าไม่พบรหัสลูกค้าในระบบ
	if($chkCountCusID == 0)
	{
		$status++;
		echo "<br>ไม่พบรหัสลูกค้าในระบบ!!</br>";
	}
  
	$pfinal_cusid=$str_cus;
}else{ //กรณีไม่พบข้อมูล
	// ดึงคำหน้า ชื่อ สกลุ มาไว้ในตัวแปร//
	$fs_firname=$_POST["f_firname"];
	$fp_name=$_POST["f_name"];
	$fp_sirname=$_POST["f_sirname"];
   
	//ตรวจสอบชื่อ นามสกุลใน function เพื่อให้ return รหัสลูกค้า 
	$qry_fname=pg_query("select check_cus_name('$fp_name','$fp_sirname')");
	$res_fname=pg_fetch_result($qry_fname,0);
  
	//ถ้าไม่พบรหัสลูกค้าที่ได้จาก function แสดงว่าเป็นลูกค้ารายใหม่
	if($res_fname==""){ 
		//------Gen CusID โดยตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
			$cus_sn = GenCus();
		//----------------------
		
		//--เช็คก่อนว่าลูกค้ามีแล้วหรือยัง (ตรวจสอบอีกครั้งเพื่อความแน่ใจ)
		$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fp_name' and \"A_SIRNAME\" = '$fp_sirname' ");
		$row_check_name = pg_num_rows($sql_check_name);
		
		//กรณีมีลูกค้าคนนี้แล้ว
		if($row_check_name > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}

		//------ เช็คก่อนว่ามีลูกค้าคนนี้รอการอนุมัติอยู่แล้วหรือไม่ โดยเช็คจาก ชื่อ - นามสกุล
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where \"A_NAME\" = '$fp_name' and \"A_SIRNAME\" = '$fp_sirname' and \"CusID\" like 'CT%' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้รอการอนุมัติอยู่แล้ว";
		}
		
		//------ เช็คก่อนว่ามีลูกค้าคนนี้รอการอนุมัติอยู่แล้วหรือไม่ โดยเช็คจาก เลขบัตร
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where replace(\"N_IDCARD\",' ','') = $f_idcard and \"CusID\" like 'CT%' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้รอการอนุมัติอยู่แล้ว";
		}
		//------ จบการเช็คก่อนว่ามีลูกค้าคนนี้รอการอนุมัติอยู่แล้วหรือยัง
		
		//กรณียังไม่มีลูกค้าคนนี้และไม่ได้รออนุมัติอยู่ ให้ insert ข้อมูลลูกค้าใหม่ในตาราง Fa1
		$in_sql_fa1="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\") values ('$cus_sn','$fs_firname','$fp_name','$fp_sirname')";
		if($result_fa1=pg_query($in_sql_fa1)){
		}else{
			$status++;
		}

		//กรณียังไม่มีลูกค้าคนนี้และไม่ได้รออนุมัติอยู่ให้ insert ข้อมูลลูกค้าใหม่ในตาราง Fn
		$in_fn="insert into \"Fn\" (\"CusID\" , \"N_STATE\" , \"N_IDCARD\") values  
				('$cus_sn','0',$f_idcard)";
		if($result_fn=pg_query($in_fn)){
		}else{
			$status++;
		}
		
		////กรณียังไม่มีลูกค้าคนนี้และไม่ได้รออนุมัติอยู่ให้ insert ประวัติการเพิ่มหรือแก้ไขข้อมูลลูกค้าคนนี้ด้วย
		$insert_Fa1="INSERT INTO \"Customer_Temp\"(
				\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\",\"N_STATE\",\"N_IDCARD\")
				VALUES ('$cus_sn','$id_user',LOCALTIMESTAMP(0),'000',LOCALTIMESTAMP(0),'1','0','$fs_firname', '$fp_name', '$fp_sirname','0',$f_idcard)";
		if($result_temp=pg_query($insert_Fa1)){
		}else{
			$status++;
			$error=$result;
		}
		
		$pfinal_cusid=$cus_sn; //แทนรหัสลูกค้าด้วยค่าที่ GEN ได้
	}else{
	    $pfinal_cusid=$res_fname; //แทนรหัสลูกค้าด้วยข้อมูลเดิม
	} 
}//end else $ss_cusid != 1

//ตรวจสอบข้อมูลรถ
$ss_carid=$_POST["sta_carid"]; 
if($ss_carid==1){ //กรณีเลือกข้อมูลเดิม
	$fp_car_val=$_POST["txtnamess"];
	$pfinal_carid=substr($fp_car_val,0,8);
	
	//หาข้อมูลของข้อมูลเดิมเพื่อนำไป insert เป็นประวัติในตาราง Carregis_temp
	$qrycar=pg_query("SELECT \"C_CARNAME\", \"C_YEAR\", \"C_REGIS\", \"C_REGIS_BY\", \"C_COLOR\", 
       \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", \"C_TAX_MON\", 
       \"C_StartDate\", \"RadioID\", \"CarType\", \"C_CAR_CC\", \"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\",\"fc_gas\"
	FROM \"Fc\" where \"CarID\"='$pfinal_carid'");
	$rescar=pg_fetch_array($qrycar);
	list($fp_band, $fp_yearcar, $fp_regis, $C_REGIS_BY, $fp_color, 
		$fp_car, $fp_mar, $C_Milage, $C_TAX_ExpDate, $C_TAX_MON, 
		$C_StartDate, $fp_radio, $cartype, $C_CAR_CC, $fc_type, $fc_brand,$fc_model, $fc_category, $fc_newcar,$fc_gas)=$rescar;

	$fp_fc_type = checknull($fc_type); // ประเภท รถยนต์/จักรยายนต์
	$fp_fc_model = checknull($fc_model); //รุ่น
	$fp_fc_category = checknull($fc_category); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
	$fp_fc_newcar = checknull($fc_newcar); //รถใหม่หรือรถใช้แล้ว
	$fp_fc_brand = checknull($fc_brand); //ยี่ห้อ
	$C_Milage = checknull($C_Milage); //ไมล์
	$fp_fc_gas = checknull($fc_gas); //ระบบแก๊สรถยนต์
	$fp_radio=checknull($fp_radio);
	$fp_regis=checknull($fp_regis);
	$C_REGIS_BY=checknull($C_REGIS_BY);
	
}else{ //กรณีเป็นข้อมูลใหม่
	$fp_mar=$_POST["f_marnum"];
	$C_REGIS_BY=checknull($_POST["f_province"]); //จังหวัดที่จดทะเบียน
	$fp_car=trim($_POST["f_carnum"]);
	$fp_regis=checknull($_POST["f_regis"]);
	$fp_color=$_POST["f_carcolor"];
	$fp_radio=checknull($_POST["f_radio"]);
	$fp_yearcar=$_POST["f_yearcar"]; // ปีรถ
	
	$C_Milage=checknull($_POST["C_Milage"]); //เลขไมล์
	$fp_fc_type = checknull($_POST["f_type_vehicle"]); // ประเภท รถยนต์/จักรยายนต์
	$fp_fc_model = checknull($_POST["f_model"]); //รุ่น
	$qrysel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '".$_POST["f_model"]."' ");
	list($model_name)=pg_fetch_array($qrysel_model);
	$fp_fc_category = checknull($_POST["f_useful_vehicle"]); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
	$fp_fc_newcar = checknull($_POST["f_status_vehicle"]); //รถใหม่หรือรถใช้แล้ว
	$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '".$_POST["f_brand"]."' ");
	list($fp_band) = pg_fetch_array($qry_sel_brand);
	$fp_band=$fp_band." ".$model_name; //เก็บทั้งชื่อยี่ห้อและรุ่น
	$fp_fc_brand = checknull($_POST["f_brand"]); //ยี่ห้อ
	$fp_fc_gas = checknull($_POST["gas_system"]); //ระบบแก๊สรถยนต์
	//save car

	if($fp_color == "เขียว-เหลือง"){
		$cartype = "2";
	}elseif($fp_color == "อื่นๆ"){
		$cartype = "3";
	}else{
		$cartype = "1";
	}
	
	//หารหัสรถเลขใหม่โดยการ query ครั้งเดียว
	$qry_carid=pg_query("select 'TAX' || lpad((count(*)+1)::text, 5, '0')  from \"Fc\"");
	list($car_sn)=pg_fetch_array($qry_carid);
	
	/*
	$res_carid=pg_fetch_array($qry_carid);
	$resc_cars=$res_carid[res_fc];
	if($resc_cars==0){
		$res_car=1;
	}else{
		$res_car=$res_carid[res_fc]+1;
	}
 
	//gencode Fc
	function insertZeros($inputValue , $digit ){
		$str = "" . $inputValue;
		while (strlen($str) < $digit){
			$str = "0" . $str;
		}
		return $str;
    }

	$afc = $res_car;
	$car_sn="TAX".insertZeros($afc , 5);
	*/
	
	// ตรวจสอบก่อนว่า เคยมีเลขตัวถังนี้แล้วหรือยัง
	$qry_chkCARNUM = pg_query("select * from \"Fc\" where \"C_CARNUM\" = '$fp_car' ");
	$numrows_chkCARNUM = pg_num_rows($qry_chkCARNUM);
	
	if($numrows_chkCARNUM > 0) // ถ้ามีเลขตัวถังอยู่แล้ว
	{
		//หารหัสรถเลขที่มากที่สุด (รหัสรถเลขล่าสุด) สามารถใช้ max ได้ตรวจสอบแล้ว
		$qry_maxID = pg_query("select max(\"CarID\") from \"Fc\" where \"C_CARNUM\" = '$fp_car'");
		list($LastCarID)=pg_fetch_array($qry_maxID);
		
		/*
		$qry_maxID = pg_query("select max(replace(\"CarID\",'TAX','')::numeric)::numeric as \"maxCarID\" from \"Fc\" where \"C_CARNUM\" = '$fp_car' ");
		while($resMaxID = pg_fetch_array($qry_maxID))
		{
			$maxCarID = $resMaxID["maxCarID"]; // เลขรหัสที่มากที่สุด
		}
		
		// เติมเลขให้ครบ 5 หลัก
		if(strlen($maxCarID) < 5)
		{
			do{
				$maxCarID = "0".$maxCarID;
			}while(strlen($maxCarID) < 5);
		}
		
		$LastCarID = "TAX".$maxCarID; // รหัสที่มากที่สุด
		*/
		
		// เช็คในตารางที่เก็บ Log ก่อนว่า เคยเก็บ Log CarID นี้ไปแล้ว
		$qry_chkLog = pg_query("select * from \"Fc_duplicate_log\" where \"LastCarID\" = '$LastCarID' ");
		$row_chkLog = pg_num_rows($qry_chkLog);
		
		if($row_chkLog == 0) // ถ้ายังไม่เคยเก็บ Log CarID นี้มาก่อน
		{
			// เพิ่มข้อมูลของรายการที่มากที่สุดก่อน
			$strInsert_FSame = "insert into \"Fc_duplicate_log\"(\"LastCarID\", \"SameCarID\") values('$LastCarID', '$LastCarID') ";
			if($result=pg_query($strInsert_FSame)){
			}else{
				$status++;
			}
			
			// หา CarID อื่นที่มีเลขตัวถังเหมือนกัน
			$qry_oldID = pg_query("select \"CarID\" from \"Fc\" where \"C_CARNUM\" = '$fp_car' and \"CarID\" <> '$LastCarID' ");
			while($resOldID = pg_fetch_array($qry_oldID))
			{
				$oldCarID = $resOldID["CarID"];
				
				$strInsert_inSame = "insert into \"Fc_duplicate_log\"(\"LastCarID\", \"SameCarID\") values('$LastCarID', '$oldCarID') ";
				if($result=pg_query($strInsert_inSame)){
				}else{
					$status++;
				}
			}
		}
		//เพิ่มการ จังหวัดที่จดทะเบียน ด้วย   ($C_REGIS_BY)
		$sql_upCar = "update public.\"Fc\" set \"C_REGIS\"=$fp_regis, \"C_MARNUM\"='$fp_mar', \"C_CARNAME\" = '$fp_band', \"C_COLOR\" = '$fp_color',
						\"RadioID\" = $fp_radio, \"C_YEAR\" = '$fp_yearcar',\"C_Milage\" = $C_Milage,\"fc_type\" = $fp_fc_type, 
						\"fc_brand\" = $fp_fc_brand, \"fc_model\" = $fp_fc_model, \"fc_category\" = $fp_fc_category, \"fc_newcar\" = $fp_fc_newcar,\"fc_gas\" = $fp_fc_gas,\"C_REGIS_BY\"=$C_REGIS_BY
						where \"CarID\"='$LastCarID'";
		if($resultUpCar = pg_query($sql_upCar)){
		}else{
			$status++;
		}
		
		$pfinal_carid=$LastCarID;
		
		$cleas = 0;
		
		//หา CarID ที่มีเลขตัวถังเหมือนกัน
		$qry_OCarID = pg_query("select \"SameCarID\" from \"Fc_duplicate_log\" where \"LastCarID\" = '$LastCarID' and \"SameCarID\" <> '$LastCarID' ");
		while($res_OCarID = pg_fetch_array($qry_OCarID))
		{
			$cleas++;
			$SameCarID = $res_OCarID["SameCarID"]; // CarID ที่มีเลขตัวถังเหมือนกัน
			
			$CarIDWhere[$cleas] = $SameCarID;
		}
		
		//-------------- Update ตารางอื่นๆที่มี CarID เป็นส่วนประกอบ
		if($cleas > 0)
		{ // ถ้ามี CarID อื่น ที่มีเลขที่ตัวถังซ้ำถึงจะทำในส่วนนี้
			$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as cl
					from INFORMATION_SCHEMA.COLUMNS
					where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
					and data_type in('character varying','text','character','char','regclass','name')";
			$query = pg_query($sql);
			while($re = pg_fetch_array($query))
			{
				$SCHEMA = $re['sm']; // ชื่อ schema
				$realtb = $re['tb']; // ชื่อ ตาราง
				$column = $re['cl']; // ชืิ่อ column
				
				// ถ้าเป็นตาราง Fc_duplicate_log , Fc ไม่ต้องทำ
				if(($SCHEMA == "public" && $realtb == "Fc_duplicate_log") || ($SCHEMA == "public" && $realtb == "Fc"))
				{
					continue;
				}
				
				// หา column ที่มีลักษณะของ CarID
				$sql1 = "select \"$column\" as \"CarID\" from $SCHEMA.\"$realtb\" where \"$column\" LIKE 'TAX%' limit 1";
				$query1 = pg_query($sql1);
				$rows = pg_num_rows($query1);
				$re1 = pg_fetch_array($query1);
				if($rows > 0 )
				{
					$chkdigi = trim($re1['CarID']); // CarID
					$chkre = substr($chkdigi,3); // ตัวอักษรด้านหน้าออก 3 ตัว
					$cjkre2 = strlen($chkre); // จำนวนตัวอักษรที่เหลือ

					if($cjkre2 == 5) // ถ้าตัวอักษรที่เหลือเท่ากับ 5 ตัว
					{
						if(is_numeric($chkre)) // ถ้าตัวอักษรที่เหลือเป็นตัวเลขทั้งหมด
						{
							for($r=1; $r<=$cleas; $r++)
							{
								if($r == 1)
								{
									$strWhere = " \"$column\" = '$CarIDWhere[$r]'";
								}
								else
								{
									$strWhere = " $strWhere or \"$column\" = '$CarIDWhere[$r]'";
								}
							}
							
							$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\"='$LastCarID' where $strWhere ";
							if($resultFnTemp=pg_query($test_sql7))
							{}
							else
							{
								$status++;
							}
						}
					}
				}
			}
		}
		//-------------- END Update ตารางอื่นๆที่มี CarID เป็นส่วนประกอบ
	}
	else // ถ้ายังไม่เคยมีเลขตัวถังนี้มาก่อน ให้เพิ่มลงไปใหม่
	{
		$in_sql_fc="insert into \"Fc\" (
		\"CarID\",\"C_REGIS\",\"C_CARNUM\",\"C_MARNUM\",\"C_CARNAME\",\"C_COLOR\",\"RadioID\",\"C_YEAR\",\"C_Milage\",\"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\", \"fc_gas\",\"C_REGIS_BY\"
		) values(
		'$car_sn',$fp_regis,'$fp_car','$fp_mar','$fp_band','$fp_color',$fp_radio,'$fp_yearcar',$C_Milage,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar,$fp_fc_gas,$C_REGIS_BY
		)";
		if($result_fc=pg_query($in_sql_fc)){
		}else{
			$status++;
		}
		
		$pfinal_carid=$car_sn;
	}
} //end else chk carid

$fp_signdate=$_POST["signDate"];
$fp_downprice=$_POST["downprice"];
$fp_count_payment=$_POST["count_payment"];
$fp_price_payment=$_POST["price_payment"];
$fp_first_price=$_POST["first_price"];
$fp_acc_first_price=$_POST["acc_first_price"];
$fp_st_datepayment=$_POST["st_datepayment"];
 
$officeid=$_SESSION["av_officeid"];

$dat=$fp_signdate;

$as_type=$_SESSION["session_company_asset_car"];

$gencode="select generate_id('$dat',$officeid,$as_type)";
$resid=pg_query($db_connect,$gencode);
$residno=pg_fetch_result($resid,0);

//saveIDNO to fp
//$rescf=$_POST["f_comefrom"];

$list_comefrom=$_POST["list_comefrom"];
$txt_comefrom=$_POST["txt_comefrom"];
$str_comefrom = $list_comefrom."".$txt_comefrom;

$gen_ref1=pg_query("select gen_encode_ref1('$residno')");
$res_gen1=pg_fetch_result($gen_ref1,0);
	
$resstnumber=strlen($fp_car);         
$var_cnumber=substr($fp_car,$resstnumber-9,9);

$check = $_POST['package'];
$interest = checknull($_POST['interest']);
$interrestreal = number_format($interest,4);

if($check == 2){

$amtvat_down=pg_query("select amt_before_vat($fp_downprice)");
$res_vatofdown=pg_fetch_result($amtvat_down,0);
$res_vatdown=$fp_downprice-$res_vatofdown;

$amtvat_month=pg_query("select amt_before_vat($fp_price_payment)");
$res_vatmonth=pg_fetch_result($amtvat_month,0);
$res_p_vat=$fp_price_payment-$res_vatmonth;

$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"P_STDATE\",
                             \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",
							 \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"ComeFrom\",
							 \"TranIDRef1\",\"TranIDRef2\",\"P_CustByYear\",\"creditType\") 
                      values  
                            ('$residno','$pfinal_cusid','$fp_signdate',
							'$res_vatofdown','$fp_count_payment','$res_vatmonth','$fp_st_datepayment','$fp_first_price',
							'$res_vatdown','$res_p_vat',FALSE,'1','$pfinal_carid','$str_comefrom',
							'$res_gen1','$var_cnumber','$n_year','$creditID')";
							
				if($result_fp=pg_query($ins_fp)){
				}else{
					$status++;
				}
				
$ins_fp_interest="insert into \"Fp_interest\" (\"IDNO\",\"interest\",\"fpackID\")
								values('$residno',$interrestreal,NULL)";
								
				if($result_fp=pg_query($ins_fp_interest)){
				}else{
					$status++;
				}

}else if($check == 1){
$fp_signdate = $_POST["signDate"];
$fp_down_payment1 = 0; 
$fp_down_payment = $_POST['down_list1'];;
$fp_month_payment = $_POST['time_list'];
$fp_period_payment = $_POST['periodvalue'];
$fp_begin_payment = $_POST['capital'];

$vat_down=pg_query("select amt_before_vat($fp_down_payment1)");
$vatofdown=pg_fetch_result($vat_down,0);
$vatdown=$fp_down_payment1-$vatofdown;

$vat_month=pg_query("select amt_before_vat($fp_period_payment)");
$vatofmonth=pg_fetch_result($vat_month,0);
$vatmonth=$fp_period_payment-$vatofmonth;


$numtest = $_POST['car_gen1'];

$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"P_STDATE\",
		 \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",
		 \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"ComeFrom\",
		 \"TranIDRef1\",\"TranIDRef2\",\"P_CustByYear\",\"creditType\") 
		values  
		('$residno','$pfinal_cusid','$fp_signdate',
		'$vatofdown','$fp_month_payment','$vatofmonth','$fp_st_datepayment','$fp_begin_payment',
		'$vatdown','$vatmonth',FALSE,'1','$pfinal_carid','$str_comefrom',
		'$res_gen1','$var_cnumber','$n_year','$creditID')";

		if($result_fp=pg_query($ins_fp)){
		}else{
			$status++;
		}
						
		$package="select * from \"Fp_package\" where \"numtest\" = '$numtest' AND \"down_payment\" = '$fp_down_payment' AND \"period\" = '$fp_period_payment' AND \"month_payment\" = '$fp_month_payment'";
		$sqlpackage=pg_query($package);	
		$result_package = pg_fetch_Array($sqlpackage);
		$fpackID = 	$result_package['fpackID'];
								
		$ins_fp_interest="insert into \"Fp_interest\" (\"IDNO\",\"interest\",\"fpackID\")
						values('$residno',$interrestreal,$fpackID)";
								
		if($result_fp=pg_query($ins_fp_interest)){
		}else{
			$status++;
		}				
}
//ให้เก็บประวัติรถด้วย 

$C_TAX_ExpDate=checknull($C_TAX_ExpDate);
$C_TAX_MON=checknull($C_TAX_MON);
$C_StartDate=checknull($C_StartDate);
$C_CAR_CC=checknull($C_CAR_CC);
$cartype=checknull($cartype);
$fp_province=checknull($fp_province);

/*
if($C_REGIS_BY==""){ $C_REGIS_BY="null";}else{ $C_REGIS_BY="'".$C_REGIS_BY."'"; }
if($C_TAX_ExpDate==""){ $C_TAX_ExpDate="null";}else{ $C_TAX_ExpDate="'".$C_TAX_ExpDate."'"; }
if($C_TAX_MON==""){ $C_TAX_MON="null";}else{ $C_TAX_MON="'".$C_TAX_MON."'"; }
if($C_StartDate==""){ $C_StartDate="null";}else{ $C_StartDate="'".$C_StartDate."'"; }
if($C_CAR_CC==""){ $C_CAR_CC="null";}else{ $C_CAR_CC="'".$C_CAR_CC."'"; }
if($cartype==""){ $cartype="null";}else{ $cartype="'".$cartype."'"; }
*/

$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
	\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
	\"RadioID\", \"CarType\",\"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\",\"fc_gas\") 
values ('$residno',$fp_regis,'$fp_band','$fp_yearcar',$C_REGIS_BY,
	'$fp_color','$fp_car','$fp_mar',$C_Milage,$C_TAX_ExpDate,
	$C_TAX_MON,$C_StartDate,'$pfinal_carid','$id_user','$startKeyDate',$C_CAR_CC,
	$fp_radio,$cartype,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar, $fp_fc_gas)";

if($result_carregis=pg_query($in_carregis)){
}else{
	$status++;
}

//บันทึก ContactNote by por
$ins_con="insert into \"Fp_Note\" (\"IDNO\",\"ContactNote\") values ('$residno','$contactnote')";
//$result_con=pg_query($ins_con);
if($result_con=pg_query($ins_con)){
}else{
	$status++;
}

//บันทึกว่าเลขที่นี้มีค่าแนะนำหรือไม่
if($_POST["valGuide"]=="1" || $_POST["oldid"]=="1"){ //กรณีมีค่าแนะนำ หรือ มีเลขที่สัญญาเก่า
	$GuidePeople=$_POST["GuidePeople"]; if($GuidePeople==""){ $GuidePeople="null";}else{ $GuidePeople="'".$GuidePeople."'"; }//ชื่อผู้แนะนำ
	$oldidno=$_POST["oldidno"]; if($oldidno==""){ $oldidno="null";}else{ $oldidno="'".$oldidno."'"; } //เลขที่สัญญาเก่า
	
	//บันทึก ในตาราง nw_IDNOGuidePeople
	$insguide="insert into \"nw_IDNOGuidePeople\" (\"IDNO\",\"GuidePeople\",\"oldidno\",\"addUser\",\"addStamp\") values ('$residno',$GuidePeople,$oldidno,'$id_user',LOCALTIMESTAMP(0))";
	if($resultguide=pg_query($insguide)){
	}else{
		$status++;
	}
}
 
//บันทึกวันที่ เวลา  และพนักงานที่ทำรายการ ตอนทำสัญญาเช่าซื้อ by por
$ins_startfp="insert into \"nw_startDateFp\" (\"IDNO\",\"id_user\",\"startDate\") values ('$residno','$id_user',LOCALTIMESTAMP(0))";
//$result_startfp=pg_query($ins_startfp); 
if($result_startfp=pg_query($ins_startfp)){
}else{
	$status++;
}

//echo $c_cpay="select \"CreateCusPayment\"('$residno')";
//$result_cpay=pg_query($c_cpay);

/*
if($result_fp=pg_query($ins_fp)){
	$status_fp ="OK".$ins_fp;
}else{
	$status_fp ="error insert Re".$ins_fp;
} 
 echo $status_fp."<br>";
*/

//saveIDNO to contactCus
$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") values ('$residno',0,'$pfinal_cusid')";				 
//$result_cc=pg_query($ins_cc);
if($result_cc=pg_query($ins_cc)){
}else{
	$status++;
}

//ปรับปรุงรถยึด หรือ ReFinance
$text_error = array();
if($list_comefrom == "REPO#"){ 
	$idno_old = $txt_comefrom;
	$idno_new = $residno;

	$count_ref = 0;
	
	$qry_catid=@pg_query("SELECT COUNT(\"auto_id\") as countautoid FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REF#$idno_old' AND \"cancel\"='FALSE'");
	if($res_catid=@pg_fetch_array($qry_catid)){
		$count_ref = $res_catid["countautoid"];
	}

	$qry=pg_query("SELECT * FROM \"Fp\" WHERE \"IDNO\" = '$idno_new'");
	if($res_fp=pg_fetch_array($qry)){
		$new_P_STDATE = $res_fp["P_STDATE"];
		$new_P_CustByYear = $res_fp["P_CustByYear"]+543;
		$new_sub_P_CustByYear = substr($new_P_CustByYear,2,2);
	}

	$qry2=pg_query("SELECT * FROM \"Fp\" WHERE \"IDNO\" = '$idno_old'");
	if($res_fp2=pg_fetch_array($qry2)){
		$old_P_ACCLOSE = $res_fp2["P_ACCLOSE"];
		$old_P_CustByYear = $res_fp2["P_CustByYear"]+543;
		$old_sub_P_CustByYear = substr($old_P_CustByYear,2,2);
		
		$s_P_BEGINX = $res_fp2["P_BEGINX"];
		$s_payment_nonvat = $res_fp2["P_MONTH"];
		$s_payment_all = $res_fp2["P_MONTH"]+$res_fp2["P_VAT"];
		$s_fp_ptotal = $res_fp2["P_TOTAL"];
		$money_all_no_vat = $s_payment_nonvat*$s_fp_ptotal;
	}

	if($old_P_ACCLOSE == "FALSE" OR $old_P_ACCLOSE == "f"){
		$up_sql="UPDATE \"Fp\" SET \"P_ACCLOSE\"='TRUE', \"P_CLDATE\"='$new_P_STDATE' WHERE \"IDNO\"='$idno_old'";
		if(!$res_up_sql=@pg_query($up_sql)){
			$text_error[] = "UPDATE Fp ACCLOSE False !<br />$up_sql<br />";
			$status++;
		}
	}

	$qry=pg_query("SELECT * FROM \"UNContact\" WHERE \"IDNO\" = '$idno_old'");
	if($res_un_old=pg_fetch_array($qry)){
		$old_full_name = $res_un_old["full_name"];
		$old_C_REGIS = $res_un_old["C_REGIS"];
		$old_C_CARNAME = $res_un_old["C_CARNAME"];
	}

	$qry=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\" = '$idno_old' AND \"R_Receipt\" IS NOT NULL ORDER BY \"DueNo\" DESC");
	if($res=pg_fetch_array($qry)){
		$DueNo = $res["DueNo"]; //
		$waitincome = round($res["waitincome"],2);
		$Remine = round($res["Remine"],2);
	}else{
		$waitincome = round($money_all_no_vat-$s_P_BEGINX,2);
		$Remine = round($money_all_no_vat,2);
	}

	$qry7=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\" = '$idno_old' AND \"R_Receipt\" IS NULL ORDER BY \"DueNo\" ASC");
	while($res7=pg_fetch_array($qry7)){
		$DueDate = $res7["DueDate"];
		$gg++;
		if($gg == 4){ break; }
		if($new_P_STDATE > $DueDate){
			$waitincome = round($res7["waitincome"],2);
			//$Remine = round($res7["Remine"],2);
		}else{
			break;
		}
	}

	/* ======= เริ่ม เตรียมข้อมูล ======= */
	if($count_ref > 0){

	}else{
		$gen_no=@pg_query("select account.\"gen_no\"('$new_P_STDATE','AP')");
		$genid=@pg_fetch_result($gen_no,0);
		if(empty($genid)){
			$text_error[] = "Empty GenID !<br />";
			$status++;
		}
	}

	$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='INV'");
	if($res_ac=@pg_fetch_array($qry_ac)){
		$acid_inv = $res_ac["AcID"];
	}
	if(empty($acid_inv)){
		$text_error[] = "Empty INV ID !<br />";
		$status++;
	}

	$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$old_P_CustByYear'");
	if($res_ac=@pg_fetch_array($qry_ac)){
		$acid_543 = $res_ac["AcID"];
	}
	if(empty($acid_543)){
		$text_error[] = "Empty ACID 543 !<br />";
		$status++;
	}

	$old_sub_P_CustByYear_gp = "GP".$old_sub_P_CustByYear;
	$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='$old_sub_P_CustByYear_gp'");
	if($res_ac=@pg_fetch_array($qry_ac)){
		$acid_gp = $res_ac["AcID"];
	}
	if(empty($acid_gp)){
		$text_error[] = "Empty ACID GP !<br />";
		$status++;
	}
	/* ======= จบ เตรียมข้อมูล ======= */

	
	/* ======= เริ่ม insert ข้อมูล======= */
	if($count_ref > 0){
		$qry_catid=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REF#$idno_old' AND \"cancel\"='FALSE'");
		if($res_catid=@pg_fetch_array($qry_catid)){
			$res_auto_id = $res_catid["auto_id"];
		}
		
		$del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$res_auto_id'");
		if(!$del_detail){
			$text_error[] = "DELETE AccountBookDetail 1<br />$del_detail<br />";
			$status++;
		}
		
		$up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"acb_date\"='$new_P_STDATE',\"acb_detail\"='บอกเลิกสัญญาเช่าซื้อ $old_full_name โอนบัญชี ไปสินค้ารถยึดคืน ทะเบียน $old_C_REGIS ยี่ห้อ $old_C_CARNAME',\"ref_id\"='REF#$idno_old' WHERE \"auto_id\"='$res_auto_id'";
		if(!$res_up_sql=@pg_query($up_sql)){
			$text_error[] = "UPDATE AccountBookHead 5.1<br />$up_sql<br />";
			$status++;
		}
		
	}else{
		$in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") 
		values  ('AP','$genid','$new_P_STDATE','บอกเลิกสัญญาเช่าซื้อ $old_full_name โอนบัญชี ไปสินค้ารถยึดคืน ทะเบียน $old_C_REGIS ยี่ห้อ $old_C_CARNAME','REF#$idno_old')";
		if(!$res_in_sql=@pg_query($in_sql)){
			$text_error[] = "Insert AccountBookHead Error ! $in_sql<br />";
			$status++;
		}

		$atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
		$res_auto_id=@pg_fetch_result($atid,0);
		if(empty($res_auto_id)){
			$text_error[] = "SELECT AccountBookHead_auto_id_seq 1 Error !<br />";
			$status++;
		}
	}

	$sum_detail = $Remine-$waitincome;
	$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_inv','$sum_detail','0')";
	if(!$res_in_sql=@pg_query($in_sql)){
		$text_error[] = "INSERT AccountBookDetail 1 Error ! $in_sql<br />";
		$status++;
	}

	$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_gp','$waitincome','0')";
	if(!$res_in_sql=@pg_query($in_sql)){
		$text_error[] = "INSERT AccountBookDetail 2 Error ! $in_sql<br />";
		$status++;
	}

	$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id','$acid_543','0','$Remine')";
	if(!$res_in_sql=@pg_query($in_sql)){
		$text_error[] = "INSERT AccountBookDetail 3 Error ! $in_sql<br />";
		$status++;
	}
	/* ======= จบ  insert ข้อมูล======= */

	
	$count_rev = 0;
	$qry_catid=@pg_query("SELECT COUNT(\"auto_id\") as countautoid FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REV#$idno_old' AND \"cancel\"='FALSE'");
	if($res_catid=@pg_fetch_array($qry_catid)){
		$count_rev = $res_catid["countautoid"];
	}

	$qry=pg_query("SELECT * FROM \"VAccPayment\" WHERE \"IDNO\" = '$idno_old' AND \"V_Receipt\" IS NOT NULL AND \"R_Receipt\" IS NULL ORDER BY \"DueNo\" ASC");
	$numrows = pg_num_rows($qry);
	if($numrows > 0){
		while($res=pg_fetch_array($qry)){
			$VatValue = round($res["VatValue"],2);
			$sum_VatValue += $VatValue;
		}
		
		//Insert เฉพาะรายการที่มี vat มากกว่า 0 รายการ
		if($count_rev > 0){
			
		}else{
			$gen_no=@pg_query("select account.\"gen_no\"('$new_P_STDATE','AP')");
			$genid2=@pg_fetch_result($gen_no,0);
			if(empty($genid2)){
				$text_error[] = "Empty GenID 2 !<br />";
				$status++;
			}
		}

		$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='NVAT'");
		if($res_ac=@pg_fetch_array($qry_ac)){
			$acid_nvat = $res_ac["AcID"];
		}
		if(empty($acid_nvat)){
			$text_error[] = "Empty NVAT ID !<br />";
			$status++;
		}
		
		$qry_ac=@pg_query("SELECT \"AcID\" FROM account.\"AcTable\" WHERE \"AcType\"='AVAT'");
		if($res_ac=@pg_fetch_array($qry_ac)){
			$acid_avat = $res_ac["AcID"];
		}
		if(empty($acid_avat)){
			$text_error[] = "Empty AVAT ID !<br />";
			$status++;
		}

		if($count_rev > 0){
			$qry_catid=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REV#$idno_old' AND \"cancel\"='FALSE'");
			if($res_catid=@pg_fetch_array($qry_catid)){
				$res_auto_id2 = $res_catid["auto_id"];
			}
			
			$del_detail=@pg_query("DELETE FROM account.\"AccountBookDetail\" WHERE \"autoid_abh\"='$res_auto_id2'");
			if(!$del_detail){
				$text_error[] = "DELETE AccountBookDetail 2<br />$del_detail<br />";
				$status++;
			}
			
			$up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"acb_date\"='$new_P_STDATE',\"acb_detail\"='ภาษีมูลค่าเพิ่มที่ส่งแล้ว แต่ลูกค้า $old_full_name ไม่ชำระ $sum_VatValue งวดๆละ $VatValue',\"ref_id\"='REV#$idno_old' WHERE \"auto_id\"='$res_auto_id2'";
			if(!$res_up_sql=@pg_query($up_sql)){
				$text_error[] = "UPDATE AccountBookHead 5.2<br />$up_sql<br />";
				$status++;
			}
		}else{
			$in_sql="insert into \"account\".\"AccountBookHead\" (\"type_acb\",\"acb_id\",\"acb_date\",\"acb_detail\",\"ref_id\") 
			values  ('AP','$genid2','$new_P_STDATE','ภาษีมูลค่าเพิ่มที่ส่งแล้ว แต่ลูกค้า $old_full_name ไม่ชำระ $sum_VatValue งวดๆละ $VatValue','REV#$idno_old')";
			if(!$res_in_sql=@pg_query($in_sql)){
				$text_error[] = "Insert AccountBookHead 2 Error ! $in_sql<br />";
				$status++;
			}

			$atid=@pg_query("select currval('account.\"AccountBookHead_auto_id_seq\"');");
			$res_auto_id2=@pg_fetch_result($atid,0);
			if(empty($res_auto_id2)){
				$text_error[] = "SELECT AccountBookHead_auto_id_seq 2 Error !<br />";
				$status++;
			}
		}
		
		$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_nvat','$sum_VatValue','0')";
		if(!$res_in_sql=@pg_query($in_sql)){
			$text_error[] = "INSERT AccountBookDetail 2.1 Error ! $in_sql<br />";
			$status++;
		}

		$in_sql="insert into \"account\".\"AccountBookDetail\" (\"autoid_abh\",\"AcID\",\"AmtDr\",\"AmtCr\") values  ('$res_auto_id2','$acid_avat','0','$sum_VatValue')";
		if(!$res_in_sql=@pg_query($in_sql)){
			$text_error[] = "INSERT AccountBookDetail 2.2 Error ! $in_sql<br />";
			$status++;
		}
    
	}   //end $numrows > 0

	if($count_rev > 0 AND $numrows == 0){
		$qry_catid=@pg_query("SELECT \"auto_id\" FROM account.\"AccountBookHead\" WHERE \"ref_id\"='REV#$idno_old' AND \"cancel\"='FALSE'");
		if($res_catid=@pg_fetch_array($qry_catid)){
			$res_auto_id2 = $res_catid["auto_id"];
		}
		$up_sql="UPDATE \"account\".\"AccountBookHead\" SET \"cancel\"='TRUE' WHERE \"auto_id\"='$res_auto_id2'";
		if(!$res_up_sql=@pg_query($up_sql)){
			$text_error[] = "UPDATE Out Vat AccountBookHead<br />$up_sql<br />";
			$status++;
		}
	}
} //End ปรับปรุงรถยึด หรือ ReFinance

if($status==0){
	echo $c_cpay="select \"CreateCusPayment\"('$residno')";
	$result_cpay=pg_query($c_cpay);
}
if(($ss_cusid==1) or ($ss_carid==1)){
	if(($result_fp) or ($result_fa1) or ($result_fn) or ($result_cc) or  ($result_fc) or ($res_fname) or ($result_cpay) or ($result_con) or ($result_startfp) or (result_temp)){
		if($status == 0){
			//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำสัญญาเช่าซื้อ', LOCALTIMESTAMP(0))");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "บันทึกข้อมูลเรียบร้อย ";
			echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_leasing.php'\" />";
		}else{
			pg_query("ROLLBACK");
			echo "มีข้อผิดพลาดในการบันทึก $text_error[0]";
			echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_leasing.php'\" />";
		}
	}else{
		pg_query("ROLLBACK");
		echo "cusid = 1 มีข้อผิดพลาดในการบันทึก";
		//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
		echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_leasing.php'\" />";	
	}
}else{
	if(($result_fp) and ($result_fa1) and ($result_fn) and ($result_fc) and ($result_cc) and ($result_cpay)  or ($res_fname) or ($result_con) or ($result_startfp) or (result_temp)){
        if($status == 0){
			//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำสัญญาเช่าซื้อ', LOCALTIMESTAMP(0))");
			//ACTIONLOG---
            pg_query("COMMIT");
            echo "บันทึกข้อมูลเรียบร้อย</br>";
			echo "รหัสสัญญาที่ได้คือ: $residno </br>";
			echo "รหัสสินทรัพย์ที่ใช้คือ: $pfinal_carid </br>";
			echo "รหัสผู้เช่าซื้อที่ใช้คือ: $pfinal_cusid </br>";
            echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_leasing.php'\"/>";   
        }else{
            pg_query("ROLLBACK");
            echo "มีข้อผิดพลาดในการบันทึก $text_error[0]";
			if($error_check != ""){echo "<br>".$error_check;}
            echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_leasing.php'\" />";
        }
	}else{
		pg_query("ROLLBACK");
		echo "มีข้อผิดพลาดในการบันทึก";
		if($error_check != ""){echo "<br>".$error_check;}
		//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
		echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_leasing.php'\" />";
	} 
}

?>