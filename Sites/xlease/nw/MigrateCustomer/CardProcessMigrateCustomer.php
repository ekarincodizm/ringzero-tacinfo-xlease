<?php
include("../../config/config.php");

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
	echo "<form method=\"post\" name=\"form3\" action=\"CardSelectCustomer.php\">";
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
			
					if($N_SAN[$a]==""){$N_SAN[$a]="NULL";} else{$N_SAN[$a]="'$N_SAN[$a]'";}
					if($N_CARD[$a]==""){$N_CARD[$a]="NULL";} else{$N_CARD[$a]="'$N_CARD[$a]'";}
					if($N_IDCARD[$a]==""){$N_IDCARD[$a]="NULL";} else{$N_IDCARD[$a]="'$N_IDCARD[$a]'";}
					if($N_BY[$a]==""){$N_BY[$a]="NULL";} else{$N_BY[$a]="'$N_BY[$a]'";}
					if($N_OCC[$a]==""){$N_OCC[$a]="NULL";} else{$N_OCC[$a]="'$N_OCC[$a]'";}
					if($N_ContactAdd[$a]==""){$N_ContactAdd[$a]="NULL";} else{$N_ContactAdd[$a]="'$N_ContactAdd[$a]'";}
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
			$test_sql4="insert into public.\"Fn_temp\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\") values ('$CusIDFn[$m]' , '$N_STATE[$m]' , $N_SAN[$m] , '$N_AGE[$m]' , $N_CARD[$m] , $N_IDCARD[$m] , '$N_OT_DATE[$m]' , $N_BY[$m] , $N_OCC[$m] , $N_ContactAdd[$m] )";
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
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql7="update public.\"Fp\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql7))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql8="update public.\"ContactCus\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql8))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql9="update public.\"Customer_Temp\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql9))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql22="update public.\"DetailCheque\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql22))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql23="update public.\"FCash\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql23))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql24="update public.\"FollowUpCus\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql24))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql25="update public.\"GroupCus_Active\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql25))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql26="update public.\"FpOutCus\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql26))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql27="update public.\"GroupCus_Bin\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql27))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	
	
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql28="update insure.\"InsureForce\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql28))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql29="update insure.\"InsureUnforce\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql29))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql30="update letter.\"cus_address\" set \"CusID\"='$TrimCusIDyes' where \"CusID\"='$TrimCusNo[$m]'";
			if($resultFnTemp=pg_query($test_sql30))
			{}
			else
			{
				$status++;
			}
		}
	}
	
	// เก็บประวัติการเปลี่ยน CusID
	for($m = 1 ; $m <= $migrate_row ; $m++)
	{
		if($TrimCusNo[$m] != $TrimCusIDyes)
		{
			$test_sql31="insert into public.\"change_cus\" (\"Cus_old\",\"Cus_new\") values ('$TrimCusNo[$m]' , '$TrimCusIDyes')";
			if($resultHistory=pg_query($test_sql31))
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
		echo "<form method=\"post\" name=\"form1\" action=\"CardSelectCustomer.php\">";
		echo "<center><input type=\"submit\" value=\"ตกลง\"></center>";
		echo "</form>";
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h2>ผิดพลาด!!</h2></center>";
		echo "<form method=\"post\" name=\"form2\" action=\"CardSelectCustomer.php\">";
		echo "<center><input type=\"submit\" value=\"กลับ\"></center>";
		echo "</form>";
	}
}
?>