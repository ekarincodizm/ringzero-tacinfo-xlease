<?php
include("../../config/config.php");

$sm = $_GET["sm"]; // SCHEMA
$tb = $_GET["tb"]; // ชื่อตาราง
$cl = $_GET["cl"]; // ชื่อ column
$mysearch = $_GET["mysearch"]; // ข้อมูลที่จะค้นหา
$whereData = $_GET["whereData"]; // รูปแบบในการค้นหา
?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<title>รายละเอียด</title>

<br>
<center>
<b>ข้อมูลในตาราง <?php echo "\"$sm\".\"$tb\""; ?> ของ <?php if($whereData==1){echo " \"$cl\" = '$mysearch'";}else{echo " \"$cl\" like '%$mysearch%'";} ?></b>
<table>
<tr align="center" bgcolor="#79BCFF">
<?php
$i = 0;
$qry_table = pg_query("select column_name, data_type, character_maximum_length
						from INFORMATION_SCHEMA.COLUMNS
						where TABLE_NAME = '$tb' order by column_name ");
while($res_table = pg_fetch_array($qry_table))
{
	$i++;
	$column_name[$i] = $res_table["column_name"];
	$data_type = $res_table["data_type"];
	$char_length = $res_table['character_maximum_length'];
	
	if($char_length != ""){$data_type_text = "$data_type($char_length)";}else{$data_type_text = $data_type;}
	
	echo "<td align=\"center\" style=\"padding:0px 7px;\">";
	echo "<b>$column_name[$i]</b><br>($data_type_text)";
	echo "</td>";
}
echo "</tr>";

if($whereData == "1")
{
	$qry_column = pg_query("select * from \"$sm\".\"$tb\" where \"$cl\" = '$mysearch' ");
}
else
{
	$qry_column = pg_query("select * from \"$sm\".\"$tb\" where \"$cl\" like '%$mysearch%' ");
}

$n = 0;
while($res_column = pg_fetch_array($qry_column))
{
	$n++;
	
	if($n%2==0){
		echo "<tr class=\"odd\">";
	}else{
		echo "<tr class=\"even\">";
	}
	
	for($m=1; $m<=$i; $m++)
	{
		$column_data_temp = $res_column["$column_name[$m]"];
		
		echo "<td>";
		echo $column_data_temp;
		echo "</td>";
	}
	echo "</tr>";
}

if($n==0){echo "<tr><td align=\"center\" colspan=\"$i\">ไม่พบข้อมูล!!</td></tr>";}
else{echo "<tr><td align=\"left\" colspan=\"$i\" class=\"red\">รวมพบทั้งหมด $n แถว</td></tr>";}
?>
</table>
</center>