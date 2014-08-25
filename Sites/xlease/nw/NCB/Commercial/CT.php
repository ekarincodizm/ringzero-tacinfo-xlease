<?php
function CT_head($day , $month , $year) // ชื่อไฟล์ของ CT
{
	while(strlen($day) < 2)
	{
		$day = "0".$day;
	}
	
	while(strlen($month) < 2)
	{
		$month = "0".$month;
	}
	
	$textreturn = "CT-1103-$year$month$day-1.xml";
	
	return $textreturn;
}

function CT_text($day , $month , $year) // ข้อความใน CT
{
	while(strlen($day) < 2)
	{
		$day = "0".$day;
	}
	
	while(strlen($month) < 2)
	{
		$month = "0".$month;
	}
	
	$textreturn = "<<??>?xml version=\"1.0\" encoding=\"UTF-8\"?<??>>";
	$textreturn .= "<br>";
	$textreturn .= "<<??>FILEDESCRIPTION contributor-code=\"1103\" period=\"$year$month\" reported-date=\"$year$month$day\" contributor-name=\"THCAP\">";
	$textreturn .= "<br>";
	$textreturn .= "<<??>FORMAT field-separator=\",\" row-separator=\"&<??>#013;\" textpad=\"\" numpad=\"\" text-qualifier=\"&<??>#034;\" />";
	$textreturn .= "<br>";
	$textreturn .= "<<??>SEGMENT id=\"PF\" name=\"Profile\" file-name=\"PF-1103-$year$month$day-1.csv\" />";
	$textreturn .= "<br>";
	$textreturn .= "<<??>SEGMENT id=\"ID\" name=\"ID\" file-name=\"ID-1103-$year$month$day-1.csv\" />";
	$textreturn .= "<br>";
	$textreturn .= "<<??>SEGMENT id=\"AD\" name=\"Address\" file-name=\"AD-1103-$year$month$day-1.csv\" />";
	$textreturn .= "<br>";
	$textreturn .= "<<??>SEGMENT id=\"CR\" name=\"Credit\" file-name=\"CR-1103-$year$month$day-1.csv\" />";
	$textreturn .= "<br>";
	$textreturn .= "<<??>/FILEDESCRIPTION>";
	
	return $textreturn;
}
?>