<?php
session_start();
include("../../config/config.php");
$O_RECEIPT = pg_escape_string($_POST['O_RECEIPT']); // เลขที่ใบเสร็จ
$idno = pg_escape_string($_POST['idno']); // เลขที่สัญญา
$IDCarTax = pg_escape_string($_POST['chk']); // รหัสค่าใช้จ่าย
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ตัดใบเสร็จค่าใช้จ่ายเก่า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script>
		function RefreshMe()
		{
			opener.location.reload(true);
			self.close();
		}
	</script>
</head>
<body>
	<center>
		<?php
		pg_query("BEGIN");
		$status = 0;
		
		if($O_RECEIPT == "" || $idno == "") // ถ้า เลขที่ใบเสร็จ หรือ เลขที่สัญญา ไม่มีค่า
		{
			if($O_RECEIPT == "")
			{
				$status++;
				$error .= "<br/>เกิดข้อผิดพลาด ไม่พบข้อมูล เลขที่ใบเสร็จ";
			}
			
			if($idno == "")
			{
				$status++;
				$error .= "<br/>เกิดข้อผิดพลาด ไม่พบข้อมูล เลขที่สัญญา";
			}
		}
		elseif($IDCarTax == "") // ถ้ายังไม่ได้เลือกค่าใช้จ่าย
		{
			$status++;
			$error .= "<br/>กรุณาเลือกค่าใช้จ่ายด้วย";
		}
		else // ถ้ามีค่าครบถ้วน
		{
			// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง (Concurrency)
			$qry_chk_use_receipt = pg_query("SELECT \"O_RECEIPT\" FROM \"FOtherpay\" WHERE \"O_RECEIPT\" = '$O_RECEIPT' AND \"RefAnyID\" IS NOT NULL AND \"Cancel\" = FALSE");
			$chk_use_receipt = pg_num_rows($qry_chk_use_receipt);
			if($chk_use_receipt > 0)
			{
				$status++;
				$error .= "<br/>มีการทำรายการไปก่อนหน้านี้แล้ว";
			}
			else
			{
				// ตรวจสอบว่าอยู่ระหว่างการขออนุมัติยกเลิกหรือไม่
				$qry_wait_app=pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue_reserve\" WHERE \"IDCarTax\" = '$IDCarTax' AND \"Approved\"='9' ");
				$nub_wait_app = pg_num_rows($qry_wait_app);
				if($nub_wait_app > 0)
				{
					$status++;
					$error .= "<br/>ค่าใช้จ่ายที่เลือก อยู่ระหว่างรอการอนุมัติยกเลิก";
				}
				else
				{
					// ตรวจสอบว่ามีการอนุมัติยกเลิกหรือไม่
					$qry_cancel_app=pg_query("select \"IDCarTax\" from carregis.\"CarTaxDue_reserve\" WHERE \"IDCarTax\" = '$IDCarTax' AND \"Approved\"='1' ");
					$nub_cancel_app = pg_num_rows($qry_cancel_app);
					if($nub_cancel_app > 0)
					{
						$status++;
						$error .= "<br/>ค่าใช้จ่ายที่เลือก ถูกอนุมัติยกเลิกไปแล้ว";
					}
				
					// ตรวจสอบก่อนว่า มีการปิดหนี้ไปก่อนหน้านี้แล้วหรือยัง (Concurrency)
					$qry_chk_close_debt = pg_query("SELECT \"cuspaid\" FROM carregis.\"CarTaxDue\" WHERE \"IDCarTax\" = '$IDCarTax'");
					$chk_close_debt = pg_fetch_result($qry_chk_close_debt,0);
					if($chk_close_debt == "t") // ถ้ามีการปิดหนี้ไปก่อนหน้านี้แล้ว
					{
						$status++;
						$error .= "<br/>มีการปิดค่าใช้จ่ายที่เลือก ไปก่อนหน้านี้แล้ว";
					}
					else // ถ้ายังไม่ได้ปิดหนี้
					{
						// จับคู่ใบเสร็จกับหนี้
						$up_sql1 = pg_query("UPDATE \"FOtherpay\" SET \"RefAnyID\" = '$IDCarTax' WHERE \"O_RECEIPT\" = '$O_RECEIPT' AND \"IDNO\" = '$idno' AND \"RefAnyID\" IS NULL");
						if($up_sql1)
						{
							// หาจำนวนหนี้คงเหลือ
							$qry_Balance = pg_query("
														SELECT
															\"CusAmt\" - (select case when sum(\"O_MONEY\") is not null then sum(\"O_MONEY\") else 0.00 end from \"FOtherpay\" where \"RefAnyID\" = \"CarTaxDue\".\"IDCarTax\" and \"Cancel\" = false) AS \"Balance\"
														FROM
															carregis.\"CarTaxDue\"
														WHERE
															\"IDCarTax\" = '$IDCarTax'
													");
							$Balance = pg_fetch_result($qry_Balance,0); // จำนวนหนี้คงเหลือ ที่ยังไม่มีใบเสร็จ
							
							// ถ้า จำนวนหนี้คงเหลือ ที่ยังไม่มีใบเสร็จ เท่ากับ 0 หรือน้อยกว่า ถือว่าจ่ายครบแล้ว
							if($Balance != "" && $Balance <= 0)
							{
								$up_sql2 = pg_query("UPDATE carregis.\"CarTaxDue\" SET \"cuspaid\" = TRUE WHERE \"IDCarTax\" = '$IDCarTax'");
								if($up_sql2){	
								}else{
									$status++;
								}
							}
						}
						else
						{
							$status++;
						}
					}
				}
			}
		}

		if($status == 0)
		{
			pg_query("COMMIT");
			//pg_query("ROLLBACK");
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', 'ตัดใบเสร็จค่าใช้จ่ายเก่า', '$datelog')");
			//ACTIONLOG---
			
			echo "<h2><font color=\"#0000FF\">บันทึกเรียบร้อยแล้ว</font></h2>";
			echo "<br/>";
			echo "<input type=\"button\" value=\"ตกลง\" class=\"ui-button\" onClick=\"RefreshMe();\" />";
		}
		else
		{
			pg_query("ROLLBACK");
			echo "<h2><font color=\"#FF0000\">ไม่สามารถบันทึกข้อมูลได้!! $error</font></h2>";
			echo "<br/>";
			echo "<input type=\"button\" value=\"ปิด\" class=\"ui-button\" onClick=\"window.close();;\" />";
		}
		?>
	</center>
</body>
</html>