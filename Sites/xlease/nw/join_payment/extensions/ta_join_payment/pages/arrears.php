<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");



 $id2 =$_POST['id'];
 $pay_date =$_POST['pay_date'];
 $start_pay_date =$_POST['start_pay_date'];
 $change_pay_type =$_POST['change_pay_type'];


					  $query = "SELECT pay_type,expire_date,amount,amount_month FROM ta_join_payment WHERE id_main='$id2' and deleted='0' and pay_date <= '$pay_date' ORDER BY period_date desc,expire_date desc,pay_date desc, id desc limit 1 ";//หารายการสุดท้าย
				//echo $query ;
				     $sql_query = pg_query($query);
					$num_row=pg_num_rows($sql_query);
					if($num_row!=0){
					while($sql_row = pg_fetch_array($sql_query))
				{	
				//$period_date = $sql_row['period_date'];
				$expire_date = $sql_row['expire_date'];
				$pay_type = $sql_row['pay_type'];
				
				if($pay_type==2){
				$amount = $sql_row['amount'];
				$amount_month = $sql_row['amount_month'];
				
				if(($amount/300)==$amount_month)$pay_type=0; //ถ้าหาร 300 ลงตัว
				else $pay_type=1;
				}
				
				if($expire_date==""){
					$cre_fr=pg_query("select join_date_diff_month('$start_pay_date','1')"); // ลบ 1 เดือน หลังจาก ชำระครั้งแรก
		$expire_date=pg_fetch_result($cre_fr,0); 

				} 

				}
					}
					else{ // ชำระครั้งแรก
						$pay_type = 0 ; //300
						
						$cre_fr=pg_query("select join_date_diff_month('$start_pay_date','1')"); // ลบ 1 เดือน หลังจาก ชำระครั้งแรก
						
		$expire_date=pg_fetch_result($cre_fr,0); 
		
		
						
					}
					// ค่าค้างชำระ
					$ar_qr=pg_query("select join_arrears_cal('$expire_date', '$pay_date', '$pay_type','$change_pay_type')"); // คำนวณค่าค้างชำระ
					$arrears=pg_fetch_result($ar_qr,0); 
					//echo "select join_arrears_cal('$expire_date', '$pay_date', '$pay_type','$change_pay_type')";
					echo $arrears.'#'.date_ch_form_m($expire_date).'#'.$expire_date.'#'.$pay_type;
		
	   
?>