<?php
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$current_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$iduser = $_SESSION["av_iduser"];

$emlevel_qry=pg_query("select emplevel from fuser where id_user='$iduser'");
$user_emlevel=pg_fetch_result($emlevel_qry,0);
?>
<table width="950" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#BBBBBB" align="center">
				<td>เลขที่สัญญา</td>
				<td>ชื่อ-นามสกุล ลูกค้า</td>
				<td>เลขที่เช็ค</td>
				<td>วันที่บนเช็ค</td>
				<td>ธนาคารที่ออกเช็ค</td>
				<td>สาขา</td>
				<td>จ่ายบริษัท</td>
				<td>ยอดเช็ค(บาท)</td>
				<?php if($user_emlevel<=1){echo "<td>นำไปยืนยันเก็บรักษาเช็คใหม่</td>";} ?>
			</tr>
			<?php
			$qry_fr=pg_query("select \"revChqID\", \"bankChqNo\", \"bankChqDate\", \"bankName\", \"bankOutBranch\", \"bankChqToCompID\", \"bankChqAmt\", \"revChqStatus\", \"isInsurChq\"
			from finance.\"V_thcap_receive_cheque_chqManage\" a
			left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
			WHERE \"revChqStatus\" ='8' and \"bankChqDate\" > '$current_date' order by a.\"bankChqDate\"");
			$nub=pg_num_rows($qry_fr);
			$i=0;
			while($res_fr=pg_fetch_array($qry_fr)){
				$revChqID = $res_fr["revChqID"];
				$bankChqNo=$res_fr["bankChqNo"];
				$bankChqDate = $res_fr["bankChqDate"]; 
				$bankName = $res_fr["bankName"]; 
				$bankOutBranch = $res_fr["bankOutBranch"]; 
				$bankChqToCompID = $res_fr["bankChqToCompID"]; 
				$bankChqAmt = $res_fr["bankChqAmt"]; 
				$revChqStatus=$res_fr["revChqStatus"];
				$isInsurChq = $res_fr["isInsurChq"];
				
				//หาเลขที่สัญญา
				$qry_conid=pg_query("select \"revChqToCCID\" from finance.\"thcap_receive_cheque\" a WHERE \"revChqID\" ='$revChqID' ");
				list($contractid) = pg_fetch_array($qry_conid);
				
				//หาชื่อลูกค้า
				$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
				list($cusid,$fullname) = pg_fetch_array($qry_cusname);
				
				//ตรวจสอบว่าเช็คว่าเคยทำรายการยืนยันแล้วหรือยัง?
				$chkkeep=pg_query("select \"revChqID\" from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID' and \"replyByTakerID\"is not null");
				$numkeep = pg_num_rows($chkkeep);
				
				$i+=1;
				if($i%2==0){
					if($isInsurChq==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr class=\"#DDDDDD\" align=center>";
					}
				}else{
					if($isInsurChq==1){
						echo "<tr bgcolor=\"#e5cdf9\" align=center>";
					}else{
						echo "<tr class=\"#EEEEEE\" align=center>";
					}
				}
				
				//ตรวจสอบว่าใช่เช็คที่จะนำไปเข้าวันถัดไปหรือไม่
				$qrydate=pg_query("select current_date+1");
				list($datecheckin)=pg_fetch_array($qrydate);
				
				//กรณีเป็นเช็คที่ต้องนำไปเข้าพรุ่งนี้ให้แสดง highlight 
				if($bankChqDate==$datecheckin){
					echo "<tr bgcolor=\"#FFA54F\" align=center>";
				}
			?>
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
				<td><?php echo $bankOutBranch; ?></td>
				<td><?php echo $bankChqToCompID; ?></td>
				<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
				<?php
				if($user_emlevel<=1)
				{
					if($numkeep==0)
					{
						echo "<td><img src=\"images/refresh.png\" height=\"20\" width=\"20\" style=\"cursor:pointer;\" onclick=\"if(confirm('นำเช็คกลับไปยืนยันการเก็บรักษาเช็คใหม่!')==true){location.href='process_chqtoconfirmkeep.php?revChqID=$revChqID';}\"></td>";
					}
					else
					{
						echo "<td></td>";
					}
				}
				?>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>
