<?php
include("../../config/config.php");

$condition=$_GET['condition']; //เงื่อนไขในการแสดงข้อมูล
$conselect = $_GET['conselect']; 

if($condition==""){ //แสดงทั้งหมด
	$conchk="AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";
}else if($condition=="1"){
	$conchk="AND \"tacTempDate\"='$conselect' AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";
}else if($condition=="2"){
	$conchk="AND date(\"makerStamp\")='$conselect' AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";
}else if($condition=="3"){
	list($month,$year)=explode(",",$conselect);
	$conchk="AND EXTRACT(MONTH FROM \"tacTempDate\")='$month' and EXTRACT(YEAR FROM \"tacTempDate\")='$year' AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";
}else if($condition=="4"){
	list($month,$year)=explode(",",$conselect);
	$conchk="AND EXTRACT(MONTH FROM \"makerStamp\")='$month' and EXTRACT(YEAR FROM \"makerStamp\")='$year' AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";;
}else if($condition=="5"){
	$conchk="AND \"tacXlsRecID\"='$conselect' AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";
}else if($condition=="6"){
	$conchk="AND \"tacID\"='$conselect' AND \"tacXlsRecID\" NOT IN (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))";
}
?>

<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php
echo "<table width=\"85%\" cellSpacing=\"1\" cellPadding=\"1\" border=\"0\" bgcolor=\"#E0EEE0\" align=\"center\">
<tr align=\"center\" style=\"font-weight:bold;\" bgcolor=\"#C1CDC1\">
	<th>เลขที่สัญญา</th>
	<th>เลขที่ใบเสร็จ</th>
	<th>ทะเบียนรถยนต์</th>
	<th>จำนวนเงินรวม</th>
	<th>วันที่ชำระ</th>
	<th>ผู้ทำรายการ</th>
	<th>วันเวลาที่ทำรายการ</th>
	<th>แก้ไข</th>
</tr>
";

$qry_con=pg_query("SELECT \"tacID\", \"tacXlsRecID\", sum(\"tacMoney\") as summoney,  
\"tacTempDate\", b.\"fullname\", date(\"makerStamp\") as stampdate,\"makerID\",c.carregis
FROM \"tacReceiveTemp\" a
LEFT JOIN \"Vfuser\" b on a.\"makerID\"=b.\"id_user\" 
LEFT JOIN \"Taxiacc\" c on a.\"tacID\"=c.\"CusID\"
WHERE 1=1  $conchk
GROUP BY \"tacID\", \"tacXlsRecID\", \"tacTempDate\",b.\"fullname\", date(\"makerStamp\"),\"makerID\",c.carregis
ORDER BY \"tacTempDate\" DESC");

$numcon=pg_num_rows($qry_con);
$i=0;
while($res=pg_fetch_array($qry_con)){
	$tacID=$res["tacID"];
	$tacXlsRecID=$res["tacXlsRecID"];
	$summoney=$res["summoney"];
	$tacTempDate=$res["tacTempDate"];
	$fullname=$res["fullname"];
	$stampdate=$res["stampdate"];
	$makerID=$res["makerID"];
	$carregis=$res["carregis"];
	
	$i=$i+1;
	if($i%2==0){
		echo "<tr align=center bgcolor=\"#F0FFF0\">";
	}else{
		echo "<tr align=center bgcolor=\"#F5FFFA\">";
	}
	
	echo "
		<td><span onclick=\"javascript:popU('frm_PaymentChk.php?car=$tacID','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" title=\"รายละเอียดรับชำระแทน 1681\" style=\"cursor:pointer\"><u>$tacID</u></span></td>
		<td>$tacXlsRecID</td>
		<td>$carregis</td>
		<td align=\"right\">".number_format($summoney,2)."</td>
		<td>$tacTempDate</td>
		<td align=left>$fullname</td>
		<td>$stampdate</td>
		<td><img src=\"images/edit.png\" width=\"16\" height=\"16\" style=\"cursor:pointer\" onclick=\"javascript:popU('frm_EditReceiveDlt.php?tacXlsRecID=$tacXlsRecID&tacID=$tacID&makerID=$makerID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\"></td>
		</tr>
	";
}			
		
if($numcon==0){
	echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=\"8\"><h2>-ไม่พบข้อมูล-</h2></td></tr>";
}
echo "</table>";
?>
