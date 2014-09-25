<?php
include("../../config/config.php");
set_time_limit(120);

$focusDate = pg_escape_string($_GET["focusDate"]); // วันที่สนใจ
$CusID_array = pg_escape_string($_GET["CusID_array"]); // รหัสลูกค้า
$annuities = pg_escape_string($_GET["annuities"]); // ค่างวด ถ้าเลือก จะเป็น checked
$PRP = pg_escape_string($_GET["PRP"]); // ค่าประกันภัย+พรบ. ถ้าเลือก จะเป็น checked
$miterTax = pg_escape_string($_GET["miterTax"]); // ค่าภาษี+ตรวจมิเตอร์ ถ้าเลือก จะเป็น checked
$charges = pg_escape_string($_GET["charges"]); // ค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
$note = pg_escape_string($_GET["note"]); // หมายเหตุ

$CusID_split = split(",", $CusID_array);

$noteShow = str_replace("codeEnter","\r\n",$note);
$noteShow = str_replace("codeSpace"," ",$noteShow);

$focusDateText = substr($focusDate,-2,2)."/".substr($focusDate,5,2)."/".substr($focusDate,0,4); // รูปแบบ วว/ดด/ปปปป (ค.ศ.)
?>

<script>
	function popU(U,N,T)
	{
		newWindow = window.open(U, N, T);
	}
	
	function printPDF()
	{
		var annuities = '<?php echo $annuities; ?>'; // ค่างวด ถ้าเลือก จะเป็น checked
		var PRP = '<?php echo $PRP; ?>'; // ค่าประกันภัย+พรบ. ถ้าเลือก จะเป็น checked
		var miterTax = '<?php echo $miterTax; ?>'; // ค่าภาษี+ตรวจมิเตอร์ ถ้าเลือก จะเป็น checked
		var charges = '<?php echo $charges; ?>'; // ค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
		var listPrint = '';
		
		if(annuities == 'checked') // ถ้าเลือก ค่างวด
		{
			var row_data = document.getElementById("row_annuities").value; // จำนวนข้อมูล ค่างวด ทั้งหมด
			
			for(var i = 1; i <= row_data; i++)
			{
				if(document.getElementById("P1R"+i).checked == true)
				{
					listPrint += '&P1R'+i+'=on';
				}
			}
		}
		
		if(PRP == 'checked') // ถ้าเลือก ค่าประกันภัย+พรบ.
		{
			var row_data = document.getElementById("row_PRP").value; // จำนวนข้อมูล ค่าประกันภัย+พรบ. ทั้งหมด
			
			for(var i = 1; i <= row_data; i++)
			{
				if(document.getElementById("P2R"+i).checked == true)
				{
					listPrint += '&P2R'+i+'=on';
				}
			}
		}
		
		if(miterTax == 'checked') // ถ้าเลือก ค่าภาษี+ตรวจมิเตอร์
		{
			var row_data = document.getElementById("row_miterTax").value; // จำนวนข้อมูล ค่าภาษี+ตรวจมิเตอร์ ทั้งหมด
			
			for(var i = 1; i <= row_data; i++)
			{
				if(document.getElementById("P3R"+i).checked == true)
				{
					listPrint += '&P3R'+i+'=on';
				}
			}
		}
		
		if(charges == 'checked') // ถ้าเลือก ค่าภาษี+ตรวจมิเตอร์
		{
			var row_data = document.getElementById("row_charges").value; // จำนวนข้อมูล ค่าใช้จ่ายอื่นๆ ทั้งหมด
			
			for(var i = 1; i <= row_data; i++)
			{
				if(document.getElementById("P4R"+i).checked == true)
				{
					listPrint += '&P4R'+i+'=on';
				}
			}
		}
		
		popU('frm_PDF.php?CusID_array=<?php echo "$CusID_array"; ?>&focusDate=<?php echo "$focusDate"; ?>&annuities='+annuities+'&PRP='+PRP+'&miterTax='+miterTax+'&charges='+charges+'&note=<?php echo $note; ?>'+listPrint,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700');
	}
	
	function calculate_sumone(typeTable, numCus) // คำนวณเงินของแต่ประเภทแต่ละคน
	{
		obj = myfrm.elements['chk_'+typeTable+'_'+numCus+'[]'];
		var arrayMoney = 0;
		
		for(i=0; i<obj.length; i++)
		{    
			if(obj[i].checked)
			{		
				//--- โยนค่าไปคำนวนใน PHP
					arrayMoney = arrayMoney+"#"+obj[i].value;
				//--- จบการโยนค่าไปคำนวนใน PHP
			}
		}
		
		$.post("calculate.php",{
			arrayMoney : arrayMoney
		},
		function(data_sum){
			$("#sum_"+typeTable+"_"+numCus).text(data_sum);
			$("#sumOne_"+typeTable+"_sumAll_"+numCus).text(data_sum);
		});
	}
	
	function calculate_allone_sumType() // รวมทั้งหมดของแต่ละประเภท ทุกคน
	{
		var annuities = '<?php echo $annuities; ?>'; // ค่างวด ถ้าเลือก จะเป็น checked
		var PRP = '<?php echo $PRP; ?>'; // ค่าประกันภัย+พรบ. ถ้าเลือก จะเป็น checked
		var miterTax = '<?php echo $miterTax; ?>'; // ค่าภาษี+ตรวจมิเตอร์ ถ้าเลือก จะเป็น checked
		var charges = '<?php echo $charges; ?>'; // ค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
		var sumClick = 0;
		var arrayMoneyAll = 0;
		
		if(annuities == 'checked') // ถ้าเลือก ค่างวด
		{
			sumClick = sumClick + 1;
			var row_data = document.getElementById("row_annuities").value; // จำนวนข้อมูล ค่างวด ทั้งหมด
			
			if(row_data > 0)
			{
				arrayMoney = 0;
				for(var i = 1; i <= row_data; i++)
				{
					if(document.getElementById("P1R"+i).checked == true)
					{
						//--- โยนค่าไปคำนวนใน PHP
							arrayMoney = arrayMoney+"#"+document.getElementById("P1R"+i).value;
							arrayMoneyAll = arrayMoneyAll+"#"+document.getElementById("P1R"+i).value;
						//--- จบการโยนค่าไปคำนวนใน PHP
					}
				}
				
				$.post("calculate.php",{
					arrayMoney : arrayMoney
				},
				function(data_sum){
					$("#everyone_annuities_sumAll").text(data_sum);
				});
			}
		}
		
		if(PRP == 'checked') // ถ้าเลือก ค่าประกันภัย+พรบ.
		{
			sumClick = sumClick + 1;
			var row_data = document.getElementById("row_PRP").value; // จำนวนข้อมูล ค่าประกันภัย+พรบ. ทั้งหมด
			
			if(row_data > 0)
			{
				arrayMoney = 0;
				for(var i = 1; i <= row_data; i++)
				{
					if(document.getElementById("P2R"+i).checked == true)
					{
						//--- โยนค่าไปคำนวนใน PHP
							arrayMoney = arrayMoney+"#"+document.getElementById("P2R"+i).value;
							arrayMoneyAll = arrayMoneyAll+"#"+document.getElementById("P2R"+i).value;
						//--- จบการโยนค่าไปคำนวนใน PHP
					}
				}
				
				$.post("calculate.php",{
					arrayMoney : arrayMoney
				},
				function(data_sum){
					$("#everyone_PRP_sumAll").text(data_sum);
				});
			}
		}
		
		if(miterTax == 'checked') // ถ้าเลือก ค่าภาษี+ตรวจมิเตอร์
		{
			sumClick = sumClick + 1;
			var row_data = document.getElementById("row_miterTax").value; // จำนวนข้อมูล ค่าภาษี+ตรวจมิเตอร์ ทั้งหมด
			
			if(row_data > 0)
			{
				arrayMoney = 0;
				for(var i = 1; i <= row_data; i++)
				{
					if(document.getElementById("P3R"+i).checked == true)
					{
						//--- โยนค่าไปคำนวนใน PHP
							arrayMoney = arrayMoney+"#"+document.getElementById("P3R"+i).value;
							arrayMoneyAll = arrayMoneyAll+"#"+document.getElementById("P3R"+i).value;
						//--- จบการโยนค่าไปคำนวนใน PHP
					}
				}
				
				$.post("calculate.php",{
					arrayMoney : arrayMoney
				},
				function(data_sum){
					$("#everyone_miterTax_sumAll").text(data_sum);
				});
			}
		}
		
		if(charges == 'checked') // ถ้าเลือก ค่าภาษี+ตรวจมิเตอร์
		{
			sumClick = sumClick + 1;
			var row_data = document.getElementById("row_charges").value; // จำนวนข้อมูล ค่าใช้จ่ายอื่นๆ ทั้งหมด
			
			if(row_data > 0)
			{
				arrayMoney = 0;
				for(var i = 1; i <= row_data; i++)
				{
					if(document.getElementById("P4R"+i).checked == true)
					{
						//--- โยนค่าไปคำนวนใน PHP
							arrayMoney = arrayMoney+"#"+document.getElementById("P4R"+i).value;
							arrayMoneyAll = arrayMoneyAll+"#"+document.getElementById("P4R"+i).value;
						//--- จบการโยนค่าไปคำนวนใน PHP
					}
				}
				
				$.post("calculate.php",{
					arrayMoney : arrayMoney
				},
				function(data_sum){
					$("#everyone_charges_sumAll").text(data_sum);
				});
			}
		}
		
		if(sumClick > 1) // ถ้าเลือกมากกว่า 1 รายการ
		{
			$.post("calculate.php",{
				arrayMoney : arrayMoneyAll
			},
			function(data_sum){
				$("#everything_sumAll").text(data_sum);
			});
		}
	}
	
	function calculate_sumone_allType(numCus) // รวมทั้งหมดทุกประเภท ของแต่ละคน
	{
		var annuities = '<?php echo $annuities; ?>'; // ค่างวด ถ้าเลือก จะเป็น checked
		var PRP = '<?php echo $PRP; ?>'; // ค่าประกันภัย+พรบ. ถ้าเลือก จะเป็น checked
		var miterTax = '<?php echo $miterTax; ?>'; // ค่าภาษี+ตรวจมิเตอร์ ถ้าเลือก จะเป็น checked
		var charges = '<?php echo $charges; ?>'; // ค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
		var sumClick = 0;
		var arrayMoney = 0;
		
		if(annuities == 'checked') // ถ้าเลือก ค่างวด
		{
			sumClick = sumClick + 1;
			obj = myfrm.elements['chk_annuities_'+numCus+'[]'];
			
			for(i=0; i<obj.length; i++)
			{    
				if(obj[i].checked)
				{		
					//--- โยนค่าไปคำนวนใน PHP
						arrayMoney = arrayMoney+"#"+obj[i].value;
					//--- จบการโยนค่าไปคำนวนใน PHP
				}
			}
		}
		
		if(PRP == 'checked') // ถ้าเลือก ค่าประกันภัย+พรบ.
		{
			sumClick = sumClick + 1;
			obj = myfrm.elements['chk_PRP_'+numCus+'[]'];
			
			for(i=0; i<obj.length; i++)
			{    
				if(obj[i].checked)
				{		
					//--- โยนค่าไปคำนวนใน PHP
						arrayMoney = arrayMoney+"#"+obj[i].value;
					//--- จบการโยนค่าไปคำนวนใน PHP
				}
			}
		}
		
		if(miterTax == 'checked') // ถ้าเลือก ค่าภาษี+ตรวจมิเตอร์
		{
			sumClick = sumClick + 1;
			obj = myfrm.elements['chk_miterTax_'+numCus+'[]'];
			
			for(i=0; i<obj.length; i++)
			{    
				if(obj[i].checked)
				{		
					//--- โยนค่าไปคำนวนใน PHP
						arrayMoney = arrayMoney+"#"+obj[i].value;
					//--- จบการโยนค่าไปคำนวนใน PHP
				}
			}
		}
		
		if(charges == 'checked') // ถ้าเลือก ค่าภาษี+ตรวจมิเตอร์
		{
			sumClick = sumClick + 1;
			obj = myfrm.elements['chk_charges_'+numCus+'[]'];
			
			for(i=0; i<obj.length; i++)
			{    
				if(obj[i].checked)
				{		
					//--- โยนค่าไปคำนวนใน PHP
						arrayMoney = arrayMoney+"#"+obj[i].value;
					//--- จบการโยนค่าไปคำนวนใน PHP
				}
			}
		}
		
		if(sumClick > 1) // ถ้าเลือกมากกว่า 1 รายการ
		{
			$.post("calculate.php",{
				arrayMoney : arrayMoney
			},
			function(data_sum){
				$("#sumOne_sumType_"+numCus).text(data_sum);
			});
		}
	}
</script>

<?php
// ถ้าเลือกให้แสดง
if($annuities == "checked" || $PRP == "checked" || $miterTax == "checked" || $charges == "checked")
{
?>
	<form name="myfrm">
	<br/>
	<table width="100%">
		<tr>
			<td align="right">
				<input type="button" value="พิมพ์ PDF" onclick="javascript:printPDF();">
			</td>
		</tr>
	</table>
<?php
	// ถ้าเลือกให้แสดง ติดตามค่างวด
	if($annuities == "checked")
	{
?>
		<fieldset><legend><B>ผลการค้นหา ค่างวด ถึงวันที่ <?php echo $focusDateText; ?></B></legend>
			<center>
				<?php
				$t1 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่างวด
				for($c=1; $c<=count($CusID_split); $c++)
				{
					$CusID = $CusID_split[$c-1];
					
					// หาชื่อลูกค้า
					$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
					$cusName = pg_fetch_result($qry_cusName,0);
					
					if($c > 1){echo "<br/><br/><br/>";} // เว้นระยะห่างของตารางแต่ละคน
				?>
					<table width="100%" bgcolor="#666666">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="7"><b><?php echo "ค่างวด ของ $cusName"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ลำดับ</th>
							<th>ทะเบียน</th>
							<th>เลขที่สัญญา</th>
							<th>ครบชำระ</th>
							<th>งวดที่</th>
							<th>งวดละ</th>
							<th>พิมพ์</th>
						</tr>
						<?php
						$qry_contract = pg_query("select \"IDNO\", \"DueNo\", \"DueDate\"
										from  \"VCusPayment\"
										WHERE \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
										AND \"R_Receipt\" is null AND \"DueDate\" <= '$focusDate'
										AND \"IDNO\" IN(select distinct \"IDNO\" from \"Fp\" where \"P_ACCLOSE\" = FALSE)
										ORDER BY \"IDNO\", \"DueDate\" ");
						$i = 0; // จำนวนรถ
						$l = 0; // จำนวนแถวข้อมูล
						$sum_P_MONTH = 0;
						$IDNO_old = "";
						$C_REGIS_old = "";
						while($res_contract = pg_fetch_array($qry_contract))
						{
							$i++;
							$l++;
							$t1++;
							
							$IDNO = $res_contract["IDNO"];
							$DueNo = $res_contract["DueNo"];
							$DueDate = $res_contract["DueDate"];
							
							$DueDateText = substr($DueDate,-2,2)."/".substr($DueDate,5,2);
							
							// หาข้อมูลอื่นๆ
							$qry_other = pg_query("SELECT \"C_REGIS\", \"P_MONTH\" + \"P_VAT\", \"C_CARNAME\", \"P_TOTAL\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
							$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
							$P_MONTH = pg_fetch_result($qry_other,1); // งวดล่ะ
							$C_CARNAME = pg_fetch_result($qry_other,2); // ประเภทรภ
							$P_TOTAL = pg_fetch_result($qry_other,3); // จำนนวนงวด
							
							if($C_REGIS == "" || $C_REGIS == "-")
							{
								$C_REGIS = $C_CARNAME;
							}
							
							if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
							{
								$i--;
								$i_text = "";
								$IDNO_text = "";
								$C_REGIS_text = "";
							}
							else
							{
								$i_text = $i;
								$IDNO_text = $IDNO;
								$C_REGIS_text = $C_REGIS;
							}
							
							if($l%2==0){
								echo "<tr class=\"odd\" style=\"font-size:16px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:16px;\">";
							}
							
							echo "<td align=\"center\">$i_text</td>";
							echo "<td align=\"center\">$C_REGIS_text</td>";
							echo "<td align=\"center\">$IDNO_text</td>";
							echo "<td align=\"center\">$DueDateText</td>";
							echo "<td align=\"center\">$DueNo/$P_TOTAL</td>";
							echo "<td align=\"right\">".number_format($P_MONTH,2)."</td>";
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"chk_annuities_$c"."[]\" id=\"P1R$t1\" value=\"$P_MONTH\" checked onClick=\"calculate_sumone('annuities', '$c'); calculate_allone_sumType(); calculate_sumone_allType('$c');\" /></td>";
							echo "</tr>";
							
							$sum_P_MONTH += $P_MONTH;
							
							$IDNO_old = $IDNO;
							$C_REGIS_old = $C_REGIS;
						}
						
						if($i > 0)
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"5\" align=\"right\"><b>รวม</b></td>";
							echo "<td align=\"right\"><b><span id=\"sum_annuities_$c\">".number_format($sum_P_MONTH,2)."</span></b></td>";
							echo "<td></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"7\" align=\"center\"><b>-- ไม่พบยอดค่าชำระ --</b> <input type=\"hidden\" name=\"chk_annuities_$c"."[]\" value=\"0\" /></td>";
							echo "</tr>";
						}
						?>
					</table>
				<?php
					$dataArray[1][$c][1] = $CusID;
					$dataArray[1][$c][2] = $cusName;
					$dataArray[1][$c][3] = $sum_P_MONTH;
				}
				?>
				<input type="hidden" name="row_annuities" id="row_annuities" value="<?php echo $t1; ?>" />
			</center>
		</fieldset>
		<br/><br/><br/><br/>
	<?php
	}
	
	// ถ้าเลือกให้แสดง ค่าประกันภัย+พรบ.
	if($PRP == "checked")
	{
	?>
		<fieldset><legend><B>ผลการค้นหา ค่าประกันภัย+พรบ. ถึงวันที่ <?php echo $focusDateText; ?></B></legend>
			<center>
				<?php
				$t2 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่าประกันภัย+พรบ.
				for($c=1; $c<=count($CusID_split); $c++)
				{
					$CusID = $CusID_split[$c-1];
					
					// หาชื่อลูกค้า
					$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
					$cusName = pg_fetch_result($qry_cusName,0);
					
					if($c > 1){echo "<br/><br/><br/>";} // เว้นระยะห่างของตารางแต่ละคน
				?>
					<table width="100%" bgcolor="#666666">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="8"><b><?php echo "ค่าประกันภัย+พรบ. ของ $cusName"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ลำดับ</th>
							<th>วันที่คุ้มครอง</th>
							<th>ภาษี+พรบ.</th>
							<th>ทะเบียน</th>
							<th>เลขที่สัญญา</th>
							<th>ยอดเงิน</th>
							<th>หมายเหตุ</th>
							<th>พิมพ์</th>
						</tr>
						<?php
						$qry_contract = pg_query("SELECT \"IDNO\",
													'ค่าประกันภัย+พรบ.' AS \"TName\",
													\"StartDate\",
													sum(\"outstanding\") AS \"outstanding\",
													sum(\"whereFrom\") AS \"whereFrom\",
													CASE WHEN sum(\"whereFrom\") = 1 THEN 'ไม่รวมประกันภัย'
													ELSE
														CASE WHEN sum(\"whereFrom\") = 2 THEN 'ไม่รวมพรบ.'
															ELSE NULL
														END
													END AS \"note\"
												FROM
												(
													 -- ประกันภัยภาคบังคับ(พรบ.)
													SELECT \"IDNO\", 'พรบ.' AS \"TName\", \"StartDate\", ceil(\"outstanding\") AS \"outstanding\", 1::integer AS \"whereFrom\"
													FROM insure.\"VInsForceDetail\"
													WHERE \"outstanding\" >= '0.01'
													AND \"StartDate\" <= '$focusDate'
													AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
													
													UNION

													-- ประกันภัยภาคสมัครใจ
													SELECT \"IDNO\", 'ประกันภัยรถ' AS \"TName\", \"StartDate\", ceil(\"outstanding\") AS \"outstanding\", 2::integer AS \"whereFrom\"
													FROM insure.\"VInsUnforceDetail\"
													WHERE \"outstanding\" >= '0.01'
													AND \"StartDate\" <= '$focusDate'
													AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

													ORDER BY 1, 3
												) AS \"tableTemp\"
												GROUP BY 1, 3
												ORDER BY 1, 3 ");
						$i = 0; // จำนวนรถ
						$l = 0; // จำนวนแถวข้อมูล
						$sum_CusAmt = 0;
						$IDNO_old = "";
						$C_REGIS_old = "";
						while($res_contract = pg_fetch_array($qry_contract))
						{
							$i++;
							$l++;
							$t2++;
							
							$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
							$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
							$StartDate = $res_contract["StartDate"]; // วันที่เริ่มประกันภัย หรือ วันที่เริ่มพรบ.
							$CusAmt = $res_contract["outstanding"]; // จำนวนเงิน
							$whereFrom = $res_contract["whereFrom"]; // 1 เฉพาะรายการ พรบ. / 2 เฉพาะรายการ ประกันภัยรถ / 3 รวมกันทั้ง 2 รายการ
							$note = $res_contract["note"]; // หมายเหตุ
							
							// หาข้อมูลอื่นๆ
							$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
							$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
							$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
							
							if($C_REGIS == "" || $C_REGIS == "-")
							{
								$C_REGIS = $C_CARNAME;
							}
							
							// วันที่ รูปแบบ วว/ดด/ปปป
							$StartDateText = substr($StartDate,-2,2)."/".substr($StartDate,5,2)."/".substr($StartDate,0,4);
							
							// วันที่คุ้มครอง
							if($whereFrom != 1) // ถ้าไม่ได้เป็น พรบ. เพียงอย่างเดียว
							{
								$StartDate_K = $StartDateText;
							}
							else
							{
								$StartDate_K = "";
							}
							
							// ภาษี+พรบ.
							if($whereFrom != 2) // ถ้าไม่ได้เป็น ประกันภัย เพียงอย่างเดียว
							{
								$StartDate_P = $StartDateText;
							}
							else
							{
								$StartDate_P = "";
							}
							
							if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
							{
								$i--;
								$i_text = "";
								$IDNO_text = "";
								$C_REGIS_text = "";
							}
							else
							{
								$i_text = $i;
								$IDNO_text = $IDNO;
								$C_REGIS_text = $C_REGIS;
							}
							
							if($l%2==0){
								echo "<tr class=\"odd\" style=\"font-size:16px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:16px;\">";
							}
							
							echo "<td align=\"center\">$i_text</td>";
							echo "<td align=\"center\">$StartDate_K</td>";
							echo "<td align=\"center\">$StartDate_P</td>";
							echo "<td align=\"center\">$C_REGIS_text</td>";
							echo "<td align=\"center\">$IDNO_text</td>";
							echo "<td align=\"right\">".number_format($CusAmt,2)."</td>";
							echo "<td align=\"center\">$note</td>";
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"chk_PRP_$c"."[]\" id=\"P2R$t2\" value=\"$CusAmt\" checked onClick=\"calculate_sumone('PRP', '$c'); calculate_allone_sumType(); calculate_sumone_allType('$c');\" /></td>";
							echo "</tr>";
							
							$sum_CusAmt += $CusAmt;
							
							$IDNO_old = $IDNO;
							$C_REGIS_old = $C_REGIS;
						}
						
						if($i > 0)
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"5\" align=\"right\"><b>รวม</b></td>";
							echo "<td align=\"right\"><b><span id=\"sum_PRP_$c\">".number_format($sum_CusAmt,2)."</span></b></td>";
							echo "<td colspan=\"2\"></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"8\" align=\"center\"><b>-- ไม่พบยอดค่าชำระ --</b> <input type=\"hidden\" name=\"chk_PRP_$c"."[]\" value=\"0\" /></td>";
							echo "</tr>";
						}
						?>
					</table>
				<?php
					$dataArray[2][$c][1] = $CusID;
					$dataArray[2][$c][2] = $cusName;
					$dataArray[2][$c][3] = $sum_CusAmt;
				}
				?>
				<input type="hidden" name="row_PRP" id="row_PRP" value="<?php echo $t2; ?>" />
			</center>
		</fieldset>
		<br/><br/><br/><br/>
<?php
	}
	
	// ถ้าเลือกให้แสดง ค่าภาษี+ตรวจมิเตอร์
	if($miterTax == "checked")
	{
	?>
		<fieldset><legend><B>ผลการค้นหา ค่าภาษี+ตรวจมิเตอร์ ถึงวันที่ <?php echo $focusDateText; ?></B></legend>
			<center>
				<?php
				$t3 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่าภาษี+ตรวจมิเตอร์
				for($c=1; $c<=count($CusID_split); $c++)
				{
					$CusID = $CusID_split[$c-1];
					
					// หาชื่อลูกค้า
					$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
					$cusName = pg_fetch_result($qry_cusName,0);
					
					if($c > 1){echo "<br/><br/><br/>";} // เว้นระยะห่างของตารางแต่ละคน
				?>
					<table width="100%" bgcolor="#666666">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="7"><b><?php echo "ค่าภาษี+ตรวจมิเตอร์ ของ $cusName"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ลำดับ</th>
							<th>ทะเบียน</th>
							<th>เลขที่สัญญา</th>
							<th>ค่าใช้จ่าย</th>
							<th>วันที่ครบรอบ</th>
							<th>ยอดเงิน</th>
							<th>พิมพ์</th>
						</tr>
						<?php
						$qry_contract = pg_query("SELECT a.\"IDNO\", a.\"TypeDep\", b.\"TName\", a.\"TaxDueDate\", a.\"CusAmt\"
												FROM carregis.\"CarTaxDue\" a, \"TypePay\" b
												WHERE a.\"TypeDep\" = b.\"TypeID\"
												AND a.\"cuspaid\" = false AND a.\"CusAmt\" > 0
												AND a.\"TaxDueDate\" <= '$focusDate'
												AND a.\"TypeDep\" IN('101', '105')
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

												ORDER BY 1, 4 ");
						$i = 0; // จำนวนรถ
						$l = 0; // จำนวนแถวข้อมูล
						$sum_CusAmt = 0;
						$IDNO_old = "";
						$C_REGIS_old = "";
						while($res_contract = pg_fetch_array($qry_contract))
						{
							$i++;
							$l++;
							$t3++;
							
							$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
							$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
							$TaxDueDate = $res_contract["TaxDueDate"]; // วันครอบกำหนดตรวจมิเตอร์หรือภาษีรถ
							$CusAmt = $res_contract["CusAmt"]; // จำนวนเงิน
							
							// หาข้อมูลอื่นๆ
							$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
							$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
							$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
							
							if($C_REGIS == "" || $C_REGIS == "-")
							{
								$C_REGIS = $C_CARNAME;
							}
							
							// ถ้าเป็น "ตรวจมิเตอร์" ไม่ต้องแสดงวันที่ครบกำหนด
							if($TName == "ตรวจมิเตอร์")
							{
								$TaxDueDateText = "";
							}
							else
							{
								$TaxDueDateText = substr($TaxDueDate,-2,2)."/".substr($TaxDueDate,5,2)."/".substr($TaxDueDate,0,4);
							}
							
							if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
							{
								$i--;
								$i_text = "";
								$IDNO_text = "";
								$C_REGIS_text = "";
							}
							else
							{
								$i_text = $i;
								$IDNO_text = $IDNO;
								$C_REGIS_text = $C_REGIS;
							}
							
							if($l%2==0){
								echo "<tr class=\"odd\" style=\"font-size:16px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:16px;\">";
							}
							
							echo "<td align=\"center\">$i_text</td>";
							echo "<td align=\"center\">$C_REGIS_text</td>";
							echo "<td align=\"center\">$IDNO_text</td>";
							echo "<td align=\"center\">$TName</td>";
							echo "<td align=\"center\">$TaxDueDateText</td>";
							echo "<td align=\"right\">".number_format($CusAmt,2)."</td>";
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"chk_miterTax_$c"."[]\" id=\"P3R$t3\" value=\"$CusAmt\" checked onClick=\"calculate_sumone('miterTax', '$c'); calculate_allone_sumType(); calculate_sumone_allType('$c');\" /></td>";
							echo "</tr>";
							
							$sum_CusAmt += $CusAmt;
							
							$IDNO_old = $IDNO;
							$C_REGIS_old = $C_REGIS;
						}
						
						if($i > 0)
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"5\" align=\"right\"><b>รวม</b></td>";
							echo "<td align=\"right\"><b><span id=\"sum_miterTax_$c\">".number_format($sum_CusAmt,2)."</span></b></td>";
							echo "<td></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"7\" align=\"center\"><b>-- ไม่พบยอดค่าชำระ --</b> <input type=\"hidden\" name=\"chk_miterTax_$c"."[]\" value=\"0\" /></td>";
							echo "</tr>";
						}
						?>
					</table>
				<?php
					$dataArray[3][$c][1] = $CusID;
					$dataArray[3][$c][2] = $cusName;
					$dataArray[3][$c][3] = $sum_CusAmt;
				}
				?>
				<input type="hidden" name="row_miterTax" id="row_miterTax" value="<?php echo $t3; ?>" />
			</center>
		</fieldset>
		<br/><br/><br/><br/>
<?php
	}

	// ถ้าเลือกให้แสดง ติดตามค่าใช้จ่ายอื่นๆ
	if($charges == "checked")
	{
	?>
		<fieldset><legend><B>ผลการค้นหา ค่าใช้จ่ายอื่นๆ ถึงวันที่ <?php echo $focusDateText; ?></B></legend>
			<center>
				<?php
				$t4 = 0; // จำนวนรายการทั้งหมด รวมทุกคน ในตาราง ค่าใช้จ่ายอื่นๆ
				for($c=1; $c<=count($CusID_split); $c++)
				{
					$CusID = $CusID_split[$c-1];
					
					// หาชื่อลูกค้า
					$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
					$cusName = pg_fetch_result($qry_cusName,0);
					
					if($c > 1){echo "<br/><br/><br/>";} // เว้นระยะห่างของตารางแต่ละคน
				?>
					<table width="100%" bgcolor="#666666">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="7"><b><?php echo "ค่าใช้จ่ายอื่นๆ ของ $cusName"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ลำดับ</th>
							<th>ทะเบียน</th>
							<th>เลขที่สัญญา</th>
							<th>ค่าใช้จ่าย</th>
							<th>วันที่ครบรอบ</th>
							<th>ยอดเงิน</th>
							<th>พิมพ์</th>
						</tr>
						<?php
						$qry_contract = pg_query("SELECT a.\"IDNO\", a.\"TypeDep\", b.\"TName\", a.\"TaxDueDate\", a.\"CusAmt\"
												FROM carregis.\"CarTaxDue\" a, \"TypePay\" b
												WHERE a.\"TypeDep\" = b.\"TypeID\"
												AND a.\"cuspaid\" = false AND a.\"CusAmt\" > 0
												AND a.\"TaxDueDate\" <= '$focusDate'
												AND a.\"TypeDep\" NOT IN('101', '105')
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
												
												UNION

												SELECT \"IDNO\", NULL, 'ประกันภัยคุ้มครองหนี้', \"StartDate\", \"outstanding\"
												FROM insure.\"VInsLiveDetail\"
												WHERE \"outstanding\" >= '0.01'
												AND \"StartDate\" <= '$focusDate'
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

												ORDER BY 1, 4 ");
						$i = 0; // จำนวนรถ
						$l = 0; // จำนวนแถวข้อมูล
						$sum_CusAmt = 0;
						$IDNO_old = "";
						$C_REGIS_old = "";
						while($res_contract = pg_fetch_array($qry_contract))
						{
							$i++;
							$l++;
							$t4++;
							
							$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
							$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
							$TaxDueDate = $res_contract["TaxDueDate"]; // วันครอบกำหนดตรวจมิเตอร์หรือภาษีรถ
							$CusAmt = $res_contract["CusAmt"]; // จำนวนเงิน
							
							// หาข้อมูลอื่นๆ
							$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
							$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
							$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
							
							if($C_REGIS == "" || $C_REGIS == "-")
							{
								$C_REGIS = $C_CARNAME;
							}
							
							// ถ้าเป็น "ตรวจมิเตอร์" ไม่ต้องแสดงวันที่ครบกำหนด
							if($TName == "ตรวจมิเตอร์")
							{
								$TaxDueDateText = "";
							}
							else
							{
								$TaxDueDateText = substr($TaxDueDate,-2,2)."/".substr($TaxDueDate,5,2)."/".substr($TaxDueDate,0,4);
							}
							
							if($IDNO == $IDNO_old && $C_REGIS == $C_REGIS_old)
							{
								$i--;
								$i_text = "";
								$IDNO_text = "";
								$C_REGIS_text = "";
							}
							else
							{
								$i_text = $i;
								$IDNO_text = $IDNO;
								$C_REGIS_text = $C_REGIS;
							}
							
							if($l%2==0){
								echo "<tr class=\"odd\" style=\"font-size:16px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:16px;\">";
							}
							
							echo "<td align=\"center\">$i_text</td>";
							echo "<td align=\"center\">$C_REGIS_text</td>";
							echo "<td align=\"center\">$IDNO_text</td>";
							echo "<td align=\"center\">$TName</td>";
							echo "<td align=\"center\">$TaxDueDateText</td>";
							echo "<td align=\"right\">".number_format($CusAmt,2)."</td>";
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"chk_charges_$c"."[]\" id=\"P4R$t4\" value=\"$CusAmt\" checked onClick=\"calculate_sumone('charges', '$c'); calculate_allone_sumType(); calculate_sumone_allType('$c');\" /></td>";
							echo "</tr>";
							
							$sum_CusAmt += $CusAmt;
							
							$IDNO_old = $IDNO;
							$C_REGIS_old = $C_REGIS;
						}
						
						if($i > 0)
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"5\" align=\"right\"><b>รวม</b></td>";
							echo "<td align=\"right\"><b><span id=\"sum_charges_$c\">".number_format($sum_CusAmt,2)."</span></b></td>";
							echo "<td></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"7\" align=\"center\"><b>-- ไม่พบยอดค่าชำระ --</b> <input type=\"hidden\" name=\"chk_charges_$c"."[]\" value=\"0\" /></td>";
							echo "</tr>";
						}
						?>
					</table>
				<?php
					$dataArray[4][$c][1] = $CusID;
					$dataArray[4][$c][2] = $cusName;
					$dataArray[4][$c][3] = $sum_CusAmt;
				}
				?>
				<input type="hidden" name="row_charges" id="row_charges" value="<?php echo $t4; ?>" />
			</center>
		</fieldset>
		<br/><br/><br/><br/>
<?php
	}
	
	// แสดงตารางผลรวมทั้งหมด
	if($annuities == "checked" || $PRP == "checked" || $miterTax == "checked" || $charges == "checked")
	{
		$columnData = 0; // จำนวน column ของข้อมูลที่เลือก
		if($annuities == "checked"){$columnData++;}
		if($PRP == "checked"){$columnData++;}
		if($miterTax == "checked"){$columnData++;}
		if($charges == "checked"){$columnData++;}
		
		// จำนวน column ที่แสดง
		if($columnData > 1){$columnSum = $columnData + 2;}
		else{$columnSum = $columnData + 1;}
?>
		<fieldset><legend><B>รวมทั้งหมด ถึงวันที่ <?php echo $focusDateText; ?></B></legend>
			<center>
				<table width="100%" bgcolor="#666666">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="<?php echo $columnSum; ?>"><b><?php echo "ยอดรวม"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ชื่อ</th>
							<?php if($annuities == "checked"){echo "<th>ค่างวด</th>";} ?>
							<?php if($PRP == "checked"){echo "<th>ค่าประกันภัย+พรบ.</th>";} ?>
							<?php if($miterTax == "checked"){echo "<th>ค่าภาษี+ตรวจมิเตอร์</th>";} ?>
							<?php if($charges == "checked"){echo "<th>ค่าใช้จ่ายอื่นๆ</th>";} ?>
							<?php if($columnData > 1){echo "<th>รวม</th>";} ?> <!-- ถ้าเลือกมากกว่า 1 หัวข้อ ให้แสดงช่องรวมด้านหลังด้วย -->
						</tr>
						<?php
						$sumI = 0; // รวมค่างวดทั้งหมด
						$sumP = 0; // รวมค่าประกันภัย+พรบ. ทั้งหมด
						$sumM = 0; // รวมค่าภาษี+ตรวจมิเตอร์ ทั้งหมด
						$sumO = 0; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
						$SumAll = 0; // รวมทั้งหมด
						for($d=1; $d<=count($CusID_split); $d++)
						{
							// ชื่อ
							if($dataArray[1][$d][2] != ""){$cusNameSum = $dataArray[1][$d][2];}
							elseif($dataArray[2][$d][2] != ""){$cusNameSum = $dataArray[2][$d][2];}
							elseif($dataArray[3][$d][2] != ""){$cusNameSum = $dataArray[3][$d][2];}
							elseif($dataArray[4][$d][2] != ""){$cusNameSum = $dataArray[4][$d][2];}
							
							// รวมของแต่ละคน
							$cusSumAll = $dataArray[1][$d][3] + $dataArray[2][$d][3] + $dataArray[3][$d][3] + $dataArray[4][$d][3];
							
							if($d%2==0){
								echo "<tr class=\"odd\" style=\"font-size:16px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:16px;\">";
							}

							echo "<td align=\"left\">".$cusNameSum."</td>";
							if($annuities == "checked"){echo "<td align=\"right\"><span id=\"sumOne_annuities_sumAll_$d\">".number_format($dataArray[1][$d][3],2)."</span></td>";}
							if($PRP == "checked"){echo "<td align=\"right\"><span id=\"sumOne_PRP_sumAll_$d\">".number_format($dataArray[2][$d][3],2)."</span></td>";}
							if($miterTax == "checked"){echo "<td align=\"right\"><span id=\"sumOne_miterTax_sumAll_$d\">".number_format($dataArray[3][$d][3],2)."</span></td>";}
							if($charges == "checked"){echo "<td align=\"right\"><span id=\"sumOne_charges_sumAll_$d\">".number_format($dataArray[4][$d][3],2)."</span></td>";}
							if($columnData > 1){echo "<td align=\"right\"><span id=\"sumOne_sumType_$d\">".number_format($cusSumAll,2)."</span></td>";}
							echo "</tr>";
							
							$sumI += $dataArray[1][$d][3]; // รวมค่างวดทั้งหมด
							$sumP += $dataArray[2][$d][3]; // รวม ค่าประกันภัย+พรบ. ทั้งหมด
							$sumM += $dataArray[3][$d][3]; // รวม ค่าภาษี+ตรวจมิเตอร์ ทั้งหมด
							$sumO += $dataArray[4][$d][3]; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
							$SumAll += $cusSumAll; // รวมทั้งหมด
						}
						
						echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
						echo "<td align=\"right\"><b>รวมทั้งหมด</b></td>";
						if($annuities == "checked"){echo "<td align=\"right\"><b><span id=\"everyone_annuities_sumAll\">".number_format($sumI,2)."</span></b></td>";}
						if($PRP == "checked"){echo "<td align=\"right\"><b><span id=\"everyone_PRP_sumAll\">".number_format($sumP,2)."</span></b></td>";}
						if($miterTax == "checked"){echo "<td align=\"right\"><b><span id=\"everyone_miterTax_sumAll\">".number_format($sumM,2)."</span></b></td>";}
						if($charges == "checked"){echo "<td align=\"right\"><b><span id=\"everyone_charges_sumAll\">".number_format($sumO,2)."</span></b></td>";}
						if($columnData > 1){echo "<td align=\"right\"><b><span id=\"everything_sumAll\">".number_format($SumAll,2)."</span></b></td>";}
						echo "</tr>";
						
						echo "<tr bgcolor=\"#FFFFFF\" style=\"font-size:16px;\">";
						echo "<td align=\"left\" colspan=\"$columnSum\"><b>หมายเหตุ : </b>$noteShow</td>";
						echo "</tr>";
						?>
					</table>
			</center>
		</fieldset>
<?php
	}
?>
	</form>
<?php
}
?>