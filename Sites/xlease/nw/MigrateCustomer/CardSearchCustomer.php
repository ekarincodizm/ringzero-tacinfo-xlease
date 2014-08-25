<?php
include("../../config/config.php");
?>
<html>
<head>
</head>
<body>

<?php

$sql_one=pg_query("select * from public.\"Fn\" where \"N_IDCARD\" <> '0' and \"N_IDCARD\" <> '-' and \"N_IDCARD\" <> '--' order by \"CusID\" ");
$i = 0;
while($result_one=pg_fetch_array($sql_one))
{
	$i++;
	
	$CusID_one[$i]=$result_one["CusID"];
	$N_IDCARD_one[$i]=$result_one["N_IDCARD"];
	$N_CARDREF_one[$i]=$result_one["N_CARDREF"];
	if($N_IDCARD_one[$i]=="")
	{
		$N_IDCARD_one[$i]=$N_CARDREF_one[$i];
	}
	
	$N_IDCARD_one[$i] = str_replace(" " , "" , $N_IDCARD_one[$i]);
	$N_IDCARD_one[$i] = str_replace("-" , "" , $N_IDCARD_one[$i]);
	$N_IDCARD_one[$i] = str_replace("." , "" , $N_IDCARD_one[$i]);
	$N_IDCARD_one[$i] = str_replace("/" , "" , $N_IDCARD_one[$i]);
}

$sql_two=pg_query("select * from public.\"Fn\" where \"N_IDCARD\" <> '0' and \"N_IDCARD\" <> '-' and \"N_IDCARD\" <> '--' order by \"CusID\" ");
$i = 0;
while($result_two=pg_fetch_array($sql_two))
{
	$i++;
	
	$CusID_two[$i]=$result_two["CusID"];
	$N_IDCARD_two[$i]=$result_two["N_IDCARD"];
	$N_CARDREF_two[$i]=$result_two["N_CARDREF"];
	if($N_IDCARD_two[$i]=="")
	{
		$N_IDCARD_two[$i]=$N_CARDREF_two[$i];
	}
	
	$N_IDCARD_two[$i] = str_replace(" " , "" , $N_IDCARD_two[$i]);
	$N_IDCARD_two[$i] = str_replace("-" , "" , $N_IDCARD_two[$i]);
	$N_IDCARD_two[$i] = str_replace("." , "" , $N_IDCARD_two[$i]);
	$N_IDCARD_two[$i] = str_replace("/" , "" , $N_IDCARD_two[$i]);
}

$check = 1; //เอาไว้เช็คว่าลูกค้าคนที่กำลังค้นหามีทั้งหมดกี่คน เช่น มี 3 เลขที่บัตรซ้ำกัน
$c = 1; //เอาไว้เก็บตัวที่มาซ้ำ เช่น มีเลขที่บัตรมาซ้ำ 2 อัน c ก็จะได้ 3
for($a = 1 ; $a < $i ; $a++)
{
	if($check > 1) //ถ้าลูกค้าคนที่กำลังเช็คมีมากกว่า 1 แสดงว่ามีลูกค้าซ้ำ
	{
		break;
	}
	
	for($b = $a+1 ; $b <= $i ; $b++)
	{
		if($N_IDCARD_one[$a] == $N_IDCARD_two[$b])
		{
			$check++;
			$c++;
			$id[$c] = $CusID_two[$b];
			$card[$c] = $N_IDCARD_two[$b];
		}
	}
}
$a--;

if($check > 1)
{
	$test_sql2=pg_query("select * from public.\"Fa1\" where \"CusID\" like '$CusID_one[$a]%' ");
	while($resultFa=pg_fetch_array($test_sql2))
	{
		$CusID[1]=$resultFa["CusID"];
		$A_FIRNAME[1]=$resultFa["A_FIRNAME"];
		$A_NAME[1]=$resultFa["A_NAME"];
		$A_SIRNAME[1]=$resultFa["A_SIRNAME"];
		$A_PAIR[1]=$resultFa["A_PAIR"];
		$A_NO[1]=$resultFa["A_NO"];
		$A_SUBNO[1]=$resultFa["A_SUBNO"];
		$A_SOI[1]=$resultFa["A_SOI"];
		$A_RD[1]=$resultFa["A_RD"];
		$A_TUM[1]=$resultFa["A_TUM"];
		$A_AUM[1]=$resultFa["A_AUM"];
		$A_PRO[1]=$resultFa["A_PRO"];
		$A_POST[1]=$resultFa["A_POST"];
	}
	
	$test_sql3=pg_query("select * from public.\"Fn\" where \"CusID\" = '$CusID_one[$a]' ");
	while($resultFn=pg_fetch_array($test_sql3))
	{
		$N_AGE[1]=$resultFn["N_AGE"];
		$N_CARD[1]=$resultFn["N_CARD"];
		$N_IDCARD[1]=$resultFn["N_IDCARD"];
		$N_OT_DATE[1]=$resultFn["N_OT_DATE"];
		$N_BY[1]=$resultFn["N_BY"];
		$N_SAN[1]=$resultFn["N_SAN"];
		$N_OCC[1]=$resultFn["N_OCC"];
		$N_ContactAdd[1]=$resultFn["N_ContactAdd"];
		$N_CARDREF[1]=$resultFn["N_CARDREF"];
		if($N_IDCARD[1]=="")
		{
			$N_IDCARD[1]=$N_CARDREF[1];
		}
		else
		{
			$N_CARD[1]="บัตรประชาชน";
		}
	}

	for($e = 2 ; $e <= $c ; $e++)
	{
		$test_sql4=pg_query("select * from public.\"Fa1\" where \"CusID\" like '$id[$e]%' ");
		while($resultFa=pg_fetch_array($test_sql4))
		{
			$CusID[$e]=$resultFa["CusID"];
			$A_FIRNAME[$e]=$resultFa["A_FIRNAME"];
			$A_NAME[$e]=$resultFa["A_NAME"];
			$A_SIRNAME[$e]=$resultFa["A_SIRNAME"];
			$A_PAIR[$e]=$resultFa["A_PAIR"];
			$A_NO[$e]=$resultFa["A_NO"];
			$A_SUBNO[$e]=$resultFa["A_SUBNO"];
			$A_SOI[$e]=$resultFa["A_SOI"];
			$A_RD[$e]=$resultFa["A_RD"];
			$A_TUM[$e]=$resultFa["A_TUM"];
			$A_AUM[$e]=$resultFa["A_AUM"];
			$A_PRO[$e]=$resultFa["A_PRO"];
			$A_POST[$e]=$resultFa["A_POST"];
		}
	
		$test_sql5=pg_query("select * from public.\"Fn\" where \"CusID\" = '$id[$e]' ");
		while($resultFn=pg_fetch_array($test_sql5))
		{
			$N_AGE[$e]=$resultFn["N_AGE"];
			$N_CARD[$e]=$resultFn["N_CARD"];
			$N_IDCARD[$e]=$resultFn["N_IDCARD"];
			$N_OT_DATE[$e]=$resultFn["N_OT_DATE"];
			$N_BY[$e]=$resultFn["N_BY"];
			$N_SAN[$e]=$resultFn["N_SAN"];
			$N_OCC[$e]=$resultFn["N_OCC"];
			$N_ContactAdd[$e]=$resultFn["N_ContactAdd"];
			$N_CARDREF[e]=$resultFn["N_CARDREF"];
			if($N_IDCARD[$e]=="")
			{
				$N_IDCARD[$e]=$N_CARDREF[e];
			}
			else
			{
				$N_CARD[$e]="บัตรประชาชน";
			}
		}
	}

?>

<div style="width:900px; overflow-x:scroll;">

<?php
	echo "<form method=\"post\" name=\"form1\" action=\"CardProcessMigrateCustomer.php\">";
	echo "<table cellspacing=1 bgcolor=\"#CCCCCC\" cellpadding=2 width=2100>";
	echo "<tr bgcolor=\"#2299CC\">";
	echo "<td align=center><b>เลือก</b></td>";
	echo "<td align=center><b>รหัสลูกค้า</b></td>";
	echo "<td align=center><b>ชื่อ - สกุล</b></td>";
	echo "<td align=center><b>อายุ</b></td>";
	echo "<td align=center><b>คู่สมรส</b></td>";
	echo "<td align=center><b>ที่อยู่เลขที่</b></td>";
	echo "<td align=center><b>หมู่</b></td>";
	echo "<td align=center><b>ซอย</b></td>";
	echo "<td align=center><b>ถนน</b></td>";
	echo "<td align=center><b>ตำบล</b></td>";
	echo "<td align=center><b>อำเภอ</b></td>";
	echo "<td align=center><b>จังหวัด</b></td>";
	echo "<td align=center><b>รหัสไปรษณีย์</b></td>";
	echo "<td align=center><b>สัญชาติ</b></td>";
	echo "<td align=center><b>ประเภทบัตร</b></td>";
	echo "<td align=center><b>เลขที่บัตร</b></td>";
	echo "<td align=center><b>วันที่ออกบัตร</b></td>";
	echo "<td align=center><b>ออกให้โดย</b></td>";
	echo "<td align=center><b>อาชีพ</b></td>";
	echo "<td align=center width=200><b>อื่นๆ</b></td>";
	echo "</tr>";

	for($z=1 ; $z <= $check ; $z++)
	{
		if($z%2==0)
		{
			echo "<tr bgcolor=#FFFFCC>";
		}
		else
		{
			echo "<tr bgcolor=#FFFFAB>";
		}

		echo "<td align=center><input type=\"radio\" name=\"CusSelect\" value=\"$CusID[$z]\"></td>";
		echo "<td align=center>$CusID[$z]</td>";
		echo "<td>$A_FIRNAME[$z] $A_NAME[$z] $A_SIRNAME[$z]</td>";
		echo "<td align=center>$N_AGE[$z]</td>";
		echo "<td>$A_PAIR[$z]</td>";
		echo "<td align=center>$A_NO[$z]</td>";
		echo "<td align=center>$A_SUBNO[$z]</td>";
		echo "<td align=center>$A_SOI[$z]</td>";
		echo "<td align=center>$A_RD[$z]</td>";
		echo "<td align=center>$A_TUM[$z]</td>";
		echo "<td align=center>$A_AUM[$z]</td>";
		echo "<td align=center>$A_PRO[$z]</td>";
		echo "<td align=center>$A_POST[$z]</td>";
		echo "<td align=center>$N_SAN[$z]</td>";
		echo "<td align=center>$N_CARD[$z]</td>";
		echo "<td align=center>$N_IDCARD[$z]</td>";
		echo "<td align=center>$N_OT_DATE[$z]</td>";
		echo "<td align=center>$N_BY[$z]</td>";
		echo "<td align=center>$N_OCC[$z]</td>";
		echo "<td>$N_ContactAdd[$z]</td>";
	
		echo "<input type=\"hidden\" name=\"CusNo$z\" value=\"$CusID[$z]\">";
		echo "<input type=\"hidden\" name=\"N_IDCARD$z\" value=\"$N_IDCARD[$z]\">";
		echo "<input type=\"hidden\" name=\"migrate_row\" value=\"$check\">";

	
		//echo "<input type=\"hidden\" name=\"N_IDCARD\" value=\"$N_IDCARD[$z]\">";
		echo "</tr>";
	}

	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=21><input type=\"submit\" value=\"เลือก\"></td></tr></tabel><br>";
	echo "</form>";
}
else
{
	echo "<center><h2>จากการกรองด้วย เลขที่บัตร ไม่พบลูกค้าที่ซ้ำกันแล้ว</h2></center>";
}

?>
</div>

</body>
</html>