<?php
include('../config/config.php');

if($_REQUEST['conn']=='av')
{ 
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=Fpdata_av.csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	//first of all I'll get the column name to put title of csv file.
	$query = "select p.\"IDNO\",a.\"A_NAME\",a.\"A_SIRNAME\",n.\"N_CARD\",n.\"N_IDCARD\",n.\"N_CARDREF\",c.\"C_REGIS\",a.\"A_MOBILE\",a.\"A_TELEPHONE\"
			from \"Fp\" p
			left join \"Fa1\" a on a.\"CusID\" = p.\"CusID\"
			left join \"Fn\" n on n.\"CusID\" = p.\"CusID\"
			left join \"Fc\" c on c.\"CarID\" = p.asset_id
			where \"P_ACCLOSE\" = false and p.\"IDNO\" not like '5%' and c.\"C_REGIS\" like 'ท%' ";
	$headers=pg_query($query);
	while($row=pg_fetch_array($headers))
	{
		//ข้อมูลเลขที่บัตร
		$N_CARD = trim($row['N_CARD']); // ประเภทบัตร
		$N_IDCARD = trim($row['N_IDCARD']); // เลขที่บัตรประชาชน
		$N_CARDREF = trim($row['N_CARDREF']); // เลขที่บัตรอื่นๆ
		
		// ตรวจสอบเลขที่บัตร ถ้ามีบัตรประชาชน ให้ใช้บัตรประชาชน ถ้าไม่มีให้ใช้บัตรอื่นๆ ถ้าไม่มีอีก ให้แสดง ไม่มีข้อมูลเลขที่บัตร
		if($N_IDCARD != "")
		{
			$N_CARD_USE = $N_IDCARD;
			$N_CARD = "บัตรประชาชน";
		}
		elseif($N_CARDREF != "")
		{
			$N_CARD_USE = $N_CARDREF;
		}
		else
		{
			$N_CARD_USE = "ไม่มีข้อมูลเลขที่บัตร";
		}
		
		//ข้อมูลโทรศัพท์
		$A_MOBILE = trim($row['A_MOBILE']);
		$A_TELEPHONE = trim($row['A_TELEPHONE']);
		
		//ตรวจสอบ ว่ามีข้อมูลโทรศัพท์มือถือ ถ้าไม่มีให้เอาโทรศัพท์บ้าน ถ้าไม่มีอีกให้ขึ้นว่า ไม่มีข้อมูลเบอร์โทรศัพท์
		if(($A_MOBILE=="") OR ($A_MOBILE=="-") OR ($A_MOBILE=="--") OR ($A_MOBILE==null))
		{
			if(($A_TELEPHONE=="") OR ($A_TELEPHONE=="-") OR ($A_TELEPHONE=="--") OR ($A_TELEPHONE==null)){
			$qry_IDCARD = pg_query("select \"N_IDCARD\" from \"Fn\" WHERE  \"CusID\"='$CusID' AND \"N_IDCARD\" IS NOT NULL");
			list($N_IDCARD)=pg_fetch_array($qry_IDCARD);
			if($N_IDCARD==''){
				$qry_Contact = pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"CusID\"='$CusID'");
			}
			else {				
				$qry_Contact = pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"N_IDCARD\"='$N_IDCARD'");					
			}			
			$count=0;
				while($res_ContactAdd = pg_fetch_array($qry_Contact)){
					$N_ContactAdd = trim($res_ContactAdd['N_ContactAdd']);
				//1.กรณีที่เป็น มือถือ
				//format 089-1111111
				$phone_format_1 = '/(08|09|06)(\d{1})-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_1,$N_ContactAdd,$match))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 089-111-1111
				$phone_format_2 = '/(08|09|06)(\d{1})-(\d{3})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_2,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}	
				//format 0891111111
				$phone_format_3 = '/(08|09|06)(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_3,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//011111111
				$phone_format_4 = '/0(1|3|4|5|6|7|8|9)(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_4,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;
					}
				}
				//01-111-1111
				$phone_format_5 = '/0(1|3|4|5|6|7|8|9)-(\d{3})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_5,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;						
					}
				}
				
				// กรณี มือถือ แบบเก่า  01-1111111
				$phone_format_6 = '/0(1|3|4|5|6|7|8|9)-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_6,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;
					}
				}
				
				//2.กรณีที่เป็น บ้าน
				//02-965-7759
				$phone_format_7 = '/02-(\d{3})-(\d{4})/';
				
				if($count==0){
					if (preg_match($phone_format_7,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//02-7777777
				$phone_format_8 = '/02-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_8,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//027777777
				$phone_format_9 = '/02-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_9,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 038-111111
				$phone_format_10 = '/0(\d{2})-(\d{6})/';
				if($count==0){
					if (preg_match($phone_format_10,$N_ContactAdd,$match))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 038-111-111
				$phone_format_11 = '/0(\d{2})-(\d{3})-(\d{3})/';
				if($count==0){
					if (preg_match($phone_format_11,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}	
				//format 038111111
				$phone_format_12 = '/0(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_12,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 0-2222-2222
				$phone_format_13 = '/0-(\d{4})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_13,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 0-22222222
				$phone_format_14 = '/0-(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_14,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				
				if($count==0){
					$mobile_telephone="ไม่มีข้อมูลเบอร์โทรศัพท์";	
					
				}
				}
			}
			else{
				$mobile_telephone=$A_TELEPHONE;
			}
		}
		else
		{
			$mobile_telephone=$A_MOBILE;
		}
		//จบ
		
		// ข้อมูลรายงาน
		$_csv_data .=trim($row['IDNO']).','.trim($row['A_NAME']).','.trim($row['A_SIRNAME']).','.$N_CARD_USE.','.trim($row['C_REGIS']).','.$mobile_telephone.','.$N_CARD."\n";
    }
    echo $_csv_data;
}
else if($_REQUEST['conn']=='tha')
{
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=Fpdata_ta.csv");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	//first of all I'll get the column name to put title of csv file.
	$query = "select p.\"IDNO\",a.\"A_NAME\",a.\"A_SIRNAME\",n.\"N_CARD\",n.\"N_IDCARD\",n.\"N_CARDREF\",c.\"C_REGIS\",a.\"A_MOBILE\",a.\"A_TELEPHONE\"
			from \"Fp\" p
			left join \"Fa1\" a on a.\"CusID\" = p.\"CusID\"
			left join \"Fn\" n on n.\"CusID\" = p.\"CusID\"
			left join \"Fc\" c on c.\"CarID\" = p.asset_id
			where \"P_ACCLOSE\" = false and p.\"IDNO\" not like '5%' and c.\"C_REGIS\" like 'ท%' ";
	$headers=pg_query($query);
	while($row=pg_fetch_array($headers))
	{
		//ข้อมูลเลขที่บัตร
		$N_CARD = trim($row['N_CARD']); // ประเภทบัตร
		$N_IDCARD = trim($row['N_IDCARD']); // เลขที่บัตรประชาชน
		$N_CARDREF = trim($row['N_CARDREF']); // เลขที่บัตรอื่นๆ
		
		// ตรวจสอบเลขที่บัตร ถ้ามีบัตรประชาชน ให้ใช้บัตรประชาชน ถ้าไม่มีให้ใช้บัตรอื่นๆ ถ้าไม่มีอีก ให้แสดง ไม่มีข้อมูลเลขที่บัตร
		if($N_IDCARD != "")
		{
			$N_CARD_USE = $N_IDCARD;
			$N_CARD = "บัตรประชาชน";
		}
		elseif($N_CARDREF != "")
		{
			$N_CARD_USE = $N_CARDREF;
		}
		else
		{
			$N_CARD_USE = "ไม่มีข้อมูลเลขที่บัตร";
		}
		
		//ข้อมูลโทรศัพท์
		$A_MOBILE = trim($row['A_MOBILE']);
		$A_TELEPHONE = trim($row['A_TELEPHONE']);
		
		//ตรวจสอบ ว่ามีข้อมูลโทรศัพท์มือถือ ถ้าไม่มีให้เอาโทรศัพท์บ้าน ถ้าไม่มีอีกให้ขึ้นว่า ไม่มีข้อมูลเบอร์โทรศัพท์
		if(($A_MOBILE=="") OR ($A_MOBILE=="-") OR ($A_MOBILE=="--") OR ($A_MOBILE==null))
		{
			if(($A_TELEPHONE=="") OR ($A_TELEPHONE=="-") OR ($A_TELEPHONE=="--") OR ($A_TELEPHONE==null)){
			$qry_IDCARD = pg_query("select \"N_IDCARD\" from \"Fn\" WHERE  \"CusID\"='$CusID' AND \"N_IDCARD\" IS NOT NULL");
			list($N_IDCARD)=pg_fetch_array($qry_IDCARD);
			if($N_IDCARD==''){
				$qry_Contact = pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"CusID\"='$CusID'");
			}
			else {				
				$qry_Contact = pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"N_IDCARD\"='$N_IDCARD'");					
			}			
			$count=0;
				while($res_ContactAdd = pg_fetch_array($qry_Contact)){
					$N_ContactAdd = trim($res_ContactAdd['N_ContactAdd']);
				//1.กรณีที่เป็น มือถือ
				//format 089-1111111
				$phone_format_1 = '/(08|09|06)(\d{1})-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_1,$N_ContactAdd,$match))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 089-111-1111
				$phone_format_2 = '/(08|09|06)(\d{1})-(\d{3})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_2,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}	
				//format 0891111111
				$phone_format_3 = '/(08|09|06)(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_3,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//011111111
				$phone_format_4 = '/0(1|3|4|5|6|7|8|9)(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_4,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;
					}
				}
				//01-111-1111
				$phone_format_5 = '/0(1|3|4|5|6|7|8|9)-(\d{3})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_5,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;						
					}
				}
				
				// กรณี มือถือ แบบเก่า  01-1111111
				$phone_format_6 = '/0(1|3|4|5|6|7|8|9)-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_6,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;
					}
				}
				
				//2.กรณีที่เป็น บ้าน
				//02-965-7759
				$phone_format_7 = '/02-(\d{3})-(\d{4})/';
				
				if($count==0){
					if (preg_match($phone_format_7,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//02-7777777
				$phone_format_8 = '/02-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_8,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//027777777
				$phone_format_9 = '/02-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_9,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 038-111111
				$phone_format_10 = '/0(\d{2})-(\d{6})/';
				if($count==0){
					if (preg_match($phone_format_10,$N_ContactAdd,$match))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 038-111-111
				$phone_format_11 = '/0(\d{2})-(\d{3})-(\d{3})/';
				if($count==0){
					if (preg_match($phone_format_11,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}	
				//format 038111111
				$phone_format_12 = '/0(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_12,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 0-2222-2222
				$phone_format_13 = '/0-(\d{4})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_13,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 0-22222222
				$phone_format_14 = '/0-(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_14,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				
				if($count==0){
					$mobile_telephone="ไม่มีข้อมูลเบอร์โทรศัพท์";	
					
				}
				}
			}
			else{
				$mobile_telephone=$A_TELEPHONE;
			}
		}
		else
		{
			$mobile_telephone=$A_MOBILE;
		}
		//จบ
		
		// ข้อมูลรายงาน
		$_csv_data .=trim($row['IDNO']).','.trim($row['A_NAME']).','.trim($row['A_SIRNAME']).','.$N_CARD_USE.','.trim($row['C_REGIS']).','.$mobile_telephone.','.$N_CARD."\n";
    }
    echo $_csv_data;
}
else
{
    echo "Unknow";
}
?>