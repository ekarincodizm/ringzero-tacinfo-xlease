<?php
echo "<select name=f_san id=f_san $disabled>";
echo "<option value=\"\" $chk>--เลือก--</option>";
echo "<option value=\"ไม่ระบุ\" $chk>ไม่ระบุ</option>";

$query_country=pg_query("select \"CountryCode\",\"Nationality_THAI\" from \"Country_Code\" where \"Status\" = 'TRUE' AND \"Nationality_THAI\" is not null order by \"Nationality_THAI\"") ;
while($res_country = pg_fetch_array($query_country))
{
	$chk = "";
	$Nationality_THAI = $res_country["Nationality_THAI"];
	
	if(trim($f_san) == $Nationality_THAI)
	{
		$chk="selected";
	}
	
	echo "<option value=\"$Nationality_THAI\" $chk>$Nationality_THAI</option>";
}

echo "</select>";
?>