<?php
require_once("../../config/config.php");
$iduser = $_SESSION["av_iduser"];
$tab_id = pg_escape_string($_GET['tabid']); //id contype ที่ต้องการให้แสดง
$s = pg_escape_string($_GET['s']); //รายการที่ต้องการให้แสดง
$current_date=nowDateTime(); 

$emlevel_qry=pg_query("select emplevel from fuser where id_user='$iduser'");
$user_emlevel=pg_fetch_result($emlevel_qry,0);
if($s==1){ //ใบแจ้งหนี้ที่ถึงกำหนดส่ง
	echo "
	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\" align=\"center\" bgcolor=\"#F0F0F0\">
	<tr style=\"font-weight:bold;\" valign=\"middle\" bgcolor=\"#79BCFF\" align=\"center\">
			<td>เลขที่สัญญา</td>
			<td>ชื่อ-นามสกุล ลูกค้า</td>
			<td>เลขที่เช็ค</td>
			<td>วันที่บนเช็ค</td>
			<td>ธนาคารที่ออกเช็ค</td>
			<td>สาขา</td>
			<td>จ่ายบริษัท</td>
			<td>ยอดเช็ค(บาท)</td>
			<td>เลือกรายการนำเช็คเข้า<br><a href=\"#\" onclick=\"javascript:selectAll('cid');\"><u>เลือกทั้งหมด</u></a></td>
			<td>หมายเหตุ</td>";
			if($user_emlevel<=1){
					echo "<td>นำไปยืนยันเก็บรักษาเช็คใหม่</td>";
				}
	echo "</tr>";
	
	if($tab_id=='0'){
		$qr=pg_query("select \"revChqID\", \"bankChqNo\", \"bankChqDate\", \"bankName\", \"bankOutBranch\", \"bankChqToCompID\", \"bankChqAmt\",
		\"revChqStatus\", \"isInsurChq\", \"keepFrom\", \"chqKeeperID\"
		from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
		left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
		WHERE \"revChqStatus\" in('2','8') and \"bankChqDate\" <= '$current_date'
		and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
		and \"bankRevResult\" is null
		order by a.\"bankChqDate\"");
	}else if($tab_id=='1'){
		$qr=pg_query("select \"revChqID\", \"bankChqNo\", \"bankChqDate\", \"bankName\", \"bankOutBranch\", \"bankChqToCompID\", \"bankChqAmt\",
		\"revChqStatus\", \"isInsurChq\", \"keepFrom\", \"chqKeeperID\"
		from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
		left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
		WHERE \"revChqStatus\" ='8' and \"bankChqDate\" <= '$current_date' and \"isInsurChq\"<>1 
		and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
		and \"bankRevResult\" is null
		order by a.\"bankChqDate\"");
	}else if($tab_id=='2'){
		$qr=pg_query("select \"revChqID\", \"bankChqNo\", \"bankChqDate\", \"bankName\", \"bankOutBranch\", \"bankChqToCompID\", \"bankChqAmt\",
		\"revChqStatus\", \"isInsurChq\", \"keepFrom\", \"chqKeeperID\"
		from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
		left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
		WHERE \"revChqStatus\" ='8' and \"bankChqDate\" <= '$current_date' and \"isInsurChq\"=1 
		and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
		and \"bankRevResult\" is null
		order by a.\"bankChqDate\"");
	}else if($tab_id=='3'){
		$qr=pg_query("select \"revChqID\", \"bankChqNo\", \"bankChqDate\", \"bankName\", \"bankOutBranch\", \"bankChqToCompID\", \"bankChqAmt\",
		\"revChqStatus\", \"isInsurChq\", \"keepFrom\", \"chqKeeperID\"
		from finance.\"V_thcap_receive_cheque_keeper_cheManage\" a
		left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
		WHERE \"revChqStatus\" ='2' and \"bankChqDate\" <= '$current_date' 
		and \"revChqID\" not in(select \"revChqID\" from finance.thcap_receive_cheque_return where \"statusChq\"='2')
		and \"bankRevResult\" is null
		order by a.\"bankChqDate\"");
	}
	$row = pg_num_rows($qr);
	if($row!=0)
	{
		$i=0;
		while($res = pg_fetch_array($qr))
		{
			$revChqID = $res["revChqID"];
			$bankChqNo=$res["bankChqNo"];
			$bankChqDate = $res["bankChqDate"]; 
			$bankName = $res["bankName"]; 
			$bankOutBranch = $res["bankOutBranch"]; 
			$bankChqToCompID = $res["bankChqToCompID"]; 
			$bankChqAmt = $res["bankChqAmt"]; 
			$revChqStatus=$res["revChqStatus"];
			$isInsurChq = $res["isInsurChq"];
			$keepFrom = $res["keepFrom"];
			
			//ตรวจสอบว่าเช็คว่าเคยทำรายการยืนยันแล้วหรือยัง?
			$chkkeep=pg_query("select \"revChqID\" from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID' and \"replyByTakerID\" is not null and \"bankRevResult\" <> '4' ");
			$numkeep = pg_num_rows($chkkeep);
			
			//หาเลขที่สัญญา
			$qry_conid=pg_query("select \"revChqToCCID\" from finance.\"thcap_receive_cheque\" a WHERE \"revChqID\" ='$revChqID' ");
			list($contractid) = pg_fetch_array($qry_conid);
			
			//หาชื่อลูกค้า
			$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
			list($cusid,$fullname) = pg_fetch_array($qry_cusname);
			
			if($i%2==0){
				if($isInsurChq==1){
					echo "<tr bgcolor=\"#e5cdf9\" align=center>";
				}else{
					echo "<tr class=\"odd\" align=center>";
				}
				
				if($revChqStatus==2){ //กรณีเป็นเช็คเด้ง
					echo "<tr bgcolor=\"#FFEBCD\" align=center>";
				}
			}else{
				if($isInsurChq==1){
					echo "<tr bgcolor=\"#e5cdf9\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
				if($revChqStatus==2){ //กรณีเป็นเช็คเด้ง
					echo "<tr bgcolor=\"#FFEBCD\" align=center>";
				}
			}
			echo "<td>
					<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\">
					<font color=\"red\"><U>$contractid</U></font></a>
				</td>
				<td align=\"left\">
					<a style=\"cursor:pointer;\" onclick=\"javascipt:popU('../search_cusco/index.php?cusid=$cusid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750')\" title=\"ดูข้อมูลลูกค้า\">					
					(<font color=\"red\"><U>$cusid</U></font>)</a>
					$fullname
				</td>
				<td>$bankChqNo</td>
				<td>$bankChqDate</td>
				<td align=\"left\">$bankName</td>
				<td>$bankOutBranch</td>
				<td>$bankChqToCompID</td>
				<td align=\"right\">".number_format($bankChqAmt,2)."</td>
				<td>";
					//ตรวจสอบว่ารออนุมัติอยู่หรือไม่
					$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revChqID'");
					$numchkapp=pg_num_rows($qrychkapp);
					if($numchkapp>0){
						echo"
							<input type=\"hidden\" id=\"cid$i\" name=\"cid[]\" value=\"$res[chqKeeperID]\" disabled>
							<font color=\"red\">เช็คนี้มีการขออนุมัติ<br>คืนลูกค้าอยู่</font>";
					}else{
						echo "
						<input type=\"checkbox\" id=\"cid$i\" name=\"cid[]\" value=\"$res[chqKeeperID]\" onclick=\"processclick('$i')\">";
					}
				echo "</td><td>";
					if($numchkapp>0){
						echo "<input type=\"hidden\" id=\"result$i\" name=\"result[]\" size=\"25\">";
					}else{
						echo "<input type=\"text\" id=\"result$i\" name=\"result[]\" size=\"25\" disabled=\"true\">";
					}
				echo "</td>";
				if($user_emlevel<=1 and $numkeep==0){
					echo "<td><img src=\"images/refresh.png\" height=\"20\" width=\"20\" style=\"cursor:pointer;\" onclick=\"if(confirm('นำเช็คกลับไปยืนยันการเก็บรักษาเช็คใหม่!')==true){location.href='process_chqtoconfirmkeep.php?revChqID=$revChqID';}\"></td>";
				}
				
				echo "</tr>";
			$i++;
		}
	}
	if($row==0){
		echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
	}
	echo "</table>";
	echo "<div align=\"center\" style=\"padding-top:10px;\"><input type=\"hidden\" id=\"num\" value=\"$row\"><input type=\"button\" value=\"ตกลง\" onclick=\"ok(this.form);\"><input type=\"button\" value=\"  ปิด  \" onclick=\"window.close();\"></div>";

}

?>