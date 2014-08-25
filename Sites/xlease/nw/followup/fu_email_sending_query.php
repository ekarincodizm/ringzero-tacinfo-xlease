<?php 
include("../../config/config.php");

//รับค่า
$temp1=pg_escape_string($_POST['template']);
$check=pg_escape_string($_POST['ra1']);
$com = pg_escape_string($_POST['com']);
$emp = pg_escape_string($_POST['emp']);
$emp1 = pg_escape_string($_POST['CH']);
$status = 0;
if(empty($com) AND empty($emp) AND empty($emp1)){
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
							echo "<script type='text/javascript'>alert('กรุณาเลือกผู้ที่จะส่งด้วยครับ')</script>";
							exit();	
}


//ดึงข้อมูล Template ที่จะส่ง
	$sqltem = pg_query("select * from \"fu_template\" where \"temID\" = '$temp1'");
	$temp = pg_fetch_array($sqltem);
	
		$temname = $temp['tem_name'];		
		$temdetail1 = $temp['tem_detail'];
		$temdetail = str_replaceout($temdetail1);
	
		$temsendname = $temp['tem_sendname'];		
		$temsendemail = $temp['tem_send_email'];
		$file = $temp['tem_file'];
		$temheader = $temp['tem_header'];

//เช็คเงื่อนไขการส่ง
if($check == 'allcomemp'){
	$comid = pg_escape_string($_POST['com']);
	$empconid = pg_escape_string($_POST['emp']);
		
			if(empty($file)){
							
					include('fu_email_sendingnotitem.php');
			
			}else if(!empty($file)){
					include('fu_email_sendinghasitem.php');
				
			}
			
	
	
			exit();
	
}else if($check == 'com'){
	$comid=pg_escape_string($_POST['com']);	
			if(empty($file)){
							
					include('fu_email_sendingnotitem.php');

			}else if(!empty($file)){
					include('fu_email_sendinghasitem.php');
					
			}
			
			exit();
		
}else if($check == 'emp'){
	$empconid=pg_escape_string($_POST['emp']);
			if(empty($file)){
							
					include('fu_email_sendingnotitem.php');
					

			}else if(!empty($file)){
					include('fu_email_sendinghasitem.php');
				
			}
		
exit();
}else if($check == 'empupcom'){
	$empconid=pg_escape_string($_POST['CH']);
			if(empty($file)){
							
					include('fu_email_sendingnotitem.php');
					

			}else if(!empty($file)){
					include('fu_email_sendinghasitem.php');
				
			}
exit();		
}


?>