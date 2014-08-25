<?php
session_start();
include("../config/config.php");

$idno=pg_escape_string($_POST["s_idno"]);
$i_vender=pg_escape_string($_POST["s_vender"]);
$is_date=pg_escape_string($_POST["s_date"]);
$is_number=pg_escape_string($_POST["s_number"]);
$is_id_rec=pg_escape_string($_POST["s_id_rec"]);
$is_cost_car=pg_escape_string($_POST["s_cost_car"]);
$is_cost_vat=pg_escape_string($_POST["s_cost_vat"]);

$stotal=$is_cost_car+$is_cost_vat;



$update_Fp="Update \"Fp\" SET \"P_BEGINX\"='$is_cost_car' where \"IDNO\"='$idno' ";
if($result=pg_query($update_Fp))
{
 $str="Update success";
}
else
{
 $str="Error At ".$result;
}
echo "<br>".$str;

//--- create acc payment            //
$c_cpay="select \"CreateAccPayment\"('$idno')";
if($result=pg_query($c_cpay))
     {
       $statusc ="สร้าง Customer Payment เรียบร้อยแล้ว";
     }
      else
     {
       $statusc ="เกิดข้อผิดพลาด";
     }	
	echo "<br>".$statusc; 
//-- end	create acc payment  --- //
	

$sql_cofcar="update account.\"CostOfCar\" 
             SET  vd_vat_date='$is_date',bill_no='$is_number',vat_no='$is_id_rec',
			 cost_of_car='$is_cost_car',vat_of_cost='$is_cost_vat',venderid='$i_vender'
             WHERE \"IDNO\"='$idno'
			 ";	
 if($result_car=pg_query($sql_cofcar))
 {
  $statuss ="OK update at Fn".$sql_cofcar;
 }
 else
 {
  $statuss ="error update  Fn Re".$sql_cofcar;
 }	 
  echo "<br>".$statuss;
  
 
  // find IDNO for update //
   $sql_f_idno=pg_query("select * from account.\"AccountBookDetail\"  
                         WHERE (\"AcID\"='1610') AND (\"RefID\"='$idno')"); 
   $res_f_idno=pg_fetch_array($sql_f_idno);
   $f_idno=$res_f_idno["RefID"];
   $f_abh=$res_f_idno["autoid_abh"];
   
   // update bookDetail //					 
   

  
    
$sql_ind1="update account.\"AccountBookDetail\" SET 
             
           \"AmtDr\"='$is_cost_car' 
		   WHERE (\"RefID\"='$idno') AND (\"AcID\"='1610') ";

if($result_adt1=pg_query($sql_ind1))
	 {
	  $status_id1 ="OK update at Fn".$sql_ind1;
	 }
	 else
	 {
	  $status_id1 ="error update  Fn Re".$sql_ind1;
	 }	 
	  echo "<br>".$status_id1;		
	  
		  
$sql_ind2="update account.\"AccountBookDetail\"  SET
           \"AmtDr\"='$is_cost_vat'
		   WHERE (\"AcID\"='1999') AND (\"RefID\"='$idno')";
		 
	if($result_dtl2=pg_query($sql_ind2))
	 {
	  $res_ind2="OK update at ".$sql_ind2;
	 }
	 else
	 {
	  $res_ind2="error update ".$sql_ind2;
	 }	 
	  echo "<br>".$res_ind2;		

$sql_ind3="update account.\"AccountBookDetail\" SET 
           \"AmtCr\"='$stotal'
		   WHERE (\"AcID\"='2617') AND (\"RefID\"='$idno')"; 
	
	if($result_dtl3=pg_query($sql_ind3))
	 {
	  $res_ind3="OK update at ".$sql_ind3;
	 }
	 else
	 {
	  $res_ind3="error update ".$sql_ind3;
	 }	 
	  echo "<br>".$res_ind3;		
		  		  


//--end update bookDetail ------------//

 echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_beginx.php\" >";
?>