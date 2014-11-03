<?php 
session_start();

ob_start();

require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server


echo "

	
	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
	<html xmlns=\"http://www.w3.org/1999/xhtml\">
	<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
	<title>สมุดรายงานค่าเข้าร่วม</title>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"".$lo_ext_current_temp."css/view.css\" media=\"all\">
	<script type=\"text/javascript\" src=\"".$lo_ext_current_temp."scripts/view.js\"></script>
	<script type=\"text/javascript\" src=\"".$lo_ext_current_temp."scripts/calendar.js\"></script>
	<script language=\"JavaScript\">

function refreshParent() {
  window.opener.location.href = window.opener.location.href;
  if (window.opener.progressWindow)
    window.opener.progressWindow.close();
  window.close();
}
function MM_jumpMenu(targ,selObj,restore){ 
  eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
  if (restore) selObj.selectedIndex=0;
}

</script>
	</head>
	<body id=\"main_body\" >
		

			</div>
	";


$f_type = pg_escape_string($_POST[f_type]);
$id = pg_escape_string($_REQUEST[id]);	
$ta_join_pm_id = pg_escape_string($_POST[ta_join_pm_id]);
$name = getCusJoin(pg_escape_string($_POST[cus_id]),$f_type,$id); //หาชื่อลูกค้า
	if(pg_escape_string($_POST[form_name]) == "add"){

			$ta_join_pm_id = generate_id("TAJM-", "ta_join_main" ,"ta_join_pm_id",date("ymd"),4);
		//$cpro_name = explode('#',$_POST[cpro_name]);
		$start_pay_date = date_ch_form($_POST[start_pay_date]);

		$query1 =	"INSERT INTO \"ta_join_main\" (
		
										ta_join_pm_id,
										carid,
										cusid,
										car_license,
										idno,
										address,
										start_pay_date,
										note,
										create_datetime,
										create_by,
										approve_status,
										prefix,
										f_name,
										l_name
										
										) 
							VALUES(
							           '$ta_join_pm_id',
									   '$_POST[car_id]',
									   '$_POST[cus_id]',
									   '$_POST[car_license]',
									   '$_POST[idno]',
									   '$_POST[join_addr]',
									   '$start_pay_date',
									   '$_POST[note]',
									   '$info_currentdatetimesql2',
									   '".$_SESSION["av_iduser"]."',
									   '1','$name[0]','$name[1]','$name[2]')";

				
				$sql_query = pg_query($query1);
//echo $query1;
				if (!$sql_query) {die('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . pg_last_error($db_connect).'<br>');}
				
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) เพิ่มข้อมูลเข้าร่วม', '$add_date')");
		//ACTIONLOG---
					
	echo "<script>alert('บันทึกข้อมูลเรียบร้อยแล้ว');window.location.href ='frm_main.php?action=add';</script>";

			
			
			
}
		
		//------------------------------------------------------------------------edit
 
elseif(pg_escape_string($_POST[form_name]) == "edit"){
	
		$cb1 = pg_escape_string($_REQUEST[cb1]);	
		if($cb1==1){
		$idno = pg_escape_string($_REQUEST[idno_new]);	
		}else{
		$idno = pg_escape_string($_REQUEST[idno]);		
			
		}
		$start_pay_date = date_ch_form(pg_escape_string($_POST[start_pay_date]));
		//$cpro_name = explode('#',$_POST[cpro_name]);
		$cancel_datetime = pg_escape_string($_POST[cancel_datetime]);
		if($cancel_datetime!=''){
		$cancel_datetime = date_ch_form($cancel_datetime);
		$ccc = ",cancel_datetime='$cancel_datetime' ";
		}
		
		if(pg_escape_string($_POST[join_addr]) != pg_escape_string($_POST[join_addr2])){
			
			$ccc2 = ",addr_user='".$_SESSION["av_iduser"]."',addr_stamp='$info_currentdatetimesql2' ";
			
		}
		
		
		$cancel = pg_escape_string($_REQUEST[cancel]);
		$car_license  = pg_escape_string($_REQUEST[car_license]);
		if($cancel==1 || $cancel==2 || $cancel==3 || $cancel==4){
			//$car_license2 = $car_license."/" ;
					$query = "INSERT INTO  ta_join_main_bin  
	   			 SELECT * FROM  ta_join_main 
				 WHERE id ='$id'";
				$sql_query = pg_query($query);
				if (!$sql_query) {die('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . pg_last_error($db_connect). '<br>');}
			//	echo $query;
				// Update เมื่อ ยกเลิกสัญญา
		$query =	"UPDATE ta_join_main SET
										car_license='".pg_escape_string($_POST[car_license])."',
										cusid='".pg_escape_string($_POST[cus_id])."',
				                        idno='$idno',
										
										start_pay_date='$start_pay_date',
										address='".pg_escape_string($_POST[join_addr])."',
										cancel='".pg_escape_string($_POST[cancel])."',
										note='".pg_escape_string($_POST[note])."',
										update_datetime='$info_currentdatetimesql2',
										approve_status='2',
										staff_check=0,
										prefix='$name[0]',
										f_name='$name[1]',
										l_name='$name[2]',
										update_by ='".$_SESSION["av_iduser"]."'
									    $ccc
										$ccc2 
										where id  ='$id' ";
			
		$sql_query = pg_query($query);		
		if (!$sql_query) {die('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . pg_last_error($db_connect). '<br>');}
//echo $query;
		// สร้าง LOG สำหรับการทำรายการ แก้ไขข้อมูลสิทธิ์พนักงานแผนกที่มีอยู่ 					
	
	
			//select like %$cancel% main desc
			$query_license= "SELECT car_license_seq FROM  \"VJoinMain\"  WHERE id = '$id' and car_license_seq != '0' AND cancel  != '0' ";
						//	echo $query_license ;
				$sql_query_license = pg_query($query_license);
				$num_rows_license = pg_num_rows($sql_query_license);//echo $num_rows_license ;
				if($num_rows_license !=0) {
				if($sql_row_license = pg_fetch_array($sql_query_license))
				{
				 $car_license_seq = $sql_row_license['car_license_seq'];
				}
				
			//	echo $car_license_Aborting;
			
			
							//$list($a,$b)   $b
							//list($aa,$bb)=split("/",$car_license_Aborting);
							$bb=$car_license_seq+1;
							
							$car_license_seq_new =$bb;
							
							 
											}else{
																			$car_license_seq_new =1; 
											}
																	//	 echo  $car_license_old;
							//update 4ตาราง
						$query =	"UPDATE ta_join_main SET car_license_seq='$car_license_seq_new' where id  ='$id' ";
										$sql_query = pg_query($query);	
										
						$query =	"UPDATE ta_join_main_bin SET car_license_seq='$car_license_seq_new' where id  ='$id' ";
									//	echo $query;
										$sql_query = pg_query($query);
											
						$query =	"UPDATE ta_join_payment  SET car_license_seq='$car_license_seq_new' where id_main  ='$id' ";
										
										$sql_query = pg_query($query);
											
						$query =	"UPDATE ta_join_payment_bin SET car_license_seq='$car_license_seq_new' where id_main  ='$id' ";
			
		 								$sql_query = pg_query($query);		
							// สร้าง LOG สำหรับการทำรายการ แก้ไขข้อมูลสิทธิ์พนักงานแผนกที่มีอยู่ 					
	
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขข้อมูลเข้าร่วม', '$add_date')");
if($cb1==1){
	
echo "<script>alert('ยกเลิกข้อมูลเรียบร้อยแล้ว');location.href='frm_main.php?idno=$idno&car_id_r=".pg_escape_string($_POST[car_id])."&id=$id&action=add&new_sp=1'</script>";
}else{
echo "<script>alert('ยกเลิกข้อมูลเรียบร้อยแล้ว');location.href='ta_join_payment_view_new.php?idno_names=$id&config=0&rf=1'</script>";		
}




		}
		else{ //แก้ไขธรรมดา

		//backup ข้อมูลก่อนแก้ไข
	   $query = "INSERT INTO  ta_join_main_bin  
	   			 SELECT * FROM  ta_join_main 
				 WHERE id ='$id'";
				$sql_query = pg_query($query);
				if (!$sql_query) {die('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . $query . '<br>');}
				
		$query =	"UPDATE ta_join_main SET
										car_license='".pg_escape_string($_POST[car_license])."',
										idno='".pg_escape_string($_POST[idno])."',
										cusid='".pg_escape_string($_POST[cus_id])."',
										start_pay_date='$start_pay_date',
										address='".pg_escape_string($_POST[join_addr])."',
										cancel='".pg_escape_string($_POST[cancel])."',
										note='".pg_escape_string($_POST[note])."',
										update_datetime='$info_currentdatetimesql2',
										approve_status='2',
										staff_check=0,
										prefix='$name[0]',
										f_name='$name[1]',
										l_name='$name[2]',
										update_by ='".$_SESSION["av_iduser"]."'
										$ccc
										$ccc2
										where id  ='$id' ";
		
		$sql_query = pg_query($query);	
	
if (!$sql_query) {die('  เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . pg_last_error($db_connect)
 . '<br>');}

 
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขข้อมูลเข้าร่วม', '$add_date')");
//ACTIONLOG---
		
echo "<script>alert('แก้ไขข้อมูลเรียบร้อยแล้ว');location.href='ta_join_payment_view_new.php?idno_names=$id&config=0&rf=1'</script>";

		}
	}
elseif(pg_escape_string($_REQUEST[form_name]) == 'del'){
	

					$query = "INSERT INTO ta_join_main_bin  SELECT * FROM ta_join_main WHERE id='$id' ";
					$sql_query = pg_query($query);
				if (!$sql_query) {die('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . pg_last_error($db_connect)
 . '<br>');}
	$query = "UPDATE ta_join_main SET deleted='1', delete_datetime='$info_currentdatetimesql2',
	delete_by ='".$_SESSION["av_iduser"]."' WHERE id='$id' ";
	$sql_query = pg_query($query);
	if (!$sql_query) {die('เกิดข้อผิดพลาด ไม่สามารถบันทึกข้อมูลได้!!: <br>' . pg_last_error($db_connect)
 . '<br>');}

			echo "<script>alert('ลบข้อมูลเรียบร้อยแล้ว');window.location.href ='frm_main.php?action=view';</script>";
	}	
echo "
			
			<div class=form_description></div>
		
			</ul>
		  </form>
		</div>
		<img id=\"bottom\" src=\"".$lo_ext_current_temp."pictures/bottom.png\" alt=\"\">
	</body>
	</html>
	
	";
	
ob_end_flush();

?>