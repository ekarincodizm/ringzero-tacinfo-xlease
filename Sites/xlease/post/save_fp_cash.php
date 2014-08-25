<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

$n_year=date("Y");
$n_mo=date("m");
$n_day='01';

$nowdate=$n_year."-".$n_mo."-".$n_day;

pg_query("BEGIN");


$ss_cusid=$_POST["sta_cusid"];
if($ss_cusid==1)
{
 $fp_cus_val=$_POST["s_val"];
 $str_cus=substr($fp_cus_val,0,6);
 
 $pfinal_cusid=$str_cus;
}
else
{
  // function Check For User //
  $fs_firname=$_POST["f_firname"];
  $fp_name=$_POST["f_name"];
  $fp_sirname=$_POST["f_sirname"];
  
 
 
  $qry_fname=pg_query("select check_cus_name('$fp_name','$fp_sirname')");
  $res_fname=pg_fetch_result($qry_fname,0);

if($res_fname=="")
  { 
 
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

 $in_sql_fa1="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\") 
          values  
          ('$cus_sn','$fs_firname','$fp_name','$fp_sirname')";
 if($result_fa1=pg_query($in_sql_fa1))
 {
  $status ="OK".$in_sql;
 }
 else
 {
  $status ="error insert Re".$in_sql;
 }


 $in_fn="insert into \"Fn\" (\"CusID\" , \"N_STATE\") 
          values  
          ('$cus_sn','0')";
 if($result=pg_query($in_fn))
 {
  $st_fn="OK".$in_fn;
 }
 else
 {
  $st_fn="error insert Re".$in_sql;
 }

 
 
 $pfinal_cusid=$cus_sn;
}
 else 
	 {
	     $pfinal_cusid=$res_fname;
	 } 
}

// chk carid
$ss_carid=$_POST["sta_carid"];
if($ss_carid==1)
{
 $fp_car_val=$_POST["s_vals"];
 $pfinal_carid=substr($fp_car_val,0,8);
 
 
}
else 
{
$fp_mar=$_POST["f_marnum"];
$fp_car=$_POST["f_carnum"];
$fp_regis=$_POST["f_regis"];
$fp_band=$_POST["f_band"];
$fp_color=$_POST["f_color"];
$fp_radio=$_POST["f_radio"];
//save car

$qry_carid=pg_query("select count(*) AS res_fc from \"Fc\" ");
$res_carid=pg_fetch_array($qry_carid);
$resc_cars=$res_carid[res_fc];


if($resc_cars==0)
{
  $res_car=1;
}
else
{
  $res_car=$res_carid[res_fc]+1;
}
 

 //gencode Fc
 
   function insertZeros($inputValue , $digit )
		{
			$str = "" . $inputValue;
			while (strlen($str) < $digit)
			{
				$str = "0" . $str;
			}
			return $str;
        }

	$afc = $res_car;
	$car_sn="TAX".insertZeros($afc , 5);

 $in_sql_fc="insert into \"Fc\" (\"CarID\",\"C_REGIS\",\"C_CARNUM\",\"C_MARNUM\",\"C_CARNAME\",\"C_COLOR\",\"RadioID\") 
          values  
          ('$car_sn','$fp_regis','$fp_car','$fp_mar','$fp_band','$fp_color','$fp_radio')";
$result_fc=pg_query($in_sql_fc);		  
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



$pfinal_carid=$car_sn;

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


$gencode="select generate_id('$dat',$officeid,4)";

$resid=pg_query($db_connect,$gencode);

$residno=pg_fetch_result($resid,0);

echo $gencode;


//saveIDNO to fp

$rescf=$_POST["f_comefrom"];

    $gen_ref1=pg_query("select gen_encode_ref1('$residno')");
	$res_gen1=pg_fetch_result($gen_ref1,0);
	
	   $resstnumber=strlen($fp_car);         
		$var_cnumber=substr($fp_car,$resstnumber-9,9);
	

$res_cfrom=$_POST["f_comefrom"];

/*
$ins_fp="insert into \"FpOutCus\" (\"IDNO\",\"CusID\",\"P_STDATE\",
                             \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",\"P_BEGINX\" ,
							  \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"ComeFrom\",
							  \"P_CLDATE\",\"P_StopVat\",\"P_ACCLOSE\",
							  \"TranIDRef1\",\"TranIDRef2\"
							 ) 
                      values  
                            ('$residno','$pfinal_cusid','$fp_signdate',
							  '0','0','0','$fp_signdate','0','0',
							  '0','0',FALSE,'1','$pfinal_carid','$rescf',
							  '$fp_signdate',FALSE,TRUE,
							  '$res_gen1','$var_cnumber' 
							 )";
							 
*/
$ins_fp="insert into \"FpOutCus\"
         (\"IDNO\",\"CusID\",\"CarID\",\"OCRef1\",\"OCRef2\",\"ACStartDate\")
		  values
		  ('$residno','$pfinal_cusid','$pfinal_carid','$res_gen1','$var_cnumber','$fp_signdate')";						 
$result_fp=pg_query($ins_fp);






//saveIDNO to contactCus

/*
$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") 
                  values  
                 ('$residno',0,'$pfinal_cusid')";
$result_cc=pg_query($ins_cc);
*/


if(($ss_cusid==1) or ($ss_carid==1))
{

 if(($result_fp) or ($result_fa1) or  ($result_fc) or ($res_fname) )
  {
   pg_query("COMMIT");
   echo "บันทึกข้อมูลเรียบร้อย รอสักครู่ .";
   echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";

  }
  else
  {
 	pg_query("ROLLBACK");
	echo "cusid = 1 มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่";
 	echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";
  }

}
else 
{
	if(($result_fp) and ($result_fa1) and  ($result_fc) and ($result_cc) or ($res_fname))
	{
 	  pg_query("COMMIT");
  	  echo "บันทึกข้อมูลเรียบร้อย รอสักครู่ .";
  	  echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";
	
	}
	else
	{
 	  pg_query("ROLLBACK");
 	  echo "มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่".$residno;
	  if($error_check != ""){echo "<br>".$error_check;}
 	  echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";
	} 
}
?>
