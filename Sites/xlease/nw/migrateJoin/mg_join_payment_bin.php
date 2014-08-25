<?php
$excel = $_REQUEST[excel];

set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$num_add = 0;
?>
<?php if($excel==1)header("Content-Type: application/vnd.ms-excel");
if($excel==1)header('Content-Disposition: attachment; filename="join_main_ck.xls"'); ?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=utf-8" />

</HEAD><BODY>
<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>


<br>

    <fieldset><legend>
    <h3> Migrate Join Payment ระบบเก่า ไป Xlease </h3>
    </legend>   

<?php			
$test_sql=pg_query("Truncate Table public.ta_join_payment_bin "); //ลบข้อมูลในตารางเดิม
$rowtest=pg_num_rows($test_sql);

$test_sql=pg_query("select  ta_join_payment_id, car_license, contract_id,cpro_name, 
            start_pay_date, pay_date, pay_type, amount, amount_balance, 
            amount_net, amount_cash, amount_transfer, amount_cheque, amount_cs_cheque, 
            amount_update_m, amount_discount, amount_vat, amount_wh_tax, 
            cash_note, transfer_note, cheque_note, cs_cheque_note, update_m_note, 
            discount_note, tax_wh_note, status_tax_wh, user_tax_wh, amount_month, 
            period_date, expire_date, pay, deduct_fin, pay_ar, note, change_pay_type, 
            payment_image, datetime, update_datetime, users, deleted from ta_tal_1r4_mg.\"ta_join_payment_bin\" ");
$rowtest=pg_num_rows($test_sql);
$seq=1;
while($result=pg_fetch_array($test_sql))
{
	$car_license=trim($result["car_license"]); //ตัดช่องว่าง ข้างหน้าและข้างหลังออก
	$contract_id=trim($result["contract_id"]);
	$ta_join_payment_id=$result["ta_join_payment_id"];
	$cpro_name=trim($result["cpro_name"]); //ชื่อ-นามสกุล
	list($A_NAME,$A_SIRNAME)=explode(" ",$cpro_name,2); //แยกช่องว่าง เพื่อตัดชื่อ กับนามสกุล  แยกเป็น 2 คำ เท่านั้น ถึงแม้จะมีช่องว่างมากกว่า 1 
	$A_SIRNAME = trim($A_SIRNAME);
	
	$A_NAME = str_replace("นาย",'',$A_NAME) ;
	$A_NAME = str_replace("นางสาว",'',$A_NAME) ;
	$A_NAME = str_replace("นาง",'',$A_NAME) ;
	
	$start_pay_date=$result["start_pay_date"];
	//$car_month=$result["car_month"];
	$start_contract_date=$result["start_contract_date"];

										$pay_date=$result["pay_date"];
										$pay_type=$result["pay_type"];
										$amount=$result["amount"];
										$amount_balance=$result["amount_balance"];
										$amount_net=$result["amount_net"];
										
										$amount_cash=$result["amount_cash"];
										$amount_transfer=$result["amount_transfer"];
										$amount_cheque=$result["amount_cheque"];
										$amount_cs_cheque=$result["amount_cs_cheque"];
										$amount_update_m=$result["amount_update_m"];
										$amount_discount=$result["amount_discount"];
											$amount_vat=$result["amount_vat"];
										$amount_wh_tax=$result["amount_wh_tax"];
										$cash_note=$result["cash_note"];
										$transfer_note=$result["transfer_note"];
										$cheque_note=$result["cheque_note"];
										
	$cs_cheque_note=$result["cs_cheque_note"];
										$update_m_note=$result["update_m_note"];
										$discount_note=$result["discount_note"];
										$tax_wh_note=$result["tax_wh_note"];
	$status_tax_wh=$result["status_tax_wh"];
										$user_tax_wh=$result["user_tax_wh"];
										$amount_month=$result["amount_month"];
										$period_date=$result["period_date"];
										$expire_date=$result["expire_date"];
										$pay=$result["pay"];
										$deduct_fin=$result["deduct_fin"];
										$pay_ar=$result["pay_ar"];
										$note=$result["note"];
										$change_pay_type=$result["change_pay_type"];
										$payment_image=$result["payment_image"];									

										$update_datetime=$result["update_datetime"];
										$datetime=$result["datetime"];
										$users =$result["users"];
										$deleted =$result["deleted"];
	//แปลง user			
	list($users_id,$users_n) = explode("[",$users);	
	
	list($users_f,$users_l) = explode(" ",$users_n,2);	
	//$users_f = str_replace("[",'',$users_f) ;
	
	$users_l = str_replace("]",'',$users_l) ;								
	//echo $users."-".$users_l."<br>";		
			$test_sql5=pg_query("select \"id_user\" from public.\"fuser\" where (\"fname\" like '%$users_f%' and \"lname\" like '%$users_l%') or (\"fname\" like '%$users_f $users_l%') ");	
	$rowtest5=pg_num_rows($test_sql5);	
	if($rowtest5==0){
		
		if($users_f=="นฤกร")
	$id_user = '034';
	else if($users_f=="Admin")
	$id_user = '001';
	else if($users_f=="หัทยา")
	$id_user = '049';
	else {
		echo $users_f." ".$users_l."<br><br>";
	$id_user = '001';	
	}
	
	
	}else{
				while($result5=pg_fetch_array($test_sql5))
{
	$id_user =$result5["id_user"];
}
		
	}
					list($car_license1,$car_license2)=explode("/",$car_license,2); //แยกช่องว่าง เพื่อตัดชื่อ กับนามสกุล  แยกเป็น 2 คำ เท่านั้น ถึงแม้จะมีช่องว่างมากกว่า 1 
					
$car_license1  =trim($car_license1);
$car_license_seq = trim($car_license2); // ลำดับการโอน ซื้อคืน ขาย..

if(!is_numeric($car_license_seq)){ 
if($car_license_seq	=="")$car_license_seq=0;
else {
	$car_license1 = $car_license;	
$car_license_seq=0;
 }
}

							
		$test_sql2=pg_query("select \"id\" from ta_tal_1r4_mg.\"ta_join_main\" where trim(\"car_license\") = '$car_license' order by id desc limit 1");
	$r2=pg_num_rows($test_sql2);
	
	if($r2>0 ){
				if($result2=pg_fetch_array($test_sql2))
{
	$id_main=$result2["id"]; //รหัสรถยนต์ใน Fc
}
	}
	else {
		
		$test_sql2=pg_query("select \"id\" from ta_tal_1r4_mg.\"ta_join_main\" where trim(\"contract_id\") = '$contract_id' order by id desc limit 1");
	$r2=pg_num_rows($test_sql2);
	
	if($r2>0 ){
				if($result2=pg_fetch_array($test_sql2))
{
	$id_main=$result2["id"]; //รหัสรถยนต์ใน Fc
}}else{
		echo $car_license." ".$contract_id." $ta_join_payment_id<br>";
		$id_main='0'; 
}
	}

if($start_pay_date=="")$start_pay_date_sql="NULL";
	else $start_pay_date_sql = "'$start_pay_date'";
		
	if($pay_date=="")$pay_date_sql="NULL";
	else $pay_date_sql = "'$pay_date'";			
		
if($expire_date=="")$expire_date_sql="NULL";
	else $expire_date_sql = "'$expire_date'";	
	
	if($period_date=="")$period_date_sql="NULL";
	else $period_date_sql = "'$period_date'";	
	
	if($note=="")$note_sql="NULL";
	else $note_sql = "'$note'";	
	
		if($cash_note=="")$cash_note2="NULL";
	else $cash_note2 = "'$cash_note'";	
	
	if($transfer_note=="")$transfer_note2="NULL";
	else $transfer_note2 = "'$transfer_note'";	
	
	if($cheque_note=="")$cheque_note2="NULL";
	else $cheque_note2 = "'$cheque_note'";	
	
	if($cs_cheque_note=="")$cs_cheque_note2="NULL";
	else $cs_cheque_note2 = "'$cs_cheque_note'";	
	
	if($update_m_note=="")$update_m_note2="NULL";
	else $update_m_note2 = "'$update_m_note'";	
	
	if($discount_note=="")$discount_note2="NULL";
	else $discount_note2 = "'$discount_note'";	
	
	if($tax_wh_note=="")$tax_wh_note2="NULL";
	else $tax_wh_note2 = "'$tax_wh_note'";	
	
	if($user_tax_wh=="")$user_tax_wh2="NULL";
	else $user_tax_wh2 = "'$user_tax_wh'";	
	
	if($update_datetime=="")$update_datetime_sql="NULL";
	else $update_datetime_sql = "'$update_datetime'";	
	
$vat_percent = 7; //%Vat ระบบเก่า	

if($pay=="เงินสด")$pay = "CA";				   
if($pay=="เช็ค")$pay = "CQ";
if($pay=="เงินโอน")$pay = "TR";

if($amount_cash!=0)$pay = "CA";				   
if($amount_cheque!=0)$pay = "CQ";
if($amount_cs_cheque!=0)$pay = "CQ";
if($amount_transfer!=0)$pay = "TR";
if($amount_update_m!=0)$pay = "UD";

	$test_sql4="INSERT INTO public.ta_join_payment_bin(
            id_main ,ta_join_payment_id, car_license,car_license_seq,IDNO,cpro_name,
            start_pay_date,  pay_date, pay_type, amount, amount_balance, 
            amount_net, amount_discount,vat_percent, amount_vat, amount_wh_tax, 
            cash_note, transfer_note, cheque_note, cs_cheque_note, update_m_note, 
            discount_note, tax_wh_note, status_tax_wh, user_tax_wh, amount_month, 
            period_date, expire_date, pay, deduct_fin, pay_ar, note, change_pay_type, 
            payment_image, create_datetime, update_datetime, update_by, deleted)
    VALUES  ('$id_main','$ta_join_payment_id','$car_license1','$car_license_seq', '$contract_id','$cpro_name', $start_pay_date_sql,$pay_date_sql, '$pay_type', '$amount', '$amount_balance', '$amount_net', '$amount_discount','$vat_percent', '$amount_vat', '$amount_wh_tax', $cash_note2, $transfer_note2, $cheque_note2, $cs_cheque_note2, $update_m_note2, $discount_note2, $tax_wh_note2, '$status_tax_wh', $user_tax_wh2, '$amount_month', $period_date_sql, $expire_date_sql, '$pay', '$deduct_fin', '$pay_ar', $note_sql, '$change_pay_type', '$payment_image', '$datetime', $update_datetime_sql, '$id_user', '$deleted')";
			if($rowtest4=pg_query($test_sql4))
			{$num_add ++;}
			else
			{
				$status++;
				echo $test_sql4."<br><br>";
			}
	
	} 
if($status == 0){
   pg_query("COMMIT");
   echo "<br>บันทึกข้อมูลจำนวน $num_add Record เรียบร้อยแล้ว";
}else{
  pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>
</fieldset>

</BODY>

</HTML>

