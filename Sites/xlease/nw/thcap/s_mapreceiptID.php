<?php
include("../../config/config.php");
$term = $_GET['term'];
$contractID = $_GET['contractID']; //เลขที่สัญญาของเช็คที่ต้องการ map

//ค้นหาเฉพาะใบเสร็จที่มีเลขที่สัญญาเดียวกับเช็คที่ต้องการ map และเป็นใบเสร็จที่เกิดจากเงินโอน
$qry_name=pg_query("SELECT distinct(\"receiptID\"),\"contractID\",date(\"receiveDate\") as \"receiveDate\"
FROM thcap_v_receipt_otherpay 
where \"receiptID\" LIKE '%$term%' and \"contractID\"='$contractID' 
and \"byChannelRef\" in (select \"revTranID\" from finance.thcap_receive_transfer) order by \"receiptID\"");
$numrows = pg_num_rows($qry_name);
if($numrows > 0){
	while($res=pg_fetch_array($qry_name)){
			$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
			$contractID=$res["contractID"]; // เลขที่สัญญา
			$receiveDate=$res["receiveDate"]; // วันที่รับชำระเงิน
			
			$name = str_replace("'", "\'"," เลขที่ใบเสร็จ ".$receiptID.""." /เลขที่สัญญา ".$contractID.""." /วันที่รับชำระ ".$receiveDate);
			$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");
									
			$dt['value'] = $receiptID;
			$dt['label'] = $display_name;
			$matches[] = $dt;
				
	}
}

if($matches==""){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
