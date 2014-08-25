<?php
set_time_limit(0);
session_start();
include("../../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;

//ดึงข้อมูลทั้งหมดที่ได้กรอกลงไปหรือ migrate ไปมาตรวจสอบ
$qryname = pg_query("select \"CusID\",\"carRadio\",\"carRegis\" from \"Cancel_Radio\"");
$numname=pg_num_rows($qryname);

$i=0;
while($resname=pg_fetch_array($qryname)){
	list($CusID,$carRadio,$carRegis)=$resname;
	$CusID=trim($CusID);
	$carRadio1=trim($carRadio);
	$carRegis1=trim($carRegis);
	
	//นำ $carRadio ที่ได้มาตรวจสอบว่าใช่ 5 หลักและเป็นตัวเลขทั้งหมดหรือไม่
	$nub1 = strlen($carRadio1);
	$nub2 = strlen($carRegis1);
	
	if($nub1=="5"){ //ถ้า $carRadio=5 ตัว ให้เช็คว่าเป็นตัวเลขทั้งหมดหรือไม่
		if(is_numeric($carRadio1) ) { //กรณีเป็นตัวเลขทั้งหมด
			$value="1"; //carRadio คือเลขวิทยุ
			$carRadio=$carRadio1;
			$carRegis=$carRegis1;
		}else{ //กรณีไม่ใช่ตัวเลขให้นำ $carRegis มาตรวจสอบว่าเป็นตัวเลขทั้งหมดหรือไม่
			if(is_numeric($carRegis1)){
				$val="2"; //carRegis คือ เลขวิทยุ
				$carRadio=$carRegis1;
				$carRegis=$carRadio1;
			}else{
				$val="0"; //ไม่มีตัวแปรไหนที่เป็นเลขวิทยุ
			}
		}
	}else{ //ถ้าไม่เท่ากับ 5 ให้นับ $carRegis 
		if(is_numeric($carRegis)){
			$val="2"; //carRegis คือ เลขวิทยุ
			$carRadio=$carRegis1;
			$carRegis=$carRadio1;
		}else{
			$val="0"; // ไม่มีตัวแปรไหนที่เป็นเลขวิทยุ
		}
	}
	
	
	//ถ้า val=1 แสดงว่าถูกต้องแล้ว แต่ถ้า =2 ต้องสลับตำแหน่งกันระหว่าง $carRadio และ  $carRegis
	if($val=="2"){
		$upcar="UPDATE \"Cancel_Radio\" SET \"carRadio\"='$carRadio',\"carRegis\"='$carRegis' where \"CusID\"='$CusID'";
		if($resup=pg_query($upcar)){
		}else{
			$status++;
		}
	}
	
	//นำเลขวิทยุที่ได้ไปค้นหาใน mssql เพื่อหาเลขที่สัญญาที่ถูกต้อง
	$qry_name=mssql_query("select a.CusID,a.CarRegis from TacCusDtl as a
	left join RadioDoc as b on a.CusID=b.CusID 
	where b.RadioID = '$carRadio' group by a.CusID,a.CarRegis");
	
	$numrows = mssql_num_rows($qry_name);
	if($numrows==0){ //กรณีไม่พบข้อมูล
		$val=3; //ให้แสดงค่าที่ไม่สามารถ update ได้
	}else{ //กรณีพบข้อมูล
		while($res_name=mssql_fetch_array($qry_name)){
			$CusID2=trim(iconv('WINDOWS-874','UTF-8',$res_name["CusID"])); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
			$CarRegis2=trim(iconv('WINDOWS-874','UTF-8',$res_name["CarRegis"]));if(empty($CarRegis)) $CarRegis="ไม่พบทะเบียนรถ";
			
			$carRegisNew=strtr($carRegis, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
			$carRegisNew=ereg_replace('[[:space:]]+', '', trim($carRegisNew)); //ตัดช่องว่างออก

			$carRegisOld=strtr($CarRegis2, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
			$carRegisOld=ereg_replace('[[:space:]]+', '', trim($carRegisOld)); //ตัดช่องว่างออก			
			
			//ตรวจสอบว่าทะเบียนรถตรงกันหรือไม่
			if($carRegisNew==$carRegisOld){ //ถ้าเหมือนกันให้ update CusID ให้ถูกต้อง		
				$upcus="UPDATE \"Cancel_Radio\" SET \"CusID\"='$CusID2' where \"carRadio\"='$carRadio'";
				if($rescus=pg_query($upcus)){
				}else{
					$status++;
				}
				$val=1;
			}else{
				$val=3; //ให้แสดงค่าที่ไม่สามารถ update ได้
			}
		}
	}

	//แสดงข้อมูลที่ไม่สามารถ Update ได้
	if($val=="3"){
		echo "<div>เลขวิทยุที่คีย์:<b>$carRadio</b>, ทะเบียนรถที่คีย์ :<b>$carRegis</b>, ทะเบียนรถในระบบ :<b>$CarRegis2</b>, เลขสัญญา :$CusID</div>";
	}
	
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>แก้ไขข้อมูลเรียบร้อยแล้ว <u>พร้อมแสดงรายการที่ไม่สามารถ update ได้</u></b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
