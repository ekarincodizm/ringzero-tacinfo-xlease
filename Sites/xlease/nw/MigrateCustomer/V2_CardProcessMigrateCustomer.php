<?php
include("../../config/config.php");
?>

<html>
<head>
</head>
<body onkeydown='if(event.keyCode==13) { document.form1.submit();} '>

<?php
$CusIDyes = $_POST["CusSelect"];
$TrimCusIDyes = trim($CusIDyes);

$migrate_row = $_POST["migrate_row"];

for($f = 1 ; $f <= $migrate_row ; $f++)
{
	$CusNo[$f] = $_POST["CusNo$f"];
	$TrimCusNo[$f] = trim($CusNo[$f]);
}

if($CusIDyes=="")
{
	$CusIDyes = "คุณยังไม่ได้เลือก";
	echo "<center><h2>".$CusIDyes."</h2></center><br>";
	echo "<form method=\"post\" name=\"form1\" action=\"V2_CardSearchCustomer.php\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center>";
	echo "</form>";
}


if($CusIDyes!="คุณยังไม่ได้เลือก")
{

	pg_query("BEGIN WORK");
	$status = 0;

	for($a = 1 ; $a <= $migrate_row ; $a++)
	{
		if($TrimCusNo[$a] != $TrimCusIDyes) // เช็คว่ารอบที่กำลังจะทำนี้ใช่ CusID ที่เลือกหรือไม่ ถ้าใช่ไม่ต้องทำ
		{
			$test_sql=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$CusNo[$a]' and \"CusID\" <> '$CusIDyes' ");
			$rowtest=pg_num_rows($test_sql);
			while($result=pg_fetch_array($test_sql))
			{
				$CusID[$a]=$result["CusID"];
				$A_FIRNAME[$a]=$result["A_FIRNAME"];
				$A_NAME[$a]=$result["A_NAME"];
				$A_SIRNAME[$a]=$result["A_SIRNAME"];
				$A_PAIR[$a]=$result["A_PAIR"];
				$A_NO[$a]=$result["A_NO"];
				$A_SUBNO[$a]=$result["A_SUBNO"];
				$A_SOI[$a]=$result["A_SOI"];
				$A_RD[$a]=$result["A_RD"];
				$A_TUM[$a]=$result["A_TUM"];
				$A_AUM[$a]=$result["A_AUM"];
				$A_PRO[$a]=$result["A_PRO"];
				$A_POST[$a]=$result["A_POST"];
				$Approved[$a]=$result["Approved"];
	
				if($A_FIRNAME[$a]==""){$A_FIRNAME[$a]="NULL";} else{$A_FIRNAME[$a]="'$A_FIRNAME[$a]'";}
				if($A_NAME[$a]==""){$A_NAME[$a]="NULL";} else{$A_NAME[$a]="'$A_NAME[$a]'";}
				if($A_SIRNAME[$a]==""){$A_SIRNAME[$a]="NULL";} else{$A_SIRNAME[$a]="'$A_SIRNAME[$a]'";}
				if($A_PAIR[$a]==""){$A_PAIR[$a]="NULL";} else{$A_PAIR[$a]="'$A_PAIR[$a]'";}
				if($A_NO[$a]==""){$A_NO[$a]="NULL";} else{$A_NO[$a]="'$A_NO[$a]'";}
				if($A_SUBNO[$a]==""){$A_SUBNO[$a]="NULL";} else{$A_SUBNO[$a]="'$A_SUBNO[$a]'";}
				if($A_SOI[$a]==""){$A_SOI[$a]="NULL";} else{$A_SOI[$a]="'$A_SOI[$a]'";}
				if($A_RD[$a]==""){$A_RD[$a]="NULL";} else{$A_RD[$a]="'$A_RD[$a]'";}
				if($A_TUM[$a]==""){$A_TUM[$a]="NULL";} else{$A_TUM[$a]="'$A_TUM[$a]'";}
				if($A_AUM[$a]==""){$A_AUM[$a]="NULL";} else{$A_AUM[$a]="'$A_AUM[$a]'";}
				if($A_PRO[$a]==""){$A_PRO[$a]="NULL";} else{$A_PRO[$a]="'$A_PRO[$a]'";}
				if($A_POST[$a]==""){$A_POST[$a]="NULL";} else{$A_POST[$a]="'$A_POST[$a]'";}
		
				$CusIDFn[$a] = trim($CusID[$a]);
	
				$test_sql2=pg_query("select * from public.\"Fn\" where \"CusID\" = '$CusIDFn[$a]' ");
				while($resultFn=pg_fetch_array($test_sql2))
				{
					$N_STATE[$a]=$resultFn["N_STATE"];
					$N_SAN[$a]=$resultFn["N_SAN"];
					$N_AGE[$a]=$resultFn["N_AGE"];
					$N_CARD[$a]=$resultFn["N_CARD"];
					$N_IDCARD[$a]=$resultFn["N_IDCARD"];
					$N_OT_DATE[$a]=$resultFn["N_OT_DATE"];
					$N_BY[$a]=$resultFn["N_BY"];
					$N_OCC[$a]=$resultFn["N_OCC"];
					$N_ContactAdd[$a]=$resultFn["N_ContactAdd"];
					$N_CARDREF[$a]=$resultFn["N_CARDREF"];
			
					if($N_SAN[$a]==""){$N_SAN[$a]="NULL";} else{$N_SAN[$a]="'$N_SAN[$a]'";}
					if($N_CARD[$a]==""){$N_CARD[$a]="NULL";} else{$N_CARD[$a]="'$N_CARD[$a]'";}
					if($N_IDCARD[$a]==""){$N_IDCARD[$a]="NULL";} else{$N_IDCARD[$a]="'$N_IDCARD[$a]'";}
					if($N_BY[$a]==""){$N_BY[$a]="NULL";} else{$N_BY[$a]="'$N_BY[$a]'";}
					if($N_OCC[$a]==""){$N_OCC[$a]="NULL";} else{$N_OCC[$a]="'$N_OCC[$a]'";}
					if($N_ContactAdd[$a]==""){$N_ContactAdd[$a]="NULL";} else{$N_ContactAdd[$a]="'$N_ContactAdd[$a]'";}
					if($N_CARDREF[$a]==""){$N_CARDREF[$a]="NULL";} else{$N_CARDREF[$a]="'$N_CARDREF[$a]'";}
				}
			}
		}
		else
		{
			$YesID = $a;
		}
	}

	
	for($z = 1 ; $z <= $migrate_row ; $z++)
	{
		if($z != $YesID)
		{
			$test_sql3="insert into public.\"Fa1_temp\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"Approved\") values ('$CusID[$z]',$A_FIRNAME[$z],$A_NAME[$z],$A_SIRNAME[$z],$A_PAIR[$z],$A_NO[$z],$A_SUBNO[$z],$A_SOI[$z],$A_RD[$z],$A_TUM[$z],$A_AUM[$z],$A_PRO[$z],$A_POST[$z],'$Approved[$z]')";
			if($resultFaTemp=pg_query($test_sql3))
			{}
			else
			{
				$status++;
			}
		}
	}

	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($m != $YesID)
		{
			$test_sql4="insert into public.\"Fn_temp\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_CARDREF\") values ('$CusIDFn[$m]' , '$N_STATE[$m]' , $N_SAN[$m] , '$N_AGE[$m]' , $N_CARD[$m] , $N_IDCARD[$m] , '$N_OT_DATE[$m]' , $N_BY[$m] , $N_OCC[$m] , $N_ContactAdd[$m] , $N_CARDREF[$m] )";
			if($resultFnTemp=pg_query($test_sql4))
			{}
			else
			{
				$status++;
			}
		}
	}

	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($m != $YesID)
		{
			$test_sql5="delete from public.\"Fa1\" where \"CusID\" = '$CusID[$m]'";
			if($resultFnTemp=pg_query($test_sql5))
			{}
			else
			{
				$status++;
			}
		}
	}

	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($m != $YesID)
		{
			$test_sql6="delete from public.\"Fn\" where \"CusID\" = '$CusIDFn[$m]'";
			if($resultFnTemp=pg_query($test_sql6))
			{}
			else
			{
				$status++;
			}
		}
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
					for($m = 1 ; $m <= $migrate_row ; $m++)
					{
						if($TrimCusNo[$m] != $TrimCusIDyes)
						{
							$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\"='$TrimCusIDyes' where \"$column\"='$TrimCusNo[$m]'";
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
		}
	}
	//-------------- จบการ Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
	
	
	// เก็บประวัติการเปลี่ยน CusID
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql99="insert into public.\"change_cus\" (\"Cus_old\",\"Cus_new\") values ('$TrimCusNo[$m]' , '$TrimCusIDyes')";
			if($resultHistory=pg_query($test_sql99))
			{}
			else
			{
				$status++;
			}
		}
	}

	if($status == 0)
	{
		pg_query("COMMIT");
		echo "<center><h2>ทำรายการเสร็จสมบูรณ์</h2></center>";
		echo "<form method=\"post\" name=\"form1\" action=\"V2_CardSearchCustomer.php\">";
		echo "<center><input type=\"submit\" value=\"ตกลง\"></center>";
		echo "</form>";
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h2>ผิดพลาด!!</h2></center>";
		echo "<form method=\"post\" name=\"form1\" action=\"V2_CardSearchCustomer.php\">";
		echo "<center><input type=\"submit\" value=\"กลับ\"></center>";
		echo "</form>";
	}
}
?>
</body>
</html>