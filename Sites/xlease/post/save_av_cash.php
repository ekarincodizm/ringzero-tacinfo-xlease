<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("../config/config.php");
include("../nw/function/checknull.php");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$n_year=date("Y");
$n_mo=date("m");
$n_day='01';

$nowdate=$n_year."-".$n_mo."-".$n_day;

pg_query("BEGIN");
$status=0;

$ss_cusid=pg_escape_string($_POST["sta_cusid"]);
$contactnote = pg_escape_string($_POST["contactnote"]);
//$contactnote = str_replace("\n", "<br>\n", "$contactnote");

$newidcard = pg_escape_string($_POST["newidcard"]);
$f_idcard = pg_escape_string($_POST["f_idcard"]); // เลขบัตรประชาชน
$befor_f_idcard = str_replace(" ","",$f_idcard); // ตัดช่องว่างในเลขที่บัตรประชาชนออก
$lenID = strlen($befor_f_idcard);
if($lenID == 13)
{
	if(is_numeric($befor_f_idcard))
	{
		$f_idcard = $befor_f_idcard;
	}
}
$f_idcard = checknull($f_idcard);

if($newidcard == 1)
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
	$oldcard2 = str_replace("-","",$oldcard2);	
	
	$sql_upid="update public.\"Fn\" set \"N_IDCARD\"='$oldcard2' , \"N_CARDREF\"='$oldcard' where \"CusID\"='$cusid_upid'";
	if($resultUpid=pg_query($sql_upid))
	{}
	else
	{
		$status++;
	}
}


if($newidcard == 2)
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


if($ss_cusid==1){
	$fp_cus_val=$_POST["txtnames"];
	list($str_cus,$fullname,$idcard)=explode("#",$fp_cus_val);
	//$str_cus=substr($fp_cus_val,0,6);
	$pfinal_cusid=$str_cus;
}else{
// function Check For User //
	$fs_firname=$_POST["f_firname"];
	$fp_name=$_POST["f_name"];
	$fp_sirname=$_POST["f_sirname"];
 
	$qry_fname=pg_query("select check_cus_name('$fp_name','$fp_sirname')");
	$res_fname=pg_fetch_result($qry_fname,0);

	if($res_fname==""){
		//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
			$cus_sn = GenCus();
		//----------------------
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fp_name' and \"A_SIRNAME\" = '$fp_sirname' ");
		$row_check_name = pg_num_rows($sql_check_name);
		if($row_check_name > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}

		$in_sql_fa1="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\") values  
					('$cus_sn','$fs_firname','$fp_name','$fp_sirname')";

		if($result_fa1=pg_query($in_sql_fa1)){	
		}else{
			$status++;
		}

		$in_fn="insert into \"Fn\" (\"CusID\" , \"N_STATE\" , \"N_IDCARD\") values  
				('$cus_sn','0',$f_idcard)";

		if($result=pg_query($in_fn)){
		}else{
			$status++;
		}
		
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where (\"A_NAME\" = '$fp_name' and \"A_SIRNAME\" = '$fp_sirname') or replace(\"N_IDCARD\",' ','') = $f_idcard ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}
		//------ เช็คก่อนว่ามีลูกค้าคนนี้รอการอนุมัติอยู่แล้วหรือยัง
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where \"A_NAME\" = '$fp_name' and \"A_SIRNAME\" = '$fp_sirname' and \"CusID\" like 'CT%' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้รอการอนุมัติอยู่แล้ว";
		}
		
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where replace(\"N_IDCARD\",' ','') = $f_idcard and \"CusID\" like 'CT%' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้รอการอนุมัติอยู่แล้ว";
		}
		//------ จบการเช็คก่อนว่ามีลูกค้าคนนี้รอการอนุมัติอยู่แล้วหรือยัง
		
		$insert_Fa1="INSERT INTO \"Customer_Temp\"(
				\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\",\"N_STATE\",\"N_IDCARD\")
				VALUES ('$cus_sn','$add_user','$add_date','000','$add_date','1','0','$fs_firname', '$fp_name', '$fp_sirname','0',$f_idcard)";
		if($result=pg_query($insert_Fa1)){
		}else{
			$status++;
			$error=$result;
		}
		
		$pfinal_cusid=$cus_sn;
	}
	else{
		$pfinal_cusid=$res_fname;
	}
}

// chk carid
$ss_carid=$_POST["sta_carid"];
if($ss_carid==1){//ข้อมูลเดิม
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
		$C_Milage = checknull($C_Milage); //ยี่ห้อ
		$fp_fc_gas = checknull($fc_gas); //ระบบแก๊สรถยนต์
		$fp_radio=checknull($fp_radio);
		$fp_regis=checknull($fp_regis); //ทะเบียน
		$C_REGIS_BY=checknull($C_REGIS_BY);//จังหวัดที่จดทะเบียน
	   
}else {//ข้อมูลใหม่
	$fp_mar=pg_escape_string($_POST["f_marnum"]);
	$fp_car=pg_escape_string($_POST["f_carnum"]);
	$C_REGIS_BY=checknull(pg_escape_string($_POST["f_province"]));//จังหวัดที่จดทะเบียน
	$fp_regis=checknull(pg_escape_string($_POST["f_regis"]));//ทะเบียน
	$fp_color=pg_escape_string($_POST["f_carcolor"]);
	$fp_radio=checknull(pg_escape_string($_POST["f_radio"]));
	$fp_yearcar=pg_escape_string($_POST["f_yearcar"]); // ปีรถ
	
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

	$qry_carid=pg_query("select count(*) AS res_fc from \"Fc\" ");
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
	
	// ตรวจสอบก่อนว่า เคยมีเลขตัวถังนี้แล้วหรือยัง
	$qry_chkCARNUM = pg_query("select * from \"Fc\" where \"C_CARNUM\" = '$fp_car' ");
	$numrows_chkCARNUM = pg_num_rows($qry_chkCARNUM);
	if($numrows_chkCARNUM > 0) // ถ้ามีเลขตัวถังอยู่แล้ว
	{
		// หาเลขที่มากที่สุด
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
		
		$sql_upCar = "update public.\"Fc\" set \"C_REGIS\"=$fp_regis,\"C_REGIS_BY\"=$C_REGIS_BY ,\"C_MARNUM\"='$fp_mar', \"C_CARNAME\" = '$fp_band', \"C_COLOR\" = '$fp_color',
						\"RadioID\" = $fp_radio, \"C_YEAR\" = '$fp_yearcar',\"C_Milage\" = $C_Milage,\"fc_type\" = $fp_fc_type, 
						\"fc_brand\" = $fp_fc_brand, \"fc_model\" = $fp_fc_model, \"fc_category\" = $fp_fc_category, \"fc_newcar\" = $fp_fc_newcar,
						\"fc_gas\" = $fp_fc_gas
						where \"CarID\"='$LastCarID'";
		if($resultUpCar = pg_query($sql_upCar)){
		}else{
			$status++;
		}
		
		$pfinal_carid=$LastCarID;
		
		$cleas = 0;
		// หา CarID ที่มีเลขตัวถังเหมือนกัน
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
								echo "<br>$test_sql7<br>";
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
		\"CarID\",\"C_REGIS\",\"C_REGIS_BY\",\"C_CARNUM\",\"C_MARNUM\",\"C_CARNAME\",\"C_COLOR\",\"RadioID\",\"C_YEAR\",\"C_Milage\",\"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\",\"fc_gas\"
		) values(
		'$car_sn',$fp_regis,$C_REGIS_BY,'$fp_car','$fp_mar','$fp_band','$fp_color',$fp_radio,'$fp_yearcar',$C_Milage,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar,$fp_fc_gas
		)";
		
		if($result_fc=pg_query($in_sql_fc)){
		}else{
			$status++;
		}
		
		$pfinal_carid=$car_sn;
	}
	
	/*
	 if($result_fc=pg_query($in_sql_fc))
	 {
	  $status ="OK".$in_sql;
	 }
	 else
	 {
	  $status ="error insert Re".$in_sql;
	 }
	*/
	
	//$pfinal_carid=$car_sn;
}

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

$gencode="select generate_cash_id('$dat', $officeid,$as_type)";

$resid=pg_query($db_connect,$gencode);

$residno=pg_fetch_result($resid,0);

//saveIDNO to fp

$rescf=$_POST["f_comefrom"];

$gen_ref1=pg_query("select gen_encode_ref1('$residno')");
$res_gen1=pg_fetch_result($gen_ref1,0);
	
$resstnumber=strlen($fp_car);         
$var_cnumber=substr($fp_car,$resstnumber-9,9);

$res_cfrom=$_POST["f_comefrom"];

$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"P_STDATE\",
                             \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",\"P_BEGINX\" ,
							  \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"ComeFrom\",
							  \"P_CLDATE\",\"P_StopVat\",\"P_ACCLOSE\",
							  \"TranIDRef1\",\"TranIDRef2\",\"P_CustByYear\"
							 ) 
                      values  
                            ('$residno','$pfinal_cusid','$fp_signdate',
							  '0','0','0','$fp_signdate','0','0',
							  '0','0',FALSE,'1','$pfinal_carid','$rescf',
							  '$fp_signdate',FALSE,TRUE,
							  '$res_gen1','$var_cnumber','$n_year' 
							 )";
if($result_fp=pg_query($ins_fp)){
}else{
	$status++;
} 

//ให้เก็บประวัติรถด้วย 
//if($C_REGIS_BY==""){ $C_REGIS_BY="null";}else{ $C_REGIS_BY="'".$C_REGIS_BY."'"; }
if($C_TAX_ExpDate==""){ $C_TAX_ExpDate="null";}else{ $C_TAX_ExpDate="'".$C_TAX_ExpDate."'"; }
if($C_TAX_MON==""){ $C_TAX_MON="null";}else{ $C_TAX_MON="'".$C_TAX_MON."'"; }
if($C_StartDate==""){ $C_StartDate="null";}else{ $C_StartDate="'".$C_StartDate."'"; }
if($C_CAR_CC==""){ $C_CAR_CC="null";}else{ $C_CAR_CC="'".$C_CAR_CC."'"; }
if($cartype==""){ $cartype="null";}else{ $cartype="'".$cartype."'"; }

$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
	\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
	\"RadioID\", \"CarType\",\"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\",\"fc_gas\") 
values ('$residno',$fp_regis,'$fp_band','$fp_yearcar',$C_REGIS_BY,
	'$fp_color','$fp_car','$fp_mar',$C_Milage,$C_TAX_ExpDate,
	$C_TAX_MON,$C_StartDate,'$pfinal_carid','$add_user','$add_date',$C_CAR_CC,
	$fp_radio,$cartype,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar,$fp_fc_gas)";

if($result_carregis=pg_query($in_carregis)){
}else{
	$status++;
}

//saveIDNO to contactCus
$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") 
                  values  
                 ('$residno',0,'$pfinal_cusid')";
if($result_cc=pg_query($ins_cc)){
}else{
	$status++;
} 
//บันทึก ContactNote by por
$ins_con="insert into \"Fp_Note\" (\"IDNO\",\"ContactNote\") values ('$residno','$contactnote')";
if($result_con=pg_query($ins_con)){
}else{
	$status++;
}

//บันทึกว่าเลขที่นี้มีค่าแนะนำหรือไม่
if($_POST["valGuide"]=="1"){ //กรณีมีค่าแนะนำ
	$GuidePeople=$_POST["GuidePeople"]; //ชื่อผู้แนะนำ
	
	//บันทึก ในตาราง nw_IDNOGuidePeople
	$insguide="insert into \"nw_IDNOGuidePeople\" (\"IDNO\",\"GuidePeople\") values ('$residno','$GuidePeople')";
	if($resultguide=pg_query($insguide)){
	}else{
		$status++;
	}
}

if(($ss_cusid==1) or ($ss_carid==1))
{
	if($status==0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(TAL) ทำสัญญาซื้อสด', '$add_date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "$residno บันทึกข้อมูลเรียบร้อย ";
		echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_cash.php'\" >";
	}else{
		pg_query("ROLLBACK");
		echo "<center>";
		echo "cusid = 1 มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่";
		if($error_check != ""){echo "<br>".$error_check."<br>";}
		echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_cash.php'\" />";
		echo "</center>";
	}
}else{
	if($status==0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(TAL) ทำสัญญาซื้อสด', '$add_date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "$residno บันทึกข้อมูลเรียบร้อย";
		echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_cash.php'\" />";
	
	}else{
		pg_query("ROLLBACK");
		echo "<center>";
		echo "มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่".$residno;
		if($error_check != ""){echo "<br>".$error_check."<br>";}
		echo "<input type=\"button\" value=\"BACK\" onclick=\"window.location='av_sign_cash.php'\" />";
		echo "</center>";
	} 
}
?>
