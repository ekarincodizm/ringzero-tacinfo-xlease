<?php
include("../../config/config.php");

include("function_payroll.php");


$cmd = $_REQUEST['cmd'];
if($cmd == 'update'){

$status = 0;

pg_query("BEGIN WORK");

$yy = $_POST['yy'];
$mm = $_POST['mm'];

$app_date = Date('Y-m-d H:i:s');
$id_user = $_POST['id_user'];


					$user_id_slip = $_POST['user_id_slip'];
					$user_fullname = $_POST['user_fullname'];
					$user_dep = $_POST['user_dep'];
					$user_div = $_POST['user_div'];
					$user_pos = $_POST['user_pos'];
					$user_start = $_POST['user_start'];
					$bank_name = $_POST['bank_name'];
					$comp_name = $_POST['comp_name'];
					$bank_acc_type = $_POST['bank_acc_type'];
					$salary_type = $_POST['salary_type'];
					$salary_amt = $_POST['salary_amt'];
					$cost_of_living = $_POST['cost_of_living'];
					$diligent = $_POST['diligent'];
					$ot = $_POST['ot'];
					$commission = $_POST['commission'];
					$other_income = $_POST['other_income'];
					$fare = $_POST['fare'];
					$depreciation= $_POST['depreciation']; 
					$tel_income= $_POST['tel_income']; 
				    $total_income= $_POST['total_income']; 
				    $tax= $_POST['tax']; 
				    $fine_late= $_POST['fine_late']; 
				    $social_amt= $_POST['social_amt']; 
				    $other_deduct= $_POST['other_deduct']; 
				    $fine_incomplete= $_POST['fine_incomplete']; 
				    $total_deduct= $_POST['total_deduct']; 
				    $total_net= $_POST['total_net']; 
				    $t_salary_amt= $_POST['t_salary_amt']; 
					$t_cost_of_living= $_POST['t_cost_of_living']; 
				    $t_diligent= $_POST['t_diligent']; 
				    $t_ot= $_POST['t_ot']; 
				    $t_commission= $_POST['t_commission']; 
				    $t_other_income= $_POST['t_other_income']; 
				    $t_fare= $_POST['t_fare']; 
				    $t_tel_income= $_POST['t_tel_income']; 
				    $t_depreciation= $_POST['t_depreciation'];
					
					$t_total_income= $_POST['t_total_income']; 
				    $t_tax= $_POST['t_tax']; 
				    $t_fine_late= $_POST['t_fine_late']; 
				    $t_social_amt= $_POST['t_social_amt']; 
				    $t_other_deduct= $_POST['t_other_deduct']; 
				    $t_fine_incomplete= $_POST['t_fine_incomplete']; 
				    $t_total_deduct= $_POST['t_total_deduct']; 
				    $t_total_net= $_POST['t_total_net'];

					$pay_type= $_POST['pay_type']; 
				   
					$social_rate= $_POST['social_rate']; 
				    $user_id_sys= $_POST['user_id_sys'];
					 $salary_note= $_POST['salary_note'];
					$bank_acc_no= $_POST['bank_acc_no'];

			
				    $sick_leave_remain= $_POST['sick_leave_remain']; 
				
				    $vacation_leave_remain= $_POST['vacation_leave_remain']; 
			
				    $busi_leave_remain= $_POST['busi_leave_remain']; 
					
					$bonus= $_POST['bonus'];
					
				$t_total_tax_so= $_POST['t_total_tax_so'];

/*
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
            user_id_sys, bank_acc_no)
    VALUES ('$user_id_slip','$mm','$yy','$user_fullname','$user_dep','$user_div',
	'$user_pos','$user_start','$bank_name','$comp_name','$bank_acc_type','$salary_type',
			'$salary_amt','$cost_of_living','$diligent','$ot','$commission','$other_income',
			'$fare','$depreciation','$tel_income','$total_income','$tax','$fine_late',
			'$social_amt','$other_deduct','$fine_incomplete','$total_deduct','$total_net',
			'$t_salary_amt','$t_cost_of_living','$t_diligent','$t_ot','$t_commission',
			'$t_other_income','$t_fare','$t_tel_income','$t_depreciation','$t_total_income',
			'$t_tax','$t_fine_late','$t_social_amt','$t_other_deduct','$t_fine_incomplete',
			'$t_total_deduct','$t_total_net','$pay_type','$salary_note','$social_rate',
			'$user_id_sys','$bank_acc_no')";


    if(!$res=pg_query($in_qry)){
        $txt_error[] = "บันทึก hr_payroll_report ไม่สำเร็จ $in_qry";
        $status++;
    }
	
*/
   $in_qry="
   UPDATE hr_payroll_report
   SET user_id_slip='$user_id_slip', month='$mm', year='$yy', user_fullname='$user_fullname', user_dep='$user_dep', 
       user_div='$user_div', user_pos='$user_div', user_start='$user_start', bank_name='$bank_name', comp_name='$comp_name', 
       bank_acc_type='$bank_acc_type', salary_type='$salary_type', salary_amt='$salary_amt', cost_of_living='$cost_of_living', 
       diligent='$diligent', ot='$ot', commission='$commission', other_income='$other_income', fare='$fare', depreciation='$depreciation', 
       tel_income='$tel_income', total_income='$total_income', tax='$tax', fine_late='$fine_late', social_amt='$social_amt', 
       other_deduct='$other_deduct', fine_incomplete='$fine_incomplete', total_deduct='$total_deduct', total_net='$total_net', 
       t_salary_amt='$t_salary_amt', t_cost_of_living='$t_cost_of_living', t_diligent='$t_diligent', t_ot='$t_ot', t_commission='$t_commission', 
       t_other_income='$t_other_income', t_fare='$t_fare', t_tel_income='$t_tel_income', t_depreciation='$t_depreciation', 
       t_total_income='$t_total_income', t_tax='$t_tax', t_fine_late='$t_fine_late', t_social_amt='$t_social_amt', t_other_deduct='$t_other_deduct', 
       t_fine_incomplete='$t_fine_incomplete', t_total_deduct='$t_total_deduct', t_total_net='$t_total_net', pay_type='$pay_type', 
       salary_note='$salary_note', social_rate='$social_rate', user_id_sys='$user_id_sys', bank_acc_no='$bank_acc_no', mo_date='$app_date', 
       mo_by='".$_SESSION["av_iduser"]."' ,f_user_app='1' ,bonus='$bonus',t_total_tax_so='$t_total_tax_so' 
	   where user_id_sys='$id_user' and month='$mm' and year='$yy' ";		

    if(!$res=pg_query($in_qry)){
        $txt_error[] = "บันทึก hr_payroll_report ไม่สำเร็จ $in_qry";
        $status++;
    }
	
 $in_qry="Update \"hr_users_setting\" set sick_leave_remain='$sick_leave_remain', vacation_leave_remain='$vacation_leave_remain', 
       busi_leave_remain='$busi_leave_remain' 
	    where user_id='$id_user' and month='$mm' and year='$yy'  ";	
	
	
	
    if(!$res=pg_query($in_qry)){
        $txt_error[] = "บันทึก hr_users_setting ไม่สำเร็จ $in_qry";
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