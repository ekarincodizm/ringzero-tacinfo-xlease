<?php
include("../../../config/config.php");
$recnum = $_GET['recnum'];
?>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php
if($recnum != ""){
	//ตรวจสอบว่ามีใบรับเช็คนี้จริงหรือไม่
	$qrychk=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqNum\"='$recnum'");
	if(pg_num_rows($qrychk)==0){
		echo "<div align=\"center\"><h2>ใบรับเช็ครายการนี้ไม่มีจริง กรุณาตรวจสอบ</h2></div>";
		exit;
	}
	echo "<table width=\"800\" cellSpacing=\"1\" cellPadding=\"1\" border=\"0\" bgcolor=\"#D1EEEE\" align=\"center\">
	<tr bgcolor=#FFF><td colspan=2><b>ใบรับเช็คเลขที่ <font color=red>$recnum</font></b></td><td colspan=\"4\" align=\"right\"><input type=\"button\" id=\"btnprint\" value=\"พิมพ์\" onclick=\"javascript:popU('frm_result.php?recnum=$recnum','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350')\" style=\"cursor: pointer;\"></td></tr>
	<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#B4CDCD\">
		<th>เลขที่ใบรับเช็ค</th>
		<th>เลขที่สัญญา</th>
		<th>เลขที่เช็ค</th>
		<th>วันที่สั่งจ่าย</th>
		<th>วันที่บนเช็ค</th>
		<th>จำนวนเงิน</th>
	</tr>
	";
	$sumamt=0;	
	$i=0;
	$qrydata=pg_query("select *,date(\"bankChqDate\") as \"bankChqDate\",date(\"revChqDate\") as \"revChqDate\" from finance.\"V_thcap_receive_cheque_chqManage\" where \"revChqNum\"='$recnum' order by \"revChqNum\"");
	$numrow=pg_num_rows($qrydata);
	while($resdata=pg_fetch_array($qrydata)){
		$revChqNum=$resdata["revChqNum"];
		$revChqToCCID=$resdata["revChqToCCID"];
		$bankChqNo=$resdata["bankChqNo"];
		$bankChqDate=$resdata["bankChqDate"];
		$revChqDate=$resdata["revChqDate"];
		$bankChqAmt=number_format($resdata["bankChqAmt"],2);
		$sumamt+=$resdata["bankChqAmt"];
		
		if($i%2==0){
			echo "<tr align=center bgcolor=\"#D1EEEE\" align=\"center\">";
		}else{
			echo "<tr align=center bgcolor=\"#E0FFFF\" align=\"center\">";
		}
		
		echo "
		<td>$revChqNum</td>
		<td><span onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$revChqToCCID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$revChqToCCID</u></font></span></td>
		<td>$bankChqNo</td>
		<td>$bankChqDate</td>
		<td>$revChqDate</td>
		<td align=right>$bankChqAmt</td>
		";
			
		echo "</tr>";
		$i++;
	}

	if($numrow==0){
		echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=\"6\"><h2>-ไม่พบข้อมูล-</h2></td></tr>";
	}else{
		echo "<tr align=center height=30 bgcolor=\"#B4CDCD\"><td colspan=\"5\"><b>รวม</b></td><td align=right><b>".number_format($sumamt,2)."</b></td></tr>";		
	}
	echo "</table>";
}
?>
		
		
		
	
