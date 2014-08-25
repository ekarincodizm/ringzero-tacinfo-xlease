<?php if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
{ ?>

<fieldset>
	<legend><B>ตารางแสดงการจ่าย</B></legend>
	<div align="center">
		<div id="panel2" align="left" style="margin-top:10px">
		
<?php

	include("../../core/core_functions.php");
	
	$sql_head=pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
	while($resultH=pg_fetch_array($sql_head))
	{
		$conStartDate = $resultH["conStartDate"];
	}

	// เนื่องจากเพื่อประหยัด Query หมายเหตุใบเสร็จ กับ thcap_temp_int_201201 และ thcap_temp_receipt_details เป็นแบบ 1-1 จึงจับมาเชื่อมโยงกันเลย เพื่อนำหมายเหตุใบเสร็จที่เดิมจะต้องเรียกจาก view มาแทน
	$sql_table=pg_query("	
							select a.\"receiveDate\", a.\"interestRate\", a.\"receiveAmount\", a.\"receiveInterest\", a.\"receivePriciple\",
									a.\"LeftPrinciple\", a.\"LeftInterest\", a.\"receiptID\", b.\"receiptRemark\"
							from 
								public.\"thcap_temp_int_201201\" a
							left join 
								public.\"thcap_temp_receipt_details\" b ON a.\"receiptID\" = b.\"receiptID\"
							where 
								a.\"contractID\" = '$contractID' and 
								a.\"isReceiveReal\" = '1' 
							order by \"serial\" 
	"); 
	$numrows_table = pg_num_rows($sql_table);
?>

	<center>
<?php
	if($numrows_table > 0)
	{
?>
		<div align="right"><span onclick="javascript:popU('../thcap_installments/view_carddebtor.php?contractID=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;"><font color="#0000FF"><b><u>แสดงการ์ดลูกหนี้</u></b></font></span></div>
<?php
	}
	else
	{
?>
		<div align="right"><span onclick="javascript:popU('../thcap_installments/view_carddebtor_noPay.php?contractID=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=560')" style="cursor: pointer;"><font color="#0000FF"><b><u>แสดงการ์ดลูกหนี้</u></b></font></span></div>
<?php
	}
?>
	<table width="100%">
		<tr align="center" bgcolor="#79BCFF">
			<th>จ่ายครั้งที่</th>
			<th>วันที่จ่าย</th>
			<th>ดอกเบี้ย<br>ปัจจุบัน</th>
			<th>จำนวนเงิน<br>ที่จ่าย</th>
			<th>จำนวนวัน</th>
			<th>จำนวนเงิน<br>หักดอกเบี้ย</th>
			<th>จำนวนเงิน<br>หักเงินต้น</th>
			<th>ยอดเงินต้น<br>คงเหลือ ณ วันจ่าย</th>
			<th>ยอดดอกเบี้ย<br>คงเหลือ ณ วันจ่าย</th>
			<th>เลขที่ใบเสร็จ</th>
			<th>ช่องทางการจ่าย</th>
			<th>หมายเหตุ</th>
		</tr>
<?php
	$sumreceiveAmount = 0; // ยอดรวมรับชำระทั้งสิ้น
	$sumreceiveInterest = 0; // รับชำระดอกเบี้ยทั้งสิ้น
	$sumreceivePriciple = 0; // รับชำระเป็นเงินต้นทั้งสิ้น
	$i = 1;
	if($numrows_table > 0)
	{
		while($result=pg_fetch_array($sql_table))
		{
			$receiveDate[$i] = $result["receiveDate"];
			$interestRate = $result["interestRate"];
			$receiveAmount = $result["receiveAmount"];
			$receiveInterest = $result["receiveInterest"];
			$receivePriciple = $result["receivePriciple"];
			$LeftPrinciple = $result["LeftPrinciple"];
			$LeftInterest = $result["LeftInterest"];
			$receiptID = $result["receiptID"];
			$receiptRemark = $result["receiptRemark"]; //หาหมายเหตุของเลขที่ใบเสร็จ
			
			//หากมีช่องทางการจ่ายมากกว่า 1 ให้แสดง * สีแดงและ ขึ้นช่องทางทั้งหมดเมื่อ ชี้เมาท์
			$sqlchannel = pg_query("SELECT \"byChannel\" FROM thcap_temp_receipt_channel where \"receiptID\" = '$receiptID' order by \"ChannelAmt\" DESC ");
			$rechannel = pg_fetch_array($sqlchannel);
			$byChannel  = $rechannel["byChannel"];
			$rowchannel = pg_num_rows($sqlchannel);

			if($rowchannel > 1){ 
				
				$redstar = "<font color=\"red\">*</font>";
				while($rechannelmore = pg_fetch_array($sqlchannel)){
				$byChannelmore	= $rechannelmore['byChannel'];
				
					if($byChannelmore=="" || $byChannelmore=="0" || $byChannelmore=="999"){$byChannelmore1="ไม่ระบุ";}
					else{
						//นำไปค้นหาในตาราง BankInt
						$qrysearch1=pg_query("select \"ta_get_bankint_details\"($byChannelmore)");
						$ressearch1=pg_fetch_array($qrysearch1);
						list($byChannelmore1)=$ressearch1;
					}
					$byChannelmore2 = $byChannelmore1."\n".$byChannelmore2;	
				}
					

			}
			///
			
			$Last_LeftInterest = $LeftInterest; // ยอดดอกเบี้ยคงเหลือล่าสุด
		
			if($i == 1){$day = core_time_datediff($conStartDate, $receiveDate[$i]);}
			else{$day = core_time_datediff($receiveDate[$i-1], $receiveDate[$i]);}
		
			if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$byChannel="ไม่ระบุ";}
			else{
				//นำไปค้นหาในตาราง BankInt
				$qrysearch=pg_query("select \"ta_get_bankint_details\"($byChannel)");
				$ressearch=pg_fetch_array($qrysearch);
				list($byChannel)=$ressearch;
			}
			
			$sumreceiveAmount += $receiveAmount; // ยอดรวมรับชำระทั้งสิ้น
			$sumreceiveInterest += $receiveInterest; // รับชำระดอกเบี้ยทั้งสิ้น
			$sumreceivePriciple += $receivePriciple; // รับชำระเป็นเงินต้นทั้งสิ้น

			if($i%2==0){
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
			}
?>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $receiveDate[$i]; ?></td>
			<td align="right"><?php echo $interestRate."%"; ?></td>
			<td align="right"><?php echo number_format($receiveAmount,2); ?></td>
			<td align="center"><?php echo $day ?></td>
			<td align="right"><?php echo number_format($receiveInterest,2); ?></td>
			<td align="right"><?php echo number_format($receivePriciple,2); ?></td>
			<td align="right"><?php echo number_format($LeftPrinciple,2); ?></td>
			<td align="right"><?php echo number_format($LeftInterest,2); ?></td>
			<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('../thcap/Channel_detail.php?receiptID=<?php echo $receiptID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')" style="cursor: pointer;"><u><?php echo $receiptID; ?></u></span></td>
			<td align="center"  <?php if($rowchannel > 1){ echo "title=\"$byChannelmore2\" style=\"cursor:pointer\"";  } ?> >
			<?php echo $byChannel.$redstar; ?></td>
			<td align="center">
				<?php
				$img=redirect($_SERVER['PHP_SELF'],'nw/thcap/images/open.png');
				$realpath=redirect($_SERVER['PHP_SELF'],'nw/thcap/allpay_result.php');
				if($receiptRemark!="" and $receiptRemark!="-" and $receiptRemark!="--"){			
					echo"<img src=\"$img\" width=\"16\" height=\"16\" onclick=\"javascript : popU('$realpath?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"หมายเหตุ\">";
				}else{
					echo "<input type=\"button\" value=\"เพิ่มหมายเหตุ\" onclick=\"javascript : popU('$realpath?receiptID=$receiptID&method=add','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350');\" style=\"cursor:pointer;\" title=\"เพิ่มหมายเหตุ\" style=\"cursor:pointer\">";
				}
				?>
			</td>
		</tr>
<?php
		$i++;
		}
	}
	else
	{
?>
		<tr>
			<td colspan="12" align="center" bgcolor="#FFC6C6">ไม่พบข้อมูลการจ่าย!!</td>
		</tr>
<?php
	}
?>
	<tr bgcolor="#FFFF99"><td align="left" colspan="12"><b><img src="<?php echo $img;?>" width="16" height="16"> หมายถึง ใบเสร็จที่มีหมายเหตุ<font color=red>*</font></b></td></tr>
	</table>

	</center>

		
		</div>
	</div>
</fieldset>
<?php
}
?>