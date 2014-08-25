<?php
include("../../config/config.php");
$debtID = $_GET['debtID'];
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<BODY BGCOLOR="#D5EFFD"> 

<div class="ui-widget" align="left">

<?php
if($debtID != "")
{
	//ค้นหาข้อมูลใบเสร็จ
	$qry_con=pg_query("select \"thcap_v_receipt_otherpay\".\"receiptID\" , \"thcap_temp_receipt_channel\".\"receiveDate\" , \"thcap_v_receipt_otherpay\".\"debtAmt\"
							, \"thcap_v_receipt_details\".\"userFullname\" , \"thcap_temp_receipt_channel\".\"byChannel\"
						from public.\"thcap_v_receipt_otherpay\" , public.\"thcap_temp_receipt_channel\" , public.\"thcap_v_receipt_details\"
						WHERE \"thcap_v_receipt_otherpay\".\"receiptID\" = \"thcap_temp_receipt_channel\".\"receiptID\"
								and \"thcap_temp_receipt_channel\".\"receiptID\" = \"thcap_v_receipt_details\".\"receiptID\"
								and \"thcap_temp_receipt_channel\".\"byChannel\" <> '999'
								and \"thcap_v_receipt_otherpay\".\"debtID\" = '$debtID' ");
	$numrec=pg_num_rows($qry_con);
	
	if($numrec > 0)
	{
		while($result=pg_fetch_array($qry_con))
		{	
			$receiptID=trim($result["receiptID"]);
			$receiveDate=trim($result["receiveDate"]);
			$debtAmt=trim($result["debtAmt"]);
			$userFullname=trim($result["userFullname"]);
			$byChannel=trim($result["byChannel"]);
				
			if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$txtby="ไม่ระบุ";}
			else{
				//นำไปค้นหาในตาราง BankInt
				$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
				$ressearch=pg_fetch_array($qrysearch);
				list($BAccount,$BName)=$ressearch;
				$txtby="$BAccount-$BName";
			}
		
			$qry_sum=pg_query("select sum(\"debtAmt\") as \"sumAmt\"
						from public.\"thcap_v_receipt_otherpay\"
						WHERE \"receiptID\" = '$receiptID' ");
			while($resultsum=pg_fetch_array($qry_sum))
			{	
				$sumAmt=trim($resultsum["sumAmt"]);
			}
		}

		echo "<center><h2>ข้อมูลใบเสร็จ</h2></center>
		<table widtd=\"850\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" align=\"center\" class=\"sort-table\">
		<tdead>
		<tr>
			<td align=\"right\"><b>เลขที่ใบเสร็จ :</b></td><td align=\"left\">$receiptID</td>
		</tr>
		<tr>
			<td align=\"right\"><b>วันที่รับชำระ :</b></td><td align=\"left\">$receiveDate</td>
		</tr>
		<tr>
			<td align=\"right\"><b>จำนวนเงินรวมของใบเสร็จ :</b></td><td align=\"left\">$sumAmt บาท</td>
		</tr>
		<tr>
			<td align=\"right\"><b>ผู้รับชำระ :</b></td><td align=\"left\">$userFullname</td>
		</tr>
		<tr>
			<td align=\"right\"><b>ช่องทางการรับชำระ :</b></td><td align=\"left\">$txtby</td>
		</tr>
		</tdead>
		";
		
		echo "</table>";
	}
	else
	{ //กรณีไม่พบข้อมูล
		echo "<center><h2>-ไม่พบข้อมูล-</h2></center>";
	}
}
else
{ //กรณีไม่กรอกคำค้น
	echo "<center><h2>-ไม่พบข้อมูล-</h2></center>";
}?>

</BODY>
