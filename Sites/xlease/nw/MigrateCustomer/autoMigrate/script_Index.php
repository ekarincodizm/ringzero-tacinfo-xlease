<?php
include("../../../config/config.php");
set_time_limit(0);
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
// หมายเหตุ script นี้จะยังไม่เอาเลขที่บัตรที่มี "โฮมเจริญ" ปนอยู่ เนื่องจากยังไม่ปัญหา โดยจะ where บัตร "3550600058492" และ "0103551028922" ออก

pg_query("BEGIN WORK");
$status = 0;

$i = 0;
$errorStr = "";

$qry_main = pg_query("select distinct replace(replace(\"N_IDCARD\",' ',''),'-','') as \"mainCardID\",
						count(replace(replace(\"N_IDCARD\",' ',''),'-','')) as \"countIDCARD\",
						count(distinct \"CusID\") as \"countCusID\"
					from \"Customer_Temp\"
					where \"N_IDCARD\" is not null and \"N_IDCARD\" <> '' and \"N_IDCARD\" not like '%-' and \"statusapp\" = '1'
						and LENGTH(replace(replace(\"N_IDCARD\",' ',''),'-','')) = '13' and replace(replace(\"N_IDCARD\",' ',''),'-','') <> '3550600058492' and replace(replace(\"N_IDCARD\",' ',''),'-','') <> '0103551028922'
					group by replace(replace(\"N_IDCARD\",' ',''),'-','') having count(replace(replace(\"N_IDCARD\",' ',''),'-','')) > 1 and count(distinct \"CusID\") > '1'
					order by \"mainCardID\" ");
while($resMain = pg_fetch_array($qry_main))
{
	$mainCardID = $resMain["mainCardID"]; // บัตรประจำตัว
	
	if(is_numeric($mainCardID)) // ถ้าบัตรที่หามาได้เป็นตัวเลขทั้งหมด
	{
		// หาวันที่อนุมัติล่าสุด
		$qry_maxAppvDate = pg_query("select max(\"app_date\") as \"maxAppvDate\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' ");
		while($res_maxAppvDate = pg_fetch_array($qry_maxAppvDate))
		{
			$maxAppvDate = $res_maxAppvDate["maxAppvDate"]; // วันที่อนุมัติล่าสุด
		}
		
		// หา CusID ล่าสุด
		$qry_cusForMaxAppvDate = pg_query("select \"CusID\" as \"selectCusID\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"app_date\" = '$maxAppvDate' ");
		$nomrows_cusForMaxAppvDate = pg_num_rows($qry_cusForMaxAppvDate); // จำนวนคนที่ถูกอนุมัติตามเวลาล่าสุด
		
		if($nomrows_cusForMaxAppvDate == 1) // ถ้ามีแค่คนเดียวถึงจะทำ
		{
			$i++;
			
			while($res_cusForMaxAppvDate = pg_fetch_array($qry_cusForMaxAppvDate))
			{
				$selectCusID = $res_cusForMaxAppvDate["selectCusID"]; // รหัสลูกค้าที่เลือกใช้
			}
			
			// หา CusID ของบัตรประชาชนนั้นๆ
			$qry_noCusID = pg_query("select distinct \"CusID\" as \"noCusID\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"CusID\" <> '$selectCusID' ");
			while($res_noCusID = pg_fetch_array($qry_noCusID))
			{
				$noCusID = $res_noCusID["noCusID"]; // รหัสลูกค้าที่ไม่ต้องการ
				
				$test_sql=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$noCusID' and \"CusID\" <> '$selectCusID' ");
				$rowtest=pg_num_rows($test_sql);
				while($result=pg_fetch_array($test_sql))
				{
					$A_FIRNAME = $result["A_FIRNAME"];
					$A_NAME = $result["A_NAME"];
					$A_SIRNAME = $result["A_SIRNAME"];
					$A_PAIR = $result["A_PAIR"];
					$A_NO = $result["A_NO"];
					$A_SUBNO = $result["A_SUBNO"];
					$A_SOI = $result["A_SOI"];
					$A_RD = $result["A_RD"];
					$A_TUM = $result["A_TUM"];
					$A_AUM = $result["A_AUM"];
					$A_PRO = $result["A_PRO"];
					$A_POST = $result["A_POST"];
					$Approved = $result["Approved"];
		
					if($A_FIRNAME == ""){$A_FIRNAME = "NULL";} else{$A_FIRNAME = "'$A_FIRNAME'";}
					if($A_NAME == ""){$A_NAME = "NULL";} else{$A_NAME = "'$A_NAME'";}
					if($A_SIRNAME == ""){$A_SIRNAME = "NULL";} else{$A_SIRNAME ="'$A_SIRNAME'";}
					if($A_PAIR == ""){$A_PAIR = "NULL";} else{$A_PAIR ="'$A_PAIR'";}
					if($A_NO == ""){$A_NO = "NULL";} else{$A_NO = "'$A_NO'";}
					if($A_SUBNO == ""){$A_SUBNO = "NULL";} else{$A_SUBNO = "'$A_SUBNO'";}
					if($A_SOI == ""){$A_SOI = "NULL";} else{$A_SOI = "'$A_SOI'";}
					if($A_RD == ""){$A_RD = "NULL";} else{$A_RD = "'$A_RD'";}
					if($A_TUM == ""){$A_TUM = "NULL";} else{$A_TUM = "'$A_TUM'";}
					if($A_AUM == ""){$A_AUM = "NULL";} else{$A_AUM = "'$A_AUM'";}
					if($A_PRO == ""){$A_PRO = "NULL";} else{$A_PRO = "'$A_PRO'";}
					if($A_POST == ""){$A_POST = "NULL";} else{$A_POST = "'$A_POST'";}
			
					$CusIDFn  = trim($result["CusID"]);
		
					$test_sql2=pg_query("select * from public.\"Fn\" where \"CusID\" = '$CusIDFn' ");
					while($resultFn=pg_fetch_array($test_sql2))
					{
						$N_STATE = $resultFn["N_STATE"];
						$N_SAN = $resultFn["N_SAN"];
						$N_AGE = $resultFn["N_AGE"];
						$N_CARD = $resultFn["N_CARD"];
						$N_IDCARD = $resultFn["N_IDCARD"];
						$N_OT_DATE = $resultFn["N_OT_DATE"];
						$N_BY = $resultFn["N_BY"];
						$N_OCC = $resultFn["N_OCC"];
						$N_ContactAdd = $resultFn["N_ContactAdd"];
						$N_CARDREF = $resultFn["N_CARDREF"];
				
						if($N_SAN == ""){$N_SAN = "NULL";} else{$N_SAN = "'$N_SAN'";}
						if($N_CARD == ""){$N_CARD = "NULL";} else{$N_CARD = "'$N_CARD'";}
						if($N_IDCARD == ""){$N_IDCARD = "NULL";} else{$N_IDCARD = "'$N_IDCARD'";}
						if($N_BY == ""){$N_BY = "NULL";} else{$N_BY = "'$N_BY'";}
						if($N_OCC == ""){$N_OCC = "NULL";} else{$N_OCC = "'$N_OCC'";}
						if($N_ContactAdd == ""){$N_ContactAdd = "NULL";} else{$N_ContactAdd = "'$N_ContactAdd'";}
						if($N_CARDREF == ""){$N_CARDREF = "NULL";} else{$N_CARDREF = "'$N_CARDREF'";}
					}
				}
			
				// copy ข้อมูลไปไว้ในตาราง Fa1_temp
				$test_sql3="insert into public.\"Fa1_temp\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"Approved\") values ('$noCusID',$A_FIRNAME,$A_NAME,$A_SIRNAME,$A_PAIR,$A_NO,$A_SUBNO,$A_SOI,$A_RD,$A_TUM,$A_AUM,$A_PRO,$A_POST,'$Approved')";
				if($resultFaTemp=pg_query($test_sql3))
				{}
				else
				{
					$status++;
				}
				
				// copy ข้อมูลไปไว้ในตาราง Fn_temp
				$test_sql4="insert into public.\"Fn_temp\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_CARDREF\") values ('$noCusID' , '$N_STATE' , $N_SAN , '$N_AGE' , $N_CARD , $N_IDCARD , '$N_OT_DATE' , $N_BY , $N_OCC , $N_ContactAdd , $N_CARDREF )";
				if($resultFnTemp=pg_query($test_sql4))
				{}
				else
				{
					$status++;
				}
				
				// ลบข้อมูลออกจาก Fa1
				$test_sql5="delete from public.\"Fa1\" where \"CusID\" = '$noCusID'";
				if($resultFnTemp=pg_query($test_sql5))
				{}
				else
				{
					$status++;
				}
				
				// ลบข้อมูลออกจาก Fn
				$test_sql6="delete from public.\"Fn\" where \"CusID\" = '$noCusID'";
				if($resultFnTemp=pg_query($test_sql6))
				{}
				else
				{
					$status++;
				}
				
				//-------------- Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
					$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as test
							from INFORMATION_SCHEMA.COLUMNS
							where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
							and data_type in('character varying','text','character','char','regclass','name')";
					$query = pg_query($sql);
					while($re = pg_fetch_array($query))
					{
						$SCHEMA = $re['sm'];
						$realtb = $re['tb'];
						$column = $re['test'];
						
						if(($SCHEMA == "public" && $realtb == "change_cus") || ($SCHEMA == "public" && $realtb == "Customer_Temp") || ($SCHEMA == "public" && $realtb == "Fa1_temp") || ($SCHEMA == "public" && $realtb == "Fn_temp"))
						{
							continue;
						}

						$sql1 = "select \"$column\" as cusid from $SCHEMA.\"$realtb\" where \"$column\" LIKE 'C%' and \"$column\" is not null limit 1";
						$query1 = pg_query($sql1);
						$rows = pg_num_rows($query1);
						$re1 = pg_fetch_array($query1);
						if($rows > 0 )
						{
							$chkdigi = trim($re1['cusid']);
							$chkre = substr($chkdigi,1);
							$cjkre2 = strlen($chkre);

							if($cjkre2 == 5)
							{
								if(is_numeric($chkre))
								{
									$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\"='$selectCusID' where \"$column\"='$noCusID'";
									if($resultFnTemp=pg_query($test_sql7))
									{}
									else
									{
										$status++;
									}
								}
							}
						}
					}
				//-------------- จบการ Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
				
				//--- เก็บประวัติการเปลี่ยน CusID
					$test_sql99="insert into public.\"change_cus\" (\"Cus_old\",\"Cus_new\") values ('$noCusID' , '$selectCusID')";
					if($resultHistory=pg_query($test_sql99))
					{}
					else
					{
						$status++;
					}
				//--- จบการเก็บประวัติการเปลี่ยน CusID
			}
		}
		else // ถ้าวันที่อนุมัติล่าสุดมีมากกว่า 1
		{
			$errorStr .= " $mainCardID<br>";
		}
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h2>SUCCESS</h2></center>";
	echo "<center>โดยสามารถแก้ไขคนที่มีเลขที่บัตรซ้ำกันได้ จำนวน $i เลขบัตร</center>";
	if($errorStr != ""){echo "<br><center>บัตรประชาชนที่ไม่สามารถดำเนินการได้<br>$errorStr</center>";}
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>ERROR</h2></center>";
}
?>