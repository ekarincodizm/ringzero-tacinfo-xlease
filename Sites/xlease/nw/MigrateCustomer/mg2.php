<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$status=0;

$sql= "SELECT * FROM \"thcap_addrContractID\"  where 
\"A_NO\" is null and \"A_SUBNO\" is null and \"A_SOI\" is null and \"A_RD\" is null and \"A_TUM\" is null and \"A_AUM\" is null and \"A_PRO\" is null and \"A_POST\" is null";
$query = pg_query($sql);
pg_query("BEGIN");
while($re = pg_fetch_array($query)){
	
echo $conID = $re['contractID'];
$num = 0;

	$sql1 = "SELECT \"CusID\" FROM \"thcap_ContactCus\" where \"contractID\" = '$conID' and \"CusState\" = 0";
	$query1 = pg_query($sql1);
	$re1 = pg_fetch_array($query1);
	
	$cusID = $re1['CusID'];
	
	$sql2 = "SELECT \"N_IDCARD\" FROM \"Fn\" where \"CusID\" = '$cusID'";
	$query2 = pg_query($sql2);
	$re2 = pg_fetch_array($query2);
	
	$idcard = $re2['N_IDCARD'];
	$idcard = str_replace(" ","",$idcard);
	
	
	$sql3 = "SELECT * FROM thcap_temp_ncbdata where \"CusIDNum\" = '$idcard'";
	$query3 = pg_query($sql3);
	$re3 = pg_fetch_array($query3);
	echo " ";
	echo $address = $re3['address'];
	echo $subdistrict = $re3['subdistrict'];
	echo $district = $re3['district'];
	echo $province = $re3['province'];
	echo $country = $re3['country'];
	echo $postal_code = $re3['postal_code'];
	echo "<p>";
	
	//ค้นหาคำที่เขียนไม่เหมือนกันแต่ความหมายเดียวกัน ให้เป็นรูปแบบเดียวกัน
		$array = array(
		"ซ." => " ซอย  ",
		"ซอย" => " ซอย  ",
		"ถ." => " ถนน  ",
		"ถนน" => " ถนน  ",
		"บ." => " บ้าน  ",
		"หมู่บ้าน" => " หมู่บ้าน  ",
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
		
			if($data[$i] == "ซอย" ||$data[$i] == "ถนน" || $data[$i] == " " || $data[$i] == "หมู่บ้าน"){	
			
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
	
	
		
		$A_NO = trim($No[$num]);
	
		$A_SUBNO = trim($Mho[$num]);
		
		$A_SOI = trim($SOI[$num]);
		
		$A_RD = trim($ROAD[$num]);
		
		$A_TUM = $re3['subdistrict'];
		
		$A_AUM = $re3['district'];
		
		$A_PRO = $re3['province'];

		$A_POST = $re3['postal_code'];
		
		
	
		$A_NO = checknull($A_NO);
		$A_SUBNO = checknull($A_SUBNO);
		$A_SOI = checknull($A_SOI);
		$A_RD = checknull($A_RD);
		$A_TUM = checknull($A_TUM);
		$A_AUM = checknull($A_AUM);
		$A_PRO = checknull($A_PRO);
		$A_POST = checknull($A_POST);
	
	
	
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
	WHERE \"contractID\" = '$conID'";
		
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
    VALUES ('$conID','3','1',$numberhome,$A_SUBNO,
			$namebulding[$num],$numberroom[$num],$numberfloor[$num],$namevillage[$num],$A_SOI,$A_RD,
			$A_TUM,$A_AUM,$A_PRO,$A_POST,'000','$add_date','1','000','$add_date')";
			
			
		if($result3=pg_query($insert_addr_tep)){
		}else{
			$status++;
			echo $insert_addr_tep;
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