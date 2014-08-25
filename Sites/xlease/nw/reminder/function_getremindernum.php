<?php
function getreminder_num($focusdate,$qry_fuc_yesterday,$date_yesterday,$tabid){
	$sum_c=0;
	while($res_fuc_getreminder=pg_fetch_array($qry_fuc_yesterday)){
		$table= $res_fuc_getreminder["table"];			
		if($table=='reminder'){
			$reminder_type= $res_fuc_getreminder["reminder_type"];
			$main_reminder= $res_fuc_getreminder["reminder_id"];
			$reminder_doerstamp= $res_fuc_getreminder["reminder_doerstamp"];			
			while($reminder_doerstamp <=$date_yesterday){
				$reminder_job_date=$reminder_doerstamp;
				if($reminder_type=='4'){//ทุกวัน	
					$c = getremindernum($focusdate,$main_reminder,$reminder_job_date,$tabid); 					
					$sum_c=$sum_c+$c;
				}
				else if($reminder_type=='3'){//เตือนเฉพาะวันที่
					str_replace("-","",$reminder_doerstamp);
					$reminder_ref= $res_fuc_getreminder["reminder_ref"];
					if($reminder_ref==$replace_str){
							$c = getremindernum($focusdate,$main_reminder,$reminder_job_date,$tabid); 
							$sum_c=$sum_c+$c;
					}
				}
				else if($reminder_type=='2'){						
					$reminder_ref= $res_fuc_getreminder["reminder_ref"];						
					$qry=pg_query("SELECT \"reminder_typeweek\"('$reminder_doerstamp'::date, '$reminder_ref'::text)");
					$re=pg_fetch_array($qry);
					list($resu)=$re;
					if($resu=='t'){
						$c = getremindernum($focusdate,$main_reminder,$reminder_job_date,$tabid); 
						$sum_c=$sum_c+$c;
					}
				}
				else if($reminder_type=='1'){//เตือนทุกวันที่
					$reminder_ref= $res_fuc_getreminder["reminder_ref"];
					list($year,$month,$day)=explode("-",$reminder_doerstamp);						
					if($day==$reminder_ref){
						$c = getremindernum($focusdate,$main_reminder,$reminder_job_date,$tabid); 
						$sum_c=$sum_c+$c;
					}
				}
				$reminder_doerstamp= date ("Y-m-d", strtotime("+1 day", strtotime($reminder_doerstamp)));
			}
		}		
		else if($table=='reminder_job'){
			$main_reminder= $res_fuc_getreminder["main_reminder_id"];
			$reminder_job_date= $res_fuc_getreminder["reminder_job_date"];				
			$c = getremindernum($focusdate,$main_reminder,$reminder_job_date,$tabid); 
			$sum_c=$sum_c+$c;
		}
	}
return 	$sum_c;
}


function getremindernum($focusdate,$main_reminder,$reminder_job_date,$tabid){
			$n=0;
			$re_reminder='';
			$reminder_job_date=date('Y-m-d', strtotime($reminder_job_date));			
			// ---------------------------------------------------------------------------------------------
			// กำหนดสีของรายการ ถ้ายังไม่ได้ดำเนินการ เป็นสีส้ม ถ้าดำเนินการแล้ว เป็นสีเขียว
			// ---------------------------------------------------------------------------------------------
			if($tabid=='1'){
				if($focusdate > nowDate()){				
					$qry_status=pg_query("SELECT *
										FROM \"reminder\" 
										WHERE 
										(\"reminder_status\"='1' or
										\"reminder_canceluserstamp\" ::date >= '$reminder_job_date') AND
										\"reminder_id\" ='$main_reminder' ");
				
					$num_row=pg_num_rows($qry_status);
					if($num_row>0){	
						$qry_status=pg_query("SELECT \"reminder_job_status\"
										FROM \"reminder_job\" 
										WHERE \"reminder_job_id\" IN
										(SELECT MAX(reminder_job_id)
										FROM \"reminder_job\" 
										WHERE 
										\"reminder_id\"= '$main_reminder' AND
										\"reminder_job_date\"::date ='$reminder_job_date' -- 1เฉพาะ job การติดตามของวันที่ที่สนใจ										
										)
									");
					$num_row_1=pg_num_rows($qry_status);
					if($num_row_1>0){
						$re_reminder='true';
						$res_status=pg_fetch_array($qry_status);						
					}
					}else{
						$re_reminder='false';
					}					
				}
				else{
					$qry_status=pg_query("SELECT \"reminder_job_status\"
										FROM \"reminder_job\" 
										WHERE \"reminder_job_id\" IN
										(SELECT MAX(reminder_job_id)
										FROM \"reminder_job\" 
										WHERE 
										\"reminder_id\"= '$main_reminder' AND
										\"reminder_job_date\"::date ='$reminder_job_date' -- 1เฉพาะ job การติดตามของวันที่ที่สนใจ										
										)
									");
					$num_row=pg_num_rows($qry_status);
					if($num_row>0){
						$re_reminder='true';
					}else{
						$re_reminder='false';
					}
					$res_status=pg_fetch_array($qry_status);
				}
				
			}else{
				if($focusdate > nowDate()){
					$qry_status=pg_query("
										SELECT MAX(reminder_job_status) as reminder_job_status
										FROM \"reminder_job\"
										WHERE
										\"reminder_id\"='$main_reminder' AND
										\"reminder_job_date\"='$reminder_job_date' -- 2เฉพาะ job การติดตามของวันที่ที่สนใจ
							");
				}else{
					$qry_status=pg_query("SELECT \"reminder_job_status\" 
										FROM \"reminder_job\" 
											WHERE \"reminder_job_id\" IN
											(SELECT MAX(reminder_job_id)
												FROM \"reminder_job\" 
												WHERE 
												\"reminder_id\"='$main_reminder' AND
												\"reminder_job_date\" ='$reminder_job_date' -- เฉพาะ job การติดตามของวันที่ที่สนใจ
											)
										");								
				}
				$res_status=pg_fetch_array($qry_status);
			}			
			if(((($tabid=='1')and($res_status["reminder_job_status"] == '1')) or ( $re_reminder=='false'))){$n=0;}	
			else{
				$n++;
				
			}
return $n;
}			
					