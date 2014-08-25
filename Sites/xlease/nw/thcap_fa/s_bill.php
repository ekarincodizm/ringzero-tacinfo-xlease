<?php
//ดึงบิลทุกใบยกเว้นใบที่รออนุมัติอยู่
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

$sql = "SELECT distinct a.\"prebillIDMaster\" as \"prebillID\",
		(select b.\"dateInvoice\" from vthcap_fa_prebill_temp b where b.\"prebillIDMaster\" = a.\"prebillIDMaster\"
		and b.\"edittime\" = (select max(c.\"edittime\") from vthcap_fa_prebill_temp c where c.\"prebillIDMaster\" = a.\"prebillIDMaster\")
		and b.\"dateInvoice\" is not null limit 1) as \"dateInvoice\",

		(select d.\"numberInvoice\" from vthcap_fa_prebill_temp d where d.\"prebillIDMaster\" = a.\"prebillIDMaster\"
		and d.\"edittime\" = (select max(e.\"edittime\") from vthcap_fa_prebill_temp e where e.\"prebillIDMaster\" = a.\"prebillIDMaster\")
		and d.\"numberInvoice\" is not null limit 1) as \"numberInvoice\",

		(select f.\"userSalebillName\" from vthcap_fa_prebill_temp f where f.\"prebillIDMaster\" = a.\"prebillIDMaster\"
		and f.\"edittime\" = (select max(g.\"edittime\") from vthcap_fa_prebill_temp g where g.\"prebillIDMaster\" = a.\"prebillIDMaster\")
		and f.\"userSalebillName\" is not null limit 1) as \"userSalebillName\",

		(select h.\"userDebtorName\" from vthcap_fa_prebill_temp h where h.\"prebillIDMaster\" = a.\"prebillIDMaster\"
		and h.\"edittime\" = (select max(i.\"edittime\") from vthcap_fa_prebill_temp i where i.\"prebillIDMaster\" = a.\"prebillIDMaster\")
		and h.\"userDebtorName\" is not null limit 1) as \"userDebtorName\",

		(select j.\"totalTaxInvoice\" from vthcap_fa_prebill_temp j where j.\"prebillIDMaster\" = a.\"prebillIDMaster\"
		and j.\"edittime\" = (select max(k.\"edittime\") from vthcap_fa_prebill_temp k where k.\"prebillIDMaster\" = a.\"prebillIDMaster\")
		and j.\"totalTaxInvoice\" is not null limit 1) as \"totalTaxInvoice\"

		FROM vthcap_fa_prebill_temp a
		WHERE  \"statusApp\"<>'2' AND (a.\"userSalebillName\" LIKE '%$term%' OR a.\"userDebtorName\" LIKE '%$term%' OR a.\"dateInvoice\"::text LIKE '%$term%' OR a.\"numberInvoice\" LIKE '%$term%')
		ORDER BY a.\"prebillIDMaster\" ";

$results=pg_query($sql);						 
$nrows=pg_num_rows($results);

while($res = pg_fetch_array( $results ))
{
	$prebillID = $res["prebillID"]; // รหัสบิล
	$dateInvoice = $res["dateInvoice"]; // วันที่ใบแจ้งหนี้
	$numberInvoice = $res["numberInvoice"]; // เลขที่ใบแจ้งหนี้
	$userDebName = $res["userDebtorName"]; // ชื่อลูกหนี้
	$userSaleName = $res["userSalebillName"]; // ชื่อผู้ขาย
	$totalTaxInvoice = number_format($res["totalTaxInvoice"],2); // จำนวนเงินในบิล
			
	$display_name = "$prebillID / เลขที่บิล: $numberInvoice / จำนวนเงินทั้งบิล:  ".$totalTaxInvoice." บาท /ผู้ขาย: $userSaleName/ลูกหนี้: $userDebName / วันที่ใบแจ้งหนี้: $dateInvoice";
    
	$dt['value'] = $prebillID."#".$numberInvoice."#".$totalTaxInvoice;
	$dt['label'] = $display_name;
    $matches[] = $dt;
}

if($nrows==0){
    $matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>