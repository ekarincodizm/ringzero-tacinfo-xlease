<?php
session_start();
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$idno=pg_escape_string($_POST["ss_name"]);
$i_vender=pg_escape_string($_POST["s_vender"]);
$is_date=pg_escape_string($_POST["s_date"]);
$is_number=pg_escape_string($_POST["s_number"]);
$is_id_rec=pg_escape_string($_POST["s_id_rec"]);
$is_cost_car=pg_escape_string($_POST["s_cost_car"]);
$is_cost_vat=pg_escape_string($_POST["s_cost_vat"]);

$stotal=$is_cost_car+$is_cost_vat;


pg_query("BEGIN");

// gen payid //
/*
$gen_pid=pg_query("select account.gen_payid('$is_date')");
$result_pid=pg_fetch_result($gen_pid,0);
echo $result_pid;
*/

$update_Fp="Update \"Fp\" SET \"P_BEGINX\"='$is_cost_car' where \"IDNO\"='$idno' ";
if($result=pg_query($update_Fp))
{
 $str="Update success";
}
else
{
 $str="Error At ".$result;
}
//echo "<br>".$str;

//--- create acc payment            //
$c_cpay="select \"CreateAccPayment\"('$idno')";
if($result_pay=pg_query($c_cpay))
     {
       $statusc ="สร้าง Customer Payment เรียบร้อยแล้ว";
     }
      else
     {
       $statusc ="เกิดข้อผิดพลาด";
     }	
	echo "<br>".$statusc; 
//-- end	create acc payment  --- //
	

$sql_cofcar="insert into account.\"CostOfCar\" (\"IDNO\",vd_vat_date,bill_no,vat_no,cost_of_car,vat_of_cost,venderid)values('$idno','$is_date','$is_id_rec','$is_number','$is_cost_car','$is_cost_vat','$i_vender')";	


 if($result_car=pg_query($sql_cofcar))
 {
  $statuss ="OK update at Fn".$sql_cofcar;
 }
 else
 {
  $statuss ="error update  Fn Re".$sql_cofcar;
 }	 
 // echo "<br>".$statuss;
  
 
// -- insert account.bookHead -------//

		//gen acb_id 
        //$gen_acbid=pg_query("select account.gen_gj_no('$is_date')");
		//$result_acbid=pg_fetch_result($gen_acbid,0);
        $gen_acbid=@pg_query("select account.\"gen_no\"('$is_date','GJ')");
        $result_acbid=@pg_fetch_result($gen_acbid,0);
       

$acb_dtl="ตั้งเจ้าหนี้รถใหม่ เลขที่สัญญา".$idno;
$sql_bhead="insert into account.\"AccountBookHead\" 
            (type_acb,acb_id,acb_date,acb_detail)
			values
			('GJ','$result_acbid','$is_date','$acb_dtl')";
			
if($result_ahead=pg_query($sql_bhead))
 {
  $status_ahead ="OK update at Fn".$sql_bhead;
 }
 else
 {
  $status_ahead ="error update  Fn Re".$sql_bhead;
 }	 
  echo "<br>".$status_ahead;		



			

//--- end  insert account.bookHead --//


//-- insert bookDetail ---------------//

  //find auto_id at bookhead
  $sql_fid=pg_query("select * from account.\"AccountBookHead\" WHERE acb_id='$result_acbid'");
  $res_fid=pg_fetch_array($sql_fid);
  $rs_id=$res_fid["auto_id"];
  
    
$sql_ind1="insert into account.\"AccountBookDetail\" 
           (autoid_abh,\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\")
		   values
		   ('$rs_id','1610','$is_cost_car','0','$idno')
		  ";
	if($result_adt1=pg_query($sql_ind1))
	 {
	  $status_id1 ="OK update at Fn".$sql_ind1;
	 }
	 else
	 {
	  $status_id1 ="error update  Fn Re".$sql_ind1;
	 }	 
	 echo "<br>".$status_id1;		
	  
		  
$sql_ind2="insert into account.\"AccountBookDetail\" 
           (autoid_abh,\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\")
		   values
		   ('$rs_id','1999','$is_cost_vat','0','$is_number')
		  ";
	if($result_dtl2=pg_query($sql_ind2))
	 {
	  $res_ind2="OK update at ".$sql_ind2;
	 }
	 else
	 {
	  $res_ind2="error update ".$sql_ind2;
	 }	 
	  echo "<br>".$res_ind2;		

$sql_ind3="insert into account.\"AccountBookDetail\" 
           (autoid_abh,\"AcID\",\"AmtDr\",\"AmtCr\",\"RefID\")
		   values
		   ('$rs_id','2617','0','$stotal','$idno')
		  ";
	if($result_dtl3=pg_query($sql_ind3))
	 {
	  $res_ind3="OK update at ".$sql_ind3;
	 }
	 else
	 {
	  $res_ind3="error update ".$sql_ind3;
	 }	 
	  echo "<br>".$res_ind3;		
		  		  

//--end insert bookDetail ------------//

if(($result_pay) and ($result_car)  and ($result_ahead) and  ($result_adt1) and ($result_dtl2) and ($result_dtl3))
  {
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกต้นทุนรถ', '$add_date')");
	//ACTIONLOG---
   pg_query("COMMIT");
   echo "บันทึกข้อมูลเรียบร้อย รอสักครู่ .";
   echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_beginx.php\" >";
  }
  else
  {
 	pg_query("ROLLBACK");
	echo "มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่";
 	//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
  }



?>