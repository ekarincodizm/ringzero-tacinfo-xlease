<?php
session_start();
include("../config/config.php");
include("../nw/function/randomDigit.php");
include('class.upload.php');
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
 $status=0; 
 pg_query("BEGIN WORK");	
 $v_title=pg_escape_string($_POST["a_title"]);
 $v_fullname=pg_escape_string($_POST["a_fname"]);
 $v_lname=pg_escape_string($_POST["a_lname"]);
 $v_username=pg_escape_string($_POST["a_username"]);
 //$v_pass=pg_escape_string($_GET["f_pass"]);
 $v_gp=pg_escape_string($_POST["a_gp"]);
 $v_fd=pg_escape_string($_POST["a_fd"]);
 $v_office=pg_escape_string($_POST["a_ofiice"]);
 $v_status=pg_escape_string($_POST["a_status"]);
 $v_id=pg_escape_string($_POST["a_id"]);
 $v_email=pg_escape_string($_POST["email"]); 
 $u_system=pg_escape_string($_POST["u_system"]);
 $is_admin_status = pg_escape_string($_POST["is_admin"]);
 $user_id = $_SESSION["av_iduser"];
 $add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
 $id_usrname_chk = pg_escape_string($_POST["a_id"]);

 $dir_dest = (isset($_GET['dir']) ? $_GET['dir'] : '../nw/upload/sign');
 $dir_pics = (isset($_GET['pics']) ? $_GET['pics'] : $dir_dest);
 $files = array();
 // ตรวจสอบว่า username ที่ กรอกมาผ่านตัวแปร "$_POST["a_username"]" มีอยู่แล้วในตารางหรือไม่
 $Sql_Chk = "
 				SELECT 
 						username
				FROM 
						fuser 
				WHERE 
						username = '".$v_username."'
						and (id_user::int <> ".$id_usrname_chk." )
 				
 			";	
  $Result = pg_query($Sql_Chk);
  $Num_Row =pg_num_rows($Result);
  if($Num_Row > 0)
  {
  	$status++;
  	$Err_Msg = " ไม่สามารถ Update ได้ เนื่องจากมี username เป็น ".$v_username." ของผู้ใช้รายอื่น อยู่ในระบบก่อนแล้ว";	
  }else{
  	$Err_Msg = "";
  }			
	// แก้ไขบอง XLEASE
	$in_sql="update fuser SET title='$v_title',fname='$v_fullname',lname='$v_lname',username='$v_username',
                             office_id='$v_office',user_group='$v_gp',status_user='$v_status',user_dep='$v_fd',
							 email='$v_email', \"isUserTA\" = '$u_system',isadmin = '$is_admin_status'
					   WHERE id_user='$v_id'  
		   ";
	   
	if($uptemp=pg_query($in_sql)){
			}else{
				$status++;
	}
	
	
	
			
			$newName = $_FILES["file0"]["name"];
			$reName = str_replace(" ","",$newName);
			$reName2 = str_replace("-","",$reName);
			$lastName = str_replace(":","",$reName2);
			if($newName !=""){
			if($_FILES["file0"]["error"]>0){
					echo '*'.$_FILES["file0"]["error"];
					$status++;
					
			} else {
						if(file_exists("upload/" .$_FILES["file0"]["name"])){
							echo $_FILES["file0"]["name"]."มีอยู่ในระบบแล้ว";
							$status++;
							
						} else {
								
								
								$Board_newfile="";	
								$Board_newfile =$_FILES["file0"]["name"];
								
								if(move_uploaded_file($_FILES["file0"]["tmp_name"],"../nw/upload/sign/".$Board_newfile)){
									
									$Board_newfile_1 = md5_file("../nw/upload/sign/$Board_newfile", FALSE);
									
									$Board_cuttext = split("\.",$Board_newfile);
									
									$Board_nubtext = count($Board_cuttext);
										
									$Board_newfile_1  = "$Board_newfile_1.".$Board_cuttext[$Board_nubtext-1];
									
									$Board_newfile_1 = date("YmdHis")."_".$Board_newfile_1; // ใส่วันเวลาไว้หน้าไฟล์
									
									$Boardfile = "'$Board_newfile_1'";
									$flgRename = rename("../nw/upload/sign/$Board_newfile", "../nw/upload/sign/$Board_newfile_1");
									
									$update_sql=" update fuser_detail SET   u_sign=$Boardfile
									WHERE id_user='$v_id'  ";
									
									if($update_sql=pg_query($update_sql)){
									}else{
									$status++;
									}
								} else {
								$status++;
							}
						}
				}
			}

 if($status==0)
 {
	pg_query("COMMIT");
	//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) แก้ไขผู้ใช้งาน', '$add_date')");
	//ACTIONLOG---
	$status ="Update ข้อมูลแล้ว";
 
	// ถ้าใช้ระบบ TA
	if($u_system == "1")
	{
		// หารหัสผ่าน
		$qry_passX = pg_query("select \"password\" from \"fuser\" where id_user = '$v_id' ");
		$passX = pg_fetch_result($qry_passX,0);
	
		// ต่อ base TA
		$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=devtaauto010 user=postgres password=". $_SESSION["session_company_dbpass"] ."";
		$db_connect = pg_connect($conn_string) or die("Can't Connect !");
		
		// หาว่ามีข้อมูลอยู่แล้วหรือยัง
		$qry_chk_user = pg_query("select * from \"fuser\" where \"id_user\" = '$v_id' ");
		$chk_user = pg_num_rows($qry_chk_user);
		
		if($chk_user == 0)
		{
			// เพิ่มบอง TA
			$in_sql_ta = "insert into \"fuser\"(\"fullname\", \"username\", \"password\", \"id_user\", \"status_user\")
							values('$v_title$v_fullname $v_lname', '$v_username', '$passX', '$v_id', TRUE) ";
			if($uptemp_ta = pg_query($in_sql_ta)){
			}else{
				$status++;
			}
		}
		else
		{
			// แก้ไขบอง TA
			$in_sql_ta = "update fuser SET fullname = '$v_title$v_fullname $v_lname', username='$v_username' WHERE id_user='$v_id' ";
			if($uptemp_ta = pg_query($in_sql_ta)){
			}else{
				$status++;
			}
		}
		
		// กลับมาต่อ base หลักเหมือนเดิม
		$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
		$db_connect = pg_connect($conn_string) or die("Can't Connect !");
	}
 }
 else
 {
	pg_query("ROLLBACK");
	$status = ""; 
 }
 $status = $status.$Err_Msg;
 echo "<div style=\"text-align:center;padding-top:50px\"><b>$status</b><br>
  <input type=\"button\" value=\"กลับ\" onclick=\"location.href='detail_user.php?iduser=$v_id'\"></div>";





?>