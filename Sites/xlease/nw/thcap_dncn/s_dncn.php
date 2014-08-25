<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);

//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("SELECT \"dcNoteID\",a.\"contractID\" as \"contractID\",\"CusState\",\"thcap_fullname\",
	CASE WHEN \"N_IDCARD\" is null THEN \"N_CARDREF\" ELSE \"N_IDCARD\" END idcard
	FROM account.thcap_dncn_payback a
	left join \"vthcap_ContactCus_detail\" b on a.\"contractID\"=b.\"contractID\" 
	WHERE (\"dcNoteID\" LIKE '%$term%' OR \"thcap_fullname\" LIKE '%$term%' OR a.\"contractID\" LIKE '%$term%'
	OR \"N_IDCARD\" LIKE '%$term%' OR \"N_CARDREF\" LIKE '%$term%') and \"dcNoteStatus\"='1' 
	union
	SELECT \"dcNoteID\",c.\"contractID\" as \"contractID\",\"CusState\",\"thcap_fullname\",
	CASE WHEN \"N_IDCARD\" is null THEN \"N_CARDREF\" ELSE \"N_IDCARD\" END idcard
	FROM account.thcap_dncn_discount c
	left join \"vthcap_ContactCus_detail\" d on c.\"contractID\"=d.\"contractID\" 
	WHERE (\"dcNoteID\" LIKE '%$term%' OR \"thcap_fullname\" LIKE '%$term%' OR c.\"contractID\" LIKE '%$term%'
	OR \"N_IDCARD\" LIKE '%$term%' OR \"N_CARDREF\" LIKE '%$term%') and \"dcNoteStatus\"='1' 
	");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		$dcNoteID=$res["dcNoteID"]; // รหัส CreditNote
		$contractID=$res["contractID"]; // เลขที่สัญญา
		$thcap_fullname=$res["thcap_fullname"];
		$idcard=$res["idcard"];
		$CusState=$res["CusState"];
		
		if($CusState==0){
			$txtstate="(ผู้กู้หลัก)";
		}else if($CusState==1){
			$txtstate="(ผู้กู้ร่วม)";
		}else if($CusState==2){
			$txtstate="(ผู้ค้ำ)";
		}
		
		$name = str_replace("'", "\'"," ".$dcNoteID."#เลขที่สัญญา".$contractID."#ชื่อลูกค้า: ".$thcap_fullname."$txtstate #เลขบัตร: ".$idcard);
		$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");		
		
		$dt['value'] = $dcNoteID;
		$dt['label'] = $display_name;
		$matches[] = $dt;
					
	}				
}
	
if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>