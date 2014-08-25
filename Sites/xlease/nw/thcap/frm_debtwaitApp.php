<?php
	$rootpath = redirect($_SERVER['PHP_SELF'],''); // rootpath สำหรับเรียกไฟล์ PHP โดยเริ่มต้นที่ root
	if($contractID!=""){
		$con=" and \"contractID\"='$contractID'";
	}
?>
<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr bgcolor="#FFFFFF">
			<td colspan="11" align="center" style="font-weight:bold;">รายการขอตั้งหนี้ที่รออนุมัติ</td>
		</tr>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<td>เลขที่สัญญา</td>
			<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
			<td>รายละเอียด<br>ค่าใช้จ่าย</td>
			<td>ค่าอ้างอิงของ<br>ค่าใช้จ่าย</td>
			<td>วันที่ตั้งหนี้</td>
			<td>จำนวนหนี้</td>
			<td>ผู้ตั้งหนี้</td>
			<td>วันเวลาที่ตั้งหนี้</td>
			<td>หมายเหตุ</td>
			<td>สถานะ</td>
		</tr>
		
		<?php
		$qry_fr=pg_query("select * from \"thcap_v_otherpay_debt_realother\" a
			left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
			where \"debtStatus\" = '9' $con order by \"debtID\" ");
		$nub=pg_num_rows($qry_fr);
		while($res_fr=pg_fetch_array($qry_fr)){
			$debtID=$res_fr["debtID"];
			$contractID1=$res_fr["contractID"];
			$typePayID=$res_fr["typePayID"];
			$typePayRefValue=$res_fr["typePayRefValue"];
			$typePayRefDate=$res_fr["typePayRefDate"];
			$typePayAmt=$res_fr["typePayAmt"];
			$fullname=$res_fr["fullname"];
			$doerStamp=$res_fr["doerStamp"];
			
			// หารายละเอียดค่าใช้จ่ายนั้นๆ
			$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
			while($res_tpDesc = pg_fetch_array($qry_tpDesc))
			{
				$tpDescShow = $res_tpDesc["tpDesc"];
			}
			
			$i+=1;
			if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
			}else{
				echo "<tr class=\"even\" align=center>";
			}
		?>
			<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID1?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID1;?></u></font></span></td>
			<td><?php echo $typePayID; ?></td>
			<td><?php echo $tpDescShow; ?></td>
			<td><?php echo $typePayRefValue; ?></td>
			<td><?php echo $typePayRefDate; ?></td>
			<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
			<td align="left"><?php echo $fullname; ?></td>
			<td><?php echo $doerStamp; ?></td>
			<td align="center"><span onclick="javascript:popU('<?php echo $rootpath.'nw/thcap/show_remark.php';?>?debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
			<td align="center">รออนุมัติ</td>
		</tr>
		<?php
		} //end while
		
		if($nub == 0){
			echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
		}
		?>
		</table>
	</td>
</tr>
</table>
