<?php
	include("../../config/config.php");
	echo"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	$query=pg_query("select * from amphur");
	while($result=pg_fetch_assoc($query))
	{
		$AMPHUR_CODE=$result['AMPHUR_CODE'];
		$AMPHUR_NAME=$result['AMPHUR_NAME'];
		$GEO_ID=$result['GEO_ID'];
		$PROVINCE_ID=$result['PROVINCE_ID'];
		pg_query("insert into carsystem.\"amphur\"(\"AMPHUR_CODE\",\"AMPHUR_NAME\",\"GEO_ID\",\"PROVINCE_ID\") values('$AMPHUR_CODE','$AMPHUR_NAME','$GEO_ID','$PROVINCE_ID')");
	}	
?>