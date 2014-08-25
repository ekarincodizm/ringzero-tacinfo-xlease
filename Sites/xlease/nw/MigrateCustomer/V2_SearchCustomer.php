<?php
include("../../config/config.php");
set_time_limit(600);
?>
<html>
<head>
<title>ค้นหาลูกค้าที่ซ้ำ โดยกรองจาก ชื่อ - นามสกุล</title>
<script type="text/javascript">

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>

</head>
<body onkeydown='if(event.keyCode==13) { document.form1.submit();} '>

<?php
$test_sql=pg_query("select * from public.\"Fa1\" order by \"CusID\" ");
$rowtest=pg_num_rows($test_sql);
$i = 1;
while($result=pg_fetch_array($test_sql))
{
	$CusID[$i]=$result["CusID"];
	$A_NAME[$i]=$result["A_NAME"];
	$A_SIRNAME[$i]=$result["A_SIRNAME"];
	
	$test_sql2=pg_query("select COUNT(*) AS \"total\" from public.\"Fa1\" where \"A_NAME\" = '$A_NAME[$i]' and \"A_SIRNAME\" = '$A_SIRNAME[$i]' ");
	while($result2=pg_fetch_array($test_sql2))
	{
		$test2=$result2["total"];
	}
	
	if($test2 > 1)
	{
		break;
	}

	$i++;
}
if($test2 > 1)
{
	//$test_sql3=pg_query("select * from public.\"Fa1\" where \"A_NAME\" = '$A_NAME[$i]' and \"A_SIRNAME\" = '$A_SIRNAME[$i]' order by \"CusID\" ");
	
	$test_sql3=pg_query("select \"Fa1\".\"CusID\" , \"Fa1\".\"A_FIRNAME\" , \"Fa1\".\"A_NAME\" , \"Fa1\".\"A_SIRNAME\" , \"Fa1\".\"A_PAIR\" , \"Fa1\".\"A_NO\"
						, \"Fa1\".\"A_SUBNO\" , \"Fa1\".\"A_SOI\" , \"Fa1\".\"A_RD\" , \"Fa1\".\"A_TUM\" , \"Fa1\".\"A_AUM\" , \"Fa1\".\"A_PRO\" , \"Fa1\".\"A_POST\"
						from public.\"Fa1\" , public.\"Fn\"
						where \"Fa1\".\"CusID\" = \"Fn\".\"CusID\"
							and \"Fa1\".\"A_NAME\" = '$A_NAME[$i]' and \"Fa1\".\"A_SIRNAME\" = '$A_SIRNAME[$i]'
							order by \"Fn\".\"N_AGE\" , \"Fn\".\"N_OT_DATE\" , \"Fa1\".\"CusID\" ");
	$rowtest=pg_num_rows($test_sql3);
	$a = 0;
	while($result=pg_fetch_array($test_sql3))
	{
		$a++;
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
	
		$TrimCusID = trim($CusID[$a]);
	
		$test_sql4=pg_query("select * from public.\"Fn\" where \"CusID\" = '$TrimCusID' ");
		while($resultFn=pg_fetch_array($test_sql4))
		{
			$N_AGE[$a]=$resultFn["N_AGE"];
			$N_CARD[$a]=$resultFn["N_CARD"];
			$N_IDCARD[$a]=$resultFn["N_IDCARD"];
			$N_OT_DATE[$a]=$resultFn["N_OT_DATE"];
			$N_BY[$a]=$resultFn["N_BY"];
			$N_SAN[$a]=$resultFn["N_SAN"];
			$N_OCC[$a]=$resultFn["N_OCC"];
			$N_ContactAdd[$a]=$resultFn["N_ContactAdd"];
			$N_CARDREF[$a]=$resultFn["N_CARDREF"];
			if($N_IDCARD[$a]=="")
			{
				$N_IDCARD[$a]=$N_CARDREF[$a];
			}
			else
			{
				$N_CARD[$a]=="บัตรประชาชน";
			}
		}
	}
?>

<!-- <div style="width:900px; overflow-x:scroll;"> -->

<?php

	$sql_migrate=pg_query("select \"CountRows\" as total , \"A_SIRNAME\"
	from
	(
		SELECT \"A_NAME\" , \"A_SIRNAME\" , COUNT('A_NAME') AS \"CountRows\"
		FROM \"Fa1\"
		GROUP BY \"A_NAME\" , \"A_SIRNAME\"
		order by \"A_SIRNAME\"
	) as test
		where \"CountRows\" > '1'
		order by \"total\" ");

	$row_nigrate=pg_num_rows($sql_migrate);
	

	echo "<br><center><h1><FONT COLOR=#0000FF>ค้นหาลูกค้าที่ซ้ำ โดยกรองจาก ชื่อ - นามสกุล</FONT></h1></center>";
	echo "<center><h2><FONT COLOR=#fe4b4b>(เหลือลูกค้าที่ซ้ำกันอีกประมาณ  $row_nigrate  คน)</FONT></h2></center>";

	echo "<form method=\"post\" name=\"form1\" action=\"V2_ProcessMigrateCustomer.php\">";
?>
<?php
	echo "<table cellspacing=1 bgcolor=\"#CCCCCC\" cellpadding=2 width=2100>";
	echo "<tr bgcolor=\"#2299CC\">";
	echo "<td align=center><b>แก้ไข</b></td>";
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

	for($z=1 ; $z <= $a ; $z++)
	{
		if($z%2==0)
		{
			echo "<tr bgcolor=#FFFFCC>";
		}
		else
		{
			echo "<tr bgcolor=#FFFFAB>";
		}

		//echo "<td align=center><input type=\"button\" name=\"on\" id=\"on\" value=\"แก้ไข\" onclick=\"javascript:popU('EditCus.php?editID=$CusID[$z]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350')\"></td>";
		echo "<td align=center><input type=\"button\" name=\"on\" id=\"on\" value=\"แก้ไข\" onclick=\"javascript:popU('../manageCustomer/frm_Edit.php?CusID=$CusID[$z]&MigrateCus=yes','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=750')\"></td>";
		echo "<td align=center><input type=\"radio\" name=\"CusSelect\" value=\"$CusID[$z]\" checked=\"checked\"></td>";
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
	
		echo "<input type=\"hidden\" name=\"A_NAME\" value=\"$A_NAME[$z]\">";
		echo "<input type=\"hidden\" name=\"A_SIRNAME\" value=\"$A_SIRNAME[$z]\">";
		echo "</tr>";
	}

	//echo "<tr bgcolor=\"#FFFFFF\"><td colspan=4 align=center>รวม $test2 คน</td></tr>";

	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=21><input type=\"submit\" value=\"เลือก\"></td></tr></tabel><br>";
	echo "</form>";
}
else
{
	echo "<center><h2>จากการกรองด้วย ชื่อ - สกุล ไม่พบลูกค้าที่ซ้ำกันแล้ว</h2></center>";
}

?>

<!-- </div> -->

</body>
</html>