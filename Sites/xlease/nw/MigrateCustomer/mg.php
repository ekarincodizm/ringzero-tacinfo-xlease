<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$status=0;
//ดึงข้อมูลลูกค้าที่ไม่มีที่อยู่
$sql= "SELECT * FROM \"Fa1\"  where 
\"A_NO\" is null and \"A_SUBNO\" is null and \"A_SOI\" is null and \"A_RD\" is null and \"A_TUM\" is null and \"A_AUM\" is null and \"A_PRO\" is null and \"A_POST\" is null";
$query = pg_query($sql);
$rows = pg_num_rows($query);

$i = 1;
pg_query("BEGIN");
while($re = pg_fetch_array($query)){
	
	$cusid =trim($re['CusID']); //ตรวจสอบหมายเลขบัตรประชาชนจาก CusID
	
	$sql1 = "SELECT \"N_IDCARD\" FROM \"Fn\" where \"CusID\" = '$cusid' ";
	$query1 = pg_query($sql1);
	$re1 = pg_fetch_array($query1);
	$idcard = trim($re1['N_IDCARD']);	
	$idcardref = trim($re1['N_CARDREF']);
	
	//ดึงข้อมูลที่ต้องการเพิ่มให้แก่ลูกค้าที่ไม่มีข้อมูล
		$sql2 = "SELECT \"contractID\", address, subdistrict, district, province,country, postal_code
		FROM thcap_temp_ncbdata where \"CusIDNum\" = '$idcard' or \"CusIDNum\" = '$idcardref'";
		$query2 = pg_query($sql2);
		$re2 = pg_fetch_array($query2);
		$rows2 = pg_num_rows($query2);
		
		
		
	$num = 0;	
	if($rows2 == 0){

	}else{	
		$address = $re2['address'];
		
		//ค้นหาคำที่เขียนไม่เหมือนกันแต่ความหมายเดียวกัน ให้เป็นรูปแบบเดียวกัน
		$array = array(
		"ซ." => " ซอย  ",
		"ซอย" => " ซอย  ",
		"ถ." => " ถนน  ",
		"ถนน" => " ถนน  ",
		);
		$new_ms = strtr($address, $array);	
		$data = explode(" ",$new_ms); //ตัดช่องว่างออกแล้วใส่ตัวแปร array
		
		
		$a_no = "";
		$mhoo = "";
		$soi ="";
		$rd  ="";
		
		for($i=0;$i<=sizeof($data);$i++){			//ค้นหาบ้านเลขที่
			if($data[$i] == "ซอย" ||$data[$i] == "ถนน" ||$data[$i] == "หมู่" || $data[$i] == " "){
			
				$h = sizeof($data);
				$h++;
				$a = $i;
				$i = $h;
				
			
			}else{
			
			    $a_no = $a_no." ".$data[$i];
			}
		
		}
		$No[$num] = $a_no;	
		
		for($i=$a;$i<=sizeof($data);$i++){ //ค้นหา หมู่
		
			if($data[$i] == "ซอย" ||$data[$i] == "ถนน" || $data[$i] == " "){	
			
				$h = sizeof($data);
				$h++;
				$a = $i;
				$i = $h;
			
			}else{
			
			    $mhoo = $mhoo." ".$data[$i];
			}
		
		}		
		
		$Mho[$num] = str_replace("หมู่","",$mhoo);
				
		for($i=$a;$i<=sizeof($data);$i++){ //ค้นหาซอย
		
			if($data[$i] == "ถนน" || $data[$i] == "หมู่"){	
			
				$h = sizeof($data);
				$h++;
				$a = $i;
				$i = $h;
			
			}else{
			
			    $soi = $soi." ".$data[$i];
			}
		
		}
				
		$SOI[$num] = str_replace("ซอย","",$soi);
		
		
		for($i=$a;$i<=sizeof($data);$i++){ //ค้นหา ถนน
		
			if($data[$i] == "ซอย" || $data[$i] == "หมู่"){	
			
				$h = sizeof($data);
				$h++;
				$a = $i;
				$i = $h;
			
			}else{
			
			    $rd = $rd." ".$data[$i];
			}
		
		}
				
		$ROAD[$num] = str_replace("ถนน","",$rd);
		

		
		//ดึงข้อมูลลูกค้าทั้งหมดออกมาเพื่อหา ข้อมูลเก่า หากมีแล้วจะไม่เขียนทับลงไป
		$sql3 = "SELECT * FROM \"Fa1\" where \"CusID\" = '$cusid' ";
		$query3 = pg_query($sql3);
		$re3 = pg_fetch_array($query3);
		
		$CusID = trim($re3['CusID']);
		$A_FIRNAME = $re3['A_FIRNAME'];
		$A_NAME = $re3['A_NAME'];
		$A_SIRNAME = $re3['A_SIRNAME'];
		$A_PAIR = $re3['A_PAIR'];
		$A_NO = $re3['A_NO'];
		$A_SUBNO = $re3['A_SUBNO'];
		$A_SOI = $re3['A_SOI'];
		$A_RD = $re3['A_RD'];
		$A_TUM = $re3['A_TUM'];
		$A_AUM = $re3['A_AUM'];
		$A_PRO = $re3['A_PRO'];
		$A_POST = $re3['A_POST'];
		$Approved = $re3['Approved'];
		$A_FIRNAME_ENG = $re3['A_FIRNAME_ENG'];
		$A_NAME_ENG = $re3['A_NAME_ENG'];
		$A_SIRNAME_ENG = $re3['A_SIRNAME_ENG'];
		$A_NICKNAME = $re3['A_NICKNAME'];
		$A_STATUS = $re3['A_STATUS'];
		$A_REVENUE = $re3['A_REVENUE'];
		$A_EDUCATION = $re3['A_EDUCATION'];
		$A_COUNTRY = $re3['A_COUNTRY'];
		$A_MOBILE = $re3['A_MOBILE'];
		$A_TELEPHONE = $re3['A_TELEPHONE'];
		$A_EMAIL = $re3['A_EMAIL'];
		$A_BIRTHDAY = $re3['A_BIRTHDAY'];
		$A_SEX = $re3['A_SEX'];
		$addr_country = $re3['addr_country'];
		
		$sql4 = "SELECT * FROM \"Fn\" where \"CusID\" = '$cusid'";
		$query4 = pg_query($sql4);
		$re4 = pg_fetch_array($query4);
		
		$N_STATE = $re4['N_STATE'];
		$N_SAN = $re4['N_SAN'];
		$N_AGE = $re4['N_AGE'];
		$N_CARD = $re4['N_CARD'];
		$N_IDCARD = $re4['N_IDCARD'];
		$N_OT_DATE = $re4['N_OT_DATE'];
		$N_BY = $re4['N_BY'];
		$N_OCC = $re4['N_OCC'];
		$N_ContactAdd = $re4['N_ContactAdd'];
		$N_CARDREF = $re4['N_CARDREF'];
		
		//หากข้อมูลที่อยู่ว่างให้แทนด้วยที่อยู่ใหม่
		if($A_NO == ""){
		$A_NO = trim($No[$num]);
		}
		if($A_SUBNO == ""){
		$A_SUBNO = trim($Mho[$num]);
		}
		if($A_SOI == ""){
		$A_SOI = trim($SOI[$num]);
		}
		if($A_RD == ""){
		$A_RD = trim($ROAD[$num]);
		}
		if($A_TUM == ""){
		$A_TUM = $re2['subdistrict'];
		}
		if($A_AUM == ""){
		$A_AUM = $re2['district'];
		}
		if($A_PRO == ""){
		$A_PRO = $re2['province'];
		}
		if($A_POST == ""){
		$A_POST = $re2['postal_code'];
		}
		if($addr_country == ""){
		$addr_country = $re2['country'];
		}
		if($N_ContactAdd == ""){
		$N_ContactAdd = $A_NO." ".$A_SUBNO." ".$A_SOI." ".$A_RD." ".$A_TUM." ".$A_AUM." ".$A_PRO." ".$A_POST;
		}
		
		//ค้นหาจำนวนครั้งการแก้ไขของลูกค้า
		$qry_count=pg_query("select MAX(\"edittime\") as numtime from \"Customer_Temp\" where \"CusID\"='$cusid'");
		$num_count=pg_num_rows($qry_count);
		
		if($num_count==0){
			$countcus=1; //แสดงว่ามีการแก้ไขข้อมูลโดยไม่ได้เพิ่มข้อมูลจากเมนูใหม่ จึงกำหนดให้ edittime=1
		}else{
			$rescount=pg_fetch_array($qry_count);
			$countcus=$rescount["numtime"] + 1;
		}
		
		//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
		$CusID = checknull($CusID);
		$A_FIRNAME = checknull($A_FIRNAME );
		$A_NAME = checknull($A_NAME );
		$A_SIRNAME = checknull($A_SIRNAME );
		$A_PAIR = checknull($A_PAIR );
		$A_NO = checknull($A_NO );
		$A_SUBNO = checknull($A_SUBNO );
		$A_SOI = checknull($A_SOI );
		$A_RD = checknull($A_RD );
		$A_TUM = checknull($A_TUM );
		$A_AUM = checknull($A_AUM );
		$A_PRO = checknull($A_PRO );
		$A_POST = checknull($A_POST );
		$Approved = checknull($Approved );
		$A_FIRNAME_ENG = checknull($A_FIRNAME_ENG );
		$A_NAME_ENG = checknull($A_NAME_ENG );
		$A_SIRNAME_ENG = checknull($A_SIRNAME_ENG );
		$A_NICKNAME = checknull($A_NICKNAME );
		$A_STATUS = checknull($A_STATUS );
		$A_REVENUE = checknull($A_REVENUE );
		$A_EDUCATION = checknull($A_EDUCATION );
		$A_COUNTRY = checknull($A_COUNTRY );
		$A_MOBILE = checknull($A_MOBILE );
		$A_TELEPHONE = checknull($A_TELEPHONE );
		$A_EMAIL = checknull($A_EMAIL );
		$A_BIRTHDAY = checknull($A_BIRTHDAY );
		$A_SEX = checknull($A_SEX );
		$addr_country = checknull($addr_country );
		$N_STATE = checknull($N_STATE );
		$N_SAN = checknull($N_SAN );
		$N_AGE = checknull($N_AGE );
		$N_CARD = checknull($N_CARD );
		$N_IDCARD = checknull($N_IDCARD );
		$N_OT_DATE = checknull($N_OT_DATE );
		$N_BY = checknull($N_BY );
		$N_OCC = checknull($N_OCC );
		$N_ContactAdd = checknull($N_ContactAdd );
		$N_CARDREF = checknull($N_CARDREF );

		// บันทึกลงฐานข้อมูล
		$insert_Fa1tep="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"app_user\",\"add_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
					\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\",\"N_CARDREF\")
				VALUES ($CusID,'000','000','$add_date','1','$countcus',$A_FIRNAME, $A_NAME, $A_SIRNAME, $A_PAIR, $A_NO,
					$A_SUBNO, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, $A_POST,$N_SAN, $N_AGE, $N_CARD, $N_IDCARD, 
					$N_OT_DATE,$N_BY, $N_OCC, $N_ContactAdd,$N_STATE,$A_FIRNAME_ENG,$A_NAME_ENG,$A_SIRNAME_ENG,
					$A_NICKNAME,$A_STATUS,$A_REVENUE,$A_EDUCATION,$A_COUNTRY,$A_MOBILE,$A_TELEPHONE,$A_EMAIL,$A_BIRTHDAY,$A_SEX,$addr_country,$N_CARDREF)";


		// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
		if($result=pg_query($insert_Fa1tep)){
		}else{
			$status++;
			echo $insert_Fa1tep;
		}

		
			$insert_fa1="UPDATE \"Fa1\"
			SET   \"A_NO\"=$A_NO, \"A_SUBNO\"=$A_SUBNO, \"A_SOI\"=$A_SOI, \"A_RD\"=$A_RD, \"A_TUM\"=$A_TUM, \"A_AUM\"=$A_AUM, 
					\"A_PRO\"=$A_PRO, \"A_POST\"=$A_POST, addr_country=$addr_country
			WHERE \"CusID\" = '$cusid' ";
			
		if($result1=pg_query($insert_fa1)){
		}else{
			$status++;
			echo $insert_fa1;
		}


		
		
		$sqlcon = "SELECT \"contractID\" FROM \"Vthcap_ContactCus_detail\" where \"CusID\" = '$cusid'";
		$querycon = pg_query($sqlcon);
		while($recon = pg_fetch_array($querycon)){
		$contractID = trim($recon['contractID']);
		

		
		//ค้นหาคำที่เขียนไม่เหมือนกันแต่ความหมายเดียวกัน ให้เป็นรูปแบบเดียวกัน
		$array1 = array(		
		"'" => "",
		"หมู่บ้าน" => " "."หมู่บ้าน"." ",
		"ห้อง" => " "."ห้อง"." ",
		"อาคาร" => " "."อาคาร"." ",
		);
		 $new_ms1 = strtr($No[$num],$array1);
		
		$data1 = explode(" ",$new_ms1); //ตัดช่องว่างออกแล้วใส่ตัวแปร array
		
		$numberhome = $data1[1];
		$room = "";
		$floor = "";
		$village = "";
		$bulding = "";
		for($z=0;$z<sizeof($data1);$z++){ //หาห้องที่
		
			if($data1[$z] == "ห้อง"){
			$a = $z+2;
			$room = $data1[$a];
			$z=sizeof($data1);
			}
		}
		if($room != ""){
		$numberroom[$num] = $room;	
		}
		
		for($z=0;$z<sizeof($data1);$z++){ //หาชั้น
		
			if($data1[$z] == "ชั้น"){
			$a = $z+1;
			$floor = $data1[$a];
			$z=sizeof($data1);
			}
		}
		
		for($z=0;$z<sizeof($data1);$z++){ //หาหมู่บ้าน
		
			if($data1[$z] == "หมู่บ้าน"){
			$a = $z+1;
			$village = $data1[$a];
			$z=sizeof($data1);
			}
		}
		
		
		for($z=0;$z<sizeof($data1);$z++){ //หาชื่ออาคาร
		
			if($data1[$z] == "อาคาร"){
			$a = $z+1;
			$bulding = $data1[$a];
			$z=sizeof($data1);
			}
		}
		$numberhome = checknull($numberhome);
		$numberroom[$num] = checknull($room);
		$numberfloor[$num] = checknull($floor);
		$namevillage[$num] = checknull($village);
		$namebulding[$num] = checknull($bulding);
		
		//อัพเดตที่อยู่
		$insert_addr = "UPDATE \"thcap_addrContractID\"
	SET   \"A_NO\"=$numberhome, \"A_SUBNO\"=$A_SUBNO, \"A_BUILDING\"=$namebulding[$num], \"A_ROOM\"=$numberroom[$num], \"A_FLOOR\"=$numberfloor[$num], \"A_VILLAGE\"=$namevillage[$num],
	\"A_SOI\"=$A_SOI, \"A_RD\"=$A_RD,\"A_TUM\"=$A_TUM, \"A_AUM\"=$A_AUM, \"A_PRO\"=$A_PRO, \"A_POST\"=$A_POST
	WHERE \"contractID\" = '$contractID'";
		
		if($result2=pg_query($insert_addr)){
		}else{
			$status++;
			echo $insert_addr;
		}
		
		
		$insert_addr_tep = "INSERT INTO \"thcap_addrContractID_temp\"(
     		\"contractID\", \"addsType\", edittime, \"A_NO\", \"A_SUBNO\", 
            \"A_BUILDING\", \"A_ROOM\", \"A_FLOOR\", \"A_VILLAGE\", \"A_SOI\", \"A_RD\", 
            \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"addUser\", \"addStamp\", \"statusApp\", 
            \"appUser\", \"appStamp\")
    VALUES ('$contractID','3','1',$numberhome,$A_SUBNO,
			$namebulding[$num],$numberroom[$num],$numberfloor[$num],$namevillage[$num],$A_SOI,$A_RD,
			$A_TUM,$A_AUM,$A_PRO,$A_POST,'000','$add_date','1','000','$add_date')";
			
			
		if($result3=pg_query($insert_addr_tep)){
		}else{
			$status++;
			echo $insert_addr_tep;
		}
	}		
		
	}	
	
	
	$num++;
}

if($status==0){
	pg_query("COMMIT");
	echo "<p>";
	echo "Success";

}else{

	pg_query("ROLLBACK");
	echo "<p>";
	echo "Error";
}
?>