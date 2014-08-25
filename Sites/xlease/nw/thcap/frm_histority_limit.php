<?php ?>
<fieldset >
	<legend><font color="black"><b>
			ประวัติการยืนยัน 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_historityall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) </font>
		</b></font>
		
	</legend>
<br>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr style="font-weight:bold;" align="center" bgcolor="#CDC9C9">
		<td>รายการที่</td>
		<td>เลขที่สัญญา</td>
		<td width="120">ชื่อ-สกุลลูกค้า</td>
		<td>เลขที่เช็ค</td>
		<td>วันที่บนเช็ค</td>
		<td width="120">ธนาคารที่ออกเช็ค</td>
		<td>จ่ายบริษัท</td>
		<td>ยอดเช็ค</td>
		<td>ผู้นำเช็คเข้า</td>
		<td>ธนาคารที่นำเข้า</td>	
		<td>ผู้ทำรายการ</td>
		<td>วันเวลาที่ทำรายการ</td>
		<td>ผลการนำเช็คเข้า</td>	
	</tr>
	<?php	
	$i=0;
	$costold="";
	
	$qry_fr = pg_query("select * from  finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
left join \"Vfuser\"c on c.\"id_user\"= a.\"replyByTakerID\"
left join  \"BankProfile\" d on d.\"bankID\"= a.\"bankOutID\" 
where \"bankRevResult\" is not null order by a.\"replyByTakerStamp\" DESC limit 30 ");
	$numrows = pg_num_rows($qry_fr);
	$i=0;
	while($res_fr=pg_fetch_array($qry_fr)){
		$chqKeeperID = $res_fr["chqKeeperID"];
		$revChqID = $res_fr["revChqID"];
		$bankChqNo=$res_fr["bankChqNo"];
		$revChqDate = $res_fr["revChqDate"]; 
		$bankName = $res_fr["bankName"]; 
		$bankOutBranch = $res_fr["bankOutBranch"]; 
		$bankChqToCompID = $res_fr["bankChqToCompID"]; 
		$bankChqAmt = $res_fr["bankChqAmt"]; 
		$revChqStatus=$res_fr["revChqStatus"];
		$bankChqDate=$res_fr["bankChqDate"];
		$BID=$res_fr["BID"];
		$giveTakerID=$res_fr["giveTakerID"];
		$bankRevResult=$res_fr["bankRevResult"];
		$isInsurChq = $res_fr["isInsurChq"];
		$fullnamedoerid = $res_fr["fullname"];
		$replyByTakerStamp = $res_fr["replyByTakerStamp"];
		//
		if($bankRevResult=='1'){$bankRevResult="เข้าปกติ";}
		else if($bankRevResult=='2'){$bankRevResult="เข้าToo Late";}
		else if($bankRevResult=='3'){
			$bankRevResult="เช็คเด้ง";
		} else {
			$bankRevResult="ยกเลิกนำเช็คเข้าธนาคาร";
		}			
			
		//หาเลขที่สัญญา
		$qry_conid=pg_query("select \"revChqToCCID\" from finance.\"thcap_receive_cheque\" a WHERE \"revChqID\" ='$revChqID' ");
		list($contractid) = pg_fetch_array($qry_conid);							
		//หาชื่อลูกค้า
		$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
		list($cusid,$fullname) = pg_fetch_array($qry_cusname);
									
		//หาชื่อธนาคาร
		if($BID!=""){
			$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\" = '$BID'");
			list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
		}else{ 
			$ourbankname="";
			$BAccount="";
		}	
									
		//หาชื่อผู้นำเข้า
		$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
		list($userfullname) = pg_fetch_array($qry_username);
									
		$i+=1;
		if($i%2==0){
			if($isInsurChq==1){
				echo "<tr bgcolor=\"#e5cdf9\" align=center>";
			}else{
				echo "<tr class=\"odd\" align=center>";
			}
		}else{
			if($isInsurChq==1){
				echo "<tr bgcolor=\"#e5cdf9\" align=center>";
			}else{
				echo "<tr class=\"even\" align=center>";
			}
		}
		
		?>
		<td><?php echo $i; ?></td>							
		<td>
		<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
		<font color="red"><U><?php echo $contractid; ?></U></font></a>
		</td>
		<td align="left">
		<a style="cursor:pointer;" onclick="javascipt:popU('../search_cusco/index.php?cusid=<?php echo $cusid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')" title="ดูข้อมูลลูกค้า">					
		(<font color="red"><U><?php echo $cusid; ?></U></font>)</a>
		<?php echo $fullname; ?>
		</td>
		<td><?php echo $bankChqNo; ?></td>
		<td><?php echo $bankChqDate; ?></td>
		<td align="left"><?php echo $bankName; ?></td>
		<td><?php echo $bankChqToCompID; ?></td>
		<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
		<td><?php echo $userfullname; ?></td>
		<td><?php echo "$ourbankname-$BAccount"; ?></td>
		<td><?php echo $fullnamedoerid ?></td>
		<td><?php echo $replyByTakerStamp; ?></td>
		<td><?php echo $bankRevResult; ?></td>
		</tr>
		<?php
	} //end whil	
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=13 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=13><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
	
</table>
</fieldset>