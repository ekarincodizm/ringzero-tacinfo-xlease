<?php
include("../../config/config.php");
$term = pg_escape_string($_GET['term']);
$term =str_replace("+","",$term );

	$sql=pg_query("SELECT af_fmid,af_fmname FROM account.\"all_accFormula\" where \"af_useformula\" ='0' and \"af_fmname\" like '%$term%' ORDER BY af_fmid ASC ");
	$numrows = pg_num_rows($sql);
		if($numrows > 0){
			while($res=pg_fetch_array($sql))
			{
			$af_fmid = $res["af_fmid"]; 
			$af_fmname = $res["af_fmname"];
			$formula['value'] = '+'.trim($af_fmid).'+'.''.trim($af_fmname);
			$formula['label'] = '+'.trim($af_fmid).'+'.''.trim($af_fmname);
			$matches[] = $formula;					
			}				
		} 
if($matches==""){
	$matches[] = "ไม่พบข้อมูล";
}
$matches = array_slice($matches, 0, 5000);
print json_encode($matches);
?>