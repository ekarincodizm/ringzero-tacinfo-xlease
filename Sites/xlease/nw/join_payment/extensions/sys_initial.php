<?php

// sys_initial.php - ไฟล์สำหรับ เชื่อมต่อฐานข้อมูล ta-edata
// Created by Kanitchet Vaiassava
// Last date modified : 31-07-2010 (01:06)


// เชื่อมต่อกับฐานข้อมูลของ MySQL
//$connect_db = mysql_connect("172.16.2.251","root","devbase")or die ("Cannot connect to MySQL Database");

// เชื่อมต่อกับฐานข้อมูลของ DATABASE TA-EDATA
//mysql_selectdb("ta_tal_1r4_mg",$connect_db); 


// Set variables DATABASE TABLE
$dbtb_asset_deeds  = "asset_deeds";
$dbtb_asset_deeds_default  = "asset_deeds_default";
$dbtb_form_cprofile = "form_cprofile";
$dbtb_contracts_mortgage = "contracts_mortgage";
$dbtb_contracts_mortgage_default = "contracts_mortgage_default";
$dbtb_gen_address = "gen_address";
$dbtb_gen_address_bin = "gen_address_bin";
$dbtb_gen_contacts = "gen_contacts";
$dbtb_gen_customers = "gen_customers";
$dbtb_info_checker = "info_checker";
$dbtb_receipt_mortgage = "receipt_mortgage";
$dbtb_ta_images = "ta_images";
$dbtb_ta_logs = "ta_logs";
$dbtb_ta_rights = "ta_rights";
$dbtb_ta_rights_default = "ta_rights_default";
$dbtb_ta_rights_name  = "ta_rights_name";
$dbtb_ta_setting  = "ta_setting";
$dbtb_ta_users = "ta_users";
$dbtb_gen_customers_default = "gen_customers_default";
$dbtb_asset_insurance_default   = "asset_insurance_default";
$dbtb_asset_insurance = "asset_insurance";
$dbtb_car_contract = "car_contract";
$dbtb_inform_hire_purchase_default = "inform_hire_purchase_default";
$dbtb_inform_hire_purchase = "inform_hire_purchase";
$dbtb_car_check = "car_check";
$dbtb_car_check_default = "car_check_default";
$dbtb_tal_hp_contract = "car_contract";
$dbtb_car_insurance = "car_insurance";
$dbtb_hire_purchase_check ="hire_purchase_check";
$dbtb_book_sale_enquiry="book_sale-enquiry";
$dbtb_ta_users_default ="ta_users_default";
$dbtb_ta_join_payment = "ta_join_payment";
$dbtb_ta_join_payment_bin = "ta_join_payment_bin";
$dbtb_sms_promotion = "sms_promotion";
$dbtb_ta_proxy = "ta_proxy";
$dbtb_ta_proxy_mgt = "ta_proxy_mgt";
$dbtb_ta_proxy_mgt_default = "ta_proxy_mgt_default";
$dbtb_ta_fine_payment = "ta_fine_payment";
$dbtb_ta_com_default  = "ta_com_default";
$dbtb_ta_com_mgt  = "ta_com_mgt";
$dbtb_ta_it_problem  = "ta_it_problem";
$dbtb_ta_it_problem_report  = "ta_it_problem_report";
$dbtb_ta_it_device  ="ta_it_device";
$dbtb_ta_users_default   = "ta_users_default";
$dbtb_ta_users_default2   = "ta_users_default2";


$dbtb_ta_com_mgt_bin  = "ta_com_mgt_bin";
$dbtb_ta_it_problem_bin = "ta_it_problem_bin";
$dbtb_ta_it_problem_report_bin  = "ta_it_problem_report_bin";
$dbtb_ta_it_device_bin  ="ta_it_device_bin";

$dbtb_ta_e_document_default = "ta_e_document_default"; 
$dbtb_ta_e_document = "ta_e_document";
$dbtb_ta_e_document_bin = "ta_e_document_bin";
$dbtb_ta_join_main  = "ta_join_main" ;  
$dbtb_ta_join_main_bin  = "ta_join_main_bin" ;
$dbtb_ta_sys_receipt_num  = "ta_sys_receipt_num" ;
$dbtb_sys_current_rate = "sys_current_rate" ;
$dbtb_gen_address_default = 'gen_address_default';
$dbtb_ta_it_after_service  = "ta_it_after_service" ;
// ชื่อ Templated ที่ใช้
$sys_temp = 'default';

// ตำแหน่ง ต่างๆ
$lo_ext_temp = '../../templates/';
$lo_index_temp = 'templates/';
$lo_ext_current_temp = $lo_ext_temp.$sys_temp.'/';
$lo_index_current_temp = $lo_index_temp.$sys_temp.'/';

?>