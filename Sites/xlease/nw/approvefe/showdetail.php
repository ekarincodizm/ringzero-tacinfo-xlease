<?php
session_start();
include("../../config/config.php");	
$db1="ta_mortgage_datastore";
$db2="ta_mortgage";

$contractID=$_GET["contractID"];

//หาอัตราดอกเบี้ยสูงสุด
$chkrate=mysql_query("select interestrate_default_maximum from $db2.tbcinterestrate_default");
if($resrate=mysql_fetch_array($chkrate)){
	$ratemax=$resrate["interestrate_default_maximum"]; //อัตราดอกเบี้ยสูงสุด
} 

$query = mysql_query("select a.contract_loans_code,a.appv_credit_money,a.appv_interest,a.appv_month,a.contract_loans_minpay,
	a.contract_loans_startdate,a.ActualTransDate,a.contract_loans_paystart,a.contract_loans_damagecloseaccount,b.cusname from $db1.loan_data a
	inner join $db1.vcustomerbycontract b on a.contract_loans_code=b.contract_loans_code 
	where b.cus_group_type_code='01' and a.contract_loans_code='$contractID'"); 
	
	if($result = mysql_fetch_array($query)){
		$contract_loans_code = $result["contract_loans_code"]; //เลขที่สัญญาจำนอง
		$appv_credit_money = $result["appv_credit_money"]; //จำนวนเงินกู้
		$appv_interest = $result["appv_interest"]; //อัตราดอกเบี้ยที่ตกลงตอนแรก
		$appv_month = $result["appv_month"]; //ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
		$cusname = $result["cusname"]; //ชื่อผู้กู้หลัก
		$appv_month2=round($appv_month,2);
		$contract_loans_minpay = $result["contract_loans_minpay"]; //จำนวนเงินผ่อนขั้นต่ำต่อ Due
		$startdate = $result["contract_loans_startdate"]; 
		$y=substr($startdate,0,4);
		if($y>="2400"){
			$y=$y-543;
		}else{
			$y=$y;
		}
		$m=substr($startdate,5,2);
		$d=substr($startdate,8,2);
		$contract_loans_startdate=$y."-".$m."-".$d; //วันที่ทำสัญญา
		
		$ActualTransDate = $result["ActualTransDate"];
		$yy=substr($ActualTransDate,0,4);
		$yy=$yy-543;
		$yy=substr($ActualTransDate,0,4);
		if($yy>="2400"){
			$yy=$yy-543;
		}else{
			$yy=$yy;
		}
		$mm=substr($ActualTransDate,5,2);
		$dd=substr($ActualTransDate,8,2);
		$ActualTransDate=$yy."-".$mm."-".$dd; //วันที่รับเิงินที่ขอกู้
		
		$contract_loans_paystart = $result["contract_loans_paystart"];
		$y2=substr($contract_loans_paystart,0,4);
		if($y2>="2400"){
			$y2=$y2-543;
		}else{
			$y2=$y2;
		}
		$m2=substr($contract_loans_paystart,5,2);
		$d2=substr($contract_loans_paystart,8,2); //วันที่ชำระของทุกๆเดือน
		$contract_loans_paystart=$y2."-".$m2."-".$d2; //Due แรก
		
		$conloansclose = $result["contract_loans_damagecloseaccount"]; if($conloansclose=="") $conloansclose="5.00"; //%ค่าปรับปิดบัญชีก่อนกำหนด
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" rel="stylesheet" href="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
	<h1 class="style4">+ รายละเอียดสัญญา +</h1>
</div>
<table width="60%" border="0" cellpadding="1" cellspacing="1" align="center">
<tr>	
	<td valign="top">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="200">เลขที่สัญญาจำนอง : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $contract_loans_code;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="200">ผู้กู้หลัก : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $cusname;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" width="200" valign="top">ผู้กู้ร่วม : </td>
			<td bgcolor="#FFFFFF" valign="top">
			<?php 
			$cusco=mysql_query("select * from $db1.vcustomerbycontract where cus_group_type_code<>'01' and contract_loans_code='$contractID'");
			$numco=mysql_num_rows($cusco);
			$cusname2="";
			$p=0;
			while($rescus=mysql_fetch_array($cusco)){
				$coname=$rescus["cusname"];
				if($numco==1){
					$cusname2=$coname;
				}else{
						$cusname2=$coname."<br>".$cusname2;
				}
				$p++;
			}
			if($numco==0){
				echo "ไม่พบผู้กู้ร่วม";
			}else{
				echo $cusname2;
			}
			?>
			</td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">จำนวนเงินกู้ (บาท) : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $appv_credit_money;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">อัตราดอกเบี้ยที่ตกลงตอนแรก (%) : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $appv_interest;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">อัตราดอกเบี้ยสูงสุด (%) : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $ratemax;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right">ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน) : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $appv_month2;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">จำนวนเงินผ่อนขั้นต่ำต่อ Due (บาท) : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $contract_loans_minpay;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">วันที่ทำสัญญา : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $contract_loans_startdate;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">วันที่รับเงินที่ขอกู้ : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $ActualTransDate;?></td>
		</tr>
		<tr height="30" bgcolor="#E8E8E8">
			<td align="right" valign="top">Due แรก : </td>
			<td bgcolor="#FFFFFF">&nbsp;<?php echo $contract_loans_paystart;?></td>
		</tr>
	</table>
	</td>
</tr>
<tr><td align="center" height="50" colspan="2"><input type="button" value="ปิดหน้านี้" onclick="window.close();"></td></tr>
</table>
</body>
</html>
