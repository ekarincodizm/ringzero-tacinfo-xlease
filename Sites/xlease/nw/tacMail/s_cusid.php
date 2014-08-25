<?php
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$term = $_GET['term'];
//$term=trim(iconv('WINDOWS-874','Thai_CI_AS',$term));
$qry_name=mssql_query("select a.CusID,b.RadioID,(replace(a.PreName,' ','')+replace(a.Name,' ','')+' '+replace(a.SurName,' ','')) as fullname from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where convert(char(10),a.Name) COLLATE thai_bin like '%$term%' or a.CusID like '%$term%' or b.RadioID like '%$term%' group by a.CusID,b.RadioID,a.PreName,a.Name,a.SurName");

/*$qry_name=mssql_query("select a.CusID,b.RadioID,{fn CONCAT(replace(a.PreName,' ',''), replace(a.Name,' ','')) } as fullname1,(replace(a.PreName,' ','')+replace(a.Name,' ','')+'  '+replace(a.SurName,' ','')) as fullname from TacCusDtl as a
left join RadioDoc as b on a.CusID=b.CusID 
where convert(char,a.Name) like '%$term%' or a.CusID like '%$term%' or b.RadioID like '%$term%' group by a.CusID,b.RadioID,a.PreName,a.Name,a.SurName");
*/
$numrows = mssql_num_rows($qry_name);
while($res_name=mssql_fetch_array($qry_name)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_name["RadioID"])); if(empty($RadioID)) $RadioID="ไม่พบข้อมูล";
    $fullname=trim(iconv('WINDOWS-874','UTF-8',$res_name["fullname"]));
    
    $dt['value'] = $CusID;
    $dt['label'] = "{$CusID} : {$fullname}, {$RadioID}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
