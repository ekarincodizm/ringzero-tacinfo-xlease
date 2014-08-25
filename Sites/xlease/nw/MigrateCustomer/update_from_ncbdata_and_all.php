<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("../../config/config.php");
include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;

//---------- update thcap_temp_ncbdata
$sql_origin_data = pg_query("select * from public.\"thcap_temp_ncbdata\" ");
$numrows_data = pg_num_rows($sql_origin_data);
if($numrows_data > 0)
{
	while($originData = pg_fetch_array($sql_origin_data))
	{
		$contractID = $originData["contractID"];
		$ID_Number = $originData["CusIDNum"];
		$Address = $originData["address"];
		$Subdistrict = $originData["subdistrict"];
		$District = $originData["district"];
		$Province = $originData["province"];
		$Country = $originData["country"];
		$Postal_Code = $originData["postal_code"];
		
		// ตัดช่องว่างและเครื่องต่างๆของเลขที่บัตรออกเพื่อใช้ในการเช็คกับฐานข้อมูล
		$ID_Number_Chk = str_replace(" ","",$ID_Number);
		$ID_Number_Chk = str_replace("-","",$ID_Number_Chk);
		
		$AddressChk = str_replace(" ","",$Address);
		
		//ค้นหาคำที่เขียนไม่เหมือนกันแต่ความหมายเดียวกัน ให้เป็นรูปแบบเดียวกัน
		$array = array("ซ." => " ชื่อซอย", "ซอย" => " ชื่อซอย", "ตรอก" => " ชื่อซอยตรอก", "ถ." => " ชื่อถนน", "ถนน" => " ชื่อถนน", "ม." => " เลขหมู่", "หมู่" => " เลขหมู่", "อาคาร" => " ชื่ออาคาร", "ชั้น" => " เลขชั้น", "ชั้นที่" => " เลขชั้น", "ห้อง" => " เลขห้อง", "หมู่บ้าน" => " ชื่อหมู่บ้าน", "ชุมชนหมู่บ้าน" => " ชื่อหมู่บ้าน");
		
		//แก้คำ
		$AddressChk = strtr($AddressChk, $array);
		
		$Address_SUBNO = ""; // หมู่
		$Address_SOI = ""; // ซอย
		$Address_RD = ""; // ถนน
		$Address_BUILDING = ""; // อาคาร
		$Address_FLOOR = ""; // ชั้น
		$Address_ROOM = ""; // ห้อง
		$Address_VILLAGE = ""; // ชื่อหมู่บ้าน
		$con = ""; // คอนโด
		
		// หาบ้านเลขที่
		if($AddressChk != "")
		{
			$AddrSplit_No = explode(" ",$AddressChk);
			$Address_NO = $AddrSplit_No[0];
			
			$search_condo = strpos($Address_NO,"อนโด");
			if($search_condo)
			{
				$Num_Address_NO = strlen($Address_NO);
				for($i=0 ; $i<$Num_Address_NO ; $i++)
				{
					$chk_text = substr($Address_NO,$i,1);
					if($chk_text == "0" || $chk_text == "1" || $chk_text == "2" || $chk_text == "3" || $chk_text == "4" || $chk_text == "5" || $chk_text == "6" || $chk_text == "7" || $chk_text == "8" || $chk_text == "9" || $chk_text == "/")
					{
						$havetext = "no";
					}
					else
					{
						$havetext = "yes";
						$place = $i;
						break;
					}
				}
				
				if($havetext == "yes")
				{
					$slen = $Num_Address_NO - $i;
					$newtext = substr($Address_NO,$place,$slen);
					
					$search_con = strpos($newtext,"อนโด");
					if($search_con)
					{
						$Address_NO = substr($Address_NO,0,$place);
						$con = $newtext;
					}
					else
					{
						$Address_NO = $Address_NO;
					}
				}
			}
		}
		else
		{
			$Address_NO = "";
		}
		
		// หาที่อยู่ส่วนอื่นๆ
		if($AddressChk != "")
		{
			$AddrSplit = explode(" ",$AddressChk);
			$AddrCount = count($AddrSplit);
			
			for($i = 0 ; $i < $AddrCount ; $i++)
			{
				// หาหมู่
				$search_SUBNO = strpos($AddrSplit[$i],"หมู่" && !strpos($AddrSplit[$i],"หมู่บ้าน"));
				if($search_SUBNO)
				{
					$Address_SUBNO = $AddrSplit[$i];
					$Address_SUBNO = str_replace("เลขหมู่","",$Address_SUBNO);
					$Address_SUBNO = str_replace(" ","",$Address_SUBNO);
					
					// ตรวจสอบว่าในหมู่มีคอนโดปนอยู่หรือไม่
					$search_condo = strpos($Address_SUBNO,"อนโด");
					if($search_condo)
					{
						$Num_Address_SUBNO = strlen($Address_SUBNO);
						for($m=0 ; $m<$Num_Address_SUBNO ; $m++)
						{
							$chk_text = substr($Address_SUBNO,$m,1);
							if($chk_text == "0" || $chk_text == "1" || $chk_text == "2" || $chk_text == "3" || $chk_text == "4" || $chk_text == "5" || $chk_text == "6" || $chk_text == "7" || $chk_text == "8" || $chk_text == "9")
							{
								$havetext = "no";
							}
							else
							{
								$havetext = "yes";
								$place = $m;
								break;
							}
						}
						
						if($havetext == "yes")
						{
							$slen = $Num_Address_SUBNO - $m;
							$newtext = substr($Address_SUBNO,$place,$slen);
							
							$search_con = strpos($newtext,"อนโด");
							if($search_con)
							{
								$Address_SUBNO = substr($Address_SUBNO,0,$place);
								$con = $newtext;
							}
							else
							{
								$Address_SUBNO = $Address_SUBNO;
							}
						}
					}
				}
				
				// หาซอย
				$search_SOI = strpos($AddrSplit[$i],"ซอย");
				if($search_SOI)
				{
					$Address_SOI = $AddrSplit[$i];
					$Address_SOI = str_replace("ชื่อซอย","",$Address_SOI);
					$Address_SOI = str_replace(" ","",$Address_SOI);
				}
				
				// หาถนน
				$search_RD = strpos($AddrSplit[$i],"ถนน");
				if($search_RD)
				{
					$Address_RD = $AddrSplit[$i];
					$Address_RD = str_replace("ชื่อถนน","",$Address_RD);
					$Address_RD = str_replace(" ","",$Address_RD);
				}
				
				// หาอาคาร
				$search_BUILDING = strpos($AddrSplit[$i],"อาคาร");
				if($search_BUILDING)
				{
					$Address_BUILDING = $AddrSplit[$i];
					$Address_BUILDING = str_replace("ชื่ออาคาร","",$Address_BUILDING);
					$Address_BUILDING = str_replace(" ","",$Address_BUILDING);
				}
				//ถ้าอาคารไม่มี ลองดูว่ามีคอนโดหรือไม่
				if($Address_BUILDING == "" && $con != "")
				{
					$Address_BUILDING = $con;
				}
				
				// หาชั้น
				$search_FLOOR = strpos($AddrSplit[$i],"ชั้น");
				if($search_FLOOR)
				{
					$Address_FLOOR = $AddrSplit[$i];
					$Address_FLOOR = str_replace("เลขชั้น","",$Address_FLOOR);
					$Address_FLOOR = str_replace(" ","",$Address_FLOOR);
				}
				
				// หาห้อง
				$search_ROOM = strpos($AddrSplit[$i],"ห้อง");
				if($search_ROOM)
				{
					$Address_ROOM = $AddrSplit[$i];
					$Address_ROOM = str_replace("เลขห้อง","",$Address_ROOM);
					$Address_ROOM = str_replace(" ","",$Address_ROOM);
				}
				
				// หาหมู่บ้าน
				$search_VILLAGE = strpos($AddrSplit[$i],"หมู่บ้าน");
				if($search_VILLAGE)
				{
					$Address_VILLAGE = $AddrSplit[$i];
					$Address_VILLAGE = str_replace("ชื่อหมู่บ้าน","",$Address_VILLAGE);
					$Address_VILLAGE = str_replace(" ","",$Address_VILLAGE);
				}
			}
		}
		
		//ตัดเครื่องหมาย ' ออก
		$Title = str_replace("'","",$Title);
		$fname = str_replace("'","",$fname);
		$sname = str_replace("'","",$sname);
		$Spouse_Name = str_replace("'","",$Spouse_Name);
		$Address_NO = str_replace("'","",$Address_NO);
		$Address_SUBNO = str_replace("'","",$Address_SUBNO);
		$Address_SOI = str_replace("'","",$Address_SOI);
		$Address_RD = str_replace("'","",$Address_RD);
		$Address_BUILDING = str_replace("'","",$Address_BUILDING);
		$Address_FLOOR = str_replace("'","",$Address_FLOOR);
		$Address_ROOM = str_replace("'","",$Address_ROOM);
		$Subdistrict = str_replace("'","",$Subdistrict);
		$District = str_replace("'","",$District);
		$Province = str_replace("'","",$Province);
		$Postal_Code = str_replace("'","",$Postal_Code);
		$ID_Number = str_replace("'","",$ID_Number);
		$Marital_Status = str_replace("'","",$Marital_Status);
		$Country = str_replace("'","",$Country);
		$Gender = str_replace("'","",$Gender);
		$Address_VILLAGE = str_replace("'","",$Address_VILLAGE);
		
		//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
		$Title = checknull($Title);
		$fname = checknull($fname);
		$sname = checknull($sname);
		$Spouse_Name = checknull($Spouse_Name);
		$Address_NO = checknull($Address_NO);
		$Address_SUBNO = checknull($Address_SUBNO);
		$Address_SOI = checknull($Address_SOI);
		$Address_RD = checknull($Address_RD);
		$Address_BUILDING = checknull($Address_BUILDING);
		$Address_FLOOR = checknull($Address_FLOOR);
		$Address_ROOM = checknull($Address_ROOM);
		$Subdistrict = checknull($Subdistrict);
		$District = checknull($District);
		$Province = checknull($Province);
		$Postal_Code = checknull($Postal_Code);
		$ID_Number = checknull($ID_Number);
		$Marital_Status = checknull($Marital_Status);
		$Country = checknull($Country);
		$Gender = checknull($Gender);
		$Address_VILLAGE = checknull($Address_VILLAGE);
		
		// กำหนดสัญชาติ และชื่อประเทศเป็นภาษาไทย
		if($Nationality == "00")
		{
			$Nationality_TH = "'ไม่ระบุ'";
			$COUNTRY_TH = "NULL";
		}
		elseif($Nationality == "'01'")
		{
			$Nationality_TH = "'ไทย'";
			$COUNTRY_TH = "'ไทย'";
		}
		elseif($Nationality == "'02'")
		{
			$Nationality_TH = "'จีน'";
			$COUNTRY_TH = "'จีน'";
		}
		elseif($Nationality == "03")
		{
			$Nationality_TH = "'ญี่ปุ่น'";
			$COUNTRY_TH = "'ญี่ปุ่น'";
		}
		elseif($Nationality == "04")
		{
			$Nationality_TH = "'อเมริกัน'";
			$COUNTRY_TH = "'อเมริกัน'";
		}
		elseif($Nationality == "99")
		{
			$Nationality_TH = "'อื่นๆ'";
			$COUNTRY_TH = "NULL";
		}
		else
		{
			if($Nationality != "")
			{
				$Nationality_TH = "'อื่นๆ'";
				$COUNTRY_TH = "NULL";
			}
			else
			{
				$Nationality_TH = "NULL";
				$COUNTRY_TH = "NULL";
			}
		}
		
		$sql_vncb_data = pg_query("select * from public.\"VNCB\" where replace(replace(\"N_IDCARD\",'-',''),' ','') = '$ID_Number_Chk'");
		$numChkVncb_data = pg_num_rows($sql_vncb_data);
		if($numChkVncb_data > 0)
		{
			//--- เริ่มบันทึกข้อมูล
			while($resVncb_data = pg_fetch_array($sql_vncb_data))
			{
				$CusID = $resVncb_data["CusID"];
				
				// หาค่าที่แก้ไขครั้งล่าสุด
				$sql_MaxCusEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID'");
				while($res_MaxCusEdit = pg_fetch_array($sql_MaxCusEdit))
				{
					$MaxCusEdit = $res_MaxCusEdit["edittime"];
				}
				$MaxOld = $MaxCusEdit;
				$MaxCusEdit++;
				
				$sqlDataMaxOld = pg_query("select * from public.\"Customer_Temp\" where \"CusID\" = '$CusID' and \"edittime\" = '$MaxOld' ");
				while($resCusOld = pg_fetch_array($sqlDataMaxOld))
				{
					$OLD_A_FIRNAME = $resCusOld["A_FIRNAME"];
					$OLD_A_NAME = $resCusOld["A_NAME"];
					$OLD_A_SIRNAME = $resCusOld["A_SIRNAME"];
					$OLD_A_PAIR = $resCusOld["A_PAIR"];
					$OLD_N_SAN = $resCusOld["N_SAN"];
					$OLD_N_AGE = $resCusOld["N_AGE"];
					$OLD_N_CARD = $resCusOld["N_CARD"];
					$OLD_N_OT_DATE = $resCusOld["N_OT_DATE"];
					$OLD_N_BY = $resCusOld["N_BY"];
					$OLD_N_OCC = $resCusOld["N_OCC"];
					$OLD_N_ContactAdd = $resCusOld["N_ContactAdd"];
					$OLD_N_STATE = $resCusOld["N_STATE"];
					$OLD_A_FIRNAME_ENG = $resCusOld["A_FIRNAME_ENG"];
					$OLD_A_NAME_ENG = $resCusOld["A_NAME_ENG"];
					$OLD_A_SIRNAME_ENG = $resCusOld["A_SIRNAME_ENG"];
					$OLD_A_NICKNAME = $resCusOld["A_NICKNAME"];
					$OLD_A_STATUS = $resCusOld["A_STATUS"];
					$OLD_A_REVENUE = $resCusOld["A_REVENUE"];
					$OLD_A_EDUCATION = $resCusOld["A_EDUCATION"];
					$OLD_A_MOBILE = $resCusOld["A_MOBILE"];
					$OLD_A_TELEPHONE = $resCusOld["A_TELEPHONE"];
					$OLD_A_EMAIL = $resCusOld["A_EMAIL"];
					$OLD_A_BIRTHDAY = $resCusOld["A_BIRTHDAY"];
					$OLD_A_SEX = $resCusOld["A_SEX"];
					$OLD_N_CARDREF = $resCusOld["N_CARDREF"];
					
					//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
					$OLD_A_FIRNAME = checknull($OLD_A_FIRNAME);
					$OLD_A_NAME = checknull($OLD_A_NAME);
					$OLD_A_SIRNAME = checknull($OLD_A_SIRNAME);
					$OLD_A_PAIR = checknull($OLD_A_PAIR);
					$OLD_N_SAN = checknull($OLD_N_SAN);
					$OLD_N_AGE = checknull($OLD_N_AGE);
					$OLD_N_CARD = checknull($OLD_N_CARD);
					$OLD_N_OT_DATE = checknull($OLD_N_OT_DATE);
					$OLD_N_BY = checknull($OLD_N_BY);
					$OLD_N_OCC = checknull($OLD_N_OCC);
					$OLD_N_ContactAdd = checknull($OLD_N_ContactAdd);
					$OLD_N_STATE = checknull($OLD_N_STATE);
					$OLD_A_FIRNAME_ENG = checknull($OLD_A_FIRNAME_ENG);
					$OLD_A_NAME_ENG = checknull($OLD_A_NAME_ENG);
					$OLD_A_SIRNAME_ENG = checknull($OLD_A_SIRNAME_ENG);
					$OLD_A_NICKNAME = checknull($OLD_A_NICKNAME);
					$OLD_A_STATUS = checknull($OLD_A_STATUS);
					$OLD_A_REVENUE = checknull($OLD_A_REVENUE);
					$OLD_A_EDUCATION = checknull($OLD_A_EDUCATION);
					$OLD_A_MOBILE = checknull($OLD_A_MOBILE);
					$OLD_A_TELEPHONE = checknull($OLD_A_TELEPHONE);
					$OLD_A_EMAIL = checknull($OLD_A_EMAIL);
					$OLD_A_BIRTHDAY = checknull($OLD_A_BIRTHDAY);
					$OLD_A_SEX = checknull($OLD_A_SEX);
					$OLD_N_CARDREF = checknull($OLD_N_CARDREF);
				}
				
				$insert_Customer_Temp = "INSERT INTO public.\"Customer_Temp\"(\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\",\"A_NAME\"
													,\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
													,\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\"
													,\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\"
													,\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\",\"N_CARDREF\")
										VALUES('$CusID','000','$add_date','000','$add_date','1','$MaxCusEdit',$OLD_A_FIRNAME,$OLD_A_NAME
												,$OLD_A_SIRNAME,$OLD_A_PAIR,$Address_NO,$Address_SUBNO,$Address_SOI,$Address_RD,$Subdistrict,$District,$Province,$Postal_Code
												,$OLD_N_SAN,$OLD_N_AGE,'บัตรประชาชน',$ID_Number,$OLD_N_OT_DATE,$OLD_N_BY,$OLD_N_OCC,$OLD_N_ContactAdd,$OLD_N_STATE,$OLD_A_FIRNAME_ENG
												,$OLD_A_NAME_ENG,$OLD_A_SIRNAME_ENG,$OLD_A_NICKNAME,$OLD_A_STATUS,$OLD_A_REVENUE,$OLD_A_EDUCATION,$COUNTRY_TH,$OLD_A_MOBILE,$OLD_A_TELEPHONE,$OLD_A_EMAIL
												,$OLD_A_BIRTHDAY,$OLD_A_SEX,$Country,$OLD_N_CARDREF)";
				if($result = pg_query($insert_Customer_Temp)){
				}else{
					$status++;
				}
				
				$update_Fa1 = "update public.\"Fa1\" set \"A_NO\" = $Address_NO ,\"A_SUBNO\" = $Address_SUBNO , \"A_SOI\" = $Address_SOI , \"A_RD\" = $Address_RD
														, \"A_TUM\" = $Subdistrict ,\"A_AUM\" = $District , \"A_PRO\" = $Province , \"A_POST\" = $Postal_Code
														,\"A_COUNTRY\" = $COUNTRY_TH , \"addr_country\" = $Country
										where \"CusID\" = '$CusID' ";
				if($result = pg_query($update_Fa1)){
				}else{
					$status++;
				}
				
				$update_Fn = "update public.\"Fn\" set \"N_CARD\" = 'บัตรประชาชน' , \"N_IDCARD\" = $ID_Number
							where \"CusID\" = '$CusID' ";
				if($result = pg_query($update_Fn)){
				}else{
					$status++;
				}
				
				// หาว่าเป็นผู้กู้หลักหรือผู้กู้ร่วม
				$sql_MaxCusEdit = pg_query("select * from public.\"VNCB\" where \"CusID\" = '$CusID'");
				while($res_MaxCusEdit = pg_fetch_array($sql_MaxCusEdit))
				{
					$chkownership = $res_MaxCusEdit["ownership"];
				}
				
				if($chkownership == 0)
				{
					// หาค่าที่แก้ไขครั้งล่าสุด
					$sql_MaxConEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"thcap_addrContractID_temp\" where \"contractID\" = '$contractID'");
					while($res_MaxConEdit = pg_fetch_array($sql_MaxConEdit))
					{
						$MaxConEdit = $res_MaxConEdit["edittime"];
					}
					$MaxConEdit++;
					
					$insert_thcap_addrContractID_temp = "INSERT INTO public.\"thcap_addrContractID_temp\"(\"contractID\",\"addsType\",\"edittime\",\"A_NO\",\"A_SUBNO\",\"A_BUILDING\",\"A_ROOM\"
																										,\"A_FLOOR\",\"A_VILLAGE\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
																										,\"addUser\",\"addStamp\",\"statusApp\",\"appUser\",\"appStamp\")
														VALUES('$contractID','1','$MaxConEdit',$Address_NO,$Address_SUBNO,$Address_BUILDING,$Address_ROOM
																,$Address_FLOOR,$Address_VILLAGE,$Address_SOI,$Address_RD,$Subdistrict,$District,$Province,$Postal_Code
																,'000','$add_date','1','000','$add_date')";
					if($result = pg_query($insert_thcap_addrContractID_temp)){
					}else{
						$status++;
					}
					
					$update_thcap_addrContractID = "update public.\"thcap_addrContractID\" set \"addsType\" = '1' , \"A_NO\" = $Address_NO , \"A_SUBNO\" = $Address_SUBNO
														, \"A_BUILDING\" = $Address_BUILDING , \"A_ROOM\" = $Address_ROOM ,\"A_FLOOR\" = $Address_FLOOR , \"A_VILLAGE\" = $Address_VILLAGE
														, \"A_SOI\" = $Address_SOI , \"A_RD\" = $Address_RD ,\"A_TUM\" = $Subdistrict , \"A_AUM\" = $District , \"A_PRO\" = $Province
														, \"A_POST\" = $Postal_Code
													where \"contractID\" = '$contractID' ";
					if($result = pg_query($update_thcap_addrContractID)){
					}else{
						$status++;
					}
				}
			}
		}
	}
}


//---------- update thcap_temp_ncbdataall
$sql_origin = pg_query("select * from public.\"thcap_temp_ncbdataall\" ");
$numrowsdataall = pg_num_rows($sql_origin);
if($numrowsdataall > 0)
{
	while($origin = pg_fetch_array($sql_origin))
	{
		$current_or_new_account_number = $origin["current_or_new_account_number"];
		$ID_Type = $origin["ID_Type"];
		$ID_Number = $origin["ID_Number"];
		$Title = $origin["Title"];
		$fname = $origin["fname"];
		$sname = $origin["sname"];
		$Date_of_Birth = $origin["Date_of_Birth"];
		$Gender = $origin["Gender"];
		$Nationality = $origin["Nationality"];
		$Marital_Status = $origin["Marital_Status"];
		$Spouse_Name = $origin["Spouse_Name"];
		$Address = $origin["Address"];
		$Subdistrict = $origin["Subdistrict"];
		$District = $origin["District"];
		$Province = $origin["Province"];
		$Country = $origin["Country"];
		$Postal_Code = $origin["Postal_Code"];
		$ownership_indicator = $origin["ownership_indicator"];
		$number_of_co_borrower = $origin["number_of_co_borrower"];
		$Date_Account_Opened = $origin["Date_Account_Opened"];
		
		// กำหนดผู้กู้หลักผู้กู้ร่วม
		if($ownership_indicator == 1)
		{
			$ownership_indicator = 0;
		}
		elseif($ownership_indicator == 4)
		{
			$ownership_indicator = 1;
		}
		else
		{
			$ownership_indicator = 0;
		}
		
		// ตัดช่องว่างและเครื่องต่างๆของเลขที่บัตรออกเพื่อใช้ในการเช็คกับฐานข้อมูล
		$ID_Number_Chk = str_replace(" ","",$ID_Number);
		$ID_Number_Chk = str_replace("-","",$ID_Number_Chk);
		
		$AddressChk = str_replace(" ","",$Address);
			
		//ค้นหาคำที่เขียนไม่เหมือนกันแต่ความหมายเดียวกัน ให้เป็นรูปแบบเดียวกัน
		$array = array("ซ." => " ชื่อซอย", "ซอย" => " ชื่อซอย", "ตรอก" => " ชื่อซอยตรอก", "ถ." => " ชื่อถนน", "ถนน" => " ชื่อถนน", "ม." => " เลขหมู่", "หมู่" => " เลขหมู่", "อาคาร" => " ชื่ออาคาร", "ชั้น" => " เลขชั้น", "ชั้นที่" => " เลขชั้น", "ห้อง" => " เลขห้อง", "หมู่บ้าน" => " ชื่อหมู่บ้าน", "ชุมชนหมู่บ้าน" => " ชื่อหมู่บ้าน");
		
		//แก้คำ
		$AddressChk = strtr($AddressChk, $array);
		
		$Address_SUBNO = ""; // หมู่
		$Address_SOI = ""; // ซอย
		$Address_RD = ""; // ถนน
		$Address_BUILDING = ""; // อาคาร
		$Address_FLOOR = ""; // ชั้น
		$Address_ROOM = ""; // ห้อง
		$Address_VILLAGE = ""; // ชื่อหมู่บ้าน
		$con = ""; // คอนโด
		
		// หาบ้านเลขที่
		if($AddressChk != "")
		{
			$AddrSplit_No = explode(" ",$AddressChk);
			$Address_NO = $AddrSplit_No[0];
			
			$search_condo = strpos($Address_NO,"อนโด");
			if($search_condo)
			{
				$Num_Address_NO = strlen($Address_NO);
				for($i=0 ; $i<$Num_Address_NO ; $i++)
				{
					$chk_text = substr($Address_NO,$i,1);
					if($chk_text == "0" || $chk_text == "1" || $chk_text == "2" || $chk_text == "3" || $chk_text == "4" || $chk_text == "5" || $chk_text == "6" || $chk_text == "7" || $chk_text == "8" || $chk_text == "9" || $chk_text == "/")
					{
						$havetext = "no";
					}
					else
					{
						$havetext = "yes";
						$place = $i;
						break;
					}
				}
				
				if($havetext == "yes")
				{
					$slen = $Num_Address_NO - $i;
					$newtext = substr($Address_NO,$place,$slen);
					
					$search_con = strpos($newtext,"อนโด");
					if($search_con)
					{
						$Address_NO = substr($Address_NO,0,$place);
						$con = $newtext;
					}
					else
					{
						$Address_NO = $Address_NO;
					}
				}
			}
		}
		else
		{
			$Address_NO = "";
		}
		
		// หาที่อยู่ส่วนอื่นๆ
		if($AddressChk != "")
		{
			$AddrSplit = explode(" ",$AddressChk);
			$AddrCount = count($AddrSplit);
			
			for($i = 0 ; $i < $AddrCount ; $i++)
			{
				// หาหมู่
				$search_SUBNO = strpos($AddrSplit[$i],"หมู่" && !strpos($AddrSplit[$i],"หมู่บ้าน"));
				if($search_SUBNO)
				{
					$Address_SUBNO = $AddrSplit[$i];
					$Address_SUBNO = str_replace("เลขหมู่","",$Address_SUBNO);
					$Address_SUBNO = str_replace(" ","",$Address_SUBNO);
					
					// ตรวจสอบว่าในหมู่มีคอนโดปนอยู่หรือไม่
					$search_condo = strpos($Address_SUBNO,"อนโด");
					if($search_condo)
					{
						$Num_Address_SUBNO = strlen($Address_SUBNO);
						for($m=0 ; $m<$Num_Address_SUBNO ; $m++)
						{
							$chk_text = substr($Address_SUBNO,$m,1);
							if($chk_text == "0" || $chk_text == "1" || $chk_text == "2" || $chk_text == "3" || $chk_text == "4" || $chk_text == "5" || $chk_text == "6" || $chk_text == "7" || $chk_text == "8" || $chk_text == "9")
							{
								$havetext = "no";
							}
							else
							{
								$havetext = "yes";
								$place = $m;
								break;
							}
						}
						
						if($havetext == "yes")
						{
							$slen = $Num_Address_SUBNO - $m;
							$newtext = substr($Address_SUBNO,$place,$slen);
							
							$search_con = strpos($newtext,"อนโด");
							if($search_con)
							{
								$Address_SUBNO = substr($Address_SUBNO,0,$place);
								$con = $newtext;
							}
							else
							{
								$Address_SUBNO = $Address_SUBNO;
							}
						}
					}
				}
				
				// หาซอย
				$search_SOI = strpos($AddrSplit[$i],"ซอย");
				if($search_SOI)
				{
					$Address_SOI = $AddrSplit[$i];
					$Address_SOI = str_replace("ชื่อซอย","",$Address_SOI);
					$Address_SOI = str_replace(" ","",$Address_SOI);
				}
				
				// หาถนน
				$search_RD = strpos($AddrSplit[$i],"ถนน");
				if($search_RD)
				{
					$Address_RD = $AddrSplit[$i];
					$Address_RD = str_replace("ชื่อถนน","",$Address_RD);
					$Address_RD = str_replace(" ","",$Address_RD);
				}
				
				// หาอาคาร
				$search_BUILDING = strpos($AddrSplit[$i],"อาคาร");
				if($search_BUILDING)
				{
					$Address_BUILDING = $AddrSplit[$i];
					$Address_BUILDING = str_replace("ชื่ออาคาร","",$Address_BUILDING);
					$Address_BUILDING = str_replace(" ","",$Address_BUILDING);
				}
				//ถ้าอาคารไม่มี ลองดูว่ามีคอนโดหรือไม่
				if($Address_BUILDING == "" && $con != "")
				{
					$Address_BUILDING = $con;
				}
				
				// หาชั้น
				$search_FLOOR = strpos($AddrSplit[$i],"ชั้น");
				if($search_FLOOR)
				{
					$Address_FLOOR = $AddrSplit[$i];
					$Address_FLOOR = str_replace("เลขชั้น","",$Address_FLOOR);
					$Address_FLOOR = str_replace(" ","",$Address_FLOOR);
				}
				
				// หาห้อง
				$search_ROOM = strpos($AddrSplit[$i],"ห้อง");
				if($search_ROOM)
				{
					$Address_ROOM = $AddrSplit[$i];
					$Address_ROOM = str_replace("เลขห้อง","",$Address_ROOM);
					$Address_ROOM = str_replace(" ","",$Address_ROOM);
				}
				
				// หาหมู่บ้าน
				$search_VILLAGE = strpos($AddrSplit[$i],"หมู่บ้าน");
				if($search_VILLAGE)
				{
					$Address_VILLAGE = $AddrSplit[$i];
					$Address_VILLAGE = str_replace("ชื่อหมู่บ้าน","",$Address_VILLAGE);
					$Address_VILLAGE = str_replace(" ","",$Address_VILLAGE);
				}
			}
		}
		
		//ตัดเครื่องหมาย ' ออก
		$Title = str_replace("'","",$Title);
		$fname = str_replace("'","",$fname);
		$sname = str_replace("'","",$sname);
		$Spouse_Name = str_replace("'","",$Spouse_Name);
		$Address_NO = str_replace("'","",$Address_NO);
		$Address_SUBNO = str_replace("'","",$Address_SUBNO);
		$Address_SOI = str_replace("'","",$Address_SOI);
		$Address_RD = str_replace("'","",$Address_RD);
		$Address_BUILDING = str_replace("'","",$Address_BUILDING);
		$Address_FLOOR = str_replace("'","",$Address_FLOOR);
		$Address_ROOM = str_replace("'","",$Address_ROOM);
		$Subdistrict = str_replace("'","",$Subdistrict);
		$District = str_replace("'","",$District);
		$Province = str_replace("'","",$Province);
		$Postal_Code = str_replace("'","",$Postal_Code);
		$ID_Number = str_replace("'","",$ID_Number);
		$Marital_Status = str_replace("'","",$Marital_Status);
		$Country = str_replace("'","",$Country);
		$Gender = str_replace("'","",$Gender);
		$Address_VILLAGE = str_replace("'","",$Address_VILLAGE);
		
		//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
		$Title = checknull($Title);
		$fname = checknull($fname);
		$sname = checknull($sname);
		$Spouse_Name = checknull($Spouse_Name);
		$Address_NO = checknull($Address_NO);
		$Address_SUBNO = checknull($Address_SUBNO);
		$Address_SOI = checknull($Address_SOI);
		$Address_RD = checknull($Address_RD);
		$Address_BUILDING = checknull($Address_BUILDING);
		$Address_FLOOR = checknull($Address_FLOOR);
		$Address_ROOM = checknull($Address_ROOM);
		$Subdistrict = checknull($Subdistrict);
		$District = checknull($District);
		$Province = checknull($Province);
		$Postal_Code = checknull($Postal_Code);
		$ID_Number = checknull($ID_Number);
		$Marital_Status = checknull($Marital_Status);
		$Country = checknull($Country);
		$Gender = checknull($Gender);
		$Address_VILLAGE = checknull($Address_VILLAGE);
		
		if($Date_of_Birth == "")
		{
			$Date_of_Birth = "NULL";
		}
		else
		{
			$Date_of_Birth = "'$Date_of_Birth'";
		}
		
		// กำหนดสัญชาติ และชื่อประเทศเป็นภาษาไทย
		if($Nationality == "00")
		{
			$Nationality_TH = "'ไม่ระบุ'";
			$COUNTRY_TH = "NULL";
		}
		elseif($Nationality == "'01'")
		{
			$Nationality_TH = "'ไทย'";
			$COUNTRY_TH = "'ไทย'";
		}
		elseif($Nationality == "'02'")
		{
			$Nationality_TH = "'จีน'";
			$COUNTRY_TH = "'จีน'";
		}
		elseif($Nationality == "03")
		{
			$Nationality_TH = "'ญี่ปุ่น'";
			$COUNTRY_TH = "'ญี่ปุ่น'";
		}
		elseif($Nationality == "04")
		{
			$Nationality_TH = "'อเมริกัน'";
			$COUNTRY_TH = "'อเมริกัน'";
		}
		elseif($Nationality == "99")
		{
			$Nationality_TH = "'อื่นๆ'";
			$COUNTRY_TH = "NULL";
		}
		else
		{
			if($Nationality != "")
			{
				$Nationality_TH = "'อื่นๆ'";
				$COUNTRY_TH = "NULL";
			}
			else
			{
				$Nationality_TH = "NULL";
				$COUNTRY_TH = "NULL";
			}
		}
		
		$sql_vncb = pg_query("select * from public.\"VNCB\" where replace(replace(\"N_IDCARD\",'-',''),' ','') = '$ID_Number_Chk'");
		$numChkVncb = pg_num_rows($sql_vncb);
		if($numChkVncb > 0)
		{
			//--- เริ่มบันทึกข้อมูล
			while($resVncb = pg_fetch_array($sql_vncb))
			{
				$CusID = $resVncb["CusID"];
				
				// หาค่าที่แก้ไขครั้งล่าสุด
				$sql_MaxCusEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID'");
				while($res_MaxCusEdit = pg_fetch_array($sql_MaxCusEdit))
				{
					$MaxCusEdit = $res_MaxCusEdit["edittime"];
				}
				$MaxCusEdit++;
				
				$insert_Customer_Temp = "INSERT INTO public.\"Customer_Temp\"(\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\",\"A_NAME\"
																	,\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
																	,\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\"
																	,\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\"
																	,\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\",\"N_CARDREF\")
										VALUES('$CusID','000','$add_date','000','$add_date','1','$MaxCusEdit',$Title,$fname
												,$sname,$Spouse_Name,$Address_NO,$Address_SUBNO,$Address_SOI,$Address_RD,$Subdistrict,$District,$Province,$Postal_Code
												,$Nationality_TH,NULL,'บัตรประชาชน',$ID_Number,NULL,NULL,NULL,NULL,'0',NULL
												,NULL,NULL,NULL,$Marital_Status,NULL,NULL,$COUNTRY_TH,NULL,NULL,NULL
												,$Date_of_Birth,$Gender,$Country,NULL)";
				if($result = pg_query($insert_Customer_Temp)){
				}else{
					$status++;
				}
				
				$update_Fa1 = "update public.\"Fa1\" set \"A_FIRNAME\" = $Title , \"A_NAME\" = $fname , \"A_SIRNAME\" = $sname , \"A_PAIR\" = $Spouse_Name , \"A_NO\" = $Address_NO
																	,\"A_SUBNO\" = $Address_SUBNO , \"A_SOI\" = $Address_SOI , \"A_RD\" = $Address_RD , \"A_TUM\" = $Subdistrict
																	,\"A_AUM\" = $District , \"A_PRO\" = $Province , \"A_POST\" = $Postal_Code , \"A_STATUS\" = $Marital_Status
																	,\"A_COUNTRY\" = $COUNTRY_TH , \"A_BIRTHDAY\" = $Date_of_Birth , \"A_SEX\" = $Gender , \"addr_country\" = $Country
										where \"CusID\" = '$CusID' ";
				if($result = pg_query($update_Fa1)){
				}else{
					$status++;
				}
				
				$update_Fn = "update public.\"Fn\" set \"N_SAN\" = $Nationality_TH , \"N_CARD\" = 'บัตรประชาชน' , \"N_IDCARD\" = $ID_Number
							where \"CusID\" = '$CusID' ";
				if($result = pg_query($update_Fn)){
				}else{
					$status++;
				}
				
				if($ownership_indicator == 0)
				{
					// หาค่าที่แก้ไขครั้งล่าสุด
					$sql_MaxConEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"thcap_addrContractID_temp\" where \"contractID\" = '$current_or_new_account_number'");
					while($res_MaxConEdit = pg_fetch_array($sql_MaxConEdit))
					{
						$MaxConEdit = $res_MaxConEdit["edittime"];
					}
					$MaxConEdit++;
					
					$insert_thcap_addrContractID_temp = "INSERT INTO public.\"thcap_addrContractID_temp\"(\"contractID\",\"addsType\",\"edittime\",\"A_NO\",\"A_SUBNO\",\"A_BUILDING\",\"A_ROOM\"
																										,\"A_FLOOR\",\"A_VILLAGE\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
																										,\"addUser\",\"addStamp\",\"statusApp\",\"appUser\",\"appStamp\")
														VALUES('$current_or_new_account_number','1','$MaxConEdit',$Address_NO,$Address_SUBNO,$Address_BUILDING,$Address_ROOM
																,$Address_FLOOR,$Address_VILLAGE,$Address_SOI,$Address_RD,$Subdistrict,$District,$Province,$Postal_Code
																,'000','$add_date','1','000','$add_date')";
					if($result = pg_query($insert_thcap_addrContractID_temp)){
					}else{
						$status++;
					}
					
					$update_thcap_addrContractID = "update public.\"thcap_addrContractID\" set \"addsType\" = '1' , \"A_NO\" = $Address_NO , \"A_SUBNO\" = $Address_SUBNO
														, \"A_BUILDING\" = $Address_BUILDING , \"A_ROOM\" = $Address_ROOM ,\"A_FLOOR\" = $Address_FLOOR , \"A_VILLAGE\" = $Address_VILLAGE
														, \"A_SOI\" = $Address_SOI , \"A_RD\" = $Address_RD ,\"A_TUM\" = $Subdistrict , \"A_AUM\" = $District , \"A_PRO\" = $Province
														, \"A_POST\" = $Postal_Code
													where \"contractID\" = '$current_or_new_account_number' ";
					if($result = pg_query($update_thcap_addrContractID)){
					}else{
						$status++;
					}
				}
			}
		}
	}
}

if($status==0)
{
	pg_query("COMMIT");
	//pg_query("ROLLBACK"); // test
	echo "<br><center><h2>บันทึกสำเร็จ</h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center><h2>บันทึกผิดพลาด</h2></center>";
}
?>