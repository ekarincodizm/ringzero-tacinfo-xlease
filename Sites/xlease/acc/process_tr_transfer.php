<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$branch_id=$_SESSION["av_officeid"];
$id_user=$_SESSION["av_iduser"];
include("../config/config.php");
$idno=pg_escape_string($_POST["p_idno"]);
$pay_brn=pg_escape_string($_POST["brn_pay"]);
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$datenow=date("Y-m-d");
$bnk_br=pg_escape_string($_POST["b_br"]);
$bnk_no=pg_escape_string($_POST["bank_tr"]);
$date_tr=pg_escape_string($_POST["qryDate"]);
$hhs=pg_escape_string($_POST["hh"]);
$mms=pg_escape_string($_POST["mm"]);
$time_tr=$hhs.":".$mms.":"."00";
$c_cusid=pg_escape_string($_POST["p_cusid"]);

pg_query("BEGIN WORK");
$status = 0;

if(($_POST["count_fr"]=="0") AND (empty($_POST["typepayment"])))
{
 //echo "ไม่มีการจ่ายใด ๆ" ;
}
else
{
    for($i=0;$i<count($_POST["typepayment"]);$i++)
 	{
  		if($_POST["typepayment"][$i] != "")
  		{	   
	  	//echo pg_escape_string($_POST['typepayment'][$i])." amt = ".pg_escape_string($_POST['amt'][$i])."<br>";
	  	$res_amt=$res_amt+pg_escape_string($_POST['amt'][$i]);
  		}		
 
	}
 
 	$amt_fr=pg_escape_string($_POST["rescal"]);
 	$total_other=$res_amt; //sum otherpay
 
    $sumtotal=$amt_fr+$total_other;
    
	
 	//echo "มีการจ่ายเกิดขึ้น";
	
 	//gen postcode 
 	if($qry_post=pg_query("select gen_pos_no('$datenow')")){
		$res_genpost=pg_fetch_result($qry_post,0); //postID
	}else{
		$status++;
	}
 	//end gen postcode
  
    //insert PostLog 
	$sql_ipostlog="insert into \"PostLog\"
			(\"PostID\",\"UserIDPost\",\"PostDate\",paytype ) 
	values 	('$res_genpost','$id_user','$datenow','TR')";
	if($result=pg_query($sql_ipostlog)){
	}else{
		$status++;
	}			   
		 
	//********************insert _ TranPay
	if($qry_ref=pg_query("select A.\"TranIDRef1\",A.\"TranIDRef2\",A.\"IDNO\", A.\"CusID\",B.\"CusID\",B.\"A_NAME\",B.\"A_SIRNAME\" from \"Fp\" A 
		LEFT OUTER JOIN \"Fa1\" B on A.\"CusID\"= B.\"CusID\"
		WHERE  A.\"IDNO\"='$idno'"))
	{
		$res_ref=pg_fetch_array($qry_ref);
		$ref1=$res_ref["TranIDRef1"];
		$ref2=$res_ref["TranIDRef2"];
		$full_name=trim($res_ref["A_NAME"])." ".trim($res_ref["A_SIRNAME"]);
	}else{
		$status++;
	}
		
		
		
	// end view vcontact //
	$sql_idtltr="insert into \"TranPay\"
		(branch_id,tr_date,tr_time,pay_bank_branch,terminal_id, 
		terminal_sq_no,ref1,ref2,ref_name,tran_type,bank_no,amt,pay_cheque_no,\"PostID\",post_by,post_to_idno,post_on_date) 
	values 
		('$branch_id','$date_tr','$time_tr','$bnk_br','TR-ACC',
		'0000','$ref1','$ref2','$full_name','TR','$bnk_no',$sumtotal,'0000000','$res_genpost','$id_user','$idno','$datenow')";
	if($result_fr=pg_query($sql_idtltr)){
	}else
	{
		$status++;
	}			   
  
	if($_POST["count_fr"]=="0")
	{
		//echo "ไม่มีค่างวด"; 
	}
	else
	{ 
		//echo pg_escape_string($_POST["count_fr"]);
		//view vcontact //	 
		 $amt_tr=pg_escape_string($_POST["rescal"]);
		 $sql_id="insert into \"DetailTranpay\"
					(\"PostID\",\"IDNO\",\"TypePay\",\"Amount\" ) 
			values 	('$res_genpost','$idno',1,'$amt_tr')";
		if($result_frs=pg_query($sql_id)){
		}else{
			$status++;
		}			   
	}

	if(empty($_POST["typepayment"]))
	{
		echo "ไม่มีค่าอื่น ๆ";
	}
	else
	{
		for($i=0;$i<count($_POST["typepayment"]);$i++)
		{	  
			if($_POST["typepayment"][$i] != "")
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
											
				$sql_idtotr="insert into \"DetailTranpay\"
						(\"PostID\",\"IDNO\",\"TypePay\",\"Amount\" ) 
					values 
						('$res_genpost','$idno','$tpay','$amtpay')";
				if($result_fr=pg_query($sql_idtotr)){
				}else{
					$status++;
				}			   
			}
	    }
    }
	//ACTIONLOG
	if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ออกเงินโอนที่ไม่ใช่ Bill Payment', '$add_date')")){
	}else{
		$status++;
	}
	//ACTIONLOG---
	
	if($status == 0){
		pg_query("COMMIT");
		//pg_query("ROLLBACK");
		echo "<center><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></center>";
		echo "<meta http-equiv=\"refresh\" content=\"2;URL=pass_acc_trreceipt.php?pid=$res_genpost\" >";
	}else{
		pg_query("ROLLBACK");
		echo "<center><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></center>";
	}		
}
?>