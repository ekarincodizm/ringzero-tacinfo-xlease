<?php
function AD_head($day , $month , $year) // ชื่อไฟล์ของ AD
{
	while(strlen($day) < 2)
	{
		$day = "0".$day;
	}
	
	while(strlen($month) < 2)
	{
		$month = "0".$month;
	}
	
	$textreturn = "AD-1103-$year$month$day-1.csv";
	
	return $textreturn;
}

function AD_text($CorporationArray2D) // ข้อความใน AD
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
		$select_addsType = "";
		
		$query_id = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID[$i]' ");
		while($result_id = pg_fetch_array($query_id))
		{
			$addsType = $result_id["addsType"]; // ประเภทที่อยู่
			
			if($select_addsType != 1)
			{
				if($select_addsType == 3)
				{
					if($addsType == 1)
					{
						$select_addsType = $addsType;
					}
				}
				else
				{
					$select_addsType = $addsType;
				}
			}
		}
		
		$query_address_new = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID[$i]' and \"addsType\" = '$select_addsType' ");
		while($result_address_new = pg_fetch_array($query_address_new))
		{
			$District = $result_address_new["District"]; // แขวง/ตำบล
			$State = $result_address_new["State"]; // เขต/อำเภอ
			$ProvinceID = $result_address_new["ProvinceID"]; // รหัสจังหวัด
			$Postal_code = $result_address_new["Postal_code"]; // รหัสไปรษณีย์
			$Country = $result_address_new["Country"]; // ประเทศ
			$phone = $result_address_new["phone"]; // โทรศัพท์
			$Fax = $result_address_new["Fax"]; // โทรสาร
		}
		
		if(!is_numeric($Postal_code)) // ถ้ารหัสไปรษณีย์ไม่ใช่ตัวเลข
		{
			$Postal_code = "";
		}
		
		$txt_addsType = address_type($select_addsType);
		
		$fulladdress = address($corpID[$i] , $select_addsType);
		$number_Address = utf8_strlen($fulladdress); // ความยาวของที่อยู่
		
		if($number_Address > 100)
		{
			$Address_Line_1 = substr_utf8($fulladdress,0,100);
			$Address_Line_2 = substr_utf8($fulladdress,100,$number_Address - 100);
		}
		else
		{
			$Address_Line_1 = $fulladdress;
			$Address_Line_2 = "";
		}
		
		$Province = province_code($ProvinceID);
		
		$textreturn .= "\"$i\"";
		$textreturn .= ",\"$txt_addsType\"";
		$textreturn .= ",\"$Address_Line_1\",\"$Address_Line_2\",\"$District\",\"$State\",\"$Province\",\"$Postal_code\",\"\",\"\"<br>";
	}
	
	if($i >= 1){$textreturn = substr($textreturn,0,strlen($textreturn)-4);} // ตัดบรรทัดว่างๆล่างสุดทิ้ง คือตัด <br> สุดท้ายทิ้ง
	
	return $textreturn;
}

function address_type($type)
{
	if($type == 1)
	{
		return "1010004";
	}
	elseif($type == 2)
	{
		return "1010005";
	}
	elseif($type == 3)
	{
		return "1010003";
	}
	else
	{
		return "1010001";
	}
}

function address($corpID , $Type)
{
	$textreturn = "";
	$query_address = pg_query("select * from public.\"th_corp_adds\" where \"corpID\" = '$corpID' and \"addsType\" = '$Type' ");
	while($result_address = pg_fetch_array($query_address))
	{
		$HomeNumber = $result_address["HomeNumber"]; // บ้านเลขที่
		$room = $result_address["room"]; // หมายเลขห้อง
		$LiveFloor = $result_address["LiveFloor"]; // อาศัยอยู่ชั้นที่
		$Moo = $result_address["Moo"]; // หมู่ที่
		$Building = $result_address["Building"]; // อาคาร/สถานที่
		$Village = $result_address["Village"]; // หมู่บ้าน
		$Lane = $result_address["Lane"]; // ซอย
		$Road = $result_address["Road"]; // ถนน
		
		if($HomeNumber != "" && $HomeNumber != "-"){$textreturn .= $HomeNumber;}
		if($room != "" && $room != "-"){$textreturn .= " ห้อง$room";}
		if($LiveFloor != "" && $LiveFloor != "-"){$textreturn .= " ชั้น$LiveFloor";}
		if($Building != "" && $Building != "-"){$textreturn .= " อาคาร$Building";}
		if($Moo != "" && $Moo != "-"){$textreturn .= " หมู่ที่$Moo";}
		if($Village != "" && $Village != "-"){$textreturn .= " หมู่บ้าน$Village";}
		if($Lane != "" && $Lane != "-"){$textreturn .= " ซอย$Lane";}
		if($Road != "" && $Road != "-"){$textreturn .= " ถนน$Road";}
		
		return $textreturn;
	}
}

function utf8_strlen($s) // function สำหรับหาจำนวนตัวอักษร ใช้ได้ดีกับภาษาไทยด้วย
{
	$c = strlen($s); $l = 0;
    for($i = 0; $i < $c; ++$i) 
	{
		if((ord($s[$i]) & 0xC0) != 0x80) ++$l;
	}
	
	return $l;
}

function substr_utf8( $str, $start_p , $len_p) // function สำหรับตัดข้อความ ใช้ได้ดีกับภาษาไทยด้วย
{
	return preg_replace( '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start_p.'}'.
						'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len_p.'}).*#s',
						'$1' , $str );
}

function province_code($ProvinceID)
{
	while(strlen($ProvinceID) < 2)
	{
		$ProvinceID = "0".$ProvinceID;
	}
	
	if($ProvinceID == "02") // "กรุงเทพมหานคร"
	{
		return "1110001";
	}
	elseif($ProvinceID == "31") // "พระนครศรีอยุธยา"
	{
		return "1110002";
	}
	elseif($ProvinceID == "46") // "ลพบุรี"
	{
		return "1110003";
	}
	elseif($ProvinceID == "10") // "ชัยนาท"
	{
		return "1110004";
	}
	elseif($ProvinceID == "58") // "สิงห์บุรี"
	{
		return "1110005";
	}
	elseif($ProvinceID == "70") // "อ่างทอง"
	{
		return "1110006";
	}
	elseif($ProvinceID == "56") // "สระบุรี"
	{
		return "1110007";
	}
	elseif($ProvinceID == "27") // "ปทุมธานี"
	{
		return "1110008";
	}
	elseif($ProvinceID == "22") // "นนทบุรี"
	{
		return "1110009";
	}
	elseif($ProvinceID == "09") // "ชลบุรี"
	{
		return "1110010";
	}
	elseif($ProvinceID == "08") // "ฉะเชิงเทรา"
	{
		return "1110011";
	}
	elseif($ProvinceID == "16") // "นครนายก"
	{
		return "1110012";
	}
	elseif($ProvinceID == "29") // "ปราจีนบุรี"
	{
		return "1110013";
	}
	elseif($ProvinceID == "43") // "ระยอง"
	{
		return "1110014";
	}
	elseif($ProvinceID == "14") // "ตราด"
	{
		return "1110015";
	}
	elseif($ProvinceID == "07") // "จันทบุรี"
	{
		return "1110016";
	}
	elseif($ProvinceID == "53") // "สมุทรปราการ"
	{
		return "1110017";
	}
	elseif($ProvinceID == "57") // "สระแก้ว"
	{
		return "1110018";
	}
	elseif($ProvinceID == "19") // "นครราชสีมา"
	{
		return "1110019";
	}
	elseif($ProvinceID == "11") // "ชัยภูมิ"
	{
		return "1110020";
	}
	elseif($ProvinceID == "26") // "บุรีรัมย์"
	{
		return "1110021";
	}
	elseif($ProvinceID == "61") // "สุรินทร์"
	{
		return "1110022";
	}
	elseif($ProvinceID == "49") // "ศรีสะเกษ"
	{
		return "1110023";
	}
	elseif($ProvinceID == "69") // "อุบลราชธานี"
	{
		return "1110024";
	}
	elseif($ProvinceID == "41") // "ยโสธร"
	{
		return "1110025";
	}
	elseif($ProvinceID == "65") // "อำนาจเจริญ"
	{
		return "1110026";
	}
	elseif($ProvinceID == "66") // "อุดรธานี"
	{
		return "1110027";
	}
	elseif($ProvinceID == "63") // "หนองคาย"
	{
		return "1110028";
	}
	elseif($ProvinceID == "75") // "เลย"
	{
		return "1110029";
	}
	elseif($ProvinceID == "50") // "สกลนคร"
	{
		return "1110030";
	}
	elseif($ProvinceID == "18") // "นครพนม"
	{
		return "1110031";
	}
	elseif($ProvinceID == "06") // "ขอนแก่น"
	{
		return "1110032";
	}
	elseif($ProvinceID == "38") // "มหาสารคาม"
	{
		return "1110033";
	}
	elseif($ProvinceID == "45") // "ร้อยเอ็ด"
	{
		return "1110034";
	}
	elseif($ProvinceID == "04") // "กาฬสินธุ์"
	{
		return "1110035";
	}
	elseif($ProvinceID == "39") // "มุกดาหาร"
	{
		return "1110036";
	}
	elseif($ProvinceID == "72") // "เชียงใหม่"
	{
		return "1110037";
	}
	elseif($ProvinceID == "47") // "ลำปาง"
	{
		return "1110038";
	}
	elseif($ProvinceID == "77") // "แม่ฮ่องสอน"
	{
		return "1110039";
	}
	elseif($ProvinceID == "71") // "เชียงราย"
	{
		return "1110040";
	}
	elseif($ProvinceID == "24") // "น่าน"
	{
		return "1110041";
	}
	elseif($ProvinceID == "48") // "ลำพูน"
	{
		return "1110042";
	}
	elseif($ProvinceID == "76") // "แพร่"
	{
		return "1110043";
	}
	elseif($ProvinceID == "32") // "พะเยา"
	{
		return "1110044";
	}
	elseif($ProvinceID == "64") // "หนองบัวลำภู"
	{
		return "1110045";
	}
	elseif($ProvinceID == "36") // "พิษณุโลก"
	{
		return "1110046";
	}
	elseif($ProvinceID == "67") // "อุตรดิตถ์"
	{
		return "1110047";
	}
	elseif($ProvinceID == "62") // "สุโขทัย"
	{
		return "1110048";
	}
	elseif($ProvinceID == "15") // "ตาก"
	{
		return "1110049";
	}
	elseif($ProvinceID == "68") // "อุทัยธานี"
	{
		return "1110050";
	}
	elseif($ProvinceID == "05") // "กำแพงเพชร"
	{
		return "1110051";
	}
	elseif($ProvinceID == "35") // "พิจิตร"
	{
		return "1110052";
	}
	elseif($ProvinceID == "74") // "เพชรบูรณ์"
	{
		return "1110053";
	}
	elseif($ProvinceID == "21") // "นครสวรรค์"
	{
		return "1110054";
	}
	elseif($ProvinceID == "17") // "นครปฐม"
	{
		return "1110055";
	}
	elseif($ProvinceID == "59") // "สุพรรณบุรี"
	{
		return "1110056";
	}
	elseif($ProvinceID == "03") // "กาญจนบุรี"
	{
		return "1110057";
	}
	elseif($ProvinceID == "44") // "ราชบุรี"
	{
		return "1110058";
	}
	elseif($ProvinceID == "73") // "เพชรบุรี"
	{
		return "1110059";
	}
	elseif($ProvinceID == "55") // "สมุทรสาคร"
	{
		return "1110060";
	}
	elseif($ProvinceID == "54") // "สมุทรสงคราม"
	{
		return "1110061";
	}
	elseif($ProvinceID == "28") // "ประจวบคีรีขันธ์"
	{
		return "1110062";
	}
	elseif($ProvinceID == "20") // "นครศรีธรรมราช"
	{
		return "1110063";
	}
	elseif($ProvinceID == "12") // "ชุมพร"
	{
		return "1110064";
	}
	elseif($ProvinceID == "60") // "สุราษฎร์ธานี"
	{
		return "1110065";
	}
	elseif($ProvinceID == "42") // "ระนอง"
	{
		return "1110066";
	}
	elseif($ProvinceID == "01") // "กระบี่"
	{
		return "1110067";
	}
	elseif($ProvinceID == "33") // "พังงา"
	{
		return "1110068";
	}
	elseif($ProvinceID == "37") // "ภูเก็ต"
	{
		return "1110069";
	}
	elseif($ProvinceID == "51") // "สงขลา"
	{
		return "1110070";
	}
	elseif($ProvinceID == "13") // "ตรัง"
	{
		return "1110071";
	}
	elseif($ProvinceID == "34") // "พัทลุง"
	{
		return "1110072";
	}
	elseif($ProvinceID == "52") // "สตูล"
	{
		return "1110073";
	}
	elseif($ProvinceID == "30") // "ปัตตานี"
	{
		return "1110074";
	}
	elseif($ProvinceID == "40") // "ยะลา"
	{
		return "1110075";
	}
	elseif($ProvinceID == "23") // "นราธิวาส"
	{
		return "1110076";
	}
}
?>