<?php
include("../../config/config.php");
$id = pg_escape_string($_GET['id']);
$condition=pg_escape_string($_GET['condition']);
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php
if($id != ""){
	echo "<table width=\"1050\" cellSpacing=\"1\" cellPadding=\"3\" border=\"0\" bgcolor=\"#D7F0FD\" align=\"center\" class=\"sort-table\">
	<thead>
	<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#BCE6FC\">
		<th>สัญญาเลขที่</th>
		<th id=\"a5\" class=\"sort-text\" >ลูกค้า</th>
		<th id=\"a1\" class=\"sort-text\" style=\"cursor:pointer;background-color:#ff6600;\">เลขที่ใบเสร็จ</th>
		<th>พิมพ์ใบเสร็จเป็นชุด</th>
		<th>พิมพ์เฉพาะต้นฉบับ</th>
		<th>พิมพ์เฉพาะสำเนา</th>
	</tr>
	</thead>
	";
	if($condition=="1"){
		//ค้นหาจากเลขที่สัญญา		
		$qry_con=pg_query("SELECT a.\"receiptID\",a.\"CusFullName\",a.\"contractID\"
							FROM \"blo_receipt\"  a
							where a.\"contractID\"='$id' ");
		

		
		$numcon=pg_num_rows($qry_con);
		
		if($numcon>0){ //แสดงว่ามีข้อมูล
			$status=1;
		}else{
			$status=0;
		}
	}else if($condition=="2"){
		//ค้นหาจากเลขที่ใบเสร็จ
		$qry_con=pg_query("SELECT a.\"receiptID\",a.\"CusFullName\",a.\"contractID\"
							FROM \"blo_receipt\"  a
							 where a.\"receiptID\"='$id' ");
		$numrec = pg_num_rows($qry_con);
		if($numrec>0){ //แสดงว่ามีข้อมูล
			$status=1;
		}else{
			$status=0;
		}
	}
	
	if($status==1){
		while($result=pg_fetch_array($qry_con)){;
			
			$contractID=trim($result["contractID"]);
			$receiptID=trim($result["receiptID"]);
			$CusFullName=trim($result["CusFullName"]);
						
			echo "<tr align=center bgcolor=\"#EAF9FF\">
			<td>$contractID</td>
			<td>$CusFullName</td>
			<td>$receiptID</td>";			
			echo "<td><a href=\"#\"                     onclick=\"javascript:popU('reprint_reason.php?receiptid=$receiptID&t=0','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
			
			echo "<td bgcolor=\"#FFC1C1\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?receiptid=$receiptID&t=1','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";
			
			echo "<td bgcolor=\"#BCEE68\"><a href=\"#\" onclick=\"javascript:popU('reprint_reason.php?receiptid=$receiptID&t=2','reason_$receiptID','');\" ><img src=\"images/icoPrint.png\" width=\"17\" height=\"14\"></a></td>";			
			echo "</tr>";
		}			
		
	}else{
		echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=\"8\"><h2>-ไม่พบข้อมูลใบเสร็จ-</h2></td></tr>";
	}
	echo "</table>";
}else{ //กรณีไม่กรอกคำค้น
	echo "<center><h2>-กรุณากรอกคำค้นหาก่อนทำรายการ-</h2></center>";
}?>
