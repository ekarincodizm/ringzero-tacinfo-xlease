<?php
include("../../../config/config.php");
set_time_limit(0);
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php

pg_query("BEGIN WORK");
$status = 0;

// หารายการที่ เลขตัวถัง ไม่ซ้ำ
$qry_noSame = pg_query("select distinct \"C_CARNUM\", count(\"C_CARNUM\") as \"countC_CARNUM\" from \"Fc\"
						where \"C_CARNUM\" is not null and \"C_CARNUM\" <> '' and \"C_CARNUM\" <> 'ตามใบแนบ'
						group by \"C_CARNUM\" having count(\"C_CARNUM\") = '1' ");
while($res_noSame = pg_fetch_array($qry_noSame))
{
	$C_CARNUM_noSame = $res_noSame["C_CARNUM"]; // เลขตัวถัง
	
	// หา CarID
	$qry_CarID_noSame = pg_query("select \"CarID\" from \"Fc\" where \"C_CARNUM\" = '$C_CARNUM_noSame' ");
	while($resCarID_noSame = pg_fetch_array($qry_CarID_noSame))
	{
		$CarID_noSame = $resCarID_noSame["CarID"];
	}
	
	$strInsert_noSame = "insert into \"Fc_duplicate_log\"(\"LastCarID\", \"SameCarID\") values('$CarID_noSame', '$CarID_noSame') ";
	if($result=pg_query($strInsert_noSame)){
	}else{
		$status++;
	}
}


//----- หารายการที่มีเลขตัวถังซ้ำ
$qry_main = pg_query("select distinct \"C_CARNUM\", count(\"C_CARNUM\") as \"countC_CARNUM\" from \"Fc\"
						where \"C_CARNUM\" is not null and \"C_CARNUM\" <> '' and \"C_CARNUM\" <> 'ตามใบแนบ'
						group by \"C_CARNUM\" having count(\"C_CARNUM\") > '1' ");			
while($resMain = pg_fetch_array($qry_main))
{
	$C_CARNUM = $resMain["C_CARNUM"];
	
	// หาเลขที่มากที่สุด
	$qry_maxID = pg_query("select max(replace(\"CarID\",'TAX','')::numeric)::numeric as \"maxCarID\" from \"Fc\" where \"C_CARNUM\" = '$C_CARNUM' ");
	while($resMaxID = pg_fetch_array($qry_maxID))
	{
		$maxCarID = $resMaxID["maxCarID"]; // เลขรหัสที่มากที่สุด
	}
	
	// เติมเลขให้ครบ 5 หลัก
	if(strlen($maxCarID) < 5)
	{
		do{
			$maxCarID = "0".$maxCarID;
		}while(strlen($maxCarID) < 5);
	}
	
	$LastCarID = "TAX".$maxCarID; // รหัสที่มากที่สุด ที่มีเลขตัวถังเหมือนกัน
	
	// เพิ่มข้อมูลของรายการที่มากที่สุดก่อน
	$strInsert_FSame = "insert into \"Fc_duplicate_log\"(\"LastCarID\", \"SameCarID\") values('$LastCarID', '$LastCarID') ";
	if($result=pg_query($strInsert_FSame)){
	}else{
		$status++;
	}
	
	// หา CarID อื่นที่มีเลขตัวถังเหมือนกัน
	$qry_oldID = pg_query("select \"CarID\" from \"Fc\" where \"C_CARNUM\" = '$C_CARNUM' and \"CarID\" <> '$LastCarID' ");
	while($resOldID = pg_fetch_array($qry_oldID))
	{
		$oldCarID = $resOldID["CarID"];
		
		$strInsert_inSame = "insert into \"Fc_duplicate_log\"(\"LastCarID\", \"SameCarID\") values('$LastCarID', '$oldCarID') ";
		if($result=pg_query($strInsert_inSame)){
		}else{
			$status++;
		}
	}
}


//++++++++++ Update ตารางอื่นๆที่มี CarID เป็นส่วนประกอบ

$qry_SCarID = pg_query("select distinct \"LastCarID\" from \"Fc_duplicate_log\" where \"LastCarID\" <> \"SameCarID\" ");
while($res_SCarID = pg_fetch_array($qry_SCarID))
{
	$LastCarID = $res_SCarID["LastCarID"]; // CarID ล่าสุด
	
	$i = 0;
	
	// หา CarID ที่มีเลขตัวถังเหมือนกัน
	$qry_OCarID = pg_query("select \"SameCarID\" from \"Fc_duplicate_log\" where \"LastCarID\" = '$LastCarID' and \"SameCarID\" <> '$LastCarID' ");
	while($res_OCarID = pg_fetch_array($qry_OCarID))
	{
		$i++;
		$SameCarID = $res_OCarID["SameCarID"]; // CarID ที่มีเลขตัวถังเหมือนกัน
		
		$CarIDWhere[$i] = $SameCarID;
	}
	
	//-------------- Update ตารางอื่นๆที่มี CarID เป็นส่วนประกอบ
	$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as cl
			from INFORMATION_SCHEMA.COLUMNS
			where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
			and data_type in('character varying','text','character','char','regclass','name')";
	$query = pg_query($sql);
	while($re = pg_fetch_array($query))
	{
		$SCHEMA = $re['sm']; // ชื่อ schema
		$realtb = $re['tb']; // ชื่อ ตาราง
		$column = $re['cl']; // ชืิ่อ column
		
		// ถ้าเป็นตาราง Fc_duplicate_log , Fc ไม่ต้องทำ
		if(($SCHEMA == "public" && $realtb == "Fc_duplicate_log") || ($SCHEMA == "public" && $realtb == "Fc"))
		{
			continue;
		}
		
		// หา column ที่มีลักษณะของ CarID
		$sql1 = "select \"$column\" as \"CarID\" from $SCHEMA.\"$realtb\" where \"$column\" LIKE 'TAX%' limit 1";
		$query1 = pg_query($sql1);
		$rows = pg_num_rows($query1);
		$re1 = pg_fetch_array($query1);
		if($rows > 0 )
		{
			$chkdigi = trim($re1['CarID']); // CarID
			$chkre = substr($chkdigi,3); // ตัวอักษรด้านหน้าออก 3 ตัว
			$cjkre2 = strlen($chkre); // จำนวนตัวอักษรที่เหลือ

			if($cjkre2 == 5) // ถ้าตัวอักษรที่เหลือเท่ากับ 5 ตัว
			{
				if(is_numeric($chkre)) // ถ้าตัวอักษรที่เหลือเป็นตัวเลขทั้งหมด
				{
					for($r=1; $r<=$i; $r++)
					{
						if($r == 1)
						{
							$strWhere = " \"$column\" = '$CarIDWhere[$r]' ";
						}
						else
						{
							$strWhere = "$strWhere or \"$column\" = '$CarIDWhere[$r]' ";
						}
					}
					
					$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\"='$LastCarID' where $strWhere ";
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


if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h2>SUCCESS</h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>ERROR</h2></center>";
}
?>