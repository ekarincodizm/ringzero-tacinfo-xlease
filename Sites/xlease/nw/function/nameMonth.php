<?php
function nameMonthTH($getData) // หาชื่อเต็มเดือน ภาษาไทย ตามเลขเดือนที่รับค่ามา
{
	if($getData == "01")
	{		
		$returnMonth = "มกราคม";
	}
	elseif($getData == "02")
	{	
		$returnMonth = "กุมภาพันธ์";
	}
	elseif($getData == "03")
	{	
		$returnMonth = "มีนาคม";
	}
	elseif($getData == "04")
	{	
		$returnMonth = "เมษายน";
	}
	elseif($getData == "05")
	{	
		$returnMonth = "พฤษภาคม";
	}
	elseif($getData == "06")
	{	
		$returnMonth = "มิถุนายน";
	}
	elseif($getData == "07")
	{	
		$returnMonth = "กรกฎาคม";
	}
	elseif($getData == "08")
	{	
		$returnMonth = "สิงหาคม";
	}
	elseif($getData == "09")
	{	
		$returnMonth = "กันยายน";
	}
	elseif($getData == "10")
	{	
		$returnMonth = "ตุลาคม";
	}
	elseif($getData == "11")
	{	
		$returnMonth = "พฤศจิกายน";
	}
	elseif($getData == "12")
	{	
		$returnMonth = "ธันวาคม";
	}
	
	return $returnMonth;
}

function shortMonthTH($getData) // หาชื่อย่อยเดือน ภาษาไทย ตามเลขเดือนที่รับค่ามา
{
	if($getData == "01")
	{		
		$returnMonth = "ม.ค.";
	}
	elseif($getData == "02")
	{	
		$returnMonth = "ก.พ.";
	}
	elseif($getData == "03")
	{	
		$returnMonth = "มี.ค.";
	}
	elseif($getData == "04")
	{	
		$returnMonth = "เม.ย.";
	}
	elseif($getData == "05")
	{	
		$returnMonth = "พ.ค.";
	}
	elseif($getData == "06")
	{	
		$returnMonth = "มิ.ย.";
	}
	elseif($getData == "07")
	{	
		$returnMonth = "ก.ค.";
	}
	elseif($getData == "08")
	{	
		$returnMonth = "ส.ค.";
	}
	elseif($getData == "09")
	{	
		$returnMonth = "ก.ย.";
	}
	elseif($getData == "10")
	{	
		$returnMonth = "ต.ค.";
	}
	elseif($getData == "11")
	{	
		$returnMonth = "พ.ย.";
	}
	elseif($getData == "12")
	{	
		$returnMonth = "ธ.ค.";
	}
	
	return $returnMonth;
}

function numMonthTH($getData) // แปลงจากชื่อย่อเป็นตัวเลข
{
	if($getData == "ม.ค.")
	{		
		$returnMonth = "01";
	}
	elseif($getData == "ก.พ.")
	{	
		$returnMonth = "02";
	}
	elseif($getData == "มี.ค.")
	{	
		$returnMonth = "03";
	}
	elseif($getData == "เม.ย.")
	{	
		$returnMonth = "04";
	}
	elseif($getData == "พ.ค.")
	{	
		$returnMonth = "05";
	}
	elseif($getData == "มิ.ย.")
	{	
		$returnMonth = "06";
	}
	elseif($getData == "ก.ค.")
	{	
		$returnMonth = "07";
	}
	elseif($getData == "ส.ค.")
	{	
		$returnMonth = "08";
	}
	elseif($getData == "ก.ย.")
	{	
		$returnMonth = "09";
	}
	elseif($getData == "ต.ค.")
	{	
		$returnMonth = "10";
	}
	elseif($getData == "พ.ย.")
	{	
		$returnMonth = "11";
	}
	elseif($getData == "ธ.ค.")
	{	
		$returnMonth = "12";
	}
	
	return $returnMonth;
}

?>