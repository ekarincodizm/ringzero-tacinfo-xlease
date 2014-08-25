<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
?>

<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F4F4F4">
	<tr align="center"  bgcolor="#CCCCCC">
		<td height="30">IDNO</td>
		<td>ยี่ห้อแก๊ส</td>
		<td>เลขถังแก๊สที่ติด</td>
		<td>ทะเบียนรถยนต์ที่ติดถังแก๊ส</td>
		<td>ทะเบียนจังหวัดของรถยนต์</td>
		<td>ปีของรถยนต์</td>
		<td>เลขตัวถังรถยนต์</td>
		<td>เลขเครื่องยนต์</td>
	</tr>
	<?php 
	$query = pg_query("SELECT * FROM pmain.fc where \"C_CARNAME\" like '%ยี่ห้อ%' or \"C_CARNUM\" like '%เลขถัง%'"); 
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query)){
		$IDNO = $result["IDNO"];
		$C_CARNAME = $result["C_CARNAME"]; //ยี่ห้อแก๊ส
		$C_COLOR = $result["C_COLOR"]; //เลขถังแก๊สที่ติด
		$C_REGIS = $result["C_REGIS"]; //ทะเบียนรถที่ติดถังแก๊ส
		$C_REGIS_BY = $result["C_REGIS_BY"]; //รถที่ติดถังจดทะเบียนจังหวัด
		$C_YEAR = $result["C_YEAR"]; //ปีของรถทีติด
	
		$querymar = pg_query("select * from \"Fc\" where \"C_REGIS\" = '$C_REGIS'");
		$resultmar = pg_fetch_array($querymar);
		$C_MARNUM = $resultmar["C_MARNUM"]; //เลขเครื่องยนต์ของรถที่ติดถังแก๊ส
		$C_CARNUM = $resultmar["C_CARNUM"]; // เลขตัวถังรถยนต์ที่ติดถังแก๊ส
		
		$query_fp = pg_query("select * from \"Fp\" where \"IDNO\" ='$IDNO'");
		$num_fp = pg_num_rows($query_fp);
		if($num_fp != 0){
			$query_gas = pg_query("select * from \"FGas\" a
			left join \"Fp\" b on a.\"GasID\" = b.\"asset_id\"
			where b.\"IDNO\" = '$IDNO'");
			$num_gas = pg_num_rows($query_gas);
			
			if($num_gas == 0){
				$i++;
				echo "<tr bgcolor= #FFFFFF>";
				echo "<td align=center valign=top height=25>$IDNO</td>";
				echo "<td align=center valign=top>$C_CARNAME</td>";
				echo "<td valign=top>$C_COLOR</td>";
				echo "<td valign=top>$C_REGIS</td>";
				echo "<td valign=top>$C_REGIS_BY</td>";
				echo "<td valign=top>$C_YEAR</td>";
				echo "<td valign=top align=center>$C_CARNUM</td>";
				echo "<td valign=top>$C_MARNUM</td>";
				echo "</tr>";		
			}
		}
	} //end while

	if($numrows==0 || $i==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการที่ยังไม่เพิ่มในตาราง FGas</b></td><tr>";
	}else{
		echo "<tr bgcolor=#CCCCCC height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>

