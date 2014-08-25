<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");
include("include/function.php");
//pg_query("BEGIN WORK");
$status = 0;
//$sql_fc=mssql_query("select *,CONVERT(varchar(4), YEAR(CardDate)) + '-' + CONVERT(varchar(2), MONTH(CardDate)) + '-' + CONVERT(varchar(2), DAY(CardDate)) + ' ' + CONVERT(varchar, CardDate, 8) AS CardDate ,"
//."CONVERT(varchar(4), YEAR(CarDate)) + '-' + CONVERT(varchar(2), MONTH(CarDate)) + '-' + CONVERT(varchar(2), DAY(CarDate)) + ' ' + CONVERT(varchar, CarDate, 8) AS CarDate  "
//."from TacCusDtl",$conn);

$sql_fc=mssql_query("SELECT CusID, OldCusID, CusType, PreName, Name, SurName, Age, CardType, CardID, Add1NO, Add1SubNO, Add1Soi, Add1Rd, Add1Tum, 
                      Add1Aum, Add1Prov, Add1AreaCode, Add1Tel, Add1Fax, Add1Mobile, Add2No, Add2SubNo, Add2Soi, Add2Rd, Add2Tum, Add2Aum, Add2Prov, 
                      Add2AreaCode, Add2Tel, Add2Fax, Add2Mobile, Add3No, Add3SubNo, Add3Soi, Add3Rd, Add3Tum, Add3Aum, Add3Prov, Add3AreaCode, Add3Tel, 
                      Add3Fax, Add3Mobile, SignDate, CarName, CarRegis, CarNum, Garage, GarTel, SalePrice, HirePurchase, ProveContact, CustRemark, 
                      BadDebt, BadDebtDate, Notice, NoticeDate, CONVERT(varchar(4), YEAR(CardDate)) + '-' + CONVERT(varchar(2), MONTH(CardDate)) 
                      + '-' + CONVERT(varchar(2), DAY(CardDate))  AS CardDate, CONVERT(varchar(4), YEAR(CarDate)) 
                      + '-' + CONVERT(varchar(2), MONTH(CarDate)) + '-' + CONVERT(varchar(2), DAY(CarDate)) AS CarDate
FROM         TacCusDtl",$conn);
while($res_fc = mssql_fetch_array($sql_fc)){
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$OldCusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["OldCusID"]));
	$CusType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusType"]));
	$PreName=trim(iconv('WINDOWS-874','UTF-8',$res_fc["PreName"]));
	$Name=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Name"]));
	$SurName=trim(iconv('WINDOWS-874','UTF-8',$res_fc["SurName"]));
	$Age=trim($res_fc["Age"]);
	$CardType=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CardType"]));
	$CardID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CardID"]));
	$CardDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CardDate"]));
	$Add1NO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1NO"]));
	$Add1SubNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1SubNO"]));
	$Add1Soi=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Soi"]));
	$Add1Rd=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Rd"]));
	$Add1Tum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Tum"]));
	$Add1Aum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Aum"]));
	$Add1Prov=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Prov"]));
	$Add1AreaCode=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1AreaCode"]));
	$Add1Tel=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Tel"]));
	$Add1Fax=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Fax"]));
	$Add1Mobile=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add1Mobile"]));
	
	$Add2NO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2No"]));
	$Add2SubNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2SubNo"]));
	$Add2Soi=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Soi"]));
	$Add2Rd=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Rd"]));
	$Add2Tum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Tum"]));
	$Add2Aum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Aum"]));
	$Add2Prov=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Prov"]));
	$Add2AreaCode=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2AreaCode"]));
	$Add2Tel=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Tel"]));
	$Add2Fax=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Fax"]));
	$Add2Mobile=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Mobile"]));
	
	$Add3NO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3No"]));
	$Add3SubNO=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3SubNo"]));
	$Add3Soi=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Soi"]));
	$Add3Rd=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Rd"]));
	$Add3Tum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Tum"]));
	$Add3Aum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Aum"]));
	$Add3Prov=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Prov"]));
	$Add3AreaCode=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3AreaCode"]));
	$Add3Tel=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Tel"]));
	$Add3Fax=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Fax"]));
	$Add3Mobile=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3Mobile"]));
	
	$SignDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["SignDate"]));
	$CarName=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CarName"]));
	$CarRegis=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CarRegis"]));
	$CarNum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CarNum"]));
	$CarDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CarDate"]));
	$Garage=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Garage"]));
	$GarTel=trim(iconv('WINDOWS-874','UTF-8',$res_fc["GarTel"]));
	$SalePrice=trim($res_fc["SalePrice"]);
	$HirePurchase=trim($res_fc["HirePurchase"]);
	$ProveContact=trim($res_fc["ProveContact"]);
	$CustRemark=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CustRemark"]));
	$BadDebt=trim($res_fc["BadDebt"]);
	$BadDebtDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["BadDebtDate"]));
	$Notice=trim($res_fc["Notice"]);
	$NoticeDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["NoticeDate"]));
	
	
	
	$query = pg_query("select car_id from \"Cars2\" where \"car_num\" = '$CarNum'"); //เช็คว่าcar_numซ้ำรึเปล่า
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		//'' Field ว่าง ให้เอาออก ค่าจะได้เป็น Null
		$car_id = GetCarID();
		
	/*	$ins="insert into \"Cars2\" (\"car_id\", \"car_name\", \"car_num\", \"mar_num\", \"car_year\", \"color\",\"license_plate\", \"regis_by\", \"regis_date\") 
          values  
          ('$car_id','$CarName','$CarNum','','','','$CarRegis','','$CarDate')";*/
		  
		  $ins="insert into \"Cars2\" (\"car_id\", \"car_name\", \"car_num\",\"license_plate\", \"regis_date\") 
          values  
          ('$car_id','$CarName','$CarNum','$CarRegis','$CarDate')";
		
				if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	}

		
/*
			$ins="insert into taxiacc.\"TacCusDtl\" (\"CusID\",\"OldCusID\",\"CusType\",\"PreName\",\"Name\",\"SurName\",\"Age\",\"CardType\",\"CardID\",\"CardDate\",
			\"Add1NO\",\"Add1SubNO\",\"Add1Soi\",\"Add1Rd\",\"Add1Tum\",\"Add1Aum\",\"Add1Prov\",\"Add1AreaCode\",\"Add1Tel\",\"Add1Fax\",\"Add1Mobile\",
			\"Add2No\",\"Add2SubNo\",\"Add2Soi\",\"Add2Rd\",\"Add2Tum\",\"Add2Aum\",\"Add2Prov\",\"Add2AreaCode\",\"Add2Tel\",\"Add2Fax\",\"Add2Mobile\",
			\"Add3No\",\"Add3SubNo\",\"Add3Soi\",\"Add3Rd\",\"Add3Tum\",\"Add3Aum\",\"Add3Prov\",\"Add3AreaCode\",\"Add3Tel\",\"Add3Fax\",\"Add3Mobile\",
			\"SignDate\",\"CarName\",\"CarRegis\",\"CarNum\",\"CarDate\",\"Garage\",\"GarTel\",\"SalePrice\",\"HirePurchase\",\"ProveContact\",\"CustRemark\",
			\"BadDebt\",\"BadDebtDate\",\"Notice\",\"NoticeDate\") values 
			('$CusID','$OldCusID','$CusType','$PreName','$Name','$SurName','$Age','$CardType','$CardID','$CardDate',
			'$Add1NO','$Add1SubNO','$Add1Soi','$Add1Rd','$Add1Tum','$Add1Aum','$Add1Prov','$Add1AreaCode','$Add1Tel','$Add1Fax','$Add1Mobile',
			'$Add2No','$Add2SubNo','$Add2Soi','$Add2Rd','$Add2Tum','$Add2Aum','$Add2Prov','$Add2AreaCode','$Add2Tel','$Add2Fax','$Add2Mobile',
			'$Add3No','$Add3SubNo','$Add3Soi','$Add3Rd','$Add3Tum','$Add3Aum','$Add3Prov','$Add3AreaCode','$Add3Tel','$Add3Fax','$Add3Mobile',
			'$SignDate','$CarName','$CarRegis','$CarNum','$CarDate','$Garage','$GarTel','$SalePrice','$HirePurchase','$ProveContact','$CustRemark',
			'$BadDebt','$BadDebtDate','$Notice','$NoticeDate')";
		}*/

	
	
		$query = pg_query("select cus_id from \"Customers2\" where \"cus_name\" = '$Name' and \"surname\" = '$SurName' and \"card_id\"='$CardID' "); //เช็คว่าcar_numซ้ำรึเปล่า
	$num_row = pg_num_rows($query);

	if($num_row == 0){
		$cus_id = GetCusID();
		
		$address = $Add1NO.";".$Add1SubNO.";".$Add1Soi.";".$Add1Rd.";".$Add1Tum.";".$Add1Aum.";".$Add1Prov.";".$Add1AreaCode.";";	
		$contact_add = $Add2NO.";".$Add2SubNO.";".$Add2Soi.";".$Add2Rd.";".$Add2Tum.";".$Add2Aum.";".$Add2Prov.";".$Add2AreaCode.";";
		$let_addr = $Add3NO.";".$Add3SubNO.";".$Add3Soi.";".$Add3Rd.";".$Add3Tum.";".$Add3Aum.";".$Add3Prov.";".$Add3AreaCode.";";
		
		//ใส่เบอร์โทรติดต่อที่ไม่ซ้ำกัน
		if($Add1Tel!="" && $Add1Tel!="-"){
		$tel = $Add1Tel.",";
		}
		if($Add2Tel!="" && $Add2Tel!=$Add1Tel && $Add1Tel!="-"){
		$tel = $tel.$Add2Tel.",";		
		}
		if($Add3Tel!="" && $Add3Tel!=$Add1Tel && $Add3Tel!=$Add2Tel && $Add1Tel!="-"){
		$tel = $tel.$Add3Tel.",";		
		}
		if($Add1Mobile!="" && $Add1Mobile!=$Add1Tel && $Add1Mobile!=$Add2Tel && $Add1Mobile!=$Add3Tel && $Add1Tel!="-"){
		$tel = $tel.$Add1Mobile.",";		
		}
		if($Add2Mobile!="" && $Add2Mobile!=$Add1Tel && $Add2Mobile!=$Add2Tel && $Add2Mobile!=$Add3Tel && $Add2Mobile!=$Add1Mobile && $Add1Tel!="-"){
		$tel = $tel.$Add2Mobile.",";		
		}
		if($Add3Mobile!="" && $Add3Mobile!=$Add1Tel && $Add3Mobile!=$Add2Tel && $Add3Mobile!=$Add3Tel && $Add3Mobile!=$Add1Mobile && $Add3Mobile!=$Add3Mobile && $Add1Tel!="-"){
		$tel = $tel.$Add3Mobile.",";		
		}
		
		
		
		
		
		/*$ins="INSERT INTO \"Customers2\" (\"cus_id\",\"pre_name\",\"cus_name\",\"surname\",\"address\",\"add_post\",\"nationality\",\"birth_date\",\"card_type\",\"card_id\",\"card_do_date\",\"card_do_by\",\"job\",\"contact_add\",\"telephone\") values 
    ('$cus_id','$PreName','$Name','$SurName','$address','','','','$CardType','$CardID','$CardDate','','','$contact_add','$tel')";*/
	
	$ins="INSERT INTO \"Customers2\" (\"cus_id\",\"pre_name\",\"cus_name\",\"surname\",\"address\",\"card_type\",\"card_id\",\"card_do_date\",\"contact_add\",\"telephone\") values 
    ('$cus_id','$PreName','$Name','$SurName','$address','$CardType','$CardID','$CardDate','$contact_add','$tel')";
	
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	
	
	/*$ins="INSERT INTO \"LetterAddress2\"(
            \"cus_id\", \"change_date\", \"address\", \"user_id\")
    VALUES ('$cus_id','', '$let_addr','')";*/
			$query = pg_query("select cus_id from \"LetterAddress2\" where \"cus_id\" = '$cus_id' "); //เช็คว่าcus_idซ้ำรึเปล่า
	$num_row = pg_num_rows($query);

	if($num_row == 0){
	$ins="INSERT INTO \"LetterAddress2\"(
            \"cus_id\", \"address\")
    VALUES ('$cus_id', '$let_addr')";

		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	}
	
	}
}
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

