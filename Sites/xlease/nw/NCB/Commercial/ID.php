<?php
function ID_head($day , $month , $year) // ชื่อไฟล์ของ ID
{
	while(strlen($day) < 2)
	{
		$day = "0".$day;
	}
	
	while(strlen($month) < 2)
	{
		$month = "0".$month;
	}
	
	$textreturn = "ID-1103-$year$month$day-1.csv";
	
	return $textreturn;
}

function ID_text($CorporationArray2D) // ข้อความใน ID
{
	// หาจำนวนนิติบุคคลที่จะนำส่ง NCB ในครั้งนี้
	$qry_numCorp = pg_query("select ta_array_count('$CorporationArray2D')");
	$numCorp = pg_fetch_result($qry_numCorp,0);
	
	// กำหนดค่าให้ตัวแปร array
	$qry_array_list_unique = pg_query("select ta_array_list_unique('$CorporationArray2D') as \"array_list\" ");
	while($res_array_list = pg_fetch_array($qry_array_list_unique))
	{
		$a = $res_array_list["array_list"];
		
		// กำหนดค่า
		$qry_array_get = pg_query("select ta_array_get('$CorporationArray2D', '$a') as \"array_get\" ");
		$corpID[$a] = pg_fetch_result($qry_array_get,0);
	}
	
	$textreturn = "";
	
	for($i=1;$i<=$numCorp;$i++)
	{
		$query = pg_query("select * from public.\"th_corp\" where \"corpID\" = '$corpID[$i]' ");
		while($result = pg_fetch_array($query))
		{
			$corp_regis = $result["corp_regis"]; // เลขทะเบียนนิติบุคคล
		}
		
		$textreturn .= "\"$i\"";
		
		$textreturn .= ",\"1150001\",\"$corp_regis\"<br>";
	}
	
	if($i >= 1){$textreturn = substr($textreturn,0,strlen($textreturn)-4);} // ตัดบรรทัดว่างๆล่างสุดทิ้ง คือตัด <br> สุดท้ายทิ้ง
	
	return $textreturn;
}
?>