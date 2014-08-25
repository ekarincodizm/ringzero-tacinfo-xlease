<script type="text/javascript">
$('#t22').hide();

</script>
<?php
//คำนวณเบี้ยขยันของเดือนที่แล้ว
				$mm = $mm-$att_cal_month_bef;
			
				$yy = $yy;
				if($mm==0){$mm=12;$yy =$yy-1; }
				if(strlen($mm)==1)$mm = "0".$mm;

			
$qry_fr=pg_query("SELECT max_late, late_fine, late_time_start, 
       late_time_end, clock_out, start_work,
       clock_out_sat, att_all, late_time_sp_start,late_time_sp_end, att_all_full_month_rate,week_holiday, 
	   late_time_af,max_late_af,late_fine_af, bf_clock_out1, bf_clock_out_start, 
       bf_clock_out_end, u_sex, bf_clock_out_amt, salary_date_cal_start, 
       sick_leave, sick_leave_remain, vacation_leave, vacation_leave_remain, 
       busi_leave, busi_leave_remain,midday_exp,work_status ,att_cal_month_bef ,set_group_id,hr_payroll_tax_id 
	   FROM \"hr_users_setting\" where user_id='$id_user' and month='$mm' and year='$yy' ");
	
			$nub=pg_num_rows($qry_fr); 

			if($nub>0){
			while($sql_row4=pg_fetch_array($qry_fr)){

					$week_holiday = $sql_row4['week_holiday'];
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
				 $att_cal_month_bef= $sql_row4['att_cal_month_bef'];
					 $set_group_id= $sql_row4['set_group_id'];
					$hr_payroll_tax_id= $sql_row4['hr_payroll_tax_id'];
					 $hol_week_code = explodeStr2('#',$week_holiday);
					$hol_week_count = explode_Count2('#',$week_holiday);
					
					

			}
$total_late_fine_amt=0;	
$total_all=0;
$total_get_allo =0;
			include("att_tb.php");	
			//เบี้ยชยัน ค่าปรับ
	
$diligent = $total_all;
$fine_late = $total_late_fine_amt;
			}
			
			
?>
