<?php

// sys_variables.php - ไฟล์สำหรับ กำหนดค่า variables ต่างๆ เพื่อใช้ร่วมกัน
// Created by Kanitchet Vaiassava
// Last date modified : 31-07-2010

// Set ค่าเริ่มต้นที่จำเป็น
//if($_SESSION['ck_load_rights']==''){
require_once("sys_functions.php");
//require_once("fpdf16/fpdf_writehtml.php");

// วันเวลาปัจจุบัน
$info_currentdatetimesql = strtotime(date("Y-m-d H:i:s"));
$info_currentdatesql = date("Y-m-d");
$info_currentdatetimesql2 = date("Y-m-d H:i:s");
// Set ให้ MySQL รองรับข้อมูลภาษาไทยได้
//pg_query("SET character_set_results=utf8");
//pg_query("SET character_set_client=utf8");
//pg_query("SET character_set_connection=utf8");
/*
$query =	"SELECT * 
			FROM $dbtb_ta_rights 
			WHERE users_id = '".$_SESSION['user_id']."'";
$sql_query = pg_query($query);

while($sql_row = pg_fetch_array($sql_query))
{
	$rights_addr_add = $sql_row['addr_add'];
	$rights_addr_view = $sql_row['addr_view'];
	$rights_addr_edit = $sql_row['addr_edit'];
	$rights_addr_del = $sql_row['addr_del'];
	
	$rights_hire_purchase_check_add = $sql_row['hire_purchase_check_add'];
	$rights_hire_purchase_check_view = $sql_row['hire_purchase_check_view'];
	$rights_hire_purchase_check_edit = $sql_row['hire_purchase_check_edit'];
	$rights_hire_purchase_check_del = $sql_row['hire_purchase_check_del'];

	$rights_users_add = $sql_row['users_add'];
	$rights_users_view = $sql_row['users_view'];
	$rights_users_edit = $sql_row['users_edit'];
	$rights_users_del = $sql_row['users_del'];
	
	$rights_report_add = $sql_row['report_add'];
	$rights_report_view = $sql_row['report_view'];
	$rights_report_edit = $sql_row['report_edit'];
	$rights_report_del = $sql_row['report_del'];
	
	$rights_rights_add = $sql_row['rights_add'];
	$rights_rights_view = $sql_row['rights_view'];
	$rights_rights_edit = $sql_row['rights_edit'];
	$rights_rights_del = $sql_row['rights_del'];	
	
	$rights_car_contract_add = $sql_row['car_contract_add'];
	$rights_car_contract_view = $sql_row['car_contract_view'];
	$rights_car_contract_edit = $sql_row['car_contract_edit'];
	$rights_car_contract_del = $sql_row['car_contract_del'];
	
	$rights_book_sale_enquiry_add = $sql_row['book_sale_enquiry_add'];
	$rights_book_sale_enquiry_view = $sql_row['book_sale_enquiry_view'];
	$rights_book_sale_enquiry_edit = $sql_row['book_sale_enquiry_edit'];
	$rights_book_sale_enquiry_del = $sql_row['book_sale_enquiry_del'];
	

	
	$rights_cpro_add = $sql_row['cpro_add'];
	$rights_cpro_view = $sql_row['cpro_view'];
	$rights_cpro_edit = $sql_row['cpro_edit'];
	$rights_cpro_del = $sql_row['cpro_del'];
	
	$rights_checker_add = $sql_row['checker_add'];
	$rights_checker_view = $sql_row['checker_view'];
	$rights_checker_edit = $sql_row['checker_edit'];
	$rights_checker_del = $sql_row['checker_del'];
	
	$rights_cus_add = $sql_row['cus_add'];
	$rights_cus_view = $sql_row['cus_view'];
	$rights_cus_edit = $sql_row['cus_edit'];
	$rights_cus_del = $sql_row['cus_del'];
	
	$rights_car_insurance_add = $sql_row['car_insurance_add'];
	$rights_car_insurance_view = $sql_row['car_insurance_view'];
	$rights_car_insurance_edit = $sql_row['car_insurance_edit'];
	$rights_car_insurance_del = $sql_row['car_insurance_del'];
	
	$rights_car_check_add = $sql_row['car_check_add'];
	$rights_car_check_view = $sql_row['car_check_view'];
	$rights_car_check_edit = $sql_row['car_check_edit'];
	$rights_car_check_del = $sql_row['car_check_del'];
	
	$rights_car_contract_image_add = $sql_row['car_contract_image_add'];
	$rights_car_contract_image_view = $sql_row['car_contract_image_view'];
	$rights_car_contract_image_edit = $sql_row['car_contract_image_edit'];
	$rights_car_contract_image_del = $sql_row['car_contract_image_del'];
	
	$rights_sms_promotion_add = $sql_row['sms_promotion_add'];
	$rights_sms_promotion_view = $sql_row['sms_promotion_view'];
	$rights_sms_promotion_edit = $sql_row['sms_promotion_edit'];
	$rights_sms_promotion_del = $sql_row['sms_promotion_del'];
	
	$rights_proxy_add = $sql_row['proxy_add'];
	$rights_proxy_view = $sql_row['proxy_view'];
	$rights_proxy_edit = $sql_row['proxy_edit'];
	$rights_proxy_del = $sql_row['proxy_del'];
	
	$rights_fine_add = $sql_row['fine_add'];
	$rights_fine_view = $sql_row['fine_view'];
	$rights_fine_edit = $sql_row['fine_edit'];
	$rights_fine_del = $sql_row['fine_del'];
	
	$rights_com_add = $sql_row['com_add'];
	$rights_com_view = $sql_row['com_view'];
	$rights_com_edit = $sql_row['com_edit'];
	$rights_com_del = $sql_row['com_del'];
	
	$rights_com_pro_add = $sql_row['com_pro_add'];
	$rights_com_pro_view = $sql_row['com_pro_view'];
	$rights_com_pro_edit = $sql_row['com_pro_edit'];
	$rights_com_pro_del = $sql_row['com_pro_del'];
	
	$rights_com_it_add = $sql_row['com_it_add'];
	$rights_com_it_view = $sql_row['com_it_view'];
	$rights_com_it_edit = $sql_row['com_it_edit'];
	$rights_com_it_del = $sql_row['com_it_del'];
	
	$rights_e_doc_add = $sql_row['e_doc_add'];
	$rights_e_doc_view = $sql_row['e_doc_view'];
	$rights_e_doc_edit = $sql_row['e_doc_edit'];
	$rights_e_doc_del = $sql_row['e_doc_del'];
	
	$rights_doc_type_set_add = $sql_row['doc_type_set_add'];
	$rights_doc_type_set_view = $sql_row['doc_type_set_view'];
	$rights_doc_type_set_edit = $sql_row['doc_type_set_edit'];
	$rights_doc_type_set_del = $sql_row['doc_type_set_del'];
	
	$rights_doc_approve_add = $sql_row['doc_approve_add'];
	$rights_doc_approve_view = $sql_row['doc_approve_view'];
	$rights_doc_approve_edit = $sql_row['doc_approve_edit'];
	$rights_doc_approve_del = $sql_row['doc_approve_del'];
	
	$rights_ta_join_payment_add = $sql_row['ta_join_payment_add'];
	$rights_ta_join_payment_view = $sql_row['ta_join_payment_view'];
	$rights_ta_join_payment_edit = $sql_row['ta_join_payment_edit'];
	$rights_ta_join_payment_del = $sql_row['ta_join_payment_del'];
	
	$rights_ta_join_main_add = $sql_row['ta_join_main_add'];
	$rights_ta_join_main_view = $sql_row['ta_join_main_view'];
	$rights_ta_join_main_edit = $sql_row['ta_join_main_edit'];
	$rights_ta_join_main_del = $sql_row['ta_join_main_del'];
	
	$rights_ta_join_report_add = $sql_row['ta_join_report_add'];
	$rights_ta_join_report_view = $sql_row['ta_join_report_view'];
	$rights_ta_join_report_edit = $sql_row['ta_join_report_edit'];
	$rights_ta_join_report_del = $sql_row['ta_join_report_del'];
	
		$rights_ta_join_update_m_add = $sql_row['ta_join_update_m_add'];
	$rights_ta_join_update_m_view = $sql_row['ta_join_update_m_view'];
	$rights_ta_join_update_m_edit = $sql_row['ta_join_update_m_edit'];
	$rights_ta_join_update_m_del = $sql_row['ta_join_update_m_del'];
	
		$rights_ta_join_discount_add = $sql_row['ta_join_discount_add'];
	$rights_ta_join_discount_view = $sql_row['ta_join_discount_view'];
	$rights_ta_join_discount_edit = $sql_row['ta_join_discount_edit'];
	$rights_ta_join_discount_del = $sql_row['ta_join_discount_del'];
	
	$rights_inform_hp_add = $sql_row['inform_hp_add'];
	$rights_inform_hp_view = $sql_row['inform_hp_view'];
	$rights_inform_hp_edit = $sql_row['inform_hp_edit'];
	$rights_inform_hp_del = $sql_row['inform_hp_del'];
	
		$rights_salary_mgt_add = $sql_row['salary_mgt_add'];
	$rights_salary_mgt_view = $sql_row['salary_mgt_view'];
	$rights_salary_mgt_edit = $sql_row['salary_mgt_edit'];
	$rights_salary_mgt_del = $sql_row['salary_mgt_del'];
	
		$rights_salary_add = $sql_row['salary_add'];
	$rights_salary_view = $sql_row['salary_view'];
	$rights_salary_edit = $sql_row['salary_edit'];
	$rights_salary_del = $sql_row['salary_del'];
	
	$rights_ta_join_main_status_add = $sql_row['ta_join_main_status_add'];
	$rights_ta_join_main_status_view = $sql_row['ta_join_main_status_view'];
	$rights_ta_join_main_status_edit = $sql_row['ta_join_main_status_edit'];
	$rights_ta_join_main_status_del = $sql_row['ta_join_main_status_del'];
	
} 
*/
// Set คำนำหน้ารหัสต่างๆ
$id_gen_address = "ADDR-";
$id_gen_contacts = "CON-";
$id_gen_customers = "CUS-";
$id_ta_users = "EP-";
$id_form_cprofile = "CPRO-";
$id_contracts_mortgage = "CMORT-";
$id_asset_deeds  = "DEED-";
$id_info_checker  = "CHK-";
$id_asset_insurance  = "INS-";
$id_inform_hp  = "IHP-";
$id_result_car_check  = "RCC-";
$id_car_contract  = "OCC-";
$id_ta_join  = "TAJ-";
$id_ta_proxy  = "PRX-";
$id_ta_proxy_mgt  = "PRXC-";
$id_ta_fine_payment  = "FINE-";
$id_ta_com_mgt  = "CM-";
$id_ta_it_problem  = "ITP-";
$id_ta_it_problem_report  = "ITPR-";
$id_ta_e_document = "EDOC-";
$id_ta_join_main ="TAJM-";
$id_ta_it_after_service="ITAS-";

$id_ta_salary ="SLY-";
$cmort_version = '00_01' ; //เวอร์ชั่น การสร้างรหัส cmort_id

// ค่าแปรผันอื่นๆ
$vat_rate = 7; // อัตราภาษีมูลค่าเพิ่มปัจจุบัน
$time_ref = '30';
// Set variables สำหรับ log
$log_pos_login = "หน้าเข้าออกระบบ";
$log_pos_contacts = "หน้าข้อมูลผู้ติดต่อ";
$log_pos_customers = "หน้าข้อมูลลูกค้า";
$log_pos_users = "หน้าข้อมูลพนักงาน";
$log_pos_rights = "หน้าข้อมูลสิทธิ์";
$log_pos_changepass = "หน้าเปลี่ยนรหัสผ่าน";
$log_pos_address = "หน้าข้อมูลที่อยู่";
$log_pos_cpro = "หน้าข้อมูลหนังสือรายละเอียดลูกค้า";
$log_pos_report = "หน้าข้อมูลรายงาน";
$log_pos_mortgage = "หน้าข้อมูลสัญญาจำนองที่ดิน";
$log_pos_deeds = "หน้าข้อมูลสินทรัพย์จำนองที่ดิน";
$log_pos_checker = "หน้าข้อมูลตรวจสอบ";
$log_pos_insur = "หน้าข้อมูลประกันภัย";
$log_pos_finance = "หน้าข้อมูลการเงิน";
$log_pos_sms = "หน้าบริการส่งเสริมการขายSMS";
$log_pos_ta_join_payment = "หน้าจัดการค่าเข้าร่วม";
$log_pos_car_contract = "หน้าเสนอขอทำสัญญาเช่าซื้อรถยนต์";
$log_pos_car_check = "หน้าตรวจสอบสภาพรถยนต์";
$log_pos_car_insurance = "หน้าประกันภัยรถยนต์";
$log_pos_inform_hp = "หน้าผู้ติดต่อแสดงความจำนง";
$log_pos_proxy = "หน้าหนังสือมอบอำนาจ";
$log_pos_fine = "หน้าข้อมูลค่าปรับจราจร";
$log_pos_itpr = "หน้าข้อมูลรายงานการแก้ปัญหาไอที";
$log_pos_itp = "หน้าข้อมูลรายละเอียดปัญหาไอที";
$log_pos_cm = "หน้าข้อมูลรายละเอียดคอมพิวเตอร์";
$log_pos_cm_del = "หน้าลบรายละเอียดคอมพิวเตอร์";
$log_pos_e_doc = "หน้าข้อมูลประกาศ";
$log_pos_e_doc_del = "หน้าลบข้อมูลประกาศ";
$log_pos_e_doc_type = "หน้าตั้งค่าประเภทเอกสาร";
$log_pos_e_doc_approve = "หน้าอนุมัติเอกสาร";
$log_pos_join_main = "หน้าข้อมูลค่าเข้าร่วม main";
$log_pos_ta_join_del = "หน้าลบประวัติการชำระค่าเข้าร่วม";
$log_pos_ta_join_edit = "หน้าแก้ไขประวัติการชำระค่าเข้าร่วม";
$log_pos_ta_join_report = "หน้ารายงานการชำระค่าเข้าร่วม";
$log_pos_itas = "หน้าผลการดำเนินงาน";

$log_action_login_login = "เข้าระบบ";
$log_action_login_logout = "ออกจากระบบ";
$log_action_main_changepass = "เปลี่ยนรหัสผ่าน";

$log_action_contacts_add = "เพิ่มข้อมูลผู้ติดต่อ";
$log_action_contacts_find = "ค้นหาข้อมูลผู้ติดต่อ";
$log_action_contacts_view = "ดูข้อมูลผู้ติดต่อ";
$log_action_contacts_edit = "แก้ไขข้อมูลผู้ติดต่อ";
$log_action_contacts_del = "ลบข้อมูลผู้ติดต่อ";

$log_action_customers_add = "เพิ่มข้อมูลลูกค้า";
$log_action_customers_find = "ค้นหาข้อมูลลูกค้า";
$log_action_customers_view = "ดูข้อมูลลูกค้า";
$log_action_customers_edit = "แก้ไขข้อมูลลูกค้า";
$log_action_customers_del = "ลบข้อมูลลูกค้า";

$log_action_rights_find = "ค้นหาข้อมูลสิทธิ์พนักงาน";
$log_action_rights_view = "ดูข้อมูลสิทธิ์พนักงาน";
$log_action_rights_edit = "แก้ไขข้อมูลสิทธิ์พนักงาน";

$log_action_users_add = "เพิ่มข้อมูลพนักงาน";
$log_action_users_find = "ค้นหาข้อมูลพนักงาน";
$log_action_users_view = "ดูข้อมูลพนักงาน";
$log_action_users_edit = "แก้ไขข้อมูลพนักงาน";
$log_action_users_del = "เลิกข้อมูลพนักงาน";

$log_action_address_add = "เพิ่มข้อมูลที่อยู่";
$log_action_address_edit = "แก้ไขข้อมูลที่อยู่";
$log_action_address_view = "ดูข้อมูลที่อยู่";
$log_action_address_find = "ค้นหาข้อมูลที่อยู่";

$log_action_cpro_add = "เพิ่มข้อมูลหนังสือรายละเอียดลูกค้า";
$log_action_cpro_edit = "แก้ไขข้อมูลหนังสือรายละเอียดลูกค้า";
$log_action_cpro_view = "ดูข้อมูลหนังสือรายละเอียดลูกค้า";
$log_action_cpro_find = "ค้นหาข้อมูลหนังสือรายละเอียดลูกค้า";

$log_action_report_add = "เพิ่มข้อมูลรายงาน";
$log_action_report_edit = "แก้ไขข้อมูลรายงาน";
$log_action_report_view = "ดูข้อมูลรายงาน";
$log_action_report_find = "ค้นหาข้อมูลรายงาน";

$log_action_mortgage_add = "เพิ่มข้อมูลสัญญาจำนองที่ดิน";
$log_action_mortgage_edit = "แก้ไขข้อมูลสัญญาจำนองที่ดิน";
$log_action_mortgage_view = "ดูข้อมูลสัญญาจำนองที่ดิน";
$log_action_mortgage_find = "ค้นหาข้อมูลสัญญาจำนองที่ดิน";

$log_action_deeds_add = "เพิ่มข้อมูลสินทรัพย์จำนองที่ดิน";
$log_action_deeds_edit = "แก้ไขข้อมูลสินทรัพย์จำนองที่ดิน";
$log_action_deeds_view = "ดูข้อมูลสินทรัพย์จำนองที่ดิน";
$log_action_deeds_find = "ค้นหาข้อมูลสินทรัพย์จำนองที่ดิน";

$log_action_checker_addr_add = "เพิ่มข้อมูลตรวจสอบที่อยู่";
$log_action_checker_deeds_add = "เพิ่มข้อมูลตรวจสอบที่ดิน";
$log_action_checker_cus_add = "เพิ่มข้อมูลตรวจสอบลูกค้า";
$log_action_checker_users_add = "เพิ่มข้อมูลตรวจสอบพนักงาน";
$log_action_checker_cpro_add = "เพิ่มข้อมูลตรวจสอบหนังสือรายละเอียดลูกค้า";
$log_action_checker_cmort_add = "เพิ่มข้อมูลตรวจสอบสัญญาจำนองที่ดิน";

$log_action_checker_addr_edit = "แก้ไขข้อมูลตรวจสอบที่อยู่";
$log_action_checker_deeds_edit = "แก้ไขข้อมูลตรวจสอบที่ดิน";
$log_action_checker_cus_edit = "แก้ไขข้อมูลตรวจสอบลูกค้า";
$log_action_checker_users_edit = "แก้ไขข้อมูลตรวจสอบพนักงาน";
$log_action_checker_cpro_edit = "แก้ไขข้อมูลตรวจสอบหนังสือรายละเอียดลูกค้า";
$log_action_checker_cmort_edit = "แก้ไขข้อมูลตรวจสอบสัญญาจำนองที่ดิน";

$log_action_checker_addr_view = "ดูข้อมูลตรวจสอบที่อยู่";
$log_action_checker_deeds_view = "ดูข้อมูลตรวจสอบที่ดิน";
$log_action_checker_cus_view = "ดูข้อมูลตรวจสอบลูกค้า";
$log_action_checker_users_view = "ดูข้อมูลตรวจสอบพนักงาน";
$log_action_checker_cpro_view = "ดูข้อมูลตรวจสอบหนังสือรายละเอียดลูกค้า";
$log_action_checker_cmort_view = "ดูข้อมูลตรวจสอบสัญญาจำนองที่ดิน";

$log_action_checker_update_lock = "ยกเลิกสิทธิ์ในการแก้ไขข้อมูลตรวจสอบ";
$log_action_checker_update_lock_no = "ให้สิทธิ์ในการแก้ไขข้อมูลตรวจสอบ";

$log_action_checker_add = "มอบหมายงานตรวจสอบ";

$log_action_insur_add = "เพิ่มข้อมูลประกันภัย";
$log_action_insur_edit = "แก้ไขข้อมูลประกันภัย";
$log_action_insur_view = "ดูข้อมูลประกันภัย";
$log_action_insur_find = "ค้นหาข้อมูลประกันภัย";

$log_action_finance_add = "เพิ่มข้อมูลการเงิน";
$log_action_finance_edit = "แก้ไขข้อมูลการเงิน";
$log_action_finance_view = "ดูข้อมูลการเงิน";
$log_action_finance_find = "ค้นหาข้อมูลการเงิน";

$log_action_sms_add = "เพิ่มเบอร์โทรและชื่อลูกค้า";
$log_action_sms_check = "ตรวจสอบเบอร์โทรศัพท์ลูกค้า";

$log_action_ta_join_payment_add = "เพิ่มข้อมูลประวัติการชำระค่าเข้าร่วม";
$log_action_ta_join_payment_edit = "แก้ไขข้อมูลประวัติการชำระค่าเข้าร่วม";
$log_action_ta_join_payment_view = "ดูข้อมูลประวัติการชำระค่าเข้าร่วม";
$log_action_ta_join_payment_find = "ค้นหาข้อมูลประวัติการชำระค่าเข้าร่วม";
$log_action_ta_join_payment_del  = "ลบประวัติการชำระค่าเข้าร่วม";

$log_action_car_contract_add = "เพิ่มข้อมูลเสนอขอทำสัญญาเช่าซื้อรถยนต์";
$log_action_car_contract_edit = "แก้ไขข้อมูลเสนอขอทำสัญญาเช่าซื้อรถยนต์";
$log_action_car_contract_view = "ดูข้อมูลเสนอขอทำสัญญาเช่าซื้อรถยนต์";
$log_action_car_contract_find = "ค้นหาข้อมูลเสนอขอทำสัญญาเช่าซื้อรถยนต์";

$log_action_car_check_add = "เพิ่มข้อมูลตรวจสอบสภาพรถยนต์";
$log_action_car_check_edit = "แก้ไขข้อมูลตรวจสอบสภาพรถยนต์";
$log_action_car_check_view = "ดูข้อมูลตรวจสอบสภาพรถยนต์";
$log_action_car_check_find = "ค้นหาข้อมูลตรวจสอบสภาพรถยนต์";

$log_action_car_insurance_add = "เพิ่มข้อมูลประกันภัยรถยนต์";
$log_action_car_insurance_edit = "แก้ไขข้อมูลประกันภัยรถยนต์";
$log_action_car_insurance_view = "ดูข้อมูลประกันภัยรถยนต์";
$log_action_car_insurance_find = "ค้นหาข้อมูลประกันภัยรถยนต์";

$log_action_inform_hp_add = "เพิ่มข้อมูลผู้ติดต่อแสดงความจำนง";
$log_action_inform_hp_edit = "แก้ไขข้อมูลผู้ติดต่อแสดงความจำนง";
$log_action_inform_hp_view = "ดูข้อมูลผู้ติดต่อแสดงความจำนง";
$log_action_inform_hp_find = "ค้นหาข้อมูลผู้ติดต่อแสดงความจำนง";

$log_action_proxy_add = "เพิ่มข้อมูลหนังสือมอบอำนาจ";
$log_action_proxy_edit = "แก้ไขข้อมูลหนังสือมอบอำนาจ";
$log_action_proxy_view = "ดูข้อมูลหนังสือมอบอำนาจ";
$log_action_proxy_find = "ค้นหาข้อมูลหนังสือมอบอำนาจ";
$log_action_proxy_pic_del = "ลบข้อมูลรูปภาพ หนังสือมอบอำนาจ";

$log_action_proxy_mgt_add = "เพิ่มข้อมูลจัดทำหนังสือมอบอำนาจ";
$log_action_proxy_mgt_edit = "แก้ไขข้อมูลจัดทำหนังสือมอบอำนาจ";
$log_action_proxy_mgt_view = "ดูข้อมูลจัดทำหนังสือมอบอำนาจ";
$log_action_proxy_mgt_find = "ค้นหาข้อมูลจัดทำหนังสือมอบอำนาจ";
$log_action_proxy_mgt_pic_del = "ลบข้อมูลรูปภาพ จัดทำหนังสือมอบอำนาจ";

$log_action_fine_add = "เพิ่มข้อมูลค่าปรับจราจร";
$log_action_fine_edit = "แก้ไขข้อมูลค่าปรับจราจร";
$log_action_fine_view = "ดูข้อมูลค่าปรับจราจร";
$log_action_fine_find = "ค้นหาข้อมูลค่าปรับจราจร";
$log_action_fine_pic_del = "ลบข้อมูลรูปภาพรายละเอียดค่าปรับจราจร";
$log_action_fine_send_mail = "ส่งจดหมายค่าปรับจราจร";


$log_action_itpr_add = "เพิ่มข้อมูลการแก้ไขปัญหาไอที";
$log_action_itpr_edit = "แก้ไขข้อมูลรายงานปัญหาไอที";
$log_action_itpr_view = "ดูข้อมูลการแก้ไขปัญหาไอที";
$log_action_itpr_find = "ค้นหาข้อมูลการแก้ไขปัญหาไอที";
$log_action_itpr_pic_del = "ลบข้อมูลรูปภาพการแก้ไขปัญหาไอที";

$log_action_itp_add = "เพิ่มข้อมูลรายละเอียดปัญหาไอทีี";
$log_action_itp_edit = "แก้ไขข้อมูลรายละเอียดปัญหาไอทีี";
$log_action_itp_view = "ดูข้อมูลรายละเอียดปัญหาไอที";
$log_action_itp_find = "ค้นหาข้อมูลรายละเอียดปัญหาไอที";
$log_action_itp_pic_del = "ลบข้อมูลรายละเอียดปัญหาไอทีี";

$log_action_ita_add = "เพิ่มข้อมูลรายละเอียดผลการดำเนินงาน";
$log_action_ita_edit = "แก้ไขข้อมูลรายละเอียดผลการดำเนินงาน";
$log_action_ita_view = "ดูข้อมูลรายละเอียดผลการดำเนินงาน";
$log_action_ita_find = "ค้นหาข้อมูลรายละเอียดผลการดำเนินงาน";


$log_action_cm_add = "เพิ่มข้อมูลรายละเอียดเครื่องคอมพิวเตอร์ ";
$log_action_cm_edit = "แก้ไขข้อมูลรายละเอียดเครื่องคอมพิวเตอร์ ีี";
$log_action_cm_view = "ดูข้อมูลรายละเอียดเครื่องคอมพิวเตอร์ ";
$log_action_cm_find = "ค้นหาข้อมูลรายละเอียดเครื่องคอมพิวเตอร์ ";
$log_action_cm_del = "ลบข้อมูลรายละเอียดเครื่องคอมพิวเตอร์ ";

$log_action_e_doc_add = "เพิ่มข้อมูลประกาศ";
$log_action_e_doc_edit = "แก้ไขข้อมูลประกาศ";
$log_action_e_doc_view = "ดูข้อมูลรายละเอียดประกาศ";
$log_action_e_doc_find = "ค้นหาข้อมูลประกาศ";
$log_action_e_doc_pic_del = "ลบข้อมูลประกาศ";


$log_action_e_doc_type_add = "เพิ่มตั้งค่าประเภทเอกสาร";
$log_action_e_doc_type_edit = "แก้ไขประเภทเอกสาร";
$log_action_e_doc_type_view = "ดูข้อมูลประเภทเอกสาร";
$log_action_e_doc_type_find = "ค้นหาข้อมูลประเภทเอกสาร";
$log_action_e_doc_type_del = "ลบข้อมูลประเภทเอกสาร";

$log_action_e_doc_approve_wait = "รออนุมัติประกาศ";
$log_action_e_doc_approve = "อนุมัติประกาศ";
$log_action_e_doc_disapp  = "ไม่อนุมัติประกาศ";
$log_action_e_doc_cancle  = "ยกเ้ลิกประกาศ";

$log_action_join_main_add = "เพิ่มข้อมูลค่าเข้าร่วม";
$log_action_join_main_edit = "แก้ไขข้อมูลค่าเข้าร่วม";
$log_action_join_main_view = "ดูข้อมูลค่าเข้าร่วม";
$log_action_join_main_find = "ค้นหาข้อมูลค่าเข้าร่วม";
$log_action_join_main_del = "ลบข้อมูลค่าเข้าร่วม";
$log_action_join_main_check ="ตรวจสอบข้อมูลค่าเข้าร่วม";

$log_action_ta_join_report_view  = "ดูรายงานการชำระค่าเข้าร่วม";

//$_SESSION['ck_load_rights']=='1';

//}
?>