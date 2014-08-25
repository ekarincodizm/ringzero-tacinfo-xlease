<?php
include("../../config/config.php");
include("../function/nameMonth.php");
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as cl
		from INFORMATION_SCHEMA.COLUMNS
		where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
		and data_type in('character varying','text','character','char','regclass','name')";
$query = pg_query($sql);

echo "<br>ตารางที่มี CarID อยู่<br>";
echo "<br>รูปแบบ คือ schema.\"ชื่อตาราง\" --> \"ชื่อฟิลด์\"<br>";
echo "<br><hr>";

while($re = pg_fetch_array($query))
{
	$SCHEMA = $re['sm']; // ชื่อ schema
	$realtb = $re['tb']; // ชื่อ ตาราง
	$column = $re['cl']; // ชืิ่อ column
	
	// หา column ที่มีลักษณะของ CarID
	$sql1 = "select \"$column\" as \"CarID\" from $SCHEMA.\"$realtb\" where \"$column\" LIKE 'TAX%' limit 1";
	$query1 = pg_query($sql1);
	$rows = pg_num_rows($query1);
	$re1 = pg_fetch_array($query1);
	if($rows > 0 )
	{
		$chkdigi = trim($re1['CarID']); // CarID
		$chkre = substr($chkdigi,3); // ตัวอักษรด้านหน้าออก 3 ตัว
		$chkre2 = strlen($chkre); // จำนวนตัวอักษรที่เหลือ

		if($chkre2 == 5) // ถ้าตัวอักษรที่เหลือเท่ากับ 5 ตัว
		{
			if(is_numeric($chkre)) // ถ้าตัวอักษรที่เหลือเป็นตัวเลขทั้งหมด
			{
				echo "<br>";
				echo "$SCHEMA.\"$realtb\" --> \"$column\" "; // ชื่อ schema."ชื่อตาราง" --> ชื่อฟิลด์
				echo "<br>";
			}
		}
	}
}
?>