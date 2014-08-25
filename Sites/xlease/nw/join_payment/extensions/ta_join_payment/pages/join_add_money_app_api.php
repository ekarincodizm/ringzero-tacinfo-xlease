<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");


pg_query("BEGIN WORK");
$status= 0;
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$id =$_REQUEST[id];
$st =$_REQUEST[st];
$memo_app =$_REQUEST['memo'];
if($st==""){
	$memo_app =$_POST['memo_app'];
	$id =$_POST['id'];
	if(isset($_POST["appv"])){
		$st='1';//อนุมัติ
	}else{
		$st='2';//ไม่อนุมัติ
	}
}

if($st=='1'){ //อนุมัติ
$qry_fr=pg_query("SELECT f.\"amount\",f.approve_status,f.create_by,f.create_datetime,f.\"memo\",f.pay_type,f.pay_date,f.change_pay_type,f.id_main FROM \"ta_join_add_money_app\" f left join \"VJoinMain\" m on m.id = f.\"id_main\" 
			WHERE f.id='$id' ");
			$nub=pg_num_rows($qry_fr);


if($sql_row4=pg_fetch_array($qry_fr)){
				

					$reason =$sql_row4['memo']; 
					$approve_status = $sql_row4['approve_status'];
					$amount =$sql_row4['amount']; 
					$create_by = $sql_row4['create_by'];
					$id_main = trim($sql_row4['id_main']);
					
					$pay_type =$sql_row4['pay_type']; 
					$pay_date =$sql_row4['pay_date']; 
					$change_pay_type =$sql_row4['change_pay_type']; 
}
$o_channel = "อนุมัติการเพิ่มเงินเข้าระบบเข้าร่วม";
$ta_join_payment_id = generate_id("TAJ-","ta_join_payment" ,"pay_date::text","$pay_date",4);

$query1 = "select join_cal_payment($id_main,$change_pay_type,$amount,'$pay_date','$ta_join_payment_id','$o_channel','$create_by','$reason',$pay_type)";

	
if($res_inss=pg_query($query1)){	
$query =	"UPDATE \"ta_join_add_money_app\"  SET
					approve_status  ='1',
					approver  = '".$_SESSION["av_iduser"]."',
					memo_app  = '$memo_app',
					approve_dt  = '$info_currentdatetimesql2'
					where \"id\" ='$id'";		
				
if($res_inss=pg_query($query)){	

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติการขอเพิ่มเงินเข้าระบบเข้าร่วม $id', '$datelog')");
	//ACTIONLOG---

		}else{
			$status=$status+1;
			//echo $query1;
		}


		}else{
			$status=$status+1;
			//echo $query1;
		}
}else if($st=='2'){ //ไม่อนุมัติ
$query =	"UPDATE \"ta_join_add_money_app\"  SET
					approve_status  ='2',
					approver  = '".$_SESSION["av_iduser"]."',
					memo_app  = '$memo_app',
					approve_dt  = '$info_currentdatetimesql2'
					where \"id\" ='$id'";	
				
if($res_inss=pg_query($query)){	

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ไม่อนุมัติการขอเพิ่มเงินเข้าระบบเข้าร่วม $id ', '$datelog')");
	//ACTIONLOG---

		}else{
			$status=$status+1;
			//echo $query1;
		}
	
}
		
		
if($status == 0){   
		pg_query("COMMIT");
		$script= '<script language=javascript>';
		$script.= " alert('บันทึกรายการเรียบร้อย');location.href='join_add_money_approve.php';";
		$script.= '</script>';
		echo $script;

		//echo "0";
	}else{
		pg_query("ROLLBACK");
		$script= '<script language=javascript>';
		$script.= " alert('ไม่สามารถบันทึกได้');location.href='join_add_money_approve.php';";
		$script.= '</script>';
		echo $script;
		//echo $status;
	}
	/*
	if($st=='1'){ //อนุมัติ
	
	$query =	"UPDATE \"ta_join_payment\"  SET
					period_date  =NULL,
					expire_date  = NULL
					where \"id_main\" ='$id_main' and deleted='0' ";		
				
$res_inss=pg_query($query);
	}
	     */
?>