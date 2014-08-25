<?php
session_start();

include("../../config/config.php");

$contractID = $_GET["idno"];
$vfocusdate = nowDate();

$sql_head=pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
$rowhead=pg_num_rows($sql_head);
$i = 1;
while($result=pg_fetch_array($sql_head))
{
	$conLoanIniRate = $result["conLoanIniRate"];
	$conLoanMaxRate = $result["conLoanMaxRate"];
	$conDate = $result["conDate"];
	$conStartDate = $result["conStartDate"];
	$conRepeatDueDay = $result["conRepeatDueDay"];
	$conLoanAmt = $result["conLoanAmt"];
	$conTerm = $result["conTerm"];
	$conMinPay = $result["conMinPay"];
}

$db1="ta_mortgage_datastore";

//ค้นหาที่อยู่จาก mysql
// $qry_add=mysql_query("select * from $db1.vaddrbycontract
// where contract_loans_code='$contractID'");
// if($resadd=mysql_fetch_array($qry_add)){
	// $address=trim($resadd["address"]);
// }

$qry_add=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID'");
if($resadd=pg_fetch_array($qry_add)){
	$address=trim($resadd["thcap_address"]);
}

//ค้นหาชื่อผู้กู้ร่วมจาก mysql
// $qry_name=mysql_query("select * from $db1.vcustomerbycontract
// where contract_loans_code='$contractID' and cus_group_type_code<>'01'");
// $numco=mysql_num_rows($qry_name);
// $i=1;
// $nameco="";
// while($resco=mysql_fetch_array($qry_name)){
	// $name2=trim($resco["cusname"]);
	// if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		// $nameco=$name2;
	// }else{ 
		// if($i==$numco){
			// $nameco=$nameco.$name2;
		// }else{
			// $nameco=$nameco.$name2.", ";
		// }
	// }
	// $i++;
// }

$qry_name=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\" ='$contractID' and \"CusState\" > 0 ");
$numco=pg_num_rows($qry_name);
$i=1;
$nameco="";
while($resco=pg_fetch_array($qry_name)){
	$name2=trim($resco["thcap_fullname"]);
	if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
		$nameco=$name2;
	}else{ 
		if($i==$numco){
			$nameco=$nameco.$name2;
		}else{
			$nameco=$nameco.$name2.", ";
		}
	}
	$i++;
}


//ค้นหาชื่อผู้กู้หลักจาก mysql
// $qry_namemain=mysql_query("select * from $db1.vcustomerbycontract
// where contract_loans_code='$contractID' and cus_group_type_code='01'");
// if($resnamemain=mysql_fetch_array($qry_namemain)){
	// $name3=trim($resnamemain["cusname"]);
// }

$qry_namemain=pg_query("select * from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID' and \"CusState\" = '0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}

// หาค่าจาก function ใน postgres
	$backAmt = pg_query("select \"thcap_backAmt\"('$contractID','$vfocusdate')");
	$backAmt = pg_fetch_result($backAmt,0);
	
	$backDueDate = pg_query("select \"thcap_backDueDate\"('$contractID','$vfocusdate')");
	$backDueDate = pg_fetch_result($backDueDate,0);
	
	$nextDueAmt = pg_query("select \"thcap_nextDueAmt\"('$contractID','$vfocusdate')");
	$nextDueAmt = pg_fetch_result($nextDueAmt,0);
	
	$nextDueDate = pg_query("select \"thcap_nextDueDate\"('$contractID','$vfocusdate')");
	$nextDueDate = pg_fetch_result($nextDueDate,0);
// จบการหาค่าจาก function ใน postgres
?>

<center>
<table>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>เลขที่สัญญา</b></td><td><?php echo $contractID; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>ชื่อผู้กู้หลัก</b></td><td><?php echo $name3; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>ผู้กู้ร่วม</b></td><td><?php echo $nameco; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>ที่อยู่ที่ติดต่อ</b></td><td><?php echo $address; ?></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>INT.ปกติ (ดอกเบี้ยเริ่มแรก)</b></td><td><?php echo $conLoanIniRate." %"; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>วันที่ทำสัญญา</b></td><td><?php echo $conDate; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>จ่ายทุกวันที่</b></td><td><?php echo $conRepeatDueDay; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>จำนวนเดือนผ่อนชำระคืน</b></td><td><?php echo $conTerm; ?> เดือน</td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>INT.ผิดนัด (ดอกเบี้ยสูงสุด)</b></td><td><?php echo $conLoanMaxRate." %"; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>วันที่เริ่มกู้</b></td><td><?php echo $conStartDate; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>ยอดกู้</b></td><td><?php echo number_format($conLoanAmt,2); ?> บาท</td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>ยอดจ่ายขั้นต่ำ/เดือน</b></td><td><?php echo number_format($conMinPay,2); ?> บาท</td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>ยอดค้างชำระปัจจุบัน</b></td><td><?php echo $backAmt; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>วันที่เริ่มค้างชำระ</b></td><td><?php echo $backDueDate; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>ยอดที่จะครบกำหนดในวันที่</b></td><td><?php echo $nextDueDate; ?></td>
		<td width=3></td>
		<td align="right" bgcolor="#79BCFF"><b>จำนวน</b></td><td><?php echo number_format($nextDueAmt,2); ?> บาท</td>
	</tr>
</table>
</center>