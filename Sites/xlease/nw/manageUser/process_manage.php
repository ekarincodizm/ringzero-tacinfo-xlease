<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
include("classupload.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$user_key=$_SESSION["av_iduser"];

if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$currentdate = nowDate(); // วันที่ปัจจุบัน
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$method = pg_escape_string($_POST["method"]);
$id_user = pg_escape_string($_POST["id_user"]);
$title = pg_escape_string($_POST["title"]);
$fname = pg_escape_string($_POST["fname"]);
$lname = pg_escape_string($_POST["lname"]);
$title_eng = pg_escape_string($_POST["title_eng"]);
$fname_eng = pg_escape_string($_POST["fname_eng"]);
$lname_eng = pg_escape_string($_POST["lname_eng"]);
$nickname = pg_escape_string($_POST["nickname"]);
$username = pg_escape_string($_POST["username"]);
$u_birthday = pg_escape_string($_POST["u_birthday"]);
if($u_birthday==""){
	$u_birthday="1900-01-01";
}
$u_status = pg_escape_string($_POST["u_status"]);
$u_sex = pg_escape_string($_POST["u_sex"]);
$u_idnum = pg_escape_string($_POST["u_idnum"]);
$u_pic = pg_escape_string($_POST["u_pic"]);
$u_pic2 = pg_escape_string($_POST["u_pic2"]);
$u_pos = pg_escape_string($_POST["u_pos"]);
$u_salary = pg_escape_string($_POST["u_salary"]);
$u_salary = str_replace(",","",$u_salary); 
if($u_salary==""){
	$u_salary=0;
}

$u_tel = pg_escape_string($_POST["u_tel"]);
$u_extens = pg_escape_string($_POST["u_extens"]);
$u_direct = pg_escape_string($_POST["u_direct"]);
$u_email = pg_escape_string($_POST["u_email"]);
$startwork = pg_escape_string($_POST["startwork"]);
if($startwork==""){
	$startwork="1900-01-01";
}
$dep_id = pg_escape_string($_POST["dep_id"]);
$fdep_id = pg_escape_string($_POST["fdep_id"]);
$empid = pg_escape_string($_POST["empid"]); // รหัสพนักงาน แบบกำหนดเอง

//----- ตรวจสอบค่าว่าง
	$empid_checknull = checknull($empid);
//----- จบการตรวจสอบค่าว่าง

pg_query("BEGIN WORK");
$status = 0;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")){
	// เริ่มต้นใช้งาน classupload.php ด้วยการสร้าง instant จากคลาส
	$upload_image = new upload($_FILES['image_name']) ; // $_FILES['image_name'] ชื่อของช่องที่ให้เลือกไฟล์เพื่ออัปโหลด
	//  ถ้าหากมีภาพถูกอัปโหลดมาจริง
	if ( $upload_image->uploaded ) {
		// ย่อขนาดภาพให้เล็กลงหน่อย  โดยยึดขนาดภาพตามความกว้าง  ความสูงให้คำณวนอัตโนมัติ
		// ถ้าหากไม่ต้องการย่อขนาดภาพ ก็ลบ 3 บรรทัดด้านล่างทิ้งไปได้เลย
		$upload_image->image_resize         = true ; // อนุญาติให้ย่อภาพได้
		$upload_image->image_x              = 150 ; // กำหนดความกว้างภาพเท่ากับ 400 pixel 
		$upload_image->image_ratio_y        = true; // ให้คำณวนความสูงอัตโนมัติ
	
		$upload_image->process( "upload_images" ); // เก็บภาพไว้ในโฟลเดอร์ที่ต้องการ  *** โฟลเดอร์ต้องมี permission 0777
	 
		// ถ้าหากว่าการจัดเก็บรูปภาพไม่มีปัญหา  เก็บชื่อภาพไว้ในตัวแปร เพื่อเอาไปเก็บในฐานข้อมูลต่อไป
		if ( $upload_image->processed ) {
			$image_name =  $upload_image->file_dst_name ; // ชื่อไฟล์หลังกระบวนการเก็บ จะอยู่ที่ file_dst_name
			$upload_image->clean(); // คืนค่าหน่วยความจำ
		}// END if ( $upload_image->processed )
	 
	}//END if ( $upload_image->uploaded ) 
}

if($method == "add"){
	$qry_uname=pg_query("select * from fuser where username='$username' ");
	$nur_name=pg_num_rows($qry_uname);
	if($nur_name > 0)
	{
		echo "<center><h2>ชื่อ username ซ้ำ</h2></center>";
		$status++;
	}
	else
	{
		if($empid != "") // ถ้ามีค่า รหัสพนักงาน ที่กำหนดเอง จะตรวจสอบว่ามีอยู่แล้วหรือไม่
		{
			$qry_chk_empid = pg_query("select * from fuser where empid = '$empid' ");
			$row_empid = pg_num_rows($qry_chk_empid);
			if($row_empid > 0){
				echo "<center><h2>รหัสพนักงาน ซ้ำ</h2></center>";
				$status++;
			}
		}
		
		if($status == 0) // ถ้ายังไม่มีอะไรผิดพลาด ให้ทำงานต่อไป
		{
			$qrylastid=pg_query("select id_user from fuser");
			$numrow=pg_num_rows($qrylastid);
	 
			$idplus=$numrow+1;
	 
			function insertZero($inputValue , $digit ){
				$str = "" . $inputValue;
				while (strlen($str) < $digit){
					$str = "0" . $str;
				}
				return $str;
			}
			$id_plus=insertZero($idplus , 3);
			$seed = $_SESSION["session_company_seed"];
			$v_pass = md5(md5($_POST['v_pass']).$seed);
			
			$in_sql="insert into fuser(id_user,username,office_id,user_group,status_user,title,fname,lname,user_dep,email,empid)values('$id_plus','$username','1','$dep_id','TRUE','$title','$fname','$lname','$fdep_id','$u_email',$empid_checknull)";
			if($resultins=pg_query($in_sql)){
			}else{
				$status++;
				echo $in_sql;
			}
			
			$in_sql2="insert into fuser_detail(id_user,title_eng,fname_eng,lname_eng,nickname,u_birthday,u_status,u_sex,u_idnum,u_pic,
						u_pos,u_salary,u_tel,u_extens,u_email,startwork,user_keylast,keydatelast,u_direct)values
						('$id_plus','$title_eng','$fname_eng','$lname_eng','$nickname','$u_birthday','$u_status',
						'$u_sex','$u_idnum','$image_name','$u_pos','$u_salary','$u_tel','$u_extens','$u_email','$startwork','$user_key','$currentdate','$u_direct')";
			if($resultins2=pg_query($in_sql2)){
			}else{
				$status++;
			}
		}
	}
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_key', '(ALL) เพิ่มประวัติพนักงาน', '$add_date')");
	//ACTIONLOG---
}else if($method=="edit"){	
	$statuswork = pg_escape_string($_POST["statuswork"]);
	$work_status = pg_escape_string($_POST["work_status"]);
	
	if($empid != "") // ถ้ามีค่า รหัสพนักงาน ที่กำหนดเอง จะตรวจสอบว่าซ้ำกับคนอื่นหรือไม่
	{
		$qry_chk_empid = pg_query("select * from fuser where empid = '$empid' and id_user <> '$id_user' ");
		$row_empid = pg_num_rows($qry_chk_empid);
		if($row_empid > 0){
			echo "<center><h2>รหัสพนักงาน ซ้ำ</h2></center>";
			$status++;
		}
	}

	if($status == 0) // ถ้ายังไม่มีอะไรผิดพลาด
	{
		if($statuswork=="0")
		{ //กรณีลาออก
			$work_status=$work_status; //ครั้งที่ทำงานเท่าเดิมไม่ต้องบวกเพิ่ม
			$resign_date1 = pg_escape_string($_POST["resign_date"]);
			$resign_date="'".$resign_date1."'"; //กำหนดวันที่ลาออก
			if ($resign_date!="")
			{
				$sqlupstatus = pg_query("UPDATE fuser SET status_user ='FALSE' WHERE id_user = '$id_user'");	
			}
		}
		else if($statuswork=="1")
		{ //กรณีรับใหม่
			$work_status=$work_status+1; //เพิ่มครั้งที่ทำงาน
			$resign_date="null"; //เคลียร์วันที่ลาออก
			
			if ($resign_date="null"){
			$sqlupstatus = pg_query("UPDATE fuser SET status_user ='TRUE' WHERE id_user = '$id_user'");	
				}
			
		}
		else
		{ //กรณีแก้ไขข้อมูลปกติ
			$work_status=$work_status; //ครั้งที่ทำงานปัจจุับัน
			$resign_date = pg_escape_string($_POST["resign_date"]); //วันที่ลาออก
			$resign_date = checknull($resign_date);
		}
		
		$upfuser="update
					\"fuser\"
				set 
					\"username\" = '$username',
					\"user_group\" = '$dep_id',
					\"title\" = '$title',
					\"fname\" = '$fname',
					\"lname\" = '$lname',
					\"user_dep\" = '$fdep_id',
					\"email\" = '$u_email',
					\"empid\" = $empid_checknull
				where
					\"id_user\" = '$id_user'";
		if($res_up=pg_query($upfuser)){
		}else{
			$status++;
		}
		
		//ตรวจสอบว่ามีการบันทึกข้อมูลในตารางนี้หรือยัง 
		$querydetail=pg_query("select * from fuser_detail where id_user='$id_user'");
		$num_detail=pg_num_rows($querydetail);
		if($num_detail==0)
		{ //ถ้ายัีงไม่มีการบันทึกให้ insert ข้อมูล
			$in_sql2="insert into fuser_detail(id_user,title_eng,fname_eng,lname_eng,nickname,u_birthday,u_status,u_sex,u_idnum,u_pic,
						u_pos,u_salary,u_tel,u_extens,u_email,startwork,user_keylast,keydatelast,work_status,resign_date,u_direct)values
						('$id_user','$title_eng','$fname_eng','$lname_eng','$nickname','$u_birthday','$u_status',
						'$u_sex','$u_idnum','$image_name','$u_pos','$u_salary','$u_tel','$u_extens','$u_email','$startwork','$user_key','$currentdate','$work_status',$resign_date,'$u_direct')";
			if($resultins2=pg_query($in_sql2)){
			}else{
				$status++;
			}
		}
		else
		{ //กรณีมีการข้อมูลให้ update
			if($image_name!=""){
				@unlink("upload_images/$u_pic2");
			}else{
				$image_name=$u_pic2;
			}
			
			$upfdetail="update \"fuser_detail\" set 
				\"title_eng\"='$title_eng',
				\"fname_eng\"='$fname_eng',
				\"lname_eng\"='$lname_eng',
				\"nickname\"='$nickname',
				\"u_birthday\"='$u_birthday',
				\"u_status\"='$u_status',
				\"u_sex\"='$u_sex',
				\"u_idnum\"='$u_idnum',
				\"u_pic\"='$image_name',
				\"u_pos\"='$u_pos',
				\"u_salary\"='$u_salary',
				\"u_tel\"='$u_tel',
				\"u_extens\"='$u_extens',
				\"u_email\"='$u_email',
				\"startwork\"='$startwork',
				\"user_keylast\"='$user_key',
				\"keydatelast\"='$currentdate',
				work_status='$work_status',
				resign_date=$resign_date,
				\"u_direct\"='$u_direct'
				where \"id_user\"='$id_user'";
			if($res_up2=pg_query($upfdetail)){
			}else{
				$status++;
			}
		}
			
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_key', '(ALL) แก้ไขประวัติพนักงาน', '$add_date')");
		//ACTIONLOG---
	}
}

if($status == 0){
	
	pg_query("COMMIT");
	echo "<center><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></center>";
	if($method=="edit"){
		echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php?id_user=$id_user&method=edit'>";
	}else{
		echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	}
	
}else{
	pg_query("ROLLBACK");
	echo "<center><h2>บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	if($method=="edit"){
		//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php?id_user=$id_user&method=edit'>";
		echo "<center><input type=\"button\" value=\"BACK\" onClick=\"window.location='frm_IndexAdd.php?id_user=$id_user&method=edit';\" /></center>";
	}else{
		//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
		echo "<center><input type=\"button\" value=\"BACK\" onClick=\"window.location='frm_IndexAdd.php';\" /></center>";
	}
}