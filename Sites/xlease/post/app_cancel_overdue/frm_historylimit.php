<?php ?>
<fieldset>
	<legend><font color="black"><b>
			ประวัติการอนุมัติ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historyappv.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>		
	</legend>
<br>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>เลขที่สัญญาเช่าซื้อ</th>
		<th>เลขที่</th>	
		<th>รายการ</th>		
		<th>ยอดเงินที่เก็บกับลูกค้า</th>
		<th>การชำระของลูกค้า</th>
		<th>ผู้ที่ทำรายการ</th>
		<th>วันที่ทำรายการ</th>
		<th>ผู้ทำการอนุมัติ</th>
		<th>วันที่การอนุมัติ</th>
		<th>หมายเหตุ</th>	
		<th>ผลการอนุมัติ</th>
	</tr>
	<?php	
	$i=0;	
	$qry_main = pg_query("select * from carregis.\"CarTaxDue_reserve\" where \"Approved\" <> 9 order by \"appvStamp\" desc	limit 30 ");
	
	
	while(($res_main = pg_fetch_array($qry_main)))
	{
			$auto_id = $res_main["auto_id"];
			$IDCarTax = $res_main["IDCarTax"];
			$IDNO = $res_main["IDNO"];
			$TypeDep = $res_main["TypeDep"];
			$CusAmt = $res_main["CusAmt"];
			$cuspaid = $res_main["cuspaid"];		
			$doerID = $res_main["doerID"];
			$doerStamp = $res_main["doerStamp"];
			$appvID = $res_main["appvID"];
			$appvStamp = $res_main["appvStamp"];
			$Approved = $res_main["Approved"];
			
			//รายการ $TypeDep			
			$qry_TName=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\" = '$TypeDep'");
			$TName=pg_fetch_array($qry_TName);
			$Pay_name= ($TName["TName"]);
			
			//การชำระเงิน 
			if($cuspaid	=='t'){
				$status_cuspaid	="ชำระแล้ว";
			}elseif($cuspaid=='f'){
				$status_cuspaid	="ยังไม่ชำระ";
			}
			//ผู้ทำรายการ
			$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID' ");
			$fullnameuser = pg_fetch_array($query_fullnameuser);
			$doerfullname=$fullnameuser["fullname"];
			
			//ผู้ทำอนุมัติ/ไม่อนุมัติรายการ
			$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvID' ");
			$fullnameuser = pg_fetch_array($query_fullnameuser);
			$appfullname=$fullnameuser["fullname"];
			
			//การอนุมัติ/ไม่อนุมัติ
			if($Approved==1){
				$Approved="อนุมัติ";
			}
			else if($Approved==0){
				$Approved="ไม่อนุมัติ";
			}
			
			
			
			$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../frm_viewcuspayment.php?idno_names=$IDNO','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$IDNO</u></font></a></td>";		
		echo "<td align=\"center\">$IDCarTax</td>";
		echo "<td align=\"left\">$Pay_name</td>";
		echo "<td align=\"right\">".number_format($CusAmt,2)."</td>";
		echo "<td align=\"center\">$status_cuspaid</td>";
		echo "<td align=\"center\">$doerfullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		
		echo "<td align=\"center\">$appfullname</td>";
		echo "<td align=\"center\">$appvStamp</td>";
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_note.php?autoid=$auto_id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600');\" style=\"cursor:pointer;\"></td>";
		echo "<td align=\"center\">$Approved</td>";
		echo "</tr>";
	  
	}
	if($i==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=12 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=12><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>
</fieldset>