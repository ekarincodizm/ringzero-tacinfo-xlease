<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");

$amount =$_REQUEST[amount];
$status =0;
$ta_join_payment_id = generate_id("TAJ-","ta_join_payment" ,"pay_date::text","$_POST[pay_date]",4);
$change_pay_type=$_REQUEST['change_pay_type'];
$id =$_REQUEST[id];
$pay_date= $_POST[pay_date];
$o_channel = "ปรับปรุงใหม่";
$reason = $_POST[reason];
$pay_type =$_POST['pay_type'];
$query1 = "select join_cal_payment($id,$change_pay_type,$amount,'$pay_date','$ta_join_payment_id','$o_channel','".$_SESSION["av_iduser"]."','$reason',$pay_type)";

	
if($res_inss=pg_query($query1)){	
		}else{
			$status=$status+1;
			//echo $query1;
		}
echo $status;
	     
?>