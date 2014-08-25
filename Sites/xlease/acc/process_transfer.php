<?php
include("../config/config.php");
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$id_user=$_SESSION["av_iduser"];
$idno=pg_escape_string($_POST["p_idno"]);

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$disc_fr=pg_escape_string($_POST["dsc_fr"]);

$pay_brn=pg_escape_string($_POST["brn_pay"]);

$datenow=date("Y-m-d");
$dq=pg_escape_string($_POST["qryDate"]);
$c_cusid=pg_escape_string($_POST["p_cusid"]);
if(($_POST["count_fr"]=="0") AND (empty($_POST["typepayment"])))
{
 //echo "ไม่มีการจ่ายใด ๆ" ;
 
}
else
{
 	//echo "มีการจ่ายเกิดขึ้น";
	
	//for close FP
	if($disc_fr==0)
	{
	  //ไม่มีจ่าย
	}
	else
	{
	 //ปิดบัญชี 
	$in_sql="update \"Fp\" SET  \"P_SLBAK\"='$disc_fr',\"P_CLDATE\"='$dq',
	         \"P_StopVatDate\"='$dq',\"P_ACCLOSE\"=TRUE ,\"P_StopVat\"=TRUE
	
	 where \"IDNO\"='$idno'";   
	 if($result=pg_query($in_sql))
	 {
 		 $status ="OK Update at Fa1".$in_sql;
 	 }
 	 else
 	 {
  		 $status ="error Update  Fa1 Re".$in_sql;
 	 }
	 echo $status;
  }
	
	
	// end clise FP
	
 	//gen postcode 
	
	   //begin  commit
	   
   pg_query("BEGIN");	
	
	
 	$qry_post=pg_query("select gen_pos_no('$datenow')");
 	$res_genpost=pg_fetch_result($qry_post,0); //postID
 	//end gen postcode
  
    //insert PostLog 
	$sql_ipostlog="insert into \"PostLog\"
	               (\"PostID\",\"UserIDPost\",\"PostDate\",paytype ) 
	               values 
				   ('$res_genpost','$id_user','$datenow','$pay_brn')";
	if($result=pg_query($sql_ipostlog))
	 {
	  $status ="OK".$sql_ipostlog;
	 }
	 else
	 {
	  $status ="error insert sql".$sql_ipostlog;
	 }			   
	 echo $status;
	  
 //insert _ Fcash
	 
	  if($_POST["count_fr"]=="0")
	  {
  		//echo "ไม่มีค่างวด"; 
	  }
	  else
	  { 
 		//echo pg_escape_string($_POST["count_fr"]);
		
		$amt_tr=pg_escape_string($_POST["rescal"]);
		
		$sql_idtltr="insert into \"FCash\"
	              	   (\"PostID\",\"CusID\",\"IDNO\",\"TypePay\",\"AmtPay\" ) 
	               	    values 
				   	   ('$res_genpost','$c_cusid','$idno',1,'$amt_tr')";
		if($result_fr=pg_query($sql_idtltr))
		 {
		  $st_fr ="OK".$sql_idtltr;
		 }
		 else
		 {
		  $st_fr ="error insert sql".$sql_idtltr;
		 }			   
		 echo $st_fr;
			
	}

  
	


  //end comit

	if(empty($_POST["typepayment"]))
	{
		echo "ไม่มีค่าอื่น ๆ";
	}
	else
	{
  		for($i=0;$i<count($_POST["typepayment"]);$i++)
   		{
		  
			if(pg_escape_string($_POST["typepayment"][$i]) != "")
			{	   
			    //echo pg_escape_string($_POST['typepayment'][$i])." amt = ".pg_escape_string($_POST['amt'][$i])."<br>";
				$tpay=pg_escape_string($_POST['typepayment'][$i]);
				if($tpay=="299")
				{
				  $amtpay_299=pg_escape_string($_POST['amt'][$i]);
				  $amtpay="-".$amtpay_299;
				  
				}
				else
				{
				  $amtpay=pg_escape_string($_POST['amt'][$i]);
				}				
				
					
				$sql_idtotr="insert into \"FCash\"
							   (\"PostID\",\"CusID\",\"IDNO\",\"TypePay\",\"AmtPay\" ) 
								values 
							   ('$res_genpost','$c_cusid','$idno','$tpay','$amtpay')";
				if($result_fr=pg_query($sql_idtotr))
				 {
				  $st_otr ="OK".$sql_idtotr;
				 }
				 else
				 {
				  $st_otr ="error insert sql".$sql_idtotr;
				 }			   
				 echo $st_otr;
				
			}
	    }
     
	
    }
  
  
  

	 if(($res_genpost) and ($result)  and ($result_fr))
		  {
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) รับเงินสดใบเสร็จชั่วคราว', '$add_date')");
			//ACTIONLOG---
		   pg_query("COMMIT");
		   echo "บันทึกข้อมูลเรียบร้อย ";
		   //echo "<meta http-equiv=\"refresh\" content=\"2;URL=../list_menu.php\" >";
		   echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";
		  }
		  else
		  {
			pg_query("ROLLBACK");
			
			//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
			echo "<input type=\"button\" value=\"CLOSE\" onclick=\"javascript:window.close();\" />";
			
		  }
  
  

  
  
  
  	
  echo "<meta http-equiv=\"refresh\" content=\"0;URL=pass_acc_receipt.php?pid=$res_genpost&dateqq=$dq\" >";
}
?>