<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]);

$zz = 1;

function search_money($S_contractID , $S_ptNum_old ) // function ในการหาค่าติดตามทวงถาม
{
	// หาค่าติดตามทวงถาม
	$S_sql_collection=pg_query("select \"debtID\", \"debtStatus\", \"typePayAmt\" from public.\"thcap_temp_otherpay_debt\" where \"contractID\" = '$S_contractID' and \"typePayID\" = '1003' and \"typePayRefValue\" = '$S_ptNum_old' and \"debtStatus\" in('1','2') ");
	$S_numrows_collection = pg_num_rows($S_sql_collection);
	if($S_numrows_collection > 0)
	{
		while($S_resultcollection = pg_fetch_array($S_sql_collection))
		{
			$S_debtID = $S_resultcollection["debtID"]; // เลขที่ค่าติดตามทวงถาม
			$S_debtStatus = $S_resultcollection["debtStatus"]; // สถานะว่าจ่ายหรือยัง
			$S_typePayAmt = $S_resultcollection["typePayAmt"]; // จำนวนเงิน
		}
		
		if($S_debtStatus == 1)
		{
			$S_collection = "ค้างชำระ"; // ค่าติดตามทวงถาม
		}
		elseif($S_debtStatus == 2)
		{
			$S_sql_pa = pg_query("select \"receiptID\" from public.\"thcap_v_receipt_otherpay\" where \"debtID\" = '$S_debtID' ");
			while($S_resultpa = pg_fetch_array($S_sql_pa))
			{
				$S_receiptID_pa = $S_resultpa["receiptID"]; // เลขที่ใบเสร็จของค่าติดตามทวงถาม
			}
			
			$S_collection = $S_typePayAmt." ($S_receiptID_pa)"; // ค่าติดตามทวงถาม
		}
	}
	else
	{
		$S_collection = ""; // ค่าติดตามทวงถาม
	}
	// จบการหาค่าติดตามทวงถาม
			
	return $S_collection;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<style type="text/css">
	.odd{
		background-color:#FFFFCF;
		font-size:12px
	}
	.even{
		background-color:#D5EFFD;
		font-size:12px
	}
	.sum{
		background-color:#FFC0C0;
		font-size:12px
	}
	</style>
</head>

<body>
<?php
$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลักจาก mysql
$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID' and \"CusState\"='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}


$sql_head=pg_query("select \"conLoanIniRate\", \"conLoanMaxRate\", \"conDate\", \"conStartDate\", \"conRepeatDueDay\", \"conLoanAmt\", \"conTerm\", \"conMinPay\"
					from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
$rowhead=pg_num_rows($sql_head);
$i = 1;
while($result=pg_fetch_array($sql_head))
{
	$conLoanIniRate = $result["conLoanIniRate"];
	$conLoanMaxRate = $result["conLoanMaxRate"];
	$conDate = $result["conDate"];
	$conStartDate = $result["conStartDate"];
	$conRepeatDueDay = $result["conRepeatDueDay"];
	$conLoanAmt = $result["conLoanAmt"];
	$conTerm = $result["conTerm"];
	$conMinPay = $result["conMinPay"];
}
?>

<center>
<h2>
<br>
ตารางชำระเงินกู้
<br>
เลขที่สัญญา : <?php echo $contractID; ?>
<br>
<?php echo $name3; ?>
<br>
<?php echo "ยอดเงินกู้ : ".number_format($conLoanAmt,2)." บาท ($conTerm งวด)"; ?>
<br>
</h2>
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#000000">
	<tr align="center" bgcolor="#79BCFF">
		<th>งวดที่</th>
		<th>วันครบกำหนด</th>
		<th>วันที่ชำระ</th>
		<th>ยอดชำระขั้นต่ำ</th>
		<th>ยอดที่จ่าย</th>
		<th>เลขที่ใบเสร็จ</th>
		<th>ค่าติดตามทวงถาม</th>
	</tr>
<?php

	$hold = 0; // จำนวนเงินที่ค้างชำระอยู่
	$money = 1; // เงินของเก่า 0ถ้าขาด 1ถ้าไม่มี 2ถ้าเงินเหลือ
	
	$i = 1;
	// งวดแรก
	$sql_one=pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' order by \"ptNum\" ASC limit 1 ");
	$numrows_one = pg_num_rows($sql_one);
	while($resultone=pg_fetch_array($sql_one))
	{
		$ptNum_now = $resultone["ptNum"]; // งวดที่
		$ptDate_now = $resultone["ptDate"]; // วันครบกำหนด
		$ptMinPay_now = $resultone["ptMinPay"]; // ยอดจ่ายขั้นต่ำ
		
		// งวดที่ต้องชำระ
		$ptNum_old = $ptNum_now;
		$ptDate_old = $ptDate_now;
		$ptMinPay_old = $ptMinPay_now;
		$hold = $ptMinPay_old;
	}
	
	for($d=1;$d<=$conTerm*3;$d++)
	{
		// หางวดต่อๆไป
		$sql_next=pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_old' order by \"ptNum\" ASC LIMIT 1");
		$numrows_next = pg_num_rows($sql_next);
		while($resultnext=pg_fetch_array($sql_next))
		{
			$ptNum_now = $resultnext["ptNum"]; // งวดที่ ที่หาได้
			$ptDate_now = $resultnext["ptDate"]; // วันครบกำหนด ที่หาได้
			$ptMinPay_now = $resultnext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
		}
	
		if($i == 1)
		{ // การจ่ายเงินครั้งแรก
			$sql_table=pg_query("select \"receiveDate\", \"receiveAmount\", \"receiptID\", \"lastReceiveDate\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
		}
		else
		{ // การจ่ายเงินครั้งที่ 2 ขึ้นไป
			$sql_table=pg_query("select \"receiveDate\", \"receiveAmount\", \"receiptID\", \"lastReceiveDate\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' and \"receiveDate\" >= '$receiveDate_last' and \"lastReceiveDate\" >= '$lastReceiveDate_last' and \"receiptID\" <> '$receiptID_last' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
		}
		$numrows_table = pg_num_rows($sql_table);
		if($numrows_table > 0)
		{
			while($result=pg_fetch_array($sql_table))
			{
				$receiveDate = $result["receiveDate"]; // วันที่ชำระ
				$receiveAmount = $result["receiveAmount"]; // จำนวนเงิน
				$receiptID = $result["receiptID"]; // เลขที่ใบเสร็จ
				$lastReceiveDate = $result["lastReceiveDate"]; // วันที่ชำระครั้งที่แล้ว
				
				// หาค่าติดตามทวงถาม
				$sql_collection=pg_query("select \"debtID\", \"debtStatus\", \"typePayAmt\" from public.\"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayID\" = '1003' and \"typePayRefValue\" = '$ptNum_old' and \"debtStatus\" in('1','2') ");
				$numrows_collection = pg_num_rows($sql_collection);
				if($numrows_collection > 0)
				{
					while($resultcollection = pg_fetch_array($sql_collection))
					{
						$debtID = $resultcollection["debtID"]; // เลขที่ค่าติดตามทวงถาม
						$debtStatus = $resultcollection["debtStatus"]; // สถานะว่าจ่ายหรือยัง
						$typePayAmt = $resultcollection["typePayAmt"]; // จำนวนเงิน
					}
					
					if($debtStatus == 1)
					{
						$collection = "ค้างชำระ"; // ค่าติดตามทวงถาม
					}
					elseif($debtStatus == 2)
					{
						$sql_pa = pg_query("select \"receiptID\" from public.\"thcap_v_receipt_otherpay\" where \"debtID\" = '$debtID' ");
						while($resultpa = pg_fetch_array($sql_pa))
						{
							$receiptID_pa = $resultpa["receiptID"]; // เลขที่ใบเสร็จของค่าติดตามทวงถาม
						}
						
						$collection = $typePayAmt." ($receiptID_pa)"; // ค่าติดตามทวงถาม
					}
				}
				else
				{
					$collection = ""; // ค่าติดตามทวงถาม
				}
				// จบการหาค่าติดตามทวงถาม
				
				
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//ถ้าไม่มียอดจ่ายขั้นต่ำ
				if($ptMinPay_old <= 0)
				{
					//ถ้าจ่ายเงินก่อนวันครบกำหนด
					if(substr($receiveDate,0,10) <= substr($ptDate_old,0,10)) // ถ้าจ่ายเงินไม่เกินวันครบกำหนด
					{
						if($money == 1) // ถ้างวดเก่าไม่มีค้าง
						{
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_old_chk = $ptNum_old;
							
							// เงินที่เหลือ
							$hold = $receiveAmount - $ptMinPay_old;
							
							$sql_table_zero = pg_query("select \"receiveDate\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' and \"receiveDate\" >= '$receiveDate' and \"lastReceiveDate\" >= '$lastReceiveDate' and \"receiptID\" <> '$receiptID' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
							$numrows_zero = pg_num_rows($sql_table_zero);
							while($chk_zero = pg_fetch_array($sql_table_zero))
							{
								$receiveDate_zero = $chk_zero["receiveDate"]; // วันที่จะจ่ายครั้งต่อไป
							}
							
							// กำหนดไว้ที่จะต้องชำระต่อไป
							if($numrows_zero != 0) // ถ้ามีการจ่ายครั้งต่อไป
							{
								if(substr($receiveDate_zero,0,10) >= substr($ptDate_now,0,10) || substr($receiveDate,0,10) >= substr($ptDate_old,0,10))
								{
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
								else
								{
									if($hold < 0){$hold *= -1;}
									$ptNum_old = $ptNum_old;
									$ptDate_old = $ptDate_old;
									$ptMinPay_old = $ptMinPay_old;
									//$money = 0;
									$money = 1;
								}
							}
							else
							{
								$hold = $ptMinPay_now;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 1;
							}
						}
					}
					
					//ถ้าจ่ายเงินเกินวันครบกำหนด
					else //ถ้าจ่ายเงินเกินวันครบกำหนด ($receiveDate > $ptDate_old)
					{
						if($money == 1) // ถ้าไม่มีเงินค้าง
						{
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							$sql_table_zero = pg_query("select \"receiveDate\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' and \"receiveDate\" >= '$receiveDate' and \"lastReceiveDate\" >= '$lastReceiveDate' and \"receiptID\" <> '$receiptID' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
							$numrows_zero = pg_num_rows($sql_table_zero);
							while($chk_zero = pg_fetch_array($sql_table_zero))
							{
								$receiveDate_zero = $chk_zero["receiveDate"]; // วันที่จะจ่ายครั้งต่อไป
							}
								
							//if($canpay >= 0) // ถ้ามีเงินพอจ่ายของเก่า
							if($numrows_zero != 0) // ถ้ามีการจ่ายครั้งต่อไป
							{
								if(substr($receiveDate_zero,0,10) >= substr($ptDate_now,0,10) || substr($receiveDate,0,10) >= substr($ptDate_old,0,10))
								{
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
								else
								{
									if($hold < 0){$hold *= -1;}
									$ptNum_old = $ptNum_old;
									$ptDate_old = $ptDate_old;
									$ptMinPay_old = $ptMinPay_old;
									//$money = 0;
									$money = 1;
								}
								/*
								// กำหนดไว้ที่จะต้องชำระต่อไป
								$hold = $ptMinPay_now;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 1;
								*/
							}
							else
							{
								$hold = $ptMinPay_now;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 1;
							}
						}
					}
				}
				//จบไม่มียอดจ่ายขั้นต่ำ
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//ถ้าจ่ายเงินก่อนวันครบกำหนด
				elseif(substr($receiveDate,0,10) <= substr($ptDate_old,0,10)) // ถ้าจ่ายเงินไม่เกินวันครบกำหนด
				{
					if($money == 1) // ถ้างวดเก่าไม่มีค้าง
					{
						if($ptNum_old%2==0){
							echo "<tr class=\"odd\" align=\"center\">";
						}else{
							echo "<tr class=\"even\" align=\"center\">";
						}
						
						echo "<td>$ptNum_old</td>"; // งวดที่
						echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
						echo "<td>$receiveDate</td>"; // วันที่ชำระ
						echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
						echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
						echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
						echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
						echo "</tr>";
						
						$ptNum_old_chk = $ptNum_old;
						
						// เงินที่เหลือ
						$hold = $receiveAmount - $ptMinPay_old;
						
						// กำหนดไว้ที่จะต้องชำระต่อไป
						if($hold >= 0)
						{
							// ตรวจสอบว่าการจ่ายครั้งต่อไป ยังเป็นงวดเดิมอยู่หรือไม่
							$sql_table_chk_next = pg_query("select \"receiveDate\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' and \"receiveDate\" >= '$receiveDate' and \"lastReceiveDate\" >= '$lastReceiveDate' and \"receiptID\" <> '$receiptID' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
							$numrows_chk_next = pg_num_rows($sql_table_chk_next);
							while($chk_next = pg_fetch_array($sql_table_chk_next))
							{
								$receiveDate_chk_next = $chk_next["receiveDate"]; // วันที่จะจ่ายครั้งต่อไป
							}
							
							if($numrows_chk_next != 0) // ถ้ามีการจ่ายครั้งต่อไป
							{
								if(substr($receiveDate_chk_next,0,10) > substr($ptDate_old,0,10)) // ถ้าวันที่จ่ายครั้งต่อไป มากกว่าวันดิวนี้
								{
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
								else // ถ้าวันที่จ่ายครั้งต่อไปยังไม่เกินวันดิวนี้
								{
									$hold = 0;
									$money = 1;
								}
							}
							else // ถ้าไม่มีการจ่ายแล้ว
							{
								$hold = $ptMinPay_now;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 1;
							}
						}
						else
						{
							$hold *= -1;
							$ptNum_old = $ptNum_old;
							$ptDate_old = $ptDate_old;
							$ptMinPay_old = $ptMinPay_old;
							$money = 0;
						}
					}
					
					elseif($money == 0) // ถ้าเงินค้างชำระอยู่
					{
						$canpay = $receiveAmount - $hold; // เงินที่เหลือจากการจ่ายงวดเก่า
						
						if($ptNum_old%2==0){
							echo "<tr class=\"odd\" align=\"center\">";
						}else{
							echo "<tr class=\"even\" align=\"center\">";
						}
						
						echo "<td>$ptNum_old</td>"; // งวดที่
						echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
						echo "<td>$receiveDate</td>"; // วันที่ชำระ
						echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
						echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
						echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
						echo "<td></td>"; // ค่าติดตามทวงถาม
						echo "</tr>";
						
						$ptNum_last_chk = $ptNum_old;
							
						if($canpay >= 0) // ถ้ามีเงินพอจ่ายของเก่า
						{
							// ตรวจสอบว่าการจ่ายครั้งต่อไป ยังเป็นงวดเดิมอยู่หรือไม่
							$sql_table_chk_next = pg_query("select \"receiveDate\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' and \"receiveDate\" >= '$receiveDate' and \"lastReceiveDate\" >= '$lastReceiveDate' and \"receiptID\" <> '$receiptID' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
							$numrows_chk_next = pg_num_rows($sql_table_chk_next);
							while($chk_next = pg_fetch_array($sql_table_chk_next))
							{
								$receiveDate_chk_next = $chk_next["receiveDate"]; // วันที่จะจ่ายครั้งต่อไป
							}
							
							if($numrows_chk_next != 0) // ถ้ามีการจ่ายครั้งต่อไป
							{
								if(substr($receiveDate_chk_next,0,10) > substr($ptDate_old,0,10)) // ถ้าวันที่จ่ายครั้งต่อไป มากกว่าวันดิวนี้
								{
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
								else // ถ้าวันที่จ่ายครั้งต่อไปยังไม่เกินวันดิวนี้
								{
									$hold = 0;
									$money = 1;
								}
							}
							else // ถ้าไม่มีการจ่ายแล้ว
							{
								$hold = $ptMinPay_now;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 1;
							}
						}
						else // ถ้าเงินไม่พอจ่ายของเก่าทั้งหมด
						{
							$ptNum_old = $ptNum_old;
							$ptDate_old = $ptDate_old;
							$ptMinPay_old = $ptMinPay_old;
							$money = 0;
							$hold = $hold - $receiveAmount;
						}
					}
				}
				//ถ้าจ่ายเงินก่อนวันครบกำหนด
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				
				
				
				
				
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//ถ้าจ่ายเงินเกินวันครบกำหนด
				
				else //ถ้าจ่ายเงินเกินวันครบกำหนด ($receiveDate > $ptDate_old)
				{
					if($money == 1) // ถ้าไม่มีเงินค้าง
					{
						if($receiveAmount == $hold) // ถ้าจำนวนเงินเท่ากับจำนวนที่ต้องจ่าย
						{
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							// กำหนดไว้ที่จะต้องชำระต่อไป
							$hold = $ptMinPay_now;
							$ptNum_old = $ptNum_now;
							$ptDate_old = $ptDate_now;
							$ptMinPay_old = $ptMinPay_now;
							$money = 1;
						}
						elseif($receiveAmount < $hold) // ถ้าจำนวนเงินที่จ่ายน้อยกว่าจำนวนที่ต้องจ่าย
						{
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							// กำหนดไว้ที่จะต้องชำระต่อไป
							$hold = $ptMinPay_old - $receiveAmount;
							$ptNum_old = $ptNum_old;
							$ptDate_old = $ptDate_old;
							$ptMinPay_old = $ptMinPay_old;
							$money = 0;
						}
						elseif($receiveAmount > $hold) // ถ้าจำนวนเงินที่จ่ายมากกว่าจำนวนที่ต้องจ่าย
						{
							// จ่ายงวดหลักก่อน
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($hold,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							
							// จ่ายงวดต่อไป
							$canpay = $receiveAmount - $hold;
							
							if($canpay <= $ptMinPay_now)
							{
								$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
								
								if($ptNum_now%2==0){
									echo "<tr class=\"odd\" align=\"center\">";
								}else{
									echo "<tr class=\"even\" align=\"center\">";
								}
								
								echo "<td>$ptNum_now</td>"; // งวดที่
								echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
								echo "<td>$receiveDate</td>"; // วันที่ชำระ
								echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
								echo "<td align=\"right\">".number_format($canpay,2)."</td>"; // ยอดที่จ่าย
								echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
								echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
								echo "</tr>";
								
								$ptNum_last_chk = $ptNum_now;
								
								$hold = $ptMinPay_now - $canpay;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 0;
								
								if($hold == 0)
								{
									$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}
									
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
							}
							else
							{ // ถ้ายังเหลือไปงวดต่อไปอีก
							
								if($receiveDate < $ptDate_now) // ถ้าวันที่จ่ายน้อยกว่าวันที่ครบกำหนดชำระ
								{
									$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
									
									if($ptNum_now%2==0){
										echo "<tr class=\"odd\" align=\"center\">";
									}else{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "<td>$ptNum_now</td>"; // งวดที่
									echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
									echo "<td>$receiveDate</td>"; // วันที่ชำระ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
									echo "<td align=\"right\">".number_format($canpay,2)."</td>"; // ยอดที่จ่าย
									echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
									echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
									echo "</tr>";
									
									$ptNum_last_chk = $ptNum_now;
									
									//งวดต่อไป
									$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}
									
									// กำหนดไว้ที่จะต้องชำระต่อไป
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
								else // ถ้าวันที่จ่ายมากกว่าวันครบกำหนดชำระ
								{
									do{
									
									$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
									
									if($ptNum_now%2==0){
										echo "<tr class=\"odd\" align=\"center\">";
									}else{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "<td>$ptNum_now</td>"; // งวดที่
									echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
									echo "<td>$receiveDate</td>"; // วันที่ชำระ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดที่จ่าย
									echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
									echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
									echo "</tr>";
									
									$ptNum_last_chk = $ptNum_now;
									
									$canpay = $canpay - $ptMinPay_now;
									
									//งวดต่อไป
									$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}
									
									}while($canpay > $ptMinPay_now && $receiveDate > $ptDate_now);
									
									
									
									// งวดถัดไป
									$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
									
									if($ptNum_now%2==0){
										echo "<tr class=\"odd\" align=\"center\">";
									}else{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "<td>$ptNum_now</td>"; // งวดที่
									echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
									echo "<td>$receiveDate</td>"; // วันที่ชำระ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
									echo "<td align=\"right\">".number_format($canpay,2)."</td>"; // ยอดที่จ่าย
									echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
									echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
									echo "</tr>";
									
									$ptNum_last_chk = $ptNum_now;
									
									if($canpay < $ptMinPay_now)
									{
										$hold = $ptMinPay_now - $canpay;
										$ptNum_old = $ptNum_now;
										$ptDate_old = $ptDate_now;
										$ptMinPay_old = $ptMinPay_now;
										$money = 0;
									}
									else
									{
										$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
										$numrows_super_next = pg_num_rows($sql_super_next);
										while($resultsupernext=pg_fetch_array($sql_super_next))
										{
											$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
											$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
											$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
										}
										$hold = $ptMinPay_now;
										$ptNum_old = $ptNum_now;
										$ptDate_old = $ptDate_now;
										$ptMinPay_old = $ptMinPay_now;
										$money = 1;
									}
								}
							}
						}
					}
					elseif($money == 0) // ถ้ามีเงินค้าง
					{
						if($receiveAmount == $hold) // ถ้าจำนวนเงินเท่ากับจำนวนที่ต้องจ่าย
						{
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td></td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							// กำหนดไว้ที่จะต้องชำระต่อไป
							$hold = $ptMinPay_now;
							$ptNum_old = $ptNum_now;
							$ptDate_old = $ptDate_now;
							$ptMinPay_old = $ptMinPay_now;
							$money = 1;
						}
						elseif($receiveAmount < $hold) // ถ้าจำนวนเงินที่จ่ายน้อยกว่าจำนวนที่ต้องจ่าย
						{
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td></td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							// กำหนดไว้ที่จะต้องชำระต่อไป
							$hold = $hold - $receiveAmount;
							$ptNum_old = $ptNum_old;
							$ptDate_old = $ptDate_old;
							$ptMinPay_old = $ptMinPay_old;
							$money = 0;
						}
						elseif($receiveAmount > $hold) // ถ้าจำนวนเงินที่จ่ายมากกว่าจำนวนที่ต้องจ่าย
						{
							// จ่ายงวดหลักก่อน
							if($ptNum_old%2==0){
								echo "<tr class=\"odd\" align=\"center\">";
							}else{
								echo "<tr class=\"even\" align=\"center\">";
							}
							
							echo "<td>$ptNum_old</td>"; // งวดที่
							echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
							echo "<td>$receiveDate</td>"; // วันที่ชำระ
							echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
							echo "<td align=\"right\">".number_format($hold,2)."</td>"; // ยอดที่จ่าย
							echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
							echo "<td></td>"; // ค่าติดตามทวงถาม
							echo "</tr>";
							
							$ptNum_last_chk = $ptNum_old;
							
							
							// จ่ายงวดต่อไป
							$canpay = $receiveAmount - $hold;
						
							if($canpay <= $ptMinPay_now)
							{
								$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
								
								if($ptNum_now%2==0){
									echo "<tr class=\"odd\" align=\"center\">";
								}else{
									echo "<tr class=\"even\" align=\"center\">";
								}
								
								echo "<td>$ptNum_now</td>"; // งวดที่
								echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
								echo "<td>$receiveDate</td>"; // วันที่ชำระ
								echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
								echo "<td align=\"right\">".number_format($canpay,2)."</td>"; // ยอดที่จ่าย
								echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
								echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
								echo "</tr>";
								
								$ptNum_last_chk = $ptNum_now;
								
								$hold = $ptMinPay_now - $canpay;
								$ptNum_old = $ptNum_now;
								$ptDate_old = $ptDate_now;
								$ptMinPay_old = $ptMinPay_now;
								$money = 0;
								
								if($hold == 0)
								{
									$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}
									
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
							}
							else
							{ // ถ้ายังเหลือไปงวดต่อไปอีก
							
								if($receiveDate < $ptDate_now) // ถ้าวันที่จ่ายน้อยกว่าวันที่ครบกำหนดชำระ
								{
									$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
									
									if($ptNum_now%2==0){
										echo "<tr class=\"odd\" align=\"center\">";
									}else{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "<td>$ptNum_now</td>"; // งวดที่
									echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
									echo "<td>$receiveDate</td>"; // วันที่ชำระ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
									echo "<td align=\"right\">".number_format($canpay,2)."</td>"; // ยอดที่จ่าย
									echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
									echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
									echo "</tr>";
									
									$ptNum_last_chk = $ptNum_now;
									
									//งวดต่อไป
									$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}
									
									// กำหนดไว้ที่จะต้องชำระต่อไป
									$hold = $ptMinPay_now;
									$ptNum_old = $ptNum_now;
									$ptDate_old = $ptDate_now;
									$ptMinPay_old = $ptMinPay_now;
									$money = 1;
								}
								else // ถ้าวันที่จ่ายมากกว่าวันครบกำหนดชำระ
								{
									do{
									
									$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
									
									if($ptNum_now%2==0){
										echo "<tr class=\"odd\" align=\"center\">";
									}else{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "<td>$ptNum_now</td>"; // งวดที่
									echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
									echo "<td>$receiveDate</td>"; // วันที่ชำระ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดที่จ่าย
									echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
									echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
									echo "</tr>";
									
									$ptNum_last_chk = $ptNum_now;
									
									$canpay = $canpay - $ptMinPay_now;
									
									//งวดต่อไป
									$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}
									
									}while($canpay > $ptMinPay_now && $receiveDate > $ptDate_now);
									
									// จ่ายงวดต่อไปอีก
									/*$sql_super_next = pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
									$numrows_super_next = pg_num_rows($sql_super_next);
									while($resultsupernext=pg_fetch_array($sql_super_next))
									{
										$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
										$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
										$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
									}*/
									
									$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
									
									if($ptNum_now%2==0){
										echo "<tr class=\"odd\" align=\"center\">";
									}else{
										echo "<tr class=\"even\" align=\"center\">";
									}
									
									echo "<td>$ptNum_now</td>"; // งวดที่
									echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
									echo "<td>$receiveDate</td>"; // วันที่ชำระ
									echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
									echo "<td align=\"right\">".number_format($canpay,2)."</td>"; // ยอดที่จ่าย
									echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
									echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
									echo "</tr>";
									
									$ptNum_last_chk = $ptNum_now;
									
									if($canpay < $ptMinPay_now)
									{
										$hold = $ptMinPay_now - $canpay;
										$ptNum_old = $ptNum_now;
										$ptDate_old = $ptDate_now;
										$ptMinPay_old = $ptMinPay_now;
										$money = 0;
									}
									else
									{
										$sql_super_next = pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" > '$ptNum_now' order by \"ptNum\" ASC LIMIT 1 ");
										$numrows_super_next = pg_num_rows($sql_super_next);
										while($resultsupernext=pg_fetch_array($sql_super_next))
										{
											$ptNum_now = $resultsupernext["ptNum"]; // งวดที่ ที่หาได้
											$ptDate_now = $resultsupernext["ptDate"]; // วันครบกำหนด ที่หาได้
											$ptMinPay_now = $resultsupernext["ptMinPay"]; // ยอดจ่ายขั้นต่ำ ที่หาได้
										}
										$hold = $ptMinPay_now;
										$ptNum_old = $ptNum_now;
										$ptDate_old = $ptDate_now;
										$ptMinPay_old = $ptMinPay_now;
										$money = 1;
									}
								}
							}
						}
					}
				}
				
				//ถ้าจ่ายเงินเกินวันครบกำหนด
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				
			}
		}
		elseif($ptNum_old < $conTerm)
		{			
			// หาค่าติดตามทวงถาม
				$sql_collection=pg_query("select \"debtID\", \"debtStatus\", \"typePayAmt\" from public.\"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayID\" = '1003' and \"typePayRefValue\" = '$ptNum_old' and \"debtStatus\" in('1','2') ");
				$numrows_collection = pg_num_rows($sql_collection);
				if($numrows_collection > 0)
				{
					while($resultcollection = pg_fetch_array($sql_collection))
					{
						$debtID = $resultcollection["debtID"]; // เลขที่ค่าติดตามทวงถาม
						$debtStatus = $resultcollection["debtStatus"]; // สถานะว่าจ่ายหรือยัง
						$typePayAmt = $resultcollection["typePayAmt"]; // จำนวนเงิน
					}
					
					if($debtStatus == 1)
					{
						$collection = "ค้างชำระ"; // ค่าติดตามทวงถาม
					}
					elseif($debtStatus == 2)
					{
						$sql_pa = pg_query("select \"receiptID\" from public.\"thcap_v_receipt_otherpay\" where \"debtID\" = '$debtID' ");
						while($resultpa = pg_fetch_array($sql_pa))
						{
							$receiptID_pa = $resultpa["receiptID"]; // เลขที่ใบเสร็จของค่าติดตามทวงถาม
						}
						
						$collection = $typePayAmt." ($receiptID_pa)"; // ค่าติดตามทวงถาม
					}
				}
				else
				{
					$collection = ""; // ค่าติดตามทวงถาม
				}
			// จบการหาค่าติดตามทวงถาม
			
			if($ptNum_last_chk == $ptNum_old){$collection = "";}
			$zz = $zz+1;
			
			//if($i%2==0){
			if($ptNum_old%2==0){
				echo "<tr class=\"odd\" align=\"center\">";
			}else{
				echo "<tr class=\"even\" align=\"center\">";
			}
			
			echo "<td>$ptNum_old</td>"; // งวดที่
			echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
			echo "<td></td>"; // วันที่ชำระ
			echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
			echo "<td align=\"right\"></td>"; // ยอดที่จ่าย
			echo "<td></td>"; // เลขที่ใบเสร็จ
			echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
			
			echo "</tr>";
			
			$ptNum_old = $ptNum_now;
			$ptDate_old = $ptDate_now;
			$ptMinPay_old = $ptMinPay_now;
		}
		
		/*$ptNum_old = $ptNum_now;
		$ptDate_old = $ptDate_now;
		$ptMinPay_old = $ptMinPay_now;*/
		$receiveDate_last = $receiveDate; // วันที่จ่ายล่าสุด
		$lastReceiveDate_last = $lastReceiveDate; // วันที่จ่ายครั้งที่แล้วล่าสุด
		$receiptID_last = $receiptID; // เลขที่ใบเสร็จล่าสุด
		$i++;
	}
	
	// งวดสุดท้าย
	
	$sql_next=pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\" from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' and \"ptNum\" = '$ptNum_old'");
	$numrows_next = pg_num_rows($sql_next);
	while($resultnext=pg_fetch_array($sql_next))
	{
		$ptNum_next = $resultnext["ptNum"];
		$ptDate_next = $resultnext["ptDate"];
		$ptMinPay_next = $resultnext["ptMinPay"];
		
		$sql_table=pg_query("select \"receiveDate\", \"receiveAmount\", \"receiptID\" from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' and \"receiveDate\" >= '$receiveDate_last' and \"lastReceiveDate\" >= '$lastReceiveDate_last' and \"receiptID\" <> '$receiptID_last' order by \"receiveDate\" ASC , \"LeftPrinciple\" DESC LIMIT 1 ");
		$numrows_table = pg_num_rows($sql_table);
		if($numrows_table > 0)
		{
			while($result=pg_fetch_array($sql_table))
			{
				$receiveDate = $result["receiveDate"]; // วันที่ชำระ
				$receiveAmount = $result["receiveAmount"]; // จำนวนเงิน
				$receiptID  = $result["receiptID"]; // เลขที่ใบเสร็จ
				
				// หาค่าติดตามทวงถาม
				$sql_collection=pg_query("select \"debtID\", \"debtStatus\", \"typePayAmt\" from public.\"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayID\" = '1003' and \"typePayRefValue\" = '$ptNum_old' and \"debtStatus\" in('1','2') ");
				$numrows_collection = pg_num_rows($sql_collection);
				if($numrows_collection > 0)
				{
					while($resultcollection = pg_fetch_array($sql_collection))
					{
						$debtID = $resultcollection["debtID"]; // เลขที่ค่าติดตามทวงถาม
						$debtStatus = $resultcollection["debtStatus"]; // สถานะว่าจ่ายหรือยัง
						$typePayAmt = $resultcollection["typePayAmt"]; // จำนวนเงิน
					}
					
					if($debtStatus == 1)
					{
						$collection = "ค้างชำระ"; // ค่าติดตามทวงถาม
					}
					elseif($debtStatus == 2)
					{
						$sql_pa = pg_query("select \"receiptID\" from public.\"thcap_v_receipt_otherpay\" where \"debtID\" = '$debtID' ");
						while($resultpa = pg_fetch_array($sql_pa))
						{
							$receiptID_pa = $resultpa["receiptID"]; // เลขที่ใบเสร็จของค่าติดตามทวงถาม
						}
						
						$collection = $typePayAmt." ($receiptID_pa)"; // ค่าติดตามทวงถาม
					}
				}
				else
				{
					$collection = ""; // ค่าติดตามทวงถาม
				}
				// จบการหาค่าติดตามทวงถาม
				
				//if($i%2==0){
				if($ptNum_old%2==0){
					echo "<tr class=\"odd\" align=\"center\">";
				}else{
					echo "<tr class=\"even\" align=\"center\">";
				}
				
				echo "<td>$ptNum_old</td>"; // งวดที่
				echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
				echo "<td>$receiveDate</td>"; // วันที่ชำระ
				echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
				echo "<td align=\"right\">".number_format($receiveAmount,2)."</td>"; // ยอดที่จ่าย
				echo "<td>$receiptID</td>"; // เลขที่ใบเสร็จ
				echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
				
				echo "</tr>";
			}
		}
		else
		{
			// หาค่าติดตามทวงถาม
				$sql_collection=pg_query("select \"debtID\", \"debtStatus\", \"typePayAmt\" from public.\"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayID\" = '1003' and \"typePayRefValue\" = '$ptNum_old' and \"debtStatus\" in('1','2') ");
				$numrows_collection = pg_num_rows($sql_collection);
				if($numrows_collection > 0)
				{
					while($resultcollection = pg_fetch_array($sql_collection))
					{
						$debtID = $resultcollection["debtID"]; // เลขที่ค่าติดตามทวงถาม
						$debtStatus = $resultcollection["debtStatus"]; // สถานะว่าจ่ายหรือยัง
						$typePayAmt = $resultcollection["typePayAmt"]; // จำนวนเงิน
					}
					
					if($debtStatus == 1)
					{
						$collection = "ค้างชำระ"; // ค่าติดตามทวงถาม
					}
					elseif($debtStatus == 2)
					{
						$sql_pa = pg_query("select \"receiptID\" from public.\"thcap_v_receipt_otherpay\" where \"debtID\" = '$debtID' ");
						while($resultpa = pg_fetch_array($sql_pa))
						{
							$receiptID_pa = $resultpa["receiptID"]; // เลขที่ใบเสร็จของค่าติดตามทวงถาม
						}
						
						$collection = $typePayAmt." ($receiptID_pa)"; // ค่าติดตามทวงถาม
					}
				}
				else
				{
					$collection = ""; // ค่าติดตามทวงถาม
				}
			// จบการหาค่าติดตามทวงถาม
				
			//if($i%2==0){
			if($ptNum_old%2==0){
				echo "<tr class=\"odd\" align=\"center\">";
			}else{
				echo "<tr class=\"even\" align=\"center\">";
			}
			
			echo "<td>$ptNum_old</td>"; // งวดที่
			echo "<td>$ptDate_old</td>"; // วันที่ครบกำหนด
			echo "<td></td>"; // วันที่ชำระ
			echo "<td align=\"right\">".number_format($ptMinPay_old,2)."</td>"; // ยอดจ่ายขั้นต่ำ
			echo "<td align=\"right\"></td>"; // ยอดที่จ่าย
			echo "<td></td>"; // เลขที่ใบเสร็จ
			echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
			
			echo "</tr>";
		}
	}
?>
</table>
</center>
</body>
</html>