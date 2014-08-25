<?php
set_time_limit(0);
include("../../config/config.php");
$s=mssql_select_db("Thaiace") or die("Can't select database");
?>
<form method="post" action="printyear_pdf.php" target="_blank">
<table align="center" width="500" border="0" cellspacing="1" cellpadding="1" bgcolor="#F4FCFF">
<?php
pg_query("BEGIN WORK");
$status = 0;

$query = pg_query("select \"IDNO\" , \"C_YEAR\",\"car_year\",\"CarID\" from \"Fp\" 
left join \"Fc\"  on \"Fp\".\"asset_id\" = \"Fc\".\"CarID\"
left join \"FGas\" on \"Fp\".\"asset_id\" = \"FGas\".\"GasID\" order by \"IDNO\""); 
$i=0;
$IDNO="";
$C_YEAR1="";
$C_YEAR2="";
while($result = pg_fetch_array($query)){
	$IDNO = $result["IDNO"];
	$CarID = $result["CarID"];
	$C_YEAR1 = $result["C_YEAR"];
	if($C_YEAR1==""){
		$C_YEAR1=$result["car_year"];
		$C_YEARS1[$i]=$C_YEAR1;
	}
		
	$sql=mssql_query("select C_YEAR from Fc where IDNO = '$IDNO' AND (C_YEAR <> '0' AND C_YEAR <> '---' AND C_YEAR <> '--' AND C_YEAR <> '202' AND C_YEAR <> '-')",$conn); 
	$numrow=mssql_num_rows($sql);
	if($res = mssql_fetch_array($sql)){
		$C_YEAR2=trim(iconv('WINDOWS-874','UTF-8',$res["C_YEAR"]));
		
	}
	if($numrow > 0){
		if($C_YEAR1!=$C_YEAR2){
			$IDNO1[$i]=$IDNO;
			$C_YEARS1[$i]=$C_YEAR1;	
			$C_YEARS2[$i]=$C_YEAR2;
			$upyear="update \"Fc\" set \"C_YEAR\"='$C_YEAR2' where \"CarID\"='$CarID'";
			if($resup=pg_query($upyear)){
			}else{
				$status++;
			}
			$i++;		
		}
	}	
} //end while

if($status == 0){
	pg_query("COMMIT");
	echo "<tr align=center height=25 bgcolor=#FFF9EA>";
	echo "<td><h2>บันทึกข้อมูลเีรียบร้อยแล้ว<p>โดยมีการแก้ไขข้อมูลดังนี้</p></h2></td>";
	echo "</tr>";
	$IDNO2="";
	$CYEAR1="";
	$CYEAR2="";
	for($j=0;$j<sizeof($IDNO1);$j++){
		echo "<tr align=center height=25>";
		echo "<td>เลขที่สัญญา <b>$IDNO1[$j]</b> จากเดิม  <b>$C_YEARS1[$j]</b> -> <b>$C_YEARS2[$j]</b></td>";
		echo "</tr>";
		if($IDNO2==""){
			$IDNO2=$IDNO1[$j];
		}else{
			$IDNO2=$IDNO2.".".$IDNO1[$j];
		}
		if($CYEAR1==""){
			$CYEAR1=$C_YEARS1[$j];
		}else{
			$CYEAR1=$CYEAR1.".".$C_YEARS1[$j];
		}
		
		if($CYEAR2==""){
			$CYEAR2=$C_YEARS2[$j];
		}else{
			$CYEAR2=$CYEAR2.".".$C_YEARS2[$j];
		}
		echo "<input type=\"hidden\" name=\"IDNO\" value=\"$IDNO2\"/>";
		echo "<input type=\"hidden\" name=\"CYEAR1\" value=\"$CYEAR1\"/>";
		echo "<input type=\"hidden\" name=\"CYEAR2\" value=\"$CYEAR2\"/>";
	}
	
    

	echo "$a<br>$b<br>$c";
	echo "<tr align=center height=50>";
	echo "<td><input type=\"hidden\" name=\"C_YEARS1\" value=\"$b\"><input type=\"hidden\" name=\"C_YEARS2\" value=\"$c\"><input type=\"submit\" value=\"พิมพ์รายงาน\"></td>";
	echo "</tr>";
}else{
	pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
</table>
</form>

