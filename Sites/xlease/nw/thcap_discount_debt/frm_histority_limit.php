<fieldset style="width:100%">
	<legend>
	<font color="black"><b>
			รายการการขอส่วนลด อยู่ระหว่างรออนุมัติ </font>
		</b></font>		
	</legend>
<br>
<?php $cur_path_ins = redirect($_SERVER['PHP_SELF'],'nw/thcap_installments');?>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>		
		<th>เลขที่สัญญา</th>
        <th>รายการ</th>
		<th>ค่าอ้างอิงของค่าใช้จ่าย</th>
		<th>วันที่ตั้งหนี้</th>
		<th>จำนวนหนี้ค้างจ่าย</th>	
		<th>จำนวนเงินที่ขอส่วนลด</th>
		<th>จำนวน VAT</th>
		<th>จำนวนเงิน</th>	
		<th>วันที่ส่วนลดมีผล</th>
		<th>ผู้ทำการขอส่วนลด</th>
		<th>วันเวลาที่ขอส่วนลด</th>
		<th>เหตุผล</th>
	
	</tr>
	<?php	
	$query = pg_query("select a.\"dcNoteID\",a.\"contractID\",c.\"typePayID\",c.\"typePayRefValue\",c.\"typePayRefDate\",c.\"typePayAmt\",c.\"doerID\"
	,c.\"doerStamp\" ,a.\"dcNoteDate\",b.\"doerID\",b.\"doerStamp\",a.\"dcNoteAmtNET\",a.\"dcNoteAmtVAT\",a.\"dcNoteAmtALL\"  from \"account\".\"thcap_dncn\" a
	left join \"account\".\"thcap_dncn_details\" b on a.\"dcNoteID\"=b.\"dcNoteID\"
	left join public.\"vthcap_otherpay_debt_current\" c on  a.\"debtID\"=c.\"debtID\"
	where  \"dcNoteStatus\"='8'  order by c.\"doerStamp\" DESC limit 30");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$dcNoteID=trim($result["dcNoteID"]);
		$typePayID=trim($result["typePayID"]); // รหัสประเภทค่าใช้จ่าย	
		
		$contractID=trim($result["contractID"]);
		$typePayRefValue=trim($result["typePayRefValue"]); 
		$typePayRefDate=trim($result["typePayRefDate"]); 
		$typePayAmt=trim($result["typePayAmt"]);		
		$dcNoteDate=trim($result["dcNoteDate"]); 
		$doerID=trim($result["doerID"]); 
		$doerStamp=trim($result["doerStamp"]); 
		$dcNoteAmtNET=trim($result["dcNoteAmtNET"]); 
		$dcNoteAmtVAT=trim($result["dcNoteAmtVAT"]); 
		$dcNoteAmtALL=trim($result["dcNoteAmtALL"]); 
		
		$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$doerID' ");
		$nameuser = pg_fetch_array($query_fullname);
		$fullname=$nameuser["fullname"];
		
		$qry_type=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
		while($res_type=pg_fetch_array($qry_type))
		{
			$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
		}
		$due = ""; // กำหนดวันดิวเป็นค่าว่าง เพื่อไม่ให้เก็บค่าเก่ามาใช้
							
		if($typePayID == "1003")
		{
			//-----------------ตัดส่วนเกินออก
			$search = strpos($typePayRefValue,"-");
			if($search)
			{
				$subtypePayRefValue = explode("-", $typePayRefValue);
				$typePayRefValue = $subtypePayRefValue[0];
			}
			//-----------------จบการตัดส่วนเกินออก
			$qry_due=pg_query("select \"ptDate\" from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
			while($res_due=pg_fetch_array($qry_due))
			{
				$ptDate=trim($res_due["ptDate"]); // วันดิว
				$due = "($ptDate)";
			}
		}
		else
		{
			$due = "";
		}
		if($i%2==0){
			echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
		}else{
			echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
		}
		echo "<td align=\"center\">$i</td>";		
		echo "<td align=\"center\"><a onclick=\"javascript:popU('$cur_path_ins/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";
		echo "<td align=\"center\">$tpDesc</td>";		
		echo "<td align=\"center\">$typePayRefValue $due</td>";
		echo "<td align=\"center\">$typePayRefDate</td>";
		echo "<td align=right>".number_format($typePayAmt,2)."</td>";	
		echo "<td align=right>".number_format($dcNoteAmtNET,2)."</td>";	
		echo "<td align=right>".number_format($dcNoteAmtVAT,2)."</td>";	
		echo "<td align=right>".number_format($dcNoteAmtALL,2)."</td>";			
		echo "<td align=\"center\">$dcNoteDate</td>";		
		echo "<td align=\"center\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";	
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_reason.php?dcNoteID=$dcNoteID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		echo "</tr>";
		}
	
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=14 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=14><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</fieldset>