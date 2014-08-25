<?php
	include("../../config/config.php");
	$query = pg_query("select * from \"FOtherpay\" 
	where ((\"RefAnyID\" like 'F%') or (\"RefAnyID\" like 'U%') or (\"RefAnyID\" like 'CR%')) and \"Cancel\"='TRUE' order by \"IDNO\""); 
	$i=0;

	echo "
	<table align=\"center\" width=\"500\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"#D8D8D8\">
		<tr align=\"center\" bgcolor=\"#35B7F4\">
			<th height=\"30\" width=\"150\">IDNO</th>
			<th>O_RECEIPT</th>
			<th>O_MONEY</th>
			<th>รหัสอ้างอิง</th>
		</tr>";
	
	while($result=pg_fetch_array($query)){
		$IDNO = $result["IDNO"];
		$O_RECEIPT = $result["O_RECEIPT"];
		$O_MONEY = $result["O_MONEY"];
		$O_MONEY= number_format($O_MONEY,2);
		$RefAnyID = $result["RefAnyID"];
		$subRef=substr($RefAnyID,0,1);
		
		if($subRef == 'F'){
			$queryf=pg_query("select * from insure.\"InsureForce\" where \"InsFIDNO\"='$RefAnyID' and \"CusPayReady\"='TRUE'");
			while($resultf=pg_fetch_array($queryf)){
				$InsFIDNO=$resultf["InsFIDNO"];
				$queryf1=pg_query("select * from \"FOtherpay\" where \"RefAnyID\"='$InsFIDNO' and \"Cancel\"='FALSE'");
				$numf1=pg_num_rows($queryf1);
				if($numf1 == 0){ //กรณีเท่ากับ 0 แสดงว่าไม่มีการนำใบเสร็จกลับมาใช้ใหม่ ดังนั้นหากไม่มีการนำใบเสร็จกลับมาใช้แล้วค่า CusPayReady = TRUE แสดงว่าข้อมูลผิดพลาด
					echo "<tr align=center bgcolor=#FDEDFE height=25>";
					echo "<td>$IDNO</td>";
					echo "<td>$O_RECEIPT</td>";
					echo "<td align=right>$O_MONEY</td>";
					echo "<td>$RefAnyID</td>";
					echo "</tr>";
					$i++;
				}
			}
		}else if($subRef=='U'){
			$queryu=pg_query("select * from insure.\"InsureUnforce\" where \"InsUFIDNO\"='$RefAnyID' and \"CusPayReady\"='TRUE'");
			while($resultu=pg_fetch_array($queryu)){
				$InsUFIDNO=$resultu["InsUFIDNO"];
				$queryf2=pg_query("select * from \"FOtherpay\" where \"RefAnyID\"='$InsUFIDNO' and \"Cancel\"='FALSE'");
				$numf2=pg_num_rows($queryf2);
				if($numf2 == 0){ //กรณีเท่ากับ 0 แสดงว่าไม่มีการนำใบเสร็จกลับมาใช้ใหม่ ดังนั้นหากไม่มีการนำใบเสร็จกลับมาใช้แล้วค่า CusPayReady = TRUE แสดงว่าข้อมูลผิดพลาด
					echo "<tr align=center bgcolor=#E9F8FE height=25>";
					echo "<td>$IDNO</td>";
					echo "<td>$O_RECEIPT</td>";
					echo "<td align=right>$O_MONEY</td>";
					echo "<td>$RefAnyID</td>";
					echo "</tr>";
					$i++;
				}
			}
		}else if($subRef=='C'){
			$queryc=pg_query("select * from carregis.\"CarTaxDue\" where \"IDCarTax\"='$RefAnyID' and \"cuspaid\"='TRUE'");
			while($resultc=pg_fetch_array($queryc)){
				$IDCarTax=$resultc["IDCarTax"];
				$queryf3=pg_query("select * from \"FOtherpay\" where \"RefAnyID\"='$InsFIDNO' and \"Cancel\"='FALSE'");
				$numf3=pg_num_rows($queryf3);
				if($numf3 == 0){ //กรณีเท่ากับ 0 แสดงว่าไม่มีการนำใบเสร็จกลับมาใช้ใหม่ ดังนั้นหากไม่มีการนำใบเสร็จกลับมาใช้แล้วค่า cuspaid = TRUE แสดงว่าข้อมูลผิดพลาด
					echo "<tr align=center bgcolor=#FFF9EA height=25>";
					echo "<td>$IDNO</td>";
					echo "<td>$O_RECEIPT</td>";
					echo "<td align=right>$O_MONEY</td>";
					echo "<td>$RefAnyID</td>";
					echo "</tr>";
					$i++;
				}
			}
		}
	} //end while
?>
	<tr><td colspan="4" height="25" align="right"><b>รวม <?php echo $i?> รายการ</b></td></tr>
</table>