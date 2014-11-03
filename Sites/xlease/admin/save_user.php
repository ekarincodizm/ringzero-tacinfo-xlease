<?php
session_start();
include("../config/config.php");
include("../nw/function/checknull.php");
$v_title=split(",",pg_escape_string($_GET["f_title"]));
$v_fullname=split(",",pg_escape_string($_GET["f_fullname"]));
$v_lname=split(",",pg_escape_string($_GET["f_lname"]));
$v_username=split(",",pg_escape_string($_GET["f_username"]));
$v_pass=split(",",pg_escape_string($_GET["f_pass"]));
$v_gp=split(",",pg_escape_string($_GET["f_gp"]));
$v_fd=split(",",pg_escape_string($_GET["f_fd"]));
$v_office=split(",",pg_escape_string($_GET["f_office"]));
$v_status=split(",",pg_escape_string($_GET["f_status"]));
$v_nickname = split(",",pg_escape_string($_GET["f_nickname"]));

$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$v_isUseTA = split(",", pg_escape_string($_GET["IsUseTA"])); 
$v_isAdmin = split(",", pg_escape_string($_GET["Admin_status"]));
  
include("../company.php");

$all_user = sizeof($v_username);

$i = 0;
$res = 0;

foreach($company as $v){
		$_SESSION["session_company_seed"]=$v['seed'];
					
		break;
}

function insertZero($inputValue , $digit )
{
	$str = "" . $inputValue;
	while (strlen($str) < $digit)
	{
		$str = "0" . $str;
	}
	return $str;
}
 
 //$v_id=pg_escape_string($_GET["id"]);
 //find last id
pg_query("BEGIN");

while($i<$all_user)
{
	$v_title1=checknull(trim($v_title[$i]));
	$v_fullname1=checknull(trim($v_fullname[$i]));
	$v_lname1=checknull(trim($v_lname[$i]));
	$v_username1=checknull(trim($v_username[$i]));
	$v_pass1=trim($v_pass[$i]);
	$v_gp1=checknull(trim($v_gp[$i]));
	$v_fd1=checknull(trim($v_fd[$i]));
	$v_office1=checknull(trim($v_office[$i]));
	$v_status1=checknull(trim($v_status[$i]));
	$v_nickname1 = checknull(trim($v_nickname[$i]));
	$v_isUseTA_i = checknull(trim($v_isUseTA[$i]));
	$v_isAdmin_i = checknull(trim($v_isAdmin[$i]));	
	$qry_uname=pg_query("select * from fuser where username=$v_username1");
	$nur_name=pg_num_rows($qry_uname);
	if($nur_name > 0)
	{
		echo "ชื่อ username ซ้ำ"; 
	}
	else
	{
		
		$qrylastid=pg_query("select id_user from fuser");
		$numrow=pg_num_rows($qrylastid);
		
		$idplus=$numrow+1;
			
		$id_plus=insertZero($idplus , 3);
		// end find last id
		$seed = $_SESSION["session_company_seed"];
		  
		$v_pass = md5(md5($v_pass1).$seed);
		
		$u_detail_sql = "insert into \"fuser_detail\"(\"id_user\",\"nickname\",\"user_keylast\",\"keydatelast\",\"work_status\") values('$id_plus',$v_nickname1,'$user_id','$add_date',$v_status1)";
		
		$in_sql="insert into fuser(id_user,username,password,office_id,user_group,status_user,title,fname,lname,user_dep,\"isUserTA\",isadmin) values('$id_plus',$v_username1,'$v_pass',$v_office1,$v_gp1,$v_status1,$v_title1,$v_fullname1,$v_lname1,$v_fd1,$v_isUseTA_i,$v_isAdmin_i)";
		
		 if(!pg_query($in_sql))
		 {
			  $status.="error insert  fuser ".$in_sql."<br />";
			  $res++;
		 }
		 if(!pg_query($u_detail_sql))
		 {
			 $status.="error insert  fuser_details ".$u_detail_sql."<br />";
			  $res++;
		 }
	}
	
	$i++;
}
if($res==0)
{
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่มผู้ใช้งาน', '$add_date')");
	//ACTIONLOG---
	$status ="insert ข้อมูลแล้ว";
	pg_query("COMMIT");
}
else
{
	pg_query("ROLLBACK");
}
echo "<br />".$status; // แจ้งผลการทำงานของ Program

?>