<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select * from TacCusDtl",$conn);
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
	
	$Add2No=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2No"]));
	$Add2SubNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2SubNo"]));
	$Add2Soi=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Soi"]));
	$Add2Rd=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Rd"]));
	$Add2Tum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Tum"]));
	$Add2Aum=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Aum"]));
	$Add2Prov=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Prov"]));
	$Add2AreaCode=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2AreaCode"]));
	$Add2Tel=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Tel"]));
	$Add2Fax=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Fax"]));
	$Add2Mobile=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add2Mobile"]));
	
	$Add3No=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3No"]));
	$Add3SubNo=trim(iconv('WINDOWS-874','UTF-8',$res_fc["Add3SubNo"]));
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
	
	$query = pg_query("select * from taxiacc.\"TacCusDtl\" where \"CusID\" = '$CusID'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){
		if($Age == ""){
			$ins="insert into taxiacc.\"TacCusDtl\" (\"CusID\",\"OldCusID\",\"CusType\",\"PreName\",\"Name\",\"SurName\",\"Age\",\"CardType\",\"CardID\",\"CardDate\",
			\"Add1NO\",\"Add1SubNO\",\"Add1Soi\",\"Add1Rd\",\"Add1Tum\",\"Add1Aum\",\"Add1Prov\",\"Add1AreaCode\",\"Add1Tel\",\"Add1Fax\",\"Add1Mobile\",
			\"Add2No\",\"Add2SubNo\",\"Add2Soi\",\"Add2Rd\",\"Add2Tum\",\"Add2Aum\",\"Add2Prov\",\"Add2AreaCode\",\"Add2Tel\",\"Add2Fax\",\"Add2Mobile\",
			\"Add3No\",\"Add3SubNo\",\"Add3Soi\",\"Add3Rd\",\"Add3Tum\",\"Add3Aum\",\"Add3Prov\",\"Add3AreaCode\",\"Add3Tel\",\"Add3Fax\",\"Add3Mobile\",
			\"SignDate\",\"CarName\",\"CarRegis\",\"CarNum\",\"CarDate\",\"Garage\",\"GarTel\",\"SalePrice\",\"HirePurchase\",\"ProveContact\",\"CustRemark\",
			\"BadDebt\",\"BadDebtDate\",\"Notice\",\"NoticeDate\") values 
			('$CusID','$OldCusID','$CusType','$PreName','$Name','$SurName',null,'$CardType','$CardID','$CardDate',
			'$Add1NO','$Add1SubNO','$Add1Soi','$Add1Rd','$Add1Tum','$Add1Aum','$Add1Prov','$Add1AreaCode','$Add1Tel','$Add1Fax','$Add1Mobile',
			'$Add2No','$Add2SubNo','$Add2Soi','$Add2Rd','$Add2Tum','$Add2Aum','$Add2Prov','$Add2AreaCode','$Add2Tel','$Add2Fax','$Add2Mobile',
			'$Add3No','$Add3SubNo','$Add3Soi','$Add3Rd','$Add3Tum','$Add3Aum','$Add3Prov','$Add3AreaCode','$Add3Tel','$Add3Fax','$Add3Mobile',
			'$SignDate','$CarName','$CarRegis','$CarNum','$CarDate','$Garage','$GarTel','$SalePrice','$HirePurchase','$ProveContact','$CustRemark',
			'$BadDebt','$BadDebtDate','$Notice','$NoticeDate')";
		}else{
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
		}
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	}
	
}
if($status == 0){
    pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

