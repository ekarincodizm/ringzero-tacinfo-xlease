<?php
$s=mssql_select_db("Thaiace") or die("Can't select database");
if($condition==2){
	echo "<div style=\"width:500px;margin:0px auto;\"><table border=0><tr><td width=15 bgcolor=#F4CEFD></td><td>= ปีที่ไม่เท่ากัน</td></tr></table></div>";
}
?>
<table align="center" width="500" border="0" cellspacing="1" cellpadding="1" bgcolor="#F4FCFF">
	<tr align="center" bgcolor="#35B7F4">
		<th height="30" width="200">เลขที่สัญญา</th>
		<th>ทะเบียนรถ (เก่า)</th>
		<th>ปีรถ (เก่า)</th>
		<th>ทะเบียนรถ (ใหม่)</th>
		<th>ปีรถ (ใหม่)</th>
	</tr>
	<?php 
	$query = pg_query("select \"Fp\".\"IDNO\" , \"C_YEAR\",\"car_year\",\"C_REGIS\",\"car_regis\" from \"Fp\" 
	left join \"VCarregistemp\"  on \"Fp\".\"IDNO\" = \"VCarregistemp\".\"IDNO\"
	left join \"FGas\" on \"Fp\".\"asset_id\" = \"FGas\".\"GasID\" where \"VCarregistemp\".\"CarID\" is not null order by \"VCarregistemp\".\"IDNO\""); 
	$i=0;
	$IDNO="";
	$C_YEAR1="";
	$C_YEAR2="";
	while($result = pg_fetch_array($query)){
		$IDNO = $result["IDNO"];
		$C_YEAR1 = $result["C_YEAR"];
		$C_REGIS = $result["C_REGIS"];
		
		if($C_YEAR1==""){
			$C_YEAR1=$result["car_year"];
			$C_REGIS = $result["car_regis"];
		}
		
		$sql=mssql_query("select C_YEAR,C_REGIS from Fc where IDNO = '$IDNO' AND (C_YEAR <> '0' AND C_YEAR <> '---' AND C_YEAR <> '--' AND C_YEAR <> '202' AND C_YEAR <> '-')",$conn); 
		$numrow=mssql_num_rows($sql);
		if($res = mssql_fetch_array($sql)){
			$C_YEAR2=trim(iconv('WINDOWS-874','UTF-8',$res["C_YEAR"]));
			$C_REGIS2=trim(iconv('WINDOWS-874','UTF-8',$res["C_REGIS"]));
		}
		if($numrow > 0){
			if(($condition == 1) and ($C_YEAR1!=$C_YEAR2)){
				if($i%2 ==0){
					$color="#E9F8FE";
				}else{
					$color="#C9EBFC";
				}
				echo "<tr align=center bgcolor=$color height=25>";
				echo "<td>$IDNO</td>";
				echo "<td>$C_REGIS2</td>";
				echo "<td>$C_YEAR2</td>";
				echo "<td>$C_REGIS</td>";
				echo "<td>$C_YEAR1</td>";
				echo "</tr>";
				$i++;
			}else if($condition == 2){
				if($C_YEAR1!=$C_YEAR2){
					$color="#F4CEFD";
				}else{
					$color="#C9EBFC";
				}
				echo "<tr align=center bgcolor=$color height=25>";
				echo "<td>$IDNO</td>";
				echo "<td>$C_REGIS2</td>";
				echo "<td>$C_YEAR2</td>";
				echo "<td>$C_REGIS</td>";
				echo "<td>$C_YEAR1</td>";
				echo "</tr>";
				$i++;
			}
		}
	} //end while
	?>
	<tr height="30" bgcolor="#35B7F4"><td colspan="5"><b>รวม <U><?php echo $i;?></U> รายการ</b></td></tr>
</table>