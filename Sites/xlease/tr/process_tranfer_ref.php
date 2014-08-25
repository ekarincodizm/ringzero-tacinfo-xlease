<?php
session_start();
session_start();
$id_user=$_SESSION["av_iduser"];
include("../config/config.php");
include("../company.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$c_code=$_SESSION["session_company_code"];
// pg_query("BEGIN");
  //chk_company //
  
  if($_SESSION["session_company_code"]=="AVL")
  {
      $con_tr="host=". $company[4]['server'] ." port=5432 dbname=". $company[4]['dbname'] ." user=". $company[4]['dbuser'] ." password=". $company[4]['dbpass'] ."";
     $db_conn_tr = pg_connect($con_tr) or die("Can't Connect ! to thaiace.");
	 
	 $comp_name=$company[4]['code'];
  
  }
  else if($_SESSION["session_company_code"]=="THA")
  {
     $con_tr="host=". $company[0]['server'] ." port=5432 dbname=". $company[0]['dbname'] ." user=". $company[0]['dbuser'] ." password=". $company[0]['dbpass'] ."";
     $db_conn_tr = pg_connect($con_tr) or die("Can't Connect ! to AVL.");
     $comp_name=$company[0]['code'];
  }
  
  //


$tr_idtranfer=$_POST["ref_idtran"];
for($i=0;$i<count($tr_idtranfer);$i++) 
	{ 
	
	   $mtr_id=$_POST["ref_idtran"][$i];
	   $list_tran=pg_query($db_connect,"select * from \"TranPay\" where \"PostID\"='$mtr_id'");
		$res_tran=pg_fetch_array($list_tran);
		
		$ref1_id=$res_tran["ref1"]; //ref1
		$ref2_id=$res_tran["ref2"]; //ref2
		$d_tr=$res_tran["tr_date"]; //tr_date
		
		$br_id=$res_tran["branch_id"]; 
		
		  $t_tr=$res_tran["tr_time"]; 
		  $pay_bank_branch=$res_tran["pay_bank_branch"]; 
		  $terminal_id=$res_tran["terminal_id"]; 
		  $terminal_sq_no=$res_tran["terminal_sq_no"];
		
		  $amt=$res_tran["amt"]; 
		  $res_tran["ref_name"]; 
		  $bank_no=$res_tran["bank_no"]; 
		  $tran_type=$res_tran["tran_type"]; 
		  $pay_cheque_no=$res_tran["pay_cheque_no"]; 
		  $res_tran["post_on_asa_sys"]; 
		  $res_tran["post_on_date"]; 
		  $res_tran["post_to_idno"]; 
		  
		  $num_postid=$res_tran["PostID"];
		  

		
	
	
	    //insert PostLog
			
					$datenow=date("Y-m-d");
					$qry_post=pg_query($db_conn_tr,"select gen_pos_no('$datenow')");
					$res_genpost=pg_fetch_result($qry_post,0); //postID
					//end gen postcode
					
					//insert PostLog 
				  
					$sql_ipostlog="insert into \"PostLog\"
								   (\"PostID\",\"UserIDPost\",\"PostDate\",paytype ) 
								   values 
								   ('$res_genpost','$id_user','$datenow','TR')";
					if($result_ps=pg_query($db_conn_tr,$sql_ipostlog))
					 {
					  $status ="OK".$sql_ipostlog;
					 }
					 else
					 {
					  $status ="error insert sql".$sql_ipostlog;
					 }			   
			// echo $status;
				
				
				   
				  $qry_ref=pg_query($db_conn_tr,"select * from \"VContact\" where (\"TranIDRef1\"='$ref1_id') AND (\"TranIDRef2\"='$ref2_id') ");
				  $res_ref=pg_fetch_array($qry_ref);
				  $res_idno=$res_ref["IDNO"];
				  $fullname=$res_ref["full_name"];
					 
		
				  $in_sql="insert into \"TranPay\"(terminal_sq_no, bank_no, tr_date, tr_time, ref_name, ref1, ref2,
						   pay_bank_branch, terminal_id, tran_type, pay_cheque_no, amt,\"PostID\",post_to_idno,branch_id) 
						   values  
						  ('$terminal_sq_no','$bank_no','$d_tr','$t_tr','$fullname','$ref1_id','$ref2_id',
						   '$pay_bank_branch','$terminal_id','$tran_type','$pay_cheque_no','$amt','$res_genpost','$res_idno','$br_id'
						  )";
					   if($result=pg_query($db_conn_tr,$in_sql))
						{
							$st_fn="OK".$in_sql;
						}
						else
						{
							$st_fn="error insert Re".$in_sql;
						}
						
						
     // update - old tranpay - //
	                    
	                    $lt_tp=pg_query($db_conn_tr,"select * from \"TranPay\" where (\"PostID\"='$res_genpost') AND (ref1='$ref1_id') AND (ref2='$ref2_id')");
						$res_tp=pg_fetch_array($lt_tp); 
						 
	 					$idno_tr=$res_idno;
						$old_idtp=$mtr_id;
						$ref_nametr=$res_tp["post_to_idno"];
						$id_trp=$res_tp["id_tranpay"];
						$str_refname=$comp_name.":".$ref_nametr."[".$res_genpost."]";
						
							
		 $up_oldtr="Update \"TranPay\" SET ref_name='$str_refname',post_on_asa_sys=TRUE,
		            post_on_date='$datenow',post_by='$id_user'		 
		             WHERE \"PostID\"='$mtr_id' ";
		 if($up_qry=pg_query($db_connect,$up_oldtr))
		 {
		  $ustr="";
		 }		  	
		 else
		 {
		   $ustr="error at ".$up_oldtr;
		 }		
			//echo $ustr.$conn_string."<br>";			
						
		// update postlog //
		 $up_dateplog="Update \"PostLog\" SET \"AcceptPost\"=TRUE,\"UserIDAccept\"='$id_user' 
		                WHERE \"PostID\"='$num_postid' "; 
		  if($up_pl=pg_query($db_connect,$up_dateplog))
		  {
		   $re_uppl="";
		  }	
		  else
		  {
		   $re_uppl="error at".$up_dateplog;
		  }			
		   echo $re_uppl;
		   
						
					
	 }

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำการย้าย Bill Payment ข้ามบริษัท', '$datelog')");
//ACTIONLOG---

	 
	 echo "<meta http-equiv=\"refresh\" content=\"3;URL=tranfer_ref.php\">";
	 /*
	 if(($result) and ($result_ps) and ($res_genpost) and ($up_qry))
			{
			  pg_query("COMMIT");
			  echo "<br>"."<b>"."บันทึกข้อมูลเรียบร้อย รอสักครู่ "."</b>";
			  echo "<meta http-equiv=\"refresh\" content=\"5;URL=tranfer_ref.php\">";
			}
			else
			{
			  pg_query("ROLLBACK");
			  echo "มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่".$residno."[postlog ".$status."]"."[tranpay ".$st_fn."]";
			  echo "<meta http-equiv=\"refresh\" content=\"50;URL=tranfer_ref.php\" >";
			}    
	*/		
?>
