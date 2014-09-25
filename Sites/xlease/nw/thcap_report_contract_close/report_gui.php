<?php
include("../../config/config.php");
set_time_limit(120);
?>

<script>
	function popU(U,N,T)
	{
		newWindow = window.open(U, N, T);
	}
</script>

<?php
$focusDate = pg_escape_string($_GET["focusDate"]); // ประจำวันวันที่เลือก
$btn_report = pg_escape_string($_GET["btn_report"]); // กดปุ่มแสดงรายงาน
$selectTime = pg_escape_string($_GET["selectTime"]); // radio วันที่ปิดสัญญา
$yy = pg_escape_string($_GET["yy"]); // ปีของปี
$mm = pg_escape_string($_GET["mm"]); // เดือน
$ym = pg_escape_string($_GET["ym"]); // ปีของเดือน

//---------- กำหนดประเภทสัญญา
	$whereContype = "and (";
	$qry_typeContract = pg_query("select \"conType\" as \"typecontract\" from \"thcap_contract_type\" order by \"conType\" ASC");
	while($loop_typeContract = pg_fetch_array($qry_typeContract))
	{
		$typecontract = $loop_typeContract["typecontract"];
		
		$looptypecontract[$typecontract] = pg_escape_string($_GET["$typecontract"]);
		
		if($looptypecontract[$typecontract] == "on")
		{
			if($whereContype == "and (")
			{
				$whereContype .= "\"thcap_get_contractType\"(\"contractID\") = '$typecontract' ";
				
				$conTypeSelectText .= "$typecontract";
			}
			else
			{
				$whereContype .= "or \"thcap_get_contractType\"(\"contractID\") = '$typecontract' ";
				
				$conTypeSelectText .= ", $typecontract";
			}
		}
	}
	$whereContype .= ")";

	if($whereContype == "and ()")
	{
		$whereContype = "";
		
		$conTypeSelectText = "ทั้งหมด";
	}
//---------- จบการกำหนดประเภทสัญญา

if($selectTime == "y")
{
	$selectTimeText = "วันที่ปิดบัญชี ประจำปี $yy";
	$selectWhere .= " AND \"thcap_checkcontractcloseddate\"(\"contractID\")::text like '$yy-%' ";
}
elseif($selectTime == "m")
{
	$selectTimeText = "วันที่ปิดบัญชี ประจำเดือน $mm ปี $ym";
	$selectWhere .= " AND \"thcap_checkcontractcloseddate\"(\"contractID\")::text like '$ym-$mm-%' ";
}
elseif($selectTime == "d")
{
	$selectTimeText = "วันที่ปิดบัญชี ประจำวันที่ $focusDate";
	$selectWhere .= " AND \"thcap_checkcontractcloseddate\"(\"contractID\") = '$focusDate' ";
}
else
{
	$selectTimeText = "วันที่ปิดบัญชี ทั้งหมด";
}
?>

<br/>
<fieldset><legend><B>ผลการค้นหา สัญญาที่ปิดบัญชีแล้ว</B></legend>
	<center>
		<table width="100%" bgcolor="#AAAAAA">
			<tr bgcolor="#FFFFFF">
				<td colspan="6"><b><?php echo "ประเภทสินเชื่อ $conTypeSelectText $selectTimeText"; ?></b></td>
			</tr>
			<tr bgcolor="#79BCFF">
				<th>รายการ</th>
				<th>เลขที่สัญญา</th>
				<th>วันที่ปิดบัญชี</th>
			</tr>
			<?php
			$qry_contract = pg_query("SELECT \"contractID\", \"thcap_checkcontractcloseddate\"(\"contractID\") as \"closeDate\"
									FROM \"thcap_contract\"
									WHERE \"thcap_checkcontractcloseddate\"(\"contractID\") is not null $selectWhere $whereContype
									ORDER BY \"thcap_checkcontractcloseddate\"(\"contractID\"), \"contractID\" ");
			$i = 0;
			while($res_contract = pg_fetch_array($qry_contract))
			{
				$i++;
				
				$contractID = $res_contract["contractID"];
				$closeDate = $res_contract["closeDate"];
				
				if($i%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
				
				echo "<td align=\"center\">".number_format($i,0)."</td>";
				echo "<td align=\"center\" onClick=\"popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1200,height=700')\"><font color=\"#0000FF\" style=\"cursor:pointer;\"><u>$contractID</u></font></td>";
				echo "<td align=\"center\">$closeDate</td>";
				echo "</tr>";
			}
			
			if($i > 0)
			{
				echo "<tr bgcolor=\"#FFCCCC\">";
				echo "<td colspan=\"3\" align=\"left\"><b>รวม ".number_format($i,0)." รายการ</b></td>";
				echo "</tr>";
			}
			else
			{
				echo "<tr bgcolor=\"#FFCCCC\">";
				echo "<td colspan=\"3\" align=\"center\"><b>-- ไม่พบข้อมูล --</b></td>";
				echo "</tr>";
			}
			?>
		</table>
	</center>
</fieldset>