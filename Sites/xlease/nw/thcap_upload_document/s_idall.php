<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$a1[] = "";
//ค้นหาตาม เลขที่สัญญา
$sql=pg_query("select distinct \"contractID\" from thcap_upload_document where \"contractID\" like '%$term%' and \"Approved\"<>'0'");
$numrows = pg_num_rows($sql);
if($numrows > 0){
	while($res=pg_fetch_array($sql))
	{
		$a1[] = $res["contractID"]; // เลขที่สัญญา
		$t1=$res["contractID"]; // เลขที่สัญญา
						
			$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$t1' ");
			while($res_chkStatus = pg_fetch_array($qry_chkStatus))
			{
				$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
			}
						
						if($conStatus == "11")
						{
							$txtLable = "<font color=\"#CCCCCC\">เลขที่สัญญา:$t1(ปิดบัญชีแล้ว)</font>";
						}
						else
						{
							$txtLable = "<font color=\"#000000\">เลขที่สัญญา:$t1</font>";
						}
						
		$dt['value'] = $t1;
		$dt['label'] = $txtLable;
		$matches[] = $dt;
					
	}				
}
if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}	


$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>