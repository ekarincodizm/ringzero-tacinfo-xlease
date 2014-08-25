<?php
include("../../config/config.php");
include("function_payroll.php");

require_once("../join_payment/extensions/sys_setup.php");

include("../join_payment/extensions/fpdf16/fpdf_writehtml.php");

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

$app_date = Date('Y-m-d H:i:s');

$last_date_month = date("t",strtotime("$yy-$mm-01"));


?>


<table width="830" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td width="43">&nbsp;</td>
    <td colspan="2" align="left">'.$comp_name.'</td>
    <td width="59"></td>
    <td width="209">'.$user_dep.'</td>
  </tr>

  <tr>
    <td width="43">&nbsp;</td>
   
    <td width="418">'.$user_fullname.'</td>
    <td width="99">'.$user_id_slip.'</td>
    <td></td>
    <td>'.$last_date_month.'</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="807" border="1" cellpadding="0" cellspacing="0">



  <tr>
    
    <td colspan="6" align=CENTER>รายการ รับ</td>
  </tr>
  <tr>
    <td width="130" align=CENTER>เงินเดือน</td>
    <td width="136" align=CENTER>ค่าครองชีพ</td>
    <td width="133" align=CENTER>เบี้ยขยัน</td>
    <td width="131" align=CENTER>คอมมิชชั่น </td>
    <td width="130" align=CENTER>ล่วงเวลา (OT)</td>
    
    <td width="133" align=CENTER>&nbsp;</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$salary_amt.'</td>
    <td align=RIGHT>'.$cost_of_living.'</td>
    <td align=RIGHT>'.$diligent.'</td>
    <td align=RIGHT>'.$commission.'</td>
    <td align=RIGHT>'.$ot.'</td>
   
    <td align=RIGHT>&nbsp;</td>
  </tr>
    <tr>
    <td align=CENTER>ค่าพาหนะ</td>
    <td align=CENTER>ค่าเสื่อมพาหนะ</td>
    <td align=CENTER>ค่าโทรศัพท์</td>
    <td align=CENTER>ค่าอื่นๆ</td>
    <td align=CENTER>&nbsp;</td>
   
    <td align=CENTER>รับรวม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$fare.'</td>
    <td align=RIGHT>'.$depreciation.'</td>
    <td align=RIGHT>'.$tel_income.'</td>
    <td align=RIGHT>'.$other_income.'</td>
    <td align=RIGHT>&nbsp;</td>
    
    <td align=RIGHT>'.$total_income.'</td>
  </tr>
  <tr>
    <td colspan="6" align=CENTER>รายการ หัก</td>
  </tr>
  <tr>
    <td align=CENTER>ภาษี</td>
    <td align=CENTER>ประกันสังคม</td>
    <td align=CENTER>ค่าปรับมาสาย</td>
    <td align=CENTER>ค่าหักอื่นๆ</td>
    <td align=CENTER>&nbsp;</td>
   
    <td align=CENTER>หักรวม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$tax.'</td>
    <td align=RIGHT>'.$social_amt.'</td>
    <td align=RIGHT>'.$fine_late.'</td>
    <td align=RIGHT>'.$other_deduct.'</td>
     <td align=CENTER>&nbsp;</td>
   
    <td align=RIGHT>'.$total_deduct.'</td>
  </tr>
  <tr>
    <td colspan="4">(สองหมื่นหนึ่งพันห้าร้อยห้าสิบสองบาทห้าสิบสตางค์)</td>
    <td align=CENTER>รับ สุทธิ เดือนนี้</td>
   
    <td align=RIGHT>'.$total_net.'</td>
  </tr>
  
  <tr>
    <td colspan="6" align=CENTER>รายการ สะสม ถึงปัจจุบัน</td>
  </tr>
  <tr>
    <td align=CENTER>เงินเดือนสะสม</td>
    <td align=CENTER>ค่าครองชีพสะสม</td>
    <td align=CENTER>เบี้ยขยันสะสม</td>
    <td align=CENTER>คอมมิชชั่นสะสม</td>
    <td align=CENTER>ล่วงเวลา (OT) สะสม</td>
    <td align=CENTER>&nbsp;</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$t_salary_amt.'</td>
    <td align=RIGHT>'.$t_cost_of_living.'</td>
    <td align=RIGHT>'.$t_diligent.'</td>
    <td align=RIGHT>'.$t_commission.'</td>
    <td align=RIGHT>'.$t_ot.'</td>
    <td align=RIGHT>&nbsp;</td>
  </tr>
  <tr>
    <td align=CENTER>ค่าพาหนะสะสม</td>
    <td align=CENTER>ค่าเสื่อมพาหนะสะสม</td>
    <td align=CENTER>ค่าโทรศัพท์สะสม </td>
    <td align=CENTER>ค่าอื่นๆสะสม</td>
    <td align=CENTER>&nbsp;</td>
    <td align=CENTER>รับรวมสะสม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$t_fare.'</td>
    <td align=RIGHT>'.$t_depreciation.'</td>
    <td align=RIGHT>'.$t_tel_income.'</td>
    <td align=RIGHT>'.$t_other_income.'</td>
    <td align=RIGHT>&nbsp;</td>
    <td align=RIGHT>'.$t_total_income.'</td>
  </tr>
  <tr>
    <td align=CENTER>ภาษีสะสม</td>
    <td align=CENTER>ประกันสังคมสะสม</td>
    <td align=CENTER>ค่าปรับมาสายสะสม</td>
    <td align=CENTER>ค่าหักอื่นๆสะสม</td>
    <td align=CENTER>&nbsp;</td>
   
    <td align=CENTER>หักรวมสะสม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$tax.'</td>
    <td align=RIGHT>'.$t_social_amt.'</td>
    <td align=RIGHT>'.$t_fine_late.'</td>
    <td align=RIGHT>'.$t_other_deduct.'</td>
     <td align=CENTER>&nbsp;</td>
   
    <td align=RIGHT>'.$t_total_deduct.'</td>
  </tr>
  <tr>
    <td align=CENTER>วันรับเข้าทำงาน</td>
    <td align=CENTER>ธนาคาร</td>
    <td align=CENTER>ประเภทบัญชี</td>
    <td align=CENTER>เลขที่บัญชี</td>
    <td align=CENTER>ชำระโดย</td>
   
    <td align=CENTER>รับสุทธิสะสม ถึงวันนี้</td>
  </tr>
  <tr>
    <td align=CENTER>'.$user_start.'</td>
    <td align=CENTER>'.$bank_name.'</td>
    <td align=CENTER>'.$bank_acc_type.'</td>
    <td align=CENTER>'.$bank_acc_no.'</td>
    <td align=CENTER>'.$pay_by.'</td>
   
    <td align=RIGHT>'.$t_total_net.'</td>
  </tr>
  <tr>
    <td align=CENTER></td>
    <td align=CENTER></td>
    <td align=CENTER></td>
    <td align=CENTER></td>
    <td align=CENTER></td>
  
    <td align=CENTER></td>
  </tr>
 
  
</table>

<?php


// สร้าง pdf ตาม ข้อมูล -------------------------------------------------------------------
$pdf=new PDF('L', 'mm', 'A4');

$qry_fr2=pg_query("SELECT user_id_slip, month, year, user_fullname, user_dep, user_div, 
       user_pos, user_start, bank_name, comp_name, bank_acc_type, salary_type, 
       salary_amt, cost_of_living, diligent, ot, commission, other_income, 
       fare, depreciation, tel_income, total_income, tax, fine_late, 
       social_amt, other_deduct, fine_incomplete, total_deduct, total_net, 
       t_salary_amt, t_cost_of_living, t_diligent, t_ot, t_commission, 
       t_other_income, t_fare, t_tel_income, t_depreciation, t_total_income, 
       t_tax, t_fine_late, t_social_amt, t_other_deduct, t_fine_incomplete, 
       t_total_deduct, t_total_net, pay_type, social_rate, 
       user_id_sys,bank_acc_no,salary_per_day ,salary_note 
	   FROM \"hr_payroll_report\" where user_id_sys='$id_user' and month='$mm' and year='$yy' ");

			$nub2=pg_num_rows($qry_fr2); 

			if($nub2>0){
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
			}

			}
$other_deduct = ($other_deduct+$fine_incomplete); 
if($other_deduct==0)$other_deduct=" - ";
else $other_deduct= number_format($other_deduct,2);

$t_other_deduct = ($t_other_deduct+$t_fine_incomplete); 
if($t_other_deduct==0)$t_other_deduct=" - ";
else $t_other_deduct= number_format($t_other_deduct,2);


if($pay_type=='1'){ $pay_by = "โอนเข้าธนาคาร" ;}
else if($pay_type=='0'){ $pay_by = "เงินสด" ;}

$pdf->AddFont('AngsanaNew','','angsa.php');
$pdf->AddFont('AngsanaNew','B','angsab.php');
$pdf->AddFont('AngsanaNew','I','angsai.php');
$pdf->SetFont('AngsanaNew','',14);

						
$text =  '<table width="830" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td width="43">&nbsp;</td>
    <td colspan="2" align="left">'.$comp_name.'</td>
    <td width="59"></td>
    <td width="209">'.$user_dep.'</td>
  </tr>

  <tr>
    <td width="43">&nbsp;</td>
   
    <td width="418">'.$user_fullname.'</td>
    <td width="99">'.$user_id_slip.'</td>
    <td></td>
    <td>'.$last_date_month.'</td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="807" border="1" cellpadding="0" cellspacing="0">



  <tr>
    
    <td colspan="6" align=CENTER>รายการ รับ</td>
  </tr>
  <tr>
    <td width="130" align=CENTER>เงินเดือน</td>
    <td width="136" align=CENTER>ค่าครองชีพ</td>
    <td width="133" align=CENTER>เบี้ยขยัน</td>
    <td width="131" align=CENTER>คอมมิชชั่น </td>
    <td width="130" align=CENTER>ล่วงเวลา (OT)</td>
    
    <td width="133" align=CENTER>&nbsp;</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$salary_amt.'</td>
    <td align=RIGHT>'.$cost_of_living.'</td>
    <td align=RIGHT>'.$diligent.'</td>
    <td align=RIGHT>'.$commission.'</td>
    <td align=RIGHT>'.$ot.'</td>
   
    <td align=RIGHT>&nbsp;</td>
  </tr>
    <tr>
    <td align=CENTER>ค่าพาหนะ</td>
    <td align=CENTER>ค่าเสื่อมพาหนะ</td>
    <td align=CENTER>ค่าโทรศัพท์</td>
    <td align=CENTER>ค่าอื่นๆ</td>
    <td align=CENTER>&nbsp;</td>
   
    <td align=CENTER>รับรวม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$fare.'</td>
    <td align=RIGHT>'.$depreciation.'</td>
    <td align=RIGHT>'.$tel_income.'</td>
    <td align=RIGHT>'.$other_income.'</td>
    <td align=RIGHT>&nbsp;</td>
    
    <td align=RIGHT>'.$total_income.'</td>
  </tr>
  <tr>
    <td colspan="6" align=CENTER>รายการ หัก</td>
  </tr>
  <tr>
    <td align=CENTER>ภาษี</td>
    <td align=CENTER>ประกันสังคม</td>
    <td align=CENTER>ค่าปรับมาสาย</td>
    <td align=CENTER>ค่าหักอื่นๆ</td>
    <td align=CENTER>&nbsp;</td>
   
    <td align=CENTER>หักรวม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$tax.'</td>
    <td align=RIGHT>'.$social_amt.'</td>
    <td align=RIGHT>'.$fine_late.'</td>
    <td align=RIGHT>'.$other_deduct.'</td>
     <td align=CENTER>&nbsp;</td>
   
    <td align=RIGHT>'.$total_deduct.'</td>
  </tr>
  <tr>
    <td colspan="4">(สองหมื่นหนึ่งพันห้าร้อยห้าสิบสองบาทห้าสิบสตางค์)</td>
    <td align=CENTER>รับ สุทธิ เดือนนี้</td>
   
    <td align=RIGHT>'.$total_net.'</td>
  </tr>
  
  <tr>
    <td colspan="6" align=CENTER>รายการ สะสม ถึงปัจจุบัน</td>
  </tr>
  <tr>
    <td align=CENTER>เงินเดือนสะสม</td>
    <td align=CENTER>ค่าครองชีพสะสม</td>
    <td align=CENTER>เบี้ยขยันสะสม</td>
    <td align=CENTER>คอมมิชชั่นสะสม</td>
    <td align=CENTER>ล่วงเวลา (OT) สะสม</td>
    <td align=CENTER>&nbsp;</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$t_salary_amt.'</td>
    <td align=RIGHT>'.$t_cost_of_living.'</td>
    <td align=RIGHT>'.$t_diligent.'</td>
    <td align=RIGHT>'.$t_commission.'</td>
    <td align=RIGHT>'.$t_ot.'</td>
    <td align=RIGHT>&nbsp;</td>
  </tr>
  <tr>
    <td align=CENTER>ค่าพาหนะสะสม</td>
    <td align=CENTER>ค่าเสื่อมพาหนะสะสม</td>
    <td align=CENTER>ค่าโทรศัพท์สะสม </td>
    <td align=CENTER>ค่าอื่นๆสะสม</td>
    <td align=CENTER>&nbsp;</td>
    <td align=CENTER>รับรวมสะสม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$t_fare.'</td>
    <td align=RIGHT>'.$t_depreciation.'</td>
    <td align=RIGHT>'.$t_tel_income.'</td>
    <td align=RIGHT>'.$t_other_income.'</td>
    <td align=RIGHT>&nbsp;</td>
    <td align=RIGHT>'.$t_total_income.'</td>
  </tr>
  <tr>
    <td align=CENTER>ภาษีสะสม</td>
    <td align=CENTER>ประกันสังคมสะสม</td>
    <td align=CENTER>ค่าปรับมาสายสะสม</td>
    <td align=CENTER>ค่าหักอื่นๆสะสม</td>
    <td align=CENTER>&nbsp;</td>
   
    <td align=CENTER>หักรวมสะสม</td>
  </tr>
  <tr>
    <td align=RIGHT>'.$tax.'</td>
    <td align=RIGHT>'.$t_social_amt.'</td>
    <td align=RIGHT>'.$t_fine_late.'</td>
    <td align=RIGHT>'.$t_other_deduct.'</td>
     <td align=CENTER>&nbsp;</td>
   
    <td align=RIGHT>'.$t_total_deduct.'</td>
  </tr>
  <tr>
    <td align=CENTER>วันรับเข้าทำงาน</td>
    <td align=CENTER>ธนาคาร</td>
    <td align=CENTER>ประเภทบัญชี</td>
    <td align=CENTER>เลขที่บัญชี</td>
    <td align=CENTER>ชำระโดย</td>
   
    <td align=CENTER>รับสุทธิสะสม ถึงวันนี้</td>
  </tr>
  <tr>
    <td align=CENTER>'.$user_start.'</td>
    <td align=CENTER>'.$bank_name.'</td>
    <td align=CENTER>'.$bank_acc_type.'</td>
    <td align=CENTER>'.$bank_acc_no.'</td>
    <td align=CENTER>'.$pay_by.'</td>
   
    <td align=RIGHT>'.$t_total_net.'</td>
  </tr>
  <tr>
    <td align=CENTER></td>
    <td align=CENTER></td>
    <td align=CENTER></td>
    <td align=CENTER></td>
    <td align=CENTER></td>
  
    <td align=CENTER></td>
  </tr>
 
  
</table>';
//echo 11;
$pdf->WriteHTML($text);								
$pdf->Output();
?>