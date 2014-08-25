<?php
include("config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$cmd = pg_escape_string($_GET['cmd']); if($cmd == 1){ $cmd = "?cmd=1"; }
$old_pass = pg_escape_string($_POST['old_pass']);
$new_pass = pg_escape_string($_POST['new_pass']);
$cnew_pass = pg_escape_string($_POST['cnew_pass']);

include("company.php");
    foreach($company as $v){
			$_SESSION["session_company_seed"]=$v['seed'];
						
            break;
    }
$seed = $_SESSION["session_company_seed"];

pg_query("BEGIN");
$status = 0;

$qry=pg_query("SELECT * FROM fuser WHERE id_user='".$_SESSION['av_iduser']."'");
if($res=pg_fetch_array($qry)){
    $password=$res["password"];
	$password_status=$res["password_status"];
}

if($password_status == 2)
{
	if($cmd == "?cmd=1"){$Spass = "&pass_status=2";}
	else{$Spass = "?pass_status=2";}
}

if(md5(md5($old_pass).$seed) != $password){
    header("Refresh: 0; url=change_pass.php$cmd$Spass");
    echo "<script language=Javascript>alert ('รหัสผ่านเดิมไม่ถูกต้อง !');</script>";
    exit(); 
}elseif($new_pass == ""){
	header("Refresh: 0; url=change_pass.php$cmd$Spass");
    echo "<script language=Javascript>alert ('กรุณาระบุรหัสผ่านใหม่ด้วย !');</script>";
    exit(); 
}elseif($new_pass != $cnew_pass){
    header("Refresh: 0; url=change_pass.php$cmd$Spass");
    echo "<script language=Javascript>alert ('รหัสผ่านใหม่ 2 ครั้งไม่เหมือนกัน !');</script>";
    exit(); 
}elseif(md5(md5($new_pass).$seed) == $password && $password_status == 2){
	header("Refresh: 0; url=change_pass.php?cmd=1&pass_status=2");
    echo "<script language=Javascript>alert ('กรุณาตั้งรหัสผ่านใหม่ที่ไม่ใช่รหัสเดิม !');</script>";
    exit(); 
}

$new_pass = md5(md5($new_pass).$seed);

if($password_status == 2)
{
	// แก้ไขของ XLEASE
	$upd_sql="UPDATE \"fuser\" SET \"password\" = '$new_pass', \"last_datepassword\" = NOW(), \"password_status\" = '1',\"status_pass_v1\"='1' WHERE id_user='".$_SESSION['av_iduser']."';";
	if($result=pg_query($upd_sql)){ pg_query("COMMIT");
	}else{
		$status++;
	}
	
	// ต่อ base TA
	$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=devtaauto010 user=postgres password=". $_SESSION["session_company_dbpass"] ."";
	$db_connect = pg_connect($conn_string) or die("Can't Connect !");
	
	// แก้ไขของ TA
	$upd_sql_ta="UPDATE \"fuser\" SET \"password\" = '$new_pass', \"last_datepassword\" = NOW() WHERE id_user='".$_SESSION['av_iduser']."';";
	if($result_ta=pg_query($upd_sql_ta)){
	}else{
		$status++;
	}
	
	// กลับมาต่อ base หลักเหมือนเดิม
	$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
	$db_connect = pg_connect($conn_string) or die("Can't Connect !");
	
	if($status == 0){
		pg_query("COMMIT");
		header("Refresh: 0; url=logout.php");
		echo "<script language=Javascript>alert ('แก้ไขเรียบร้อยแล้ว ออกจากระบบ กรุณาล็อคอินด้วยรหัสผ่านใหม่ !');</script>";
		exit();
	}else{
		pg_query("ROLLBACK");
		header("Refresh: 0; url=change_pass.php?cmd=1&pass_status=2");
		echo "<script language=Javascript>alert ('ไม่สามารถเปลี่ยนรหัสผ่านได้ กรุณาลองใหม่อีกครั้ง !');</script>";
		exit();
	}
}
else
{
	// แก้ไขของ XLEASE
	$upd_sql="UPDATE fuser SET \"password\"='$new_pass', last_datepassword=NOW(),\"status_pass_v1\"='1' WHERE id_user='".$_SESSION['av_iduser']."';";
	if($result=pg_query($upd_sql)){ pg_query("COMMIT");
	}else{
		$status++;
	}
	
	// ต่อ base TA
	$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=devtaauto010 user=postgres password=". $_SESSION["session_company_dbpass"] ."";
	$db_connect = pg_connect($conn_string) or die("Can't Connect !");
	
	// แก้ไขของ TA
	$upd_sql_ta="UPDATE fuser SET \"password\"='$new_pass', last_datepassword=NOW() WHERE id_user='".$_SESSION['av_iduser']."';";
	if($result=pg_query($upd_sql_ta)){
	}else{
		$status++;
	}
	
	// กลับมาต่อ base หลักเหมือนเดิม
	$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
	$db_connect = pg_connect($conn_string) or die("Can't Connect !");
	
	if($status == 0){
		pg_query("COMMIT");
		echo "<script language=Javascript>";
		echo "alert('แก้ไขเรียบร้อยแล้ว ออกจากระบบ กรุณาล็อคอินด้วยรหัสผ่านใหม่ !');";
		echo "window.location='logout.php';";
		echo "</script>";
		exit();
	}else{
		pg_query("ROLLBACK");
		header("Refresh: 0; url=change_pass.php$cmd");
		echo "<script language=Javascript>alert ('ไม่สามารถเปลี่ยนรหัสผ่านได้ กรุณาลองใหม่อีกครั้ง !');</script>";
		exit();
	}
}
?>