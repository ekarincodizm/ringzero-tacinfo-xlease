<?php
include("../../config/config.php");
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

pg_query("BEGIN");
$status = 0;

$sql="select * from \"thcap_v_receipt_details\"";
//$sql="select distinct \"receiptID\" from \"thcap_v_receipt_otherpay\"";
$dbquery=pg_query($sql);
$rows5=pg_num_rows($dbquery);
if($rows5>0)
{
	while($result=pg_fetch_assoc($dbquery))
	{
		$id=$result['receiptID'];
		
		$sql1="select * from \"thcap_v_receipt_otherpay\" where \"receiptID\"='$id' and \"vatAmt\"<>'0'";
		$dbquery1=pg_query($sql1);
		if(pg_num_rows($dbquery1) == 0)
		{
			continue;
		}
		
		$doerID=$result['doerID'];
		if($doerID=="")
		{
			$doerID="null";
		}
		else
		{
			$doerID="'".$doerID."'";
		}
		$doerStamp=$result['doerStamp'];
		if($doerStamp=="")
		{
			$doerStamp="null";
		}
		else
		{
			$doerStamp="'".$doerStamp."'";
		}
		$backAmt=$result['backAmt'];
		if($backAmt=="")
		{
			$backAmt="null";
		}
		else
		{
			$backAmt="'".$backAmt."'";
		}
		$backDueDate=$result['backDueDate'];
		if($backDueDate=="")
		{
			$backDueDate="null";
		}
		else
		{
			$backDueDate="'".$backDueDate."'";
		}
		$nextDueAmt=$result['nextDueAmt'];
		if($nextDueAmt=="")
		{
			$nextDueAmt="null";
		}
		else
		{
			$backAmt="'".$nextDueAmt."'";
		}
		$nextDueDate=$result['nextDueDate'];
		if($nextDueDate=="")
		{
			$nextDueDate="null";
		}
		else
		{
			$nextDueDate="'".$nextDueDate."'";
		}
		$cusFullname=$result['cusFullname'];
		if($cusFullname=="")
		{
			$cusFullname="null";
		}
		else
		{
			$cusFullname="'".$cusFullname."'";
		}
		$cusCoFullname=$result['cusCoFullname'];
		if($cusCoFullname=="")
		{
			$cusCoFullname="null";
		}
		else
		{
			$cusCoFullname="'".$cusCoFullname."'";
		}
		$userFullname=$result['userFullname'];
		if($userFullname=="")
		{
			$userFullname="null";
		}
		else
		{
			$userFullname="'".$userFullname."'";
		}
		$addrFull=$result['addrFull'];
		if($addrFull=="")
		{
			$addrFull="null";
		}
		else
		{
			$addrFull="'".$addrFull."'";
		}
		
		$sql_max_date="select max(\"receiveDate\") as \"receiveDate\" from \"thcap_temp_receipt_channel\" where \"receiptID\"='$id'";
		$query_max_date=pg_query($sql_max_date);
		
		while($rs=pg_fetch_assoc($query_max_date))
		{
			$max_date=$rs['receiveDate'];
		}
		
		if($max_date=="")
		{
			$max_date="null";
		}
		else
		{
			$max_date="'".$max_date."'";
		}
		
		//gen running number
		$type="V".substr($id,6,2);
		$receiveDate="20".substr($id,0,2)."-".substr($id,2,2)."-".substr($id,4,2);
		$runningNumber=00000;

		//หาจำนวนใบเสร็จในแต่ละวัน
		$sql_check_date = pg_query("select * from \"thcap_running_receipt\" where \"receiptDate\" = '$receiveDate' and \"receiptType\" = '$type'");
		$numrowcheck = pg_num_rows($sql_check_date);
		if($numrowcheck > 0) // ถ้ามีข้อมูลอยู่แล้ว
		{
			while($resultDate=pg_fetch_array($sql_check_date))
			{
				$maxnumber = $resultDate["receiptRunning"]; // เลขล่าสุด
			}
			$maxnumber++; // เลขที่จะนำไปใช้ต่อไป	
			
			$up_sqldate = "update public.\"thcap_running_receipt\" set \"receiptRunning\" = '$maxnumber' where \"receiptDate\" = '$receiveDate' and \"receiptType\" = '$type' ";
			if($resultUpdate = pg_query($up_sqldate))
			{}
			else
			{
				$status++;
			}
		}
		else // ถ้ายังไม่มีข้อมูล
		{
			$maxnumber = 1;
			
			$in_sql_date = "insert into public.\"thcap_running_receipt\" (\"receiptDate\",\"receiptType\",\"receiptRunning\") values ('$receiveDate','$type','$maxnumber')";
			if($resultUpdate = pg_query($in_sql_date))
			{}
			else
			{
				$status++;
			}
		}
		//จบการหาจำนวนใบเสร็จ
		//echo $taxinvoiceID."<br>";
		//$sql1="select * from thcap_v_receipt_otherpay where receiptID=$id VatAmt<>0";
		switch(strlen($maxnumber)){
			case 1:
				$runningNumber="0000".$maxnumber;
				break;
			case 2:
				$runningNumber="000".$maxnumber;
				break;
			case 3:
				$runningNumber="00".$maxnumber;
				break;
			case 4;
				$runningNumber="0".$maxnumber;
				break;
			case 5;
				$runningNumber=$maxnumber;
				break;
		}
		
		$taxinvoiceID=substr($id,0,6).$type."-".$runningNumber;
		
		$sql1="select * from \"thcap_v_receipt_otherpay\" where \"receiptID\"='$id' and \"vatAmt\"<>'0'";
		$dbquery1=pg_query($sql1);
		while($rs=pg_fetch_assoc($dbquery1))
		{
			$debtID=$rs['debtID'];
			$netAmt=$rs['netAmt'];
			$vatAmt=$rs['vatAmt'];
			$debtAmt=$rs['debtAmt'];
			$whtAmt=$rs['whtAmt'];
			
			//จบการหาจำนวนใบเสร็จ
			$sql3="insert into thcap_temp_taxinvoice_otherpay(\"taxinvoiceID\",\"debtID\",\"netAmt\",\"vatAmt\",\"debtAmt\",\"whtAmt\") values('$taxinvoiceID','$debtID','$netAmt','$vatAmt','$debtAmt','$whtAmt')";
			if(pg_query($sql3))
			{}
			else
			{
				$status++;
			}
		}
		$sql2="insert into thcap_temp_taxinvoice_details(\"taxinvoiceID\",\"doerID\",\"doerStamp\",\"backAmt\",\"backDueDate\",\"nextDueAmt\",\"nextDueDate\",\"cusFullname\",\"cusCoFullname\",\"userFullname\",\"addrFull\",\"taxpointDate\") values('$taxinvoiceID',$doerID,$doerStamp,$backAmt,$backDueDate,$nextDueAmt,$nextDueDate,$cusFullname,$cusCoFullname,$userFullname,$addrFull,$max_date)";
		if(pg_query($sql2))
		{}
		else
		{
			$status++;
		}
	}
	if($status == 0)
	{
		//pg_query("ROLLBACK");
		pg_query("COMMIT");
		echo "<center><h2>กำหนดเลขที่ใบเสร็จสำเร็จ</h2></center>";
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h2>ผิดพลาด!! กรุณาติดต่อเจ้าหน้าที่ Programmer เพื่อดำเนินการแก้ไขข้อผิดพลาด</h2></center>";
	}
}
else
{
echo "<center><h2>ไม่พบรายการที่ยังไม่มีเลขที่ใบเสร็จ</h2></center>";
}
?>