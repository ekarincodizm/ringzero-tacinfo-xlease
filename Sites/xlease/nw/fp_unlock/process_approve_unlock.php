<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$autoID = trim(pg_escape_string($_GET["autoID"]));
$appvRemark = pg_escape_string($_GET["appvRemark"]);
$appvStatus = pg_escape_string($_GET["appvStatus"]);

$appvRemark = str_replace("<br>","\n",$appvRemark);
$appvRemark = checknull($appvRemark);

pg_query("BEGIN");
$status = 0;

$qry_data = pg_query("select \"IDNO\", \"doerRemark\" from \"Fp_unlock\" where \"autoID\" = '$autoID' ");
$id_no = pg_result($qry_data,0);

//cusid
$qry_fp = pg_query("select A.\"CusID\" from \"VContact\" A LEFT OUTER JOIN \"Fp\" B on B.\"IDNO\" = A.\"IDNO\" where  A.\"IDNO\" = '$id_no' ");
$res_cusid = pg_result($qry_fp,0);

// ตรวจสอบมีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_chk = pg_query("select * from \"Fp_unlock\" where \"autoID\" = '$autoID' and \"appvStatus\" <> '9' ");
$row_chk = pg_num_rows($qry_chk);

if($row_chk > 0)
{
	$status++;
	echo "มีการทำรายการไปก่อนหน้านี้แล้ว";;
}

if($status == 0)
{
	if($appvStatus == 0)
	{
		$in_status = "Update \"Fp_unlock\" SET \"appvID\" = '$user_id', \"appvStamp\" = '$add_date', \"appvStatus\" = '$appvStatus', \"appvRemark\" = $appvRemark WHERE \"autoID\" = '$autoID' and \"appvStatus\" = '9' ";
		if($result_status = pg_query($in_status))
		{
		
		}
		else
		{
			$status++;
		}
	}
	elseif($appvStatus == 1)
	{
		$in_status = "Update \"Fp_unlock\" SET \"appvID\" = '$user_id', \"appvStamp\" = '$add_date', \"appvStatus\" = '$appvStatus', \"appvRemark\" = $appvRemark WHERE \"autoID\" = '$autoID' and \"appvStatus\" = '9' ";
		if($result_status = pg_query($in_status))
		{
		
		}
		else
		{
			$status++;
		}
		
		$in_fp="Update \"Fp\" SET \"LockContact\"=false WHERE \"IDNO\"='$id_no' "; 
		if($result_unlock=pg_query($in_fp))
		{
			$stat_Fp="OK update at Fn".$in_fp;
		  
			//update fa1
			$sql_upfa1="update \"Fa1\" SET \"Approved\"=false WHERE \"CusID\"='$res_cusid' ";
			if($result_fa1=pg_query($sql_upfa1))
			{
				$stat_Fa1="OK update at Fa1".$sql_upfa1;
			}
			else
			{ 
				$stat_Fa1="error update at Fa1".$sql_upfa1;
				$status++;
			} 
			// end upate fa1
		}
		else
		{
			$stat_Fp ="error update  Fn Re".$in_fp;
			$status++;
		}	 
		$res_up="ปลด Lock ข้อมูล $idno แล้ว";
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', 'อนุมัติปลดล็อกสัญญาเช่าซื้อ ', '$add_date')");
	//ACTIONLOG---
	
	//echo $res_up."บันทึกข้อมูลเรียบร้อย";
	//echo "<meta http-equiv=\"refresh\" content=\"2;URL=../list_menu.php\" >";
}
else
{
	pg_query("ROLLBACK");
	//echo " มีข้อผิดพลาดในการบันทึก";
	//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
}
?>