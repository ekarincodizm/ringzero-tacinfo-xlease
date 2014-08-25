<?php
include("../../config/config.php");
include("../function/checknull.php");
$db1="ta_mortgage_datastore";

pg_query('BEGIN');

$sql =  mysql_query("select * from $db1.vcontractconsumer");
$status = 0;

while($result = mysql_fetch_array($sql)){

	$contract_loans_code = $result['contract_loans_code'];
	$customer_code = $result['customer_code'];
	$contract_loans_startdate = $result['contract_loans_startdate'];
	$appv_credit_money = $result['appv_credit_money'];
	$contract_loans_minpay = $result['contract_loans_minpay'];
	$customer_lastname_thai = $result['customer_lastname_thai'];
	$customer_firstname_thai = $result['customer_firstname_thai'];
	$status_code = $result['status_code'];
	$cus_birth_date = $result['cus_birth_date'];
	$sex_code = $result['sex_code'];
	$prefix_name_thai = $result['prefix_name_thai'];
	$customer_maid = $result['customer_maid'];
	$customer_type_card_code = $result['customer_type_card_code'];
	$cus_idnum = $result['cus_idnum'];
	$address = $result['address'];
	$addr_district = $result['addr_district'];
	$amphur_name = $result['amphur_name'];
	$province_name = $result['province_name'];
	$customer_nationality_code = $result['customer_nationality_code'];
	$addr_country = $result['addr_country'];
	$zip_code = $result['zip_code'];
	$customer_mobile = $result['customer_mobile'];
	$coborrow = $result['coborrow'];
	$ownership_indicator = $result['ownership_indicator'];
	$appv_month = $result['appv_month'];
	$account_status_default_code = $result['account_status_default_code'];
	
	$contract_loans_code = checknull($contract_loans_code);
	$customer_code = checknull($customer_code);
	$contract_loans_startdate = checknull($contract_loans_startdate);
	$appv_credit_money = checknull($appv_credit_money);
	$contract_loans_minpay = checknull($contract_loans_minpay);
	$customer_lastname_thai = checknull($customer_lastname_thai);
	$customer_firstname_thai = checknull($customer_firstname_thai);
	$status_code = checknull($status_code);
	$cus_birth_date = checknull($cus_birth_date);
	$sex_code = checknull($sex_code);
	$prefix_name_thai = checknull($prefix_name_thai);
	$customer_maid = checknull($customer_maid);
	$customer_type_card_code = checknull($customer_type_card_code);
	$cus_idnum = checknull($cus_idnum);
	$address = checknull($address);
	$addr_district = checknull($addr_district);
	$amphur_name = checknull($amphur_name);
	$province_name = checknull($province_name);
	$customer_nationality_code = checknull($customer_nationality_code);
	$addr_country = checknull($addr_country);
	$zip_code = checknull($zip_code);
	$customer_mobile = checknull($customer_mobile);
	$coborrow = checknull($coborrow);
	$ownership_indicator = checknull($ownership_indicator);
	$appv_month = checknull($appv_month);
	$account_status_default_code = checknull($account_status_default_code);
	

	
		$sql_insert = "INSERT INTO contractconsumer(
            contract_loans_code, customer_code, contract_loans_startdate, 
            appv_credit_money, contract_loans_minpay, customer_lastname_thai, 
            customer_firstname_thai, status_code, cus_birth_date, sex_code, 
            prefix_name_thai, customer_maid, customer_type_card_code, cus_idnum, 
            address, addr_district, amphur_name, province_name, customer_nationality_code, 
            addr_country, zip_code, customer_mobile, coborrow, ownership_indicator, 
            appv_month, account_status_default_code)
    VALUES ($contract_loans_code, $customer_code, $contract_loans_startdate, 
            $appv_credit_money, $contract_loans_minpay, $customer_lastname_thai, 
            $customer_firstname_thai, $status_code, $cus_birth_date, $sex_code, 
            $prefix_name_thai, $customer_maid, $customer_type_card_code, $cus_idnum, 
            $address, $addr_district, $amphur_name, $province_name, $customer_nationality_code, 
            $addr_country, $zip_code, $customer_mobile, $coborrow, $ownership_indicator, 
           $appv_month, $account_status_default_code)";
		   
		  $re_insert = pg_query($sql_insert); 
			  if($re_insert){
			  }else{	  
				$status++;
			  }
		  

}

 if($status == 0){		  
		  pg_query('COMMIT');
		  echo "success";
 }else{		  
		  pg_query('ROLLBACK');
		  echo "error";
 }

?>