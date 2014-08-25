<?php
include("../../config/config.php");

include("function_payroll.php");

$id = $_REQUEST['id'];
$cmd = $_REQUEST['cmd'];
if($cmd == 'update'){

$status = 0;

pg_query("BEGIN WORK");

$app_date = Date('Y-m-d H:i:s');

					$max_late = $_POST['max_late'];
					$late_fine = $_POST['late_fine'];
					$late_time_start = $_POST['late_time_start'];
					$late_time_end = $_POST['late_time_end'];
					$clock_out = $_POST['clock_out'];
					
					$start_work = $_POST['start_work'];
					
			
					$clock_out_sat = $_POST['clock_out_sat'];
					$att_all = $_POST['att_all'];
					$late_time_sp_start = $_POST['late_time_sp_start'];
					$late_time_sp_end = $_POST['late_time_sp_end'];
					$att_all_full_month_rate = $_POST['att_all_full_month_rate'];
					$week_holiday = $_POST['week_holiday'];
					
					$late_time_af = $_POST['late_time_af'];
					
					$max_late_af = $_POST['max_late_af'];
					$late_fine_af = $_POST['late_fine_af'];
					$u_sex = $_POST['u_sex'];
					$bf_clock_out1= $_POST['bf_clock_out1']; 
					$bf_clock_out_start= $_POST['bf_clock_out_start']; 
				    $bf_clock_out_end= $_POST['bf_clock_out_end']; 
				    $bf_clock_out_amt= $_POST['bf_clock_out_amt']; 
				    $salary_date_cal_start= $_POST['salary_date_cal_start']; 
				    $sick_leave= $_POST['sick_leave']; 
				    $sick_leave_remain= $_POST['sick_leave_remain']; 
				    $vacation_leave= $_POST['vacation_leave']; 
				    $vacation_leave_remain= $_POST['vacation_leave_remain']; 
				    $busi_leave= $_POST['busi_leave']; 
				    $busi_leave_remain= $_POST['busi_leave_remain']; 
					$midday_exp= $_POST['midday_exp']; 
					$att_cal_month_bef = $_POST['att_cal_month_bef'];
							
					
					$bank_name = $_POST['bank_name'];
					$comp_name = $_POST['comp_name'];
					$bank_acc_type = $_POST['bank_acc_type'];
					
					$salary_type = $_POST['salary_type'];

				
					$salary_per_day = $_POST['salary_per_day'];

					$work_status = $_POST['work_status'];
					$user_status= $_POST['user_status'];
					
					
					
					$pay_type= $_POST['pay_type']; 
				    $user_note= $_POST['user_note'];
					$social_rate= $_POST['social_rate']; 
					
					 $set_group_id= $_POST['set_group_id'];
					$hr_payroll_tax_id= $_POST['hr_payroll_tax_id'];
					
if($midday_exp=='')$midday_exp=0;
    if($salary_date_cal_start=='')$salary_date_cal_start=1;
   $in_qry="Update \"hr_users_setting\" set
           max_late='$max_late', late_fine='$late_fine', late_time_start='$late_time_start', 
            late_time_end='$late_time_end', clock_out='$clock_out', start_work='$start_work', 
            clock_out_sat='$clock_out_sat', att_all='$att_all', late_time_sp_start='$late_time_sp_start',late_time_sp_end='$late_time_sp_end', 
			att_all_full_month_rate='$att_all_full_month_rate',late_time_af='$late_time_af',max_late_af='$max_late_af',late_fine_af='$late_fine_af',
           bf_clock_out1='$bf_clock_out1', bf_clock_out_start='$bf_clock_out_start', 
       bf_clock_out_end='$bf_clock_out_end', u_sex='$u_sex', bf_clock_out_amt='$bf_clock_out_amt', salary_date_cal_start='$salary_date_cal_start', 
       sick_leave='$sick_leave', sick_leave_remain='$sick_leave_remain', vacation_leave='$vacation_leave', vacation_leave_remain='$vacation_leave_remain', 
       busi_leave='$busi_leave', busi_leave_remain='$busi_leave_remain',midday_exp='$midday_exp',user_note='$user_note',mo_date ='$app_date',mo_by='".$_SESSION["av_iduser"]."',
	   week_holiday='$week_holiday',att_cal_month_bef='$att_cal_month_bef',set_group_id='$set_group_id' , hr_payroll_tax_id='$hr_payroll_tax_id' ,work_status='$work_status' 
	    where id='$id' ";	
	
	
	
    if(!$res=pg_query($in_qry)){
        $txt_error[] = "บันทึก hr_users_setting ไม่สำเร็จ $in_qry";
        $status++;
    }
	

   $in_qry="Update \"hr_payroll_report\" set
           bank_name='$bank_name', 
            comp_name='$comp_name', bank_acc_type='$bank_acc_type', salary_type='$salary_type',
			pay_type='$pay_type',social_rate='$social_rate',mo_date ='$app_date',mo_by='".$_SESSION["av_iduser"]."',salary_per_day='$salary_per_day' ,user_status='$user_status' 
			 where id='$id'  ";	
	
	
	
    if(!$res=pg_query($in_qry)){
        $txt_error[] = "บันทึก hr_payroll_report ไม่สำเร็จ $in_qry";
        $status++;
    }
	


    if($status == 0){
        //pg_query("ROLLBACK");
       pg_query("COMMIT");
        $data['success'] = true;
        $data['message'] = "แก้ไขข้อมูลเรียบร้อยแล้ว  ";
    }else{
        pg_query("ROLLBACK");
        $data['success'] = false;
        $data['message'] = "ไม่สามารถบันทึกได้! $txt_error[0]";
    }
    echo json_encode($data);
    
}
?>