<?php
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php $appvID = $_SESSION["av_iduser"];
$appvStamp = nowDateTime();
pg_query("BEGIN WORK");
$contractAutoID = pg_escape_string($_POST['contractAutoID']);
$note = pg_escape_string($_POST['note']);
//$Approved=$_POST['Approved'];
if(isset($_POST["appv"])){
	$Approved=1;//อนุมัติ
}else if(isset($_POST["unappv"])){
	$Approved=0;//ไม่อนุมัติ
}
/*if($Approved=="true"){$Approved=1;}
else {$Approved=0;}*/
$status="yes";
$query_main = pg_query("select * from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
$numrows=pg_num_rows($query_main);
if($numrows>0)
{   $result = pg_fetch_array($query_main);
	$autoID=$result["autoID"]; // ลำดับเลขที่สัญญา
	$contractID = $result["contractID"]; // เลขที่สัญญา
	$appvidcheck=$result["doerUser"]; 
	//ตรวจสอบว่า ผู้ใช้ เคยตรวจสอบหรือไม่ โดยจะตรวจสอบได้ไม่เกิน 2 ครั้ง
	$query_appvid= pg_query("select \"appvID\" from public.\"thcap_contract_check_temp\" where \"appvID\" = '$appvID' and \"ID\"='$autoID'");
	$numappvID=pg_num_rows($query_appvid);
	if($numappvID>=2)
	{ 
		$status="noAppv"; 
	}
	else{
	
		// ตรวจสอบว่ามีสินทรัพย์ที่ผูกกับสัญญาหรือไม่
		$qry_chk_asset = pg_query("select \"assetDetailID\" from \"thcap_contract_asset_temp\" where \"contractID\" = '$contractID' and \"Approved\" is null ");
		$row_chk_asset = pg_num_rows($qry_chk_asset);
		if($row_chk_asset > 0)
		{
			// รหัสสินทรัพย์ย่อย
			$assetDetailID = pg_fetch_result($qry_chk_asset,0);
			
			// หารหัสใบเสร็จหลัก
			$qry_main_asset = pg_query("select \"assetID\" from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
			$assetID = pg_fetch_result($qry_main_asset,0);
			
			// ตรวจสอบว่าถูกยกเลิกแล้วหรือยัง
			$qry_chk_asset = pg_query("select \"ActiveStatus\" from \"thcap_asset_biz\" where \"assetID\" = '$assetID' ");
			$ActiveStatus = pg_fetch_result($qry_chk_asset,0);
			if($ActiveStatus == "0")
			{ 
				$status = "assetCancel"; 
			}
		}
		
		//level ของ คนผูกสัญญา
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$appvidcheck' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];
		//level ของ คนตรวจผูกสัญญา
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevelcheck=$leveluser["emplevel"];
		
		if(($emplevelcheck<=1)){//($emplevel<=1)and
			$in_sql="insert into \"thcap_contract_check_temp\" (\"ID\",\"contractID\",\"note\",\"appvID\",\"appvStamp\",\"Approved\") 
			values  ('$autoID','$contractID','$note','$appvID','$appvStamp',$Approved)";
			if($resup=pg_query($in_sql)){}
			else{$status="no";}
		}else{
			if($appvidcheck==$appvID){$status="noinsert";}
			else{
				$in_sql="insert into \"thcap_contract_check_temp\" (\"ID\",\"contractID\",\"note\",\"appvID\",\"appvStamp\",\"Approved\") 
				values  ('$autoID','$contractID','$note','$appvID','$appvStamp',$Approved)";
				if($resup=pg_query($in_sql)){}
				else{$status="no";}
			}		
		}
		//$in_sql="insert into \"thcap_contract_check_temp\" (\"ID\",\"contractID\",\"note\",\"appvID\",\"appvStamp\",\"Approved\") 
		//values  ('$autoID','$contractID','$note','$appvID','$appvStamp',$Approved)";
		//if($resup=pg_query($in_sql)){}
		//else{$status="no";}
	}
}
else{$status="no";}
if($status=="no"){ 
	pg_query("ROLLBACK");    
    //echo 1;
	$script= '<script language=javascript>';
	$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้ กรุณาตรวจสอบใหม่ ภายหลัง!');
				window.opener.location.reload();
				window.close();";
	$script.= '</script>';
	echo $script;

}else if($status=="yes"){ 
	pg_query("COMMIT");	
    //echo 2;
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกรายการเรียบร้อย');
				window.opener.location.reload();
				window.close();";
	$script.= '</script>';
	echo $script;
}else if($status=="noinsert"){
	pg_query("ROLLBACK");    
    //echo 4;
	$script= '<script language=javascript>';
	$script.= " alert('ไม่สามารถบันทึกได้  เนื่องจากผู้ที่ผูกสัญญาไม่สามารถทำการตรวจสอบสัญญาได้');
				window.opener.location.reload();
				window.close();";
	$script.= '</script>';
	echo $script;
}else if($status=="assetCancel"){
	pg_query("ROLLBACK");    
    //echo 4;
	$script= '<script language=javascript>';
	$script.= " alert('ไม่สามารถบันทึกได้  เนื่องจากมีสินทรัพย์ถูกยกเลิก');
				window.opener.location.reload();
				window.close();";
	$script.= '</script>';
	echo $script;
}
else{
pg_query("ROLLBACK");    
$script= '<script language=javascript>';
	$script.= " alert('ไม่สามารถบันทึกได้ เนื่องจากผู้ตรวจสอบ 1 คนสามารถตรวจสอบได้สูงสุดเพียง 2 ครั้งเท่านั้น');
				window.opener.location.reload();
				window.close();";
	$script.= '</script>';
	echo $script;
//echo 3;
}
?>
