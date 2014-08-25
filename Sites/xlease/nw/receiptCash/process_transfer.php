<?php
session_start();
include("../../config/config.php");

header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id_user=$_SESSION["av_iduser"];
$idno=$_POST["p_idno"];
//หา CusID
$qrycus=pg_query("select \"CusID\" from \"RadioContract\" a
left join \"GroupCus_Active\" b on a.\"RadioRelationID\"=b.\"GroupCusID\" 
where a.\"COID\"='$idno' and b.\"CusState\"='0'");
$res_cus=pg_fetch_array($qrycus);
$CusID=$res_cus["CusID"];

$pay_brn=$_POST["brn_pay"];

$datenow=date("Y-m-d");
$dq=$_POST["qryDate"];

if(empty($_POST["typepayment"])){
	echo "ไม่มีค่าอื่น ๆ";
}else{ //มีการจ่ายเกิดขึ้น
	pg_query("BEGIN");	
	$status=0;
	
 	$qry_post=pg_query("select gen_pos_no('$datenow')");
 	$res_genpost=pg_fetch_result($qry_post,0); //postID
 	//end gen postcode
  
    //insert PostLog 
	$sql_ipostlog="insert into \"PostLog\"
	               (\"PostID\",\"UserIDPost\",\"PostDate\",paytype ) 
	               values 
				   ('$res_genpost','$id_user','$datenow','$pay_brn')";
	if($result=pg_query($sql_ipostlog)){
	}else{
		$status++;
	}			   
	  
	
  	for($i=0;$i<count($_POST["typepayment"]);$i++){
		if($_POST["typepayment"][$i] != ""){	   
			$tpay=$_POST['typepayment'][$i];
			if($tpay=="299"){
				 $amtpay_299=$_POST['amt'][$i];
				 $amtpay="-".$amtpay_299;  
			}else{
				 $amtpay=$_POST['amt'][$i];
			}				
					
			$sql_idtotr="insert into \"FCash\"
					(\"PostID\",\"CusID\",\"IDNO\",\"TypePay\",\"AmtPay\" ) 
					values 
					('$res_genpost','$CusID','$idno','$tpay','$amtpay')";
			if($result_fr=pg_query($sql_idtotr)){
			}else{
				$status++;
			}			   	
		}
	}
}
  
  
if($status==0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) รับเงินสดใบเสร็จชั่วคราว (วิทยุลูกค้านอก)', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "บันทึกข้อมูลเรียบร้อย ";
	echo "<meta http-equiv=\"refresh\" content=\"0;URL=pass_acc_receipt.php?pid=$res_genpost&dateqq=$dq\" >";
	//echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";
}else{
	pg_query("ROLLBACK");
	echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";	
}

?>