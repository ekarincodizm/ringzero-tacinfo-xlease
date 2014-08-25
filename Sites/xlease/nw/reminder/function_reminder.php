<?php
function getreminderquery($focusdate, $get_userid,$tab_id){
	// ---------------------------------------------------------------------------------------------
	// ถ้าไม่มีการกำหนดเวลามาให้ใช้เป็นวันทีปัจจุบัน
	// ---------------------------------------------------------------------------------------------
	if ($focusdate == '--' || $focusdate == '' ||$focusdate == NULL) {
		$focusdate=nowDate();
	}	
	
	// ---------------------------------------------------------------------------------------------
	// +0 เพื่อแปลงเป็นตัวเลข จาก 01 จะเป็น 1 เป็นการตัด 0 ตัวหน้าออกถ้ามี
	// ---------------------------------------------------------------------------------------------
	$year = substr($focusdate,0,4); // ปี
	$month = substr($focusdate,5,2); // เดือน
	$day = substr($focusdate,8,2) + 0; // วัน
	$dayeng = date('l',strtotime($focusdate)); // วัน เช่า monday, friday
					
	// ---------------------------------------------------------------------------------------------
	// หาวันที่ว่าเป็นวันอะไร และเป็นที่เท่าไหร่ของเดือน
	// ---------------------------------------------------------------------------------------------
	if ($dayeng == 'Monday'){
		$checkdata = '1';
	} else if ($dayeng == 'Tuesday'){
		$checkdata = '2';
	} else if ($dayeng == 'Wednesday'){
		$checkdata = '3';
	} else if ($dayeng == 'Thursday'){
		$checkdata = '4';
	} else if ($dayeng == 'Friday'){
		$checkdata = '5';
	} else if ($dayeng == 'Saturday'){
		$checkdata = '6';
	} else if ($dayeng == 'Sunday'){
		$checkdata = '7';
	}
					
	// กรณีที่ให้ขึ้นทุกสัปดาห์
	$checkdata1 = $checkdata.'0';
	// buffer focusdate
	$chkfocusdate = $focusdate;
					
	// ---------------------------------------------------------------------------------------------
	// หา reminder_ref ของ reminder_type = 1 ว่าวันนี้เป็นวันที่เท่าไหร่
	// ---------------------------------------------------------------------------------------------
	$checkdata0 = $day;
					
	// ---------------------------------------------------------------------------------------------
	// หา reminder_ref ของ reminder_type = 2 ว่าวันนี้เป็นวันอะไรที่เท่าไหร่ของเดือน
	// ---------------------------------------------------------------------------------------------
	if ($chkfocusdate == date('Y-m-d',strtotime('1 '.$dayeng.' '.$year.'-'.$month))) {
		$checkdata2 = $checkdata.'1';
		
	} else if ($chkfocusdate == date('Y-m-d',strtotime('2 '.$dayeng.' '.$year.'-'.$month))) {
		$checkdata2 = $checkdata.'2';
	} else if ($chkfocusdate == date('Y-m-d',strtotime('3 '.$dayeng.' '.$year.'-'.$month))) {
		$checkdata2 = $checkdata.'3';
	} else if ($chkfocusdate == date('Y-m-d',strtotime('4 '.$dayeng.' '.$year.'-'.$month))) {
		$checkdata2 = $checkdata.'4';
		// กรณีที่สัปดาห์ที่ 4 เป็นสัปดาห์สุดท้ายของเดือนสัปดาห์ต่อไปเดือนจะต้องคนละเดือนกัน จะต้องให้ถือตัวนี้เป็นตัวสุดท้ายของเดือนด้วย
		if (date('Y-m',strtotime($chkfocusdate)) != date('Y-m',strtotime('5 '.$dayeng.' '.$year.'-'.$month))) {
			$checkdata2 = $checkdata2." OR \"reminder_ref\"=".$checkdata.'9';
			
		}
	} else if ($chkfocusdate == date('Y-m-d',strtotime('5 '.$dayeng.' '.$year.'-'.$month))) {
		$checkdata2 = $checkdata.'9';
	}

	// ---------------------------------------------------------------------------------------------
	// หา reminder_ref ของ reminder_type = 3 ว่าเป็นรหัสอะไร
	// ---------------------------------------------------------------------------------------------	
	$checkdata3 = str_replace('-', '', $focusdate);	
	
	//ตรวจสอบว่า  tab อนาคต หรือหากเลือกวันปัจจุบันเป็นอนาคต จะยังต้องเห็นรายการตามหลักการเดิม
	if($tab_id=='1'){
		//จะไม่แสดงรายการที่ปิดไปแล้ว
		//กรณีที่ที่วันที่เป็นอนาคต
		if($focusdate > nowDate()){		
			$qry_fuc=pg_query("
						SELECT 'reminder' as \"table\",* FROM \"reminder\" 
						WHERE 
							reminder_doerstamp::date <= '$focusdate' AND -- แสดงรายการนับตั้งแต่วันที่ตั้ง
							reminder_expiredate >= '$focusdate' AND -- จะต้องไม่แสดงรายการที่หมดอายุแล้ว
							( -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active หรือ ที่ไม่ acitve แสดงถึงวันที่ยกเลิก
								reminder_status = '1'::smallint OR
								reminder_status = '0'::smallint AND reminder_canceluserstamp::date >= '$focusdate' -- แสดงรายการดังกล่าวจนถึงวันที่ยกเลิก
							) AND
							( -- แสดงรายการที่เป็น public ทุกรายการ และแสดงรายการ private เฉพาะที่เป็นของ user นั้นๆ
								reminder_isprivate = '0'::smallint OR -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active
								(reminder_isprivate = '1'::smallint AND reminder_doerid = '$get_userid')
							) /*AND
							(
								(\"reminder_type\"=1 AND \"reminder_ref\"=$checkdata0) OR
								(\"reminder_type\"=2 AND (\"reminder_ref\"=$checkdata2 OR \"reminder_ref\"=$checkdata1)) OR
								(\"reminder_type\"=3 AND \"reminder_ref\"=$checkdata3) OR
								(\"reminder_type\"=4 AND \"reminder_ref\"=0) -- เตือนทุกวัน
							)*/
							ORDER BY \"reminder_doerstamp\" ASC"
						);		
		}
		else{
			$qry_fuc=pg_query("select 'reminder_job' as \"table\",\"main_reminder_id\",\"reminder_job_date\",\"reminder_type\",\"reminder_ref\" from (
						select DISTINCT(a.\"reminder_id\") as \"main_reminder_id\",a.\"reminder_job_date\",\"reminder_type\",\"reminder_ref\" from reminder_job a
						left join reminder b on a.\"reminder_id\"=b.\"reminder_id\"
						where a.reminder_job_date <='$focusdate'  and
						( -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active หรือ ที่ไม่ acitve แสดงถึงวันที่ยกเลิก
							b.reminder_status = '1'::smallint OR
							b.reminder_status = '0'::smallint AND reminder_canceluserstamp::date >= '$focusdate' -- แสดงรายการดังกล่าวจนถึงวันที่ยกเลิก
						) AND
						( -- แสดงรายการที่เป็น public ทุกรายการ และแสดงรายการ private เฉพาะที่เป็นของ user นั้นๆ
							b.reminder_isprivate = '0'::smallint OR -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active
							(b.reminder_isprivate = '1'::smallint AND b.reminder_doerid = '$get_userid')
						)
						AND
						( 	(SELECT MAX(reminder_job_status)
								FROM \"reminder_job\"
								WHERE \"reminder_id\"=a.\"reminder_id\"
								and \"reminder_job_date\"=a.\"reminder_job_date\"
							 ) <>'1'
						)) a  
						where  
						(\"reminder_type\"=1 AND substring(\"reminder_job_date\"::text from 9 for 2)::integer=a.\"reminder_ref\"::integer) OR
						(\"reminder_type\"=2 AND (SELECT \"reminder_typeweek\"(a.\"reminder_job_date\"::date, a.\"reminder_ref\"::text)))OR
						(\"reminder_type\"=3 AND REPLACE(\"reminder_job_date\"::text,'-','')= a.\"reminder_ref\"::text ) OR
						(\"reminder_type\"=4 AND \"reminder_ref\"=0) -- เตือนทุกวัน 
						order by a.\"reminder_job_date\" ASC");		
			}
	} else {
		if($focusdate > nowDate()){
			$qry_fuc=pg_query("
						SELECT 'reminder_job' as \"table\",\"reminder_id\" as \"main_reminder_id\",'$focusdate' as \"reminder_job_date\" FROM \"reminder\" a
						WHERE 
							reminder_doerstamp::date <= '$focusdate' AND -- แสดงรายการนับตั้งแต่วันที่ตั้ง
							reminder_expiredate >= '$focusdate' AND -- จะต้องไม่แสดงรายการที่หมดอายุแล้ว
							( -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active หรือ ที่ไม่ acitve แสดงถึงวันที่ยกเลิก
								reminder_status = '1'::smallint OR
								(reminder_status = '0'::smallint AND reminder_canceluserstamp::date >= '$focusdate' -- แสดงรายการดังกล่าวจนถึงวันที่ยกเลิก 
								
								)
							) AND
							( -- แสดงรายการที่เป็น public ทุกรายการ และแสดงรายการ private เฉพาะที่เป็นของ user นั้นๆ
								reminder_isprivate = '0'::smallint OR -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active
								(reminder_isprivate = '1'::smallint AND reminder_doerid = '$get_userid')
							) AND
							(
								(\"reminder_type\"=1 AND \"reminder_ref\"=$checkdata0) OR
								(\"reminder_type\"=2 AND (\"reminder_ref\"=$checkdata2 OR \"reminder_ref\"=$checkdata1)) OR
								(\"reminder_type\"=3 AND \"reminder_ref\"=$checkdata3) OR
								(\"reminder_type\"=4 AND \"reminder_ref\"=0) -- เตือนทุกวัน
							)
							
							ORDER BY \"reminder_doerstamp\" ASC");
		
		}else{
			$qry_fuc=pg_query("select DISTINCT(a.\"reminder_id\") as \"main_reminder_id\",a.\"reminder_job_date\",'reminder_job' as \"table\" from reminder_job a
						left join reminder b on a.\"reminder_id\"=b.\"reminder_id\"
						where a.reminder_job_date='$focusdate'  and
						( -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active หรือ ที่ไม่ acitve แสดงถึงวันที่ยกเลิก
							b.reminder_status = '1'::smallint OR
							b.reminder_status = '0'::smallint AND reminder_canceluserstamp::date >= '$focusdate' -- แสดงรายการดังกล่าวจนถึงวันที่ยกเลิก
						) AND
						( -- แสดงรายการที่เป็น public ทุกรายการ และแสดงรายการ private เฉพาะที่เป็นของ user นั้นๆ
							b.reminder_isprivate = '0'::smallint OR -- จะต้องแสดงรายการที่ยังมีสถานะเป็น Active
							(b.reminder_isprivate = '1'::smallint AND b.reminder_doerid = '$get_userid')
						)
						AND a.reminder_job_status='-1' 
						order by a.\"reminder_job_date\" ASC");
		}
	}
	return $qry_fuc;	
}
?>