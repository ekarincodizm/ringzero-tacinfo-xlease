
<!--<fieldset style="width:97%;height: 290px; background-Color:#FFFFFF;"><legend style="background-Color:#BBBBBB;"><font color="#000000">สัญญาทั้งหมด</font></legend>
-->			<div style="width:100%; height: 290px; overflow: auto;">
				<table width="98%" frame="box" cellspacing="1" cellpadding="0" align="center" bgcolor="">
				<tr height="25px" bgcolor="#7AC5CD">
					<th width="16.5%">เลขที่สัญญา</th>
					<th width="16.5%">ปีรถ</th>
					<th width="16.5%">สี</th>
					<th width="16.5%">จำนวนครั้งค้างชำระ</th>
					<th width="16.5%">จำนวนเงินค้างชำระ</th>
					<th width="16.5%">ยอดคงเหลือ</th>
				</tr>
<?php
						$sql1 = pg_query("SELECT \"IDNO\",\"P_MONTH\",\"P_VAT\",\"P_TOTAL\",\"P_ACCLOSE\" FROM \"Fp\" where \"CusID\" = '$Cusid'");
						$rows1 = pg_num_rows($sql1);
						while($result1 = pg_fetch_array($sql1)){
						
							$IDNO = $result1['IDNO'];
							$P_ACCLOSE = trim($result1["P_ACCLOSE"]);	
							$query2 = pg_query("select COUNT(\"DueNo\") as \"SumDueNo\" from \"VRemainPayment\" where \"IDNO\" = '$IDNO' GROUP BY \"IDNO\"");
								$result2 = pg_fetch_array($query2);
									
									$SumDueNo = $result2['SumDueNo'];																
										
										
										$query3 = pg_query("select \"C_COLOR\" , \"C_YEAR\" from \"VCarregistemp\" where \"IDNO\" = '$IDNO'");
											$result3 = pg_fetch_array($query3);
												$rows3 = pg_num_rows($query3);
									if($rows3 == 0 || empty($rows3)){
									
											$sql11 = pg_query("SELECT \"asset_id\" FROM \"Fp\" where \"IDNO\" = '$IDNO'");
											$result11 = pg_fetch_array($sql11);
											$asset_id = $result11['asset_id'];
											$query22 = pg_query("select \"car_year\" from \"FGas\" where \"GasID\" = '$asset_id'");
												$result22 = pg_fetch_array($query22);
												$caryear = $result22['car_year'];
								
									}else{
											
											$caryear = $result3['C_YEAR'];					
									}
									
								$carcolor =	$result3['C_COLOR'];
									
									
								if($caryear == ""){ $caryear = '---';}
								if($carcolor == ""){ $carcolor = '---';}
								if($SumDueNo == ""){ $SumDueNo = '---';}
									
								$s_payment_all = $result1["P_MONTH"]+$result1["P_VAT"];
								$s_fp_ptotal = $result1["P_TOTAL"];
								
								$money_all_in_vat = $s_payment_all*$s_fp_ptotal;
								
								$qry_VCusPayment=pg_query("select * from \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO') AND (\"R_Receipt\" IS NULL) ORDER BY \"DueDate\" LIMIT(1)");
								$res_VCusPayment=pg_fetch_array($qry_VCusPayment);
								$stdate=$res_VCusPayment["DueDate"];		
								
								$ssdate = nowDate();
								
								$qry_amt=@pg_query("select *  from  \"VCusPayment\" WHERE  (\"IDNO\"='$IDNO')  AND (\"DueDate\" BETWEEN '$stdate' AND '$ssdate') ");
								while($res_amt=@pg_fetch_array($qry_amt)){
									 $s_amt=pg_query("select \"CalAmtDelay\"('$ssdate','$res_amt[DueDate]',$s_payment_all)"); 
									 $res_s=pg_fetch_result($s_amt,0);
									 
									$sumres = $s_payment_all+$sumres+$res_s;
									$test = $test+$money_all_in_vat-($res_amt["DueNo"]*$s_payment_all);
								}
								
					if($SumDueNo == 1 && $P_ACCLOSE=='f'){ echo "<tr bgcolor=\"#FEF4B1\">"; }
					else if($SumDueNo == 2 && $P_ACCLOSE=='f'){ echo "<tr bgcolor=\"#FFDBB7\">"; }
					else if($SumDueNo == 3 && $P_ACCLOSE=='f'){ echo "<tr bgcolor=\"#FFA8A8\">"; }
					else if($SumDueNo == "---" && $P_ACCLOSE=='t'){ echo "<tr bgcolor=\"#D6D6D6\">"; }
					else{ echo "<tr>"; }
							?>
							
									
						<td align="center" onclick="javascript : popU('../../post/frm_viewcuspayment.php?idno=<?php echo $result1['IDNO'];?>');" style="cursor:pointer;"><u><?php echo $result1['IDNO']; ?></u></td>
						<td align="center"><?php echo $caryear; ?></td>
						<td align="center"><?php echo $carcolor ; ?></td>
						<td align="center"><?php echo $SumDueNo ; ?></td>
						<td align="center"><?php echo number_format($sumres,2); ?></td>
						<td align="center"><?php echo number_format($test,2); ?></td>
					</tr>
<?php					}  ?>
					<tr height="20px">
						<td colspan="6"  bgcolor="#7AC5CF" align="right">รวมทั้งหมด <?php echo $rows1 ?> สัญญา</td>
					</tr>	
				</table>
			</div>
</fieldset>