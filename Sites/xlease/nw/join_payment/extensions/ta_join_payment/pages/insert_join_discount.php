<?php
session_start();
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$amount =$_REQUEST[amount];
$status =0;
$a= '"FOtherpayDiscount"';
$b= '"O_RECEIPT"';
$ta_join_payment_id = $ta_join_pm_id = generate_id("DC-",$a ,$b,"$_POST[pay_date]",4);
$change_pay_type=$_REQUEST['change_pay_type'];

if($change_pay_type==1)$get_arg=1;else $get_arg=2;
	$O_Type_q=pg_query("select join_get_join_type($get_arg)");
            $O_Type_f=@pg_fetch_result($O_Type_q,0);
			list($O_Type,$xx)=explode("#",$O_Type_f,2);
			

$query1 =	"INSERT INTO \"FOtherpayDiscount\" (
		
										\"IDNO\",
										\"O_DATE\",
										\"O_RECEIPT\",
										\"O_MONEY\",
										\"O_Type\",
										\"O_BANK\",
										\"O_PRNDATE\",
										\"PayType\",
										\"O_memo\",
										create_by,
										create_datetime
										
										) 
							VALUES(
							           '$_POST[idno]',
									   '$_POST[pay_date]',
									   '$ta_join_payment_id',
									   '$amount',
									   '$O_Type',
									   'CA',
									   '$_POST[pay_date]',
									   'DC',
									   '$_POST[reason]',
									   '".$_SESSION["av_iduser"]."',
									   '$info_currentdatetimesql2')";

				//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ขอส่วนลดเข้าร่วม', '$add_date')");
				//ACTIONLOG---
				
if($res_inss=pg_query($query1)){	
		}else{
			$status=$status+1;
			//echo $query1;
		}
echo $status;
	     
?>