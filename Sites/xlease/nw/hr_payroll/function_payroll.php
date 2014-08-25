<?php

function CheckPublicHoliday($strChkDate)
	{

		$strSQL = "SELECT \"desc\" FROM hr_public_holiday WHERE pub_holiday = '".$strChkDate."' ";
		
		$objQuery = pg_query($strSQL);
		$objResult = pg_fetch_array($objQuery);
	
			return $objResult['desc'];
	
		
	}
function CountPublicHoliday($yy)
	{

		$strSQL = "SELECT \"desc\" FROM hr_public_holiday WHERE pub_holiday::character varying LIKE '$yy-%' ";
		
		$objQuery = pg_query($strSQL);
	
	$nub=pg_num_rows($objQuery);
			return $nub;
	
		
	}
	function explodeStr2($de,$Str){
	
				$strr = explode($de,$Str) ;
				
				return $strr;
		}
		function explode_Count2($de,$Str){
		
				$num = count(explode($de,$Str));
				$num --;
				return $num;
		}

function dateDiv($t1,$t2){ // ส่งวันที่ที่ต้องการเปรียบเทียบ ในรูปแบบ มาตรฐาน 2006-03-27 21:39:12

$t1Arr=splitTime($t1);
 $t2Arr=splitTime($t2);

 $Time1=mktime($t1Arr["h"], $t1Arr["m"], $t1Arr["s"], $t1Arr["M"], $t1Arr["D"], $t1Arr["Y"]);
 $Time2=mktime($t2Arr["h"], $t2Arr["m"], $t2Arr["s"], $t2Arr["M"], $t2Arr["D"], $t2Arr["Y"]);
 $TimeDiv=($Time2-$Time1);

 $Time["D"]=intval($TimeDiv/86400); //� จำนวนวัน
 $Time["H"]=intval(($TimeDiv%86400)/3600); // จำนวน ชั่วโมง
 $Time["M"]=intval((($TimeDiv%86400)%3600)/60); // จำนวน นาที
 $Time["S"]=intval(((($TimeDiv%86400)%3600)%60)); // จำนวน วินาที
return $Time;
}



function splitTime($time){ // เวลาในรูปแบบ มาตรฐาน 2006-03-27 21:39:12
$timeArr["Y"]= substr($time,2,2);
$timeArr["M"]= substr($time,5,2);
$timeArr["D"]= substr($time,8,2);
$timeArr["h"]= substr($time,11,2);
$timeArr["m"]= substr($time,14,2);
 $timeArr["s"]= substr($time,17,2);
return $timeArr;
}

function getUserFullName($user_id){ 
	$res_profile=pg_query("select fullname from \"Vfuser\" where id_user='$user_id'");
   $res_userprofile=pg_fetch_array($res_profile);
   $fullname= $res_userprofile["fullname"];
   return $fullname;
}
function getUserFName($user_id){ 
	$res_profile=pg_query("select fname from \"fuser\" where id_user='$user_id'");
   $res_userprofile=pg_fetch_array($res_profile);
   $fname= $res_userprofile["fname"];
   return $fname;
}


?>