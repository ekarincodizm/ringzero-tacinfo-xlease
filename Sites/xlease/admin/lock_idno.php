<?php
session_start();
include("../config/config.php");
include("../nv_function.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$id_no=pg_escape_string($_GET["idnoget"]);
$sslock=pg_escape_string($_GET["stalock"]);
$fscarnum=pg_escape_string($_GET["fcarnum"]);
$fscusid=pg_escape_string($_GET["fcusnum"]);
$n_asid=pg_escape_string($_GET["fass_id"]);
	
pg_query("BEGIN");
//gen coderef 1,2

$gen1=pg_query("select gen_encode_ref1('$id_no')");
$resgen1=pg_fetch_result($gen1,0);
	
$resgen2=$fscarnum;

if($n_asid!=1)
{
	if($sslock==0)
	{
		$resgen2 = nv_correct_TranIDRef2($resgen2); // รัน function เพื่อแก้ปัญหาที่ TranIDRef2 มีตัว a-z,A-Z,- ติดอยู่ซึ่งผิดหลัก โดย function return ค่าเป็นตัวเลขล้วน เปลี่ยน a-z,A-Z เป็น 0 และ - เป็น 9
		$in_lockfp="Update \"Fp\" SET \"LockContact\"=true,\"TranIDRef1\"='$resgen1',\"TranIDRef2\"='$resgen2'  WHERE \"IDNO\"='$id_no' "; 

		if($result_lock=pg_query($in_lockfp))
		{
			$stat_Fp="OK update at Fn".$in_lockfp;
			//update fa1
			$sql_upfa1="update \"Fa1\" SET \"Approved\"=true WHERE \"CusID\"='$fscusid' ";
			if($result_fa1=pg_query($sql_upfa1))
			{
				$stat_Fa1="OK update at Fa1".$sql_upfa1;
			}
			else
			{ 
				$stat_Fa1="error update at Fa1".$sql_upfa1;
			} 
			// end upate fa1
		}
		else
		{
			$stat_Fp ="error update  Fn Re".$in_lockfp;
		}	 
		
		$res_up="Lock ข้อมูล $idno แล้ว  gencode1= ".$resgen1." gencode2=".$resgen2 ; 
	}
	else
	{
		$in_fp="Update \"Fp\" SET \"LockContact\"=false WHERE \"IDNO\"='$id_no' "; 
		if($result_unlock=pg_query($in_fp))
		{
			$stat_Fp="OK update at Fn".$in_fp;
		  
			//update fa1
			$sql_upfa1="update \"Fa1\" SET \"Approved\"=false WHERE \"CusID\"='$fscusid' ";
			if($result_fa1=pg_query($sql_upfa1))
		    {
				$stat_Fa1="OK update at Fa1".$sql_upfa1;
			}
			else
			{ 
				$stat_Fa1="error update at Fa1".$sql_upfa1;
			} 
			// end upate fa1
		 }
		else
		{
			$stat_Fp ="error update  Fn Re".$in_fp;
		}	 
		$res_up="ปลด Lock ข้อมูล $idno แล้ว"; 
	}
	if(($resgen1) or ($result_lock)  or ($result_unlock) or ($result_fa1))
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ทำการอนุมัติสัญญาเช่าซื้อ', '$add_date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo $res_up."บันทึกข้อมูลเรียบร้อย รอสักครู่ .";
		//echo "<meta http-equiv=\"refresh\" content=\"2;URL=../list_menu.php\" >";
	}
	else
	{
		pg_query("ROLLBACK");
		echo " มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่";
		//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
	} 
}
else
{
	if($sslock==0)
	{
	
		$in_lockfp="Update \"Fp\" SET \"LockContact\"=true  WHERE \"IDNO\"='$id_no' "; 
		 
		if($result_lock=pg_query($in_lockfp))
		{
			$stat_Fp="OK update at Fn".$in_lockfp;
			//update fa1
			$sql_upfa1="update \"Fa1\" SET \"Approved\"=true WHERE \"CusID\"='$fscusid' ";
			if($result_fa1=pg_query($sql_upfa1))
			{
				$stat_Fa1="OK update at Fa1".$sql_upfa1;
			}
			else
			{ 
				$stat_Fa1="error update at Fa1".$sql_upfa1;
			}  
			// end upate fa1
		}
		else
		{
			$stat_Fp ="error update  Fn Re".$in_lockfp;
		}	 
		$res_up="Lock ข้อมูล $idno แล้ว  gencode1= ".$resgen1." gencode2=".$resgen2 ; 
	}
	else
	{
		$in_fp="Update \"Fp\" SET \"LockContact\"=false WHERE \"IDNO\"='$id_no' "; 

		if($result_unlock=pg_query($in_fp))
		{
			$stat_Fp="OK update at Fn".$in_fp;
		  
			//update fa1
			$sql_upfa1="update \"Fa1\" SET \"Approved\"=false WHERE \"CusID\"='$fscusid' ";
			if($result_fa1=pg_query($sql_upfa1))
			{
				$stat_Fa1="OK update at Fa1".$sql_upfa1;
			}
			else
			{ 
				$stat_Fa1="error update at Fa1".$sql_upfa1;
			} 
			// end upate fa1
		}
		else
		{
			$stat_Fp ="error update  Fn Re".$in_fp;
		}	 
		$res_up="ปลด Lock ข้อมูล $idno แล้ว"; 
	}
   
	if(($resgen1) or ($result_lock)  or ($result_unlock) or ($result_fa1))
	{
		pg_query("COMMIT");
		echo $res_up."บันทึกข้อมูลเรียบร้อย รอสักครู่ .";
		//echo "<meta http-equiv=\"refresh\" content=\"2;URL=../list_menu.php\" >";
	}
	else
	{
		pg_query("ROLLBACK");
		echo " มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่";
		//echo "<meta http-equiv=\"refresh\" content=\"5;URL=../list_menu.php\" >";
	}
}
?>