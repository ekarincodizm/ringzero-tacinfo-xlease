<?php
set_time_limit(0);
include("../../config/config.php");
$s=mssql_select_db("Thaiace") or die("Can't select database");
?>
<table align="center" width="500" border="0" cellspacing="1" cellpadding="1" bgcolor="#F4FCFF">
	<tr align="center" bgcolor="#35B7F4">
		<th height="30" width="200">เลขที่สัญญา</th>
		<th>ปีในระบบใหม่</th>
		<th>ปีในระบบเก่า</th>
	</tr>
	<?php 
	$query = pg_query("select \"Fp\".\"IDNO\" , \"C_YEAR\",\"car_year\" from \"Fp\" 
	left join \"VCarregistemp\"  on \"Fp\".\"IDNO\" = \"VCarregistemp\".\"IDNO\"
	left join \"FGas\" on \"Fp\".\"asset_id\" = \"FGas\".\"GasID\" order by \"Fp\".\"IDNO\""); 
	$i=0;
	$IDNO="";
	$C_YEAR1="";
	$C_YEAR2="";
	while($result = pg_fetch_array($query)){
		$IDNO = $result["IDNO"];
		$C_YEAR1 = $result["C_YEAR"];
		
		if($C_YEAR1==""){
			$C_YEAR1=$result["car_year"];
		}
		$sql=mssql_query("select C_YEAR from Fc where IDNO = '$IDNO' AND (C_YEAR <> '0' AND C_YEAR <> '---' AND C_YEAR <> '--' AND C_YEAR <> '202' AND C_YEAR <> '-')",$conn); 
		$numrow=mssql_num_rows($sql);
		if($res = mssql_fetch_array($sql)){
			$C_YEAR2=trim(iconv('WINDOWS-874','UTF-8',$res["C_YEAR"]));
		}
		if($numrow > 0){
			if($C_YEAR1!=$C_YEAR2){
				if($i%2 ==0){
					$color="#E9F8FE";
				}else{
					$color="#C9EBFC";
				}
				echo "<tr align=center bgcolor=$color height=25>";
				echo "<td>$IDNO</td>";
				echo "<td>$C_YEAR1</td>";
				echo "<td>$C_YEAR2</td>";
				echo "</tr>";
				$i++;
			}
		}	
	} //end while
	?>
	<tr height="30" bgcolor="#35B7F4"><td colspan="3"><b>รวม <U><?php echo $i;?></U> รายการ</b></td></tr>
</table>