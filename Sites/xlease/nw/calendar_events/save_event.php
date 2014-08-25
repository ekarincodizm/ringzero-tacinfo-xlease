<?php
/*
File name : saveAppointment.php
Description : ใช้สำหรับบันทึกข้อมูลการนัดหมายและมีการเก็บ logs การใช้งาน ซึ่งบันทึกลงที่ตาราง calendar_events,calendar_events_logs
              ใช้สำหรับการบันทึกข้อมูล 1 transaction
*/

//session_start(); 
include('../../config/config.php');
include('../function/checknull.php');

//========== ประกาศตัวแปรสำหรับใช้งานภายใน Class ==========//
$trans_action = "";
$trans_status = 0;
 
//========= ตัวแปรสำหรับการรับค่าจากหน้าจอ frm_event.php =========//
$id = pg_escape_string($_POST["id"]);
$title = pg_escape_string($_POST["title"]);
$description = pg_escape_string($_POST["description"]);
$place = pg_escape_string($_POST["place"]);
$shared = pg_escape_string($_POST["shared"]);
$events_status = pg_escape_string($_POST["events_status"]);
$start_time = $_POST["starttime_hr"].":".$_POST["starttime_min"];
$end_time = $_POST["endtime_hr"].":".$_POST["endtime_min"];
$day = pg_escape_string($_POST["bday"]);
$month = pg_escape_string($_POST["bmonth"]);
$year = pg_escape_string($_POST["byear"]);
$created_by = $_SESSION["av_iduser"];
$created_date = nowDateTime(); 
$action = pg_escape_string($_POST["action"]);
$num_of_month = pg_escape_string($_POST["num_of_month1"]);
$need_gen_events = pg_escape_string($_POST["need_gen_events"]);;


 foreach($_POST['contract_ref'] as $i=>$item){
	$ret[] = $item;
} 
	$contract_ref =  implode(',',$ret);

if($need_gen_events == "1"){
	$month = $month - 1;
}else {
	$month = $month;
}

if($action == "ADD"){
    $trans_action = "ADD";
    $events_id = date("YmdHis");
}else if($action == "EDIT"){
    $trans_action = "EDIT";
    $events_id = pg_escape_string($_POST["events_id"]);
}

    pg_query("BEGIN");
    
    //========== insert to table calendar_events ==========//
	
	if($action == "ADD"){// action = add
		if($need_gen_events == "1"){// insert mutiple record
			for($i = 1;$i<=$num_of_month;$i++)
		{
			$month++;
			
			if($month > 12 ){
					$year = $year+1;
				$month = 1;
			}
			
			$sql_insert_events = " INSERT INTO calendar_events( events_id,title,description,place,shared,events_status,
															  start_time,end_time,day,month,year,approved,created_by,created_date,
																  contract_ref,action,flag )
															VALUES( '$events_id','$title','$description','$place','$shared','$events_status',
																   '$start_time','$end_time','$day','$month','$year','1','$created_by','$created_date',
																   '$contract_ref','$trans_action','1' )";
			if($sql_insert_events){
				$obj_insert_events = pg_query($sql_insert_events);
			}else{
				$trans_status++;
			}
		}
		}else{// insert single record
		
			$sql_insert_events = " INSERT INTO calendar_events( events_id,title,description,place,shared,events_status,
															  start_time,end_time,day,month,year,approved,created_by,created_date,
															  contract_ref,action,flag )
															VALUES( '$events_id','$title','$description','$place','$shared','$events_status',
																  '$start_time','$end_time','$day','$month','$year','1','$created_by','$created_date',
																  '$contract_ref','$trans_action','1' )";
			if($sql_insert_events){
				$obj_insert_events = pg_query($sql_insert_events);
				//echo $obj_insert_events;
			}else{
				$trans_status++;
			}	
		}
		
		
	}else{// action = edit
		$sql_update_events =" UPDATE calendar_events SET \"flag\" = '0' 
														WHERE \"id\" = '$id' ";
								
			if($sql_update_events){
				$obj_update_events = pg_query($sql_update_events);
				
			}else{
				$trans_status++;
			}						
														
		$sql_insert_events = " INSERT INTO calendar_events( events_id,title,description,place,shared,events_status,
															  start_time,end_time,day,month,year,approved,created_by,created_date,
															  contract_ref,action,flag )
															VALUES( '$events_id','$title','$description','$place','$shared','$events_status',
																  '$start_time','$end_time','$day','$month','$year','1','$created_by','$created_date',
																  '$contract_ref','$trans_action','1' )";
			if($sql_insert_events){
				$obj_insert_events = pg_query($sql_insert_events);
				//echo $obj_insert_events;
			}else{
				$trans_status++;
			}	
	}
	
	if($trans_status == 0){
        //========= insert to table calendar_events_logs =========//
        $sql_insert_events_logs = "INSERT INTO calendar_events_logs( id_user,action,action_date,events_id ) 
                                                         VALUES( '$created_by','$trans_action','$created_date','$events_id')";
    }
    if($sql_insert_events_logs){
       $obj_insert_events_logs = pg_query($sql_insert_events_logs);
    }else{$trans_status++;}
  
    if($trans_status == 0){
        pg_query("COMMIT");
		echo "บันทึกข้อมูลเรียบร้อยแล้ว";
    }else{
		pg_query("ROOLBACK");
        echo "ไม่สามารถบันทึกข้อมูลได้";
    } 
    
?>
     