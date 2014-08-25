<?php
include("../../config/config.php");
set_time_limit(0);

$mysearch = $_GET["mydata"]; // ข้อมูลที่จะค้นหา
$whereData = $_GET["whereData"]; // รูปแบบในการค้นหา
$showView = $_GET["showView"]; // การค้นหารวม VIEW หรือไม่  1 รวม 2 ไม่รวม
$showSerial = $_GET["showSerial"]; // การค้นหารวมฟิลด์ Serial หรือไม่  1 รวม 2 ไม่รวม

if($showView == 1 && $showSerial == 1){$textShow = "(รวม VIEW และ รวมฟิลด์ Serial)";}
elseif($showView == 1 && $showSerial != 1){$textShow = "(รวม VIEW แต่ ไม่รวมฟิลด์ Serial)";}
elseif($showView != 1 && $showSerial == 1){$textShow = "(ไม่รวม VIEW แต่ รวมฟิลด์ Serial)";}
else{$textShow = "(ไม่รวม VIEW และ ไม่รวมฟิลด์ Serial)";}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function subvalidate() 
{
	var subTheMessage = "Please complete the following: \n-----------------------------------\n";
	var subNoErrors = subTheMessage

	if ($("#textUpdate").val() == ""){
		subTheMessage = subTheMessage + "\n -->  กรุณาระบุด้วยว่าจะให้เปลี่ยนข้อความเป็นอะไร";
	}
	
	if($('input[name="updatelist[]"]:checked').length==0)
	{
		subTheMessage = subTheMessage + "\n -->  กรุณาเลือกว่าจะให้ update ฟิลด์ใดบ้าง";
	}

	// If no errors, submit the form
	if (subTheMessage == subNoErrors) {
		return true;
	} 
	else
	{
		// If errors were found, show alert message
		alert(subTheMessage);
		return false;
	}
}

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

<form name="frm2" method="post" action="processUpdate.php">
<?php
if($mysearch != "" && $whereData != "")
{
	if($whereData == "1"){echo "<font color=\"#888888\">DATA = '$mysearch'</font>";}
	else{echo "<font color=\"#888888\">DATA LIKE '%$mysearch%'</font>";}
	
	echo "<font color=\"#888888\"> $textShow</font>";
	
	$i = 0;
?>
	<table>
	<tr align="center" bgcolor="#79BCFF">
		<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TABLE_SCHEMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TABLE_NAME &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COLUMN_NAME &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; data_type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; constraint_type &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
		<th>&nbsp;&nbsp; รายละเอียด &nbsp;&nbsp;</th>
		<th>&nbsp;&nbsp; ทำรายการ &nbsp;&nbsp;</th>
	</tr>
<?php
	if($showView == 1 && $showSerial == 1)
	{
		$sql = "select a.TABLE_SCHEMA as sm, a.TABLE_NAME as tb, a.COLUMN_NAME as cl, a.data_type, a.character_maximum_length, d.constraint_type
				from INFORMATION_SCHEMA.COLUMNS a
				LEFT JOIN (SELECT b.table_schema, b.table_name, c.column_name, b.constraint_name, b.constraint_type
				FROM information_schema.table_constraints b, information_schema.constraint_column_usage c
				where b.constraint_name = c.constraint_name) d ON a.column_name = d.column_name and a.table_name = d.table_name
				order by a.TABLE_SCHEMA, a.TABLE_NAME";
	}
	elseif($showView == 1 && $showSerial != 1)
	{
		$sql = "select a.TABLE_SCHEMA as sm, a.TABLE_NAME as tb, a.COLUMN_NAME as cl, a.data_type, a.character_maximum_length, d.constraint_type
				from INFORMATION_SCHEMA.COLUMNS a
				LEFT JOIN (SELECT b.table_schema, b.table_name, c.column_name, b.constraint_name, b.constraint_type
				FROM information_schema.table_constraints b, information_schema.constraint_column_usage c
				where b.constraint_name = c.constraint_name) d ON a.column_name = d.column_name and a.table_name = d.table_name
				where a.data_type not in('serial')
				order by a.TABLE_SCHEMA, a.TABLE_NAME";
	}
	elseif($showView != 1 && $showSerial == 1)
	{
		$sql = "select a.TABLE_SCHEMA as sm, a.TABLE_NAME as tb, a.COLUMN_NAME as cl, a.data_type, a.character_maximum_length, d.constraint_type
				from INFORMATION_SCHEMA.COLUMNS a
				LEFT JOIN (SELECT b.table_schema, b.table_name, c.column_name, b.constraint_name, b.constraint_type
				FROM information_schema.table_constraints b, information_schema.constraint_column_usage c
				where b.constraint_name = c.constraint_name) d ON a.column_name = d.column_name and a.table_name = d.table_name
				where a.TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
				order by a.TABLE_SCHEMA, a.TABLE_NAME";
	}
	else
	{
		$sql = "select a.TABLE_SCHEMA as sm, a.TABLE_NAME as tb, a.COLUMN_NAME as cl, a.data_type, a.character_maximum_length, d.constraint_type
				from INFORMATION_SCHEMA.COLUMNS a
				LEFT JOIN (SELECT b.table_schema, b.table_name, c.column_name, b.constraint_name, b.constraint_type
				FROM information_schema.table_constraints b, information_schema.constraint_column_usage c
				where b.constraint_name = c.constraint_name) d ON a.column_name = d.column_name and a.table_name = d.table_name
				where a.TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
				and a.data_type not in('serial')
				order by a.TABLE_SCHEMA, a.TABLE_NAME";
	}
	$query = pg_query($sql);
	while($re = pg_fetch_array($query))
	{
		$SCHEMA = $re['sm']; // ชื่อ schema
		$realtb = $re['tb']; // ชื่อ ตาราง
		$column = $re['cl']; // ชืิ่อ column
		$data_type = $re['data_type'];
		$constraint_type = $re['constraint_type'];
		$char_length = $re['character_maximum_length'];
		
		if($char_length != ""){$data_type_text = "$data_type($char_length)";}else{$data_type_text = $data_type;}
		
		if($whereData == "1"){$sql1 = "select \"$column\" from $SCHEMA.\"$realtb\" where \"$column\"::text = '$mysearch' limit 1";}
		else{$sql1 = "select \"$column\" from $SCHEMA.\"$realtb\" where \"$column\"::text like '%$mysearch%' limit 1";}
		$query1 = pg_query($sql1);
		$rows = pg_num_rows($query1);
		$re1 = pg_fetch_array($query1);
		if($rows > 0 )
		{
			$i++;
			
			if($i%2==0){
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
			}
			
			echo "<td align=\"left\">$SCHEMA</td>";
			echo "<td align=\"left\">$realtb</td>";
			echo "<td align=\"left\">$column</td>";
			echo "<td align=\"center\">$data_type_text</td>";
			echo "<td align=\"center\">$constraint_type</td>";
			echo "<td align=\"center\"><span onclick=\"javascript:popU('detailTable.php?sm=$SCHEMA&tb=$realtb&cl=$column&mysearch=$mysearch&whereData=$whereData','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=800')\" style=\"cursor: pointer;\"><img src=\"images/detail.gif\" height=\"19\" width=\"19\" border=\"0\"></span></td>";
			echo "<td align=\"center\"><input type=\"checkbox\" name=\"updatelist[]\" value=\"$SCHEMA#$realtb#$column\" checked></td>";
			echo "</tr>";
		}
	}
	if($i==0){echo "<td align=\"center\" colspan=\"7\">ไม่พบข้อมูล!!</td>";}
	else{echo "<td align=\"left\" colspan=\"7\" class=\"red\">รวมพบทั้งหมด $i ฟิลด์</td>";}
	echo "</table>";
	
	if($showView == 2)
	{
?>
		<br>แก้ไขข้อมูล <input type="text" name="textUpdate" id="textUpdate" size="40">
		<input type="submit" id="btn_sentUpdate" value="UPDATE" onclick="return subvalidate()">
		<font color="#FF0000">* การแก้ไขข้อมูลมีผลต่อ database ทันที โปรดระมัดระวังในการ UPDATE ข้อมูล</font>
<?php
	}
}
else
{
	echo "<center><h1><font color=\"#FF0000\">ไม่พบข้อมูล!!</font></h1></center>";
}
?>
<input type="hidden" name="submysearch" value="<?php echo $mysearch; ?>">
<input type="hidden" name="subwhereData" value="<?php echo $whereData; ?>">
<input type="hidden" name="subshowView" value="<?php echo $showView; ?>">
<input type="hidden" name="subshowSerial" value="<?php echo $showSerial; ?>">
</form>