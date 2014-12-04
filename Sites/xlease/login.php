<?php
session_start();
include("company.php");
include("ldap.php");

$comp = pg_escape_string($_POST['comp']);
if(!empty($comp)){
    foreach($company as $v){
        if($v['code'] == $comp){
            $_SESSION["session_company_code"] = $v['code'];
            $_SESSION["session_company_name"] = $v['name'];
			$_SESSION["session_company_name_thcap"] = $v['name_thcap'];
            $_SESSION["session_company_thainame"] = $v['thainame'];
			$_SESSION["session_company_thainame_thcap"] = $v['thainame_thcap'];
            $_SESSION["session_company_server"] = $v['server'];
            $_SESSION["session_company_dbname"] = $v['dbname'];
            $_SESSION["session_company_dbuser"] = $v['dbuser'];
            $_SESSION["session_company_dbpass"] = $v['dbpass'];
			$_SESSION["session_company_asset_car"] = $v['asset_car'];
			$_SESSION["session_company_asset_gas"] = $v['asset_gas'];
			$_SESSION["session_company_nv"]=$v['NV'];
			$_SESSION["session_company_jr"]=$v['JR'];
			$_SESSION["session_company_tv"]=$v['TV'];
			$_SESSION["session_company_seed"]=$v['seed'];
			$_SESSION["session_path_save_pdf"]=$v['path_save_pdf'];
			$_SESSION["session_ftp_server"]=$v['ftp_server'];
			$_SESSION["session_ftp_user_name"]=$v['ftp_user_name'];
			$_SESSION["session_ftp_user_pass"]=$v['ftp_user_pass'];		
						
            break;
        }
    }
    
    if(empty($_SESSION["session_company_code"]) || empty($_SESSION["session_company_name"]) || empty($_SESSION["session_company_server"]) || empty($_SESSION["session_company_dbname"]) || empty($_SESSION["session_company_dbuser"]) || empty($_SESSION["session_company_dbpass"])){
        echo "ข้อมูลสำหรับการเชื่อมต่อไม่ถูกต้อง";
        exit;
    }
}

require_once("config/config.php");
pg_query("BEGIN WORK");
$status = 0;

$seed = $_SESSION["session_company_seed"];

$username = pg_escape_string($_POST['username']); // username
$passSend = pg_escape_string($_POST['password']); // pass ในการเช็คค่าเปลี่ยนบริษัท
$password = md5(md5($passSend).$seed); // เข้ารหัส password
$branch = pg_escape_string($_POST['branch']); // สาขา
$remember = pg_escape_string($_POST['chkbxremember']); // จดจำฉัน

$result_chk_pwd = pg_query("SELECT * FROM \"fuser\" WHERE \"username\" = '$username' and \"password\" = '$password' and \"password_status\" = '2' ");
$num_chk_pwd = pg_num_rows($result_chk_pwd);
if($num_chk_pwd == 1){
	if($iduse = pg_fetch_array($result_chk_pwd)){$av_iduser = $iduse["id_user"];}
	$_SESSION["av_iduser"]=$av_iduser;
	header("Refresh: 0; url=change_pass.php?cmd=1&pass_status=2");
	exit();
}

$result_user=pg_query("SELECT * FROM fuser WHERE username='$username'");
$num_user=pg_num_rows($result_user);
if($num_user == 0){
	header("Refresh: 0; url=index.php?showtext=1");
	exit();
}else{
	$result=pg_query("SELECT id_user,username,password,user_group,office_id,last_log,last_datepassword,status_user,status_pass_v1 FROM fuser WHERE username='$username' AND password='$password'");
	if($arr = pg_fetch_array($result)){
		$status_user=$arr["status_user"];
		$status_pass_v1=$arr["status_pass_v1"];
		if($status_user == 'f'){
			header("Refresh: 0; url=index.php?showtext=2");
			exit();
		}else{
			$av_officeid=$arr["office_id"];
			session_register("av_officeid");
			$_SESSION["av_officeid"]=$av_officeid;

			$av_usergroup=$arr["user_group"];
			session_register("av_usergroup");
			$_SESSION["av_usergroup"]=$av_usergroup;

			$av_iduser=$arr["id_user"];
			session_register("av_iduser");
			$_SESSION["av_iduser"]=$av_iduser;

			$_SESSION['uid'] = $arr["id_user"];

			$_SESSION['user_login'] = $username;
			$_SESSION['pass_login'] = $passSend;
			$_SESSION['branch_login'] = $branch; // สาขาที่ login
			$_SESSION['lasttime_login'] = $arr["last_log"]; // ล็อคอินล่าสุด
			
			//เก็บคุุกกี้ถ้ามีการเลือก"จดจำฉัน"
			if($remember=="1")
			{
				//setcookie("xleaseUsername",$username,time()+3600*24*2);
				setcookie("xleaseUsername",$username,time()+3600*24*356); //set cookie ให้อยู่ตลอดไป
			}
			else if(isset($_COOKIE['carSystemUsername']))
			{
				setcookie("carSystemUsername");
			}

			$upd_sql="UPDATE fuser SET \"last_log\"=NOW() WHERE username='$username' AND password='$password';";
			if($result=pg_query($upd_sql)){
				
				$stime = explode("-", date( "Y-m-d", strtotime( $arr['last_datepassword'])));
				foreach ($stime as $value) { }
				$timeing = GregorianToJD($stime[1], $stime[2], $stime[0])-GregorianToJD(date("m"), date("d"), date("Y"));
				$timeing = abs($timeing);
				
				$stime2 = explode("-", date( "Y-m-d", strtotime( $arr['last_log'])));
				foreach ($stime as $value) { }
				$timeing2 = GregorianToJD($stime2[1], $stime2[2], $stime2[0])-GregorianToJD(date("m"), date("d"), date("Y"));
				$timeing2 = abs($timeing2);
				$_SESSION['lasttime_login_number'] = $timeing2; // จำนวนวัน
				if($status_pass_v1 !='1'){
					header("Refresh: 0; url=change_pass.php?cmd=2");
					exit();
				}
				if($timeing > 45){
					header("Refresh: 0; url=change_pass.php?cmd=1");
					exit();
				}else{
					//กรณีเข้า username ถูกต้อง ให้ยกเลิกสถานะในการเข้าผิดออก ให้เริ่มนับ 1 ใหม่
					$uplogin="update \"logs_nw_login\" set cancel='FALSE' WHERE username='$username'";
					if($res_log=pg_query($uplogin)){
					}else{
						$status++;
					}
					
					if($status == 0)
					{
						pg_query("COMMIT");
						
						//================== ส่งรหัสผ่านไป อัพเดทที่ LDAP Server ==================
							$ldapconn = connect_ldap(); // Database Connection (LDAP)
							if (isset($ldapconn)) {
								
								$userdn = search_ldap_user_entry_from_uid($ldapconn, $username);
								if (isset($userdn)) {
									$pwd_mod = change_ldap_user_password($ldapconn, $userdn, $passSend);
									if ($pwd_mod) {
										//echo "Password Changed";
									} else {
										$error = ldap_error($ldapconn);
									}
								} else {
									$error = ldap_error($ldapconn);
								}
							}
							
							// Database Connection (Postgres)
							$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
							$db_connect = pg_connect($conn_string) or die("Can't Connect !");
						//================== จบการส่งรหัสผ่านไป อัพเดทที่ LDAP Server ==================
					}
					else
					{
						pg_query("ROLLBACK");	
					}
					
					header("Refresh: 0; url=nw/annoucement/frm_show.php");
					exit();
				}
			}else{
				header("Refresh: 0; url=index.php");
				exit();
			}
		}
	}else{
		//ตรวจสอบก่อนว่าสถานะของ user เป็น ระงับการใช้งานหรือไม่
		$num_status=pg_query("SELECT * FROM fuser WHERE username='$username' and status_user='FALSE'");
		$num_row_status=pg_num_rows($num_status);
		
		//กรณี user ยังไม่ถูกระงับการใช้งาน
		if($num_row_status == 0){
			//กรณีกรอกรหัสผ่านผิดต้องตรวจสอบด้วยว่าผิดครบ 3 ครั้งหรือไม่ ถ้าผิดครบ 3 ครั้งให้อัพเดทสถานะ user เป็นระงับการใช้งาน โดยตรวจสอบเฉพาะที่เป็นวันที่ปัจจุบันเท่านั้น
			$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
			$currentdate = trim(substr($nowdate,0,10));
			$currenttime=trim(substr($nowdate,10));
			$query_count=pg_query("select count(\"username\") as count from \"logs_nw_login\" where username='$username' and \"loginDate\"='$currentdate' and \"cancel\"='TRUE'");
			if($res_count=pg_fetch_array($query_count)){
				$countuser=$res_count["count"];
			}
			if($countuser < 3){
				$querymax=pg_query("select max(\"logid\") as maxcount from \"logs_nw_login\"");
				$num_row_max=pg_num_rows($querymax);
				if($num_row_max==0){
					$maxcount=0;
				}else{
					if($res_max=pg_fetch_array($querymax)){
						$maxcount=$res_max["maxcount"];
					}
				}
				$maxcount=$maxcount+1;
				$ins_count="insert into \"logs_nw_login\" (\"logid\",\"username\",\"loginDate\",\"loginTime\",\"cancel\") values ('$maxcount','$username','$currentdate','$currenttime','TRUE')";
				if($res_count=pg_query($ins_count)){
				}else{
					$status++;
				}
				
				if($countuser == 2){
					$upstatus="update fuser set status_user='FALSE' WHERE username='$username'";
					if($res_up=pg_query($upstatus)){
					}else{
						$status++;
					}
					
					$uplogin="update \"logs_nw_login\" set cancel='FALSE' WHERE username='$username'";
					if($res_log=pg_query($uplogin)){
					}else{
						$status++;
					}
				}
				if($status == 0){
					pg_query("COMMIT");
				}else{
					pg_query("ROLLBACK");	
				}
			}
			header("Refresh: 0; url=index.php?showtext=3");
			exit();
		}else{
			//กรณีสถานะ user ถูกระงับแล้ว
			header("Refresh: 0; url=index.php?showtext=2");
			exit();
		}
	}
}
?>
