<?php

include("../../config/config.php");
include("function_payroll.php");
$id_user = $_REQUEST[id_user];

$datepicker = $_GET['datepicker'];
$yy = $_GET['yy'];
$mm = $_GET['mm'];

$yy_l = $_GET['yy'];
$mm_l = $_GET['mm'];
$ty = $_GET['ty'];
//$yy = '2012';
//$mm = '01';

if($ty ==2){				
				$datepicker = $yy."-".$mm."-" ;
			}
$date_now = Date('Y-m-d');
$app_date = Date('Y-m-d H:i:s');


// setting

//เพศ

if($id_user!="" and $id_user!="ไม่พบข้อมูล" ){

	$c_p_hol = CountPublicHoliday($yy);
	if($c_p_hol==0){echo "<center><font color=red size=\"+2\">ไม่มีข้อมูลวันหยุดประจำปี กรุณาเพิ่มข้อมูล</font><a href=\"holiday_mgt.php\"><u><font color=red size=\"+2\">วันหยุดประจำปี</u></font></a></center>";};
			//ดึงข้อมูลเดือนปัจจุบันมาแสดง
$qry_fr=pg_query("SELECT max_late, late_fine, late_time_start, 
       late_time_end, clock_out, start_work,
       clock_out_sat, att_all, late_time_sp_start,late_time_sp_end, att_all_full_month_rate,week_holiday, 
	   late_time_af,max_late_af,late_fine_af, bf_clock_out1, bf_clock_out_start, 
       bf_clock_out_end, u_sex, bf_clock_out_amt, salary_date_cal_start, 
       sick_leave, sick_leave_remain, vacation_leave, vacation_leave_remain, 
       busi_leave, busi_leave_remain,midday_exp,work_status ,att_cal_month_bef ,set_group_id,hr_payroll_tax_id 
	   FROM \"hr_users_setting\" where user_id='$id_user' and month='$mm' and year='$yy' ");
//ไปแก้หน้า all_cal.php ด้วย
			$nub=pg_num_rows($qry_fr); 
if($nub==0){
	$f_new = 1; //ให้ insert ใหม่
	//ให้ดึงข้อมูล setting เดือนก่อน มาเพิ่มในเดือนปัจจุบัน
				$mm_ins = $mm-1;
			
				$yy_ins = $yy;
				if($mm_ins==0){$mm_ins=12;$yy_ins =$yy_ins-1; }
				//หาเดือนล่าสุดที่มีข้อมูล
					$qry_fr3=pg_query("SELECT max(month) as max_m 
	   FROM \"hr_users_setting\" where user_id='$id_user' and month <= '$mm_ins' and year='$yy_ins' ");
				while($sql_row33=pg_fetch_array($qry_fr3)){
					
					$max_m = $sql_row33['max_m'];
				}
			if($max_m!=""){
				$qry_fr=pg_query("SELECT max_late, late_fine, late_time_start, 
       late_time_end, clock_out, start_work, 
       clock_out_sat, att_all, late_time_sp_start,late_time_sp_end, att_all_full_month_rate,week_holiday, 
	   late_time_af,max_late_af,late_fine_af, bf_clock_out1, bf_clock_out_start, 
       bf_clock_out_end, u_sex, bf_clock_out_amt, salary_date_cal_start, 
       sick_leave, sick_leave_remain, vacation_leave, vacation_leave_remain, 
       busi_leave, busi_leave_remain,midday_exp,work_status,att_cal_month_bef ,set_group_id,hr_payroll_tax_id 
	   FROM \"hr_users_setting\" where user_id='$id_user' and month='$max_m' and year='$yy_ins' ");
	   $nub=pg_num_rows($qry_fr); 
	   	}else{
					
					$nub=0;
					
				}
						//พนักงานใหม่ ให้ดึงข้อมูล Global มา
			if($nub==0){
				$f_new = 1; //ให้ insert ใหม่					
					//ดึงข้อมูลพนักงานจาก Xlease
			$query=pg_query("select u_sex from \"fuser_detail\" where \"id_user\"='$id_user'");
	if($result=pg_fetch_array($query)){

		$u_sex=$result["u_sex"];

	}
			//ตรวจสอบว่าเป็นชายหรือหญิง เพื่อจะดึงข้อมูลของเพศนัน้มา
			if($u_sex=="หญิง"){$hr_users_setting_id = 2;}
			else if($u_sex=="ชาย"){$hr_users_setting_id = 1;}
			
				$qry_fr=pg_query("SELECT max_late, late_fine, late_time_start, 
       late_time_end, clock_out, start_work, 
       clock_out_sat, att_all, late_time_sp_start,late_time_sp_end, att_all_full_month_rate,week_holiday, 
	   late_time_af,max_late_af,late_fine_af, bf_clock_out1, bf_clock_out_start, 
       bf_clock_out_end, u_sex, bf_clock_out_amt, salary_date_cal_start, 
       sick_leave, sick_leave_remain, vacation_leave, vacation_leave_remain, 
       busi_leave, busi_leave_remain,midday_exp,work_status,user_note ,att_cal_month_bef ,set_group_id,hr_payroll_tax_id  
	   FROM \"hr_users_setting\" where id='$hr_users_setting_id' ");
	   
	  
	   $nub=pg_num_rows($qry_fr); 
				
			} //ปิด if($nub==0){2
				
	
	
		}//ปิด if($nub==0){1
		

			if($nub>0){
				while($sql_row4=pg_fetch_array($qry_fr)){

					$max_late = $sql_row4['max_late'];
					$late_fine = $sql_row4['late_fine'];
					$late_time_start = $sql_row4['late_time_start'];
					$late_time_end = $sql_row4['late_time_end'];
					$clock_out = $sql_row4['clock_out'];
					
					$start_work = $sql_row4['start_work'];
					
					$clock_out_sat = $sql_row4['clock_out_sat'];
					$att_all = $sql_row4['att_all'];
					$late_time_sp_start = $sql_row4['late_time_sp_start'];
					$late_time_sp_end = $sql_row4['late_time_sp_end'];
					$att_all_full_month_rate = $sql_row4['att_all_full_month_rate'];
					$week_holiday = $sql_row4['week_holiday'];
					
					$late_time_af = $sql_row4['late_time_af'];
					
					$max_late_af = $sql_row4['max_late_af'];
					$late_fine_af = $sql_row4['late_fine_af'];
					$u_sex = $sql_row4['u_sex'];
					$bf_clock_out1= $sql_row4['bf_clock_out1']; 
					$bf_clock_out_start= $sql_row4['bf_clock_out_start']; 
				    $bf_clock_out_end= $sql_row4['bf_clock_out_end']; 
				    $bf_clock_out_amt= $sql_row4['bf_clock_out_amt']; 
				    $salary_date_cal_start= $sql_row4['salary_date_cal_start']; 
				    $sick_leave= $sql_row4['sick_leave']; 
				    $sick_leave_remain= $sql_row4['sick_leave_remain']; 
				    $vacation_leave= $sql_row4['vacation_leave']; 
				    $vacation_leave_remain= $sql_row4['vacation_leave_remain']; 
				    $busi_leave= $sql_row4['busi_leave']; 
				    $busi_leave_remain= $sql_row4['busi_leave_remain']; 
					$midday_exp= $sql_row4['midday_exp']; 
					$work_status= $sql_row4['work_status']; 
					// $user_note= $sql_row4['user_note'];
					 $att_cal_month_bef= $sql_row4['att_cal_month_bef'];
					 $set_group_id= $sql_row4['set_group_id'];
					$hr_payroll_tax_id= $sql_row4['hr_payroll_tax_id'];
					 $hol_week_code = explodeStr2('#',$week_holiday);
					$hol_week_count = explode_Count2('#',$week_holiday);
				}
					if($f_new==1){
			
				$qry_fr=pg_query("INSERT INTO hr_users_setting(
            user_id, month, year, max_late, late_fine, late_time_start, 
            late_time_end, clock_out, start_work,
            clock_out_sat, att_all, late_time_sp_start,late_time_sp_end, att_all_full_month_rate,week_holiday,late_time_af,max_late_af,late_fine_af ,
            create_date, create_by, bf_clock_out1, bf_clock_out_start, 
       bf_clock_out_end, u_sex, bf_clock_out_amt, salary_date_cal_start, 
       sick_leave, sick_leave_remain, vacation_leave, vacation_leave_remain, 
       busi_leave, busi_leave_remain,midday_exp,work_status,att_cal_month_bef,set_group_id,hr_payroll_tax_id)
    VALUES ('$id_user','$mm','$yy','$max_late','$late_fine','$late_time_start',
	'$late_time_end','$clock_out','$start_work',
	'$clock_out_sat','$att_all','$late_time_sp_start','$late_time_sp_end','$att_all_full_month_rate','$week_holiday',
	'$late_time_af','$max_late_af','$late_fine_af',
	'$app_date','000','$bf_clock_out1','$bf_clock_out_start',
	'$bf_clock_out_end','$u_sex','$bf_clock_out_amt','$salary_date_cal_start',
	'$sick_leave','$sick_leave_remain','$vacation_leave','$vacation_leave_remain',
	'$busi_leave','$busi_leave_remain','$midday_exp','$work_status','$att_cal_month_bef','$set_group_id','$hr_payroll_tax_id')");	
	
		}
			}

			//เกี่ยวกับสลิปเงินเดือน
			$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no,salary_per_day ,salary_note ,bonus,t_total_tax_so ,user_status 
	   FROM \"hr_payroll_report\" where user_id_sys='$id_user' and month='$mm' and year='$yy' ");

			$nub2=pg_num_rows($qry_fr2); 

			if($nub2==0){
			$f_new2 = 1;//ให้ insert ใหม่		
				
				//ให้ดึงข้อมูล setting เดือนก่อน มาเพิ่มในเดือนปัจจุบัน
				$mm_ins = $mm-1;
				$yy_ins = $yy;
				if($mm_ins==0){$mm_ins=12;$yy_ins =$yy_ins-1; }
				
			$qry_fr3=pg_query("SELECT max(month) as max_m 
	   FROM \"hr_payroll_report\" where user_id_sys='$id_user' and month <= '$mm_ins' and year='$yy_ins' ");
				while($sql_row33=pg_fetch_array($qry_fr3)){
					
					$max_m = $sql_row33['max_m'];
				}
				
				$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no,salary_per_day,salary_note ,bonus,t_total_tax_so ,user_status 
	   FROM \"hr_payroll_report\" where user_id_sys='$id_user' and month='$max_m' and year='$yy_ins' ");

			$nub2=pg_num_rows($qry_fr2); 

				if($nub2==0){ //เมื่อเป็นพนักงานใหม่ ยังไม่มีข้อมูล ให้ไปเอาของ user ให้ดึง ค่า mainหลัก โดยแยกหญิงชาย 
					$f_new2 = 1;//ให้ insert ใหม่		
					$f_new3=1;
									//ดึงข้อมูลพนักงานจาก Xlease
			$query=pg_query("select u_sex from \"fuser_detail\" where \"id_user\"='$id_user'");
	if($result=pg_fetch_array($query)){
		

		$u_sex=$result["u_sex"];	

		
	}
		
			if($u_sex=="หญิง"){$hr_payroll_report_id = 2;}
			else if($u_sex=="ชาย"){$hr_payroll_report_id = 1;}	
				
				$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no ,salary_per_day,tax_exc_sal_col ,bonus,t_total_tax_so,user_status 
	   FROM \"hr_payroll_report\" where id='$hr_payroll_report_id' ");	   
	  
	   $nub2=pg_num_rows($qry_fr2); 
				
			} //ปิด if($nub2==0){ 2
				
			}  //ปิด if($nub2==0){ 1
						
			if($nub2>0){ //ถ้ามีข้อมูล
			while($sql_row42=pg_fetch_array($qry_fr2)){
					

					$user_id_slip = $sql_row42['user_id_slip'];
					$user_fullname = $sql_row42['user_fullname'];
					$user_dep = $sql_row42['user_dep'];
					$user_div = $sql_row42['user_div'];
					$user_pos = $sql_row42['user_pos'];
					$user_start = $sql_row42['user_start'];
					$bank_name = $sql_row42['bank_name'];
					$comp_name = $sql_row42['comp_name'];
					$bank_acc_type = $sql_row42['bank_acc_type'];
					$salary_type = $sql_row42['salary_type'];
					$salary_amt = $sql_row42['salary_amt'];
					$cost_of_living = $sql_row42['cost_of_living'];
					$diligent = $sql_row42['diligent'];
					$ot = $sql_row42['ot'];
					
					$commission = $sql_row42['commission'];
					$other_income = $sql_row42['other_income'];
					$fare = $sql_row42['fare'];
					$depreciation= $sql_row42['depreciation']; 
					$tel_income= $sql_row42['tel_income']; 
				    $total_income= $sql_row42['total_income']; 
				    $tax= $sql_row42['tax']; 
				    $fine_late= $sql_row42['fine_late']; 
				    $social_amt= $sql_row42['social_amt']; 
				    $other_deduct= $sql_row42['other_deduct']; 
				    $fine_incomplete= $sql_row42['fine_incomplete']; 
				    $total_deduct= $sql_row42['total_deduct']; 
				    $total_net= $sql_row42['total_net']; 
				    $t_salary_amt= $sql_row42['t_salary_amt']; 
					$t_cost_of_living= $sql_row42['t_cost_of_living']; 
				    $t_diligent= $sql_row42['t_diligent']; 
				    $t_ot= $sql_row42['t_ot']; 
				    $t_commission= $sql_row42['t_commission']; 
				    $t_other_income= $sql_row42['t_other_income']; 
				    $t_fare= $sql_row42['t_fare']; 
				    $t_tel_income= $sql_row42['t_tel_income']; 
				    $t_depreciation= $sql_row42['t_depreciation'];
					
					$t_total_income= $sql_row42['t_total_income']; 
				    $t_tax= $sql_row42['t_tax']; 
				    $t_fine_late= $sql_row42['t_fine_late']; 
				    $t_social_amt= $sql_row42['t_social_amt']; 
				    $t_other_deduct= $sql_row42['t_other_deduct']; 
				    $t_fine_incomplete= $sql_row42['t_fine_incomplete']; 
				    $t_total_deduct= $sql_row42['t_total_deduct']; 
				    $t_total_net= $sql_row42['t_total_net'];

					$pay_type= $sql_row42['pay_type']; 
				   
					$social_rate= $sql_row42['social_rate']; 
				    $user_id_sys= $sql_row42['user_id_sys'];
					
					 $bank_acc_no= $sql_row42['bank_acc_no'];
					   $salary_per_day= $sql_row42['salary_per_day'];
					   $salary_note= $sql_row42['salary_note'];
					   $bonus= $sql_row42['bonus'];
					$t_total_tax_so= $sql_row42['t_total_tax_so'];
					   $user_status= $sql_row42['user_status'];
					
					
			}
		if(($mm==date('m') && $yy == date('Y')) || $f_new3==1){  //ถ้าเดือนที่เลือกเท่ากับเดือนปัจจุบัน ให้ดึงข้อมูลจาก xlease มา
			
			
												//ดึงข้อมูลพนักงานจาก Xlease
			$query=pg_query("select a.fname,a.title,a.lname,a.status_user,b.u_status,b.u_sex,b.u_pos,b.u_salary,b.startwork,a.user_group,a.user_dep from \"fuser\" a 
					left join \"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"
					where a.\"id_user\"='$id_user'");
	if($result=pg_fetch_array($query)){
		
		$title=$result["title"];
		$fname=$result["fname"];
		$lname=$result["lname"];
		$u_status=$result["u_status"];
		$u_sex=$result["u_sex"];	
		$u_pos=$result["u_pos"];
		$u_salary=$result["u_salary"];
		$startwork=$result["startwork"];
		$dep_id=$result["user_group"];
		$fdep_id=$result["user_dep"];
		$status_user=$result["status_user"]; //true ยังทำงานอยู่
		
	}
		

		
			$user_id_sys = $id_user;
			
			if($u_sex=="หญิง"){$u_sex=0;$hr_payroll_report_id = 2;}
			else if($u_sex=="ชาย"){$u_sex=1;$hr_payroll_report_id = 1;}
			$salary_amt = $u_salary;
			$user_fullname = $title.$fname." ".$lname;
			$user_start = $startwork;
			$user_status = $u_status;
			$user_pos = $u_pos;
			//ฝ่าย
			$qry_dep=pg_query("select fdep_name from f_department where fstatus='TRUE' and fdep_id ='$fdep_id' ");
									if($resd=pg_fetch_array($qry_dep))
									 {
			$user_div = $resd["fdep_name"];
	
									 }
			//แผนก
			$qry_gpuser=pg_query("select dep_name from department where dep_id = '$dep_id' ");
									if($resg=pg_fetch_array($qry_gpuser))
									 {
			$user_dep = $resg["dep_name"];
									 }
									 
								if($status_user=='true')$work_status=1; //ยังทำงานอยู่
								else if($status_user=='false')$work_status=2; //ออก
								
								
				$qry_fr3=pg_query("SELECT set_value 
	   FROM \"hr_payroll_setting\" where set_group_id = '$set_group_id' and set_name='current_social_rate' order by set_seq ");

			while($sql_row43=pg_fetch_array($qry_fr3)){

					$social_rate = $sql_row43['set_value'];
								
			}
			
			
						//สถานะปัจจุบัน โสด/สมรส
			$in_qry="Update \"hr_users_setting\" set work_status='$work_status',mo_date ='$app_date',mo_by='000' 
	    where user_id='$id_user' and month='$mm' and year='$yy'  ";	
	
	
	
    if(!$res=pg_query($in_qry)){
        echo "บันทึก hr_users_setting ไม่สำเร็จ $in_qry";
        }
  
	//
			 $in_qry="Update \"hr_payroll_report\" set
           user_fullname='$user_fullname', user_dep='$user_dep', 
            user_div='$user_div', user_pos='$user_pos', user_start='$user_start',
            comp_name='$comp_name', salary_amt='$salary_amt', user_status='$user_status' ,mo_date ='$app_date',mo_by='000' ,social_rate='$social_rate' 
			 where user_id_sys='$id_user' and month='$mm' and year='$yy'  ";	
	
	
	
    if(!$res=pg_query($in_qry)){
        echo "บันทึก hr_payroll_report ไม่สำเร็จ $in_qry";
       
    }
			
			         }

				if($f_new2==1){
			    $in_qry="INSERT INTO hr_payroll_report(
            user_id_slip, month, year, user_fullname, user_dep, user_div, 
            user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
            salary_amt, cost_of_living, diligent, ot, commission, other_income, 
            fare, depreciation, tel_income, total_income, tax, fine_late, 
            social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
            t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
            t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
            t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
            t_total_deduct, t_total_net, pay_type, salary_note, social_rate, 
            user_id_sys, bank_acc_no,salary_per_day,bonus,t_total_tax_so,user_status)
    VALUES ('$user_id_slip','$mm','$yy','$user_fullname','$user_dep','$user_div',
	'$user_pos','$user_start','$bank_name','$comp_name','$bank_acc_type','$salary_type',
			'$salary_amt','$cost_of_living','$diligent','$ot','$commission','$other_income',
			'$fare','$depreciation','$tel_income','$total_income','$tax','$fine_late',
			'$social_amt','$other_deduct','$fine_incomplete','$total_deduct','$total_net',
			'$t_salary_amt','$t_cost_of_living','$t_diligent','$t_ot','$t_commission',
			'$t_other_income','$t_fare','$t_tel_income','$t_depreciation','$t_total_income',
			'$t_tax','$t_fine_late','$t_social_amt','$t_other_deduct','$t_fine_incomplete',
			'$t_total_deduct','$t_total_net','$pay_type','$salary_note','$social_rate',
			'$user_id_sys','$bank_acc_no','$salary_per_day','$bonus','$t_total_tax_so','$user_status')";

    		$res=pg_query($in_qry);
			 

			
			
				}
     
    

			}//ปิด $nub2>0

			//เช็คเมื่อเป็นเดือนปัจจุบัน ให้ดึง setting ปัจจุบัน เช่น %ประกันสังคม
			//if($mm==date('m') && $yy == date('Y')){
				//ดึงค่า setting ต่างๆ ที่เป็น Global ตาม id นั้นๆ
			$qry_fr3=pg_query("SELECT set_value 
	   FROM \"hr_payroll_setting\" where set_group_id = '$set_group_id' order by set_seq ");

			$nub3=pg_num_rows($qry_fr3); 
			$s_c =0;
			if($nub3>0){
			while($sql_row43=pg_fetch_array($qry_fr3)){

					$set_value[$s_c] = $sql_row43['set_value'];
					$s_c++;
					
			}
			//ค่า setting ต่างๆ เรียงลำดับตามฐานข้อมูล
			$social_rate = $set_value[0];
			$tax_exp_deduct_percent = $set_value[1];
			
			$tax_exp_deduct_max = $set_value[2];
			$tax_private_deductible = $set_value[3];
			}
			
			//}
			//ใส่ Array ช่วงภาษี ถ้ามีการเปลี่ยนแปลง ให้ if ตามช่วงเวลา ให้ใช้ของเดิม ถ้า มากกว่า เดือนนั้นให้ใช้ของใหม่
			
			$qry_fr3=pg_query("SELECT tax_percent,tax_rate 
	   FROM \"hr_payroll_tax\" where id='$hr_payroll_tax_id' order by tax_rate  ");

			$nub3=pg_num_rows($qry_fr3); 
			$s_c =0;
			if($nub3>0){
			while($sql_row43=pg_fetch_array($qry_fr3)){

					//$tax_percent[$s_c] = $sql_row43['tax_percent'];
					//$tax_rate[$s_c] = $sql_row43['tax_rate'];
					$s_c++;
					
					if($s_c==1){
					$tax_rate1 = $sql_row43['tax_rate'];
					$tax_percent1 = ($tax_percent[0]/100);
					}
					else if($s_c==$nub3){ //recordสุดท้ายไม่ต้องนำมาคิด เพราะเป็นช่วง ตั้งแต่ xx ขึ้นไป
						$tax_percent1 = $tax_percent1.",".($sql_row43['tax_percent']/100);
						}
					else 
					{ //ถ้าเป็นrate ที่ไม่ใช่่แถวแรกและ แถวสุดท้าย
						$tax_rate1 = $tax_rate1.",".$sql_row43['tax_rate'];
						$tax_percent1 = $tax_percent1.",".($sql_row43['tax_percent']/100);
					}
				
			}
			//ค่า setting ต่างๆ เรียงลำดับตามฐานข้อมูล
			
			//$tax_rate1 = $tax_rate[0].",".$tax_rate[1].",".$tax_rate[2].",".$tax_rate[3];
			//$tax_percent1 = ($tax_percent[0]/100).",".($tax_percent[1]/100).",".($tax_percent[2]/100).",".($tax_percent[3]/100).",".($tax_percent[4]/100);

			}

  
  $year_new_user = substr($user_start,0,4);
  $month_new_user = substr($user_start,5,2);
  
$tb22 = 0;
		include("att_tb.php");	
?>

            <br />
             <table><tr>
             
             <?php
			 $busi_leave_remain=$busi_leave_remain-$busi_leave_c;
			$sick_leave_remain= ($sick_leave_remain-$sick_leave_c);
			$vacation_leave_remain= ($vacation_leave_remain-$vacation_leave_c);
	
			  ?>
              <td align="right">ลากิจคงเหลือ = <?php echo $busi_leave_remain."/".$busi_leave ?> วัน</td>
             <td  align="right">ลาป่วยคงเหลือ = <?php echo $sick_leave_remain."/".$sick_leave ?> วัน</td>
				
                  <td align="right">ลาพักร้อนคงเหลือ = <?php echo $vacation_leave_remain."/".$vacation_leave ?> วัน</td>
                  <td align="right">สายช่วงเช้า = <?php echo $int_late_mor ?> ครั้ง</td>
                  <td align="right">สายช่วงบ่าย = <?php echo $int_late_af ?> ครั้ง</td>
                  <td align="right">สายช่วงเย็น = <?php echo $int_late_ev ?> ครั้ง</td>
                  
              </tr>
              <!--
              <tr>
              <td align="right"><?php echo "Work Day = $intWorkDay" ?>;</td>
              
              <td align="right"><?php echo "Holiday = $intHoliday" ?></td>
              <td align="right"><?php echo "Public Holiday = $intPublicHoliday" ?></td>
              <td align="right"><?php echo "All Holiday = ".($intHoliday+$intPublicHoliday) ?></td>
			</tr> -->
             </table>

<?php
//if($salary_type=='0')
				//วันหยุด
				
	for($w=0;$w<($hol_week_count);$w++){
			
			if($hol_week_code[$w]==0 )$wk0=1;
			else if($hol_week_code[$w]==1 )$wk1=1;
			else if($hol_week_code[$w]==2 )$wk2=1;
			else if($hol_week_code[$w]==3 )$wk3=1;
			else if($hol_week_code[$w]==4 )$wk4=1;
			else if($hol_week_code[$w]==5 )$wk5=1;
			else if($hol_week_code[$w]==6 )$wk6=1;
			
	}
	$tb22 = 1;
	//คำนวณเบี้ยขยันเดือนที่แล้ว
	include("all_cal.php");

include("salary_dtl.php");

}
?>
