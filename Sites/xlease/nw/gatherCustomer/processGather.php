<?php
session_start();
include("../../config/config.php");
set_time_limit(0);

$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); // เวลาปัจจุบันจาก postgres

$appv = $_GET["appv"]; // 1 อนุมัติ  2 ไม่อนุมัติ
$autoID=$_GET["autoID"];
if($autoID==""){
	$autoID= $_POST["autoID"];
	if(isset($_POST["appv"])){ 
		$appv ='1';//กดอนุมัติ
	}else{
		$appv ='2';//กดไม่อนุมัติ
	}
}

$qry_cusTemp=pg_query("select * from \"change_cus_temp\" where \"autoID\" ='$autoID' ");
while($cusTemp = pg_fetch_array($qry_cusTemp))
{
	$noCusID=$cusTemp["Cus_old"];
	$selectCusID=$cusTemp["Cus_new"];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
pg_query("BEGIN WORK");
$status = 0;

if($appv == 2)
{
	$qry_noAppv="update \"change_cus_temp\" set \"appvID\"='$app_user', \"appvStamp\"='$app_date', \"appvStatus\"='0' where \"autoID\"='$autoID' and \"appvStatus\"='9' ";
	if($resultNoAppv=pg_query($qry_noAppv))
	{}
	else
	{
		$status++;
	}
}
elseif($appv == 1)
{
	$qry_yesAppv="update \"change_cus_temp\" set \"appvID\"='$app_user', \"appvStamp\"='$app_date', \"appvStatus\"='1' where \"autoID\"='$autoID' and \"appvStatus\"='9' ";
	if($resultYesAppv=pg_query($qry_yesAppv))
	{}
	else
	{
		$status++;
	}
	
	$test_sql=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$noCusID' ");
	$rowNowFa1=pg_num_rows($test_sql);
	if($rowNowFa1 == 0){continue;} // ถ้าไม่เจอ CusID ที่ต้องการจะลบ แสดงว่าเคยลบไปแล้ว ไม่ต้องทำอีก ให้วนรอบต่อไปเลย
	while($result=pg_fetch_array($test_sql))
	{
		$A_FIRNAME_N_CARDREF = $result["A_FIRNAME"];
		$A_NAME_N_CARDREF = $result["A_NAME"];
		$A_SIRNAME_N_CARDREF = $result["A_SIRNAME"];
		$A_PAIR_N_CARDREF = $result["A_PAIR"];
		$A_NO_N_CARDREF = $result["A_NO"];
		$A_SUBNO_N_CARDREF = $result["A_SUBNO"];
		$A_SOI_N_CARDREF = $result["A_SOI"];
		$A_RD_N_CARDREF = $result["A_RD"];
		$A_TUM_N_CARDREF = $result["A_TUM"];
		$A_AUM_N_CARDREF = $result["A_AUM"];
		$A_PRO_N_CARDREF = $result["A_PRO"];
		$A_POST_N_CARDREF = $result["A_POST"];
		$Approved_N_CARDREF = $result["Approved"];

		if($A_FIRNAME_N_CARDREF == ""){$A_FIRNAME_N_CARDREF = "NULL";} else{$A_FIRNAME_N_CARDREF = "'$A_FIRNAME_N_CARDREF'";}
		if($A_NAME_N_CARDREF == ""){$A_NAME_N_CARDREF = "NULL";} else{$A_NAME_N_CARDREF = "'$A_NAME_N_CARDREF'";}
		if($A_SIRNAME_N_CARDREF == ""){$A_SIRNAME_N_CARDREF = "NULL";} else{$A_SIRNAME_N_CARDREF ="'$A_SIRNAME_N_CARDREF'";}
		if($A_PAIR_N_CARDREF == ""){$A_PAIR_N_CARDREF = "NULL";} else{$A_PAIR_N_CARDREF ="'$A_PAIR_N_CARDREF'";}
		if($A_NO_N_CARDREF == ""){$A_NO_N_CARDREF = "NULL";} else{$A_NO_N_CARDREF = "'$A_NO_N_CARDREF'";}
		if($A_SUBNO_N_CARDREF == ""){$A_SUBNO_N_CARDREF = "NULL";} else{$A_SUBNO_N_CARDREF = "'$A_SUBNO_N_CARDREF'";}
		if($A_SOI_N_CARDREF == ""){$A_SOI_N_CARDREF = "NULL";} else{$A_SOI_N_CARDREF = "'$A_SOI_N_CARDREF'";}
		if($A_RD_N_CARDREF == ""){$A_RD_N_CARDREF = "NULL";} else{$A_RD_N_CARDREF = "'$A_RD_N_CARDREF'";}
		if($A_TUM_N_CARDREF == ""){$A_TUM_N_CARDREF = "NULL";} else{$A_TUM_N_CARDREF = "'$A_TUM_N_CARDREF'";}
		if($A_AUM_N_CARDREF == ""){$A_AUM_N_CARDREF = "NULL";} else{$A_AUM_N_CARDREF = "'$A_AUM_N_CARDREF'";}
		if($A_PRO_N_CARDREF == ""){$A_PRO_N_CARDREF = "NULL";} else{$A_PRO_N_CARDREF = "'$A_PRO_N_CARDREF'";}
		if($A_POST_N_CARDREF == ""){$A_POST_N_CARDREF = "NULL";} else{$A_POST_N_CARDREF = "'$A_POST_N_CARDREF'";}

		$CusIDFn  = trim($result["CusID"]);

		$test_sql2=pg_query("select * from public.\"Fn\" where \"CusID\" = '$CusIDFn' ");
		while($resultFn=pg_fetch_array($test_sql2))
		{
			$N_STATE_N_CARDREF = $resultFn["N_STATE"];
			$N_SAN_N_CARDREF = $resultFn["N_SAN"];
			$N_AGE_N_CARDREF = $resultFn["N_AGE"];
			$N_CARD_N_CARDREF = $resultFn["N_CARD"];
			$N_CARDREF_N_IDCARD = $resultFn["N_IDCARD"];
			$N_OT_DATE_N_CARDREF = $resultFn["N_OT_DATE"];
			$N_BY_N_CARDREF = $resultFn["N_BY"];
			$N_OCC_N_CARDREF = $resultFn["N_OCC"];
			$N_ContactAdd_N_CARDREF = $resultFn["N_ContactAdd"];
			$N_CARDREF_N_CARDREF = $resultFn["N_CARDREF"];

			if($N_SAN_N_CARDREF == ""){$N_SAN_N_CARDREF = "NULL";} else{$N_SAN_N_CARDREF = "'$N_SAN_N_CARDREF'";}
			if($N_CARD_N_CARDREF == ""){$N_CARD_N_CARDREF = "NULL";} else{$N_CARD_N_CARDREF = "'$N_CARD_N_CARDREF'";}
			if($N_CARDREF_N_IDCARD == ""){$N_CARDREF_N_IDCARD = "NULL";} else{$N_CARDREF_N_IDCARD = "'$N_CARDREF_N_IDCARD'";}
			if($N_BY_N_CARDREF == ""){$N_BY_N_CARDREF = "NULL";} else{$N_BY_N_CARDREF = "'$N_BY_N_CARDREF'";}
			if($N_OCC_N_CARDREF == ""){$N_OCC_N_CARDREF = "NULL";} else{$N_OCC_N_CARDREF = "'$N_OCC_N_CARDREF'";}
			if($N_ContactAdd_N_CARDREF == ""){$N_ContactAdd_N_CARDREF = "NULL";} else{$N_ContactAdd_N_CARDREF = "'$N_ContactAdd_N_CARDREF'";}
			if($N_CARDREF_N_CARDREF == ""){$N_CARDREF_N_CARDREF = "NULL";} else{$N_CARDREF_N_CARDREF = "'$N_CARDREF_N_CARDREF'";}
		}
	}

	// copy ข้อมูลไปไว้ในตาราง Fa1_temp
	$test_sql3_ref = "insert into public.\"Fa1_temp\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"Approved\")
				values ('$noCusID' , $A_FIRNAME_N_CARDREF , $A_NAME_N_CARDREF , $A_SIRNAME_N_CARDREF , $A_PAIR_N_CARDREF , $A_NO_N_CARDREF , $A_SUBNO_N_CARDREF , $A_SOI_N_CARDREF , $A_RD_N_CARDREF , $A_TUM_N_CARDREF , $A_AUM_N_CARDREF , $A_PRO_N_CARDREF , $A_POST_N_CARDREF , '$Approved_N_CARDREF')";
	if($resultFaTemp=pg_query($test_sql3_ref))
	{}
	else
	{
		$status++;
	}

	// copy ข้อมูลไปไว้ในตาราง Fn_temp
	$test_sql4="insert into public.\"Fn_temp\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_CARDREF\")
				values ('$noCusID' , '$N_STATE_N_CARDREF' , $N_SAN_N_CARDREF , '$N_AGE_N_CARDREF' , $N_CARD_N_CARDREF , $N_CARDREF_N_IDCARD , '$N_OT_DATE_N_CARDREF' , $N_BY_N_CARDREF , $N_OCC_N_CARDREF , $N_ContactAdd_N_CARDREF , $N_CARDREF_N_CARDREF )";
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
	$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as test, data_type as dt
			from INFORMATION_SCHEMA.COLUMNS
			where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
			and data_type in('character varying','text','character','char','regclass','name','character varying[]','ARRAY','\"char\"')";
	$query = pg_query($sql);
	while($re = pg_fetch_array($query))
	{
		$SCHEMA = $re['sm'];
		$realtb = $re['tb'];
		$column = $re['test'];
		$dataType = $re['dt'];
		
		if(($SCHEMA == "public" && $realtb == "change_cus") || ($SCHEMA == "public" && $realtb == "Customer_Temp") || ($SCHEMA == "public" && $realtb == "Fa1_temp") || ($SCHEMA == "public" && $realtb == "Fn_temp") || ($SCHEMA == "public" && $realtb == "change_cus_temp"))
		{
			continue;
		}

		$sql1 = "select \"$column\" as cusid from $SCHEMA.\"$realtb\" where \"$column\"::text LIKE '%$noCusID%' and \"$column\" is not null limit 1";
		$query1 = pg_query($sql1);
		$rows = pg_num_rows($query1);
		if($rows > 0 )
		{
			if($dataType == "ARRAY"){$dataType = "character varying[]";}
			if($dataType == "\"char\""){$dataType = "char";}
			
			$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\" = replace(\"$column\"::text,'$noCusID','$selectCusID')::$dataType where \"$column\"::text LIKE '%$noCusID%' ";
			if($resultFnTemp=pg_query($test_sql7))
			{}
			else
			{
				$status++;
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
	
	// ไม่อนุมัติรายการอื่นๆที่มี CusID ที่จะถูกลบออก
	$qry_noAppv="update \"change_cus_temp\" set \"appvID\"='$app_user', \"appvStamp\"='$app_date', \"appvStatus\"='0' where (\"Cus_old\"='$noCusID' or \"Cus_new\"='$noCusID') and \"appvStatus\"='9' and \"autoID\"<>'$autoID' ";
	if($resultNoAppv=pg_query($qry_noAppv))
	{}
	else
	{
		$status++;
	}
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(ALL) อนุมัติจัดการรวมลูกค้าซ้ำ', '$app_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_approve.php'>";
	echo "<input type=\"button\" value=\" ตกลง \" onclick=\"javascript:RefreshMe();\">";
}
else
{
	pg_query("ROLLBACK");
	echo "<font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br><br>";
	//echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_approve.php'\">";
	echo "<input type=\"button\" value=\" ปิด \" onclick=\"javascript:RefreshMe();\">";
}
?>