<?php
session_start();
include("../../config/config.php");

$revtranstatussubtype_id = $_REQUEST['revtranstatus'];

if($revtranstatussubtype_id != "")
{
	echo "<table width=\"100%\" border=\"0\"  align=\"left\">";
	$qryrevtranstatus=pg_query("SELECT column_name
								FROM INFORMATION_SCHEMA.COLUMNS
								WHERE TABLE_SCHEMA = 'finance' AND TABLE_NAME = 'thcap_receive_transfer_status_subtype'
								AND column_name like 'revtranstatussubtype_ref%'
								AND column_name not like '%desc%'
								ORDER BY column_name");

	$i=0;
	while($resrevtranstatus=pg_fetch_array($qryrevtranstatus))
	{
		$column_name = $resrevtranstatus["column_name"];
		$column_name_desc = $column_name.'_desc';
		
		$sub_qry = pg_query("
			select 
				\"$column_name\" as \"column_value\", 
				\"$column_name_desc\" as \"column_value_desc\"
			from 
				finance.thcap_receive_transfer_status_subtype 
			where 
				revtranstatussubtype_id = '$revtranstatussubtype_id' 
		");
		list($column_value, $column_value_desc) = pg_fetch_array($sub_qry);
		
		if($column_value != "")
		{
			echo "	<tr>
						<td>
							<input type=\"textbox\" name=\"$column_name\" id=\"$column_name\" size=\"40\"><font color=red>$column_value</font> ($column_value_desc)
						</td>
					</tr>";
			$i++;
		}
	}
	echo "</table>";
}
else
{
	echo "Error: พบปัญหาในการโหลดข้อมูล ประเภทการคืนเงินที่ไม่ใช่ค่าสินค้าหรือบริการหลัก";
}
?>