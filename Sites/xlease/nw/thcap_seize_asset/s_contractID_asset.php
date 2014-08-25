<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$a1[] = "";
$Criteria = pg_escape_string($_GET["criteria"]);

//Query จากตารางที่ insert ข้อมูล โดยใช้ฟังก์ชัน 
if($Criteria=="Default" or $Criteria=="")
{
	$sql=pg_query("select \"TIS_Default\" from thcap_installment_search where \"TIS_Default\" like '%$term%'");
	$numrows = pg_num_rows($sql);
	if($numrows > 0)
	{
		while($res=pg_fetch_array($sql))
		{
			$TIS_Default = $res["TIS_Default"]; // เลขที่สัญญา
			$str=explode("#",$TIS_Default);
			list($string,$contractID)=explode(":",$str[1]);
			
			// ตรวจสอบว่า เลขที่สัญญานั้นๆมีสินทรัพย์หรือไม่
			$qry_chk = pg_query("select distinct \"contractID\" from public.\"thcap_contract_asset\" where \"contractID\" like '%".trim($contractID)."%'
								and \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_asset_biz_detail\" where \"as_status_id\" in('2','3','4')) ");
			$row_chk = pg_num_rows($qry_chk);
			if($row_chk > 0)
			{
				$dt['value'] = trim($contractID);
				$dt['label'] = trim($TIS_Default);
				$matches[] = $dt;
			}
		}
	}
}
elseif($Criteria=="Asset10")
{
	$sql=pg_query("select \"TIS_Asset10\" from thcap_installment_search where \"TIS_Asset10\" like '%$term%'");
	$numrows = pg_num_rows($sql);
	if($numrows > 0)
	{
		while($res=pg_fetch_array($sql))
		{
			$TIS_Asset10 = $res["TIS_Asset10"]; // เลขที่สัญญา
			$str=explode("#",$TIS_Asset10);
			list($string,$contractID)=explode(":",$str[1]);
			
			// ตรวจสอบว่า เลขที่สัญญานั้นๆมีสินทรัพย์หรือไม่
			$qry_chk = pg_query("select distinct \"contractID\" from public.\"thcap_contract_asset\" where \"contractID\" like '%".trim($contractID)."%'
								and \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_asset_biz_detail\" where \"as_status_id\" in('2','3','4')) ");
			$row_chk = pg_num_rows($qry_chk);
			if($row_chk > 0)
			{
				$dt['value'] = trim($contractID);
				$dt['label'] = trim($TIS_Asset10);
				$matches[] = $dt;
			}
		}
	} 
}
elseif($Criteria=="PrimaryCus")
{
	$sql=pg_query("select \"TIS_PrimaryCus\" from thcap_installment_search where \"TIS_PrimaryCus\" like '%$term%'");
	$numrows = pg_num_rows($sql);
	if($numrows > 0)
	{
		while($res=pg_fetch_array($sql))
		{
			$TIS_PrimaryCus = $res["TIS_PrimaryCus"]; // เลขที่สัญญา
			$str=explode("#",$TIS_PrimaryCus);
			list($string,$contractID)=explode(":",$str[1]);
			
			// ตรวจสอบว่า เลขที่สัญญานั้นๆมีสินทรัพย์หรือไม่
			$qry_chk = pg_query("select distinct \"contractID\" from public.\"thcap_contract_asset\" where \"contractID\" like '%".trim($contractID)."%'
								and \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_asset_biz_detail\" where \"as_status_id\" in('2','3','4')) ");
			$row_chk = pg_num_rows($qry_chk);
			if($row_chk > 0)
			{
				$dt['value'] = trim($contractID);
				$dt['label'] = trim($TIS_PrimaryCus);
				$matches[] = $dt;
			}
		}
	}
}

if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>