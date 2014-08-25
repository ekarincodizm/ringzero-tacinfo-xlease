<?php
session_start();
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$i_name=pg_escape_string($_GET["vname"]);
$i_add=pg_escape_string($_GET["vadd"]);
$i_tel=pg_escape_string($_GET["vtel"]);



if(empty($_GET["ot_ty"]))
{
 $i_type=pg_escape_string($_GET["vtype"]);
}
else
{
 $i_type=pg_escape_string($_GET["ot_ty"]);
}

 $qry_id=pg_query("select \"VenderID\" from account.vender");
 $res_id=pg_num_rows($qry_id);
 
 
 
 function insertZero($inputValue , $digit )
		{
			$str = "" . $inputValue;
			while (strlen($str) < $digit)
			{
				$str = "0" . $str;
			}
			return $str;
        }

	
$gen_vid=insertZero($res_id+1 , 3);


$in_sql="insert into account.vender( \"VenderID\",type_vd,vd_name,vd_address,vd_tel)values('VD$gen_vid','$i_type','$i_name','$i_add','$i_tel')";

if($result=pg_query($in_sql))
 {
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) เพิ่ม VENDER', '$add_date')");
	//ACTIONLOG---
  $status ="บันทึกข้อมูลแล้ว";
 }
 else
 {
  $status ="เกิดข้อพิดพลาด หรือ ข้อมูลซ้ำกัน ".$in_sql;
 }

echo $status;



?>