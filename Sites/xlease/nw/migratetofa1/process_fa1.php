<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
$db1="ta_mortgage_datastore";

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;


$qryname = mysql_query("select * from $db1.vcustomerdetail");
$numname=mysql_num_rows($qryname);

$i=0;
while($resname=mysql_fetch_array($qryname)){
	$CusID=$resname["CusID"]; if($CusID==""){ $CusID="null"; }else{ $CusID="'".$CusID."'"; }
	$A_FIRNAME=$resname["A_FIRNAME"]; if($A_FIRNAME==""){ $A_FIRNAME="null"; }else{ $A_FIRNAME="'".$A_FIRNAME."'"; }
	$A_NAME=$resname["A_NAME"]; if($A_NAME==""){ $A_NAME="null"; }else{ $A_NAME="'".$A_NAME."'"; }
	$A_SIRNAME=$resname["A_SIRNAME"]; if($A_SIRNAME==""){ $A_SIRNAME="null"; }else{ $A_SIRNAME="'".$A_SIRNAME."'"; }
	$A_PAIR=$resname["A_PAIR"]; if($A_PAIR==""){ $A_PAIR="null"; }else{ $A_PAIR="'".$A_PAIR."'"; }
	$A_NO=$resname["A_NO"]; if($A_NO==""){ $A_NO="null"; }else{ $A_NO="'".$A_NO."'"; }
	$A_SUBNO=$resname["A_SUBNO"]; if($A_SUBNO==""){ $A_SUBNO="null"; }else{ $A_SUBNO="'".$A_SUBNO."'"; }
	$A_SOI=$resname["A_SOI"]; if($A_SOI==""){ $A_SOI="null"; }else{ $A_SOI="'".$A_SOI."'"; }
	$A_RD=$resname["A_RD"]; if($A_RD==""){ $A_RD="null"; }else{ $A_RD="'".$A_RD."'"; }
	$A_TUM=$resname["A_TUM"]; if($A_TUM==""){ $A_TUM="null"; }else{ $A_TUM="'".$A_TUM."'"; }
	$A_AUM=$resname["A_AUM"]; if($A_AUM==""){ $A_AUM="null"; }else{ $A_AUM="'".$A_AUM."'"; }
	$A_PRO=$resname["A_PRO"]; if($A_PRO==""){ $A_PRO="null"; }else{ $A_PRO="'".$A_PRO."'"; }
	$A_POST=$resname["A_POST"]; if($A_POST==""){ $A_POST="null"; }else{ $A_POST="'".$A_POST."'"; }
	$N_SAN=$resname["N_SAN"]; if($N_SAN==""){ $N_SAN="null"; }else{ $N_SAN="'".$N_SAN."'"; }
	$N_AGE=$resname["N_AGE"]; if($N_AGE==""){ $N_AGE="null"; }else{ $N_AGE="'".$N_AGE."'"; }
	$N_CARD=$resname["N_CARD"]; if($N_CARD==""){ $N_CARD="null"; }else{ $N_CARD="'".$N_CARD."'"; }
	$N_IDCARD=$resname["N_IDCARD"]; 
	$N_OT_DATE=$resname["N_OT_DATE"]; if($N_OT_DATE==""){ $N_OT_DATE="null"; }else{ $N_OT_DATE="'".$N_OT_DATE."'"; }
	$N_BY=$resname["N_BY"]; if($N_BY==""){ $N_BY="null"; }else{ $N_BY="'".$N_BY."'"; }
	$N_OCC=$resname["N_OCC"]; if($N_OCC==""){ $N_OCC="null"; }else{ $N_OCC="'".$N_OCC."'"; }
	$N_ContactAdd=$resname["N_ContactAdd"]; if($N_ContactAdd==""){ $N_ContactAdd="null"; }else{ $N_ContactAdd="'".$N_ContactAdd."'"; }
	$A_EMAIL=$resname["A_EMAIL"]; if($A_EMAIL==""){ $A_EMAIL="null"; }else{ $A_EMAIL="'".$A_EMAIL."'"; }
	$A_FIRNAME_ENG=$resname["A_FIRNAME_ENG"]; if($A_FIRNAME_ENG==""){ $A_FIRNAME_ENG="null"; }else{ $A_FIRNAME_ENG="'".$A_FIRNAME_ENG."'"; }
	$A_NAME_ENG=$resname["A_NAME_ENG"]; if($A_NAME_ENG==""){ $A_NAME_ENG="null"; }else{ $A_NAME_ENG="'".$A_NAME_ENG."'"; }
	$A_SIRNAME_ENG=$resname["A_SIRNAME_ENG"]; if($A_SIRNAME_ENG==""){ $A_SIRNAME_ENG="null"; }else{ $A_SIRNAME_ENG="'".$A_SIRNAME_ENG."'"; }
	$A_NICKNAME=$resname["A_NICKNAME"]; if($A_NICKNAME==""){ $A_NICKNAME="null"; }else{ $A_NICKNAME="'".$A_NICKNAME."'"; }
	$A_STATUS=$resname["A_STATUS"]; if($A_STATUS==""){ $A_STATUS="null"; }else{ $A_STATUS="'".$A_STATUS."'"; }
	$A_REVENUE=$resname["A_REVENUE"]; if($A_REVENUE==""){ $A_REVENUE="null"; }else{ $A_REVENUE="'".$A_REVENUE."'"; }
	$A_EDUCATION=$resname["A_EDUCATION"]; if($A_EDUCATION==""){ $A_EDUCATION="null"; }else{ $A_EDUCATION="'".$A_EDUCATION."'"; }
	$A_MOBILE=$resname["A_MOBILE"]; if($A_MOBILE==""){ $A_MOBILE="null"; }else{ $A_MOBILE="'".$A_MOBILE."'"; }
	$A_TELEPHONE=$resname["A_TELEPHONE"]; if($A_TELEPHONE==""){ $A_TELEPHONE="null"; }else{ $A_TELEPHONE="'".$A_TELEPHONE."'"; }
	$A_BIRTHDAY=$resname["A_BIRTHDAY"]; if($A_BIRTHDAY==""){ $A_BIRTHDAY="null"; }else{ $A_BIRTHDAY="'".$A_BIRTHDAY."'"; }
	$A_SEX=$resname["A_SEX"]; if($A_SEX==""){ $A_SEX="null"; }else{ $A_SEX="'".$A_SEX."'"; }
	
	//นำเลขบัตรประชาชนที่ได้มาตรวจสอบว่ามีข้อมูลนี้ในระบบหรือยัง
	$N_IDCARD=strtr($N_IDCARD, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
	$N_IDCARD=ereg_replace('[[:space:]]+', '', trim($N_IDCARD)); //ตัดช่องว่างออก
	
	$qry_check=pg_query("select \"CusID\" from \"Fn\" WHERE replace(replace(\"N_IDCARD\",' ',''),'-','') = '$N_IDCARD' limit 1");
	list($CusFn)=pg_fetch_array($qry_check);
	$CusFn=trim($CusFn);
	$numrows = pg_num_rows($qry_check);
	if($numrows>0){  //กรณีมีข้อมูลแล้ว
		if($N_IDCARD==""){ //ถ้าเลขบัตรเป็นค่าว่างต้องตรวจสอบก่อนว่ามีชื่อกับนามสกุลนี้หรือไม่
			$sql_check_name = pg_query("select \"CusID\" from \"Fa1\" where \"A_NAME\" = $A_NAME and \"A_SIRNAME\" = $A_SIRNAME limit 1");
			$row_check_name = pg_num_rows($sql_check_name);
			list($CusFa1)=pg_fetch_array($sql_check_name);
			$CusFa1=trim($CusFa1);
			if($row_check_name > 0) //กรณีมีข้อมูล
			{
				
				//update CusID ในตาราง thcap_ContactCus
				$up="UPDATE \"thcap_ContactCus\" SET \"CusID\"='$CusFa1' WHERE \"CusID\"=$CusID";
				if($result2=pg_query($up)){
				}else{
					$status++;
				}
				
				//update ข้อมูลที่ยังไม่มี
				$upfa="UPDATE \"Fa1\" SET \"A_FIRNAME_ENG\"=$A_FIRNAME_ENG,\"A_NAME_ENG\"=$A_NAME_ENG,\"A_SIRNAME_ENG\"=$A_SIRNAME_ENG,\"A_NICKNAME\"=$A_NICKNAME,
				\"A_STATUS\"=$A_STATUS,\"A_REVENUE\"=$A_REVENUE,\"A_EDUCATION\"=$A_EDUCATION,\"A_COUNTRY\"='ไทย',\"A_MOBILE\"=$A_MOBILE,\"A_TELEPHONE\"=$A_TELEPHONE,
				\"A_EMAIL\"=$A_EMAIL,\"A_BIRTHDAY\"=$A_BIRTHDAY,\"A_SEX\"=$A_SEX,addr_country='TH' where \"CusID\"='$CusFa1'";
				if($resup=pg_query($upfa)){
				}else{
					$status++;
				}
				
				//insert temp ว่ามีการแก้ไขข้อมูล
				$qryedittime=pg_query("select max(\"edittime\") from \"Customer_Temp\" where \"CusID\"='$CusFa1'");
				list($maxedittime)=pg_fetch_array($qryedittime);
				if($maxedittime==0){
					$maxedittime=1;
				}else{
					$maxedittime++;
				}
				
				$insert_temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
							\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country)
				select  a.\"CusID\",'000','$add_date','000','$add_date','1','$maxedittime',\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\",\"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",\"A_NAME_ENG\",\"A_SIRNAME_ENG\",
					\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",'ไทย',\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",'TH' from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusFa1'";
			
				if($res_temp=pg_query($insert_temp)){
				}else{
					$status++;
				}
				
				$cusname[$i]=$CusFa1; //เก็บไว้แสดงว่าชื่อซ้ำในฐานข้อมูล
				$i++;

				$statushas=1; //แสดงว่ามีแล้ว
			}else{
				$statushas=0; //ยังไม่มี
			}
		}else{
			//update CusID ในตาราง thcap_ContactCus
				$up="UPDATE \"thcap_ContactCus\" SET \"CusID\"='$CusFn' WHERE \"CusID\"=$CusID";
				if($result2=pg_query($up)){
				}else{
					$status++;
				}
				
			//update ข้อมูลที่ยังไม่มี
				$upfa="UPDATE \"Fa1\" SET \"A_FIRNAME_ENG\"=$A_FIRNAME_ENG,\"A_NAME_ENG\"=$A_NAME_ENG,\"A_SIRNAME_ENG\"=$A_SIRNAME_ENG,\"A_NICKNAME\"=$A_NICKNAME,
				\"A_STATUS\"=$A_STATUS,\"A_REVENUE\"=$A_REVENUE,\"A_EDUCATION\"=$A_EDUCATION,\"A_COUNTRY\"='ไทย',\"A_MOBILE\"=$A_MOBILE,\"A_TELEPHONE\"=$A_TELEPHONE,
				\"A_EMAIL\"=$A_EMAIL,\"A_BIRTHDAY\"=$A_BIRTHDAY,\"A_SEX\"=$A_SEX,addr_country='TH' where \"CusID\"='$CusFn'";
				if($resup=pg_query($upfa)){
				}else{
					$status++;
				}
				
				//insert temp ว่ามีการแก้ไขข้อมูล
				$qryedittime=pg_query("select max(\"edittime\") from \"Customer_Temp\" where \"CusID\"='$CusFn'");
				list($maxedittime)=pg_fetch_array($qryedittime);
				if($maxedittime==0){
					$maxedittime=1;
				}else{
					$maxedittime++;
				}
				$insert_temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
							\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country)
				select  a.\"CusID\",'000','$add_date','000','$add_date','1','$maxedittime',\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\",\"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",\"A_NAME_ENG\",\"A_SIRNAME_ENG\",
					\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",'ไทย',\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",'TH' from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusFn'";
				
				if($res_temp=pg_query($insert_temp)){
				}else{
					$status++;
				}
			$cusname[$i]=$CusFn; //เก็บไว้แสดงว่าชื่อซ้ำในฐานข้อมูล
			$i++;
			
			$statushas=1;
		}
	}else{
		$statushas=0; //กรณียังไม่มีข้อมูล
	}
	
	//กรณียังไม่มีข้อมูล
	if($statushas==0){ 
		$cus_sn = GenCus(); //รหัสลูกค้าใหม่
		
		if($N_IDCARD==""){ $N_IDCARD2="null"; }else{ $N_IDCARD2="'".$N_IDCARD."'"; }
		//ทำการ Insert ข้อมูลในตาราง Customer_Temp
		
		$insert_temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
							\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country)
				VALUES ('$cus_sn','000','$add_date','000','$add_date','1','0',$A_FIRNAME, $A_NAME, $A_SIRNAME, $A_PAIR, $A_NO,
					$A_SUBNO, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, $A_POST,$N_SAN, $N_AGE, $N_CARD, $N_IDCARD2, 
					$N_OT_DATE,$N_BY, $N_OCC, $N_ContactAdd,'0',$A_FIRNAME_ENG,$A_NAME_ENG,$A_SIRNAME_ENG,
					$A_NICKNAME,$A_STATUS,$A_REVENUE,$A_EDUCATION,'ไทย',$A_MOBILE,$A_TELEPHONE,$A_EMAIL,$A_BIRTHDAY,$A_SEX,'TH')";
		if($res_temp=pg_query($insert_temp)){
		}else{
			$status++;
		}

		//insert ใน Fa1
		$insert_Fa1="INSERT INTO \"Fa1\"(
					\"CusID\", \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					\"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", 
					\"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", 
					\"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country)
				VALUES ('$cus_sn', $A_FIRNAME, $A_NAME, $A_SIRNAME, $A_PAIR, $A_NO,
					$A_SUBNO, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, $A_POST,
					$A_FIRNAME_ENG,$A_NAME_ENG,$A_SIRNAME_ENG,$A_NICKNAME,
					$A_STATUS,$A_REVENUE,$A_EDUCATION,'ไทย',$A_MOBILE,$A_TELEPHONE,$A_EMAIL,$A_BIRTHDAY,$A_SEX,'TH')";
		if($res_fa1=pg_query($insert_Fa1)){
		}else{
			$status++;
		}

		//insert ใน Fn
		$insert_Fn="INSERT INTO \"Fn\"(
				\"CusID\", \"N_STATE\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",
				\"N_BY\", \"N_OCC\", \"N_ContactAdd\")
				VALUES ('$cus_sn', '0', $N_SAN, $N_AGE, $N_CARD, $N_IDCARD2, $N_OT_DATE,
					$N_BY, $N_OCC, $N_ContactAdd)";
		if($result=pg_query($insert_Fn)){
		}else{
			$status++;
		}
		
		//update CusID ในตาราง thcap_ContactCus
		$up="UPDATE \"thcap_ContactCus\" SET \"CusID\"='$cus_sn' WHERE \"CusID\"=$CusID";
		if($result2=pg_query($up)){
		}else{
			$status++;
		}
		
	}
	
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>สิ้นสุดการ Migrate เรียบร้อยแล้ว</b></font></div>";
	
	?>
	<form method="post" action="pdf_printcustomer.php" target="_blank">
	<table width="600" align="center" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD">
	<tr><td colspan="3" align="left" bgcolor="#FFFFFF"><b>รายชื่อลูกค้าที่ซ้ำ</b></td></tr>
	<tr align="center" style="font-weight:bold;" bgcolor="#BCE6FC" height="25">
		<td>ลำดับที่</td>
		<td>รหัสลูกค้า</td>
		<td>ชื่อ-นามสกุลลูกค้า</td>
	</tr>
	<?php
	$j=1;
	foreach($cusname as $key => $value){
		$qryname=pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$value'");
		list($fullname)=pg_fetch_array($qryname);
		echo "<input type=\"hidden\" name=\"cus[]\" value=\"$value\">";	
		echo "<tr bgcolor=#EAF9FF><td align=center>$j</td><td align=center>$value</td><td align=left>$fullname</td></tr>";
		$j++;
	}
	?>
	<tr><td colspan="3" align="right" bgcolor="#FFFFFF"><input type="submit" value="Print"></td></tr>
	</table>
	</form>
	<?php
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
