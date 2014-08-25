<?php
session_start();
include("../config/config.php");

$pfinal_carid=$_POST["final_carid"];
$pfinal_cusid=$_POST["final_cusid"];

/*
$qry_asset=pg_query("select * from \"TypeOfAsset\" WHERE asset_type='1' ");
$res_ast=pg_fetch_array($qry_asset);
$fp_preid=$res_ast["asset_preid"];

$qry_docid=pg_query("select * from \"ContactID\" ");
$res_docid=pg_fetch_array($qry_docid);
if($fp_preid==1)
{
 $fp_docid=$res_docid["carid"];
}
else 
{
 $fp_docid=$res_docid["gasid"];
}


 function insertZero($inputValue , $digit )
		{
			$str = "" . $inputValue;
			while (strlen($str) < $digit)
			{
				$str = "0" . $str;
			}
			return $str;
        }

		$a = $fp_docid+1;
echo $pre_idsn=$fp_preid.insertZero($a , 5);
*/



$fp_signdate=$_POST["signDate"];
$fp_downprice=$_POST["downprice"];
$fp_count_payment=$_POST["count_payment"];
$fp_price_payment=$_POST["price_payment"];
$fp_first_price=$_POST["first_price"];
$fp_acc_first_price=$_POST["acc_first_price"];
$fp_st_datepayment=$_POST["st_datepayment"];
 

$officeid=$_SESSION["av_officeid"];

$dat=date("Y/m/d");



$gencode="select generate_id('$dat', $officeid,1)";

$resid=pg_query($db_connect,$gencode);

$residno=pg_fetch_result($resid,0);




$amtvat_down=pg_query("select amt_before_vat($fp_downprice)");
$res_vatofdown=pg_fetch_result($amtvat_down,0);
$res_vatdown=$fp_downprice-$res_vatofdown;




$amtvat_month=pg_query("select amt_before_vat($fp_price_payment)");
$res_vatmonth=pg_fetch_result($amtvat_month,0);
$res_p_vat=$fp_price_payment-$res_vatmonth;



//saveIDNO to fp



$ins_fp="insert into \"Fp\" (\"IDNO\",\"CusID\",\"P_STDATE\",
                             \"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",\"P_BEGINX\" ,
							  \"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id
							 ) 
                      values  
                            ('$residno','$pfinal_cusid','$fp_signdate',
							  '$res_vatofdown','$fp_count_payment','$res_vatmonth','$fp_st_datepayment','$fp_first_price','$fp_acc_first_price','$res_vatdown','$res_p_vat',FALSE,'1','$pfinal_carid')";
if($result_fp=pg_query($ins_fp))
 {
  $status_fp ="OK".$ins_fp;
 }
 else
 {
  $status_fp ="error insert Re".$ins_fp;
 } 
// echo $status_fp."<br>";

//saveIDNO to contactCus

$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") 
                  values  
                 ('$residno',0,'$pfinal_cusid')";
if($result_cc=pg_query($ins_cc))
 {
  $status_cc ="OK".$ins_cc;
 }
 else
 {
  $status_cc ="error insert Re".$ins_cc;
 } 
//echo "<br>".$status_cc;
 

echo "<meta http-equiv=\"refresh\" content=\"0;URL=../list_menu.php>";

?>